<?php
//LOAD INFOS
//include ("connect.php");
//$connexionBIG = connectdatabase();
//$row = unserialize(base64_decode($_POST['row']));
//$id = $row['protein_id'];

?>

<?php
print('<pre>Redondant proteins identified: ');
$request_tmp="SELECT query FROM lectinpred_skipped WHERE target = '{$row['protein_ac']}'";
$results_tmp = mysqli_query ( $connexionBIG, $request_tmp ) or die ( "SQL Error:<br>$request_tmp<br>" . mysqli_error ( $connexionBIG ) );
while ( $row_tmp = mysqli_fetch_array ( $results_tmp, MYSQLI_ASSOC ) ) {
    $AC = $row_tmp['query'];
    if (strpos($AC,'.') !== false){
        print('<a href="https://www.ncbi.nlm.nih.gov/protein/'.$AC.'">'.$AC.'</a>&nbsp;');
    }
    else{
        print('<a href="http://www.uniprot.org/uniprot/'.$AC.'">'.$AC.'</a>&nbsp;');
    }
}
print('</pre>');

$onload = "<script>";

$ref_seqs = $row['ref_seq'];
$match_seqs = $row['match_seq'];

$id = $row['protein_id'];
$match_sequenceData = explode(",",$match_seqs);
echo "<div class='div-border-title' style='margin-top:20px;'>Amino acid conservation</div>";
echo "<div class='div-border' id='visualization_container' style='width:100%;overflow-x:auto;'>";
echo "<label>Best predicted lectin domain</label>";
echo "<div id='visualization_$id' style='width:100%;'></div>";
echo "<label>Verified reference</label>";
echo "<div id='visualization_domain_$id' style='width:100%;'></div>";
$width = strlen($match_sequenceData[0]) * 14;
$onload .= "
	var sequences  = ['" . implode("','", $match_sequenceData) . "'];
  var div_name = '#visualization_$id';
  msaViewer(sequences, div_name, $width, 80);
	";

// CREATE QUERY-REF DOMAIN ALIGNMENT PLOT
$query_sequenceData = explode(",",$ref_seqs);
$onload .= "
	var sequences  = ['" . implode("','", $query_sequenceData) . "'];
  var div_name = '#visualization_domain_$id';
  msaViewer(sequences, div_name, $width, 80);
	";

