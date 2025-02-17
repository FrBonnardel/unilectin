<?php 

//RESULTS ARRAY
//$results = mysqli_query ( $connexion, $request ) or die ( "SQL Error:<br>$request<br>" . mysqli_error ( $connexion ) );
$list_domain=array();
$protein_id=0;
$results_tmp = $results;
foreach($results_array as $row){
	array_push($list_domain , $row['nbdomain']);
}
$min = min($list_domain)*1;
$min = max($min, 1);
$max = max($list_domain)*1;
$data = "<script>var data = [";
for ($i = $min; $i <= $max; $i++) {
	$nbprot = array_count_values($list_domain)["$i"];
	if($nbprot == ""){
		$nbprot=0;
	}
	$data.= '{xvalue: "'.$i.'", yvalue: '.$nbprot.'},';
}
$data = rtrim($data,",");
$data .= "];</script>";
echo $data;
//SPECIES NODES


//SPECIES GROUP NODE
//$json_formatted_species=$list_sk_nodes;
?>

<div class="div-border-title">
	<?php echo $POST_ARRAY['domain']?> Blade distribution
</div>
<div class="div-border" style="width:100%; height:auto;display:inline-block;">
	<div id="domain_graph" style="width:100%; height:auto;"></div>

	<style>

		.bar rect {
			shape-rendering: crispEdges;
		}
		.bar text {
			fill: #999999;
		}
		.axis path, .axis line {
			fill: none;
			stroke: #000;
			shape-rendering: crispEdges;
		}
	</style>
<script src='/js/propeller_barchart.js?152396639'></script>
  <script>create_barchart(500,254);</script>
</div>