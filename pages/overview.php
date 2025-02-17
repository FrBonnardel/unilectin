<title>Overview</title>
<?php include($_SERVER['DOCUMENT_ROOT']."/header.php"); ?>

<?php
$request = "select count(distinct(pdb)) as num_rows from lectin_view";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$total_lectin = $row['num_rows'];

$request = "select count(distinct(pdb)) as num_rows from lectin_view where sequence != '' ";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$total_lectin_with_glycan = $row['num_rows'];

$request = "select count(distinct(sequence)) as num_rows from lectin_view where sequence != '' ";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$total_glycan = $row['num_rows'];

$request = "select uniparc from lectin_view WHERE uniparc != '' GROUP BY uniparc";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$total_seq = mysqli_num_rows($results);

$request = "select count(distinct(title)) as num_ref from _bibliography LEFT JOIN _ref ON(_bibliography.id = _ref.bib_id) LEFT JOIN _molecule ON(_ref.molecule_id = _molecule.id) WHERE database_id = 92 and journal != '' and journal not like '%To be published%'";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$total_ref = $row['num_ref'];

$request = "SELECT dt as last_update FROM _spec_lectine ORDER BY dt DESC LIMIT 1";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$last_update = $row['last_update'];

$request = "select count(protein_id) as num_rows from lectinpred_protein";
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$pred_total_lectin = $row['num_rows'];

$request = "select count(distinct(species)) as num_rows from lectinpred_species";
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$pred_total_species = $row['num_rows'];

$request = "select count(distinct(species)) as num_rows from lectinpred_view WHERE score > 0.25";
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$pred_total_species_filtered = $row['num_rows'];

$request = "select count(distinct(protein_id)) as num_rows from lectinpred_view WHERE score > 0.25";
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$pred_total_lectin_filtered = $row['num_rows'];

$request = "SELECT UPDATE_TIME FROM information_schema.tables WHERE TABLE_SCHEMA = 'unilecticvbig' AND TABLE_NAME = 'lectinpred_protein'";
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$pred_last_update = $row['UPDATE_TIME'];
?>

<div class="div-border-title">
    Global statistics
</div>
<div class="div-border" style='width:100%;'>
    <div  style='padding:5px;font-size: 18px; text-align: justify; text-justify: inter-word;'>
        <span style='font-weight:bold;'>
      How many curated lectins and structures ?
    </span>
        <br>
        <span style='font-weight:bold;'><?php echo $total_lectin;?></span> 3D XRay structures (<span style='font-weight:bold;'><?php echo $total_lectin_with_glycan." ";?></span> with interacting glycan), <span style='font-weight:bold;'><?php echo $total_seq." ";?></span> distinct lectin sequences, <span style='font-weight:bold;'><?php echo $total_glycan." ";?></span> distinct glycans, <span style='font-weight:bold;'><?php echo $total_ref." ";?></span> articles
        <br>
        Last update the <?php echo $last_update." ";?>
        <br>
        <span style='font-weight:bold;'>
    How many predicted lectins ?
  </span>
        <br>
        <span style='font-weight:bold;'><?php echo $pred_total_lectin; ?></span> predicted lectins and <span style='font-weight:bold;'><?php echo $pred_total_lectin_filtered; ?></span> filtered lectins
        <br>
        <span style='font-weight:bold;'><?php echo $pred_total_species; ?></span> distinct species strand and <span style='font-weight:bold;'><?php echo $pred_total_species_filtered; ?></span> filtered species strand
        <br>
        Last update the <?php echo $pred_last_update . " "; ?>
        <br>
    </div>
</div>


<table style='width: 100%;margin-top:30px;' class='manage_tables'>
<thead><tr>
<td>species</td>
<td>curated lectin structures count</td>
<td>curated lectins count</td>
</tr></thead>
<tbody>

<?php
$request = "select espece, count(molecule_id) as nb_lectin_structures, count(distinct(uniparc)) as nb_lectins from lectin_view group by espece order by espece";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);

while ( $row = mysqli_fetch_array ( $results, MYSQLI_ASSOC ) ) {
    echo "<tr>";
    echo "<td>{$row['espece']}</td>";
    echo "<td>{$row['nb_lectin_structures']}</td>";
    echo "<td>{$row['nb_lectins']}</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";

?>

<table style='width: 100%;margin-top:30px;' class='manage_tables'>
    <thead><tr>
        <td>phylum (ie. species)</td>
    </tr></thead>
<tbody>

<?php
$request = "select phylum, species from lectinpred_species species group by phylum order by phylum";
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);

while ( $row = mysqli_fetch_array ( $results, MYSQLI_ASSOC ) ) {
    echo "<tr>";
    echo "<td>{$row['phylum']}      (ie. {$row['species']})</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";

?>

<?php include($_SERVER['DOCUMENT_ROOT']."/footer.php"); ?>