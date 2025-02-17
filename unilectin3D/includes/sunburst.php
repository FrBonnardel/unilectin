<div class="div-border-title">
	Sunburst of Lectin 3D
</div>
<div class="div-border" style="width:100%; height:auto;display:inline-block;margin-bottom:30px;">
	<div id="main" style="width:100%;">
		<div id="sequence"></div>
		<div id="chart">
			<div id="explanation-container">
				<div id="tutorial">
					Select a category on the sunburst
				</div>
				<div id="explanation" style="visibility: hidden;">
					<span id="percentage"></span><br>
					<span id="percentage-value"></span>&nbsp;structures
				</div>
				<div id="legend"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="/js/sunburst_sequences.js"></script>
<?php
$request = "SELECT origine, classe, famille FROM lectin_view GROUP BY origine, classe, famille  ORDER BY origine, classe, famille ";
$results = mysqli_query ( $connexion, $request ) or die ( "SQL Error:<br>$request<br>" . mysqli_error ( $connexion ) );
$total_objects_sunburst= mysqli_num_rows($results);
$counter=array();
while ( $row = mysqli_fetch_array ( $results, MYSQLI_ASSOC ) ) {
	$value = str_replace(".",":",$row['origine']).".".str_replace(".",":",$row['classe']).".".str_replace(".",":",$row['famille']);
	if(!isset($counter[$value])){
		$counter[$value]=0;
	}
	$counter[$value]+=1;
}
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
		0: "origine",
		1: "classe",
		2: "famille"
	};
	var total_objects_sunburst = <?php echo $total_objects_sunburst; ?>;
    var onclick_link = "https://unilectin.eu/unilectin3D/search?";
	create_sunburst(data, onclick_link);
</script>