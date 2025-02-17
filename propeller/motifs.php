<?php include($_SERVER['DOCUMENT_ROOT']."/propeller/header.php"); ?>
<script src="/js/architecture.js" xmlns:right></script>
<script src="/js/d3.v3.min.js"></script>
<script src='/js/msaViewer.js' charset='utf.8'></script>
<script src='/js/binding_site_viewer.js' charset='utf.8'></script>
<link rel="stylesheet" href="/css/nouislider.min.css">
<script src="/js/nouislider.min.js"></script>

<?php
include ("./includes/align_binding_sites.php");
// CREATE MATCH ALIGNMENT PLOT
$id = $row['protein_id'];
$query_sequenceData=[
"..........VGGESMLRGVYQDKFYQGTYPQNKNDNWLARATLIGK",
"GGWSNFKFLFLSPGGELYGVLNDKIYKGTPPTHDNDNWMGRAKKIGN",
"GGWNQFQFLFFDPNGYLYAVSKDKLYKASPPQSDTDNWIARATEVGS",
"GGWSGFKFLFFHPNGYLYAVHGQQFYKALPPVSNQDNWLARATKIGQ",
"GGWDTFKFLFFSSVGTLFGVQGGKFYEDYPPSYAYDNWLARAKLIGN",
"GGWDDFRFLFF...................................."
];
$domain="PropLec5A";
echo "<div id='visualization_container' style='width:100%;overflow.x:auto;'>";
echo "<label>Verified reference</label>";
echo "<div id='visualization_domain_$id' style='width:100%;'></div>";
// CREATE QUERY DOMAIN ALIGNMENT PLOT
echo "<script>
	var sequences  = ['" . implode("','", $query_sequenceData) . "'];
  var div_name = '#visualization_domain_$id';
  msaViewer(sequences, div_name, 960, 150);
	</script>";
echo "<label>Reference carbohydrates binding sites</label><br>";
// CREATE BINDING SITES ARRAY
//$binding_sites_array = get_binding_sites($row['query_sequence'], $row['match_sequence'], $row['domain'], "binding_sites.txt");
$binding_sites_aligned_seq = align_binding_sites(implode(",", $query_sequenceData), $domain, "binding_sites.txt");
echo "<div id='binding_site_$id' style='width:100%;'></div>";
echo "<script>
	var sequences  = ['" . implode("','", $binding_sites_aligned_seq) . "'];
  var div_name = '#binding_site_$id';
  msaViewer_bindingsites(sequences, div_name, 960, 150);
	</script>";
?>

<?php include($_SERVER['DOCUMENT_ROOT']."/propeller/footer.php"); ?>