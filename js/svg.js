

function create_svg(region_id){
    var services_color = document.getElementById("services_color_"+region_id).value;
    services_color = JSON.parse(services_color);
    var services_annot = document.getElementById("services_annot_"+region_id).value;
    services_annot = JSON.parse(services_annot);
    var svg_div = document.getElementById("svg_div_"+region_id);
    svg_div.innerHTML = "";

    var transcript_seq = document.getElementById("transcript_seq_"+region_id).value;
    var prot_seq = document.getElementById("prot_seq_"+region_id).value;
    var region_info = document.getElementById("row_"+region_id).value;
    region_info = JSON.parse(region_info);

    var region_id          = parseInt(region_info['region_id']);
    var temp_region_id     = parseInt(region_info['temp_region_id']);
    var region_size        = parseInt(region_info['region_size']);
    var dataset_id         = parseInt(region_info['dataset_id']);
    var region_begin       = parseInt(region_info['region_begin'])*1+1;
    var region_end         = parseInt(region_info['region_end'])*1+1;
    var dataset_name       = region_info['dataset_name'];
    var transcript_name    = region_info['transcript_name'];
    var transcript_desc    = region_info['transcript_desc'];
    var transcript_size    = parseInt(region_info['transcript_size']);
    var region_strand      = region_info['region_strand'];


    var display_begin = document.getElementById("range_lower_"+region_id).value*1;
    var display_end = document.getElementById("range_upper_"+region_id).value*1;
    var svg_default_width = $("#content").width();

    var transcript_displayed_size = display_end - display_begin +1;
    var zoom_ratio = svg_default_width / transcript_displayed_size;
    var offset_x = 0;
    var pos_y = 0;
    var height_rect=8;
    var height_space=2;
    var region_color="(255, 165 ,79)";
    var transcript_color="(51, 0, 102)";
    var svgtext = "\n<svg id='svg_"+region_id+"' width='"+svg_default_width+"' height='100%' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' >";


    //transcript RECTANGLE
    svgtext += "\n<rect x='"+offset_x+"' y='"+pos_y+"'  width='"+zoom_ratio*transcript_displayed_size+"' height='"+height_rect+"' style='stroke: black;' fill='DeepSkyBlue' fill-opacity='0.8'>";
    svgtext += "<title>transcript 1-"+transcript_size+"</title>";
    svgtext += "</rect>";
    var region_displayed_begin = 0;
    var region_displayed_size = 0;
    if((region_begin <= display_begin && region_end >= display_begin) || (region_begin >= display_begin && region_end <= display_end) || (region_begin <= display_end && region_end >= display_end)){
	region_displayed_size = Math.min(region_end,display_end) - Math.max(region_begin,display_begin) + 1 ;
	if(region_begin > display_begin){
	    region_displayed_begin = region_begin-display_begin;
	}
	svgtext += "\n<rect x='"+(region_displayed_begin*zoom_ratio+offset_x)+"' y='"+pos_y+"' width='"+zoom_ratio*region_displayed_size+"' height='"+height_rect+"' style='stroke: black;' fill='orange' fill-opacity='0.8'>";
	svgtext += "<title>region "+(region_begin)+"-"+(region_end)+"</title>";
	svgtext += "</rect>";
    }
    pos_y = pos_y + height_rect + height_space;
    pos_y = pos_y + height_rect;

    //SCALE
    var step = 1000;
    if(transcript_displayed_size < 2000){
	step = 100;
    }
    if(transcript_displayed_size < 200){
	step = 10;
    }
    for(var scale=0; scale<display_end; scale+=step){
	svgtext+="\n<text  x='"+(offset_x+(zoom_ratio*(scale))-2)+"' ";
	svgtext+="y='"+pos_y+"' ";
	svgtext+="font-family='Verdana' font-size='10'  ";
	svgtext+="style='fill:rgb(0,0,0)' >";
	svgtext+="|"+new Intl.NumberFormat("en-EN").format(display_begin+scale)+"";
	svgtext+="</text>";
    }
    pos_y = pos_y + 2;

    //Display seq
    if(transcript_displayed_size > 50 && transcript_displayed_size < 300){
	pos_y = pos_y + ((11/8)*zoom_ratio);
	var seq = transcript_seq.substr(display_begin-1,transcript_displayed_size);
	var seq_tmp = seq.replace(/[TGC]/g,' ');
	svgtext+="\n<text xml:space='preserve' style='font-family: Consolas;fill:blue;font-size:"+((11/6.0475)*zoom_ratio)+"px;' x='"+offset_x+"' y='"+pos_y+"'>"+seq_tmp+"</text>";
	seq_tmp = seq.replace(/[AGC]/g,' ');
	svgtext+="\n<text xml:space='preserve' style='font-family: Consolas;fill:orange;font-size:"+((11/6.0475)*zoom_ratio)+"px;' x='"+offset_x+"' y='"+pos_y+"'>"+seq_tmp+"</text>";
	seq_tmp = seq.replace(/[ATC]/g,' ');
	svgtext+="\n<text xml:space='preserve' style='font-family: Consolas;fill:green;font-size:"+((11/6.0475)*zoom_ratio)+"px;' x='"+offset_x+"' y='"+pos_y+"'>"+seq_tmp+"</text>";
	seq_tmp = seq.replace(/[ATG]/g,' ');
	svgtext+="\n<text xml:space='preserve' style='font-family: Consolas;fill:red;font-size:"+((11/6.0475)*zoom_ratio)+"px;' x='"+offset_x+"' y='"+pos_y+"'>"+seq_tmp+"</text>";
	pos_y = pos_y + 4;
    }

    //Display prot_seq
    if(prot_seq != "" && transcript_displayed_size > 50 && transcript_displayed_size < 300 && region_displayed_size > 0){
	prot_seq = prot_seq+"*";
	if(region_strand == "-"){
	    prot_seq = prot_seq.split("").reverse().join("");
	}
	if(region_begin > display_begin){
	    region_displayed_begin = region_begin-display_begin;
	    prot_seq_displayed_begin = 0;
	}else{
	    region_displayed_begin = 0;
	    prot_seq_displayed_begin = display_begin-region_begin;
	}
	prot_seq = prot_seq.replace(/(.)/g, "[$1]")
	pos_y = pos_y + ((11/8)*zoom_ratio);
	svgtext+="\n<text style='font-family: Consolas;font-size:"+((11/6.0475)*zoom_ratio)+"px;' x='"+(region_displayed_begin*zoom_ratio+offset_x)+"' y='"+pos_y+"'>"+prot_seq.substr(prot_seq_displayed_begin,region_displayed_size)+"</text>";
	pos_y = pos_y + 4;
    }

    //ANNOTATIONS
    //!empty(services)
    if(true){
	var begin_tmp=1;
	var end_tmp=region_size;
	var service_tmp="";
	pos_y = pos_y - height_rect;
	//!empty(_POST['annot_label'])
	for (var annotationkey in services_annot){
	    var annotation = services_annot[annotationkey];
	    //in_array(annotation['service'],_POST['services'])
	    var service = annotation['service'];
	    if(document.getElementById(service+'_'+region_id).classList.contains("active")){
		var name = annotation['name'];
		var description = annotation['description'];
		//name = preg_replace("/\r|\n/", '', name);
		var begin = annotation['begin']*1 + region_begin;
		var end = annotation['end']*1 + region_begin;
		var length_annot = end - begin + 1;
		if((begin <= display_begin && end >= display_begin) || (begin >= display_begin && end <= display_end) || (begin <= display_end && end >= display_end)){
		    if((service != service_tmp) || (begin <= begin_tmp && end >= begin_tmp) || (begin >= begin_tmp && end <= end_tmp) || (begin <= end_tmp && end >= end_tmp)){
			pos_y = pos_y + height_rect + height_space;
		    }
		    var color = services_color[service];
		    service_tmp=service;
		    begin_tmp=begin;
		    end_tmp=end;
		    var annot_displayed_size = zoom_ratio * (Math.min(end,display_end) - Math.max(begin,display_begin) + 1) ;
		    var annot_displayed_begin = 0;
		    if(begin > display_begin){
			annot_displayed_begin = begin-display_begin;
		    }
		    annot_displayed_begin = annot_displayed_begin * zoom_ratio;
		    if(annot_displayed_size < 4){
			annot_displayed_size = 4;
		    }
		    //HYPERLINK
		    svgtext+="\n<a target='_blank' href='https://www.google.fr/search?q="+name+" "+service+"'>";
		    //RECTANGLE OF THE ANNOTATION
		    svgtext+="\n<rect x='"+annot_displayed_begin+"' y='"+pos_y+"' width='"+annot_displayed_size+"'";
		    svgtext+=" height='"+height_rect+"' fill='"+color+"' fill-opacity='0.8'>";
		    svgtext+="<title>["+begin+","+end+"] "+name+" "+service+" "+description+"</title> ";
		    svgtext+="</rect>";
		    //TEXT OF THE ANNOTATION
		    var nametmp = name+" "+service;
		    var namecut = nametmp.substr(0,annot_displayed_size/5);
		    var left_margin = 2;
		    if((annot_displayed_size/2 - namecut.length*3) > namecut.length){
			left_margin = annot_displayed_size/2 - name.length*3;
		    }
		    if(annot_displayed_size < 15){
			namecut = "";
		    }
		    svgtext+="\n<text   x='"+(annot_displayed_begin+left_margin)+"' y='"+(pos_y+height_rect-1)+"' ";
		    svgtext+="font-family='Verdana' font-size='"+height_rect+"'  style='fill:rgb(0,0,0);'>";
		    svgtext+=namecut;
		    svgtext+="<title>["+begin+","+end+"] "+name+" "+service+" "+description+"</title> ";
		    svgtext+="</text>";
		    svgtext+="</a>";
		}
	    }
	}
    }

    svgtext += "\n</svg>";
    pos_y = pos_y + height_rect + height_space;
    svg_div_text = "<div id='svg_div_content_"+region_id+"' style='border-top:1px solid black;border-bottom:1px solid black;margin:1px;padding:0px;overflow:hidden;width:"+svg_default_width+"px; height:"+pos_y+"px; '>";
    svg_div.innerHTML += svg_div_text+svgtext+"</div>";
}