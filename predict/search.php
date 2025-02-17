<script src="../js/architecture.js" xmlns:right></script>
<script src="../js/d3.v3.min.js"></script>
<script src='../js/msaViewer.js' charset='utf-8'></script>
<script src='../js/binding_site_viewer.js' charset='utf-8'></script>
<link rel="stylesheet" href="../css/nouislider.min.css">
<script src="../js/nouislider.min.js"></script>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/predict/header.php"); ?>

<title>LectomeXplore field search</title>
<center>
    <label class="title_main" href='./'>LectomeXplore field search</label>
</center>
<script>
    function scroll_to_div(id) {
        var divPosition = $('#' + id).offset().top - $('#header').height();
        divPosition = $('#content_scroll').scrollTop() + divPosition;
        $('#content_scroll').animate({scrollTop: divPosition}, "slow");
    }

    function new_search() {
        $("#activepage").val(1);
        load_page();
    }

    function load_page() {
        $('#load_page_button').html("loading");
        if ($('#loading').val() == 1) {
            return 0;
        }
        $('#loading').val(1);
        $('#viewer_div').html("");
        $('#viewer_div').append("loading");
        $.post("./includes/search_parser.php", $('#search').serialize(),
            function (data, status) {
                $("#viewer_div").html("");
                $('#viewer_div').append(data);
            }
        );
        $('#graphics_viewer_div').html("");
        $('#graphics_viewer_div').append("loading");
        $.post("./includes/load_graphics.php", $('#search').serialize(),
            function (data, status) {
                $("#graphics_viewer_div").html("");
                $('#graphics_viewer_div').append(data);
                $('#load_page_button').html("Load predicted lectins");
                $('#loading').val(0);
            }
        );
    }

    function load_lectin_viewer_details(protein_id) {
        var button = $("#button_load_details_" + protein_id);
        button.find('span').toggleClass('glyphicon-plus').toggleClass('glyphicon-minus');
        var div = $('#lectin_viewer_container_' + protein_id);
        if (button.hasClass("btn-danger")) {
            button.removeClass("btn-danger");
            button.addClass("btn-success");
            div.html("");
        }
        else {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', './includes/viewer_details.php?protein_id=' + protein_id);
            xhr.addEventListener('readystatechange', function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    div.html("");
                    div.append(xhr.responseText);
                    scroll_to_div("lectin_viewer_container_" + protein_id);
                    button.removeClass("btn-success");
                    button.addClass("btn-danger");
                }
            });
            xhr.send(null);
        }
    }
</script>

