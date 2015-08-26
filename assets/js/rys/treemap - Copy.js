
// 2015-Mar-24 this version of treemap requires v2, drilldown requires v3
// so explicitly get v2 for the treemap implementation

var zoom_in = true;

$(document).ready(function() {
        var chart_selector2 = "#treemap";
		var sector_name = $('#sectors_name').val();
        var url_json2 = site_url + "card/company_tree/"+sector_name;
        var chartWidth = $('html').width();
        chartWidth = chartWidth*0.8*0.60;

        if($( window ).width() < 900)
        chartWidth = $('html').width()*0.74;

        //chartWidth = chartWidth.substring(0, chartWidth.lastIndexOf('px'));
        //var chartWidth = $('#treemap').css('width');
        //alert(chartWidth);
        var chartHeight = $('html').height() *0.7;

        var treemap2 = d3.layout.treemap()
          .round(false)
          .size([chartWidth, chartHeight])
          .sticky(true)
          .value(function(d) {
              return d.size;
            });

        buildTreemap(chart_selector2,url_json2,treemap2,false,chartWidth,chartHeight);
        var treemapResize;

    $(window).resize(function(e) {
            clearTimeout(treemapResize);
            treemapResize = setTimeout(function(){
              $('#treemap svg').remove();
			  var sector_name = $('#sectors_name').val();
              var chart_selector2 = "#treemap";
              var zoom_in = true;
              var url_json2 = site_url + "card/company_tree/"+sector_name;
              var chartWidth = $('#treemap').width();
              var chartHeight = $('html').height() *0.7;
              var treemap2 = d3.layout.treemap()
                  .round(false)
                  .size([chartWidth, chartHeight])
                  .sticky(true)
                  .value(function(d) {
                      return d.size;
              });
              buildTreemap(chart_selector2,url_json2,treemap2,false,chartWidth,chartHeight);
            }, 500);
    });
	$('#sectors_name').change(function(e) {
		var sector_name = $('#sectors_name').val();
		var chart_selector2 = "#treemap";
		$('#treemap svg').remove();
        var url_json2 = site_url + "card/company_tree/"+sector_name;
        var chartWidth = $('html').width();
        chartWidth = chartWidth*0.8*0.60;
        if($( window ).width() < 900)
        chartWidth = $('html').width()*0.74;
		
        var chartHeight = $('html').height() *0.7;

        var treemap2 = d3.layout.treemap()
          .round(false)
          .size([chartWidth, chartHeight])
          .sticky(true)
          .value(function(d) {
              return d.size;
            });
		buildTreemap(chart_selector2,url_json2,treemap2,false,chartWidth,chartHeight);
        var treemapResize;
    });
	$('#btn_go_company').click(function(e) {
		var search_company = $('#search_company').val();
		if(search_company!= ''){
			var chart_selector2 = "#treemap";
			$('#treemap svg').remove();
			var url_json2 = site_url + "card/company_search_tree/"+search_company;
			var chartWidth = $('html').width();
			chartWidth = chartWidth*0.70;
			if($( window ).width() < 900)
			chartWidth = $('html').width()*0.70;
			
			var chartHeight = $('html').height();
	
			var treemap2 = d3.layout.treemap()
			  .round(false)
			  .size([chartWidth, chartHeight])
			  .sticky(true)
			  .value(function(d) {
				  return d.size;
				});
			buildTreemap(chart_selector2,url_json2,treemap2,false,chartWidth,chartHeight);
			var treemapResize;
			auto_select_company(search_company);
		}
    });
});
function auto_select_company(search_company){
	$.ajax( {
        url :  site_url + "card/company_search_tree_param/"+search_company,
        error : function(){
        },
        success : function(result){
			var json_result = $.parseJSON(result);
			var company_name = json_result[0].company_name;
			var sector_name = json_result[0].sector;
			var industry_name = json_result[0].industry;
			var sic = json_result[0].sic;
			load_sic_companies(industry_name);
			$('#sectors_name option[value="'+sector_name+'"]').attr('selected', true);
			setTimeout(
    			function() {
			$('.company label[title="'+company_name+'"]').prev('input[type="checkbox"]').prop('checked', true);
			$('.company label[title="'+company_name+'"]').parents('ul.fourth_lvl').removeClass('collapsed').addClass('expanded').show();
			$('.company label[title="'+company_name+'"]').parents('ul.fourth_lvl').prev('.tree_element').children('a').removeClass('expand').addClass('collapse');
			$('.company label[title="'+company_name+'"]').parents('ul.fourth_lvl').prev('.tree_element').children('a').children('i').removeClass('fa-plus-circle').addClass('fa-minus-circle');
			update_checkboxes($('.company label[title="'+company_name+'"]').parents('ul.fourth_lvl'), true, 'comp_checkbox', 'sic_checkbox');
			}, 1000);
		}
    });
}
function buildTreemapKpi(){
    var chart_selector = "#treemap2";
    var card_data = get_card_data();
    var url_json = site_url + "explore/tree?id="+card_data['id']+"&companies="+card_data['companies']+"&kpis="+card_data['kpis']+"&kpi="+card_data['kpi']+"&period="+card_data['period'];
    $(chart_selector).empty();
    var chartWidth = (parseInt($("#card_core").width())-30);
    var chartHeight = 400;
    var _treemap = d3.layout.treemap()
        .round(false)
        .size([chartWidth, chartHeight])
        .sticky(true)
        .value(function(d) {
            return d.size;
        });

    buildTreemap(chart_selector,url_json,_treemap,true,chartWidth,chartHeight);
}

