<title>b-propeller lectin prediction database</title>
<meta name="description" content="PropLec is a module of UniLectin dedicated to the prediction of &beta;-propeller lectins in UniProt and UniParc databases with quality check">

<center>
  <label class="title_main" href='./'>&beta;-propeller lectin prediction platform</label>
</center>
<?php
$request = "select distinct(protein.protein_id) as num_rows FROM tandem_protein protein JOIN tandem_species species ON (protein.species_id = species.species_id) JOIN tandem_aligned_domains domains ON (protein.protein_id = domains.protein_id) WHERE fold = 'prop'";
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
$total_lectin = mysqli_num_rows($results);

$request = "select distinct(protein.protein_id) as num_rows FROM tandem_protein protein JOIN tandem_species species ON (protein.species_id = species.species_id) JOIN tandem_aligned_domains domains ON (protein.protein_id = domains.protein_id) WHERE fold = 'prop' AND score > 0.25";
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
$total_lectin_filtered = mysqli_num_rows($results);

$request = "SELECT UPDATE_TIME FROM information_schema.tables WHERE TABLE_SCHEMA = 'unilecticvbig' AND TABLE_NAME = 'tandem_protein'";
$results = mysqli_query($connexionBIG, $request) or die("SQL Error:<br>$request<br>" . mysqli_error($connexionBIG));
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$last_update = $row['UPDATE_TIME'];
?>
<div  style='padding:5px;font-size: 18px; text-align: justify; text-justify: inter-word;float:left;'>
  <br>
  <span style='font-weight:bold;'>
    What is PropLec ?
  </span>
  <br>
  The PropLec module of lectin is dedicated to the prediction of &beta;-propeller lectins in UniProt and UniParc databases with quality check of the blades and their carbohydrates binding sites.
  <br>
  <span style='font-weight:bold;'>
    How many predicted lectins ?
  </span>
  <br>
  <span style='font-weight:bold;'><?php echo $total_lectin;?></span> predicted lectins and <span style='font-weight:bold;'><?php echo $total_lectin_filtered;?></span> filtered lectins
  <br>
  Last update the <?php echo $last_update." ";?>
  <br>
  <label style='font-size:20px;'>Search for a lectin by name, species, sequence and structure</label>
  <p>
    ie. salmon, Microbacterium , B9ENV9(uniprot) ...
  </p>
</div>
<div style='width:100%;'>
  <div style='width:100%;'>
    <input placeholder="Search" type='text' id='search_lectin_home' name="search_lectin_home" style="font-size:26px;width: 80%; height: 40px;padding-top:5px;margin:0;" onkeydown="if (event.keyCode == 13) { search_keyword_home($('#search_lectin_home').val()); }">
    <button class='btn btn-primary' style="width:20%;height:40px;margin:0;padding:2px;font-size:25px;float:right;border:none;border-radius:5px;"  onclick="search_keyword_home($('#search_lectin_home').val());"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
  </div>
  <div style="display: contents;vertical-align: middle;vertical-align:center;width: 100%;height: 50px;">
    <div id="keyword_search_content_home" style='width:100%;max-height:400px;overflow-y:auto;position: relative;'></div>
  </div>
  <a href='./search'>Advanced search</a> -
    <a href='./protein'>All proteins</a>
</div>
<script>
  function search_keyword_home(keyword){
    if(keyword==''){window.location.replace("https://unilectin.eu/propeller/search");return 0; };
    $("#keyword_search_content_home").html("<div class='div-border' style='color:black;background-color:lightgrey;margin-bottom:5px;'>Searching ...</div>");
      $.get("/pages/keyword_search.php?keyword="+keyword, function(data, status){
          $("#keyword_search_content_home").html("");
          $('#keyword_search_content_home').append(data);
      });
  }
</script>
<br>
<div style="width:100%;padding:0;margin:0;display:inline-block;">
  <button onclick="$('#domain').val('PropLec5A_tachy');new_search();" class="btn btn-primary" style="width:33%;margin:0;float: left;">
    <p style='font-size:20px;'>PropLec5A<br>Tachylectin 2 like (FLF motif)</p>
    <div style="background:url('../img/symbol_FLF.png');background-size: contain;background-repeat:no-repeat;background-position:top center;height:50px;"></div>
  </button>
  <button onclick="$('#domain').val('PropLec6A_RSL_AAL');new_search();" class="btn btn-primary" style="width:33%;margin:0;float: left;">
    <p style='font-size:20px;'>PropLec6A<br>RSL and AAL like (RVY motif)</p>
    <div style="background:url('../img/symbol_RVY.png');background-size: contain;background-repeat:no-repeat;background-position:top center;height:50px;"></div>
  </button>
  <button onclick="$('#domain').val('PropLec6B_tectonin');new_search();" class="btn btn-primary" style="width:33%;margin:0;float: left;">
    <p style='font-size:20px;'>PropLec6B<br>Tectonin like (GVN motif)</p>
    <div style="background:url('../img/symbol_GVN.png');background-size: contain;background-repeat:no-repeat;background-position:top center;height:50px;"></div>
  </button>
