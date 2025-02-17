function create_barchart(viewerWidth, viewerHeight) {
  var margin = {top: 20, right: 20, bottom: 30, left: 50},
      width = viewerWidth - margin.left - margin.right,
      height = viewerHeight - margin.top - margin.bottom;

  var nbdomain_link="./search?nb_domain=";

  // Parse the xvalue / time
  var	parseDate = d3.time.format("%Y-%m").parse;

  var x = d3.scale.ordinal().rangeRoundBands([0, width], .05);

  var y = d3.scale.linear().range([height, 0]);

  var xAxis = d3.svg.axis()
  .scale(x)
  .orient("bottom")

  var yAxis = d3.svg.axis()
  .scale(y)
  .orient("left")
  .ticks(10);

  var svg = d3.select("#domain_graph").append("svg")
  .attr("width", width + margin.left + margin.right)
  .attr("height", height + margin.top + margin.bottom)
  .append("g")
  .attr("transform", 
        "translate(" + margin.left + "," + margin.top + ")");
  x.domain(data.map(function(d) { return d.xvalue; }));
  y.domain([0, d3.max(data, function(d) { return d.yvalue; })]);

  svg.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + height + ")")
    .call(xAxis)
    .selectAll("text")
    .style("text-anchor", "end")
    .attr("dx", "-.8em")
    .attr("dy", "-.55em")
    .attr("transform", "rotate(-90)" );

  svg.append("g")
    .attr("class", "y axis")
    .call(yAxis)
    .append("text")
    .attr("transform", "rotate(-90)")
    .attr("y", 6)
    .attr("dy", "1em")
    .style("text-anchor", "end")
    .text("Proteins");

  svg.selectAll("bar")
    .data(data)
    .enter().append("rect")
    .style("fill", "steelblue")
    .attr("x", function(d) { return x(d.xvalue); })
    .attr("width", x.rangeBand())
    .attr("y", function(d) { return y(d.yvalue); })
    .attr("height", function(d) { return height - y(d.yvalue); })
    .on("mouseover", function(d) { d3.select(this).style("opacity", 1); })
    .on("mouseleave", function(d) { d3.select(this).style("opacity", 0.8); })
    .on("click", function(d) {
    $("#nb_domain").val(d.xvalue);
    $("#search").submit();
  });

  svg.selectAll("text.bar")
    .data(data)
    .enter().append("text")
    .attr("class", "bar")
    .attr("text-anchor", "middle")
    .attr("x", function(d) { return x(d.xvalue) + x.rangeBand()/2; })
    .attr("y", function(d) { return y(d.yvalue) - 5; })
    .text(function(d) { return d.yvalue; });
}