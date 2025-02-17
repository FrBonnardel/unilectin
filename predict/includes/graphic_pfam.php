<?php

arsort($count_nbprot_pfam);
$count_nbprot_pfam_other = array_slice($count_nbprot_pfam, 13);
$count_other = array_sum ( $count_nbprot_pfam_other );
$count_nbprot_pfam = array_splice($count_nbprot_pfam, 0, 13);
#$count_nbprot_pfam['others'] = $count_other;
unset($count_nbprot_pfam['']);

$max_count = max ( $count_nbprot_pfam );

echo "<div class=\"div-border-title\">{$POST_ARRAY['species']} Most frequent Pfam</div>";
echo "<div class=\"div-border\" style='padding:5px;height: 380px;'>";

foreach($count_nbprot_pfam as $domain => $count){
    $percent = $count / $max_count * 10;
    echo "<div style='width:100%;display:flex;'><div style='width:80%;text-align: end;padding-right:5px;font-size: 20px;'>$domain</div><button style='height:26px;' onclick='$( \"#domain_pfam_name\" ).val(\"$domain\");$( \"#search\" ).submit();'><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span></button><div class='hbar' style='width:$percent%;background-color:#3A88FB;font-size: 20px;height:26px;margin:1px;padding:1px;'>$count</div></div>";
}
if(count($count_nbprot_pfam) == 0){
    echo "No Pfam identified";
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