// CREATE BINDING SITES ARRAY
function strpos_recursive($haystack, $needle, $offset = 0, &$results = array()) {
    $offset = strpos($haystack, $needle, $offset);
    if($offset === false) {
        return $results;
    } else {
        $results[] = $offset;
        return strpos_recursive($haystack, $needle, ($offset + 1), $results);
    }
}
echo "<label>Reference carbohydrate binding sites</label><br>";
echo "<div id='binding_site_{$id}_ALL' style='width:100%;'></div>";
$lectin_class = $row['domain'];
$motif_request="SELECT distinct(motif) from binding_sites WHERE pdb in ( SELECT PDB FROM test_cluster WHERE REPLACE(lecclass, '(', '') LIKE '%$lectin_class%')";
$motif_results = mysqli_query ( $connexionBIG, $motif_request ) or die ( "SQL Error:<br>$motif_request<br>" . mysqli_error ( $connexionBIG ) );
$motif_request="SELECT distinct(motif_end) from binding_sites WHERE pdb in ( SELECT PDB FROM test_cluster WHERE REPLACE(lecclass, '(', '') LIKE '%$lectin_class%')";
$motif_results_end = mysqli_query ( $connexionBIG, $motif_request ) or die ( "SQL Error:<br>$motif_request<br>" . mysqli_error ( $connexionBIG ) );
$motif_list=array();
if (mysqli_num_rows($motif_results) == 0){
    echo("<pre>No binding sites available for this class</pre>");
}
else {
    $bs_positions = array();
    $all_bs_joined = array();
    $motif_list=array();
    $motif_end_list=array();
    while ($motif_row = mysqli_fetch_array($motif_results, MYSQLI_ASSOC)) {
        array_push($motif_list, $motif_row['motif']);
    }
    while ($motif_row = mysqli_fetch_array($motif_results_end, MYSQLI_ASSOC)) {
        array_push($motif_end_list, $motif_row['motif_end']);
    }
    foreach ($query_sequenceData as $sequence) {
        $seq_index = 0;
        $bs_positions[$seq_index] = array();
        for ($i = 0; $i < strlen($query_sequenceData[0]); $i++) {
            $bs_positions[$seq_index][$i] = "-";
        }
        foreach ($motif_list as $motif) {
            $found = strpos_recursive($sequence, $motif);
            if($found) {
                foreach ($found as $bs_pos) {
                    $bs_positions[$seq_index][$bs_pos + 4] = $motif[4];
                }
            }
        }
        foreach ($motif_end_list as $motif) {
            $found = strpos_recursive($sequence, $motif);
            if($found) {
                foreach ($found as $bs_pos) {
                    $bs_positions[$seq_index][$bs_pos] = $motif[0];
                }
            }
        }
        $bs_positions_joined = implode("", $bs_positions[$seq_index]);
        array_push($all_bs_joined, $bs_positions_joined);
        $seq_index += 1;
    }

//$binding_sites_array = get_binding_sites($row['query_sequence'], $row['match_sequence'], $row['domain'], "binding_sites.txt");
    $onload .= "
  var binding_sites  = ['" . implode("','", $all_bs_joined) . "'];
  console.log('binding_sites');
  console.log(binding_sites);
  var div_name = '#binding_site_{$id}_ALL';
  msaViewer_bindingsites(binding_sites, div_name, $width, 80);
	";
}
echo "</div>";
//END DIV BINDING SITES

//DISPLAY FULL MOTIF
$fold_domain = str_replace(' ', '_', $row['fold'])."_CLASS_".str_replace(' ', '_', $row['domain']);
?>
<div class='div-border-title' style='display: inline-block;margin-top:20px;'>Reference conserved motif. Graphics generated by Skylign</div>
<div class='div-border' style='margin-bottom:20px;display: inline-block;'>
    <iframe width="1000" height="400" src="https://unilectin.eu/predict/includes/display_logo?domain=<?php echo $fold_domain; ?>"></iframe>
</div>

<?php

//DISPLAY GENE
if($row['gene'] != ""){
    echo "<div class='div-border-title' style='margin-top:20px;'>Gene viewer</div>";
    echo "<div style='display: inline-block;width:100%;'>";
    echo "<div style='display: inline-block;width:100%;'>";
    $display_gene_arg="id=".$row['gene']."&v=".$row['gene_begin'].":".$row['gene_end'];
    ?>
    <iframe id="theiframe" src="https://www.ncbi.nlm.nih.gov/projects/sviewer/embedded_iframe.html?iframe=theiframe&amp;embedded=true&amp;<?php echo $display_gene_arg; ?>" width="1000" height="303"
            onload="if(!window._SViFrame){
                  _SViFrame=true;window.addEventListener('message',function(e){
                  if(e.origin=='https://www.ncbi.nlm.nih.gov' &amp;&amp; !isNaN(e.data.h))document.getElementById(e.data.f).height=parseInt(e.data.h);
                  });
                  }">
        <p>Your browser does not support iframes.</p>
    </iframe>
    <?php
    echo "</div>";
}

echo "<br><pre style='margin-top:20px;'>".$motif_request."</pre>";
echo "<button data-toggle='collapse' data-target='#global_alignment_$id'>View global alignment</button>";
echo "<div id='global_alignment_$id' class='collapse' style='font-family: Consolas, monaco, monospace;'>";

