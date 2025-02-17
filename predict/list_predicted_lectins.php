<?php
//echo '<pre>'; print_r($_GET); echo '</pre>';
if ($_GET['species_id'] == "") {
    echo "<a href='./list_predicted_species'>All species</a>";
} else if ($_GET['format'] == "txt") {
    //CONNECT SQL
    include ("includes/connect.php");
    $connexionBIG = connectdatabaseBIG();
    $request = "SELECT uniprot FROM lectinpred_view WHERE score > 0.25 AND species_id = '{$_GET['species_id']}'";
    $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
        $links .= $row['uniprot'] . "\n";
    }
    echo $links;
} else {
    include($_SERVER['DOCUMENT_ROOT'] . '/predict/header.php');
    $links = "<title>LectomeXplore protein index for {$_GET['species']}</title>\n";
    echo ("<label>LectomeXplore protein index for {$_GET['species']}</label><br>");
    $request = "SELECT protein_ac, protein_id, domains_id, domain, ROUND(score, 2) as score FROM lectinpred_view WHERE species_id = '{$_GET['species_id']}' ORDER BY score DESC";
    $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
        $links .= "<a href='/predict/display?protein_id={$row['protein_id']}&protein_ac={$row['protein_ac']}&domains_id={$row['domains_id']}'>{$row['protein_ac']} identified as {$row['domain']} scored {$row['score']}</a><br>\n";
    }
    echo $links;
    include($_SERVER['DOCUMENT_ROOT'] . '/predict/footer.php');
}
?>