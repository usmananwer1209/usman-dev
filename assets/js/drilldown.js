/**
 * Created by Eric on 2/23/2015.
 */


var oneMillion = 1000000;

var dd_url		= 'card/get_dimension_drilldown';

var theMaxWeight = 40;
var dd_temp_width = -1;

var root = {
    name : 'root',
    parent : null,
    value : 0,
    radius : theMaxWeight,
    stroke : '#194C3E',
    children : []
};

// ************** Generate the tree diagram	 *****************
var dd_margin = {top: 20, right: 120, bottom: 20, left: 175};

var i = 0, svg, dd_width = 0, dd_height, tree, duration = 1500;

var diagonal = d3.svg.diagonal()
    .projection(function(d) { return [d.y, d.x]; });

var red = '#ff0000';
/* these are pastels
var colors = [
    '#194C3E', '#9191FF', '#5E7A95', '#FF68DD', '#06DCFB',
    '#9A03FE', '#5E9586', '#2DC800', '#FFB60B', '#DBDB97',
    '#B300B3', '#CCC144', '#67C7E2', '#CFE7E2', '#DEB19E',
    '#AD8BFE', '#FFCC33', '#9999FF', '#E3FBE9', '#E9F1EA',
    '#FFA04A', '#DDB9B9', '#DBDBFF', '#75ECFD', '#CAFFD8',
    '#F4D2F4', '#5B5BFF', '#FF2626', '#5EAE9E', '#1F88A7'
];
*/

var colors = [
    '#6666CC', '#009933', '#003399' , '#FF9933', '#	FFFF66'
];


function draw_drilldown(the_entityIds, the_termId, the_year, the_fiscal_type) {

    $('#dim_svg', window.parent.document).remove();

    set_window_height(.70);

    dd_temp_width = -1;

    $('#drilldown_display_Modal', window.parent.document).modal('show');

    $('#drilldown_display_Modal', window.parent.document).ready(function() {

        $('#dd_display', window.parent.document).append('<img id="dd_spinner" src="'+site_url+'/assets/img/AjaxLoader.gif" />');

        $.ajax({
            url: site_url + dd_url,
            data: {entityId: the_entityIds, termId: the_termId, year: the_year, fiscal_type: the_fiscal_type},
            type: "POST",
            dataType: 'json',
            error: function(jqXHR, textStatus, errorThrown) {

                $('#dd_spinner', window.parent.document).remove();

                console.log(textStatus, errorThrown);
            },
            success: function (data) {

                if (data != null) {

                    $('#dd_spinner', window.parent.document).remove();
                    $('#dd_title', window.parent.document).text(data['parent'].name + " Dimensional Drilldown");

                    initialize_drilldown( data['numDimensions']);

                    // create the treeData structure
                    appendData(data['parent']);

                    // and draw it
                    dd_draw();

                    // and reposition the window
                }
            }
        }); // end of ajax
    }); // end of ready
}

function set_window_height(factor) {

    var height = $(window.parent).height() * factor;

    $('#dd_display', window.parent.document).height(height + 'px');
}

function initialize_drilldown(numDimensions) {

    if (numDimensions > 20) {

        var overDims = numDimensions - 20;

        var factor = .70 + (0.0008 * overDims);
        set_window_height(factor);

        //$('#dd_display').center();
    }

    setWidth();

    dd_height = $("#dd_display", window.parent.document).height()- dd_margin.top - dd_margin.bottom;

    root.x0 = dd_height / 2;
    root.y0 = 0;

    tree = d3.layout.tree().size([dd_height, dd_width]);
}

function setWidth() {

    if ($("#dd_display", window.parent.document).is(":visible")){
        var temp = $("#dd_display", window.parent.document).width()- dd_margin.right - dd_margin.left;

        if (temp > 0 && (temp == dd_temp_width || (temp >= dd_temp_width-20 && temp <= dd_temp_width+20))) {
            // we're done
            dd_width = temp;
            //console.log('Done: ' + dd_width);
        }
        else {
            if (temp > 0) {
                dd_temp_width = temp;
                //console.log('Do it again: ' + dd_width);
            }

            // check again in 50
            setTimeout("setWidth();", 50);
        }
    }
    else {

        // way to soon
        setTimeout("setWidth();", 50);
        //console.log('way too soon');
    }
}

function appendData(rawData) {

    root.name = rawData.name;

    // remove existing children
    root.children = [];
    //root.value = 0;

    root.children.push.apply(root.children, rawData.children);

    root.value = (rawData.value / oneMillion);

    // now walk the data and add the correct weight
    var parentNum = 1;

    $.each(root.children, function(k,v) {

        v.value /= oneMillion;

        setTreeParams(v, colors[parentNum], root.value, theMaxWeight);

        v.stroke = colors[parentNum]; //colors[0];
        parentNum += 1;
    });
}

function setTreeParams(parent, strokeColor, maxAmt, maxWeight) {

    // figure out the parent info
    calcTreeRadius(parent, maxAmt, maxWeight);

    $.each(parent.children, function(k,v) {

        v.orgValue = v.value / oneMillion;

        if (v.value >= 0) {
            v.value /= oneMillion;
            v.stroke = strokeColor;
        }
        else {
            v.value = Math.abs(v.value) / oneMillion;
            v.stroke = red;
        }

        calcTreeRadius(v, parent.value, parent.radius);

        if (v.children) {
            setTreeParams(v, strokeColor, v.value, v.radius);
        }

    });
}