function buildTreemap (chart_selector,url_json,_treemap2,active_tooltip,chartWidth,chartHeight,show_top_header){

    $.getScript('assets/js/d3.v2.min.js', function() {

        var treemap2 = _treemap2;
        var isIE = BrowserDetect.browser == 'Explorer';
        var xscale = d3.scale.linear().range([0, chartWidth]);
        var yscale = d3.scale.linear().range([0, chartHeight]);
        //var color = d3.scale.category10();
        var color = d3.scale.ordinal().range(['#7884A0', '#44837C', '#C5C5C5'])
        var headerHeight = 20;
        var headerColor = "#22262E";
        var headerTextColor = "#B3D31A";
        var transitionDuration = 500;
        var root;
        var node;



        var svg = d3.select(chart_selector)
            .append("svg:svg")
            .attr("width", chartWidth)
            .attr("height", chartHeight);

        var chart = svg.append("svg:g");

        var defs = svg.append("defs");

        var filter = defs.append("svg:filter")
            .attr("id", "outerDropShadow")
            .attr("x", "-20%")
            .attr("y", "-20%")
            .attr("width", "140%")
            .attr("height", "140%");

        filter.append("svg:feOffset")
            .attr("result", "offOut")
            .attr("in", "SourceGraphic")
            .attr("dx", "1")
            .attr("dy", "1");

        filter.append("svg:feColorMatrix")
            .attr("result", "matrixOut")
            .attr("in", "offOut")
            .attr("type", "matrix")
            .attr("values", "1 0 0 0 0 0 0.1 0 0 0 0 0 0.1 0 0 0 0 0 .5 0");

        filter.append("svg:feGaussianBlur")
            .attr("result", "blurOut")
            .attr("in", "matrixOut")
            .attr("stdDeviation", "3");

        filter.append("svg:feBlend")
            .attr("in", "SourceGraphic")
            .attr("in2", "blurOut")
            .attr("mode", "normal");


        d3.json(url_json, function(data) {
            node = root = data;
            var nodes = treemap2.nodes(root);

            var children = nodes.filter(function(d) {
                return !d.children;
            });
            var parents = nodes.filter(function(d) {
                return d.children;
            });

            // create parent cells
            var parentCells = chart.selectAll("g.cell.parent")
                .data(parents, function(d) {
                    return "p-" + d.id;
                });
            var parentEnterTransition = parentCells.enter()
                .append("g")
                .attr("class", "cell parent")
                .on("click", function(d) {
                    if(zoom_in)
                        zoom(d,treemap2);
                    else
                        zoom(d.parent,treemap2);
                    zoom_in = !zoom_in;
                });
            parentEnterTransition.append("rect")
                .attr("width", function(d) {
                    return Math.max(0.01, d.dx);
                })
                .attr("height", headerHeight)
                .style("fill", headerColor)
                ;
            parentEnterTransition.append('foreignObject')
                .attr("class", "foreignObject")
                .append("xhtml:body")
                .attr("class", "labelbody")
                .append("div")
                .attr("class", "label")
                .style("color", headerTextColor)
                .attr("title", function(d) {
                    return d.name;
                });

            var parentUpdateTransition = parentCells.transition().duration(transitionDuration);
            parentUpdateTransition.select(".cell")
                .attr("transform", function(d) {
                    return "translate(" + d.dx + "," + d.y + ")";
                });
            parentUpdateTransition.select("rect")
                .attr("width", function(d) {
                    return Math.max(0.01, d.dx);
                })
                .attr("height", headerHeight)
                .style("fill", headerColor);
            parentUpdateTransition.select(".foreignObject")
                .attr("title", function(d) {
                    return d.name;
                })
                .attr("width", function(d) {
                    return Math.max(0.01, d.dx);
                })
                .attr("height", headerHeight)
                .select(".labelbody .label")
                .text(function(d) {
                    return d.name;
                });
            // remove transition
            parentCells.exit()
                .remove();


            // create children cells
            var childrenCells = chart.selectAll("g.cell.child")
                .data(children, function(d) {
                    return "c-" + d.id;
                });
            // enter transition
            var childEnterTransition = childrenCells.enter()
                .append("g")
                .attr("class", "cell child")
                .attr("data-name", function(d) {
                    if(active_tooltip){
                        for(var key in d) {
                            if(key.startsWith("data-")){
                                var value = d[key];
                                $(this).attr(key,value);
                            }
                        }
                        return d.name;
                    }
                    else
                        return "";
                })
                .on("click", function(d) {
                    if(zoom_in){
                        zoom(node === d.parent ? root : d.parent,treemap2);
                        zoom_in = !zoom_in;
                    }
                    else
                        load_sic_companies(d.id);
                })
                .on("mouseover", function() {
                    if(active_tooltip)
                        create_tooltips2(this);
                })
                .on("mouseout", function() {
                });


            childEnterTransition.append("rect")
                .classed("background", true)
                .style("fill", function(d) {
                    return color(d.parent.name);
                });
            childEnterTransition.append('foreignObject')
                .attr("class", "foreignObject")
                .attr("width", function(d) {
                    return Math.max(0.01, d.dx);
                })
                .attr("title", function(d) {
                    return d.name;
                })
                .attr("height", function(d) {
                    return Math.max(0.01, d.dy);
                })
                .append("xhtml:body")
                .attr("class", "labelbody")
                .append("div")
                .attr("class", "label")
                .text(function(d) {
                    return d.name;
                });

            if (isIE) {
                //childEnterTransition.selectAll(".foreignObject .labelbody .label").style("display", "none");
            } else {
                //childEnterTransition.selectAll(".foreignObject").style("display", "none");
            }

            // update transition
            var childUpdateTransition = childrenCells.transition().duration(transitionDuration);
            childUpdateTransition.select(".cell")
                .attr("transform", function(d) {
                    return "translate(" + d.x  + "," + d.y + ")";
                });
            childUpdateTransition.select("rect")
                .attr("width", function(d) {
                    return Math.max(0.01, d.dx);
                })
                .attr("height", function(d) {
                    return d.dy;
                })
                .style("fill", function(d) {
                    return color(d.parent.name);
                });
            childUpdateTransition.select(".foreignObject")
                .attr("title", function(d) {
                    return d.name;
                })
                .attr("width", function(d) {
                    return Math.max(0.01, d.dx);
                })
                .attr("height", function(d) {
                    return Math.max(0.01, d.dy);
                })
                .select(".labelbody .label")
                .text(function(d) {
                    return d.name;
                });
            // exit transition
            childrenCells.exit()
                .remove();

            zoom(node,treemap2);


        });

    //and another one
    function textHeight(d) {
        var ky = chartHeight / d.dy;
        yscale.domain([d.y, d.y + d.dy]);
        return (ky * d.dy) / headerHeight;
    }


    function getRGBComponents (color) {
        var r = color.substring(1, 3);
        var g = color.substring(3, 5);
        var b = color.substring(5, 7);
        return {
            R: parseInt(r, 16),
            G: parseInt(g, 16),
            B: parseInt(b, 16)
        };
    }


    function idealTextColor (bgColor) {
        var nThreshold = 105;
        var components = getRGBComponents(bgColor);
        var bgDelta = (components.R * 0.299) + (components.G * 0.587) + (components.B * 0.114);
        return ((255 - bgDelta) < nThreshold) ? "#000000" : "#ffffff";
    }


    function zoom(d,treemap) {
        treemap
            .padding([headerHeight/(chartHeight/d.dy), 0, 0, 0])
            .nodes(d);

        // moving the next two lines above treemap layout messes up padding of zoom result
        var kx = chartWidth  / d.dx;
        var ky = chartHeight / d.dy;
        var level = d;

        xscale.domain([d.x, d.x + d.dx]);
        yscale.domain([d.y, d.y + d.dy]);

        if (node != level) {
            if (isIE) {
                //chart.selectAll(".cell.child .foreignObject .labelbody .label").style("display", "none");
            } else {
                //chart.selectAll(".cell.child .foreignObject").style("display", "none");
            }
        }

        var zoomTransition = chart.selectAll("g.cell").transition().duration(transitionDuration)
            .attr("transform", function(d) {
                return "translate(" + xscale(d.x) + "," + yscale(d.y) + ")";
            })
            .each("end", function(d, i) {

                if (!i && (level !== self.root)) {
                    chart.selectAll(".cell.child")
                        .filter(function(d) {
                            return d.parent === self.node; // only get the children for selected group
                        })
                        .select(".foreignObject .labelbody .label")
                        .style("color", function(d) {
                            return idealTextColor(color(d.parent.name));
                        });

                    if (isIE) {
                        chart.selectAll(".cell.child")
                            .filter(function(d) {
                                return d.parent === self.node; // only get the children for selected group
                            })
                            .select(".foreignObject .labelbody .label")
                            .style("display", "")
                    } else {
                        chart.selectAll(".cell.child")
                            .filter(function(d) {
                                return d.parent === self.node; // only get the children for selected group
                            })
                            .select(".foreignObject")
                            .style("display", "")
                    }
                }
            });

        zoomTransition.select(".foreignObject")
            .attr("width", function(d) {
                return Math.max(0.01, kx * d.dx);
            })
            .attr("height", function(d) {
                return d.children ? headerHeight: Math.max(0.01, ky * d.dy);
            })
            .select(".labelbody .label")
            .text(function(d) {
                return d.name;
            });

        // update the width/height of the rects
        zoomTransition.select("rect")
            .attr("width", function(d) {
                return Math.max(0.01, kx * d.dx);
            })
            .attr("height", function(d) {
                return d.children ? headerHeight : Math.max(0.01, ky * d.dy);
            })
            .style("fill", function(d) {
                return d.children ? headerColor : color(d.parent.name);
            });

        node = d;

        if (d3.event) {
            d3.event.stopPropagation();
        }
    }

    });

}



