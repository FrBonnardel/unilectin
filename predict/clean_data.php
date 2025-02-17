<?php include($_SERVER['DOCUMENT_ROOT']."/predict/header.php"); ?>
<?php
$request = "update lectinpred_species set superkingdom = 'unclassified' where superkingdom = 'Unclassified' or superkingdom = 'unclassified sequences' or superkingdom = 'other sequences'";
$results = mysqli_query ( $connexion, $request ) or die ( "SQL Error:<br>$request<br>" . mysqli_error ( $connexion ) );
$request = "update lectinpred_species set kingdom = superkingdom where superkingdom like '%andidat%' and kingdom = 'unclassified'";
$results = mysqli_query ( $connexion, $request ) or die ( "SQL Error:<br>$request<br>" . mysqli_error ( $connexion ) );
?>

<?php include($_SERVER['DOCUMENT_ROOT']."/predict/footer.php"); ?>