function calcTreeRadius(node, maxAmt, maxWeight) {

    var resetValue = false;
    if (node.value == 0) {
        node.value=maxAmt * 0.02;
        resetValue = true;
    }

    node.radius = ((node.value / maxAmt) * maxWeight);

    if (node.radius < 0.2) {
        node.radius = 0.2;
    }

    if (resetValue) {
        node.value = 0;
    }
}

function dd_draw() {
//      root = treeData[0];

    if (dd_width == 0) {
        // gotta wait
        setTimeout("dd_draw();", 50);
    }
    else {
        var jqTreeDiv = $("#dd_display", window.parent.document);
        var treeDiv = d3.selectAll(jqTreeDiv.toArray());

        svg = treeDiv.append("svg")
            .attr("id", "dim_svg")
            .attr("name", "dim_svg")
            .attr("width", dd_width + dd_margin.right + dd_margin.left)
            .attr("height", dd_height + dd_margin.top + dd_margin.bottom)
            .append("g")
            .attr("transform", "translate(" + dd_margin.left + "," + dd_margin.top + ")");

        dd_update(root);
    }
    // don't display the amount
    //root.value = 0;
}

function dd_update(source) {

    // Compute the new tree layout.
    var nodes = tree.nodes(root).reverse(),
        links = tree.links(nodes);

    // Normalize for fixed-depth.
    nodes.forEach(function(d)
    {
        d.y = d.depth * 180;
    });

    // Update the nodes…
    var node = svg.selectAll("g.node")
        .data(nodes, function(d) { return d.id || (d.id = ++i); });

    // Enter any new nodes at the parent's previous position.
    var nodeEnter = node.enter().append("g")
        .attr("class", "node")
        .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
        .on("click", dd_click);

    nodeEnter.append("circle")
//	  .attr("r", 1e-6)
        .attr("r", function (d) { return d.radius; })
        .style("stroke", function(d) { return d.stroke })
        .style("fill", function(d) { return d._children ? d.stroke : "#fff"; });

    nodeEnter.append("text")
        .attr("x", function(d)
        {
            //return d.children || d._children ? - (d.radius + 5) : d.radius + 5;
            return d.children || d._children ? +5 : d.radius + 5;
        })
        //.attr("dy", function(d) { return d.children || d._children ? "-1em" : ".35em"; })
        .attr("dy", function(d)
        {
            if (!d.children && !d._children) return ".35em";

            var num = 1.4;

            if (d.radius > 4.0) {
                if (d.radius < 9.0) {
                    num = d.radius * 0.275;
                }
                else if (d.radius < 12.0) {
                    num = d.radius * 0.2;
                }
                else if (d.radius < 20.0) {
                    num = d.radius * 0.175;
                }
                else if (d.radius < 30.0) {
                    num = d.radius * 0.15;
                }
                else {
                    num = d.radius * 0.125;
                }
            }

            return num + "em";
        })
        .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
        .text(function(d)
        {
            var tempName = "";
            if (!d.children) {
                var string = numeral(d.orgValue).format('0,0');

                tempName = d.name + " $ " + string + " M";
            }
            else {
                tempName = d.name;
            }

            // debug displaying radius
            //tempName += (" (" + numeral(d.radius).format('0,0') + ")");

            return tempName;
        })
        .style("fill-opacity", 1e-6);

    // Transition nodes to their new position.
    var nodeUpdate = node.transition()
        .duration(duration)
        .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

    nodeUpdate.select("circle")
        //.attr("r", 10)
        .attr("r", function (d) { return d.radius; })
        .style("stroke", function(d) { return d.stroke })
        .style("fill", function(d) { return d._children ? d.stroke : "#fff"; });

    nodeUpdate.select("text")
        .style("fill-opacity", 1);

    // Transition exiting nodes to the parent's new position.
    var nodeExit = node.exit().transition()
        .duration(duration)
        .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
        .remove();

    nodeExit.select("circle")
        .style("stroke", function(d) { return d.stroke })
        .attr("r", function (d) { return d.radius; });


    nodeExit.select("text")
        .style("fill-opacity", 1e-6);

    // Update the links…
    var link = svg.selectAll("path.link")
        .data(links, function(d) { return d.target.id; });

    // Enter any new links at the parent's previous position.
    link.enter().insert("path", "g")
        .attr("class", "link")
        .style("stroke-opacity", 0.85)
        .style("stroke", function(d) { return d.target.stroke; })
        .style("stroke-width", function(d) { return d.target.radius; })
        .attr("d", function(d) {
            var o = {x: source.x0, y: source.y0};
            return diagonal({source: o, target: o});
        });

    // Transition links to their new position.
    link.transition()
        .duration(duration)
        .attr("d", diagonal);

    // Transition exiting nodes to the parent's new position.
    link.exit().transition()
        .duration(duration)
        .attr("d", function(d) {
            var o = {x: source.x, y: source.y};
            return diagonal({source: o, target: o});
        })
        .remove();

    // Stash the old positions for transition.
    nodes.forEach(function(d) {
        d.x0 = d.x;
        d.y0 = d.y;
    });
}

// Toggle children on click.
function dd_click(d) {
    if (d.children) {
        d._children = d.children;
        d.children = null;
    } else {
        d.children = d._children;
        d._children = null;
    }
    dd_update(d);
}
