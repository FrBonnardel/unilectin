<?php
include ("page_bar.php") ;
include ("connect.php") ;
$connexionBIG = connectdatabaseBIG() ;

$activepage = 1;
if (isset($_POST['activepage'])) {
	$activepage = $_POST['activepage'];
}

$results_array = array();
$domain_count = array();
//die();
$rwhere ="";
if (isset($_POST['domain']) && $_POST['domain'] != "") {
	$rwhere .= " AND ad.domain LIKE '%" . $_POST['domain'] . "%'";
}
if (isset($_POST['similarity_score']) && $_POST['similarity_score'] > 0) {
	$rwhere .= " AND ad.score > '" . $_POST['similarity_score'] . "'";
}
if (isset($_POST['domain_pfam']) && $_POST['domain_pfam'] != "") {
	$rwhere .= " AND pfam_name LIKE '%" . $_POST['domain_pfam'] . "%'";
}
if (isset($_POST['superkingdom']) && $_POST['superkingdom'] != "") {
    $rwhere .= " AND superkingdom LIKE '%" . $_POST['superkingdom'] . "%'";
}
if (isset($_POST['kingdom']) && $_POST['kingdom'] != "") {
    $rwhere .= " AND kingdom LIKE '%" . $_POST['kingdom'] . "%'";
}
if (isset($_POST['phylum']) && $_POST['phylum'] != "") {
    $rwhere .= " AND phylum LIKE '%" . $_POST['phylum'] . "%'";
}

$request_architecture = "
SELECT GROUP_CONCAT(p.protein_id) as proteins_id, 
GROUP_CONCAT(score) as similarities, 
pfam, 
domain, 
COUNT(DISTINCT(p.protein_id)) as nb_protein 
FROM lectinpred_protein p  left JOIN lectinpred_species s ON (p.species_id = s.species_id) LEFT join lectinpred_aligned_domains ad ON(p.protein_id=ad.protein_id)
WHERE pfam != '' $rwhere GROUP BY pfam, domain ORDER BY nb_protein DESC";

//echo $request_architecture;

$results = mysqli_query($connexionBIG, $request_architecture) or die("SQL Error:<br>$request_architecture<br>" . mysqli_error($connexionBIG));
$nb_regions = mysqli_num_rows($results);
$nbresultbypage = 20;
$windownumberpage = ceil(intval($nb_regions) / $nbresultbypage);
$first_display_region = (($activepage - 1) * $nbresultbypage) + 1;
$last_display_region = min(($activepage * $nbresultbypage), $nb_regions);
// RESULTS ARRAY

$request_architecture .= " LIMIT " . (($activepage - 1) * $nbresultbypage) . " , " . $nbresultbypage;
echo "<div style='line-height:30px;height:40px;border:1px solid lightgrey;padding:5px;background-image: linear-gradient(to bottom,#fff 0,#e0e0e0 100%);'>architecture list $first_display_region to $last_display_region out of $nb_regions </div>";

