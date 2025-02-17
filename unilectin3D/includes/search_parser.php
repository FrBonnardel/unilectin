<?php
include ("page_bar.php");
include ("connect.php");
$connexion = connectdatabase();

$activepage = 1;
$request_begin = 1;
if (isset($_POST['activepage'])) {
  $activepage = $_POST['activepage'];
}
include("create_request.php");
$POST_ARRAY=$_POST;
$rwhere = create_request($POST_ARRAY,0);

echo "<button  class='btn btn-success' role='button' data-toggle='collapse' data-target='#explore_graphics_panel' style='width:100%;margin-bottom:20px;height:30px;'>View the exploration graphs</button>";
echo "<div id='explore_graphics_panel' class='collapse' style=''>";
include("load_graphics.php");
echo "</div>";

if ($_POST['results_type']=="lectins") {
  $request = "SELECT uniprot, max(resolution) AS resolution, origin, class, family, ";
  $request .= "species, fold, group_concat(distinct monosac separator ',') AS sucres_list, group_concat(pdb separator ',') AS pdb_list, iupac ";
  $request .= " from lectin_view  where uniprot != '' $rwhere group by fold, uniprot ";
  //echo $request;
  $results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
  $nb_regions = mysqli_num_rows($results);
  $nbresultbypage = 20;
  $windownumberpage = ceil(intval($nb_regions) / $nbresultbypage);
  $first_display_region = (($activepage - 1) * $nbresultbypage) + 1;
  $last_display_region = min(($activepage * $nbresultbypage), $nb_regions);
  $request .= " LIMIT " . (($activepage - 1) * $nbresultbypage) . " , " . $nbresultbypage;
  if($nb_regions == 0){
    exit("No results with current filters");
  }
  //echo $request;
  echo "<div style='line-height:30px;height:40px;border:1px solid lightgrey;padding:5px;background-image: linear-gradient(to bottom,#fff 0,#e0e0e0 100%);'>protein list $first_display_region to $last_display_region out of $nb_regions</div>";
  $results = mysqli_query ( $connexion, $request ) or die ( "SQL Error:<br>$request<br>" . mysqli_error ( $connexion ) );
  while ( $row = mysqli_fetch_array ( $results, MYSQLI_ASSOC ) ) {
    include("viewer_light.php");
  }
  page_bar($activepage, $windownumberpage);
}
if ($_POST['results_type']=="structures") {
  $request = "SELECT * FROM lectin_view WHERE 1 ";
  $request .= $rwhere . " ORDER BY pdb";
  $results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
  //echo $request;
  $nb_regions = mysqli_num_rows($results);
  $nbresultbypage = 50;
  $windownumberpage = ceil(intval($nb_regions) / $nbresultbypage);
  $first_display_region = (($activepage - 1) * $nbresultbypage) + 1;
  $last_display_region = min(($activepage * $nbresultbypage), $nb_regions);
  $request .= " LIMIT " . (($activepage - 1) * $nbresultbypage) . " , " . $nbresultbypage;
  if($nb_regions == 0){
    exit("No results with current filters");
  }
  echo "<div style='line-height:30px;height:40px;border:1px solid lightgrey;padding:5px;background-image: linear-gradient(to bottom,#fff 0,#e0e0e0 100%);'>lectin list $first_display_region to $last_display_region out of $nb_regions</div>";
  $results = mysqli_query ( $connexion, $request ) or die ( "SQL Error:<br>$request<br>" . mysqli_error ( $connexion ) );
  $source = $_POST['source'];
  while ( $row = mysqli_fetch_array ( $results, MYSQLI_ASSOC ) ) {
    include("structure_viewer_light.php");
  }
  page_bar($activepage, $windownumberpage);
}
?>