$query_sequences = explode(",", $ref_seqs);
$match_sequences = explode(",", $match_seqs);
$global_alignment="";
foreach ($query_sequences as $query_sequence){
	$global_alignment.="REF: ".$query_sequence."<br>";
}
foreach ($match_sequences as $match_sequence){
	$global_alignment.="PRED: ".$match_sequence."<br>";
}
echo "$global_alignment</div>";

//Domains sequences

$request_domain = "SELECT domain, begin, end, hmm_begin, hmm_end FROM lectinpred_aligned_domains LEFT JOIN lectinpred_domain ON (lectinpred_domain.domains_id = lectinpred_aligned_domains.domains_id) ";
$request_domain .= "WHERE protein_id = '{$row['protein_id']}' ORDER BY domain, begin ";
$results_domain = mysqli_query($connexionBIG, $request_domain) or die("SQL Error:<br>$request_domain<br>" . mysqli_error($connexionBIG));
while ($row_annot = mysqli_fetch_array($results_domain, MYSQLI_ASSOC)) {
    $url = "https://www.uniprot.org/blast/?about={$row['protein']}[" . (intval($row_annot['begin'])) . "-" . (intval($row_annot['end'])) . "]&key=Domain";
    if ($row['alt_ac'] != ''){
        $url = "https://www.ncbi.nlm.nih.gov/sviewer/viewer.fcgi?id={$row['alt_ac']}&db=prot&report=fasta&from=" . (intval($row_annot['begin'])) . "&to=" . (intval($row_annot['end'])) . "&retmode=html";
    }
    echo "<br><a target='_blank' href='$url'>View sequence for {$row_annot['domain']} {$row_annot['begin']} : {$row_annot['end']}</a>";
}

if ($row['gene'] != '') {
    echo "<br><button data-toggle='collapse' data-target='#neighbors_genes_$id'>View neighbors genes details</button>";
    $url = "https://www.ncbi.nlm.nih.gov/sviewer/viewer.fcgi?id={$row['gene']}&db=nuccore&report=fasta&extrafeat=null&conwithfeat=on&from=" . (intval($row['gene_begin'])) . "&to=" . (intval($row['gene_end'])) . "&retmode=html&withmarkup=on&tool=portal&log$=seqview&maxdownloadsize=1000000";
    echo "<a target='_blank' href='$url'>View gene sequence</a>";
    $url = "https://www.ncbi.nlm.nih.gov/sviewer/viewer.fcgi?id={$row['gene']}&db=nuccore&report=fasta&extrafeat=null&conwithfeat=on&from=" . (intval($row['gene_begin']) - 999) . "&to=" . (intval($row['gene_end']) + 999) . "&retmode=html&withmarkup=on&tool=portal&log$=seqview&maxdownloadsize=1000000";
    echo "<a target='_blank' href='$url'>View neighbors sequence</a>";
    echo "<div id='neighbors_genes_$id' class='collapse' style=''></div>";
    $url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=nucleotide&id=" . $row['gene'] . "&seq_start=" . (intval($row['gene_begin']) - 5000) . "&seq_stop=" . (intval($row['gene_end']) + 5000) . "&rettype=gb&retmode=text";
    $div_name = "#neighbors_genes_$id";
}
?>
<script>
	var theURL = "<?php echo $url; ?>";
	var xhr3 = new XMLHttpRequest();
	xhr3.onreadystatechange = function() {
		if (xhr3.readyState == XMLHttpRequest.DONE) {
			var text = xhr3.responseText;
			var text = text.split("ORIGIN")[0];
			var genelist = text.split("     CDS             ");
			genelist.shift();
			var genes_text="";
			for(var i= 0; i < genelist.length; i++)
			{
				var gene = genelist[i];
				var gene_features = gene.split("\n");
				gene_features.shift();
				genes_text += gene_features.join("<br>")+"<br><br>";
			}
			var div = $("<?php echo $div_name; ?>");
			div.html(genes_text);
		}
	}
	xhr3.open('GET', theURL, true);
	xhr3.send(null);
</script>
<?php
echo "</div>";
$onload .= "</script>";
echo $onload;
?>

