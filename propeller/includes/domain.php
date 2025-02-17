<?php
$services_color = array ();
$services_color['prop5_TACHY']="#f4d800";
$services_color['prop6_RVY']="#e53e00";
$services_color['prop6_GVN']="#d60ddd";
$services_color['prop7_EVF']="#9060ff";
$services_color['prop7_DGFG']="#6ab975";
$services_color['PFAM']="#ffaa00";

include ("page_bar.php") ;
include ("connect.php") ;
$connexionBIG = connectdatabaseBIG();

$activepage = 1;
if (isset($_POST['activepage'])) {
    $activepage = $_POST['activepage'];
}

$results_array = array();
$domain_count = array();
//die();
$rwhere ="";
if (isset($_POST['domain']) && $_POST['domain'] != "") {
    $rwhere .= " AND domain LIKE '%" . $_POST['domain'] . "%'";
}
if (isset($_POST['score']) && $_POST['score'] > 0) {
    $rwhere .= " AND score > '" . $_POST['score'] . "'";
}
if (isset($_POST['domain_pfam']) && $_POST['domain_pfam'] != "") {
    $rwhere .= " AND pfam_name LIKE '%" . $_POST['domain_pfam'] . "%'";
}

$request_architecture = "
SELECT protein, length, protein_id, domains_id, GROUP_CONCAT(domains_id) as aligned_domains_id_list, GROUP_CONCAT(score) as similarities, pfam, pfam_name, domain, COUNT(DISTINCT(protein_id)) as nb_protein 
FROM propeller_view WHERE pfam != '' $rwhere GROUP BY domain, pfam ORDER BY nb_protein DESC";

$results = mysqli_query($connexionBIG, $request_architecture) or die("SQL Error:<br>$request_architecture<br>" . mysqli_error($connexionBIG));
$nb_regions = mysqli_num_rows($results);
$nbresultbypage = 10;
$windownumberpage = ceil(intval($nb_regions) / $nbresultbypage);
$first_display_region = (($activepage - 1) * $nbresultbypage) + 1;
$last_display_region = min(($activepage * $nbresultbypage), $nb_regions);
// RESULTS ARRAY

$request_architecture .= " LIMIT " . (($activepage - 1) * $nbresultbypage) . " , " . $nbresultbypage;
echo "<div style='line-height:30px;height:40px;border:1px solid lightgrey;padding:5px;background-image: linear-gradient(to bottom,#fff 0,#e0e0e0 100%);'>architecture list $first_display_region to $last_display_region out of $nb_regions </div>";

