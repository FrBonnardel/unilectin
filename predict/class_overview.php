<?php include($_SERVER['DOCUMENT_ROOT'] . "/predict/header.php"); ?>

<title>Browse the predicted lectins by fold and origin</title>
<center>
    <label class="title_main" href='./'>Browse the predicted lectins by fold and origin</label>
</center>
<p>The blue buttons correspond to the predicted lectin classes with the presence of 3D structure(s) in UniLectin3D and the Green buttons represent the predicted lectin classes available in LectomeXplore (for a score > 0.25)</p>
<?php
$info=array();
$predict_info=array();

$request = "SELECT DISTINCT(fold) FROM lectin_view ORDER BY fold";
$results = mysqli_query($connexion, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexion));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    $fold = str_replace('/','',$row['fold']);
    $info[$fold]=array();
    $predict_info[$fold]=array();
}

echo "<table style='font-size: 10px;'>";
echo "<tr><td></td>";
$request = "SELECT DISTINCT(origin) FROM lectin_view ORDER BY origin";
$results = mysqli_query($connexion, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexion));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    echo "<td>{$row['origin']}</td>";
    foreach($info as $fold => $value){
        $info[$fold][$row['origin']]=array();
        $predict_info[$fold][$row['origin']]=array();
    }
}
echo "</tr>";

$request = "SELECT fold, origin, class FROM lectin_view WHERE class != '' GROUP BY fold, origin, class ORDER BY fold, origin, class";
$results = mysqli_query($connexion, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexion));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    $fold = str_replace('/','',$row['fold']);
    array_push($info[$fold][$row['origin']], $row['class']);
}

$request = "SELECT fold, kingdom, domain, count(distinct(protein.protein_id)) FROM lectinpred_protein protein left JOIN lectinpred_species species ON (protein.species_id = species.species_id) 
left JOIN lectinpred_aligned_domains domains ON (protein.protein_id = domains.protein_id) 
 WHERE kingdom = 'Fungi' AND score > 0.25 GROUP BY fold, kingdom, domain ORDER BY fold, kingdom, domain";
$results = mysqli_query($connexionBIG, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    array_push($predict_info[$row['fold']]['Fungal and yeast lectins'], $row['domain']);
}

$request = "SELECT fold, kingdom, domain, count(distinct(protein.protein_id)) FROM lectinpred_protein protein left JOIN lectinpred_species species ON (protein.species_id = species.species_id) 
left JOIN lectinpred_aligned_domains domains ON (protein.protein_id = domains.protein_id) 
 WHERE kingdom = 'Viridiplantae' AND score > 0.25 GROUP BY fold, kingdom, domain ORDER BY fold, kingdom, domain";
$results = mysqli_query($connexionBIG, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    array_push($predict_info[$row['fold']]['Plant lectins'], $row['domain']);
}

$request = "SELECT fold, kingdom, domain, count(distinct(protein.protein_id)) FROM lectinpred_protein protein left JOIN lectinpred_species species ON (protein.species_id = species.species_id) 
left JOIN lectinpred_aligned_domains domains ON (protein.protein_id = domains.protein_id) 
 WHERE kingdom = 'Metazoa' AND score > 0.25 GROUP BY fold, kingdom, domain ORDER BY fold, kingdom, domain";
$results = mysqli_query($connexionBIG, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    array_push($predict_info[$row['fold']]['Animal lectins'], $row['domain']);
}

$request = "SELECT fold, superkingdom, domain, count(distinct(protein.protein_id)) FROM lectinpred_protein protein left JOIN lectinpred_species species ON (protein.species_id = species.species_id) 
left JOIN lectinpred_aligned_domains domains ON (protein.protein_id = domains.protein_id) 
 WHERE superkingdom = 'Bacteria' AND score > 0.25 GROUP BY fold, superkingdom, domain ORDER BY fold, superkingdom, domain";
$results = mysqli_query($connexionBIG, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    array_push($predict_info[$row['fold']]['Bacterial lectins'], $row['domain']);
}

$request = "SELECT fold, superkingdom, domain, count(distinct(protein.protein_id)) FROM lectinpred_protein protein left JOIN lectinpred_species species ON (protein.species_id = species.species_id) 
left JOIN lectinpred_aligned_domains domains ON (protein.protein_id = domains.protein_id) 
 WHERE superkingdom = 'Viruses' AND score > 0.25 GROUP BY fold, superkingdom, domain ORDER BY fold, superkingdom, domain";
$results = mysqli_query($connexionBIG, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    array_push($predict_info[$row['fold']]['Virus lectins'], $row['domain']);
}

$request = "SELECT fold, domain, count(distinct(protein.protein_id)) FROM lectinpred_protein protein left JOIN lectinpred_species species ON (protein.species_id = species.species_id)
left JOIN lectinpred_aligned_domains domains ON (protein.protein_id = domains.protein_id) 
 WHERE superkingdom = 'Eukaryota' AND score > 0.25 AND kingdom != 'Metazoa' AND kingdom != 'Viridiplantae' AND kingdom != 'Fungi' GROUP BY fold, domain ORDER BY fold, domain";
$results = mysqli_query($connexionBIG, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    array_push($predict_info[$row['fold']]['Protist, parasites, mold'], $row['domain']);
}

//echo '<pre>'; print_r($predict_info); echo '</pre>';

//$cellrequest = "SELECT class FROM lectin_view WHERE origine = '$origine' AND fold = '$fold' GROUP BY class";
//$cellresults = mysqli_query($connexion, $cellrequest) or die ("SQL Error:<br>$cellrequest<br>" . mysqli_error($connexion));

foreach($info as $fold => $foldinfo){
    $fold_tmp = str_replace('_',' ',$fold);
    $fold_tmp = str_replace('/','',$fold_tmp);
    echo "<tr>";
    echo "<td>$fold_tmp</td>";
    foreach($foldinfo as $origine => $cnames){
        echo "<td>";
        if(! empty($cnames)){
            foreach($cnames as $cname) {
                echo "<a class='btn btn-primary btn-sm'  style='width:160px;white-space: normal;word-wrap: break-word;font-size: 10px;' target='_blank' 
                href='./search?domain=$cname' >$origine<br>$cname</a>";
            }
        }else if(! empty($predict_info[$fold][$origine])){
            $cnames = $predict_info[$fold][$origine];
            foreach($cnames as $cname) {
                $convert = [];
                $convert['Fungal and yeast lectins'] = 'kingdom=Fungi';
                $convert['Plant lectins'] = 'kingdom=Viridiplantae';
                $convert['Animal lectins'] = 'kingdom=Metazoa';
                $convert['Bacterial lectins'] = 'superkingdom=Bacteria';
                $convert['Virus lectins'] = 'superkingdom=Viruses';
                $convert['Protist, parasites, mold'] = 'superkingdom=Eukaryota&kingdom=None';
                $convert['Archaeal lectins'] = 'superkingdom=Archaea';
                echo "<a class='btn btn-success btn-sm'  style='width:160px;white-space: normal;word-wrap: break-word;font-size: 10px;' target='_blank' 
                href='./search?domain=$cname&{$convert[$origine]}' >$origine<br>$cname</a>";
            }
        }
        echo "</td>";
    }
    echo "</tr>";
}
echo "</table>";
?>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/predict/footer.php"); ?>

