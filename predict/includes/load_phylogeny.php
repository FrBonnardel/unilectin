<script src="/predict/js/heatmap.js?v=829376"></script>
<script>
    var myheatmap = Object.create(Heatmap);
    myheatmap.square = false;
</script>

<?php
include ("connect.php");
$connexionBIG = connectdatabaseBIG();

if ($_POST['superkingdom'] == '') {
    echo 'Please select a superkingdom';
    return(0);
}
$POST_ARRAY=$_POST;
include("create_request.php");
$predict_info=array();
$request = create_request($POST_ARRAY,0);
$request = str_replace(', ref_seq, match_seq', ', supclass, family', $request);
$request = str_replace(', alt_ac, gene, gene_begin, gene_end, cluster', '', $request);
$request .= " ORDER BY superkingdom, kingdom, phylum, supclass, family, species";
//echo '<pre>'.$request.'</pre>';
$results = mysqli_query($connexionBIG, $request) or die ("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
$domains=array();
while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    $superk = $row['superkingdom'];
    $kingdom = $row['kingdom'];
    $phylum = $row['phylum'];
    $s_group = $row['sgroup'];
    $domain = $row['domain'];
    $phylo = $superk.' > '.$kingdom;
    $supclass = $row['supclass'];
    $supclass =  substr($supclass, 0, 30);
    $family = $row['family'];
    $family =  substr($family, 0, 30);
    $species = $row['species'];
    if ($_POST['tax_level'] == 'phylum') {
        $phylo = $kingdom.' > '.$phylum;
    }
    if ($_POST['tax_level'] == 'class') {
        $phylo = $kingdom[0].' > '.$phylum.' > '.$supclass;
    }
    if ($_POST['tax_level'] == 'family') {
        $phylo = $kingdom[0].' > '.$phylum[0].' > '.$supclass.' > '.$family;
    }
    if ($_POST['tax_level'] == 'genus') {
        $phylo = $kingdom[0].' > '.$phylum[0].' > '.$supclass[0].' > '.$family.' > '.$s_group;
    }
    if ($_POST['tax_level'] == 'species') {
        $phylo = $kingdom[0].' > '.$phylum[0].' > '.$supclass[0].' > '.$family.' > '.$s_group.' > '.$species;
    }
    $phylo = str_replace(["'", '"', "(", ")", "[", "]"], "", $phylo);
    if (!isset($predict_info[$phylo][$domain])) {
        $predict_info[$phylo][$domain] = 0;
    }
    $predict_info[$phylo][$domain] += 1;
    $domains[$domain] = $row['fold'];
}

ksort($predict_info);

$tax_values=[];
$data="myheatmap.data = [";
foreach ($predict_info as $phylo => $value) {
    $nb_zero=0;
    foreach ($domains as $domain => $fold) {
        if (!isset($predict_info[$phylo][$domain])){
            $nb_zero += 1;
        }
    }
    if ($nb_zero > count($domains) - $_POST['min_families']){continue;}
    $count_total=0;
    foreach ($predict_info[$phylo] as $domain => $count) {
        $count_total += $count;
    }
    if(! isset($tax_values[$phylo])){$tax_values[$phylo]=[];}
    foreach ($domains as $domain => $fold) {
        $count = $predict_info[$phylo][$domain] / $count_total;
        $style = '';
        if ($count > 0){
            $style='background-color:black;color:white;';
            $data.="{'tax': '$phylo', 'lec': '$domain < $fold', 'value': '$count'},";
        }
        array_push($tax_values[$phylo], $count);
    }
}
$data = rtrim($data, ',');
$data .= "];";
echo("<script>".$data."</script>");

//AXIS LABELS
$data="myheatmap.x_elements_names = [";
foreach ($tax_values as $phylo => $value) {
    $data.="'$phylo',";
}
$data = rtrim($data, ',');
$data .= "];";
echo("<script>".$data."</script>");

//echo '<pre>'; print_r($tax_values); echo '</pre>';
//BY LINE VALUES FOR CLUSTERING
$data="myheatmap.tax_values = {";
$data_csv="";
foreach ($tax_values as $phylo => $value) {
    $data.="'$phylo':".json_encode($value)." ,";
    $data_csv.=$phylo."\t".implode("\t",$value)."\n";
}
$data = rtrim($data, ',');
$data .= "};";
echo("<script>".$data."</script>");

?>

<style>
    .tooltip {
        position: absolute;
        text-align: center;
        width: 150px;
        height: auto;
        padding: 2px;
        font: 12px sans-serif;
        background: black;
        border: 0px;
        border-radius: 8px;
        color: white;
        box-shadow: -3px 3px 15px #888888;
        opacity: 0;

    }
    .axis path,
    .axis line {
        fill: none;
        stroke: black;
        shape-rendering: crispEdges;
    }

    .axis text {
        font-family: sans-serif;
        font-size: 11px;
    }
</style>

<label>In line the taxonomy, in column the lectin class and in value the families frequency</label>
<button onclick="$('#heatmap').html('');myheatmap.hclust=false;console.log(myheatmap);myheatmap.load_heatmap();";>Order by name</button>
<button onclick="$('#heatmap').html('');myheatmap.hclust=true;console.log(myheatmap);myheatmap.load_heatmap();";>Order by cluster</button>
<div id="heatmap" class="heatmap" style="position: relative;"><div id="tooltip" class="tooltip"></div></div>

<script>
    myheatmap.load_heatmap();
</script>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<div class="div-border-title" style="margin-top:30px;">Heatmap flat data</div>
<?php
echo("<pre style='max-height:60px;'>".$data_csv."</pre>");
?>