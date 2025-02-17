<?php

arsort($count_nbprot_protname);
$count_nbprot_protname_other = array_slice($count_nbprot_protname, 13);
$count_other = array_sum ( $count_nbprot_protname_other );
$count_nbprot_protname = array_splice($count_nbprot_protname, 0, 13);
#$count_nbprot_protname['others'] = $count_other;

$max_count = max ( $count_nbprot_protname );

echo "<div class=\"div-border-title\">{$POST_ARRAY['species']} Most frequent protein name(s)</div>";
echo "<div class=\"div-border\" style='padding:5px;height: 380px'>";

foreach($count_nbprot_protname as $domain => $count){
    $domain = substr($domain,0,30);
    $percent = $count / $max_count * 10;
    echo "<div style='width:100%;display:flex;'><div style='width:80%;text-align: end;padding-right:5px;font-size: 20px;'>$domain</div><button style='height:26px;' onclick='$( \"#protein_name\" ).val(\"$domain\");$( \"#search\" ).submit();'><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span></button><div class='hbar' style='width:$percent%;background-color:#3A88FB;font-size: 20px;height:26px;margin:1px;padding:1px;'>$count</div></div>";
}
echo "</div>";
?>

<style>
    .hbar{
        border-radius: 5px;
        padding:2px;
        margin:1px;
        display:block;
    }
</style>
