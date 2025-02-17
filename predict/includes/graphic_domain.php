<?php

arsort($count_nbprot_domain);
//$count_nbprot_domain_other = array_slice($count_nbprot_domain, 13);
//$count_other = array_sum ( $count_nbprot_domain_other );
//$count_nbprot_domain = array_splice($count_nbprot_domain, 0, 13);
$max_count = max ( $count_nbprot_domain );
$nbfold = count($count_nbprot_fold);

echo "<div class=\"div-border-title\">{$POST_ARRAY['species']} ".count($count_nbprot_domain)." Identified lectin class(es) in $nbfold fold(s)</div>";
echo "<div class=\"div-border\" style='padding:5px;height: 360px;overflow-y: auto;'>";

foreach($count_nbprot_domain as $domain => $count){
  $percent = $count / $max_count * 10;
    echo "<div style='width:100%;display:flex;'><div style='width:80%;text-align: end;padding-right:5px;font-size: 20px;'>$domain</div><button style='height:26px;' onclick='$( \"#domain\" ).val(\"$domain\");$( \"#search\" ).submit();'><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span></button><div class='hbar' style='width:$percent%;background-color:#3A88FB;font-size: 20px;height:26px;margin:1px;padding:1px;'>$count</div></div>";
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
