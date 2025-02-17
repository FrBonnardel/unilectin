<title>LectomeXplore - Database of predicted lectins</title>
<meta name="description" content="LectomeXplore is a module of UniLectin dedicated to the exploration of predicted lectins. The proteins are extracted from UniProt and UniParc databases with quality check">
<script src="/js/d3.v3.min.js"></script>
<center>
    <label class="title_main" href='./'>LectomeXplore - A database of predicted lectins</label>
</center>
<?php
$request = "SELECT UPDATE_TIME FROM information_schema.tables WHERE TABLE_SCHEMA = 'unilecticvbig' AND TABLE_NAME = 'lectinpred_protein'";
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$last_update = $row['UPDATE_TIME'];

$request = "SELECT * FROM lectinpred_statistics WHERE module = 'lectinpred'";
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$row_stats = mysqli_fetch_array($results, MYSQLI_ASSOC);
?>
<div style='padding:5px;font-size: 18px; text-align: justify; text-justify: inter-word;float:left;'>
    <br>
    <span style='font-weight:bold;'>
    What is LectomeXplore ?
  </span>
    <br>
    LectomeXplore is a module dedicated to the exploration of predicted lectins for each of the 107 classes from UniLectin3D classification. Translated genomes (proteomes) released in the UniProtKB and RefSeq sequence databases and in the PDB structure database were screened to identify the lectome (complete set of lectins) of the corresponding species.    <br>
    <span style='font-weight:bold;'>
    How many predicted lectins ?
  </span>
    <br>Proteins with a specific lectin domain:
    <span style='font-weight:bold;'><?php echo $row_stats['number_lectins']; ?></span> candidate lectins in <span style='font-weight:bold;'><?php echo $row_stats['number_species']; ?></span> species
    <br>At score > 0.25 = 25% similarity:
    <span style='font-weight:bold;'><?php echo $row_stats['number_lectins_25']; ?></span> lectins in <span style='font-weight:bold;'><?php echo $row_stats['number_species_25']; ?></span> species
    <br>At score > 0.5  = 50% similarity:
    <span style='font-weight:bold;'><?php echo $row_stats['number_lectins_50']; ?></span> lectins in <span style='font-weight:bold;'><?php echo $row_stats['number_species_50']; ?></span> species
    <br>
    Last update the <?php echo $last_update . " "; ?> - <a href='./list_predicted_species'>list of all species</a>
    <br>
    <label style='font-size:20px;'>Search for any occurence of a keyword by protein name, species and accession number</label>
    <p>
        ie. Salmonis, Mycobacterium, B9ENV9(UniProt AC) ...
    </p>
</div>
<div style='width:100%;'>
    <div style='width:100%;'>
        <input placeholder="Search" type='text' id='search_lectin_home' name="search_lectin_home" style="font-size:26px;width: 80%; height: 40px;padding-top:5px;margin:0;" onkeydown="if (event.keyCode == 13) { search_keyword_home($('#search_lectin_home').val()); }">
        <button class='btn btn-primary' style="width:20%;height:40px;margin:0;padding:2px;font-size:25px;float:right;border:none;border-radius:5px;" onclick="search_keyword_home($('#search_lectin_home').val());"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
    </div>
    <div style="display: contents;vertical-align: middle;vertical-align:center;width: 100%;height: 50px;">
        <div id="keyword_search_content_home" style='width:100%;max-height:400px;overflow-y:auto;position: relative;'></div>
    </div>
</div>
<script>
    function search_keyword_home(keyword) {
        if (keyword == '') {
            window.location.replace("https://unilectin.eu/predict/search");
            return 0;
        }
        $("#keyword_search_content_home").html("<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>Searching ...</div>");
        $.get("/pages/keyword_search.php?keyword="+keyword, function(data, status){
            $("#keyword_search_content_home").html("");
            $('#keyword_search_content_home').append(data);
        });
    }
</script>
<br>

<div style="width:100%;padding:0;margin:0;display:inline-block;">
    <a href="./search" class="btn btn-primary" style="height:60px;width:50%;float: left;">
        <p style='font-size:20px;'>Search by field</p>
    </a>
    <a href="./domains" class="btn btn-primary" style="height:60px;width:50%;float: left;">
        <p style='font-size:20px;'>Search for domains architectures</p>
    </a>
    <a href="./class_overview" class="btn btn-primary" style="height:60px;width:50%;float: left;">
        <p style='font-size:20px;'>Browse the lectin classes by fold and origin</p>
    </a>
    <a href="./phylogeny" class="btn btn-primary" style="height:60px;width:50%;float: left;">
        <p style='font-size:20px;'>Phylogeny heatmap of the lectin classes</p>
    </a>
</div>

<div class="div-border-title">
    Predicted lectins with a minimum similarity of 50%
</div>
<div class="div-border" style="padding:5px;margin-bottom:5px;">
    <div id = "graphics_container"></div>
</div>
<div id = "graphics_container_class"></div>


<form action="./search.php" method="get" id="search" name="search"  autocomplete="off" style="display:none;">
    <input type="number" id="similarity_score"	name="similarity_score" min="0" max="1" value="0.5" style="width: 100%; height: 30px; text-align: right; float: right">
    <input type='text' id='domain' name="domain" style="width: 100%; height: 30px;padding-top:5px" value="">
    <input type="number" id="nb_domain" name="nb_domain"  min="0" max="100" value=""  style="width: 100%; height: 30px; text-align: right; float: right">
    <input type="number" id="interdomain"  name="interdomain" min="0" max="100" value=""  style="width: 100%; height: 30px; text-align: right; float: right">
    <input type="text"  id="keyword" name="keyword" value="" style="width: 100%; height: 30px; text-align: left; float: right">

    <input type='text' id='superkingdom' name="superkingdom" style="width: 100%; height: 30px;padding-top:5px" value="">
    <input type='text' id='kingdom' name="kingdom" style="width: 100%; height: 30px;padding-top:5px" value="">
    <input type='text' id='phylum' name="phylum" style="width: 100%; height: 30px;padding-top:5px" value="">
    <input type='text' id='species' name="species" style="width: 100%; height: 30px;padding-top:5px" value="">
    <input type="checkbox" id="pathogen_only" name="pathogen_only" style='display: none;'>
    <input type="checkbox" id="uniref_cluster" name="uniref_cluster" style='display: none;'>

    <input type='text' id='domain_pfam' name="domain_pfam" style="width: 100%; height: 30px;padding-top:5px" value="">
    <input type='text' id='domain_pfam_name' name="domain_pfam_name" style="width: 100%; height: 30px;padding-top:5px" value="">
    <input type='text' id='uniprot' name="uniprot" style="width: 100%; height: 30px;padding-top:5px" value="">
    <input type='text' id='pdb' name="pdb" style="width: 100%; height: 30px;padding-top:5px" value="">
    <input type="text" id="protein_type" name="protein_type" value="" style="width: 100%; height: 30px; text-align: left; float: right">
    <input type="text" id="protein_name" name="protein_name" value="" style="width: 100%; height: 30px; text-align: left; float: right">
</form>


<script>
    function load_graphics() {
        $.post("./includes/load_graphics.php", $('#search').serialize(),
            function(data,status){
                $("#graphics_container").html("");
                $('#graphics_container').append(data);
            }
        );
    }
    load_graphics();

    function load_tree_class() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', './includes/graphic_treeview_class');
        xhr.addEventListener('readystatechange', function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                var div = $('#graphics_container_class');
                div.html("");
                div.append(xhr.responseText);
            }
        });
        xhr.send(null);
    }
    load_tree_class();

    function new_search() {
        $("#search").submit();
    }
</script>