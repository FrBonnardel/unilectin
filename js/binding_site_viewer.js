function size_dict(d){c=0; for (i in d) ++c; return c}

function msaViewer_bindingsites(sequences, div_name, defwidth, defheight){
  var amino_acid_counter=[];
  var amino_acids=[];
  sequences.forEach(function(sequence) {
    for (index = 0; index < sequence.length; ++index) {
      amino_acid_counter[index]={};
    }
  });
  sequences.forEach(function(sequence) {
    for (index = 0; index < sequence.length; ++index) {
      var amino_acid = sequence[index];
      if (amino_acid == '-'){
        continue;
      }
      amino_acids[amino_acid]=1;
      if (amino_acid in amino_acid_counter[index]) {
        amino_acid_counter[index][sequence[index]] +=1; 
      }else {
        amino_acid_counter[index][sequence[index]] = 1;
      }
    }
  });
  var nbseq = sequences.length;
  var dataset = [];
  for (index = 0; index < amino_acid_counter.length; ++index) {
    var counts = amino_acid_counter[index];
    var values={};
    values.label=index;
    for (var amino_acid in amino_acids) {
      var freq = 0 ;
      if (amino_acid in counts) {
        freq = 1 / size_dict(counts);
      }
      values[amino_acid]=freq;
    }
    dataset.push(values);
  }

  function wrap(text, width) {
    text.each(function() {
      var text = d3.select(this),
          words = text.text().split(/\s+/).reverse(),
          word,
          line = [],
          lineNumber = 0,
          lineHeight = 1.1, // ems
          y = text.attr("y"),
          dy = parseFloat(text.attr("dy")),
          tspan = text.text(null).append("tspan").attr("x", 0).attr("y", y).attr("dy", dy + "em");
      while (word = words.pop()) {
        line.push(word);
        tspan.text(line.join(" "));
        if (tspan.node().getComputedTextLength() > width) {
          line.pop();
          tspan.text(line.join(" "));
          line = [word];
          tspan = text.append("tspan").attr("x", 0).attr("y", y).attr("dy", ++lineNumber * lineHeight + dy + "em").text(word);
        }
      }
    });
  }


  var margin = {top: 10, right: 30, bottom: 15, left: 30};
  var width = defwidth - margin.left - margin.right,
      height = defheight - margin.top - margin.bottom;

  var x = d3.scale.ordinal()
  .rangeRoundBands([0, width], .1,.3);

  var y = d3.scale.linear()
  .rangeRound([height, 0]);

  var colorRange = d3.scale.category20();
  var color = d3.scale.ordinal()
  .range(colorRange.range());

  var aa_color = [];
  aa_color['R']="#1FAABD";
  aa_color['H']="#1FAABD";
  aa_color['K']="#1FAABD";
  aa_color['D']="#D75032";
  aa_color['E']="#D75032";
  aa_color['C']="#64AD59";
  aa_color['S']="#64AD59";
  aa_color['G']="#64AD59";
  aa_color['Y']="#64AD59";
  aa_color['T']="#64AD59";
  aa_color['P']="#4B3E4D";
  aa_color['F']="#4B3E4D";
  aa_color['V']="#4B3E4D";
  aa_color['L']="#4B3E4D";
  aa_color['I']="#4B3E4D";
  aa_color['A']="#4B3E4D";
  aa_color['M']="#E57E25";
  aa_color['W']="#E57E25";
  aa_color['N']="#92278F";
  aa_color['Q']="#92278F";
  aa_color['X']="#f1f2f1";
  aa_color['.']="#ccced1";

  var xAxis = d3.svg.axis()
  .scale(x)
  .tickFormat(function(e){return e+1;})
  .orient("bottom");

  var yAxis = d3.svg.axis()
  .scale(y)
  .orient("left");

  var svg = d3.select(div_name).append("svg")
  .attr("width", width + margin.left + margin.right)
  .attr("height", height + margin.top + margin.bottom)
  .append("g")
  .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
  color.domain(d3.keys(dataset[0]).filter(function(key) { return key !== "label"; }));

  dataset.forEach(function(d) {
    var y0 = 0;
    d.values = color.domain().map(function(name) { return {name: name, y0: y0, y1: y0 += +d[name]}; });
    d.total = d.values[d.values.length - 1].y1;
  });
  x.domain(dataset.map(function(d) { return d.label; }));
  y.domain([0, d3.max(dataset, function(d) { return d.total; })]);

  svg.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + height + ")")
    .call(xAxis)
    .style("fill", "1px grey")
    .style("font-size", "8px");

  svg.append("g")
    .attr("class", "y axis")
    .call(yAxis)
    .style("fill", "1px grey")
    .style("font-size", "8px")
    .append("text")
    .attr("transform", "rotate(-90)")
    .attr("y", 9)
    .attr("dy", ".71em")
    .style("text-anchor", "end");

  var bar = svg.selectAll(".label")
  .data(dataset)
  .enter().append("g")
  .attr("class", "g")
  .attr("transform", function(d) { return "translate(" + x(d.label) + ",0)"; });
  svg.selectAll(".x.axis .tick text")
    .call(wrap, x.rangeBand());

  var bar_enter = bar.selectAll("rect")
  .data(function(d) { return d.values; })
  .enter();

  bar_enter.append("rect")
    .attr("width", x.rangeBand())
    .attr("y", function(d) { return y(d.y1); })
    .attr("height", function(d) { return y(d.y0) - y(d.y1); })
    .style("fill", function(d) { return aa_color[d.name]; });

  bar_enter.append("text")
    .text(function(d) { if(d.y1-d.y0 > 0.1){return d.name;} })
    .attr("y", function(d) { return y(d.y1)+14; })
    .attr("x", 1)
    .style("fill", '#ffffff')
    .style("font-weight", "bold");

  svg.append("g")
    .attr("class", "legendLinear")
    .attr("transform", "translate(0,"+(height+30)+")");
}