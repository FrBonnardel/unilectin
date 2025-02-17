<script src="/js/glycoctRDF.js"></script>
<script>
    function maximise_image(id) {
        var div = $('#' + id);
        if (div.width() < 500) {
            div.width(500);
            div.height(500);
        } else {
            div.width(150);
            div.height(150);
        }
    }

    function load_glycoct(div, iupac) {
        $.ajax({
            url: "https://glyconnect.expasy.org/api/structures/translate/iupac/glycoct",
            type: "POST",
            data: JSON.stringify({"iupac": iupac, "glycanType": ""}),
            dataType: 'json',
            contentType: "application/json",
            complete: function (data) {
                if (data.responseText.indexOf("problem") >= 0) {
                    return;
                }
                if (data.responseText.indexOf("Null content") >= 0) {
                    return;
                }
                $('#' + div).html(data.responseText);
                $('#glytoucan_input').val(data.responseText.replace(/\n/g, '\\n'));
                //load_glycoRDF(data.responseText);
            }
        });
    }

    function load_glycoRDF(glycoCtCode) {
        var queryRDF = glycoctRDF(glycoCtCode);
        queryRDF = queryRDF.join(' ');
        $('#query').val(queryRDF);
        $('#queryRDF_content_div').html("");
        $('#queryRDF_content_div').append("loading");
        $.post("/unilectin3D/includes/sparql.php", $('#query_form').serialize(),
            function (data, status) {
                $("#queryRDF_content_div").html("");
                $('#queryRDF_content_div').append(data);
            }
        );
    }
</script>

<style>
    #overlay {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0px;
        left: 0px;
        background-color: #000;
        opacity: 0.7;
        filter: alpha(opacity=70) !important;
        display: none;
        z-index: 100;
    }

    #overlayContent {
        position: fixed;
        width: 100%;
        top: 100px;
        text-align: center;
        display: none;
        overflow: hidden;
        z-index: 100;
    }

    #contentGallery {
        margin: 0px auto;
    }

    #imgBig, #imgSmall {
        cursor: pointer;
    }
</style>

<?php
//echo '<pre>'; print_r($row); echo '</pre>';
$id = $row['lectin_id'];
$seq = $row['iupac'];
$pdblink = "";
echo "<div class='div-border-title' style='height:33px;'>{$row['pdb']}<div style='width:130px;float:right;' class='sharethis-inline-share-buttons'></div></div>";
?>
<script src="//platform-api.sharethis.com/js/sharethis.js#property=5b6057db78eb8b00113e3405&product=inline-share-buttons"></script>
<?php
echo "<div class='div-border' style='margin-bottom:20px;display: inline-block;'>";

//DISPLAY LITEMOL
echo "<div style='width:100%' id='litemol_viewers_containers'>";
echo "<div style='width:50%;display: inline-block;vertical-align: top;'>";
$pdb = $row['pdb'];
$lectin_id = $row['id'];
$source = "PDBe";
$height = 400;
$width = 500;
if ($row['ligand_link'] == "") {
    $width = 1000;
}
include($_SERVER['DOCUMENT_ROOT'] . '/unilectin3D/includes/litemol.php');
echo "</div>";

//DISPLAY LITEMOL-LIGAND
if ($row['ligand_link'] != "") {
    echo "<div style='position:relative;width:50%;display: inline-block;overflow-y:auto;'>";
    $ligand_links = explode(" ", $row['ligand_link']);
    echo "<div style='position:absolute; top:0;z-index: 100;'>";
    echo "<label>Select a ligand</label>";
    echo "<select id='select_ligand'>";
    foreach ($ligand_links as $ligand_link) {
        echo "<option>$ligand_link</option>";
    }
    $LIGAND = $ligand_links[0];
    echo "</select>";
    $PDB = $row['pdb'];
    echo "<button onclick=\"load_ligand('$PDB',$('#select_ligand').val())\">Focus</button>";
    echo "</div>";
    echo "<div id='liteMolViewerligand' style='position:relative;width:498px;height:400px;'></div>";
    echo "<script>load_ligand('$PDB', '$LIGAND');</script>";
    echo "</div>";
}
echo "</div>";

//DESCRIPTION
echo "<div style='width:50%;display: inline-block;float:left;'>";
?>
    <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
        <span class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">fold</span><input readonly type="text" value="<?php echo $row['fold']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
    <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
        <span class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">class</span><input readonly type="text" value="<?php echo $row['class']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
    <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
        <span class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">family</span><input readonly type="text" value="<?php echo $row['family']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
    <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
        <span class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">resolution (&Aring;)</span><input readonly type="text" value="<?php echo $row['resolution']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
    <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
        <span class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">origin</span><input readonly type="text" value="<?php echo $row['origin']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
    <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
        <span class='input-group-addon' type="text" style="height: 20px;width:130px;padding:0;border:none;font-weight: bold;">species</span><input readonly type="text" value="<?php echo $row['species']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;font-style:italic; ">
    </div>
