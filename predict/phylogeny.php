<?php include($_SERVER['DOCUMENT_ROOT'] . "/predict/header.php"); ?>
    <script src="/js/d3.v3.min.js"></script>

<?php
if (isset($_GET['fold'])) {
    $_POST['fold']=$_GET['fold'];
}
if (isset($_GET['domain'])) {
    $_POST['domain']=$_GET['domain'];
}
?>
<title>Taxonomy heatmap of the predicted lectin classes</title>
<center>
    <label class="title_main" href='./'>Taxonomy heatmap of the predicted lectin classes</label>
</center>
<p>Each species for which one ore multiple lectin have been identified posses it's own set of lectin(s) called lectome. This interface allows to explore the lectome in function of the taxonomy</p>
<p>For example the lectome of Streptomyces can be explored by setting the superkingdom to Bacteria and the Genus to Streptomyces.</p>
<script>
    function scroll_to_div(id) {
        var divPosition = $('#' + id).offset().top - $('#header').height();
        divPosition = $('#content_scroll').scrollTop() + divPosition;
        $('#content_scroll').animate({scrollTop: divPosition}, "slow");
    }

    function load_page() {
        $('#load_page_button').html("loading");
        if ($('#loading').val() == 1) {
            return 0;
        }
        $('#loading').val(1);
        $('#viewer_div').html("");
        $('#viewer_div').append("loading");
        $.post("./includes/load_phylogeny.php", $('#search').serialize(),
            function (data, status) {
                $("#viewer_div").html("");
                $('#viewer_div').append(data);
                $('#load_page_button').html("Load the species vs lectin class heatmap");
                $('#loading').val(0);
            }
        );
    }
    function load_page_2() {
        $('#load_page_button_2').html("loading");
        if ($('#loading').val() == 1) {
            return 0;
        }
        $('#loading').val(1);
        $('#viewer_div').html("");
        $('#viewer_div').append("loading");
        $.post("./includes/load_phylogeny_dual.php", $('#search').serialize(),
            function (data, status) {
                $("#viewer_div").html("");
                $('#viewer_div').append(data);
                $('#load_page_button_2').html("Load the taxonomy vs taxonomy heatmap");
                $('#loading').val(0);
            }
        );
    }
</script>

