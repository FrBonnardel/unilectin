<div id="chart_carbo" class="chart" style="display:inline-block;">
	<div id="tooltip_carbo" class="tooltip"></div>
</div>
<div id="legend_carbo" class="legend"  style="display:inline-block;"></div>
<?php
$request = "SELECT monosac, iupac FROM lectin_view WHERE monosac != '' $rwhere GROUP BY monosac, iupac ORDER BY monosac, iupac ";
$results = mysqli_query ( $connexion, $request ) or die ( "SQL Error:<br>$request<br>" . mysqli_error ( $connexion ) );
$counter=array();
$total_objects_sunburst=0;
while ( $row = mysqli_fetch_array ( $results, MYSQLI_ASSOC ) ) {
	$sucres = explode(',', $row['monosac']);
	foreach ($sucres as $sucre){
		$sucre = str_replace(' ', '', $sucre);
		$value = str_replace(".",":",$sucre).".".str_replace(".",":",$row['iupac']);
    if(!isset($counter[$value])){
      $counter[$value]=0;
    }
    $counter[$value]+=1;
    $total_objects_sunburst+=1;
	}
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
		"D-Galp": "#5687d1",
		"D-GlcpNAc": "#6ab975",
		"D-Glcp": "#de783b",
		"D-Manp": "#7b615c",
		"L-Fucp": "#a173d1",
		"D-GalpNAc": "#20b19a",
		"D-Neup5Ac": "#ccc49c"
	};
	var levels = {
		0: "monosac",
		1: "iupac"
	};
	var total_objects_sunburst = <?php echo $total_objects_sunburst; ?>;
	var onclick_link = "https://unilectin.eu/unilectin3D/search?iupac_exact=true&";
	create_sunburst("#chart_carbo","#tooltip_carbo","#legend_carbo",data,colors,levels,total_objects_sunburst,onclick_link,200);
</script>