<form method="get" id="search" name="search" autocomplete="off">
<div class="div-border-title">Please select a score and optional other criteria</div>
<div class="div-border" style="width: 100%; margin-bottom: 10px;">
        <div style="width: 33%;padding:10px;display: inline-block;">
            <input class="form-control" type="hidden" id="activepage"
                   name="activepage" min="0" max="100" value="1"
                   style="width: 100%; height: 30px; text-align: right; float: right">
            <div style="width: 100%;">
                <label style="width: 100%;">Minimum score (similarity to the reference)<input class="form-control" type="number" id="similarity_score" name="similarity_score" min="0" max="10000" value="<?php if (isset($_GET['similarity_score'])) {
                        echo $_GET['similarity_score'];
                    } else {
                        echo '0.25';
                    } ?>" step="0.01" style="width: 100%; height: 30px; text-align: right; float: right">
                </label>
            </div>
            <div style="width: 100%;">
                <label style="width: 100%;">Fold
                    <input list='fold_list' type='text' id='fold' name="fold" style="width: 100%; height: 30px;padding-top:5px"
                    value="<?php if (isset($_GET['fold'])) {echo $_GET['fold'];} ?>">
                </label>
                <?php
                $request = "SELECT distinct(fold) from lectinpred_aligned_domains ";
                $request .= "ORDER BY fold ";
                $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                echo "<datalist name='fold_list' id='fold_list'>";
                while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    echo "<option value='{$row['fold']}'>";
                }
                echo "</datalist>";
                ?>
            </div>
            <div style="width: 100%;">
                <label style="width: 100%;">Lectin class
                    <input list='domain_list' type='text' id='domain' name="domain" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['domain'])) {
                        echo $_GET['domain'];
                    } ?>">
                </label>
                <?php
                $request = "SELECT distinct(domain) from lectinpred_aligned_domains ";
                $request .= "ORDER BY domain ";
                $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                echo "<datalist name='domain_list' id='domain_list'  onchange=\"domain_refresh(this.value);\">";
                while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    echo "<option value='{$row['domain']}'>";
                }
                echo "</datalist>";
                ?>
            </div>
            <div style="width: 100%;">
                <label style="width: 100%;">Keyword to exclude (word1;word2)<input type="text" id="keyword" name="keyword"
                                                                     value="<?php if (isset($_GET['keyword'])) {
                                                                         echo $_GET['keyword'];
                                                                     } else {
                                                                         echo '';
                                                                     } ?>"
                                                                     style="width: 100%; height: 30px; text-align: left; float: right">
                </label>
            </div>
        </div>
        <div style="width: 33%;padding:10px;display: inline-block;vertical-align: top; ">
            <div style="width: 100%;">
                <label style="width: 100%;">Superkingdom
                    <input list='superkingdom_list' type='text' id='superkingdom' name="superkingdom" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['superkingdom'])) {
                        echo $_GET['superkingdom'];
                    } ?>">
                </label>
                <?php
                $request = "SELECT distinct(superkingdom) from lectinpred_species ";
                $request .= "ORDER BY superkingdom ";
                $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                echo "<datalist name='superkingdom_list' id='superkingdom_list'  onchange=\"superkingdom_refresh(this.value);\">";
                while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    echo "<option value='{$row['superkingdom']}'>";
                }
                echo "</datalist>";
                ?>
            </div>
            <div style="width: 100%;">
                <label style="width: 100%;">kingdom (Metazoa;Fungi;Viridiplantae)
                    <input list='kingdom_list' type='text' id='kingdom' name="kingdom" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['kingdom'])) {
                        echo $_GET['kingdom'];
                    } ?>">
                </label>
                <?php
                $request = "SELECT distinct(kingdom) from lectinpred_species ";
                $request .= "ORDER BY kingdom ";
                $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                echo "<datalist name='kingdom_list' id='kingdom_list'  onchange=\"kingdom_refresh(this.value);\">";
                while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    echo "<option value='{$row['kingdom']}'>";
                }
                echo "</datalist>";
                ?>
            </div>
            <div style="width: 100%;">
                <label style="width: 100%;">Phylum (Chordata;Arthropoda;Nematoda) (Actinobacteria;Bacteroidetes) (Firmicutes;Proteobacteria)
                    <input list='phylum_list' type='text' id='phylum' name="phylum" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['phylum'])) {
                        echo $_GET['phylum'];
                    } ?>">
                </label>
                <?php
                $request = "SELECT distinct(phylum) from lectinpred_species ";
                $request .= "ORDER BY phylum ";
                $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                echo "<datalist name='phylum_list' id='phylum_list'  onchange=\"phylum_refresh(this.value);\">";
                while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    echo "<option value='{$row['phylum']}'>";
                }
                echo "</datalist>";
                ?>
            </div>
        </div>
    <div style="width: 33%;padding:10px;display: inline-block;vertical-align: top; ">
            <div style="width: 100%;">
                <label style="width: 100%;">Genus
                    <input list='genus_list' type='text' id='genus' name="genus" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['genus'])) {
                        echo $_GET['genus'];
                    } ?>">
                </label>
                <?php
                $request = "SELECT distinct(genus) from lectinpred_species ORDER BY genus ";
                $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                echo "<datalist name='genus_list' id='genus_list'  onchange=\"sgroup_refresh(this.value);\">";
                while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    echo "<option value='{$row['genus']}'>";
                }
                echo "</datalist>";
                ?>
            </div>
            <div style="width: 100%;">
                <label style="width: 100%;">Species
                    <input list='species_list' type='text' id='species' name="species" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['species'])) {  echo $_GET['species']; } ?>">
                </label>
                <?php
                $request = "SELECT DISTINCT(sgroup) as sgroup FROM lectinpred_species ORDER BY sgroup ";
                $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                echo "<datalist name='species_list' id='species_list'  onchange=\"species_refresh(this.value);\">";
                while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    echo "<option value='{$row['sgroup']}'>";
                }
                echo "</datalist>";
                ?>
            </div>
        </div>
        <input id="loading" name="loading" type="hidden" value="0">
</div>

<div class="div-border-title">Bioinformatics detailed criteria
   <button class='btn-primary' type='button' style='float:right;height:30px;' data-toggle='collapse' data-target='#detailed_criteria'>Expand</button>
