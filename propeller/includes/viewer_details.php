<?php
include("align_binding_sites.php");
include("connect.php");
$connexionBIG = connectdatabaseBIG();

include("create_request.php");
$POST_ARRAY = $_POST;
$request = create_request($POST_ARRAY, $_GET['protein_id']);

//LOAD THE ALIGNED SEQUENCES
function get_infos($alignment_index) {
    $path = dirname ( __DIR__ ) . "/../db_load/tandem_alignments.txt";
    if (! file_exists ( $path )) {
        return ( "File not found $path" );
    }
    $file = new SplFileObject($path);
    if (!$file->eof()) {
        $file->seek($alignment_index);
        $contents = $file->current(); // $contents would hold the data from line x
    }
    return (rtrim($contents));
}

// RESULTS ARRAY
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
$results_array = array();
$protein_id = 0;
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);

print('<pre>Redondant proteins identified: ');
$request_tmp="SELECT query FROM lectinpred_skipped WHERE target = '{$row['protein']}'";
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
?>

<?php
if ($row['gene'] != "") {
    echo "<div style='display: inline-block;width:100%;'>";
    echo "<div style='display: inline-block;width:100%;'>";
    $display_gene_arg = "id=" . $row['gene'] . "&v=" . $row['gene_begin'] . ":" . $row['gene_end'];
    ?>
    <label>Gene viewer</label>
    <iframe id="theiframe" src="https://www.ncbi.nlm.nih.gov/projects/sviewer/embedded_iframe.html?iframe=theiframe&amp;embedded=true&amp;<?php echo $display_gene_arg; ?>" width="970" height="303"
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


// CREATE MATCH ALIGNMENT PLOT
$alignment_index = $row['alignment_index'];
//echo "test $alignment_index";
$info = get_infos($alignment_index-1);

//echo '<pre>'; print_r(explode("\t",$alignment_index.' '.$info)); echo '</pre>';
$ref_seqs = explode("\t",$info)[2];
$match_seqs = explode("\t",$info)[3];
$id = $row['protein_id'];
$match_sequenceData = explode(",", $match_seqs);
echo "<div id='visualization_container' style='width:100%;overflow-x:auto;'>";
echo "<label>Amino acid conservation in the blades</label>";
echo "<div id='visualization_$id' style='width:100%;'></div>";
echo "<label>Verified reference</label>";
echo "<div id='visualization_domain_$id' style='width:100%;'></div>";
echo "<script>
var sequences  = ['" . implode("','", $match_sequenceData) . "'];
var div_name = '#visualization_$id';
msaViewer(sequences, div_name, 960, 150);
</script>";
// CREATE QUERY DOMAIN ALIGNMENT PLOT
$query_sequenceData = explode(",", $ref_seqs);
echo "<script>
	var sequences  = ['" . implode("','", $query_sequenceData) . "'];
  var div_name = '#visualization_domain_$id';
  msaViewer(sequences, div_name, 960, 150);
	</script>";
echo "<label>Reference carbohydrate binding sites</label><br>";
// CREATE BINDING SITES ARRAY
$binding_sites_aligned_seq = align_binding_sites(implode(",", $query_sequenceData), $row['domain'], "binding_sites.txt");
echo "<div id='binding_site_$id' style='width:100%;margin-bottom:5px;'></div>";
echo "<script>
	var sequences  = ['" . implode("','", $binding_sites_aligned_seq) . "'];
  var div_name = '#binding_site_$id';
  msaViewer_bindingsites(sequences, div_name, 960, 150);
	</script>";

echo "<div style='margin:5px;'>";
echo "<button data-toggle='collapse' data-target='#global_alignment_$id'>View global alignment</button>";
echo "<button data-toggle='collapse' data-target='#neighbors_genes_$id'>View neighbors genes details</button>";
echo "<button data-toggle='collapse' data-target='#genes_seq_$id'>View gene sequence</button>";
echo "<button data-toggle='collapse' data-target='#neighbors_genes_seq_$id'>View neighbors sequence</button>";
echo "</div>";

echo "<div id='global_alignment_$id' class='collapse' style='font-family: Consolas, monaco, monospace;'>";

$query_sequences = explode(",", $row['query_sequence']);
$match_sequences = explode(",", $row['match_sequence']);
$global_alignment = "";
foreach ($query_sequenceData as $query_sequence) {
    $global_alignment .= "Q " . $query_sequence . "<br>";
}
foreach ($match_sequenceData as $match_sequence) {
    $global_alignment .= "M " . $match_sequence . "<br>";
}
echo "$global_alignment</div>";

echo "<div id='neighbors_genes_$id' class='collapse' style=''></div>";
$url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=nucleotide&id=" . $row['gene'] . "&seq_start=" . (intval($row['gene_begin']) - 5000) . "&seq_stop=" . (intval($row['gene_end']) + 5000) . "&rettype=gb&retmode=text";
$div_name = "#neighbors_genes_$id";
?>
<script>
    var theURL = "<?php echo $url; ?>";
    var xhr3 = new XMLHttpRequest();
    xhr3.onreadystatechange = function () {
        if (xhr3.readyState == XMLHttpRequest.DONE) {
            var text = xhr3.responseText;
            var text = text.split("ORIGIN")[0];
            var genelist = text.split("     CDS             ");
            genelist.shift();
            var genes_text = "";
            for (var i = 0; i < genelist.length; i++) {
                var gene = genelist[i];
                var gene_features = gene.split("\n");
                gene_features.shift();
                genes_text += gene_features.join("<br>") + "<br><br>";
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

echo "<div id='genes_seq_$id' class='collapse' style=''><textarea style='width:100%;height:auto;'>";
$url = "https://www.ncbi.nlm.nih.gov/sviewer/viewer.fcgi?id={$row['gene']}&db=nuccore&report=fasta&extrafeat=null&conwithfeat=on&from=" . (intval($row['gene_begin'])) . "&to=" . (intval($row['gene_end'])) . "&retmode=html&withmarkup=on&tool=portal&log$=seqview&maxdownloadsize=1000000";
$content = file_get_contents($url);
echo $content;
echo "</textarea></div>";

echo "<div id='neighbors_genes_seq_$id' class='collapse' style=''><textarea style='width:100%;height:auto;'>";
$url = "https://www.ncbi.nlm.nih.gov/sviewer/viewer.fcgi?id={$row['gene']}&db=nuccore&report=fasta&extrafeat=null&conwithfeat=on&from=" . (intval($row['gene_begin']) - 999) . "&to=" . (intval($row['gene_end']) + 999) . "&retmode=html&withmarkup=on&tool=portal&log$=seqview&maxdownloadsize=1000000";
$content = file_get_contents($url);
echo $content;
echo "</textarea></div>";

?>


