<script>
    _paq.push(['trackSiteSearch', "<?php echo $_GET['keyword'] ?>", false, false]);
</script>

<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113302841-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-113302841-1');
</script>
<!-- End Google Analytics -->
<?php
$keyword = $_GET['keyword'];
if (strlen($keyword) < 3){
    echo "<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>";
    echo "Please set a longer keyword";
    echo "</div>";
    return(-1);
}
include ($_SERVER['DOCUMENT_ROOT']."/unilectin3D/includes/connect.php");
$connexion = connectdatabase();
include ($_SERVER['DOCUMENT_ROOT']."/predict/includes/connect.php");
$connexionBIG = connectdatabaseBIG();

$activepage = 1;
$request_begin = 1;
$request = "SELECT * FROM lectin_view ";
$rwhere="";
if ($keyword != "") {
	$rwhere .= " WHERE species LIKE '%$keyword' OR species LIKE '%$keyword %'";
	$rwhere .= " OR iupac LIKE '%$keyword' OR iupac LIKE '%$keyword %'";
	$rwhere .= " OR monosac LIKE '%$keyword' OR monosac LIKE '%$keyword %'";
	$rwhere .= " OR origin LIKE '%$keyword' OR origin LIKE '%$keyword %'";
	$rwhere .= " OR fold LIKE '%$keyword' OR fold LIKE '%$keyword %'";
    $rwhere .= " OR class LIKE '%$keyword' OR class LIKE '%$keyword %'";
	$rwhere .= " OR family LIKE '%$keyword' OR family LIKE '%$keyword %'";
	$rwhere .= " OR pdb LIKE '%$keyword' OR pdb LIKE '%$keyword %'";
	$rwhere .= " OR uniprot LIKE '%$keyword' OR uniprot LIKE '%$keyword %'";
	$rwhere .= " OR title LIKE '%$keyword' OR title LIKE '%$keyword %'";
    $rwhere .= " OR protein_name LIKE '%$keyword' OR protein_name LIKE '%$keyword %'";
}
$request .= $rwhere . " GROUP BY pdb";

$request_tmp = "select count(lectin_view.lectin_id) as num_rows from lectin_view WHERE monosac LIKE '%$keyword%'";
$results = mysqli_query($connexion, $request_tmp) or die("SQL Error:<br>$request_tmp<br>" . mysqli_error($connexion));
$row     = mysqli_fetch_array($results, MYSQLI_ASSOC);
$total_sucres = $row['num_rows'];

$request_tmp = "select count(lectin_view.lectin_id) as num_rows from lectin_view WHERE iupac LIKE '%$keyword%'";
$results = mysqli_query($connexion, $request_tmp) or die("SQL Error:<br>$request_tmp<br>" . mysqli_error($connexion));
$row     = mysqli_fetch_array($results, MYSQLI_ASSOC);
$total_sequence = $row['num_rows'];

$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$nb_regions = mysqli_num_rows($results);
echo "<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>";
echo "Found lectin structure(s): $nb_regions ($total_sucres with matching monosaccharide and $total_sequence with matching IUPAC)";
echo "<br><button class='btn btn-primary' data-toggle='collapse' href='#unilectin3d' data-parent='#accordion'>View $nb_regions structure(s) in UniLectin3D</button>";
$results_unilectin3d = $results;

//SEARCH IN PROPLEC
$request = "SELECT * FROM propeller_view ";
$rwhere='';
if ($keyword != "") {
    $rwhere .= " WHERE ( name LIKE '%$keyword' OR name LIKE '%$keyword %' ";
    $rwhere .= " OR species LIKE '%$keyword' OR species LIKE '%$keyword %'";
    $rwhere .= " OR domain LIKE '%$keyword' OR domain LIKE '%$keyword %'";
    $rwhere .= " OR uniprot LIKE '%$keyword' OR uniprot LIKE '%$keyword %'";
    $rwhere .= " OR ncbi LIKE '%$keyword' OR ncbi LIKE '%$keyword %' ) ";
}
$request .= $rwhere;
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
$nb_regions = mysqli_num_rows($results);
echo "<button class='btn btn-primary' data-toggle='collapse' href='#propeller' data-parent='#accordion'>View $nb_regions lectin(s) in PropLec</button>";
$results_propeller = $results;

//SEARCH IN TREFOIL
$request = "SELECT * FROM trefoil_view ";
$rwhere='';
if ($keyword != "") {
    $rwhere .= " WHERE ( name LIKE '%$keyword' OR name LIKE '%$keyword %' ";
    $rwhere .= " OR species LIKE '%$keyword' OR species LIKE '%$keyword %'";
    $rwhere .= " OR domain LIKE '%$keyword' OR domain LIKE '%$keyword %'";
    $rwhere .= " OR uniprot LIKE '%$keyword' OR uniprot LIKE '%$keyword %'";
    $rwhere .= " OR ncbi LIKE '%$keyword' OR ncbi LIKE '%$keyword %' ) ";
}
$request .= $rwhere;
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
$nb_regions = mysqli_num_rows($results);
echo "<button class='btn btn-primary' data-toggle='collapse' href='#trefoil' data-parent='#accordion'>View $nb_regions lectin(s) in TrefoilLec</button>";
$results_trefoil = $results;

