<title>Tutoriel - UniLectin3D</title>
<?php include($_SERVER['DOCUMENT_ROOT']."/unilectin3D/header.php"); ?>

<div style="width:100%;background-color:white;display:inline;">
  <center><label class="title_main">UniLectin3D tutorial</label></center>
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
  <pre>The UniLectin3D database contains the three-dimensional structures of Lectins that have been crystallized with or without their carbohydrate (glycans) ligands and non-carbohydrate ligands (see Background for detailed information).

The following options for searching are available:
&emsp;1.	Keywords
&emsp;2.	Kingdom order, carbohydrate binding site class and species family
&emsp;3.	Monosaccharide and associate IUPAC sequence
&emsp;4.	Search by binding site fold
&emsp;5.	Search by multiple criteria

Once selected, lectins can be explored (and their features pictured and downloaded):
&emsp;6.	Sequence (with the UniProt AC)
&emsp;7.	Structure (with the PDB ID)

For each lectins a detailed page is available with 3D visualization, interactions and links to external databases.
</pre>
</div>

<div class="anchor div-border-title" style='width: 100%;' id='<?php $anchor_id++;echo $anchor_id; ?>'>1.	Searching by Keywords</div>
<div class="div-border" style="margin-bottom: 10px;">
  <img src="/img/tuto_fig1.PNG?526946846" alt="img/tuto_fig1.PNG?526946846" style="width: 60%;" class="center">
  <label>Quick search box</label>
  <pre>
  The search  can be performed by entering keywords by textual input : i.e. human, PDB code, UniProt accession number, lectin name, type of domains, fragment of glycan sequence,  or textual fragments of the title of a publication.,….i.e : human, propeller, 1TL2 (PDB), Q47200 (UniProt), GalNAc, Lewis,….

Example: Entering “human” in the search box, will return the following.
  </pre>
  <img src="/img/tutorial_quick_search.PNG?526946846" alt="img/tutorial_quick_search.PNG?526946846" style="width: 60%;" class="center">
  <label>Quick search results</label>
</div>

<div class="anchor div-border-title" style='width: 100%;' id='<?php $anchor_id++;echo $anchor_id; ?>'>2.	Searching by kingdom order, carbohydrate binding site class and species family</div>
<div class="div-border" style="margin-bottom: 10px;">
  <img src="/img/tuto_fig3.PNG?526946846" alt="img/tuto_fig3.PNG?526946846" style="width: 40%;" class="center">
  <label>Sunburst tree for classification</label>
  <p>
    The inner circle corresponds to the kingdom orders along with their respective percentage of occurrence. For a given kingdom order, (for example animal lectins) the carbohydrate binding site class appears in the central section (for example galectins) whereas the species families can be browsed on the outer section (for example galectin 3).
  </p>
  <img src="/img/tuto_fig6.PNG?526946846" alt="img/tuto_fig6.png" style="width: 60%;" class="center">
  <label>Taxonomic tree for classification exploration</label>
  <p>The taxonomic tree also allows to explore the classification with the tree leaves which can be clicked to expand the tree and access subcategories</p>
</div>

<div class="anchor div-border-title" style='width: 100%;' id='<?php $anchor_id++;echo $anchor_id; ?>'>3.	Searching by monosaccharide and associate IUPAC sequence</div>
<div class="div-border" style="margin-bottom: 10px;">
  <p>
    Glycan are described by their monosaccharide content and encoded in IUPAC condensed format (<a href='http://www.sbcs.qmul.ac.uk/iupac/2carb/38.html'>nomenclature of carbohydrates</a>). <br>Ex. Gal(b1-4)GlcNAc(b1-2)Man(a1-3)[GlcNAc(b1-2)Man(a1-6)]Man
  </p>
  <img src="/img/tuto_fig4.PNG?526946846" alt="img/tuto_fig4.PNG?526946846" style="width: 40%;" class="center">
  <label>Sunburst tree for carbohydrates ligands exploration</label>
  <p>The inner circle correspond to the monosaccharide that compose the carbohydrates chains interacting with the lectins, together with their respective percentage of occurrence. The outer circle correspond to the carbohydrate chains. For a given monosaccharide (D-Galp for example) appears the number of occurrences of glycans containing the selected monosaccharide (for example 29 in the case of Gal(b1-3)GalNAc).</p>
  <br>
  <p>
    Clicking on a selected glycan (for example Gal(b1-3)GalNAc) lists the 3D structures of lectins complexed with this carbohydrate.  
  </p>
  <img src="/img/tuto_fig7.PNG?526946846" alt="img/tuto_fig7.PNG?526946846" style="width: 40%;" class="center">
  <label>Results for carbohydrate ligands exploration</label>