$results_architecture = mysqli_query ( $connexionBIG, $request_architecture ) or die ( "SQL Error:<br>$request_architecture<br>".mysqli_error ( $connexionBIG ) );
include("../includes/create_request.php");
$display_increment_id = 0;
while ( $row_architecture = mysqli_fetch_array ( $results_architecture, MYSQLI_ASSOC ) ) {
    $display_increment_id+=1;
    $region_id = $display_increment_id;
    $id = $display_increment_id;
    echo "<div class='div-border' style='display: inline-block;width:100%;margin-top:30px;'>";
    echo "<div  class='div-border' id='lectin_viewer_main_container_$id' style='border:1px solid lightgrey;font-size:16px;background-image: linear-gradient(to bottom,#fff 0,#e0e0e0 100%);display: inline-block;'>{$row_architecture['domain']} with {$row_architecture['pfam']} : {$row_architecture['pfam_name']}";
    echo "  -  {$row_architecture['nb_protein']} related proteins";
    echo "<button style='width:30px;height:30px;padding:5px;float:right;' class='btn btn-md btn-success' data-toggle='collapse' data-target='#details_$id'>+</button>";
    echo "</div>";

    $aligned_domains_id_list = explode(",",$row_architecture['aligned_domains_id_list']);
    $similarities = explode(",",$row_architecture['similarities']);
    $proteins_by_simi=array();
    for ($i = 0; $i < count($aligned_domains_id_list); $i++) {
        if($aligned_domains_id_list[$i]==""){continue;}
        $proteins_by_simi[$aligned_domains_id_list[$i]]=$similarities[$i];
    }
    arsort($proteins_by_simi);
    //reset($proteins_by_simi);
    $protein = $row_architecture['protein'];
    $length = $row_architecture['length'];
    $protein_id = $row_architecture['protein_id'];
    $domains_id = $row_architecture['domains_id'];

    // CREATE DOMAIN PLOT FILE
    $services_annot = array ();
    $request_domain = "SELECT domain, begin, end FROM tandem_aligned_domains LEFT JOIN tandem_domain ON (tandem_domain.domains_id = tandem_aligned_domains.domains_id) WHERE protein_id = $protein_id AND tandem_aligned_domains.domains_id = $domains_id ";
    $results_domain = mysqli_query($connexionBIG, $request_domain) or die("SQL Error:<br>$request_domain<br>" . mysqli_error($connexionBIG));
    //echo $request_domain;
    while ($row_annot = mysqli_fetch_array($results_domain, MYSQLI_ASSOC)) {
        $annotation = array ();
        $annotation['service'] = $row_annot['domain'];
        $annotation['name'] = $row_annot['domain'];
        $annotation['description'] = "";
        $annotation['begin'] = $row_annot['begin'];
        $annotation['end'] = $row_annot['end'];
        array_push($services_annot,$annotation);
    }
    $request_domain = "SELECT name, begin, end FROM lectinpred_pfam pfam LEFT JOIN lectinpred_pfam_domain pfamdo ON (pfam.pfam_entry_id = pfamdo.pfam_entry_id) WHERE protein = '$protein'";
    $results_domain = mysqli_query($connexionBIG, $request_domain) or die("SQL Error:<br>$request_domain<br>" . mysqli_error($connexionBIG));
    while ($row_annot = mysqli_fetch_array($results_domain, MYSQLI_ASSOC)) {
        $annotation = array ();
        $annotation['service'] = "PFAM";
        $annotation['name'] = $row_annot['name'];
        $annotation['description'] = "";
        $annotation['begin'] = $row_annot['begin'];
        $annotation['end'] = $row_annot['end'];
        array_push($services_annot,$annotation);
    }

    //ENCODE
    $rowser = array ();
    $rowser['sequence'] = "";
    $rowser['length'] = $length;
    $rowser['name'] = "";
    $rowser['protein_id'] = $protein_id;
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
    //echo '<pre>'; print_r($proteins_by_simi); echo '</pre>';
    echo "<div id='details_$id' class='collapse' style=''>";
    echo "<table style='width: 100%;' class='manage_tables'>";
    echo "<thead><tr>";
    echo ("<td>protein</td>");
    echo ("<td>name</td>");
    echo ("<td>species</td>");
    echo ("<td>domains</td>");
    echo ("<td>gene</td>");
    echo ("<td>similarity</td>");
    echo ("<td>nbdomain</td>");
    echo ("<td>action</td>");
    echo "</tr></thead>";
    echo "<tbody>";
    foreach($proteins_by_simi as $aligned_domains_id => $similarity){
        $request = "SELECT protein.protein_id, protein.protein, name, species, gene, gene_begin, gene_end, domains_id, domain, score, nbdomain ";
        $request .= "FROM tandem_protein protein JOIN tandem_species species ON (protein.species_id = species.species_id) JOIN tandem_aligned_domains ON(protein.protein_id=tandem_aligned_domains.protein_id) ";
        $request .= "WHERE tandem_aligned_domains.domains_id = $aligned_domains_id ORDER BY score DESC";
        $results = mysqli_query ( $connexionBIG, $request ) or die ( "SQL Error:<br>$request<br>".mysqli_error ( $connexionBIG ) );
        $row = mysqli_fetch_array ( $results, MYSQLI_ASSOC );
        echo "<tr>";
        echo "<td>{$row['protein']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['species']}</td>";
        echo "<td>{$row['domain']}</td>";
        echo "<td>{$row['gene']} [{$row['gene_begin']}-{$row['gene_end']}]</td>";
        echo "<td>{$row['score']}</td>";
        echo "<td>{$row['nbdomain']}</td>";
        echo "<td><button style='width:60px;height:30px;padding:5px;float:right;' class='btn btn-md btn-success' onclick=\"window.open('/propeller/display?protein_id={$row['protein_id']}&aligned_domains_id={$row['domains_id']}')\"><span class='glyphicon glyphicon-resize-full'></span></button></td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";

}
page_bar($activepage, $windownumberpage);
?>
