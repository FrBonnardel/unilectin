<?php
//echo '<pre>'; print_r($_GET); echo '</pre>';
if ($_GET['format'] == "txt") {
    //CONNECT SQL
    include ("includes/connect.php");
    $connexionBIG = connectdatabaseBIG();
    $request = "SELECT species, species_id FROM lectinpred_view GROUP BY species_id ORDER BY species";
    $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
        $links .= $row['species'] . "\n";
    }
    echo $links;
} else {
    include($_SERVER['DOCUMENT_ROOT'] . '/predict/header.php');
    $links = "<title>LectomeXplore species index</title>\n";
    echo ("<label>LectomeXplore species index</label><br>");
    $request = "SELECT superkingdom, kingdom, phylum, species, species_id, ROUND(MAX(score),2) as maxs, ROUND(MIN(score),2) as mins FROM lectinpred_view GROUP BY species_id ORDER BY superkingdom, kingdom, phylum, species";
    $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
        $links .= "<a href='/predict/list_predicted_lectins?species_id={$row['species_id']}&species={$row['species']}'>{$row['superkingdom']} // {$row['kingdom']} // {$row['phylum']} // {$row['species']} [ {$row['mins']} - {$row['maxs']} ]</a><br>\n";
    }
    echo $links;
    include($_SERVER['DOCUMENT_ROOT'] . '/predict/footer.php');
}
?>