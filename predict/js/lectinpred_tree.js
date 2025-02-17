function create_tree(viewerWidth, viewerHeight) {
  // Get JSON data
  var data = document.getElementById('data').innerHTML;
  var treeData = JSON.parse(data);
  // Calculate total nodes, max label length
  var totalNodes = 0;
  var maxLabelLength = 0;
  // variables for drag/drop
  var selectedNode = null;
  var draggingNode = null;
  // panning variables
  var panSpeed = 200;
  var panBoundary = 20; // Within 20px from edges will pan when dragging.
  // Misc. variables
  var i = 0;
  var duration = 750;
  var root;

  var tree = d3.layout.tree()
  .size([viewerHeight, viewerWidth]);

  // define a d3 diagonal projection for use by the node paths later on.
  var diagonal = d3.svg.diagonal()
  .projection(function(d) {
    return [d.y, d.x];
  });

  // A recursive helper function for performing some setup by walking through all nodes

  function visit(parent, visitFn, childrenFn) {
    if (!parent) return;

    visitFn(parent);

    var children = childrenFn(parent);
    if (children) {
      var count = children.length;
      for (var i = 0; i < count; i++) {
        visit(children[i], visitFn, childrenFn);
      }
    }
  }

  // Call visit function to establish maxLabelLength
  visit(treeData, function(d) {
    totalNodes++;
    maxLabelLength = Math.max(d.name.length, maxLabelLength);

  }, function(d) {
    return d.children && d.children.length > 0 ? d.children : null;
  });


  // sort the tree according to the node names

  function sortTree() {
    tree.sort(function(a, b) {
      return b.name.toLowerCase() < a.name.toLowerCase() ? 1 : -1;
    });
  }
  // Sort the tree initially incase the JSON isn't in a sorted order.
  sortTree();

  // Define the zoom function for the zoomable tree
  var zooming = false;
  function zoom() {
    if (zooming) {
      svgGroup.attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
    }
    //centerNode(root);
  }

  // define the zoomListener which calls the zoom function on the "zoom" event constrained within the scaleExtents
  var zoomListener = d3.behavior.zoom().scaleExtent([0.5, 2]).on("zoom", zoom);

  // define the baseSvg, attaching a class for styling and the zoomListener
  var baseSvg = d3.select("#tree-container").append("svg")
  .attr("width", viewerWidth)
  .attr("height", viewerHeight)
  .attr("class", "overlay")
  .attr("id", "tree-container-svg");

  d3.select("body").on("keydown", function () {
    zooming = d3.event.ctrlKey;
    baseSvg.call(zoomListener);
  });

  d3.select("body").on("keyup", function () {
    zooming = false;
    baseSvg.on('.zoom', null);
  });

  // Helper functions for collapsing and expanding nodes.

  function collapse(d) {
    if (d.children) {
      d._children = d.children;
      d._children.forEach(collapse);
      d.children = null;
    }
  }

  function expand(d) {
    if (d._children) {
      d.children = d._children;
      d.children.forEach(expand);
      d._children = null;
    }
  }

  var overCircle = function(d) {
    selectedNode = d;
  };
  var outCircle = function(d) {
    selectedNode = null;
  };

  // Function to center node when clicked/dropped so node doesn't get lost when collapsing/moving with large amount of children.

  function centerNode(source) {
    scale = zoomListener.scale();
    x = -source.y0;
    y = -source.x0;
    x = x * scale + viewerWidth / 2.6;
    y = y * scale + viewerHeight / 2;
    d3.select("#tree-container").select('g').transition()
      .duration(duration)
      .attr("transform", "translate(" + x + "," + y + ")scale(" + scale + ")");
    zoomListener.scale(scale);
    zoomListener.translate([x, y]);
  }

  // Toggle children function

  function toggleChildren(d) {
    if (d.children) {
      d._children = d.children;
      d.children = null;
    } else if (d._children) {
      d.children = d._children;
      d._children = null;
    }
    return d;
  }

  // Toggle children on click.
  function click(d) {
    if (d.protein_id) {
      $("#protein_id").val(d.protein_id);
      $( "#search" ).submit();
    }
    if (d3.event.defaultPrevented) return; // click suppressed
    d = toggleChildren(d);
    update(d);
    centerNode(d);
  }

  function update(source) {
    // Compute the new height, function counts total children of root node and sets tree height accordingly.
    // This prevents the layout looking squashed when new nodes are made visible or looking sparse when nodes are removed
    // This makes the layout more consistent.
    var levelWidth = [1];
    var childCount = function(level, n) {

      if (n.children && n.children.length > 0) {
        if (levelWidth.length <= level + 1) levelWidth.push(0);

        levelWidth[level + 1] += n.children.length;
        n.children.forEach(function(d) {
          childCount(level + 1, d);
        });
      }
    };
    childCount(0, root);
    var newHeight = d3.max(levelWidth) * 30; // 25 pixels per line  
    tree = tree.size([newHeight, viewerWidth]);

    // Compute the new tree layout.
    var nodes = tree.nodes(root).reverse(),
        links = tree.links(nodes);

    // Set widths between levels based on maxLabelLength.
    nodes.forEach(function(d) {
      d.y = (d.depth * (maxLabelLength * 8)); //maxLabelLength * 10px
      // alternatively to keep a fixed scale one can set a fixed depth per level
      // Normalize for fixed-depth by commenting out below line
      // d.y = (d.depth * 500); //500px per level.
    });

    // Update the nodes…
    node = svgGroup.selectAll("g.node")
      .data(nodes, function(d) {
      return d.id || (d.id = ++i);
    });

    // Enter any new nodes at the parent's previous position.
    var nodeEnter = node.enter().append("g")
    .attr("class", "node")
    .attr("transform", function(d) {
      return "translate(" + source.y0 + "," + source.x0 + ")";
    });

    nodeEnter.append("circle")
      .attr('class', 'nodeCircle')
      .attr("r", 8)
      .attr("fill", function(d) {
      return d._children ? "lightsteelblue" : "#fff";
    })
      .on('click', click)
      .on("mouseover", function (d) {d3.select(this).attr('r', '10');})
      .on("mouseout", function (d) {d3.select(this).attr('r', '8');});

    nodeEnter.append("text")
      .attr("x", 20)
      .attr("dy", ".2em")
      .attr('class', 'nodeText')
      .attr("text-anchor", function(d) {
      return d.children || d._children ? "start" : "start";
    })
      .text(function(d) {
      return d.name;
    })
      .style("fill-opacity", 0)
      .on("mouseover", function (d) {
      d3.select(this).classed("selected", true);
    })
      .on("mouseout", function (d) {
      d3.select(this).classed("selected", false);
    })
      .on("click", function (d) {
      $("#"+levels[d.cat]).val(d.name);
      $( "#search" ).submit();
    });

    //add icons for search
    //nodeEnter.append("svg:foreignObject")
    //  .attr("width", 20)
    //  .attr("height", 20)
    //  .attr("y", "-10px")
    //  .attr("x", "10px")
    //  .append("xhtml:span")
    //  .attr("class", "control glyphicon glyphicon-search")
    //  .attr("font-size", "20px")
    //  .on("click", function (d) {
    //  var similarity_score = "&similarity_score="+$("#similarity_score").val();
    //  var domain = "&domain="+$("#domain").val();
    //  var nb_domain = "&nb_domain="+$("#nb_domain").val();
    //  var interdomain = "&interdomain="+$("#interdomain").val();
    //  var keyword = "&keyword="+$("#keyword").val();
    //  var superkingdom = "&superkingdom="+$("#superkingdom").val();
    //  var kingdom = "&kingdom="+$("#kingdom").val();
    //  var phylum = "&phylum="+$("#phylum").val();
    //  var species = "&species="+$("#species").val();
    //  var args = similarity_score + domain + interdomain + keyword + superkingdom + kingdom + phylum + species;
    //  var link = tree_link+args+"&"+levels[d.cat]+"="+d.name;
    //  window.open(link);
    //});

    // phantom node to give us mouseover in a radius around it
    //nodeEnter.append("circle")
    //  .attr('class', 'ghostCircle')
    //  .attr("r", 10)
    //  .attr("opacity", 0.2) // change this to zero to hide the target area
    //  .style("fill", "red")
    //  .attr('pointer-events', 'mouseover')
    //  .on("mouseover", function(node) {
    //  overCircle(node);
    //})
    //  .on("mouseout", function(node) {
    //  outCircle(node);
    //})
    //.on('click', click);

    // Update the text to reflect whether node has children or not.
    //node.select('text')
    //	.attr("font-size", "18px")
    //  .attr("x", 30)
    //  .attr("text-anchor", function(d) {
    //  return d.children || d._children ? "start" : "start";
    //})
    //  .text(function(d) {
    //  return d.name;
    //});

    // Change the circle fill depending on whether it has children and is collapsed
    node.select("circle.nodeCircle")
      .style("fill", function(d) {
      return d._children ? "lightsteelblue" : "#fff";
    });

    // Transition nodes to their new position.
    var nodeUpdate = node.transition()
    .duration(duration)
    .attr("transform", function(d) {
      return "translate(" + d.y + "," + d.x + ")";
    });

    // Fade the text in
    nodeUpdate.select("text")
      .style("fill-opacity", 1);

    // Transition exiting nodes to the parent's new position.
    var nodeExit = node.exit().transition()
    .duration(duration)
    .attr("transform", function(d) {
      return "translate(" + source.y + "," + source.x + ")";
    })
    .remove();

    nodeExit.select("circle")
      .attr("r", 0);

    nodeExit.select("text")
      .style("fill-opacity", 0);

    // Update the links…
    var link = svgGroup.selectAll("path.link")
    .data(links, function(d) {
      return d.target.id;
    });

    // Enter any new links at the parent's previous position.
    link.enter().insert("path", "g")
      .attr("class", "link")
      .style("stroke-width", 3+'px')
      .style("stroke", "grey")
      .style("opacity", 0.5)
      .on("click", function(d) {click(d.target)})
      .attr("d", function(d) {
      var o = {
        x: source.x0,
        y: source.y0
      };
      return diagonal({
        source: o,
        target: o
      });
    });

    // Transition links to their new position.
    link.transition()
      .duration(duration)
      .attr("d", diagonal);

    // Transition exiting nodes to the parent's new position.
    link.exit().transition()
      .duration(duration)
      .attr("d", function(d) {
      var o = {
        x: source.x,
        y: source.y
      };
      return diagonal({
        source: o,
        target: o
      });
    })
      .remove();

    // Stash the old positions for transition.
    nodes.forEach(function(d) {
      d.x0 = d.x;
      d.y0 = d.y;
    });
  }

  // Append a group which holds all nodes and which the zoom Listener can act upon.
  var svgGroup = baseSvg.append("g");

  // Define the root
  root = treeData;
  root.x0 = viewerHeight / 2;
  root.y0 = 0;

  // Collapse all children of roots children before rendering.
  root.children.forEach(function(child){
    collapse(child);
  });

  // Layout the tree initially and center on the root node.
  update(root);
  centerNode(root);
}