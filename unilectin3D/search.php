<?php include($_SERVER['DOCUMENT_ROOT'] . "/unilectin3D/header.php"); ?>
    <title>Field search in UniLectin3D</title>
    <meta name="description" content="Lectin3D V2 interactive database provides information on the lectin proteins and structures for a large number of families.">

    <center>
        <label class="title_main">Fields search in UniLectin3D</label>
    </center>

    <link rel="stylesheet" type="text/css" href="/css/LiteMol-plugin.css">
    <script src="/js/bundle-bundle_litemol-core_defer.js"></script>

    <script src="/js/protvista.js"></script>
    <link href="/css/protvista.css" rel="stylesheet"/>
    <script>var ProtVista = require('ProtVista');</script>

<?php
//echo '<pre>'; print_r($_GET); echo '</pre>';
?>


    <script>
        function load_page() {
            $('#viewer_div').html("");
            $('#viewer_div').append("loading");
            $.post("/unilectin3D/includes/search_parser.php",
                $('#search').serialize(),
                function (data, status) {
                    $("#viewer_div").html("");
                    $('#viewer_div').append(data);
                }
            );
        }

        function load_structures() {
            $('#results_type').val("structures");
            $('#search').submit();
        }

        function load_lectins() {
            $('#results_type').val("lectins");
            $('#search').submit();
        }
    </script>
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

        function load_glycoct(div, div_pic, iupac) {
            console.log(iupac);
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
                    $('#' + div).html(data.responseText);
                    load_glycoct_svg(div_pic, div);
                }
            });
        }

        function load_glycoct_svg(div, div_glycoct) {
            var element = document.getElementById(div_glycoct);
            var glycoct = element.innerHTML;
            console.log(glycoct);
            $.ajax({
                url: "https://glycoproteome.expasy.org/glycoUtils/utils/image/generate",
                type: "POST",
                data: JSON.stringify({"glycoCtCode": glycoct, "notation": "cfg"}),
                dataType: 'json',
                contentType: "application/json",
                complete: function (data) {
                    $('#' + div).html(data.responseText);
                    var width = $('#' + div).find("rect")[0].attributes.width.value;
                    var height = $('#' + div).find("rect")[0].attributes.height.value;
                    $('#' + div).width(width);
                    $('#' + div).height(height);
                    $('#' + div).find("svg")[0].setAttribute('width', width);
                    $('#' + div).find("svg")[0].setAttribute('height', height);
                }
            });
        }
    </script>
    <form method="get" id="search" autocomplete="off" name="search">
        <div class="div-border" style="width: 100%; margin-bottom: 5px;display: inline-block;">
            <div style="width: 50%;display: inline-block;padding:5px;">
                <div style="width: 100%;">
                    <label style="width: 100%;">PDB
                        <input list='keyword_list' type='text' id='pdb' name="pdb" style="width: 100%; height: 30px;padding-top:5px;" value="<?php if (isset($_GET['pdb'])) {
                            echo $_GET['pdb'];
                        } ?>"></label>
                </div>
                <?php
                $request = "SELECT distinct(pdb) from lectin_view ";
                $request .= "ORDER BY pdb ";
                $results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
                echo "<datalist name='pdb_list' id='pdb_list'>";
                while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    echo "<option value='{$row['pdb']}'>";
                }
                echo "</datalist>";
                ?>
                <div style="width: 100%;">
                    <label style="width: 100%;">UniProt AC
                        <input list='keyword_list' type='text' id='uniprot' name="uniprot" style="width: 100%; height: 30px;padding-top:5px;" value="<?php if (isset($_GET['uniprot'])) {
                            echo $_GET['uniprot'];
                        } ?>"></label>
                </div>
                <?php
                $request = "SELECT distinct(fold) from lectin_view ";
                $request .= "ORDER BY fold ";
                $results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
                echo "<datalist name='fold_list' id='fold_list'>";
                while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    echo "<option value='{$row['fold']}'>";
                }
                echo "</datalist>";
                ?>
                <div style="width: 100%;">
                    <label style="width: 100%;">Fold
                        <input list='fold_list' type='text' id='fold' name="fold" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['fold'])) {
                            echo $_GET['fold'];
                        } ?>">
                    </label>
                    <?php
                    $request = "SELECT distinct(fold) from lectin_view ";
                    $request .= "ORDER BY fold ";
                    $results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
                    echo "<datalist name='fold_list' id='fold_list'>";
                    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                        echo "<option value='{$row['fold']}'>";
                    }
                    echo "</datalist>";
                    ?>
                </div>
                <div style="width: 100%;">
                    <label style="width: 100%;">Class
                        <input list='class_list' type='text' id='class' name="class" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['class'])) {
                            echo $_GET['class'];
                        } ?>">
                    </label>
                    <?php
                    $request = "SELECT distinct(class) from lectin_view ORDER BY class ";
                    $results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
                    echo "<datalist name='class_list' id='class_list'>";
                    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                        echo "<option value='{$row['class']}'>";
                    }
                    echo "</datalist>";
                    ?>
                </div>
                <div style="width: 100%;">
                    <label style="width: 100%;">Family
                        <input list='family_list' type='text' id='family' name="family" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['family'])) {
                            echo $_GET['family'];
                        } ?>">
                    </label>
                    <?php
                    $request = "SELECT distinct(family) from lectin_view ";
                    $request .= "ORDER BY family ";
                    $results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
                    echo "<datalist name='family_list' id='family_list'>";
                    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                        echo "<option value='{$row['family']}'>";
                    }
                    echo "</datalist>";
                    ?>
                </div>
                <input class="form-control" type="hidden" id="activepage"
                       name="activepage" min="0" max="100" value="1"
                       style="width: 20em; height: 30px; text-align: right; float: right">
                <input class="form-control" type="hidden" id="results_type" name="results_type" value="<?php if (isset($_GET['results_type'])) {
                    echo $_GET['results_type'];
                } else { echo "structures";} ?>">
            </div>
            <div style="width: 50%;display: inline-block;float:right;padding:5px;">
                <div style="width: 100%;">
                    <label style="width: 100%;">origin
                        <input list='origin_list' type='text' id='origin' name="origin" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['origin'])) {
                            echo $_GET['origin'];
                        } ?>">
                    </label>
                    <?php
                    $request = "SELECT distinct(origin) from lectin_view ";
                    $request .= "ORDER BY origin ";
                    $results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
                    echo "<datalist name='origin_list' id='origin_list'>";
                    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                        echo "<option value='{$row['origin']}'>";
                    }
                    echo "</datalist>";
                    ?>
                </div>
                <div style="width: 100%;">
                    <label style="width: 100%;">species
                        <input list='species_list' type='text' id='species' name="species" style="width: 100%; height: 30px;padding-top:5px;" value="<?php if (isset($_GET['species'])) {
                            echo $_GET['species'];
                        } ?>">
                    </label>
                    <?php
                    $request = "SELECT distinct(species) from lectin_view ";
                    $request .= "ORDER BY species ";
                    $results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
                    echo "<datalist name='species_list' id='species_list'>";
                    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                        echo "<option value='{$row['species']}'>";
                    }
                    echo "</datalist>";
                    ?>
                </div>
                <div style="width: 100%;">
                    <label style="width: 100%;">resolution threshold (&Aring;)</label>
                    <span style="width:9%;height:30px;padding:5px;font-size: 14px;border:1px solid darkgrey;float:left;"><=</span><input type='number' id='resolution' name="resolution" min=0 max=10 step="0.01" style="width: 90%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['resolution'])) {
                        echo $_GET['resolution'];
                    } else {echo '0';} ?>">
                </div>
                <div style="width: 100%;">
                    <label style="width: 100%;">monosaccharide (ie. L-Fucp, D-Galp, D-GlcpNAc, D-Neup5Ac ...)
                        <input list='monosac_list' type='text' id='monosac' name="monosac" style="width: 100%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['monosac'])) {
                            echo $_GET['monosac'];
                        } ?>">
                        <datalist name='monosac_list' id='monosac_list'>
                            <option value='D-Abep'>
                            <option value='D-Arap'>
                            <option value='D-Fruf'>
                            <option value='D-Frup'>
                            <option value='D-Fucp'>
                            <option value='D-Galf'>
                            <option value='D-GalNp'>
                            <option value='D-Galp'>
                            <option value='D-GalpN'>
                            <option value='D-GalpNAc'>
                            <option value='D-GlcANAcp'>
                            <option value='D-Glcp'>
                            <option value='D-GlcpN'>
                            <option value='D-GlcpNAc'>
                            <option value='D-Manp'>
                            <option value='D-ManpN'>
                            <option value='D-ManpNAc'>
                            <option value='D-Murp'>
                            <option value='D-Neup5Ac'>
                            <option value='D-Neup5Gc'>
                            <option value='D-Talp'>
                            <option value='D-Tyvp'>
                            <option value='D-Xylp'>
                            <option value='L-Fucp'>
                            <option value='L-Galp'>
                            <option value='L-Rhap'>
                            <option value='LD-Hepp'>
                            <option value='LD-manHepp'>
                            <option value='DD-manHepp'>
                        </datalist>
                    </label>
                </div>
                <div style="width: 100%;">
                    <label style="width: 100%;">IUPAC condensed (ie. Gal(b1-4)GlcNAc(b1-3)Gal(b1-4)Glc)
                        <input list='iupac_list' type='text' id='iupac' name="iupac" style="width: 70%; height: 30px;padding-top:5px" value="<?php if (isset($_GET['iupac'])) {
                            echo $_GET['iupac'];
                        } ?>">
                        <span style="width: 30%;"> <label data-toggle='buttons' class='btn btn-default <?php if (isset($_GET['iupac_exact'])) {
                                echo 'active';
                            } ?>' for="iupac_exact" style="width: 100%;height:30px;">
					<input <?php if (isset($_GET['iupac_exact'])) {
                        echo 'checked';
                    } ?> type="checkbox" id="iupac_exact" name="iupac_exact" style='display: none;'>
					exact motif occurrence
					</span>
                    </label>
                    <?php
                    $request = "SELECT distinct(iupac) as iupac from lectin_view WHERE iupac != ''";
                    $request .= "ORDER BY iupac ";
                    $results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
                    echo "<datalist name='iupac_list' id='iupac_list'>";
                    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                        echo "<option value='{$row['iupac']}'>";
                    }
                    echo "</datalist>";
                    ?>
                </div>
            </div>
        </div>
    </form>
    <div style="width:100%;display:inline-block;margin-bottom:20px" class="btn-group" role="group">
        <button class="btn btn-primary" role="button" style="height:40px;width:50%;padding:5px;" onclick="load_structures();">
            <p style='font-size:20px;'>Explore X-Ray structures</p>
            </a></button>
        <button class="btn btn-primary" role="button" style="height:40px;width:50%;padding:5px;" onclick="load_lectins();">
            <p style='font-size:20px;'>Explore lectins grouped by protein</p>
            </a></button>
    </div>


    <div id="viewer_div" name="viewer_div"
         style='width: 100%; margin-top: 10px;'></div>
    <script>
        $(document).ready(function () {
            load_page();
        });
    </script>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/unilectin3D/footer.php"); ?>