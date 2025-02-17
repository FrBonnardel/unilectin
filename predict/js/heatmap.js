var Heatmap = {
    data : 0,
    x_elements_names : 0,
    tax_values : 0,
    hclust : true,
    square : true,
    load_heatmap: function () {
        function count_common_value(values1, values2, values_max) {
            var total_common = 0;
            var interval = values_max / 10;
            for (var i = 0; i < values1.length; i++) {
                var value1 = values1[i];
                var value2 = values2[i];
                if (value1 == value2) {
                    total_common += 2;
                }else if(0 < value1 && 0 < value2 &&  ( value1 > (value2 - interval) ) && (value1 < (value2 + interval) ) ){
                    total_common += 1;
                }
            }
            return total_common;
        }

        function find_closer(tax_values_lc, refvalues, values_max) {
            var phylo_total_common = new Array();
            for (var phylo in tax_values_lc) {
                var values = tax_values_lc[phylo];
                phylo_total_common[phylo] = count_common_value(refvalues, values, values_max);
            }
            var maxkey = '';
            var maxval = -1;
            for (var phylo in phylo_total_common) {
                var nval = phylo_total_common[phylo];
                if (nval > maxval) {
                    maxkey = phylo;
                    maxval = nval;
                }
            }
            var closer_phylo = maxkey;
            return closer_phylo;
        }

        function hclust(tax_values, values_max) {
            var tax_values_lc = {};
            for (var phylo in tax_values) {
                tax_values_lc[phylo] = tax_values[phylo];
            }
            var closer_phylo = Object.keys(tax_values_lc)[0];
            var order = new Array();
            var refvalues = tax_values_lc[closer_phylo];
            delete tax_values_lc[closer_phylo];
            order[closer_phylo] = 1;
            var clust_index = 2;
            while (Object.keys(tax_values_lc).length > 1) {
                closer_phylo = find_closer(tax_values_lc, refvalues, values_max);
                refvalues = tax_values_lc[closer_phylo];
                delete tax_values_lc[closer_phylo];
                order[closer_phylo] = clust_index;
                clust_index += 1;
            }
            closer_phylo = Object.keys(tax_values_lc)[0];
            order[closer_phylo] = clust_index;
            clust_index += 1;
            return order;
        }

        //var tax_values = new Array();
        //tax_values['a'] = new Array(1, 1, 1, 1);
        //tax_values['b'] = new Array(1, 2, 3, 6);
        //tax_values['c'] = new Array(1, 1, 1, 1);
        //tax_values['d'] = new Array(1, 2, 3, 7);
        //var order = hclust(tax_values);
        //console.log(order);

        //var x_elements = d3.set(data.map(function( item ) { return item.lec; } )).values();
        var x_elements = d3.set(this.data.map(function( item ) { return item.lec; } )).values();
        var y_elements = this.x_elements_names;
        if (this.square) {
            var x_elements = this.x_elements_names;
        }
        var values_set = d3.set(this.data.map(function (item) {
            return item.value;
        })).values();
        var values_max = Math.max.apply(Math, values_set);
        if (this.hclust) {
            var y_elements_hclust = hclust(this.tax_values, values_max);
            y_elements_hclust = Object.keys(y_elements_hclust);
            y_elements = y_elements_hclust;
            if (this.square) {
                x_elements = y_elements_hclust;
            }
        }
        //var subgroups = d3.map(data, function(d){return(d.taxgroup)}).keys();

        var itemSize = Math.min(1000 / x_elements.length, 20);
        var cellSize = itemSize - 1;
        var margin = {top: 300, right: 10, bottom: 10, left: 300};

        var width = x_elements.length * 2 * itemSize + 200, height = y_elements.length * itemSize + 200;

        var xScale = d3.scale.ordinal()
            .domain(x_elements)
            .rangeBands([0, x_elements.length * itemSize]);

        var xAxis = d3.svg.axis()
            .scale(xScale)
            .orient("top");

        var yScale = d3.scale.ordinal()
            .domain(y_elements)
            .rangeBands([0, y_elements.length * (itemSize)]);

        var yAxis = d3.svg.axis()
            .scale(yScale)
            .orient("left");

        var colorScale = d3.scale.threshold()
            .domain([values_max / 14, values_max / 12, values_max / 10, values_max / 8, values_max / 6, values_max / 4, values_max / 2])
            .range(["#99FF99", "#66FF66", "#FFCC99", "#FFB266", "#FF6666", "#FF3333", "#FF0000", "#CC0000"]);

        var svg = d3.select('.heatmap')
            .append("svg")
            .attr("width", width)
            .attr("height", height)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        //svg.selectAll("text").attr("transform", "rotate(90)");

        var cells = svg.selectAll('rect')
            .data(this.data)
            .enter().append('g').append('rect')
            .attr('class', 'cell')
            .attr('width', cellSize)
            .attr('height', cellSize)
            .attr('y', function (d) {
                return yScale(d.tax);
            })
            .attr('x', function (d) {
                return xScale(d.lec);
            })
            .attr('fill', function (d) {
                return colorScale(d.value);
            })
            .on("mouseover", function (d) {
                var div = d3.select("#tooltip");
                var mouseVal = d3.mouse(this);
                div.html('C=' + d.lec + ' L=' + d.tax + ' : ' + d.value)
                    .style("left", (mouseVal[0] + 10) + "px")
                    .style("top", (mouseVal[1] + 10) + "px")
                    .style("opacity", 1)
                    .style("display", "block");
            })
            .on("mouseout", function () {
                var div = d3.select("#tooltip");
                div.html(" ").style("display", "none")
            });

        svg.append("g")
            .attr("class", "y axis")
            .call(yAxis)
            .selectAll('text')
            .attr('font-weight', 'normal')
            .style("font-size", (cellSize/1.5)+"px");

        svg.append("g")
            .attr("class", "x axis")
            .call(xAxis)
            .selectAll('text')
            .attr('font-weight', 'normal')
            .style("font-size", (cellSize/1.5)+"px")
            .style("text-anchor", "start")
            .attr("dx", ".8em")
            .attr("dy", ".5em")
            .attr("transform", function (d) {
                return "rotate(-45)";
            });
    }
}
