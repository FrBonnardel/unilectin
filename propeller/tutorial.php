<title>Tutoriel - b-propeller lectin prediction database</title>
<?php include($_SERVER['DOCUMENT_ROOT']."/propeller/header.php"); ?>

<div style="min-width:600px;width:100%;background-color:white;display:inline;">
  <center><label class="title_main">UniLectin PropLec tutorial</label></center>
</div>

<style>
  p {
    padding-left: 10px;
    padding-right: 10px;
    text-align: justify;
    text-justify: inter-word;
  }
  pre {
    padding-left: 10px;
    padding-right: 10px;
    text-align: justify;
    text-justify: inter-word;
    background-color:white;
    border:none;
    font-family:inherit;
    white-space:pre-line;
    word-break: keep-all;!important
  }

  .div-border {
    padding-top: 10px;
    padding-bottom: 10px;
  }

  .div-border-title {
    line-height: 50px;
    padding-left: 10px;
    background-color: lightgrey;
    color: black;
    border: 1px solid grey;
  }

  .div-border-title + .div-border-subtitle {
    margin-top: 20px;
  }

  .div-border-subtitle {
    font-size: 18px;
    font-family: Helvetica, Arial, Sans-Serif;
    line-height: 30px;
    width:100%;
    border: 1px solid black;
    border-radius: 5px;
    padding: 0;
    margin: 0;

    padding-left: 10px;
    background-color: lightgrey;
    color: black;
    border: 1px solid grey;
  }

  img {
    padding: 10px;
  }
  label{
    text-align:center;
    display: block;
    width:100%;
    text-decoration: underline;
  }
  .center {
    display: block;
    margin-left: auto;
    margin-right: auto;
  }
</style>
<script>
  function scroll_to_div(id){
    var divPosition = $('#'+id).offset().top - $('#header').height() ;
    divPosition = $('#content_scroll').scrollTop() + divPosition;
    $('#content_scroll').animate({scrollTop: divPosition}, "slow");
  }
</script>
<center><div id="navbar" style='width: 70%;padding: 30px;'></div></center>
<?php $anchor_id=0; ?>

<center>
    <div style="margin-bottom:20px;">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/eOxOf7i6DdU" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
</center>

<div class="anchor div-border-title" style='width: 100%;' id='<?php $anchor_id++;echo $anchor_id; ?>'>Introduction</div>
<div class="div-border" style="margin-bottom: 10px;">
  <pre>
The PropLec database provides predicted propeller lectins in all genomes, ordered by quality scores based on the conservation of their carbohydrate binding site.

The following options for searching are available:
 1.	Keyword (sequence ID, name and annotation)
 2.	b-propeller lectin family
 3.	Number of blades
 4.	Taxonomy (superkingdom, phylum and species)
 5.	Advanced search with multiple criteria
 6.	Domain architectures

Once selected, lectins can be explored (and their features pictured and downloaded) by Sequence (with the NCBI AC and UniProt AC)

For each lectin a detailed panel and page are available with the NCBI gene viewer and a representation of the blade conservation compared to the reference.
</pre>
</div>

<div class="anchor div-border-title" style='width: 100%;' id='<?php $anchor_id++;echo $anchor_id; ?>'>1. Searching by Keywords</div>
<div class="div-border" style="margin-bottom: 10px;">
  <pre>
  The search can be performed by entering keywords by textual input : i.e. salmon, Microbacterium , B9ENV9(uniprot) ...
  </pre>
  <img src="/img/propeller_tuto_1.PNG?v=564164" alt="img/propeller_tuto_1.PNG?v=564164" style="min-width:600px;width: 60%;" class="center">
  <label>Quick search results</label>
  <p>
    The predicted proteins matching the keyword(s) are listed and a link to a page with further detailed is available.
  </p>
</div>

