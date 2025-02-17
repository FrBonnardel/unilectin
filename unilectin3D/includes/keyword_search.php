<?php
include ("page_bar.php");
include ("connect.php");
$connexion = connectdatabase();

$activepage = 1;
$request_begin = 1;
echo "<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>";
$request = "SELECT lectin_view.molecule_id, origine, fold, classe, new_class, famille, espece, pdb, sequence as sugar_sequence, uniparc, title FROM lectin_view LEFT JOIN _ref on(lectin_view.molecule_id = _ref.molecule_id) LEFT JOIN _bibliography ON(_ref.bib_id = _bibliography.id) ";
$keyword = $_GET['keyword'];
if ($keyword != "") {
	$rwhere .= " WHERE espece LIKE '%$keyword%'";
	$rwhere .= " OR sequence LIKE '%$keyword%'";
	$rwhere .= " OR sucres LIKE '%$keyword%'";
	$rwhere .= " OR origine LIKE '%$keyword%'";
	$rwhere .= " OR fold LIKE '%$keyword%'";
	$rwhere .= " OR classe LIKE '%$keyword%'";
    $rwhere .= " OR new_class LIKE '%$keyword%'";
	$rwhere .= " OR famille LIKE '%$keyword%'";
	$rwhere .= " OR pdb LIKE '%$keyword%'";
	$rwhere .= " OR uniparc LIKE '%$keyword%'";
	$rwhere .= " OR title LIKE '%$keyword%'";
}
$request .= $rwhere . " GROUP BY pdb";

$request_tmp = "select count(lectin_view.molecule_id) as num_rows from lectin_view WHERE sucres LIKE '%$keyword%'";
$results = mysqli_query($connexion, $request_tmp) or die("SQL Error:<br>$request_tmp<br>" . mysqli_error($connexion));
$row     = mysqli_fetch_array($results, MYSQLI_ASSOC);
$total_sucres = $row['num_rows'];

$request_tmp = "select count(lectin_view.molecule_id) as num_rows from lectin_view WHERE sequence LIKE '%$keyword%'";
$results = mysqli_query($connexion, $request_tmp) or die("SQL Error:<br>$request_tmp<br>" . mysqli_error($connexion));
$row     = mysqli_fetch_array($results, MYSQLI_ASSOC);
$total_sequence = $row['num_rows'];

$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$nb_regions = mysqli_num_rows($results);
echo "Found lectin structure(s): $nb_regions ($total_sucres with matching monosaccharide and $total_sequence with matching IUPAC)</div>";
$results = mysqli_query ( $connexion, $request ) or die ( "SQL Error:<br>$request<br>" . mysqli_error ( $connexion ) );
$source = $_POST['source'];
$i=1;
while ( $row = mysqli_fetch_array ( $results, MYSQLI_ASSOC ) ) {
  echo "<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>$i - {$row['title']}<br>F={$row['fold']} > C={$row['new_class']} > F={$row['famille']} ; O={$row['origine']}<br>";
  echo "<a class='btn btn-success' style='width:200px;' href='https://unilectin.eu/unilectin3D/display_structure?pdb={$row['pdb']}'>Go to the structure {$row['pdb']}</a>";
  echo "<a class='btn btn-success' style='width:200px;' href='https://unilectin.eu/unilectin3D/display_lectin?uniparc={$row['uniparc']}'>Go to the lectin {$row['uniparc']}</a>";
  echo "<button class='btn btn-success scroll_down_button' style='width:200px;' onclick=\"console.log($(this).position());$(this).parent().parent().animate({ scrollTop:  $(this).parent().parent().scrollTop() + 100 }, 'slow');\">Next</a>";
  echo "</div>";
  $i+=1;
}

//SEARCH IN PROPLEC
$request = "SELECT * FROM lectinpropellerpred_protein";
$keyword = $_GET['keyword'];
if ($keyword != "") {
    $rwhere .= " WHERE name LIKE '%$keyword%'";
    $rwhere .= " OR species LIKE '%$keyword%'";
}
$request .= $rwhere;
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$nb_regions = mysqli_num_rows($results);
echo "<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>Found propeller lectin(s): $nb_regions</div>";
$results = mysqli_query ( $connexion, $request ) or die ( "SQL Error:<br>$request<br>" . mysqli_error ( $connexion ) );
$source = $_POST['source'];
$i=1;
while ( $row = mysqli_fetch_array ( $results, MYSQLI_ASSOC ) ) {
    echo "<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>$i - {$row['name']}<br>{$row['species']} {$row['phylum']} {$row['kingdom']}<br>";
    echo "<a class='btn btn-success' style='width:200px;' href='https://unilectin.eu/propeller/display?protein_id={$row['protein_id']}&name={$row['name']}' target='_blank'>Go to the lectin {$row['name']}</a>";
    echo "<button class='btn btn-success scroll_down_button' style='width:200px;' onclick=\"console.log($(this).position());$(this).parent().parent().animate({ scrollTop:  $(this).parent().parent().scrollTop() + 100 }, 'slow');\">Next</a>";
    echo "</div>";
    $i+=1;
}

//SEARCH IN TREFOIL



?>


