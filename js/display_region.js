function reloadphp_div(valuepage){
    document.getElementById('activepage').value = valuepage;
    document.getElementById('submit_search_form').click();
}

function viewer(region_id){
    var width = window.innerWidth
    || document.documentElement.clientWidth
    || document.body.clientWidth;

    var height = window.innerHeight
    || document.documentElement.clientHeight
    || document.body.clientHeight;
    window.open("/includes/display_region.php?viewer=1&region_id="+region_id+"&width="+width, "_blank", 'height='+height+', width='+width);
}

function refresh_svg(region_id){
    $.post('./includes/display_region_svg.php',
	    $('#update_svg_'+region_id).serialize(),
	    function(data,status){
	$('#svg_div_'+region_id).html('');
	$('#svg_div_'+region_id).append(data);
    }
    );
}

function onchange_range(region_id,size){
    if(size<150){
	return false;
    }
    var range_lower = document.getElementById('range_lower_'+region_id);
    var range_upper = document.getElementById('range_upper_'+region_id);
    var range_slider = document.getElementById('range_slider_'+region_id);
    var value_upper = range_upper.value*1-99;
    var value_lower = range_lower.value*1+99;
    range_lower.max = value_upper;
    range_upper.min = value_lower;
    var percent_lower=((value_upper)/size*100);
    var percent_upper=((size-value_lower+1)/size*100);
    var percent_slider = ((value_upper*1 + value_lower*1 - 100)/2)/size*100;
    percent_lower = percent_lower * 97 / 100;
    percent_upper = percent_upper * 97 / 100;
    if(percent_upper < 2.5){
	percent_upper = 2.5;
	percent_slider = 88;
    }
    if(percent_slider > 88){
	percent_slider = 88;
    }
    range_lower.style.width = percent_lower+'%';
    range_upper.style.width = percent_upper+'%';
    range_lower.style.marginRight = (100-percent_lower)+'%';
    range_upper.style.marginLeft  = (100-percent_upper)+'%';
    range_slider.style.marginLeft  = percent_slider+'%';
    document.getElementById('amount_lower_'+region_id).value = range_lower.value;
    document.getElementById('amount_upper_'+region_id).value = range_upper.value;
    $('#button_'+region_id).click();
}

function move_left(region_id,size,step){
    if(size<150){
	return false;
    }
    var range_lower = document.getElementById('range_lower_'+region_id);
    var range_upper = document.getElementById('range_upper_'+region_id);
    var value_lower = range_lower.value;
    var value_upper = range_upper.value;
    range_lower.value = value_lower*1-(step*1);
    range_upper.value = value_upper*1-(step*1);
    onchange_range(region_id,size);
}

function move_right(region_id,size,step){
    if(size<150){
	return false;
    }
    var range_lower = document.getElementById('range_lower_'+region_id);
    var range_upper = document.getElementById('range_upper_'+region_id);
    var value_lower = range_lower.value;
    var value_upper = range_upper.value;
    range_lower.value = value_lower*1+(step*1);
    range_upper.value = value_upper*1+(step*1);
    onchange_range(region_id,size);
}

function slide_region(region_id,size,direction){
    if(size<150){
	return false;
    }
    var range_slider = document.getElementById('range_slider_'+region_id);
    range_slider.value = 0;
    var range_lower = document.getElementById('range_lower_'+region_id);
    var range_upper = document.getElementById('range_upper_'+region_id);
    var value_lower = range_lower.value;
    var value_upper = range_upper.value;
    var step = 0.1*(value_upper - value_lower);
    if(direction*1 < 0){
	range_lower.value = value_lower*1-step;
	range_upper.value = value_upper*1-step;
    }
    if(direction*1 > 0){
	range_lower.value = value_lower*1+step;
	range_upper.value = value_upper*1+step;
    }
    onchange_range(region_id,size);
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

function wheel_svg_zoom(region_id,size){
    if(size<150){
	return false;
    }
    var wheelEvent = isEventSupported('mousewheel') ? 'mousewheel' : 'wheel';
    $('#svg_div_content_'+region_id).on(wheelEvent, function(e) {
	preventDefault(e);
	var oEvent = e.originalEvent,
	delta = oEvent.deltaY || oEvent.wheelDelta;
	var range_lower = document.getElementById('range_lower_'+region_id);
	var range_upper = document.getElementById('range_upper_'+region_id);
	var value_lower = range_lower.value;
	var value_upper = range_upper.value;
	if (delta < 0) {
	    var svg_div = $('#svg_div_content_'+region_id);
	    var x = value_lower*1 + ((e.pageX - svg_div.offset().left) + svg_div.scrollLeft()) / svg_div.width() * (value_upper-value_lower);
	    range_lower.value = value_lower*1+(0.1*Math.abs(value_lower-x));
	    range_upper.value = value_upper*1-(0.1*Math.abs(value_upper-x));
	    document.getElementById('amount_lower_'+region_id).value = range_lower.value;
	    document.getElementById('amount_upper_'+region_id).value = range_upper.value;
	    onchange_range(region_id,size);
	} else {
	    range_lower.value = value_lower*1-(0.1*(value_upper-value_lower));
	    range_upper.value = value_upper*1+(0.1*(value_upper-value_lower));
	    document.getElementById('amount_lower_'+region_id).value = range_lower.value;
	    document.getElementById('amount_upper_'+region_id).value = range_upper.value;
	    onchange_range(region_id,size);
	}
    });
}

