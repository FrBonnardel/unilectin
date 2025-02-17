<title><?php echo $_GET['pdb']; ?> structure in UniLectin3D</title>

<?php include($_SERVER['DOCUMENT_ROOT']."/unilectin3D/header.php"); ?>
<script src="/js/glycoctRDF.js"></script>
<script>
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
                load_glycoRDF(data.responseText);
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

<?
if($_GET['pdb']==""){
    exit("PDB ID unset");
}
$pdb = $_GET['pdb'];
$id = $_GET['pdb'];
$request = "SELECT origine, fold, classe, famille, espece, pdb, lectin_view.sequence as sugar_sequence FROM lectin_view WHERE pdb = '$pdb'";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
while($row = mysqli_fetch_array ( $results, MYSQLI_ASSOC )){
    echo "<meta name='description' content='The lectin domain {$_GET['pdb']} from the {$row['origine']}, {$row['classe']}, {$row['famille']}>";
    //DISPLAY CONNECTED GLYCANS
    if ($row['sugar_sequence'] != "") {
        echo "<div style='width: 100%;border:1px solid grey;height:20px;'  class='input-group'>";
        echo "<label>Query GlycoCT</span>";
        echo "<textarea readonly id = 'glycoct_$id' style='white-space: pre-line;border:1px solid black;width: 100%; height: 100px; text-align: left; overflow-y:auto;background-color: white;'></textarea>";
        echo "<script>load_glycoct(\"glycoct_{$id}\",\"{$row['sugar_sequence']}\");</script>";
        echo "</div>";
        echo "<form id='query_form' encrypt='application/x-www-form-urlencoded'>";
        echo "<input type='text' id='query' name='query' value='' style='width:100%'></form>";
        echo "<div class='div-border-title' style='display: inline-block;'>Glycan(s) with matching epitope</div>";
        echo "<div class='div-border' style='margin-bottom:20px;display: inline-block;max-height:1000px;overflow-y:auto;padding:5px;'>";
        echo "<div id='queryRDF_content_div'></div>";
        echo "</div>";
    }
}
?>

<?php include($_SERVER['DOCUMENT_ROOT']."/unilectin3D/footer.php"); ?>
