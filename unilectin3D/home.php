<title>UniLectin3D interactive lectin database</title>

<script src="/js/d3.v3.min.js"></script>
<script type="text/javascript" src="/js/sunburst_sequences_small.js?v=65468486"></script>

<style>
  .selected{
    color: orange;
    fill: orange;
  }
  .node {
    position: absolute;
    list-style: none;
    cursor: default;
  }
  .node span {
    margin-right: 3px;
  }

  .node .caret {
    font-size: 10px;
  }
  .legend_button:hover {
    stroke: black;
    stroke-width:2;
  }
  .legend_text:hover {
    fill: black;
  }
</style>

<meta name="description" content="UniLectin3D curated database provides information on the lectin proteins and structures for a large number of families. Lectins are carbohydrate-binding proteins, macromolecules that are highly specific for sugars. Lectins perform biological recognition mechanisms involving cells, carbohydrates, and proteins. Lectins also bind bacteria and viruses to their targets.">
<div style="width:100%;background-color:white;display:inline;">
  <center><label class="title_main">UniLectin3D curated database</label>
    <div style="float:right;margin-left:20px;display: inline-block;vertical-align: middle;">
      <div class="sharethis-inline-share-buttons" style="width:130px;height:32px;"></div>
      <a href="https://twitter.com/FBonnardel_BI"><div class="sharethis-inline-follow-buttons" style="background-color: #55acee;border-radius:10px;width:120px;color:white;"></div></a>
    </div>
  </center>
</div>
<script src="//platform-api.sharethis.com/js/sharethis.js#property=5b6057db78eb8b00113e3405&product=inline-share-buttons"></script>

<?php
$request = "select count(distinct(pdb)) as num_rows from lectin";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$total_lectin = $row['num_rows'];

$request = "select count(distinct(pdb)) as num_rows from lectin where iupac != '' ";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$total_lectin_with_glycan = $row['num_rows'];

$request = "select count(distinct(iupac)) as num_rows from lectin where iupac != '' ";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$total_glycan = $row['num_rows'];

$request = "select uniprot from lectin WHERE uniprot != '' GROUP BY uniprot";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$total_seq = mysqli_num_rows($results);

$request = "select count(distinct(title)) as num_ref from lectin_view WHERE title != '' ";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$total_ref = $row['num_ref'];

$request = "SELECT dt as last_update FROM lectin ORDER BY dt DESC LIMIT 1";
$results = mysqli_query($connexion, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexion));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$last_update = $row['last_update'];
?>