</div>

<div class="anchor div-border-title" style='width: 100%;' id='<?php $anchor_id++;echo $anchor_id; ?>'>4.	Searching by fold</div>
<div class="div-border" style="margin-bottom: 10px;">
  <img src="/img/tuto_fig5.PNG?526946846" alt="img/tuto_fig5.PNG?526946846" style="width: 60%;" class="center">
  <label>Sunburst tree for carbohydrates ligands exploration</label>
  <p>The circle correspond to the fold identified in the crystal structures of lectins used to interact with the carbohydrate chain, together with their respective percentage of occurrence. Buttons are also displayed to access the lectin with the respective fold.</p>
</div>

<div class="anchor div-border-title" style='width: 100%;' id='<?php $anchor_id++;echo $anchor_id; ?>'>5.	Advanced Search, searching by multiple criteria</div>
<div class="div-border" style="margin-bottom: 10px;">
  <img src="/img/tuto_fig8.PNG?526946846" alt="img/tuto_fig8.png" style="width: 60%;" class="center">
  <p>This advanced search option offers a range of criteria to be selected in a combined fashion to search throughout the whole content of the database. The research interface provides multiples criteria to select specific lectins or structures. For every structure multiple manually curated information are available, mostly about the associate carbohydrate.</p>
  <img src="/img/tuto_fig9.PNG?526946846" alt="img/tuto_fig9.png" style="width: 60%;" class="center">
  <label>Advanced search page, search for a specific family</label>
  <p>
    The advanced search offers selection of criteria. Lectin can be searched by structure or by sequence family with the support of drop-down lists. The classification of lectins (Origin, Class, Family) also provides several search criteria. Other criteria pertain to the nature of the fold and taxonomic details of the lectin. Keywords from the title in a reference article can also be searched. A unique feature is the search of fragments of glycan ligands that will output lectins interacting with a given carbohydrate. Finally, a cutoff on the resolution (Å) of the X-ray structure can be used as a filter for selecting of high-quality data. UniLectin3D allows precise taxonomic search for all lectins that have been structurally characterized in a given organism. Another key feature is the option to search lectin structures with oligosaccharide motifs that are complexed within the binding sites.
  </p>
  <p>
    The resolution criteria relates to the resolution of the structure determination (High numeric values of resolution, such as 4 Å, mean poor resolution, while low numeric values, such as 1.5 Å, mean good resolution. 2.05 Å is the median resolution for X-ray crystallographic results in the Protein Data Bank). Whenever the resolution is set to 0, the structures are not filtered.
  </p>
  <img src="/img/tuto_fig10.PNG?526946846" alt="img/tuto_fig10.png" style="width: 60%;" class="center">
  <label>Advanced search 3D XRay structure results</label>
  <img src="/img/tuto_fig11.PNG?526946846" alt="img/tuto_fig11.png" style="width: 60%;" class="center">
  <label>Advanced search sequence result</label>
</div>

<div class="anchor div-border-title" style='width: 100%;' id='<?php $anchor_id++;echo $anchor_id; ?>'>6.	Lectin sequence detailed interface</div>
<div class="div-border" style="margin-bottom: 10px;">
  <p>The lectins can be explored by protein uniprot AC. One lectin can be related with multiples PDB structures, which are displayed in the results together with the ligand.</p>

  <img src="/img/tutorial_protein_viewer.PNG?526946846" alt="img/tutorial_protein_viewer.png" style="width: 60%;" class="center">
  <label>Explore lectins by protein ID</label>
</div>