$results_architecture = mysqli_query ( $connexionBIG, $request_architecture ) or die ( "SQL Error:<br>$request_architecture<br>".mysqli_error ( $connexionBIG ) );
include("create_request.php");
$display_increment_id = 0;
while ( $row_architecture = mysqli_fetch_array ( $results_architecture, MYSQLI_ASSOC ) ) {
	$display_increment_id+=1;
	$region_id = $display_increment_id;
	$id = $display_increment_id;
	$proteins_id = explode(",",$row_architecture['proteins_id']);
	$similarities = explode(",",$row_architecture['similarities']);
	$proteins_by_simi=array();
	for ($i = 0; $i < count($proteins_id); $i++) {
		if($proteins_id[$i]==""){continue;}
		$proteins_by_simi[$proteins_id[$i]]=$similarities[$i];
	}
	arsort($proteins_by_simi);
	reset($proteins_by_simi);
	$protein_id = key($proteins_by_simi);
	$request = create_request(null,$protein_id,null);
	$results = mysqli_query ( $connexionBIG, $request ) or die ( "SQL Error:<br>$request<br>".mysqli_error ( $connexionBIG ) );
	$row = mysqli_fetch_array ( $results, MYSQLI_ASSOC );
	if($row['uniparc'] != ""){
		if(strpos($row['uniparc'],"UPI") !== false){
			$uniparc = "<a target='_blank' href='http://www.uniprot.org/uniparc/{$row['uniparc']}'>{$row['uniparc']}</a>";
		}else{
			$uniparc = "<a target='_blank' href='http://www.uniprot.org/uniprot/{$row['uniparc']}'>{$row['uniparc']}</a>";
		}
	}
	$ncbi = "<a target='_blank' href='https://www.ncbi.nlm.nih.gov/protein/{$row['name']}'>{$row['name']}</a>";
	echo "<div class='div-border' style='display: inline-block;width:100%;margin-top:30px;'>";
	echo "<div  class='div-border' id='lectin_viewer_main_container_$id' style='border:1px solid lightgrey;font-size:16px;background-image: linear-gradient(to bottom,#fff 0,#e0e0e0 100%);display: inline-block;'>Architecture {$row_architecture['domain']} : {$row_architecture['pfam']}";
	echo "  -  {$row_architecture['nb_protein']} related proteins";
	echo "<button style='width:30px;height:30px;padding:5px;float:right;' class='btn btn-md btn-success' data-toggle='collapse' data-target='#details_$id'>+</button>";
	echo "</div>";


	// CREATE DOMAIN PLOT FILE
	$services_annot = array ();
	$request_domain = "SELECT domain, begin, end FROM lectinpred_aligned_domains LEFT JOIN lectinpred_domain ON (lectinpred_domain.domains_id = lectinpred_aligned_domains.domains_id) WHERE protein_id = '{$row['protein_id']}'";
	$results_domain = mysqli_query($connexionBIG, $request_domain) or die("SQL Error:<br>$request_domain<br>" . mysqli_error($connexionBIG));
  $domain_begin=100000000000000;
  $domain_end=0;
	while ($row_annot = mysqli_fetch_array($results_domain, MYSQLI_ASSOC)) {
		$annotation = array ();
		$annotation['service'] = "unilectin";
		$annotation['name'] = $row_annot['domain'];
		$annotation['description'] = "";
		$annotation['begin'] = $row_annot['begin'];
		$annotation['end'] = $row_annot['end'];
		array_push($services_annot,$annotation);
    if($row_annot['begin'] < $domain_begin ){
      $domain_begin=$row_annot['begin'];
    }
    if($row_annot['end'] > $domain_end ){
      $domain_end=$row_annot['end'];
    }
	}
    $request_domain = "SELECT name, pfam, begin, end FROM lectinpred_pfam pfam LEFT JOIN lectinpred_pfam_domain pfamdo ON (pfam.pfam_entry_id = pfamdo.pfam_entry_id) ";
    $request_domain .= " WHERE protein_id = '{$row['protein_id']}' ";
    $pfam_list = explode(', ',$row_architecture['pfam']);
    $where_pfam=" AND ( ";
    foreach($pfam_list as $pfam){
        $where_pfam.=" pfam = '{$pfam}' OR ";
	}
    $where_pfam = rtrim($where_pfam, 'OR ');
    $where_pfam .=" ) ";
    $request_domain = $request_domain . $where_pfam ;
	$results_domain = mysqli_query($connexionBIG, $request_domain) or die("SQL Error:<br>$request_domain<br>" . mysqli_error($connexionBIG));
	while ($row_annot = mysqli_fetch_array($results_domain, MYSQLI_ASSOC)) {
		$annotation = array ();
        $annotation['service'] = "pfam";
		$annotation['name'] = $row_annot['name'];
		$annotation['description'] = $row_annot['pfam'];
		$annotation['begin'] = $row_annot['begin'];
		$annotation['end'] = $row_annot['end'];
		array_push($services_annot,$annotation);
	}
	//COLOR
	$services_color = array ();
	$services_color['pfam']="#ffaa00";

	//ENCODE
	$rowser = array ();
	$rowser['sequence'] = $row['sequence'];
	$rowser['length'] = $row['length'];
	$rowser['name'] = $row['name'];
	$rowser['protein_id'] = $row['protein_id'];
	foreach($rowser as $key => $value){
		$value = str_replace(' ', '_', $value);
		$value = str_replace('|', '_', $value);
		$rowser[$key] = preg_replace('/["\']/', '', $value);
	}
	$rowser = json_encode( $rowser);
	foreach($services_annot as $key => $value){
		$value = str_replace(' ', '_', $value);
		$value = str_replace('|', '_', $value);
		$services_annot[$key] = preg_replace('/["\']/', '', $value);
	}
	$services_annot_ser = json_encode( $services_annot);
	$services_color_ser = json_encode( $services_color );

	//STORE INFO
	echo "<div id='input_$region_id'>";
	echo "<input type='hidden' name='row' id='row_$region_id' value=$rowser>";
	echo "<input type='hidden' name='services_annot' id='services_annot_$region_id' value=$services_annot_ser>";
	echo "<input type='hidden' name='services_color' id='services_color_$region_id' value=$services_color_ser>";
	echo "</div>";

	// SVG SECTION
	$transcript_size=$row['length']*3;
	echo "<div id='slider_div_$region_id' style='width:100%;display:inline-block;'></div>";
	echo "<button name='button_$region_id' id='button_$region_id' class='button_$region_id' style='display:none;' onclick=\"create_svg('$region_id');\">Refresh SVG</button>";
	echo "<div id='svg_div_$region_id' style='margin:0px;padding:0px;overflow:hidden;overflow-y:auto;width:100%;max-height:30em;' onwheel=\"wheel_svg_zoom('$region_id','$transcript_size');\"></div>";
	echo "<script>";
	echo "		create_slider('$region_id');";
	echo "		create_svg('$region_id');";
	echo "</script>";
	//DETAILS DIV	//DETAILS DIV
	echo "<div id='details_$id' class='collapse' style=''>";
	echo "<table style='width: 100%;' class='manage_tables'>";
	echo "<thead><tr>";
	echo ("<td>protein</td>");
	echo ("<td>name</td>");
	echo ("<td>species</td>");
	echo ("<td>domains</td>");
	echo ("<td>gene</td>");
	echo ("<td>similarity</td>");
	echo ("<td>action</td>");
	echo "</tr></thead>";
	echo "<tbody>";
	foreach($proteins_by_simi as $protein_id => $similarity){
		$request = "SELECT p.protein_id, p.uniprot, p.name, species, gene, gene_begin, gene_end, GROUP_CONCAT(DISTINCT(domain)) as domains, score ";
        $request .= "FROM lectinpred_protein p LEFT JOIN lectinpred_aligned_domains ad ON(p.protein_id=ad.protein_id) ";
        $request .= "LEFT JOIN lectinpred_species s ON(p.species_id=s.species_id) ";
		$request .= "WHERE p.protein_id = $protein_id GROUP BY p.protein_id ORDER BY score DESC";
		$results = mysqli_query ( $connexionBIG, $request ) or die ( "SQL Error:<br>$request<br>".mysqli_error ( $connexionBIG ) );
		$row = mysqli_fetch_array ( $results, MYSQLI_ASSOC );
		echo "<tr>";
		echo "<td>{$row['uniprot']}</td>";
        echo "<td>{$row['name']}</td>";
		echo "<td>{$row['species']}</td>";
		echo "<td>{$row['domains']}</td>";
		echo "<td>{$row['gene']} [{$row['gene_begin']}-{$row['gene_end']}]</td>";
		echo "<td>{$row['score']}</td>";
		echo "<td><button style='width:60px;height:30px;padding:5px;float:right;' class='btn btn-md btn-success' onclick=\"window.open('./display?protein_id={$row['protein_id']}')\"><span class='glyphicon glyphicon-resize-full'></span></button></td>";
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";
	echo "</div>";
	echo "</div>";

}
page_bar($activepage, $windownumberpage);
?>
