<?php
$count_nbprot_domain=array();
$count_nbprot_domain['PropLec5A_tachy']=0;
$count_nbprot_domain['PropLec6A_RSL_AAL']=0;
$count_nbprot_domain['PropLec6B_tectonin']=0;
$count_nbprot_domain['PropLec7A_PLL']=0;
$count_nbprot_domain['PropLec7B_PVL']=0;
$count_nbprot_domain['PropLec7C_BPL_CVL']=0;
foreach($results_array as $row){
	if(!isset($count_nbprot_domain[$row['domain']])){
		$count_nbprot_domain[$row['domain']]=0;
	}
	$count_nbprot_domain[$row['domain']]+=1;
}
//echo '<pre>'; print_r($count_nbprot_domain); echo '</pre>';
$color=array();
$color['PropLec5A_tachy']='#fbc02d';
$color['PropLec6A_RSL_AAL']='#d32f2f';
$color['PropLec6B_tectonin']='#7b1fa2';
$color['PropLec7A_PLL']='#1976d2';
$color['PropLec7B_PVL']='#388e3c';
$color['PropLec7C_BPL_CVL']='#66ffff';

$data = "<script>var dataset = [";
arsort($count_nbprot_domain);
foreach($count_nbprot_domain as $domain => $count){
    $data.= "{legend:'$domain', value:$count, color:'{$color[$domain]}'},";
}
$data = rtrim($data,',');
$data .= "];</script>";
echo $data;
?>
<div class="div-border-title">
	<?php echo $POST_ARRAY['domain']?> Family distribution
</div>
<div class="div-border" style="width:100%; height:100%;display:inline-block;">
	<div id="piechart_container" style="width:100%;display:inline-block;">
		<div id="piechart" style="padding-left:20px;padding-top:20px;display:inline-block;"></div>
		<div id="piechart_legend" style="padding:5px;display:inline-block;float:right;"></div>
	</div>
</div>
<script src='/propeller/js/propeller_piechart.js?v=2943784625'></script>
<script>
function unsync_create_piechart() {
  return new Promise(resolve => {
    setTimeout(() => {
      create_piechart(300,300);
    }, 10);
  });
};
unsync_create_piechart();
</script>