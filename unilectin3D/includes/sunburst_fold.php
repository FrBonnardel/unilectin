<div id="chart_fold" class="chart" style="display:inline-block;">
	<div id="tooltip_fold" class="tooltip"></div>
</div>
<div id="legend_fold" class="legend"  style="display:inline-block;max-height:246px;overflow-y:auto;overflow-x:hidden;"></div>
<?php
$request = "SELECT fold, class, family FROM lectin_view WHERE 1 $rwhere GROUP BY fold, class, family ORDER BY fold, class, family ";
$results = mysqli_query ( $connexion, $request ) or die ( "SQL Error:<br>$request<br>" . mysqli_error ( $connexion ) );
$total_objects_sunburst= mysqli_num_rows($results);
$counter=array();
while ( $row = mysqli_fetch_array ( $results, MYSQLI_ASSOC ) ) {
    $value = str_replace(".",":",$row['fold']).".".str_replace(".",":",$row['class']).".".str_replace(".",":",$row['family']);
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
"b-sandwich / ConA-like":"#5687d1",
"b-trefoil":"#6ab975",
"a/b mixed / C-type lectin-like":"#de783b",
"b-sandwich / viral coat and capsid protein":"#7b615c",
"b-prism I":"#a173d1",
"b-sandwich / viral protein domain":"#20b19a",
"b-sandwich / pili and adhesins":"#59052f",
"a/b OB-fold":"#ccc49c",
"b-propeller":"#d68c24",
"b-helix":"#d14b7b",
"b-sandwich / Galactose-binding domain-like":"#099b5a",
"b-sandwich / 2 calcium lectin":"#f2de24",
"b-sandwich / Ig-like":"#aed28c",
"small protein / Knottin":"#8cd02a",
"b-prism II":"#89d280",
"b-sandwich / cyanovirin-like":"#539929",
"b-sandwich / virus globular domain":"#fa950c",
"a/b mixed with b-sheet / Fibrinogen C-ter like":"#fb7b74",
"b-sandwich / yeast adhesin":"#4d978c",
"b-barrel":"#d102b2",
"b-sandwich / cytolysin-like":"#30df41",
"a/b mixed with b-sheet / not classified":"#44b3e3",
"a/b barrel / TIM":"#6bcd07",
"a/b hairpin / non-globular proline-rich":"#9a9a97",
"b-sandwich / CUB-like":"#cdd82e",
"b-prism III":"#0e6de9",
"small protein / APPLE domain":"#9c0a37",
"small protein / disulfide rich":"#f7ec74"
	};
	var levels = {
		0: "fold",
        1: "class",
        2: "family"
	};
	var total_objects_sunburst = <?php echo $total_objects_sunburst; ?>;
	var onclick_link = "https://unilectin.eu/unilectin3D/search?";
	create_sunburst("#chart_fold","#tooltip_fold","#legend_fold",data,colors,levels,total_objects_sunburst,onclick_link,340);
</script>