//SEARCH IN PREDICT
$request = "SELECT * FROM lectinpred_view ";
$rwhere='';
if ($keyword != "") {
    $rwhere .= " WHERE ( name LIKE '%$keyword' OR name LIKE '%$keyword %' ";
    $rwhere .= " OR species LIKE '%$keyword' OR species LIKE '%$keyword %'";
    $rwhere .= " OR domain LIKE '%$keyword' OR domain LIKE '%$keyword %'";
    $rwhere .= " OR uniprot LIKE '%$keyword' OR uniprot LIKE '%$keyword %'";
    $rwhere .= " OR alt_ac LIKE '%$keyword' OR alt_ac LIKE '%$keyword %' ) ";
}
$request .= $rwhere;
$results = mysqli_query ( $connexionBIG, $request ) or die ( "SQL Error:<br>$request<br>" . mysqli_error ( $connexionBIG ) );
$nb_regions = mysqli_num_rows($results);
echo "<button class='btn btn-primary' data-toggle='collapse' href='#predict' data-parent='#accordion'>View $nb_regions lectin(s) in LectomeXplore</button>";

//END BUTTON

echo "</div>";
echo "<div class='panel-group' id='accordion'><div class='panel panel-default'>";

//UNILECTIN3D
echo "<div id='unilectin3d' class='panel-collapse collapse in' style=''>";
if(mysqli_num_rows($results_unilectin3d) == 0){
    print("<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>UniLectin3D: No results with current filters</div>");
}
$i=1;
while ( $row = mysqli_fetch_array ( $results_unilectin3d, MYSQLI_ASSOC ) ) {
    echo "<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>$i - {$row['pdb']} {$row['title']}<br>F={$row['fold']} > C={$row['class']} > F={$row['family']} ; O={$row['origin']}; {$row['iupac']}<br>";
    echo "<a class='btn btn-success' style='width:200px;' href='https://unilectin.eu/unilectin3D/display_structure?pdb={$row['pdb']}'>Go to the structure {$row['pdb']}</a>";
    echo "<a class='btn btn-success' style='width:200px;' href='https://unilectin.eu/unilectin3D/display_lectin?uniparc={$row['uniprot']}'>Go to the lectin {$row['uniprot']}</a>";
    echo "</div>";
    $i+=1;
}
echo "</div>";

//PROPELLER
echo "<div id='propeller' class='panel-collapse collapse' style=''>";
if(mysqli_num_rows($results_propeller) == 0){
    print("<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>PropLec: No results with current filters</div>");
}
$i=1;
while ( $row = mysqli_fetch_array ( $results_propeller, MYSQLI_ASSOC )  AND $i < 1000) {
    echo "<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>PropLec $i - {$row['name']}<br>{$row['species']} {$row['phylum']} {$row['kingdom']}<br>";
    echo "<a class='btn btn-success' style='width:200px;' href='https://unilectin.eu/propeller/display?protein_id={$row['protein_id']}&name={$row['name']}' target='_blank'>Go to the lectin {$row['name']}</a>";
    echo "</div>";
    $i+=1;
}
echo "</div>";

//TREFOIL
echo "<div id='trefoil' class='panel-collapse collapse' style=''>";
if(mysqli_num_rows($results_trefoil) == 0){
    print("<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>TrefoilLec: No results with current filters</div>");
}
$i=1;
while ( $row = mysqli_fetch_array ( $results_trefoil, MYSQLI_ASSOC ) AND $i < 1000) {
    echo "<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>Trefoil $i - {$row['name']}<br>{$row['species']} {$row['phylum']} {$row['kingdom']}<br>";
    echo "<a class='btn btn-success' style='width:200px;' href='https://unilectin.eu/trefoil/display?protein_id={$row['protein_id']}&name={$row['name']}' target='_blank'>Go to the lectin {$row['name']}</a>";
    echo "</div>";
    $i+=1;
}
echo "</div>";

//PREDICT
echo "<div id='predict' class='panel-collapse collapse' style=''>";
if(mysqli_num_rows($results) == 0){
    print("<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>LectomeXplore: No results with current filters</div>");
}
$i=1;
while ( $row = mysqli_fetch_array ( $results, MYSQLI_ASSOC )  AND $i < 1000) {
    echo "<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>AllPred $i - {$row['uniprot']} {$row['name']}<br>{$row['species']} {$row['phylum']} {$row['kingdom']}<br>";
    echo "<a class='btn btn-success' style='width:200px;' href='https://unilectin.eu/predict/display?protein_id={$row['protein_id']}&uniprot={$row['uniprot']}' target='_blank'>Go to the lectin {$row['uniprot']}</a>";
    echo "</div>";
    $i+=1;
}
echo "</div>";

//END

echo "</div></div>";

?>


