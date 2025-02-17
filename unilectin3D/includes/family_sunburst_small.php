<div id="chart_families" class="chart"  style="display:inline-block;">
	<div id="tooltip_families" class="tooltip"></div>
</div>
<div id="legend_families" class="legend"  style="display:inline-block;"></div>
<?php
$request = "SELECT origin, species FROM lectin_view WHERE 1 $rwhere GROUP BY origin, species ORDER BY origin, species";
$results = mysqli_query ( $connexion, $request ) or die ( "SQL Error:<br>$request<br>" . mysqli_error ( $connexion ) );
$total_objects_sunburst= mysqli_num_rows($results);
$counter=array();
while ( $row = mysqli_fetch_array ( $results, MYSQLI_ASSOC ) ) {
	$value = str_replace(".",":",$row['origin']).".".str_replace(".",":",$row['species']);
	if(!isset($counter[$value])){
		$counter[$value]=0;
	}
	$counter[$value]+=1;
}
//echo '<pre>'; print_r($counter); echo '</pre>';
$data = "var data = [";
foreach($counter as $key => $value){
	$data.="['".$key."',".($value / $total_objects_sunburst)."],";
}
$data = rtrim($data,",");
$data.="];";
echo "<script>$data</script>";
?>
<script>
	var colors = {
		"Animal lectins": "#5687d1",
		"Plant lectins": "#6ab975",
		"Bacterial lectins": "#de783b",
		"Virus lectins": "#7b615c",
		"Fungal and yeast lectins": "#a173d1",
		"Protist, parasites, mold": "#ccc49c"
	};
	var levels = {
		0: "origin",
		1: "species"
	};
	var total_objects_sunburst = <?php echo $total_objects_sunburst; ?>;
	var onclick_link = "https://unilectin.eu/unilectin3D/search?";
	create_sunburst("#chart_families","#tooltip_families","#legend_families",data,colors,levels,total_objects_sunburst,onclick_link,200);
</script>