<div style="width: 100%;border:1px solid grey;height:50px;" class="input-group">
    <span class='input-group-addon' type="text" style="height: 50px;width:130px;padding:0;font-weight: bold;">comments</span>
    <div readonly type="text" style="border:none;width: 100%; height: 50px; text-align: left;overflow-y:auto;background-color: white;"><?php echo $row['comments']; ?></div>
</div>
<?php
echo "<div style='width: 100%;border:1px solid grey;height:90px;overflow-y:auto;background-color: white;'>";
$reference = $row['authors'];
$reference .= "\n\r" . $row['title'];
$reference .= " " . $row['journal'];
$reference .= " " . $row['volume'];
$reference .= " " . $row['pages'];
$reference .= " " . $row['year'];
echo $reference;
echo "</div>";
echo "<meta name='reference' content=\"$reference\">";
?>
</div>
<div style='width:50%;display: inline-block;'>
    <?php
    if ($row['uniprot'] != "") {
        ?>
        <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
            <?php
            $lectinlink = "<a class='btn btn-primary' href='https://unilectin.eu/unilectin3D/display_lectin?uniprot={$row['uniprot']}'>{$row['uniprot']} in UniLectin</a><br>";
            ?>
            <span class='input-group-addon' type="text" style="height: 30px;width:140px;padding:0;font-weight: bold;">Matching protein</span>
            <div style="border:none;width: 100%; height: 20px; text-align: left;background-color: white;"><?php echo $lectinlink; ?></div>
        </div>
        <?php
        $pdb_listlink = "";
        $pdb_list = "";
        $related_pdb_request = "SELECT pdb, ligand FROM lectin_view WHERE uniprot = '{$row['uniprot']}' ORDER BY pdb";
        $related_pdb_results = mysqli_query($connexion, $related_pdb_request) or die ("SQL Error:<br>$related_pdb_request<br>" . mysqli_error($connexion));
        while ($related_pdb_row = mysqli_fetch_array($related_pdb_results, MYSQLI_ASSOC)) {
            $pdb = $related_pdb_row['pdb'];
            $ligand = $related_pdb_row['ligand'];
            if ($ligand == '') {
                $ligand = "no ligand";
            }
            $ligand = substr($ligand, 0, 30);
            $pdb_listlink .= "<a class='btn btn-primary' href='https://unilectin.eu/unilectin3D/display_structure?pdb=$pdb'>$pdb : $ligand</a><br>";
            $pdb_list .= $pdb . " ";
        }
        ?>
        <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
            <span class='input-group-addon' type="text" style="height: 20px;width:140px;padding:0;font-weight: bold;">available structures</span>
            <div style="border:none;height:60px;overflow-x:auto; text-align: left;background-color: white;"><?php echo $pdb_listlink; ?></div>
        </div>
        <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
            <span class='input-group-addon' type="text" style="height: 20px;width:140px;padding:0;font-weight: bold;">related proteins<br>> 50% identity</span>
            <div id="related_50" style="border:none;width: 100%; height:60px;overflow-x:auto;text-align: left;background-color: white;">
                <?php
                $content = file_get_contents("https://www.uniprot.org/uniref/?fil=identity:0.5&sort=score&format=tab&query=" . $row['uniprot']);
                $lines = explode("\n", $content);
                $info = explode("\t", $lines[1])[4];
                $info = explode("; ", $info);
                foreach ($info as $protein) {
                    $unidb = "uniprot";
                    if (strpos($protein, "UPI") !== false) {
                        $unidb = "uniprot";
                    }
                    if ($protein != '') {
                        echo "<a class='btn btn-success' style='width:50%' role='button' href='http://www.uniprot.org/$unidb/$protein' target='_blank'>$protein</a>";
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }
    ?>
    <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
        <span class='input-group-addon' type="text" style="height: 20px;width:140px;padding:0;font-weight: bold;">ligand</span><input readonly type="text" value="<?php echo $row['ligand']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
    <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
        <span class='input-group-addon' type="text" style="height: 20px;width:140px;padding:0;font-weight: bold;">glycan composition</span><input readonly type="text" value="<?php echo $row['monosac']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
    <div style="width: 100%;border:1px solid grey;height:20px;" class="input-group">
        <span class='input-group-addon' type="text" style="height: 20px;width:140px;padding:0;font-weight: bold;">IUPAC condensed</span><input readonly type="text" value="<?php echo $row['iupac']; ?>" style="border:none;width: 100%; height: 20px; text-align: left;">
    </div>
    <?php
    echo "<div style='width: 100%;border:1px solid grey;height:20px;'  class='input-group'>";
    echo "<span  class='input-group-addon' type='text' style='height: 20px;width:140px;padding:0;font-weight: bold;'>GlycoCT</span>";
    echo "<textarea readonly id = 'glycoct_$id' style='white-space: pre-line;border:none;width: 100%; height: 50px; text-align: left; overflow-y:auto;background-color: white;'></textarea>";
    echo "<script>load_glycoct(\"glycoct_{$id}\",\"{$row['iupac']}\");</script>";
    echo "</div>";
    echo "</div>";

    //LINK SITES
    echo "<div style='width:100%;float:right;'>";
    if ($row['pubmed'] != "") {
        echo "<a class='btn btn-success' style='width:50%' role='button' href='https://pubmed.ncbi.nlm.nih.gov/{$row['pubmed']}' target='_blank'>PubMed</a>";
    }
    if ($row['cfg'] != "") {
        echo "<a class='btn btn-success' style='width:50%' role='button' href='http://www.functionalglycomics.org/glycomics/HServlet?operation=view&sideMenu=no&psId=primscreen_{$row['cfg']}' target='_blank'>Glycan Array CFG</a>";
    }
    echo "<a class='btn btn-success' style='width:50%' role='button' href='http://www.rcsb.org/pdb/explore/explore.do?structureId={$row['pdb']}' target='_blank'>RCSB.ORG</a>";
    echo "<a class='btn btn-success' style='width:50%' role='button' href='https://www.ebi.ac.uk/pdbe/entry/pdb/{$row['pdb']}' target='_blank'>PDBe</a>";
    echo "<form id='form-pdb-care' method='post' target='_blank' action='http://www.glycosciences.de/tools/pdb-care/pdbcare_val.php' style='display:none;'>";
    echo "<input name='pdbid' size='5' maxlength='4' type='text' value='{$row['pdb']}' style='display:none;'>";
    echo "</form>";
    echo "<button class='btn btn-success' style='width:50%' type='submit' form='form-pdb-care'>PDB-CARE (glycan validation)</button>";
    if ($row['iupac'] != "") {
        $iupac = $row['iupac'];
        echo "<form id='form-glytoucan' method='get' target='_blank' action='https://glytoucan.org/Structures/structureSearch' style='display:none;'>";
        echo "<input name='iupacInput' id='glytoucan_input' size='5' maxlength='4' type='text' value='$iupac' style='display:none;'>";
        echo "</form>";
        echo "<button class='btn btn-success' style='width:50%' type='submit' form='form-glytoucan'>GlyTouCan (glycan information)</button>";
        echo "<a class='btn btn-success' style='width:50%' href='https://swissmodel.expasy.org/templates/{$row['pdb']}.1'>SwissModel (ligand interactions)</a>";
        echo "<a class='btn btn-success' style='width:50%' href='https://unilectin.eu/unilectin3D/display_superstructures?pdb={$row['pdb']}'>Glyconnect and SugarBind glycan superstructure(s)</a>";
    }
    if ($row['uniprot'] != "") {
        echo "<a class='btn btn-success' style='width:50%' role='button' href='http://www.uniprot.org/uniprot/{$row['uniprot']}' target='_blank'>Uniprot</a>";
    }
    if ($row['gene'] != "") {
        echo "<a class='btn btn-success' style='width:50%' role='button' href='https://www.ncbi.nlm.nih.gov/gene/{$row['gene']}' target='_blank'>Gene</a>";
    }
    include($_SERVER['DOCUMENT_ROOT'] . '/unilectin3D/includes/expasylinks_array.php');
    if (isset($pdb_to_sugarbind[$row['pdb']])) {
        $pdb_to_sugarbind[$row['pdb']] = explode(";", $pdb_to_sugarbind[$row['pdb']])[0];
        echo "<a class='btn btn-success' style='width:50%' role='button' href='https://sugarbind.expasy.org/lectins/{$pdb_to_sugarbind[$row['pdb']]}' target='_blank'>Sugarbind lectin</a>";
    }
    if (isset($uniprot_to_glyconnect[$row['uniprot']])) {
        echo "<a class='btn btn-success' style='width:50%' role='button' href='https://glyconnect.expasy.org/browser/proteins/{$uniprot_to_glyconnect[$row['uniprot']]}' target='_blank'>GlyConnect lectin</a>";
    }
    echo "</div>";
    echo "</div>";

    //DISPLAY INFO BLOCK
    echo "<div class='div-border-title' style='display: inline-block;'>Pictures</div>";
    echo "<div class='div-border' style='margin-bottom:20px;display: inline-block;'>";
    if (isset($row['taxonomyLienImg'])) {
        $filepath = $row['taxonomyLienImg'];
        echo "<div id='pic_{$row['id']}' onclick=\"maximise_image(this.id)\" style='background:url(\"/glyco3d/$filepath\");background-size: contain;background-repeat:no-repeat;background-position:top center;width:150px;height:100px;float:left;'></div>";
    }
    $file_request = "SELECT * FROM file WHERE file.lectin_id = {$row['lectin_id']}";
    $file_results = mysqli_query($connexion, $file_request) or die ("SQL Error:<br>$file_request<br>" . mysqli_error($connexion));
    while ($file_row = mysqli_fetch_array($file_results, MYSQLI_ASSOC)) {
        $filepath = $file_row['filepath'];
        if (pathinfo($filepath, PATHINFO_EXTENSION) != "pdb") {
            echo "<div id='pic_{$file_row['id']}' onclick=\"maximise_image(this.id)\" style='background:url(\"/glyco3d/$filepath\");background-size: contain;background-repeat:no-repeat;background-position:top center;width:150px;height:100px;float:left;'></div>";
        }
    }
    if ($row['iupac'] != "") {
        $iupac = $row['iupac'];
        echo "<div id = 'glycoct_svg_{$id}' style='float:right;margin-right:5px;'><img src='../../templates/png/$iupac.png'></div>";
    }
    echo "</div>";
    echo "</div>";

    //DISPLAY CONNECTED GLYCANS
    //if ($row['iupac'] != "") {
    //    echo "<div class='div-border-title' style='display: inline-block;'>Glycan(s) with matching epitope</div>";
    //    echo "<div class='div-border' style='margin-bottom:20px;display: inline-block;max-height:300px;overflow-y:auto;padding:5px;'>";
    //    echo "<form id='query_form' encrypt='application/x-www-form-urlencoded' style='display:none;'>";
    //    echo "<input type='text' id='query' name='query' value=''></form>";
    //    echo "<div id='queryRDF_content_div'></div>";
    //    echo "</div>";
    //}

    //SWISS MODEL PLIP INTERACTIONS VIEWER
    if ($row['iupac'] != "") {
        echo "<div class='div-border-title' style='display: inline-block;'>SWISS MODEL PLIP INTERACTIONS VIEWER</div>";
        echo "<div class='div-border' style='margin-bottom:20px;display: inline-block;'>";
        $pdb = strtolower($row['pdb']);
        echo "<iframe src='https://www.unilectin.eu/unilectin3D/includes/swissmodel_viewer?pdb=$pdb' width='990' height='340' style='border:none;'></iframe>";
        echo "</div>";
    }


    //SWISS MODEL PLIP INTERACTIONS VIEWER
    if ($row['iupac'] != "" && False) {
        echo "<div class='div-border-title' style='display: inline-block;'>SWISS MODEL PLIP INTERACTIONS VIEWER</div>";
        ?>
        <div class='div-border' style='margin-bottom:20px;display: inline-block;'>
            <iframe width="1000" height="550" src="https://unilectin.eu/templates/html/<?php echo strtolower($row['pdb']); ?>.html"></iframe>
        </div>
        <?php
        echo "</div>";
    }
    ?>

    <!-- PDB SEQ VIEWER -->
    <div class='div-border-title' style='display: inline-block;'>PDBe SEQ viewer</div>
    <div class='div-border' style='margin-bottom:20px;display: inline-block;'>
        <?php
        echo "<pdb-seq-viewer entry-id='" . strtolower($row['pdb']) . "' entity-id='1' height='300'></pdb-seq-viewer>";
        ?>
    </div>
    <script>
        angular.element(document).ready(function () {
            angular.bootstrap(document, ['pdb.sequence.viewer']);
        });
    </script>

    <!-- PDB Topology  VIEWER -->
    <div class='div-border-title' style='display: inline-block;'>PDBe Topology viewer</div>
    <div class='div-border' style='margin-bottom:20px;display: inline-block;'>
        <iframe width="1000" height="450" src="https://unilectin.eu/unilectin3D/includes/topology_viewer?pdb=<?php echo strtolower($row['pdb']); ?>"></iframe>
    </div>

