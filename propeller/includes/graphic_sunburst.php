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

	#legend {
		padding: 5px;
		text-align: center;
    float: right;
	}

	#sequence text, #legend text {
		font-weight: 600;
		fill: #fff;
	}

	#chart {
		position: relative;
	}

	#chart path {
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
	<div id="chart" style="display:inline-block;margin-top:20px;">
	<div id="tooltip" class="tooltip"></div>
	</div>
	<div id="legend" style="display:inline-block;"></div>
</div>
<script type="text/javascript" src="/propeller/js/propeller_sunburst.js"></script>
<?php
//include ("connect.php") ;
//$connexion = connectdatabase () ;
//include("lectinpropellerpred_create_request.php");
//$POST_ARRAY=$_POST;
//$request = create_request($POST_ARRAY,"tree",0);
//$results = mysqli_query ( $connexion, $request ) or die ( "SQL Error:<br>$request<br>" . mysqli_error ( $connexion ) );
$total_objects_sunburst= mysqli_num_rows($results);
$counter=array();

foreach($results_array as $row){
  $chars=[".","[","]","'"];
	$s_group=explode(" ",str_replace($chars,":",$row['species']))[0];
	$value = str_replace($chars,":",$row['superkingdom']).".".str_replace($chars,":",$row['kingdom']);
	$value .= ".".str_replace($chars,":",$row['phylum']).".".$s_group.".".str_replace($chars,":",$row['species']);
	if(!isset($counter[$value])){
		$counter[$value]=0;
	}
	$counter[$value]+=1;
}
$data = "";
foreach($counter as $key => $value){
	$data.="['".$key."',".($value / $total_objects_sunburst)."],";
}
$data = rtrim($data,",");
?>
<script>
function unsync_create_sunburst() {
  return new Promise(resolve => {
    setTimeout(() => {
      var data = [<?php echo $data ?>];
      var colors = {
        "Bacteria": "#5687d1",
        "Eukaryota": "#7b615c",
        "Archaea": "#de783b"
      };
      var levels = {
        0: "superkingdom",
        1: "kingdom",
        2: "phylum",
        3: "species",
        4: "species"
      };
      var total_objects_sunburst = <?php echo $total_objects_sunburst; ?>;
      create_sunburst(300, 300, "#chart","#tooltip","#legend",data,colors,levels,total_objects_sunburst);
    }, 10);
  });
};
unsync_create_sunburst();
</script>