<div style='width:100%;'>
  <div  style='padding:5px;font-size: 18px; text-align: justify; text-justify: inter-word;float:left;'>
    <br>
    <span style='font-weight:bold;'>
      What is UniLectin3D ?
    </span>
    <br>
    The UniLectin3D module provides curated information on 3D structures of lectins. Lectins are grouped into families based on the carbohydrates binding domains.
    <br>
    <span style='font-weight:bold;'>
      How many lectins and structures ?
    </span>
    <br>
    <span style='font-weight:bold;'><?php echo $total_lectin;?></span> 3D XRay structures (<span style='font-weight:bold;'><?php echo $total_lectin_with_glycan." ";?></span> with interacting glycan), <span style='font-weight:bold;'><?php echo $total_seq." ";?></span> distinct lectin sequences, <span style='font-weight:bold;'><?php echo $total_glycan." ";?></span> distinct glycans, <span style='font-weight:bold;'><?php echo $total_ref." ";?></span> articles
    <br>
    Last update the <?php echo $last_update." ";?>
    <br>
    <label style='font-size:20px;'>Search for a lectin by name, species, structure and glycan</label>
    <p>
      ie. human, propeller, 1TL2(PDB), Q47200(UniProt), GalNAc, Lewis ...
    </p>
    <div style='width:100%;' class='div-border'>
      <div style="display: contents;vertical-align: middle;vertical-align:center;width: 100%;height: 50px;">
        <div style='width:100%;'>
          <input placeholder="Search" type='text' id='search_lectin_home' name="search_lectin_home" style="font-size:26px;width: 80%; height: 40px;padding-top:5px;margin:0;border:none;" onkeydown="if (event.keyCode == 13) { search_keyword_home($('#search_lectin_home').val()); }">
          <button class='btn btn-primary' style="width:20%;height:40px;margin:0;padding:2px;font-size:25px;float:right;border:none;border-radius:5px;"  onclick="search_keyword_home($('#search_lectin_home').val());"><span class="glyphicon glyphicon-search" aria-hidden="true"></span>
            </div>
          <div id="keyword_search_content_home" style='width:100%;max-height:400px;overflow-y:auto;position: relative;'></div>
        </div>
      </div>
      <div style="width:100%;float:left;margin-top:5px;">
          <a href='./search' class="btn btn-primary" role="button" style="height:60px;width:50%;"><label class="title" style='font-size:30px;'>Search by field</label></a>
          <a href='./glycan_search' class="btn btn-primary" role="button" style="height:60px;width:50%;float:right;"><label class="title" style='font-size:30px;'>Glycan Search</label></a>
      </div>
    <div style="width:100%;padding-right:2px;margin-top:5px;">
        <a href='/predict' class="btn btn-primary" role="button" style="height:80px;width:50%;float:left;"><label class="title" style='font-size:20px;padding:2px;'>LectomeXplore</label>
            <p style='font-size:16px;'>Predicted lectins in genomes</p>
        </a>
        <a href='/propeller' class="btn btn-primary" role="button" style="height:80px;width:50%;"><label class="title" style='font-size:20px;'>PropLec</label>
            <p style='font-size:16px;'>Predicted &beta;-propeller lectins</p>
        </a>
    </div>
      <a href='./newlclass'>Class overview</a> -
      <a href='/curated/protein'>All proteins</a> -
      <a href='/curated/structure'>All structures</a>
    </div>




    <script>
      function search_keyword_home(keyword){
        if(keyword==''){window.location.replace("https://unilectin.eu/unilectin3D/search");return 0; };
        $("#keyword_search_content_home").html("<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>Searching ...</div>");
        $.get("/pages/keyword_search.php?keyword="+keyword, function(data, status){
          $("#keyword_search_content_home").html("");
          $('#keyword_search_content_home').append(data);
        });
      }
    </script>

    <?php
    //DISPLAY THE FAMILY SUNBURST
    ?>
    <style>
      .tooltip{
        position: absolute; 
        text-align: center; 
        width: 150px; 
        height: auto;   
        padding: 2px; 
        font: 12px sans-serif;  
        background: black;  
        border: 0px;          
        border-radius: 8px;
        color:white;
        box-shadow: -3px 3px 15px #888888;
        opacity:0;  

      }  
      .legend {
        padding: 5px;
        text-align: center;
        font-weight: 600;
        fill: #fff;
      }

      .chart {
        position: relative;
        stroke: #fff;
      }

      #explanation, #tutorial {
        top: 5px;
        left: 5px;
        width: 140px;
        text-align: center;
        color: #666;
        z-index: -1;
      }

      #percentage {
        font-size: 1em;
      }
      h2{
        padding:5px;font-size: 18px; text-align: justify; text-justify: inter-word;
      }
    </style>


    <div style="display:inline-block;width:100%;float:left;padding:5px;margin-left:20%;margin-right:20%;">
        <h2>
            Browse by Fold > Class > Familly
        </h2>
        (The number of families is indicated when the mouse is over)
        <div style="position: relative; width:100%; height:260px;display:inline-block;padding:10px;padding-top:0px;">
            <?php
            $rwhere="";
            include("./includes/sunburst_fold.php");
            ?>
        </div>
    </div>
    <div style="display:inline-block;width:50%;float:left;padding:5px;">
        <h2>
            Browse by Origin > Species
        </h2>
        (The number of species is indicated when the mouse is over)
        <div style="position: relative; width:100%; height:260px;display:inline-block;padding:10px;padding-top:0px;">
            <?php
            $rwhere="";
            include("./includes/family_sunburst_small.php");
            ?>
        </div>
    </div>
    <div style="display:inline-block;width:50%;float:left;padding:5px;border-left:1px solid grey;">
        <h2>
            Browse by monosaccharide and associate IUPAC sequence
        </h2>
        (The number of distinct iupac is indicated when the mouse is over)
        <div style="position: relative; width:100%; height:260px;display:inline-block;padding:10px;padding-top:0px;">
            <?php
            $rwhere="";
            include("./includes/sunburst_carbo.php");
            ?>
        </div>
    </div>

    <div style='width:100%;padding-left:3%;padding-right:6%;display: inline;'><div style='width:94%;border-top:1px solid black;display: inline-block;'></div></div>
    <h2>
        Browse by Fold > Class > Familly
    </h2>
    (Click on panel to expand it and on the blue search button to explore)

    <?php
    //DISPLAY THE FAMILY TREE
    $rwhere="";
    include("./includes/tree.php");
    ?>
    <!--   cat list  -->
    <div style='width:100%;padding-left:3%;padding-right:6%;display: inline;'><div style='width:94%;border-top:1px solid black;display: inline-block;'></div></div>

  </div>

  <div class="div-border-title" style="margin-top:30px;">
    SIB ExPASy external links
  </div>
  <div style="width:100%;display:inline-block;margin-bottom:30px" class="btn-group" role="group">
    <a href='https://sugarbind.expasy.org/' class="btn btn-primary" role="button" style="height:120px;width:50%;padding-top:5px;"><label class="title_links">SugarBind</label>
      <p style='font-size:16px;'>carbohydrate sequences related to pathogenic organisms<br>Supported by SIB ExPASy</p>
    </a>
    <a href='https://glyconnect.expasy.org/browser/' class="btn btn-primary" role="button" style="height:120px;width:50%;padding-top:5px;"><label class="title_links">GlyConnect</label>
      <p style='font-size:16px;'>relations between glycan, proteins, taxonomy,<br>expression tissue and diseases<br>Supported by SIB ExPASy</p>
    </a>
  </div>

  <div class="div-border-title">
    External links
  </div>
  <div style="width:100%;display:inline-block;margin-bottom:30px" class="btn-group" role="group">
    <div style="width:100%;display:inline-block;" class="btn-group" role="group">
      <a href='http://www.uniprot.org/' class="btn btn-primary" role="button" style="height:100px;width:50%;padding-top:5px;"><label class="title_links">UniProt</label>
        <p style='font-size:16px;'>protein sequence and functional information<br>Supported by EMBL-EBI</p>
      </a>
      <a href='https://www.ncbi.nlm.nih.gov/protein/' class="btn btn-primary" role="button" style="height:100px;width:50%;padding-top:5px;"><label class="title_links">NCBI Protein DB</label><p style='font-size:16px;'>collection of sequences from several sources<br>Supported by the NCBI</p>
      </a>
    </div>
    <div style="width:100%;display:inline-block;" class="btn-group" role="group">
      <a href='http://www.cazy.org/' class="btn btn-primary" role="button" style="height:100px;width:50%;padding-top:5px;"><label class="title_links">CAZy</label><p style='font-size:16px;'>families of carbohydrate binding enzymes<br>Supported by the Glycogenomics group at AFMB</p>
      </a>
      <a href='http://glyco3d.cermav.cnrs.fr/home.php' class="btn btn-primary" role="button" style="height:100px;width:50%;padding-top:5px;"><label class="title_links">Glyco3D</label>
        <p style='font-size:16px;'>
          cermav.cnrs.fr
          <br> including previous setup of lectin3D
        </p>
      </a>
    </div>
    <div style="width:100%;display:inline-block;" class="btn-group" role="group">
      <a href='https://glycopedia.eu/' class="btn btn-primary" role="button" style="height:100px;width:50%;padding-top:5px;"><label class="title_links">GlycoPedia</label><p style='font-size:16px;'>Important topics in the field of Glycoscience</p></a>
      <a href='https://projects.biotec.tu-dresden.de/plip-web/plip/index' class="btn btn-primary" role="button" style="height:100px;width:50%;padding-top:5px;"><label class="title_links">PLIP</label>
        <p style='font-size:16px;'>Easy and fast identification of noncovalent interactions<br>between proteins and their ligands</p></a>
    </div>
  </div>

