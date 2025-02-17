

<?php
//echo '<pre>'; print_r($_POST); echo '</pre>';

$query = str_replace(" ","+",$_POST['query']);

//$query = "query=SELECT+?type+(COUNT(?type)+AS+?typecount)+WHERE+{+?subject+a+?type.}+GROUP+by+?type+ORDER+by+desc(?typecount)";
$opts = array('http' =>
    array(
        'method'  => 'GET',
        'header'  => 'Accept: application/sparql-results+json'
    )
);
$context  = stream_context_create($opts);
$content = file_get_contents("http://129.194.71.205:9999/bigdata/namespace/kb/sparql?query=$query", false, $context);
//echo '<pre>'; print_r($content); echo '</pre>';
$content = json_decode($content, true);
//echo '<pre>'; print_r($content); echo '</pre>';
$glycans = $content['results']['bindings'];
foreach($glycans as $glycan){
    $uri = $glycan['glycan']['value'];
    //echo "<div id='glycan'>$uri</div>";
    $glycan_id = explode('_',$uri)[1];
    $glycan_db = end(explode('/',$uri));
    $glycan_db = explode('_',$glycan_db)[0];
    if($glycan_db == "GC"){echo "<a class='btn btn-secondary' href='https://glyconnect.expasy.org/browser/structures/$glycan_id' target='_blank' style='image-rendering:auto;transform:scale(0.8);-webkit-transform-origin: 0 0;'><label>glyconnect</label><br><img src='https://glyconnect.expasy.org/api/cartoon/$glycan_id?format=cfg' style='image-rendering:auto;'></a>";}
    if($glycan_db == "SB"){echo "<a class='btn btn-secondary' href='http://sugarbind.expasy.org/structures/$glycan_id' target='_blank' style='image-rendering:auto;transform:scale(0.8);-webkit-transform-origin: 0 0;'><label>sugarbind</label><br><img src='https://sugarbind.expasy.org/image/cartoon/$glycan_id?format=cfg'  style='image-rendering:auto;'></a>";}
    //https://glyconnect.expasy.org/api/structures/1288
}
?>

