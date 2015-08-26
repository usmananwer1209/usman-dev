var map_obj;
var markers;
var animation_markers;
var AnimationMarkersIndex = 0;
var AnimationMarkersTimerId;
var aimation_marker = {
    step : 25,
    duration:800,
    }


function createMap(load_ajax){
    var card_data = get_card_data();
    filter = get_filters();
    filter = (filter == '-1' ? '' : filter);
    
    if(load_ajax)
        $('.add_card').block({ message: null }); 
    $.ajax( {
        url :  site_url + 'explore/markers',
        data: card_data,
        type:"POST",
        error : function(){
            $('.add_card').unblock();
        },
        success : function(data) {
            $("#views").css("display","block");
            $(".map").css("display","block");
            $("#usa_map").css("display","block");

            var new_markers = eval(data);
            markers = new_markers;
            $('.add_card').block({ message: null }); 
            $('.add_card').unblock();
            
            if(!markers)
                var markers = [];


            var div = $("#usa_map");
            var map_options = {
                container: div,
                map: 'us_aea_en',
                zoomOnScroll: true,
                backgroundColor: '#FFFFFF',
                markersSelectable: false,
                markerStyle: {
                    initial: {
                        stroke: 'rgba(169,198,21,0.75)',
                        },
                    selected: {
                        stroke: 'rgb(0,0,0,0)',
                        },
                    hover: {
                        stroke: '#DFE7B7',
                        },
                    },
                regionStyle: {
                    initial:{
                        fill: '#B7B7B7',
                        "fill-opacity": 1,
                        stroke: 'none',
                        "stroke-width": 0,
                        "stroke-opacity": 1
                        },
                    },
                onMarkerOver: function(e, code) {
                    create_tooltips_map(e,code);
                }
            };
            if(map_obj)
                $(div).find('*').remove();
            map_obj = new jvm.WorldMap(map_options);
            map_obj.reset();
            map_obj.removeAllMarkers();
            map_obj.addMarkers(new_markers);
            return map_obj;
            }
        });
    }




function create_tooltips_map(e,code){
    //$('body').on('mouseenter', '.jvectormap-marker', function (event) {
        var circle = $('circle.jvectormap-marker[data-index="'+code+'"]');

        var sort = get_filters()['sort'];
        var data_index = $(circle).attr("data-index");
        var tip_rank = $(circle).attr("data_index");
        var tip_name = $(circle).attr("data_name");

        var card_data = get_card_data();
        var kpis =  card_data['kpis'].split(',');
        var tip_value = "";
        var tip_text = "";
        for (var i = kpis.length - 1; i >= 0; i--) {
            if(sort==kpis[i]) 
            {
                if($('#filters ul.kpis_select').length == 0)
                {
                    if($(circle).attr("data_"+sort+"_exist") == 'true')
                    {
                        tip_value = $(circle).attr("data_"+sort);
                        tip_text = $('#filters [data-kpi-id="'+sort+'"]').text();
                    }
                    else
                    {
                        tip_value = 'Not Provided';
                        tip_text = $('#filters [data-kpi-id="'+sort+'"]').text();
                    }
                }
                else
                {
                    if($(circle).attr("data_"+sort+"_exist") == 'true')
                    {
                        tip_value = $(circle).attr("data_"+sort);
                        tip_text = $('#filters a.active').text();
                    }
                    else
                    {
                        tip_value = 'Not Provided';
                        tip_text = $('#filters a.active').text();
                    }
                }
            }
        };
        var html = "";
        //html += '<span class="tip_rank">#'+tip_rank+'</span><br/>';
        html += '<span class="tip_name">'+tip_name+'</span><br/>';
        if(tip_value != 'Not Provided')
            html += '<span class="tip_revenue">'+get_displayable_value(tip_value)+'</span><br/>';
        else
            html += '<span class="tip_revenue">'+tip_value+'</span><br/>';
        html += '<span class="tip_text">'+tip_text+'</span><br/>';

        $(circle).qtip({
            overwrite: true,
            show: {
                event: e.type, 
                ready: true
                },
            content: html,
            position: {
                my: 'bottom left',
                at: 'top right',
                target: 'mouse', 
                adjust: { x: 3, y: -2 } 
                },
            style: {classes: 'qtip-green qtip-rounded qtip-shadow'}
            });
        //});
    }