</div>
    <div style="width:100%;padding:0;margin:0;display:inline-block;">
  <button onclick="$('#domain').val('PropLec7A_PLL');new_search();" class="btn btn-primary" style="width:33%;margin:0;float: left;">
    <p style='font-size:20px;'>PropLec7A<br>PLL like (EVF motif)</p>
    <div style="background:url('../img/symbol_EVF.png');background-size: contain;background-repeat:no-repeat;background-position:top center;height:50px;"></div>
  </button>
  <button onclick="$('#domain').val('PropLec7B_PVL');new_search();" class="btn btn-primary" style="width:33%;margin:0;float: left;">
    <p style='font-size:20px;'>PropLec7B<br>PVL like (GFG motif)</p>
    <div style="background:url('../img/symbol_GFG.png');background-size: contain;background-repeat:no-repeat;background-position:top center;height:50px;"></div>
  </button>
    <button onclick="$('#domain').val('PropLec7C_BPL_CVL');new_search();" class="btn btn-primary" style="width:33%;margin:0;float: left;">
        <p style='font-size:20px;'>PropLec7C<br>BPL & CVL like</p>
    </button>
</div>
<div style="width:100%;padding:0;margin:0;display:inline-block;">
  <a href="./pathogens" class="btn btn-primary" style="height:60px;width:50%;float: left;">
    <p style='font-size:20px;'>List pathogen B-propeller lectins</p>
  </a>
  <a href="./domains" class="btn btn-primary" style="height:60px;width:50%;float: left;">
    <p style='font-size:20px;'>Explore domains assembly</p>
  </a>
</div>
<!--<img src="img/propeller_family.PNG" alt="Mountain View" style="width:100%">-->

<script src="/js/d3.v3.min.js"></script>
<div id="graphics_viewer_div" name="graphics_viewer_div" style='width: 100%; margin-top: 10px;'></div>

<form action="./search.php" method="get" id="search" name="search"  autocomplete="off" style="display:none;">
  <input type="number" id="similarity_score"	name="similarity_score" min="0" max="1" value="0.25" style="width: 100%; height: 30px; text-align: right; float: right">
  <input type='text' id='domain' name="domain" style="width: 100%; height: 30px;padding-top:5px" value="">
  <input type="number" id="nb_domain" name="nb_domain"  min="0" max="100" value=""  style="width: 100%; height: 30px; text-align: right; float: right">
  <input type="number" id="interdomain"  name="interdomain" min="0" max="100" value=""  style="width: 100%; height: 30px; text-align: right; float: right">
  <input type="text"  id="keyword" name="keyword" value="" style="width: 100%; height: 30px; text-align: left; float: right">

  <input type='text' id='superkingdom' name="superkingdom" style="width: 100%; height: 30px;padding-top:5px" value="">
  <input type='text' id='kingdom' name="kingdom" style="width: 100%; height: 30px;padding-top:5px" value="">
  <input type='text' id='phylum' name="phylum" style="width: 100%; height: 30px;padding-top:5px" value="">
  <input type='text' id='species' name="species" style="width: 100%; height: 30px;padding-top:5px" value="">
  <input type="checkbox" id="pathogen_only" name="pathogen_only" style='display: none;'>

  <input type='text' id='domain_pfam' name="domain_pfam" style="width: 100%; height: 30px;padding-top:5px">
  <input type='text' id='uniprot' name="uniprot" style="width: 100%; height: 30px;padding-top:5px">
  <input type='text' id='pdb' name="pdb" style="width: 100%; height: 30px;padding-top:5px">
  <input type="text" id="protein_type" name="protein_type" value="" style="width: 100%; height: 30px; text-align: left; float: right">
  <input type="text" id="protein_name" name="protein_name" value="" style="width: 100%; height: 30px; text-align: left; float: right">
</form>

<script>
  $('#graphics_viewer_div').html("");
  $('#graphics_viewer_div').append("loading");
  $.post("./includes/load_graphics.php", $('#search').serialize(),
         function(data,status){
            $("#graphics_viewer_div").html("");
            $('#graphics_viewer_div').append(data);
          }
        );
  
function new_search(){
  $("#activepage").val(1);
  $( "#search" ).submit();
}
</script>
