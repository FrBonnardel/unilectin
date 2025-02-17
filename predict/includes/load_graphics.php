<?php

include ("page_bar.php");
include ("connect.php");
$connexionBIG = connectdatabaseBIG();

$activepage = 1;
if (isset($_POST['activepage'])) {
	$activepage = $_POST['activepage'];
}
include("create_request.php");
//echo "<pre>".print_r($_POST)."</pre>";
$POST_ARRAY=$_POST;
$request = create_request($POST_ARRAY,0,"");
$request = str_replace(', ref_seq, match_seq', '', $request);
$request = str_replace(', alt_ac, gene, gene_begin, gene_end, cluster, strain', '', $request);
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
//echo $request;

//INITIALIZATION DATA
//SPECIES NODES
$root = array();
$root['name'] = 'Lectins';
$root['children'] = array();
$added_species = array();
//SUNBURST
$total_objects_sunburst = mysqli_num_rows($results);
$counter = array();
//PIECHART
$count_nbprot_domain=array();
$count_nbprot_fold=array();
$count_nbprot_protname=array();
$count_nbprot_pfam=array();


//DISPLAY NUMBER PROTEINS
echo "<div style='line-height:30px;height:40px;border:1px solid lightgrey;padding:5px;background-image: linear-gradient(to bottom,#fff 0,#e0e0e0 100%);font-size: 24px;'>$total_objects_sunburst matching lectin candidates</div>";


//CHECK IF NO DATA
if($total_objects_sunburst == 0){
    exit("No results with current filters. Try to remove one or more criteria(s).");
}

//DATA PARSER FUNCTIONS
function piechart_data_parser($row, &$count_nbprot_domain, &$count_nbprot_fold) {
    if(!isset($count_nbprot_domain[$row['domain']])){
        $count_nbprot_domain[$row['domain']]=0;
    }
    $count_nbprot_domain[$row['domain']]+=1;
    if(!isset($count_nbprot_fold[$row['fold']])){
        $count_nbprot_fold[$row['fold']]=0;
    }
    $count_nbprot_fold[$row['fold']]+=1;
}

function piechart_protname_data_parser($row, &$count_nbprot_protname) {
    $name = $row['name'] . "    ";
    $name_split = explode(' ', $name);
    $name = $name_split[0].' '.$name_split[1];
    $name = preg_replace('/[^a-zA-Z\s\-]/','', $name);
    $name = substr($name, 0, 20);
    $name = strtolower(trim($name));
    if(!isset($count_nbprot_protname[$name])){
        $count_nbprot_protname[$name]=0;
    }
    $count_nbprot_protname[$name]+=1;
}

function piechart_pfam_data_parser($row, &$count_nbprot_pfam) {
    $pfam_names = explode(", ",$row['pfam_name']);
    foreach ($pfam_names as $pfam) {
        if (!isset($count_nbprot_pfam[$pfam])) {
            $count_nbprot_pfam[$pfam] = 0;
        }
        $count_nbprot_pfam[$pfam] += 1;
    }
}

function sunburst_data_parser($row, &$counter) {
    $chars = [".", "[", "]", "'"];
    $s_group = explode(" ", str_replace($chars, ":", $row['species']))[0];
    $value = str_replace($chars, ":", $row['superkingdom']) . "." . str_replace($chars, ":", $row['kingdom']);
    $value .= "." . str_replace($chars, ":", $row['phylum']) ;
    $value .= "." . $s_group;
    //$value .= "." . str_replace($chars, ":", $row['species']);
    if (!isset($counter[$value])) {
        $counter[$value] = 0;
    }
    $counter[$value] += 1;
}