<div class="anchor div-border-title" style='width: 100%;' id='<?php $anchor_id++;echo $anchor_id; ?>'>7.	XRay structure detailed interface</div>
<div class="div-border" style="margin-bottom: 10px;">
  <p>The lectins can be explored by struture. Each structure is related to a protein with an ID uniprot. For each structure the other structure from the same protein are proposed in the results.</p>
  <img src="/img/tutorial_structure_viewer.PNG?526946846" alt="tutorial_structure_viewer.png" style="width: 60%;" class="center">
  <label>Explore lectins by PDB structure</label>
  <p>Information can be obtained for the protein partner, the glycan partner, and details of their interactions using the Protein-Ligand Interaction Profiler (PLIP) server. Special care was devoted to the description of the bound glycan ligands with the use of simple graphical representation and numerical format for cross-linking to other databases in glycoscience. We conceive the architecture and navigation tools to extend the search to all organisms, as well as to search for all oligosaccharide epitopes complexed within specified binding sites.</p>

  <img src="/img/tutorial_plip_viewer.PNG?526946846" alt="tutorial_plip_viewer.png" style="width: 60%;" class="center">
  <label>JAV viewer from SwissModel display PLIP interactions information between the protein and ligand(s)</label>
  <p>PLIP allows to visualize in details the interactions on the 3D structure, specific interactions types, and the surounding residues</p>

  <img src="/img/pdbeviewer.PNG?526946846" alt="pdbeviewer.png" style="width: 60%;" class="center">
  <label>PDBe domain viewer display the functional domains identified on the PDB structure</label>
  <p>The PDBe functionaol domain viewer provides information on the glycosilated sites, functional domain, carbohydrates binding sites</p>
</div>

<div class="anchor div-border-title" style='width: 100%;' id='<?php $anchor_id++;echo $anchor_id; ?>'>Background</div>
<div class="div-border" style="margin-bottom: 10px;">
  <p>
    The UniLectin3D project gathers structural information on lectins, which are glycan-binding proteins from all origins, along with their interactions with carbohydrate ligands. Among the proteins that interact non-covalently with carbohydrates, lectins bind mono- and oligosaccharides reversibly and specifically while displaying no catalytic or immunological activity. Lectins are oligomeric proteins that can specifically recognize carbohydrates, which as per present knowledge act as macromolecular tools to decipher sugar-encoded messages. Those complex carbohydrates (also referred to as glycans), both in the form of single molecules, or bound to proteins and lipids, are the most abundant class of biomolecules. They are increasingly being implicated in human health and environmental issues. Complex carbohydrates are built for high-density bio-coding, which is at par with proteins and nucleic acids, The information carried by glycans is encoded by their 3D-structure and sometimes by their dynamics. Consequently, the structural characterization of lectin interactions, as a reader of the glyco-code is essential.
  </p>
  <p>
    Lectins are still poorly classified and annotated, and since their functions are based on recognition, we use their 3D-structures as the foundation of project. UniLectin3D is a curated database with a classification proposed on origin and fold with association of literature and functional data such as known specificity. The content of UniLectin3D is centered on 3-dimensional data, using PDB information, with an appropriate curation of the glycan topology. It provides a family-based classification and cross-links to specialized glyco-related databases. In particular, each carbohydrate ligand can be seen (upon one click) as part of the full carbohydrate structures. Finally, the 3D visualization of contacts between the lectin and the ligand, is visualized via the Protein-Ligand Interaction Profiler (PLIP) server. The introduction of such a feature is likely to meet the expectations of lectin specialists. The UniLectin3D contains 1740 lectin structures that have been manually curated; this corresponds to 428 different lectins (as of 2018-08-29). Bibliographic entries cover the 766 published articles describing at least one structure. The first classification level, referred to as origins, separates the lectins into seven different classes, which correspond to the main domains of the living kingdom. The second level orders the lectins according to the protein fold into 75 classes. The third level separates the lectins according to their species in 309 families.
  </p>
  <p>
    Among the 1740 3D structures, 1120 occur as complexed with glycans. The most commonly observed monosaccharides are as follows: Galactose (Gal) 34%(600); N-Acetyl glucosamine (GlcNAc) 17%(302), Glucose (Glc) 15%(275), Mannose (Man) 14%(251), Fucose (Fuc) 10%(179), N-Acetyl galactosamine (GalNAc) 8% (145), sialic acid (Neu5Ac) (134) 7%., but rarer sugars are also observed in complexes with lectin (Rhamnose, Arabinose …). The ligands occur as monosaccharides, but also as oligosaccharides or glycoconjugates. The set of distinct glycan ligands amounts to 188. 
  </p>
</div>
<?php include($_SERVER['DOCUMENT_ROOT']."/unilectin3D/footer.php"); ?>
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