function create_tooltips2(obj){
    var card_data = get_card_data();
    var sort = card_data['kpi'];
    var item = $(obj);
    var tip_rank = $(item).attr("data-index");
    var tip_name = $(item).attr("data-name");
    var tip_value ="";

    //TODO foreach KPIS.
    var kpis =  card_data['kpis'].split(',');
    var tip_value = "";
    var tip_text = "";
    for (var i = kpis.length - 1; i >= 0; i--) {
        if(sort==kpis[i]) 
        {
            if($('#filters ul.kpis_select').length == 0)
            {
                if($(item).attr("data-"+sort+"-exist") == 'true')
                {
                    tip_value = $(item).attr("data-"+sort);
                    tip_text = $('#filters [data-kpi-id="'+sort+'"]').title();
                }
                else
                {
                    tip_value = 'Not Provided';
                    tip_text = $('#filters [data-kpi-id="'+sort+'"]').title();
                }
            }
            else
            {
                if($(item).attr("data-"+sort+"-exist") == 'true')
                {
                    tip_value = $(item).attr("data-"+sort);
                    tip_text = $('#filters a.active').attr('title');
                }
                else
                {
                    tip_value = 'Not Provided';
                    tip_text = $('#filters a.active').attr('title');
                }
            }
        }
    };
    //var tip_value = get_displayable_value(tip_value,sort);
    //var tip_text = get_displayable_desc(sort);
    var html = "";
    //html += '<span class="tip_rank">#'+tip_rank+'</span><br/>';
    html += '<span class="tip_name">'+tip_name+'</span><br/>';
    if(tip_value != 'Not Provided')
        html += '<span class="tip_revenue">'+get_displayable_value(tip_value)+'</span><br/>';
    else
        html += '<span class="tip_revenue">'+tip_value+'</span><br/>';

    html += '<span class="tip_text">'+tip_text+'</span><br/>';

    $(obj).qtip({
        overwrite: true, // Don't overwrite tooltips already bound
        show: {
            //event: event.type, // Use the same event type as above
            ready: true // Show immediately - important!
            },
        content: html,
        position: {
            my: 'bottom left',
            at: 'top right',
            target: 'mouse', // Track the mouse as the positioning target
            adjust: { x: 3, y: -2 } // Offset it slightly from under the mouse
            },
        style: {classes: 'qtip-green qtip-rounded qtip-shadow'}
        });
    

}
/*===========================================================================*/
var card_data = get_card_data();
if(card_data.type_chart=='explore' || card_data.type_chart=='map' || card_data.type_chart=='tree' || card_data.type_chart=='combo_new' || card_data.type_chart=='column' || card_data.type_chart=='area' || card_data.type_chart=='line' || card_data.type_chart=='column') {
    //alert(card_data.type_chart)
    /////////////////// Get details /////////////////////
    $.ajax( {
        url :  site_url + 'card/getcarddetails',
        data: card_data,
        type:"GET",
        dataType: 'json',
        error : function(){
            $('.add_card').unblock();
        },
        success : function(detail) {
            $('.new_filter').remove();

            var style = $('#preview_style').val();

            if (typeof detail.details[0] !== 'undefined') {

                if($('#loggedin').val()==1 || detail.preveiw_title=='')
                {
                    //$('#filters').remove();

                    var description = '<div id="filters" class="new_filter"><ul class="card_des mCustomScrollBox  '+detail.details[0].style+' kpis_select" style="float: right; width: 24%; position: absolute; top:0; right:0;"><li><h1>'+detail.details[0].title+'</h1>'+detail.details[0].description;+'</li></ul></div>';
                }
                else
                {
                    //	$('#filters').remove();
                    if(style=="")
                    {
                        style = detail.details[0].style;
                    }
                    var description = '<div id="filters" class="new_filter"><ul class=" mCustomScrollBox card_des '+style+' kpis_select" style="float: right; width: 24%; position: absolute; top:0; right:0;"><li><h1>'+detail.preveiw_title+'</h1>'+detail.preview_description+'</li></ul></div>';
                }
                $(description).insertAfter('.content');
            }
        }

    });

}
/*===========================================================================*/