<div class="anchor div-border-title" style='width: 100%;' id='<?php $anchor_id++;echo $anchor_id; ?>'>2. Searching by b-propeller lectin family</div>
<div class="div-border" style="margin-bottom: 10px;">
  <pre>
  The search can be performed by selecting a b-propeller lectin family.
  </pre>
  <img src="/img/propeller_tuto_2.PNG?v=564164" alt="img/propeller_tuto_2.PNG?v=564164" style="min-width:300px;width: 40%;" class="center">
  <label>b-propeller lectin family piechart</label>
  <p>
      Represent the distribution of the predicted lectins by the number of identified blades in the sequence. Each section can be clicked to explore the corresponding predicted lectins.
  </p>
</div>

<div class="anchor div-border-title" style='width: 100%;' id='<?php $anchor_id++;echo $anchor_id; ?>'>4. Searching by Number of blades</div>
<div class="div-border" style="margin-bottom: 10px;">
  <pre>
  The search can be performed by selecting a Number of blades.
  </pre>
  <img src="/img/propeller_tuto_3.PNG?v=564164" alt="img/propeller_tuto_3.PNG?v=564164" style="min-width:600px;width: 60%;" class="center">
  <label>blade number sunburst</label>
  <p>
    Represent the distribution of the predicted lectins by number of identified blades in the sequence. Each section can be clicked to esplore the corresponding predicted lectins.
  </p>
</div>

<div class="anchor div-border-title" style='width: 100%;' id='<?php $anchor_id++;echo $anchor_id; ?>'>3. Searching by Taxonomy</div>
<div class="div-border" style="margin-bottom: 10px;">
  <pre>
  The search can be performed by selecting in the Taxonomy sunburst a superkingdom, phylum or species.
  </pre>
  <img src="/img/propeller_tuto_4a.PNG?v=564164" alt="img/propeller_tuto_4a.PNG?v=564164" style="min-width:300px;width: 40%;" class="center">
  <label>taxonomy sunburst</label>
  <p>
      Predicted lectins species can be explored with the inner circle representing the superkingdom, the second circle the kingdom, the third circle the phylum, the fourth circle the species group and the outer circle the species. Each section can be clicked to get further details.
  </p>
  <img src="/img/propeller_tuto_4b.PNG?v=564164" alt="img/propeller_tuto_4b.PNG?v=564164" style="min-width:600px;width: 80%;" class="center">
  <label>taxonomy tree</label>
  <p>
    The tree viewer allows exploring the predicted lectins taxonomy with a better insight in less represented branches. The nodes can be opened to access in order the superkingdom, kingdom, phylum, species group, and species. Each node label can be clicked to access further details.
  </p>
</div>

<div class="anchor div-border-title" style='width: 100%;' id='<?php $anchor_id++;echo $anchor_id; ?>'>5.	Advanced search with multiple criteria</div>
<div class="div-border" style="margin-bottom: 10px;">
  <pre>
  The search can be performed with multiple criteria on the advanced search page.
  </pre>
  <img src="/img/propeller_tuto_5.PNG?v=564164" alt="img/propeller_tuto_5.PNG?v=564164" style="min-width:600px;width: 60%;" class="center">
  <label>advanced search interface</label>
  <p>
    Predicted propeller lectins can be explored based on selected criteria, ordered by scores. Are available the score threshold by default at 0.25; the propeller family identified, the number of blades identified, the maximum interval of blades top avoid low conserved region between blades, keywords to exclude proteins based on their description by default set with partial, synthetic and undefined keywords, taxonomy, PFAM domains, RefSeq AC, protein name and description (to include) and the UniProt AC.
  </p>
  <p>
    The button checkbox pathogen species allows to keep only species contained in a predefined list of pathogene species (based on the NIH pathogen species list).
  </p>
  <img src="/img/propeller_tuto_6.PNG?v=564164" alt="img/propeller_tuto_6.PNG?v=564164" style="min-width:600px;width: 60%;" class="center">
  <label>advanced search results: overview interactive graphics</label>
  <p>
    Graphics are generated to have an overview of the predicted lectins distribution, for the lectins corresponding to the criteria selected by the user. PropLec families distribution, number of blades distribution, taxonomy sunburst and tree are available as in the homepage. The graphics can be clicked to access further details.
  </p>
  <img src="/img/propeller_tuto_7.PNG?v=564164" alt="img/propeller_tuto_7.PNG?v=564164" style="min-width:600px;width: 60%;" class="center">
  <label>advanced search results: list of predicted lectins</label>
  <p>
      The predicted proteins matching the criteria are ordered by score with 20 results displayed by page. For each predicted lectins features are displayed with the protein name, UniProt AC and RefSeq AC which can be clicked to access the corresponding pages, length of the protein, species, domain the proplec families identified, the number of blades, the similarity score of the predicted protein blades to the reference blade, and the gene list with all chromosome and position encoding this protein. When multiple chromosomes are available they correspond to the different version of the chromosome encoding this exact protein.
    <p>
      For each predicted lectin a 2D sequence feature viewer allows visualizing the localization of the predicted blades and eventual PFAM domains, with a drag and drop button to zoom in the sequence. At the top, a button allows to display further details in a new window with the gene viewer and the blade conservation viewer.
    </p>
