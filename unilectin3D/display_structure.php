<title><?php echo $_GET['pdb']; ?> structure in UniLectin3D</title>

<script src="/js/angular.1.4.7.min.js?v=938456"></script>
<script src="/js/d3.v3.min.js?v=938456"></script>

<link rel="stylesheet" type="text/css" href="../css/LiteMol-plugin.css?v=938456">
<script src='/js/LiteMol-plugin.min.js?v=938456'></script>
<script src='/js/litemol-plugin-instance-pdbe.js?v=938456'></script>
<script src="/js/litemol_ligand.js?v=938456"></script>

<link rel="stylesheet" type="text/css" href="/css/pdb.sequence.viewer.min.css?v=938456">
<script src="/js/pdb.sequence.viewer.min.js?v=938456"></script>


<?php include($_SERVER['DOCUMENT_ROOT']."/unilectin3D/header.php"); ?>

<?
if($_GET['pdb']==""){
  exit("PDB ID unset");
}
$pdb = $_GET['pdb'];
$request = "SELECT * FROM lectin_view WHERE pdb = '$pdb'";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
while($row = mysqli_fetch_array ( $results, MYSQLI_ASSOC )){
  echo "<meta name='description' content='The lectin domain {$_GET['pdb']} from the {$row['fold']}, {$row['class']}, {$row['family']}, {$row['origin']}";
  if ($row['monosac'] != ""){
    echo " with the ligand {$row['monosac']} ";
  }
  echo ".'>";
  include($_SERVER['DOCUMENT_ROOT']."/unilectin3D/includes/structure_viewer.php");
}
?>

<?php include($_SERVER['DOCUMENT_ROOT']."/unilectin3D/footer.php"); ?>
