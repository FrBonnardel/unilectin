function load_glycoct(div,div_pic,iupac){
    $.ajax({
        url:"https://glyconnect.expasy.org/api/structures/translate/iupac/glycoct",
        type:"POST",
        data:JSON.stringify({"iupac":iupac,"glycanType":""}),
        dataType: 'json',
        contentType:"application/json",
        complete: function(data) {
            if(data.responseText.indexOf("problem") >= 0) {
                return;
            }
            $('#'+div).html(data.responseText);
            load_glycoct_svg(div_pic,div);
            load_glycoRDF(data.responseText);
            $('#container_'+div).css('display','inline-block');
        }
    });
}
function load_glycoct_svg(div,div_glycoct){
    var element = document.getElementById(div_glycoct);
    var glycoct = element.innerHTML;
    $.ajax({
        url:"https://glyconnect.expasy.org/api/structures/cartoon",
        type:"POST",
        data:JSON.stringify({"glycoct":glycoct,"notation":"cfg","format":"svg"}),
        dataType: 'json',
        contentType:"application/json",
        complete: function(data) {
            if(data.responseText.indexOf("error") >= 0) {
                $('#'+div).html("No image");
                return;
            }
            $('#'+div).html(data.responseText);
            var width = $('#'+div).find("rect")[0].attributes.width.value;
            var height = $('#'+div).find("rect")[0].attributes.height.value;
            $('#'+div).width(width*0.8);
            $('#'+div).height(height*0.8);
            $('#'+div).find("svg")[0].setAttribute('width', width);
            $('#'+div).find("svg")[0].setAttribute('height', height);
            $('#'+div).find("svg")[0].setAttribute('style', "fill-opacity:1; color-rendering:auto; color-interpolation:auto; text-rendering:auto; stroke:black; stroke-linecap:square; stroke-miterlimit:10; shape-rendering:auto; stroke-opacity:1; fill:black; stroke-dasharray:none; font-weight:normal; stroke-width:1; font-family:'Dialog'; font-style:normal; stroke-linejoin:miter; font-size:12; stroke-dashoffset:0; image-rendering:auto;transform:scale(0.8);-webkit-transform-origin: 0 0;");
        }
    });
}