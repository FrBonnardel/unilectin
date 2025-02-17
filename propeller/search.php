<?php include($_SERVER['DOCUMENT_ROOT'] . "/propeller/header.php"); ?>

    <script src="/js/architecture.js" xmlns:right></script>
    <script src="/js/d3.v3.min.js"></script>
    <script src='/js/msaViewer.js' charset='utf-8'></script>
    <script src='/js/binding_site_viewer.js' charset='utf-8'></script>
    <link rel="stylesheet" href="/css/nouislider.min.css">
    <script src="/js/nouislider.min.js"></script>
    <title>PropLec Advanced search</title>
    <center>
        <label class="title_main" href='./'>PropLec Advanced search</label>
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

    <div class="div-border-title">Filters</div>
    <div class="div-border" style="width: 100%; margin-bottom: 30px;">
        <form method="get" id="search" name="search" autocomplete="off">
            <div style="width: 33%;padding:10px;display: inline-block;">
                <input class="form-control" type="hidden" id="activepage"
                       name="activepage" min="0" max="100" value="1"
                       style="width: 100%; height: 30px; text-align: right; float: right">
                <div style="width: 100%;">
                    <label style="width: 100%;">Minimum score <input
                                class="form-control" type="number" id="similarity_score"
                                name="similarity_score" min="0" max="1" style="width: 100%; height: 30px; text-align: right; float: right" value="<?php if (isset($_GET['similarity_score'])) {
                            echo $_GET['similarity_score'];
                        } else {
                            echo '0.25';
                        } ?>" step=".01" >
                    </label>
                </div>
                <div style="width: 100%;">
                    <label style="width: 100%;">Propeller family
                        <input list='domain_list' type='text' id='domain' name="domain" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['domain'])) {
                            echo $_GET['domain'];
                        } ?>">
                    </label>
                    <?php
                    $request = "SELECT distinct(domain) FROM propeller_view ";
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
                    <label style="width: 100%;">Number of blades <input
                                class="form-control" type="number" id="nb_domain" name="nb_domain"
                                min="0" max="100" style="width: 100%; height: 30px; text-align: right; float: right" value="<?php if (isset($_GET['nb_domain'])) {
                            echo $_GET['nb_domain'];
                        } else {
                            echo '0';
                        } ?>" >
                    </label>
                </div>
                <div style="width: 100%;">
                    <label style="width: 100%;">Maximum interval between the blades <input
                                class="form-control" type="number" id="interdomain"
                                name="interdomain" min="0" max="100" style="width: 100%; height: 30px; text-align: right; float: right" value="<?php if (isset($_GET['interdomain'])) {
                            echo $_GET['interdomain'];
                        } else {
                            echo '0';
                        } ?>">
                    </label>
                </div>
                <div style="width: 100%;">
                    <label style="width: 100%;">Keyword to exclude<input type="text"  id="keyword" name="keyword" style="width: 100%; height: 30px; text-align: left; float: right" value="<?php if (isset($_GET['keyword'])) {
                            echo $_GET['keyword'];
                        } else {
                            echo 'partial;synthetic;undefined';
                        } ?>" >
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
                    $request = "SELECT distinct(superkingdom) FROM propeller_view ";
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
                    <label style="width: 100%;">Kingdom
                        <input list='kingdom_list' type='text' id='kingdom' name="kingdom" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['kingdom'])) {
                            echo $_GET['kingdom'];
                        } ?>">
                    </label>
                    <?php
                    $request = "SELECT distinct(kingdom) FROM propeller_view ";
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
                    <label style="width: 100%;">Phylum
                        <input list='phylum_list' type='text' id='phylum' name="phylum" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['phylum'])) {
                            echo $_GET['phylum'];
                        } ?>">
                    </label>
                    <?php
                    $request = "SELECT distinct(phylum) FROM propeller_view ";
                    $request .= "ORDER BY phylum ";
                    $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                    echo "<datalist name='phylum_list' id='phylum_list'  onchange=\"phylum_refresh(this.value);\">";
                    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                        echo "<option value='{$row['phylum']}'>";
                    }
                    echo "</datalist>";
                    ?>
                </div>
                <div style="width: 100%;">
                    <label style="width: 100%;">Species
                        <input list='species_list' type='text' id='species' name="species" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['species'])) {
                            echo $_GET['species'];
                        } ?>">
                    </label>
                    <?php
                    $request = "SELECT distinct(species) FROM propeller_view  ";
                    $request .= "ORDER BY species ";
                    $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                    echo "<datalist name='species_list' id='species_list'  onchange=\"species_refresh(this.value);\">";
                    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                        echo "<option value='{$row['species']}'>";
                    }
                    echo "</datalist>";
                    ?>
                </div>
                <div style="width: 100%; padding: 0;  margin-top: 20px;">
				<span style="width: 100%;"> <label data-toggle='buttons' class='btn btn-default <?php if (isset($_GET['pathogen_only'])) { echo 'active';} ?>' for="pathogen_only" style="width: 100%;">
					<input type="checkbox" id="pathogen_only" name="pathogen_only" style='display: none;' <?php if (isset($_GET['pathogen_only'])) {  echo 'checked'; } ?>>
					pathogen species
					</span>
                </div>
            </div>
            <div style="width: 33%;padding:10px;display: inline-block;vertical-align: top; ">
                <div style="width: 100%;">
                    <label style="width: 100%;">PFAM
                        <input list='pfam_list' type='text' id='domain_pfam' name="domain_pfam" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['domain_pfam'])) {
                            echo $_GET['domain_pfam'];
                        } ?>">
                    </label>
                    <?php
                    $request = "SELECT distinct(pfam_name) FROM propeller_view ";
                    $request .= "ORDER BY pfam_name ";
                    $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                    echo "<datalist name='pfam_list' id='pfam_list'>";
                    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                        $pfam_list = explode(', ', $row['pfam_name']);
                        foreach($pfam_list AS $k => $pfam) {
                            echo "<option value='$pfam'>";
                        }
                    }
                    echo "</datalist>";
                    ?>
                </div>
                <div style="width: 100%;">
                    <label style="width: 100%;">RefSeq
                        <input list='refseq_list' type='text' id='refseq' name="refseq" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['refseq'])) {
                            echo $_GET['refseq'];
                        } ?>">
                    </label>
                    <?php
                    $request = "SELECT distinct(ncbi) FROM propeller_view ORDER BY ncbi ";
                    $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                    echo "<datalist name='refseq_list' id='refseq_list'>";
                    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                        echo "<option value='{$row['ncbi']}'>";
                    }
                    echo "</datalist>";
                    ?>
                </div>
                <div style="width: 100%;">
                    <label style="width: 100%;">Protein name <input type="text"  id="protein_type" name="protein_type" style="width: 100%; height: 30px; text-align: left; float: right" value="<?php if (isset($_GET['protein_type'])) {
                            echo $_GET['protein_type'];
                        } ?>">
                    </label>
                </div>
                <div style="width: 100%;">
                    <label style="width: 100%;">UniProt
                        <input list='uniprot_list' type='text' id='uniprot' name="uniprot" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['uniprot'])) {
                            echo $_GET['uniprot'];
                        } ?>">
                    </label>
                    <?php
                    $request = "SELECT distinct(uniprot) FROM propeller_view ORDER BY uniprot ";
                    $results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
                    echo "<datalist name='uniprot_list' id='uniprot_list'>";
                    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                        echo "<option value='{$row['uniprot']}'>";
                    }
                    echo "</datalist>";
                    ?>
                </div>
            </div>
            <button type="submit" id="load_tree_viewer" class="btn-primary" style="width: 100%;height:40px;font-size:20px;">Load predicted lectins</button>
        </form>
        <button id="load_lectin_viewer_byid_button" class="btn-primary" style="width: 100%; display: none;" onclick="load_lectin_viewer_byid('-1');">Load lectin viewer byid</button>
        <input id="protein_id" name="protein_id" type="hidden" value="">
    </div>
    <div id="graphics_viewer_div" name="graphics_viewer_div" style='width: 100%; margin-top: 10px;'></div>
    <div id="viewer_div" name="viewer_div" style='width: 100%; margin-top: 10px;'></div>
    <script>
        load_page();
    </script>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/propeller/footer.php"); ?>