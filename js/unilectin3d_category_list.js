/**
 * Implements a simple layout for rendering trees in a list style view as seen in file system browsers
 * @author Patrick Oladimeji
 * @date 5/24/14 12:21:50 PM
 */
/*jshint unused: true, undef: true*/
/*jslint vars: true, plusplus: true, devel: true, nomen: true, indent: 4, maxerr: 50 */
/*global d3*/
(function (d3) {
  d3.layout.treelist = function () {
    "use strict";
    var hierarchy = d3.layout.hierarchy().sort(null).value(null),
        nodeHeight = 40,
        childIndent = 30,
        size;

    var treelist = function (d, i) {
      var nodes = hierarchy.call(this, d, i),
          root = nodes[0];

      function visit(f, t, index, parent) {
        if (t) {
          f(t, index, parent);
        }
        var children = t.children;
        if (children && children.length) {
          children.forEach(function (child, ci) {
            visit(f, child, ci, t);
          });
        }
      }

      /**
             visit all nodes in the tree and set the x, y positions
            */
      function layout(node) {
        //all children of the same parent are rendered on the same  x level
        //y increases every time a child is added to the list 
        var x = 0, y = 0;
        visit(function (n, index, parent) {
          x = parent ? parent.x + childIndent : 0;
          y = y + nodeHeight;
          n.y = y;
          n.x = x;

        }, node);
        //update size after visiting
        size = [x, y];
      }

      layout(root);
      return nodes;
    };

    treelist.size = function () {
      return size;
    };

    treelist.nodeHeight = function (d) {
      if (arguments.length) {
        nodeHeight = d;
        return treelist;
      }
      return nodeHeight;
    };

    treelist.childIndent = function (d) {
      if (arguments.length) {
        childIndent = d;
        return treelist;
      }
      return childIndent;
    };

    treelist.nodes = treelist;

    return treelist;
  };

}(d3));

//CREATE LIST
function create_category_list(tree_link,levels){
  var data = JSON.parse(document.getElementById('oldtree_data').innerHTML);
  var id = 0;
  var tree = d3.layout.treelist()
  .childIndent(20)
  .nodeHeight(20);
  var ul = d3.select("#cat-browser-container").append("ul").classed("treelist", "true");

  function toggleChildren(d) {
    if (d.children) {
      d._children = d.children;
      d.children = null;
    } else if (d._children) {
      d.children = d._children;
      d._children = null;
    }
  }
  function render(data, parent) {
    var nodes = tree.nodes(data),
        duration = 250;

    var nodeEls = ul.selectAll("li.node").data(nodes, function (d) {
      d.id = d.id || ++id;
      return d.id;
    });
    //entered nodes
    var entered = nodeEls.enter().append("li").classed("node", true)
    .style("top", parent.y +"px")
    .style("opacity", 0)
    .style("height", tree.nodeHeight() + "px")
    .on("mouseover", function (d) {
      d3.select(this).classed("selected", true);
    })
    .on("mouseout", function (d) {
      d3.selectAll(".selected").classed("selected", false);
    });
    //add arrows if it is a folder
    entered.append("span").attr("class", function (d) {
      var icon = d.children ? " glyphicon-chevron-down"
      : d._children ? "glyphicon-chevron-right" : "";
      return "caret glyphicon " + icon;
    });
    //add icons for folder for file
    entered.append("span").attr("class", "unset");
    //add text
    entered.append("span").attr("class", "filename")
      .html(function (d) { return d.name.split(".").pop(); })
      .on("click", function (d) {
      toggleChildren(d);
      render(data, d);
    });
    //add icons for search
    entered.append("span")
      .attr("class", "glyphicon glyphicon-search")
      .on("click", function (d) {
        var parts = d.name.split(".");
        var get_arg = "";
        for (var j = 0; j < parts.length; j++) {
          get_arg += levels[j]+"="+parts[j].replace(/:/g, ".")+"&";
        }
        var link = tree_link+get_arg;
        window.open(link);
    });
    //update caret direction
    nodeEls.select("span.caret").attr("class", function (d) {
      var icon = d.children ? " glyphicon-chevron-down" : d._children ? "glyphicon-chevron-right" : "";
      return "caret glyphicon " + icon;
    });
    //Update div size
    $("#cat-browser-container").height(tree.size()[1]);
    //update position with transition
    nodeEls.transition().duration(duration)
      .style("top", function (d) { return (d.y - tree.nodeHeight()) + "px";})
      .style("left", function (d) { return d.x + "px"; })
      .style("opacity", 1);
    nodeEls.exit().remove();
  }
  render(data, data);
  $('.filename').click();
  $('.filename')[0].click();
}