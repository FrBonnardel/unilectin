<title><?php echo $_GET['uniprot']; ?> protein in UniLectin3D</title>

<script src="/js/angular.1.4.7.min.js"></script>
<script src="/js/d3.v3.min.js"></script>

<!-- minified component CSS and script -->
<link rel="stylesheet" href="/css/pdb.uniprot.viewer.min.css" />
<script src="/js/pdb.uniprot.viewer.min.js"></script>

<!-- PDB Prints CSS and script -->
<link rel="stylesheet" href="/css/pdb.prints.min.css" />
<script src="/js/pdb.prints.min.js"></script>
<style>
  .pdbeLogoCol{
    display:inline;
  }
</style>

<script src="/js/protvista.js"></script>
<link href="/css/protvista.css" rel="stylesheet" />
<script>var ProtVista= require('ProtVista');</script>

<?php include($_SERVER['DOCUMENT_ROOT']."/unilectin3D/header.php"); ?>


<?
if($_GET['uniprot']==""){
  exit("uniprot ID unset");
}
$uniprot = $_GET['uniprot'];
$request = "SELECT 
GROUP_CONCAT(DISTINCT(origin)) as origin, 
GROUP_CONCAT(DISTINCT(class)) as class, 
GROUP_CONCAT(DISTINCT(family)) as family, 
GROUP_CONCAT(DISTINCT(species)) as species, 
GROUP_CONCAT(DISTINCT(fold)) as fold, 
GROUP_CONCAT(DISTINCT(monosac)) as monosac, 
uniprot FROM lectin_view 
WHERE uniprot = '$uniprot' GROUP BY uniprot";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
while($row = mysqli_fetch_array ( $results, MYSQLI_ASSOC )){
  echo "<meta name='description' content='The lectin {$_GET['uniprot']} from the {$row['origin']}, {$row['class']}, {$row['family']}";
  if ($row['monosac'] != ""){
    echo " with the ligand {$row['monosac']} ";
  }
  echo ".'>";
?>
<?php
  include($_SERVER['DOCUMENT_ROOT']."/unilectin3D/includes/viewer.php");
}
?>

<?php include($_SERVER['DOCUMENT_ROOT']."/unilectin3D/footer.php"); ?>