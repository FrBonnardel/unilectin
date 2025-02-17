function create_piechart(viewerWidth, viewerHeight) {
	var width = viewerWidth;
	var height = viewerHeight;
	var radius = Math.min(viewerWidth, viewerHeight) / 2 ;
	var domain_link = "./search?domain=";

	var svg = d3.select("#piechart").append("svg")
	.attr("width", width)
	.attr("height", height)
	.append("g")
	.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

	var arc = d3.svg.arc()
	.outerRadius(radius)
	.innerRadius(30);

	var pie = d3.layout.pie()
	.sort(null)
	.value(function(d){ return d.value; });

	var g = svg.selectAll(".fan")
	.data(pie(dataset))
	.enter()
	.append("g")
	.attr("class", "fan");

	g.append("path")
		.attr("d", arc)
		.attr("fill", function(d){ return d.data.color; })
		.style("fill-opacity", 1)
		.style("stroke", "white")
		.on("mouseover", function(d) { d3.select(this).style("opacity", 0.8); })
		.on("mouseleave", function(d) { d3.select(this).style("opacity", 1); })
		.on("click", function(d) {
			$("#domain").val(d.data.legend);
            $("#search").submit();
		});

	g.append("text")
		.attr("transform", function(d) { console.log(" "+arc.centroid(d)); return "translate(" + arc.centroid(d)[0]*1.2 + "," + arc.centroid(d)[1]*1.2 + ")"; })
		.style("text-anchor", "middle")
		.text(function(d) { if(d.data.value > 0){return d.data.value;} })
		.style("z-index", "-100")
		.style("fill", "black")
		.style("font-weight", "bold")
		.attr("font-size", "20")
		.style("text-shadow", "0px 0px 3px white");

	function drawLegend() {
		// Dimensions of legend item: width, height, spacing, radius of rounded rect.
		var li = {
			w: 140, h: 30, s: 3, r: 3
		};

		var legend = d3.select("#piechart_legend").append("svg:svg")
		.attr("width", li.w)
		.attr("height", d3.keys(dataset).length * (li.h + li.s));

		var g = legend.selectAll("g")
		.data(d3.entries(dataset))
		.enter().append("svg:g")
		.attr("transform", function(d, i) {
			return "translate(0," + i * (li.h + li.s) + ")";
		});

		g.append("svg:rect")
			.attr("rx", li.r)
			.attr("ry", li.r)
			.attr("width", li.w)
			.attr("height", li.h)
			.style("fill", function(d) { return d.value.color; })
			.style("fill-opacity", 1);

		g.append("svg:text")
			.attr("x", li.w / 2)
			.attr("y", li.h / 2)
			.attr("dy", "0.35em")
			.attr("text-anchor", "middle")
			.text(function(d) { return d.value.legend; })
			.style("fill", "black")
			.style("font-weight", "bold")
		  .attr("font-size", "10")
			.style("text-shadow", "0px 0px 3px white");
	}
	drawLegend();
}