</div>
<div class="div-border collapse" style="width: 100%; margin-bottom: 10px;" id="detailed_criteria">
    <div style="width: 33%;padding:10px;display: inline-block;vertical-align: top; ">
        <div style="width: 100%;">
            <label style="width: 100%;">Protein name <input type="text" id="protein_name" name="protein_name" value="<?php if (isset($_GET['protein_name'])) {  echo $_GET['protein_name']; } ?>"
                style="width: 100%; height: 30px; text-align: left; float: right">
            </label>
        </div>
        <div style="width: 100%;">
            <label style="width: 100%;">Protein AC
                <input type='text' id='uniprot' name="uniprot" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['uniprot'])) {  echo $_GET['uniprot']; } ?>">
            </label>
        </div>
        <div style="width: 100%;">
            <label style="width: 100%;">PFAM
                <input list='pfam_list' type='text' id='domain_pfam' name="domain_pfam" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['domain_pfam'])) {  echo $_GET['domain_pfam']; } ?>">
            </label>
            <?php
            $request = "SELECT distinct(pfam) from lectinpred_pfam ORDER BY pfam ";
            $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
            echo "<datalist name='pfam_list' id='pfam_list'>";
            while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                echo "<option value='{$row['pfam']}'>";
            }
            echo "</datalist>";
            ?>
        </div>
        <div style="width: 100%;">
            <label style="width: 100%;">PFAM name
                <input list='pfam_name_list' type='text' id='domain_pfam_name' name="domain_pfam_name" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['domain_pfam_name'])) {  echo $_GET['domain_pfam_name']; } ?>">
            </label>
            <?php
            $request = "SELECT distinct(name) from lectinpred_pfam ORDER BY name ";
            $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
            echo "<datalist name='pfam_name_list' id='pfam_name_list'>";
            while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                echo "<option value='{$row['name']}'>";
            }
            echo "</datalist>";
            ?>
        </div>
    </div>
    <div style="width: 33%;padding:10px;display: inline-block;vertical-align: top; ">
        <div style="width: 100%;">
            <label style="width: 100%;">Chromosome
                <input type='text' id='gene' name="gene" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['gene'])) {echo $_GET['gene'];} ?>">
            </label>
        </div>
        <div style="width: 100%; padding: 0;  margin-top: 20px;">
        <span style="width: 100%;"> <label data-toggle='buttons' class='btn btn-default <?php if (isset($_GET['pathogen_only'])) {echo 'active';} ?>' for="pathogen_only" style="width: 100%;">
          <input type="checkbox" id="pathogen_only" name="pathogen_only" style='display: none;' <?php if (isset($_GET['pathogen_only'])) {echo 'checked';} ?>>
          Pathogen species
          </span>
        </div>
        <div style="width: 100%; padding: 0;  margin-top: 20px;">
          <span style="width: 100%;"> <label data-toggle='buttons' class='btn btn-default <?php if (isset($_GET['uniref_cluster'])) {echo 'active';} ?>' for="uniref_cluster" style="width: 100%;">
            <input type="checkbox" id="uniref_cluster" name="uniref_cluster" style='display: none;' <?php if (isset($_GET['uniref_cluster'])) {echo 'checked';} ?>>
            UniRef cluster representative
            </span>
        </div>
        <div style="width: 100%; padding: 0;  margin-top: 20px;">
          <span style="width: 100%;"> <label data-toggle='buttons' class='btn btn-default <?php if (isset($_GET['pdb_only'])) {echo 'active';} ?>' for="pdb_only" style="width: 100%;">
            <input type="checkbox" id="pdb_only" name="pdb_only" style='display: none;' <?php if (isset($_GET['pdb_only'])) {echo 'checked';} ?>>
            PDB structures only
            </span>
        </div>
    </div>
    <div style="width: 33%;padding:10px;display: inline-block;vertical-align: top; ">
        <div style="width: 100%; padding: 0;  margin-top: 20px;">
          <span style="width: 100%;"> <label data-toggle='buttons' class='btn btn-default <?php if (isset($_GET['multiple_class'])) {echo 'active';} ?>' for="multiple_class" style="width: 100%;">
            <input type="checkbox" id="multiple_class" name="multiple_class" style='display: none;' <?php if (isset($_GET['multiple_class'])) {echo 'checked';} ?>>
            Multiple lectin classes
            </span>
        </div>
    </div>
</div>
<button type="submit" id="load_page_button" class="btn-primary" style="width: 100%;height:40px;font-size:20px;margin-top: 10px;">Explore the lectins</button>
</form>
<div id="graphics_viewer_div" name="graphics_viewer_div" style='width: 100%; margin-top: 10px;'></div>
<div id="viewer_div" name="viewer_div" style='width: 100%; margin-top: 10px;'></div>

<?php

if(isset($_GET['similarity_score'])){
    echo "<script>load_page();</script>";
}

?>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/predict/footer.php"); ?>