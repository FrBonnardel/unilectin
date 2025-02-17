<?php include($_SERVER['DOCUMENT_ROOT']."/header.php"); ?>
<script src="/js/d3.v3.min.js"></script>

<center>
<div>
  <h1 class="logo-text" style="font-size: 52">
    <img src='https://glyconnect.expasy.org/api/assets/images/glyconnect_logo.png' style="max-width:15%;"/></li> GlyConnect
  </h1>
</div>
</center>

<?php
$data = "<script>var dataset = [";
$data.= "{name:'Taxonomy', value:1, color:'#CA2A2C'},";
$data.= "{name:'Protein', value:1, color:'#f89406'},";
$data.= "{name:'Site', value:1, color:'#cc6600'},";
$data.= "{name:'Tissue', value:1, color:'#46a546'},";
$data.= "{name:'Structure', value:1, color:'#018FD5'},";
$data.= "{name:'Reference', value:1, color:'#1a47d5'},";
$data.= "{name:'Composition', value:1, color:'#5d6bd5'},";
$data.= "{name:'Disease', value:1, color:'#f000b6'},";
$data.= "{name:'Peptide', value:1, color:'#fe5006'}";
$data .= "];</script>";
echo $data;
?>

<div style="margin-left:25%;width:50%;height:100%;display:inline-block;">
  <div id="piechart_container" style="width:100%;display:inline-block;position:relative;">
    <div id="piechart" style="padding-left:20px;padding-top:20px;display:inline-block;"></div>
    <div style="position:absolute;left:205px;top:240px;"><img src='https://glyconnect.expasy.org/api/assets/images/glyconnect_logo.png' style="max-width:130px;"/></div>
  </div>
</div>
<script>
  function create_piechart(viewerWidth, viewerHeight) {
    var width = viewerWidth;
    var height = viewerHeight;
    var radius = Math.min(viewerWidth, viewerHeight) / 2 ;
    var domain_link = "./browser/";

    var svg = d3.select("#piechart").append("svg")
    .attr("width", width)
    .attr("height", height)
    .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    var arc = d3.svg.arc()
    .outerRadius(radius)
    .innerRadius(80);

    var pie = d3.layout.pie()
    .sort(null)
    .value(function(d){ return d.value; });

    var g = svg.selectAll(".fan")
    .data(pie(dataset))
    .enter()
    .append("g")
    .attr("class", "fan")

    g.append("path")
      .attr("d", arc)
      .attr("fill", function(d){ return d.data.color; })
      .style("fill-opacity", 1)
      .style("stroke", "white")
      .on("mouseover", function(d) { d3.select(this).style("opacity", 0.8); })
      .on("mouseleave", function(d) { d3.select(this).style("opacity", 1); })
      .on("click", function(d) {
      var link = domain_link+d.data.name;
      window.open(link);
    });

    g.append("text")
      .attr("transform", function(d) { console.log(" "+arc.centroid(d)); return "translate(" + arc.centroid(d)[0]*1.1 + "," + arc.centroid(d)[1]*1.1 + ")"; })
      .style("text-anchor", "middle")
      .text(function(d) { return d.data.name; })
      .style("z-index", "-100")
      .style("fill", "black")
      .style("font-weight", "bold")
      .attr("font-size", "18")
      .style("text-shadow", "0px 0px 3px white");
  }
</script>
<script>
  function unsync_create_piechart() {
    return new Promise(resolve => {
      setTimeout(() => {
        create_piechart(500,500);
      }, 10);
    });
  };
  unsync_create_piechart();
</script>


<!--


<div style="width:100%;">
<div class='row' style="display: inline-flex">
<a href="/browser/taxonomy" title="Taxonomic information" class='toggle-info'><div class='home-button label-important'><span style="font-weight: bold;font-size: 30px" class='icon-tags icon-white'></span> Taxonomy (245)</div></a>
<a href="/browser/proteins" title="Glycoproteins" class='toggle-info'><div class='home-button label-warning'><span class='icon-map-marker icon-white'></span> Protein (1819)</div></a>
<a href="/browser/tissues" title="Source tissues" class='toggle-info'><div class='home-button label-success'><span class='icon-leaf icon-white'></span> Tissue (253)</div></a>
</div>
<div class="centered">
<div class='row' style="display: inline-flex">
<a href="/browser/structures" title="Glycan structures" class='toggle-info'><div class='home-button label-notice'><span class='icon-th icon-white'></span> Structure (3783) </div></a>
<a href="/browser/compositions" title="Glycan compositions" class='toggle-info'><div class='home-button label-compo'><span class='icon-magnet icon-white'></span> Composition (963) </div></a>
<a href="/browser/diseases" title="Diseases" class='toggle-info'><div class='home-button label-purple'><span class='icon-cog icon-white'></span> Disease (90) </div></a>
</div>
</div>
<div class="row-fluid">
<div class='row' style="display: inline-flex">
<a href="/browser/references" title="References" class='toggle-info'><div class='home-button label-book'><span class='icon-book icon-white'></span> Reference (894) </div></a>
<a  title="Glycosylation sites" class='toggle-info'><div class='home-button label-site'><span class='icon-arrow-down icon-white'></span> Site (3310) </div></a>
<a  title="Mass spectrometry validated peptides" class='toggle-info'><div class='home-button label-peptide'><span class='icon-align-center icon-white'></span> Peptide (2601) </div></a>
</div>
</div>
</div>

-->