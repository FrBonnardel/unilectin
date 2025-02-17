function create_svg(region_id){
  region_id = region_id.toString();
  var display_begin = document.getElementById("amount_lower_"+region_id).value*1;
  var display_end = document.getElementById("amount_upper_"+region_id).value*1;
  if((display_end - display_begin) <100){
    exit();
  }

  var services_color = document.getElementById("services_color_"+region_id).value;
  services_color = JSON.parse(services_color);
  var services_annot = document.getElementById("services_annot_"+region_id).value;
  services_annot = JSON.parse(services_annot);
  var svg_div = document.getElementById("svg_div_"+region_id);
  svg_div.innerHTML = "";
  var region_info = document.getElementById("row_"+region_id).value;
  region_info = JSON.parse(region_info);

  //var region_id          = parseInt(region_info['protein_id']);
  var region_size        = parseInt(region_info['length'])*3;
  var prot_seq = region_info['sequence'];
  var region_name = region_info['name'];
  var region_begin       = parseInt(0)*1+1;
  var region_end         = parseInt(region_size);

  var svg_default_width = $("#content").width();

  var transcript_displayed_size = display_end - display_begin +1;
  var zoom_ratio = svg_default_width / transcript_displayed_size;
  var offset_x = 0;
  var pos_y = 0;
  var height_rect=16;
  var height_space=2;
  var region_color="(255, 165 ,79)";
  var transcript_color="(51, 0, 102)";
  var svgtext = "\n<svg id='svg_"+region_id+"' width='"+svg_default_width+"' height='100%' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' >";


  //PROTEIN RECTANGLE
  var region_displayed_begin = 0;
  var region_displayed_size = 0;
  region_displayed_size = Math.min(region_end,display_end) - Math.max(region_begin,display_begin) + 1 ;
  if(region_begin > display_begin){
    region_displayed_begin = region_begin-display_begin;
  }
  pos_y = pos_y + height_rect;

  //SCALE
  var step = 1000;
  if(transcript_displayed_size < 50000){
    step = 500;
  }
  if(transcript_displayed_size < 10000){
    step = 200;
  }
  if(transcript_displayed_size < 2000){
    step = 100;
  }
  if(transcript_displayed_size < 500){
    step = 10;
  }
  for(var scale=0; scale<display_end; scale+=step){
    svgtext+="\n<text  x='"+(offset_x+(zoom_ratio*(scale*3))-2)+"' ";
    svgtext+="y='"+pos_y+"' ";
    svgtext+="font-family='Verdana' font-size='10'  ";
    svgtext+="style='fill:rgb(0,0,0)' >";
    svgtext+="|"+new Intl.NumberFormat("en-EN").format(Math.round(display_begin/3)+scale+1)+"";
    svgtext+="</text>";
  }
  pos_y = pos_y + 2;

  //Display seq
  if(false & transcript_displayed_size > 50 && transcript_displayed_size < 300){
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
  if(prot_seq != "" && transcript_displayed_size < 120){
    var prot_seq_displayed_begin = 0;
    if(region_begin > display_begin){
      region_displayed_begin = region_begin-display_begin;
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
      var name = annotation['name'];
      var description = ""+annotation['description'];
      //name = preg_replace("/\r|\n/", '', name);
      var begin = annotation['begin']*3-2;
      var end = annotation['end']*3+1;
      var length_annot = (annotation['end'] - annotation['begin'] + 1)*3;
      if((begin <= display_begin && end >= display_begin) || (begin >= display_begin && end <= display_end) || (begin <= display_end && end >= display_end)){
        if((service != service_tmp) || (begin <= begin_tmp && end > begin_tmp) || (begin >= begin_tmp && end <= end_tmp) || (begin < end_tmp && end >= end_tmp)){
          pos_y = pos_y + height_rect + height_space;
        }
        var color = "#3399ff";
        if(service in services_color){
          color = services_color[service];
        }
        service_tmp=service;
        begin_tmp=begin;
        end_tmp=end;
        var annot_displayed_size = zoom_ratio * (Math.min(end,display_end) - Math.max(begin,display_begin)) ;
        var annot_displayed_begin = 0;
        if(begin > display_begin){
          annot_displayed_begin = begin-display_begin;
        }
        annot_displayed_begin = annot_displayed_begin * zoom_ratio;
        if(annot_displayed_size < 4){
          annot_displayed_size = 4;
        }
        //HYPERLINK
        if(name.substring(0,2)=="PF"){
          svgtext+="\n<a target='_blank' href='http://pfam.xfam.org/family/"+name.substring(0,7)+"'>";
        }
        //svgtext+="\n<a target='_blank' href='https://www.google.fr/search?q="+name+" "+service+"'>";
        //RECTANGLE OF THE ANNOTATION
        svgtext+="\n<rect x='"+annot_displayed_begin+"' y='"+pos_y+"' rx='5' ry='5' width='"+annot_displayed_size+"'";
        svgtext+=" height='"+height_rect+"' fill='"+color+"' fill-opacity='0.8'  style='stroke: black;'>";
        svgtext+="<title>["+annotation['begin']+","+annotation['end']+"] "+name+" "+service+" "+description+"</title> ";
        svgtext+="</rect>";
        //TEXT OF THE ANNOTATION
        var nametmp = name;
        var namecut = nametmp.substr(0,annot_displayed_size/12);
        var left_margin = (annot_displayed_size - namecut.length*8)/2;
        if(annot_displayed_size < 30){
          namecut = "";
          left_margin = 2;
        }
        svgtext+="\n<text   x='"+(annot_displayed_begin+left_margin)+"' y='"+(pos_y+height_rect-4)+"' ";
        svgtext+="font-family='Verdana' font-size='12'  style='fill:rgb(0,0,0);'>";
        svgtext+=namecut;
        svgtext+="<title>["+annotation['begin']+","+annotation['end']+"] "+name+" "+service+" "+description+"</title> ";
        svgtext+="</text>";
        if(name.substring(0,2)=="PF"){
          svgtext+="</a>";
        }
      }
    }
  }

  svgtext += "\n</svg>";
  pos_y = pos_y + height_rect + height_space;
  svg_div_text = "<div id='svg_div_content_"+region_id+"' style='margin:1px;padding:0px;overflow:hidden;width:"+svg_default_width+"px; height:"+pos_y+"px; '>";
  svg_div.innerHTML += svg_div_text+svgtext+"</div>";
}

function create_slider(region_id){
  region_id = region_id.toString();
  var region_info = document.getElementById("row_"+region_id).value;
  region_info = JSON.parse(region_info);
  // SLIDER SECTION
  var svg_default_width=836;
  var path_includes="../includes/";
  var protein_id=region_info['protein_id'];
  var transcript_size=region_info['length']*3;
  var min=1;
  var div = $("#slider_div_"+region_id+"");
  var sliders = "";
  sliders+=("	<input name='amount_lower_"+region_id+"' id='amount_lower_"+region_id+"' style='display:none;width: 10%;height:20px;text-align:center;border-radius:0;' readonly value="+min+" onchange=\"refresh_svg('"+region_id+"');\">");
  sliders+=("	<input name='amount_upper_"+region_id+"' id='amount_upper_"+region_id+"' style='display:none;width: 10%;height:20px;text-align:center;border-radius:0;' readonly value="+transcript_size+" onchange=\"refresh_svg('"+region_id+"');\">");
  sliders+=("<div id='dual_range_"+region_id+"'' style='margin:5px;margin-left:20px;margin-right:20px;'></div>");
  div.append(sliders);
  console.log("dual_range_"+region_id);
  var skipSlider = document.getElementById('dual_range_'+region_id);
  noUiSlider.create(skipSlider, {
    start: [ min, transcript_size ],
    connect: [false, true, false],
    range: {
      'min': [  min ],
      'max': [ transcript_size ]
    }
  });
  var skipValues = [
    $('#amount_lower_'+region_id),
    $('#amount_upper_'+region_id)
  ];
  skipSlider.noUiSlider.on('update', function( values, handle ) {
    skipValues[handle].val(values[handle]);
    skipValues[handle].change();
  });
}

function refresh_svg(region_id){
  create_svg(region_id);
}

function isEventSupported(eventName) {
  var el = document.createElement('div');
  eventName = 'on' + eventName;
  var isSupported = (eventName in el);
  if (!isSupported) {
    el.setAttribute(eventName, 'return;');
    isSupported = typeof el[eventName] == 'function';
  }
  el = null;
  return isSupported;
}

function preventDefault(e) {
  e = e || window.event;
  if (e.preventDefault)
    e.preventDefault();
  e.returnValue = false;  
}

function wheel_svg_zoom_old(region_id,size){
  if(size<120){
    return false;
  }
  var wheelEvent = isEventSupported('mousewheel') ? 'mousewheel' : 'wheel';
  $('#svg_div_content_'+region_id).on(wheelEvent, function(e) {
    preventDefault(e);
    var oEvent = e.originalEvent,
        delta = oEvent.deltaY || oEvent.wheelDelta;
    var range_lower = document.getElementById('amount_lower_'+region_id);
    var range_upper = document.getElementById('amount_upper_'+region_id);
    var value_lower = range_lower.value;
    var value_upper = range_upper.value;
    if (delta < 0) {
      range_lower.value = value_lower*1+(0.1*(value_upper-value_lower));
      range_upper.value = value_upper*1-(0.1*(value_upper-value_lower));
    } else {
      range_lower.value = value_lower*1-(0.1*(value_upper-value_lower));
      range_upper.value = value_upper*1+(0.1*(value_upper-value_lower));
    }
    refresh_svg(region_id);
  });
}

