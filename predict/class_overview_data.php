
<title>Predict new classification overview</title>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/predict/header.php"); ?>
<textarea>
<?php

$request = "SELECT fold, origine, new_class FROM lectin_view WHERE new_class != '' GROUP BY fold, origine, new_class ORDER BY fold, origine, new_class";
$results = mysqli_query($connexion, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexion));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    $row['fold'] = str_replace('(','',$row['fold']);
    $row['fold'] = str_replace(')','',$row['fold']);
    $row['fold'] = str_replace(' ','_',$row['fold']);
    echo $row['fold'].'\t'.$row['origine'].'\t'.$row['new_class'].'\t0\n';
}

$request = "SELECT fold, kingdom, domain, count(distinct(protein.protein_id)) FROM lectinpred_protein protein left JOIN lectinpred_species species ON (protein.species_id = species.species_id) 
left JOIN lectinpred_aligned_domains domains ON (protein.protein_id = domains.protein_id) LEFT JOIN test_fold ON (domains.domain=test_fold.cname)
 WHERE kingdom = 'Fungi' GROUP BY fold, kingdom, domain ORDER BY fold, kingdom, domain";
$results = mysqli_query($connexionBIG, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    echo $row['fold'].'\t'.'Fungal and yeast lectins'.'\t'.$row['domain'].'\t1\n';
}

$request = "SELECT fold, kingdom, domain, count(distinct(protein.protein_id)) FROM lectinpred_protein protein left JOIN lectinpred_species species ON (protein.species_id = species.species_id) 
left JOIN lectinpred_aligned_domains domains ON (protein.protein_id = domains.protein_id) LEFT JOIN test_fold ON (domains.domain=test_fold.cname)
 WHERE kingdom = 'Viridiplantae' GROUP BY fold, kingdom, domain ORDER BY fold, kingdom, domain";
$results = mysqli_query($connexionBIG, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    echo $row['fold'].'\t'.'Plant lectins'.'\t'.$row['domain'].'\t1\n';
}

$request = "SELECT fold, kingdom, domain, count(distinct(protein.protein_id)) FROM lectinpred_protein protein left JOIN lectinpred_species species ON (protein.species_id = species.species_id) 
left JOIN lectinpred_aligned_domains domains ON (protein.protein_id = domains.protein_id) LEFT JOIN test_fold ON (domains.domain=test_fold.cname)
 WHERE kingdom = 'Metazoa' GROUP BY fold, kingdom, domain ORDER BY fold, kingdom, domain";
$results = mysqli_query($connexionBIG, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    echo $row['fold'].'\t'.'Animal lectins'.'\t'.$row['domain'].'\t1\n';
}

$request = "SELECT fold, superkingdom, domain, count(distinct(protein.protein_id)) FROM lectinpred_protein protein left JOIN lectinpred_species species ON (protein.species_id = species.species_id) 
left JOIN lectinpred_aligned_domains domains ON (protein.protein_id = domains.protein_id) LEFT JOIN test_fold ON (domains.domain=test_fold.cname)
 WHERE superkingdom = 'Bacteria' GROUP BY fold, superkingdom, domain ORDER BY fold, superkingdom, domain";
$results = mysqli_query($connexionBIG, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    echo $row['fold'].'\t'.'Bacterial lectins'.'\t'.$row['domain'].'\t1\n';
}

$request = "SELECT fold, superkingdom, domain, count(distinct(protein.protein_id)) FROM lectinpred_protein protein left JOIN lectinpred_species species ON (protein.species_id = species.species_id) 
left JOIN lectinpred_aligned_domains domains ON (protein.protein_id = domains.protein_id) LEFT JOIN test_fold ON (domains.domain=test_fold.cname)
 WHERE superkingdom = 'Viruses' GROUP BY fold, superkingdom, domain ORDER BY fold, superkingdom, domain";
$results = mysqli_query($connexionBIG, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    echo $row['fold'].'\t'.'Virus lectins'.'\t'.$row['domain'].'\t1\n';
}

$request = "SELECT fold, domain, count(distinct(protein.protein_id)) FROM lectinpred_protein protein left JOIN lectinpred_species species ON (protein.species_id = species.species_id) 
left JOIN lectinpred_aligned_domains domains ON (protein.protein_id = domains.protein_id) LEFT JOIN test_fold ON (domains.domain=test_fold.cname)
 WHERE kingdom = 'Alveolata' OR kingdom = 'Stramenopiles' GROUP BY fold, domain ORDER BY fold, domain";
$results = mysqli_query($connexionBIG, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    echo $row['fold'].'\t'.'Protist lectins'.'\t'.$row['domain'].'\t1\n';
}

?>
</textarea>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/predict/footer.php"); ?>