function treeview_data_parser($row, &$root, &$added_species) {
    $row['superkingdom'] = substr($row['superkingdom'], 0, 30);
    $row['kingdom'] = substr($row['kingdom'], 0, 30);
    $row['phylum'] = substr($row['phylum'], 0, 30);
    $row['species'] = substr($row['species'], 0, 30);
    if (in_array($row['species'], $added_species)) {
        return 0;
    }
    array_push($added_species, $row['species']);
    $s_group = explode(" ", $row['species'])[0];
    $parts = [$row['superkingdom'], $row['kingdom'], $row['phylum'], $s_group, $row['species']];
    $currentNode = &$root;
    for ($j = 0; $j < count($parts); $j++) {
        $children = &$currentNode["children"];
        $nodeName = $parts[$j];
        $cat_level = $j;
        if ($j + 1 < count($parts)) {
            // Not yet at the end of the sequence; move down the tree.
            $foundChild = false;
            for ($k = 0; $k < count($children); $k++) {
                if ($children[$k]["name"] == $nodeName) {
                    $currentNode = &$children[$k];
                    $foundChild = true;
                    break;
                }
            }
            // If we don't already have a child node for this branch, create it.
            if (!$foundChild) {
                $childNode = array();
                $childNode['name'] = $nodeName;
                $childNode['cat'] = $cat_level;
                $childNode['children'] = array();
                array_push($children, $childNode);
                // Get new child pointer
                for ($k = 0; $k < count($children); $k++) {
                    if ($children[$k]["name"] == $nodeName) {
                        $currentNode = &$children[$k];
                        break;
                    }
                }
            }
        } else {
            // Reached the end of the sequence; create a leaf node.
            $childNode = array();
            $childNode['name'] = $nodeName;
            $childNode['cat'] = $cat_level;
            $childNode['link_pdb'] = $nodeName;
            $childNode['children'] = "";
            array_push($children, $childNode);
        }
    }
}

$protein_id=0;
while ( $row = mysqli_fetch_array ( $results, MYSQLI_ASSOC ) ) {
    piechart_data_parser($row, $count_nbprot_domain, $count_nbprot_fold);
    piechart_protname_data_parser($row, $count_nbprot_protname);
    piechart_pfam_data_parser($row, $count_nbprot_pfam);
    sunburst_data_parser($row, $counter);
    treeview_data_parser($row, $root, $added_species);
}

//AFTER PARSER SUNBUSRT
$graphic_sunburst_data = "";
foreach ($counter as $key => $value) {
    $graphic_sunburst_data .= "['" . $key . "'," . ($value / $total_objects_sunburst) . "],";
}
$graphic_sunburst_data = rtrim($graphic_sunburst_data, ",");

//DISPLAY graphic_domain
echo "<div style='width:100%;display:inline-block;'>";
echo "<div style='width:49%;height:360px;display:inline-block;margin-bottom:50px;margin-right:5px;float:left;'>";
//include("lectinpred_graphic_domain.php");
include("graphic_domain.php");
echo "</div>";

//DISPLAY graphic_sunburst
echo "<div style='width:49%;height:360px;display:inline-block;margin-bottom:50px;float:left;margin-right:5px;'>";
include("graphic_sunburst.php");
echo "</div>";
echo "</div>";

//DISPLAY graphic_domain
echo "<div style='width:100%;display:inline-block;'>";
echo "<div style='width:49%;height:360px;display:inline-block;margin-bottom:50px;margin-right:5px;float:left;'>";
include("graphic_protname.php");
echo "</div>";

//DISPLAY graphic_domain
echo "<div style='width:49%;height:360px;display:inline-block;margin-bottom:50px;margin-right:5px;float:left;'>";
include("graphic_pfam.php");
echo "</div>";
echo "</div>";

//DISPLAY TREEGRAPH
echo "<div id='tree-container_container' style='width:100%;display:inline-block;margin-bottom:5px;margin-top:10px;'>";
?>
<div class="div-border-title">
	Taxonomic tree (Hold left CTRL to drag and CTRL+SCROLL to zoom)
</div>
<div class="div-border" id="tree-container" style="width:100%; height:auto;display:inline-block;">
</div>
<?php
include("graphic_treeview_data.php");
include("graphic_treeview_large.php");
echo "</div>";
echo "</div>";
?>

