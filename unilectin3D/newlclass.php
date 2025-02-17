
<title>UniLectin3D classification by kingdom and fold</title>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/unilectin3D/header.php"); ?>

<?php
$info=array();

$request = "SELECT DISTINCT(fold) FROM lectin_view ORDER BY fold";
$results = mysqli_query($connexion, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexion));
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    $row['fold'] = str_replace('(','',$row['fold']);
    $row['fold'] = str_replace(')','',$row['fold']);
    $row['fold'] = str_replace(' ','_',$row['fold']);
    $info[$row['fold']]=array();
    $predict_info[$row['fold']]=array();
}

echo "<table style='font-size: 10px;'>";
echo "<tr><td>lectin fold</td>";
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
    $row['fold'] = str_replace('(','',$row['fold']);
    $row['fold'] = str_replace(')','',$row['fold']);
    $row['fold'] = str_replace(' ','_',$row['fold']);
    array_push($info[$row['fold']][$row['origin']], $row['class']);
}

//$cellrequest = "SELECT new_class FROM lectin_view WHERE origine = '$origine' AND fold = '$fold' GROUP BY new_class";
//$cellresults = mysqli_query($connexion, $cellrequest) or die ("SQL Error:<br>$cellrequest<br>" . mysqli_error($connexion));

foreach($info as $fold => $foldinfo){
    $fold_tmp = str_replace('_',' ',$fold);
    echo "<tr>";
    echo "<td>$fold_tmp</td>";
    foreach($foldinfo as $origine => $cnames){
        echo "<td>";
        if(! empty($cnames)){
            foreach($cnames as $cname) {
                echo "<a class='btn btn-primary btn-sm'  style='width:160px;white-space: normal;word-wrap: break-word;font-size: 10px;' target='_blank' 
                href='/unilectin3D/search?new_class=$cname' >$origine<br>$cname</a>";
            }
        }
        echo "</td>";
    }
    echo "</tr>";
}
echo "</table>";
?>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/unilectin3D/footer.php"); ?>

