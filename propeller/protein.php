<?php
  include($_SERVER['DOCUMENT_ROOT'].'/header.php');
  $links="<title>UniLectin propeller protein index</title>\n";
  $request = "SELECT protein_id, name from lectinpropellerpred_protein ORDER BY name";
  $results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
  while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    $links.="<a href='https://www.unilectin.eu/propeller/display?protein_id={$row['protein_id']}&name={$row['name']}'>{$row['name']}</a>\n";
  }
  echo $links;
  include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
?>