<div class="div-border-title">Filters</div>
<div class="div-border" style="width: 100%; margin-bottom: 30px;">
    <form action="javascript:void(0);" method="post" id="search" name="search" autocomplete="off">
        <div style="width: 33%;padding:10px;display: inline-block;">
            <input class="form-control" type="hidden" id="activepage"
                   name="activepage" min="0" max="100" value="1"
                   style="width: 100%; height: 30px; text-align: right; float: right">
            <div style="width: 100%;">
                <label style="width: 100%;">Displayed taxonomic level
                    <select name='tax_level' id='tax_level' style="width:100%;height:30px;background-color: white;border: 1px solid lightgrey;border-radius: 5px;">
                        <option value='kingdom'>kingdom</option>
                        <option value='phylum'>phylum</option>
                        <option value='class'>class</option>
                        <option value='family'>family</option>
                        <option value='genus' selected>genus</option>
                        <option value='species'>species</option>
                    </select>
                </label>
            </div>
            <div style="width: 100%;">
                <label style="width: 100%;">Minimum score <input class="form-control" type="number" id="similarity_score" name="similarity_score" min="0" max="10000" value="<?php if (isset($_POST['similarity_score'])) {
                        echo $_POST['similarity_score'];
                    } else {
                        echo '0.25';
                    } ?>" step="0.01" style="width: 100%; height: 30px; text-align: right; float: right">
                </label>
            </div>
            <div style="width: 100%;">
                <label style="width: 100%;">Minimum lectin classes<input class="form-control" type="number" id="min_families" name="min_families" min="0" max="100" value="1" step="1" style="width: 100%; height: 30px; text-align: right; float: right">
                </label>
            </div>
            <div style="width: 100%;">
                <label style="width: 100%;">Lectin class
                    <input list='domain_list' type='text' id='domain' name="domain" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_POST['domain'])) {
                        echo $_POST['domain'];
                    } ?>">
                </label>
                <?php
                $request = "SELECT distinct(domain) from lectinpred_aligned_domains ORDER BY domain ";
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
                                                                                   value="<?php if (isset($_POST['keyword'])) {
                                                                                       echo $_POST['keyword'];
                                                                                   } else {
                                                                                       echo '';
                                                                                   } ?>"
                                                                                   style="width: 100%; height: 30px; text-align: left; float: right">
                </label>
            </div>
            <div style="width: 100%;">
                <label style="width: 100%;">Lectin class to exclude (word1;word2)<input type="text" id="keyword" name="keyword"
                                                                                        value="<?php if (isset($_POST['keyword'])) {
                                                                                            echo $_POST['keyword'];
                                                                                        } else {
                                                                                            echo 'chi-lectin_TCLL;hevein;P-domain_of_calnexin_and_reticulin;ERGIC-VIP_L-type';
                                                                                        } ?>"
                                                                                        style="width: 100%; height: 30px; text-align: left; float: right">
                </label>
            </div>
            <div style="width: 100%; padding: 0;  margin-top: 20px;">
        <span style="width: 100%;"> <label data-toggle='buttons' class='btn btn-default <?php if (isset($_POST['pathogen_only'])) {echo 'active';} ?>' for="pathogen_only" style="width: 100%;">
          <input type="checkbox" id="pathogen_only" name="pathogen_only" style='display: none;' <?php if (isset($_POST['pathogen_only'])) {echo 'checked';} ?>>
          Pathogen species
          </span>
            </div>
            <div style="width: 100%; padding: 0;  margin-top: 20px;">
          <span style="width: 100%;"> <label data-toggle='buttons' class='btn btn-default <?php if (isset($_POST['uniref_cluster'])) {echo 'active';} ?>' for="uniref_cluster" style="width: 100%;">
            <input type="checkbox" id="uniref_cluster" name="uniref_cluster" style='display: none;' <?php if (isset($_POST['uniref_cluster'])) {echo 'checked';} ?>>
            UniRef cluster representative
            </span>
            </div>
        </div>
        <div style="width: 66%;padding:10px;display: inline-block;vertical-align: top; ">
            <div style="width: 100%;">
                <label style="width: 100%;">Superkingdom (Required)
                    <input list='superkingdom_list' type='text' id='superkingdom' name="superkingdom" style="width: 100%; height: 30px;padding-top:5px;border: 1px solid red" value="<?php if (isset($_POST['superkingdom'])) {
                        echo $_POST['superkingdom'];
                    } ?>">
                </label>
                <?php
                $request = "SELECT distinct(superkingdom) from lectinpred_species ORDER BY superkingdom ";
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
                    <input list='kingdom_list' type='text' id='kingdom' name="kingdom" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_POST['kingdom'])) {
                        echo $_POST['kingdom'];
                    } ?>">
                </label>
                <?php
                $request = "SELECT distinct(kingdom) from lectinpred_species ORDER BY kingdom ";
                $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                echo "<datalist name='kingdom_list' id='kingdom_list'  onchange=\"kingdom_refresh(this.value);\">";
                while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    echo "<option value='{$row['kingdom']}'>";
                }
                echo "</datalist>";
                ?>
            </div>
            <div style="width: 100%;">
                <label style="width: 100%;">Phylum (Actinobacteria;Bacteroidetes;Firmicutes;Proteobacteria)
                    <input list='phylum_list' type='text' id='phylum' name="phylum" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_POST['phylum'])) {
                        echo $_POST['phylum'];
                    } ?>">
                </label>
                <?php
                $request = "SELECT distinct(phylum) from lectinpred_species ORDER BY phylum ";
                $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                echo "<datalist name='phylum_list' id='phylum_list'  onchange=\"phylum_refresh(this.value);\">";
                while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    echo "<option value='{$row['phylum']}'>";
                }
                echo "</datalist>";
                ?>
            </div>
            <div style="width: 100%;">
                <label style="width: 100%;">Class
                    <input list='class_list' type='text' id='class' name="class" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_POST['class'])) {
                        echo $_POST['class'];
                    } ?>">
                </label>
                <?php
                $request = "SELECT distinct(supclass) from lectinpred_species ORDER BY supclass ";
                $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                echo "<datalist name='class_list' id='class_list'  onchange=\"class_refresh(this.value);\">";
                while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    echo "<option value='{$row['supclass']}'>";
                }
                echo "</datalist>";
                ?>
            </div>
            <div style="width: 100%;">
                <label style="width: 100%;">Family
                    <input list='family_list' type='text' id='family' name="family" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_POST['family'])) {
                        echo $_POST['family'];
                    } ?>">
                </label>
                <?php
                $request = "SELECT distinct(family) from lectinpred_species ORDER BY family ";
                $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                echo "<datalist name='family_list' id='family_list'  onchange=\"family_refresh(this.value);\">";
                while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    echo "<option value='{$row['family']}'>";
                }
                echo "</datalist>";
                ?>
            </div>
            <div style="width: 100%;">
                <label style="width: 100%;">Genus
                    <input list='genus_list' type='text' id='genus' name="genus" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_POST['sgroup'])) {
                        echo $_POST['sgroup'];
                    } ?>">
                </label>
                <?php
                $request = "SELECT distinct(genus) as genus from lectinpred_species ORDER BY genus ";
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
                    <input list='species_list' type='text' id='species' name="species" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_POST['species'])) {  echo $_POST['species']; } ?>">
                </label>
                <?php
                $request = "SELECT DISTINCT(SUBSTRING_INDEX(species, ' ', 1)) as species FROM `lectinpred_species`";
                $request .= "ORDER BY species ";
                $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                echo "<datalist name='species_list' id='species_list'  onchange=\"species_refresh(this.value);\">";
                while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    echo "<option value='{$row['species']}'>";
                }
                echo "</datalist>";
                ?>
            </div>
        </div>
        </div>
        <button id="load_page_button" class="btn-primary" style="width: 100%;height:40px;font-size:20px;" onclick="load_page();">Load the taxonomy vs lectin class heatmap</button>
        <button id="load_page_button_2" class="btn-primary" style="width: 100%;height:40px;font-size:20px;" onclick="load_page_2();">Load the taxonomy vs taxonomy heatmap</button>
        <input id="loading" name="loading" type="hidden" value="0">
    </form>
    <input id="protein_id" name="protein_id" type="hidden" value="">
</div>
<div id="graphics_viewer_div" name="graphics_viewer_div" style='width: 100%; margin-top: 10px;'></div>
<div id="viewer_div" name="viewer_div" style='width: 100%; margin-top: 10px;'></div>
<center> <input type='text' style="width: 100%; height: 30px;padding-top:5px">
</center>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/predict/footer.php"); ?>