</div>

<div class="anchor div-border-title" style='width: 100%;' id='<?php $anchor_id++;echo $anchor_id; ?>'>5.	Predicted lectin detailed interface</div>
<div class="div-border" style="margin-bottom: 10px;">
  <pre>
For each lectins a detailed panel and page are available with the NCBI gene viewer and a representation of the blade conservation compared to the reference.
  </pre>
  <img src="/img/propeller_tuto_8.PNG?v=564164" alt="img/propeller_tuto_8.PNG?v=564164" style="min-width:600px;width: 60%;" class="center">
  <label>Predicted lectin detailed interface: features and gene viewer</label>
  <p>
    The protein features are described on the previous section. The protein encoding gene is represented on its chromozome by the ncbi viewer, when the information is available. The visualisation can be unzoomed and moved by drag and drop to check the surrounding genes information.
  </p>
  
  <img src="/img/propeller_tuto_9.PNG?v=564164" alt="img/propeller_tuto_9.PNG?v=564164" style="min-width:600px;width: 60%;" class="center">
  <label>Predicted lectin detailed interface: blade amino acid conservation</label>
  <p>
      The predicted lectin blades are aligned against the reference blades by muscle. The resulting alignment is represented in two distinct barchart. The first barchart on top contains the amino acid conservation of the predicted blades. The second barchart represent the amino acid conservation of the reference blade. As all blade are aligned the barchart position match for comparison.
  </p>
  <p>
      The binding sites are represented below with the amino acids known to be used to bind a glycan either by a hydrogen bond or by hydrophobic interactions.
  </p>
</div>

<div class="anchor div-border-title" style='width: 100%;' id='<?php $anchor_id++;echo $anchor_id; ?>'>6. Domain architectures</div>
<div class="div-border" style="margin-bottom: 10px;">
  <pre>
  The predicted lectins can be explored by combination of propeller domain(s) with one or more PFAM domains
  </pre>
  <img src="/img/propeller_tuto_10.PNG?v=564164" alt="img/propeller_tuto_10.PNG?v=564164" style="min-width:600px;width: 60%;" class="center">
  <label>Domain architectures interface</label>
  <p>
    For each domain architecture a button allow to display the list of corresponding predicted lectins with the same pattern.
  </p>
</div>

<?php include($_SERVER['DOCUMENT_ROOT']."/propeller/footer.php"); ?>
<script>
  var ToC = "";

  var newLine, el, title, link;

  $(".anchor").each(function() {
    el = $(this);
    title = el.html();
    link = el.attr("id");
    background = el.css('background-color');
    color = el.css('color');

    newLine = "<button style='width:100%;text-align:left;word-break:break-word;white-space: normal;' class='btn btn-primary' onclick=\"scroll_to_div('" + link + "')\">" + title + "</button>";

    ToC += newLine;

  });

  $('#navbar').prepend(ToC);
</script>