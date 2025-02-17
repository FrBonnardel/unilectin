<style>
	.tooltip{
	position: absolute; 
		text-align: center; 
		width: 150px; 
		height: auto;   
		padding: 2px; 
		font: 12px sans-serif;  
		background: black;  
		border: 0px;          
		border-radius: 8px;
		color:white;
		box-shadow: -3px 3px 15px #888888;
		opacity:0;  

	}  
	#sidebar {
		float: right;
		width: 100px;
	}

	#sequence {
		width: 600px;
		height: 70px;
	}

	#legend_cat {
		padding: 5px;
		text-align: center;
    float: right;
    overflow-y:auto;
    max-height: 330px;
	}

	#sequence text, #legend text {
		font-weight: 600;
		fill: #fff;
	}

	#chart_cat {
		position: relative;
	}

	#chart_cat path {
		stroke: #fff;
	}

	#explanation, #tutorial {
		top: 5px;
		left: 5px;
		width: 140px;
		text-align: center;
		color: #666;
		z-index: -1;
	}

	#percentage {
		font-size: 1em;
	}
</style>

<div class="div-border-title">
	<?php echo $POST_ARRAY['domain']?> Species distribution
</div>
<div class="div-border" style="width:100%; height:100%;display:inline-block;padding-left:30px;">
	<div id="chart_cat" style="display:inline-block;margin-top:20px;">
	<div id="tooltip_cat" class="tooltip"></div>
	</div>
	<div id="legend_cat" style="display:inline-block;"></div>
</div>
<script type="text/javascript" src="/js/lectinpred_sunburst.js"></script>
<?php
//include ("connect.php") ;
//$connexionBIG = connectdatabase () ;
//include("lectinpropellerpred_create_request.php");
//$POST_ARRAY=$_POST;
//$request = create_request($POST_ARRAY,"tree",0);
//$results = mysqli_query ( $connexionBIG, $request ) or die ( "SQL Error:<br>$request<br>" . mysqli_error ( $connexionBIG ) );
$total_objects_sunburst= mysqli_num_rows($results);
$counter=array();
$classes=array();
mysqli_data_seek($result, 0);
foreach($results_array as $row){
  $chars=[".","[","]","'"];
	$value = str_replace($chars,":",$row['domain']);
	if(!isset($counter[$value])){
		$counter[$value]=0;
	}
	$counter[$value]+=1;
	$classes[$row['domain']]=1;
}
$data = "";
foreach($counter as $key => $value){
	$data.="['".$key."',".($value / $total_objects_sunburst)."],";
}
$data = rtrim($data,",");
  
function rand_color() {
    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}
$count_nbprot_domain=array();
$color_data = "";
foreach($classes as $class => $count){
  $color =  rand_color();
  $color_data.= "'$class': '$color',";
}
$color_data = rtrim($color_data ,',');

?>
<script>
function unsync_create_sunburst_cat() {
  return new Promise(resolve => {
    setTimeout(() => {
      var data_cat = [<?php echo $data ?>];
      var colors_cat = {<?php echo $color_data ?>};
      var levels_cat = {
        0: "domain",
        1: "domain"
      };
      var total_objects_sunburst_cat = <?php echo $total_objects_sunburst; ?>;
      create_sunburst(300, 300, "#chart_cat","#tooltip_cat","#legend_cat",data_cat,colors_cat,levels_cat,total_objects_sunburst_cat);
    }, 10);
  });
};
unsync_create_sunburst_cat();
</script>