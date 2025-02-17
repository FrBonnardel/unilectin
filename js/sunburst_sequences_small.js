function create_sunburst(div_container,div_tooltip,div_legend,data,colors,levels,total_objects_sunburst,onclick_link,legend_width){
	// Use d3.text and d3.csv.parseRows so that we do not need to have a header
	// row, and can receive the csv as an array of arrays.
	var familySunburst = new Sunburst(div_container,div_tooltip,div_legend,data,colors,levels,total_objects_sunburst,onclick_link,legend_width);
	familySunburst.buildHierarchy();
	familySunburst.createVisualization();
}

function Sunburst(div_container,div_tooltip,div_legend,data,colors,levels,total_objects_sunburst,onclick_link,legend_width){
	this.data=data;
	this.colors=colors;
	this.levels=levels;
	this.total_objects_sunburst=total_objects_sunburst;
	this.onclick_link=onclick_link;

	// Dimensions of sunburst.
	this.json = {"name": "root", "children": []};
	this.width = 200;
	this.height = 200;
	this.radius = Math.min(this.width, this.height) / 2;
	//var color = d3.scale.category20c();

	var totalSize = 0;

	this.vis = d3.select(div_container).append("svg:svg")
		.attr("width", this.width)
		.attr("height", this.height)
		.append("svg:g")
		.attr("transform", "translate(" + this.width / 2 + "," + this.height / 2 + ")");

	this.partition = d3.layout.partition()
		.size([2 * Math.PI, this.radius * this.radius])
		.value(function(d) { return d.size; });

	this.arc = d3.svg.arc()
		.startAngle(function(d) { return d.x; })
		.endAngle(function(d) { return d.x + d.dx; })
		.innerRadius(function(d) { return Math.sqrt(d.y); })
		.outerRadius(function(d) { return Math.sqrt(d.y + d.dy); });

	function mouseover_sunburst(d) {
		// Given a node in a partition layout, return an array of all of its ancestor
		// nodes, highest first, but excluding the root.
		var getAncestors = function (node) {
			var path = [];
			var current = node;
			while (current.parent) {
				path.unshift(current);
				current = current.parent;
			}
			return path;
		};
		var percentage = (100 * d.value / totalSize).toPrecision(3);
		var percentageString = percentage + "%";
		if (percentage < 0.1) {
			percentageString = "< 0.1%";
		}
		var sequenceArray = getAncestors(d);

		// Fade all the segments.
		d3.select(div_container).selectAll("path")
			.style("opacity", 0.3);

		// Then highlight only those that are an ancestor of the current segment.
		d3.select(div_container).selectAll("path")
			.filter(function(node) {
			return (sequenceArray.indexOf(node) >= 0);
		})
			.style("opacity", 1);
	};

	// Restore everything to full opacity when moving off the visualization.
	function mouseleave_sunburst(d) {

		// Deactivate all segments during transition.
		//d3.select(div_container).selectAll("path").on("mouseover", null);

		// Transition each segment to full opacity and then reactivate it.
		d3.select(div_container).selectAll("path")
			.transition()
			.duration(100)
			.style("opacity", 1)
			.each("end", function() {
			//d3.select(this).on("mouseover", mouseover_sunburst(this.totalSize));
			var div = d3.select(div_tooltip);
			div.style("display","none");
		});
	};

	function mousemove_sunburst(d){
		var div = d3.select(div_tooltip);
		var percentage = (100 * d.value / totalSize);
		var percentageString = Math.round(0.01 * percentage * total_objects_sunburst);
		var mouseVal = d3.mouse(this);
		div.style("display","none");
		div
			.html(d.name.split(".").pop()+"</br>"+percentageString)
			.style("left", (mouseVal[0]+20) + "px")
			.style("top", (mouseVal[1]+40) + "px")
			.style("opacity", 1)
			.style("display","block");
		//var selectthegraphs = $('.arc').not(this);
		//d3.selectAll(selectthegraphs).style("opacity",.5);
		//d3.select(this).style("stroke", "black");
	};

	// Take a 2-column CSV and transform it into a hierarchical structure suitable
	// for a partition layout. The first column is a sequence of step names, from
	// root to leaf, separated by hyphens. The second column is a count of how 
	// often that sequence occurred.
	this.buildHierarchy = function () {
		var root = this.json;
		for (var i = 0; i < this.data.length; i++) {
			var sequence = this.data[i][0];
			var size = +this.data[i][1];
			if (isNaN(size)) { // e.g. if this is a header row
				continue;
			}
			var parts = sequence.split(".");
			var nodename_tmp="";
			var currentNode = root;
			for (var j = 0; j < parts.length; j++) {
				var children = currentNode["children"];
				var nodeName = nodename_tmp+parts[j];
				nodename_tmp += parts[j]+".";
				var cat_level=j;
				var childNode;
				if (j + 1 < parts.length) {
					// Not yet at the end of the sequence; move down the tree.
					var foundChild = false;
					for (var k = 0; k < children.length; k++) {
						if (children[k]["name"] == nodeName) {
							childNode = children[k];
							foundChild = true;
							break;
						}
					}
					// If we don't already have a child node for this branch, create it.
					if (!foundChild) {
						childNode = {"name": nodeName, "cat": cat_level, "children": []};
						children.push(childNode);
					}
					currentNode = childNode;
				} else {
					// Reached the end of the sequence; create a leaf node.
					childNode = {"name": nodeName, "size": size, "cat": cat_level};
					children.push(childNode);
				}
			}
		}
	};

	// Circle action on click.
	var click = function (d) {
    if(!d.name){d.name=d.key;}
		var parts = d.name.split(".");
		var get_arg = "";
		for (var j = 0; j < parts.length; j++) {
			get_arg += levels[j]+"="+parts[j].replace(/:/g, ".")+"&";
		}
		var link_pdb = onclick_link+get_arg;
		window.open(link_pdb);
	};

	this.drawLegend = function () {

		// Dimensions of legend item: width, height, spacing, radius of rounded rect.
		var li = {
			w: legend_width, h: 30, s: 3, r: 3
		};

		var legend = d3.select(div_legend).append("svg:svg")
		.attr("width", li.w)
		.attr("height", d3.keys(colors).length * (li.h + li.s));

		var g = legend.selectAll("g")
		.data(d3.entries(colors))
		.enter().append("svg:g")
		.attr("transform", function(d, i) {
			return "translate(0," + i * (li.h + li.s) + ")";
		});

		g.append("svg:rect")
			.attr("rx", li.r)
			.attr("ry", li.r)
			.attr("width", li.w)
			.attr("height", li.h)
			.attr("class", "legend_button")
		  .on("click", click)
			.style("fill", function(d) { return d.value; });

		g.append("svg:text")
			.attr("x", li.w / 2)
			.attr("y", li.h / 2)
			.attr("dy", "0.20em")
			.attr("text-anchor", "middle")
			.attr("class", "legend_text")
		  .on("click", click)
			.text(function(d) { return d.key; });
	};
	// Main function to draw and set up the visualization, once we have the data.
	this.createVisualization = function() {
		var json = this.json;
		// Basic setup of page elements.
		this.drawLegend();

		// Bounding circle underneath the sunburst, to make it easier to detect
		// when the mouse leaves the parent g.
		this.vis.append("svg:circle")
			.attr("r", this.radius)
			.style("opacity", 0);

		// For efficiency, filter nodes to keep only those large enough to see.
		var nodes = this.partition.nodes(json)
		.filter(function(d) {
			return (d.dx > 0.005); // 0.005 radians = 0.29 degrees
		});

		var path = this.vis.data([json]).selectAll("path")
		.data(nodes)
		.enter().append("svg:path")
		.attr("display", function(d) { return d.depth ? null : "none"; })
		.attr("d", this.arc)
		//.style("fill", function(d) { return color((d.children ? d : d.parent).name); })
		.style("fill", function(d) { return colors[d.name.split(".")[0]]; })
		.style("fill-rule", "evenodd")
		.style("opacity", 1)
		.on("mouseover", mouseover_sunburst)
		.on("click", click)
		.on("mouseout",function(){ 
			var div = d3.select("#tooltip_sunburst_families");
			div.html(" ").style("display","none")
		})
		.on("mousemove",mousemove_sunburst);
		// Add the mouseleave handler to the bounding circle.
		d3.select(div_container).on("mouseleave", mouseleave_sunburst);

		// Get total size of the tree = value of root node from partition.
		totalSize = path.node().__data__.value;
	};
}