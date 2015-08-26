function get_companies(set_filter) {
    var is_rank = $(".control.explore.rank").attr("islist");
    is_rank = (is_rank=="false"?false:true);
    toggle_grid_list(is_rank, set_filter);
    set_quicksand();
    }

function toggle_grid_list(is_list, set_filter) {
    var card_data = get_card_data();
    var kpi = card_data['kpi'];
    var order = card_data['order'];
	
    if (is_list) {
        $('#card_core .grid ul#source li.element-item').addClass('list_item');
        $('#card_core .grid ul#source li.element-item').removeClass('cell');
        $('#card_core .grid ul#source li.element-item').removeClass('hide');

        $('#ul_control_sort').show();
        $('.controls_rank').css('display', 'inline-block');
        $('#filters').hide();
    }
    else {
        $('#card_core .grid ul#source li.element-item').addClass('cell');
        $('#card_core .grid ul#source li.element-item').removeClass('list_item');
        $('#card_core .grid ul#source li.element-item').removeClass('hide');

        $('#filters').removeClass('hide');
        $('#filters').show();
        $('#filters button.kpis_select_button').show();
        $('#ul_control_sort').hide();
        $('.controls_rank').css('display', 'inline');

        // efj 15-02-27 hide all the dd checkboxes
        //$('#card_core .grid ul#source li.element-item .grid_view input:checkbox').hide();
        $('#card_core .grid ul#source li.element-item .grid_view .dd_cb').hide();
        dimCompanies = [];

        // and uncheck the boxes
        $('#card_core .grid ul#source li.element-item .grid_view input:checkbox').each(function() {
            $(this).prop('checked', false);
        });

    }
    if (set_filter)
        set_filters(kpi, order);

    // efj 15-02-24
    $("#drilldown_btn_div").hide();

    if (card_data['type_chart'] == "rank") {
        $('#filters').hide();
        $('.controls_rank').show();
    }
    else if (card_data['type_chart'] == "explore") {
        $('#filters').show();
        $('.controls_rank').hide();

        var oneExists = false;
        // and display the correct drilldown cb's
        $('#card_core .grid ul#source li.element-item .grid_view input:checkbox').each(function() {

            var dataIndex = $(this).attr('d_index');
            var ddDataExists = $("li[data-index='" + dataIndex +"']" ).attr('dd-data-'+kpi);

            if (ddDataExists === 'true') {
                oneExists = true;
                $(this.parentElement).show();
            }
        });

        if (oneExists === true) {
            // efj 15-02-24
            $("#drilldown_btn_div").show();
        }
    }
}

function get_filters(){
    var kpi_id = $("#filters .kpi.active").attr("data-kpi-id");
    var name_attr_value = "data-"+kpi_id;
    if($("#filters button.active i").hasClass("fa-sort-amount-asc"))
        var sort_ascending = true;
    else
        var sort_ascending = false;

    if($(".add_card .square[rank]").hasClass("active"))
        var is_list = true;
    else
        var is_list = false;
    var f = {
        sort : kpi_id,
        name_attr_value : name_attr_value,
        ascending : sort_ascending,
        is_list : is_list
        }
    return f;
    }    
function set_filters(kpi, order){
    if(kpi) {
        var active_a = $('ul.kpis_select li a.kpi.active');
        if(active_a.parent().is( "strong" ) ) 
            active_a.unwrap();
        $('ul.kpis_select li a.kpi').removeClass('active');
        var new_active = $('ul.kpis_select a[data="'+kpi+'"]');
        new_active.addClass('active').wrap('<strong></strong>');
        //$('#kpi_text').text(cut_string(new_active.attr('title'), 20));
		$('#kpi_text').text(cut_string(new_active.html(), 20));
        //$('#kpi_text').parent().attr('title', new_active.attr('title'));
        $('#active_kpi_desc').attr('data-original-title', new_active.attr('data-desc'));
        $('ul.kpis_select input[name="kpis_select"]').val(new_active.attr('data'));

        $('#filters button i').removeClass("fa-sort-amount-desc").removeClass("fa-sort-amount-asc");
        
        $('#filters [data-kpi-id="'+kpi+'"]').addClass("active");
        if($('#filters ul.kpis_select').length == 0)//if filters are displayed like buttons
        {
            if($.trim(order).toLowerCase() == "asc")
                $('#filters [data-kpi-id="'+kpi+'"] i').addClass("fa-sort-amount-asc");        
            else
                $('#filters [data-kpi-id="'+kpi+'"] i').addClass("fa-sort-amount-desc");
        }
        else //if filters are displayed in a dropdown
        {
            //alert(order);
            if($.trim(order).toLowerCase() == "asc")
                $('#filters  button i').addClass("fa-sort-amount-asc");        
            else
                $('#filters  button i').addClass("fa-sort-amount-desc");
        }
        $('.controls_rank ul.sort li').each(function (){
            var txt = $(this).html();
            txt = txt.replace('<i class="fa fa-level-down"></i>',"");
            txt = txt.replace('<i class="fa fa-level-up"></i>',"");
            $(this).html(txt);
            $(this).removeClass("active_sort");
            });
        $('.controls_rank ul.sort li[name="'+kpi+'"]').addClass("active_sort");
        if($.trim(order).toLowerCase() == "asc"){
            var txt = $('.controls_rank ul.sort li[name="'+kpi+'"]').html();
            txt = txt.replace('<i class="fa fa-level-down"></i>',"");
            txt = txt.replace('<i class="fa fa-level-up"></i>',"");
            txt += '<i class="fa fa-level-up"></i>';
            $('.controls_rank ul.sort li[name="'+kpi+'"]').html(txt);
            }
        else{
            var txt = $('.controls_rank ul.sort li[name="'+kpi+'"]').html();
            if(txt)
            {
                txt = txt.replace('<i class="fa fa-level-down"></i>',"");
                txt = txt.replace('<i class="fa fa-level-down"></i>',"");
                txt += '<i class="fa fa-level-down"></i>';
                $('.controls_rank ul.sort li[name="'+kpi+'"]').html(txt);
            }
        }
    }
} 
   
function getSortData(){
    sortData = {};
    var $source = $('#filters .kpi[data-kpi-id]'); 
    $source.each(function() {
        key = $(this).attr('data-kpi-id'); 
        sortData['data-'+key] = '[data-'+key+'] parseFloat';
        });
    return sortData;
    } 
function set_quicksand(data){
	
    var sort_fct = getSortData();
    var f = get_filters();
    var _sort_ = 'data-'+f['sort'];
    var sort_ascending = f['ascending'];
    if($('#card_core .grid').attr('first_load') != "first_load"){
        sort_ascending = true;
        $('#card_core .grid').attr('first_load','first_load');
    }
    
    var is_list = f['is_list'];
    $('#card_core .grid').isotope({
        itemSelector: '.element-item',
        transitionDuration: '1s',
        sortAscending: sort_ascending,
        sortBy: _sort_ ,
        layoutMode: (is_list?'vertical':'masonry'),
        vertical: {
            horizontalAlignment: 0,
            columnWidth : 16,
            columnHeight : 16
            },
         masonry: { 
            columnWidth : 16,
            columnHeight : 16
            },
        getSortData: sort_fct
    });

    $('#card_core .grid').isotope( 'on', 'layoutComplete', function() {
        init_cell();
        $('#card_core .grid').isotope( 'updateSortData', getSortData() );
        return true;
    });
}

function create_tooltips(){
    $('#card_core .grid .element-item, g.child').on('mouseenter', '.companyIcon', function (event) {
        var card_data = get_card_data();
        var sort = card_data['kpi'];
        var item = $(this).parent();
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
                    if($(item).attr("data-"+sort+"-exist") == 'true')
                    {
                        tip_value = $(item).attr("data-"+sort);
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
        //var tip_value = get_displayable_value(tip_value,sort);
        //var tip_text = get_displayable_desc(sort);
        var html = "";
        //html += '<span class="tip_rank">#'+tip_rank+'</span><br/>';
        html += '<span class="tip_name">'+tip_name+'</span><br/>';
        if(tip_value == 'Not Provided')
            html += '<span class="tip_revenue">'+'Not Provided'+'</span><br/>';
        else
            html += '<span class="tip_revenue">'+ get_displayable_value(tip_value)+'</span><br/>';
        html += '<span class="tip_text">'+tip_text+'</span><br/>';
        $(this).qtip({
            overwrite: true, // Don't overwrite tooltips already bound
            show: {
                event: event.type, // Use the same event type as above
                ready: true // Show immediately - important!
                },
            content: html,
            position: {
                my: 'bottom left',
                at: 'top right',
                target: 'mouse', // Track the mouse as the positioning target
                adjust: {
                    x: 3, 
                    y: -2
                } // Offset it slightly from under the mouse
                },
            style: {
                classes: 'qtip-green qtip-rounded qtip-shadow'
            }
            });
        });
    }


function init_cell(){
    var sort = get_filters()['name_attr_value'];
    $('#card_core .grid .element-item').each(function(index, value) {
        if($(this).attr(sort+'-exist') == 'true')
        { 
            var txt = $(this).attr(sort);
            txt = get_displayable_value(txt,sort);
            $(this).find('.num span').text(txt).fadeIn();
        }
        else
        {
            txt = "Not Provided";
            $(this).find('.num span').text(txt).fadeIn();
        } 
    });
    create_tooltips();
    }
function get_displayable_value(val){
    val = parseFloat(val);
    return format_number(val);
    }    
function format_number(val){
    var is_neg = (val < 0)? true : false;
    val = Math.abs(val); 
    var r = val;

    if(val>=1000000000000) 
        r = ((Math.round((val/10000000000)))/100)+' T';
    else if(val>=1000000000) 
        r = ((Math.round((val/10000000)))/100)+' B';
    else if(val>=1000000) 
        r = ((Math.round((val/10000)))/100)+' M';
    else
        r = addCommas(val);
    //else if($val>1000) return round(($val/1000),1).' thousand';
    r = (is_neg)? '-'+r: r;
    return r;
}

function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    x2 = x2.substring(0, 2);
    if(x2 == '.00')
        x2 = '';
    return x1 + x2;
}


function active_scroll(){
    var h = $('div#companies_animation_container').height();
    $('.grid').isotope( );
    $('.explore  .grid').height(h);
    $('.explore  .grid').slimScroll({
        height: h,
        color: '#B3D31A',
        alwaysVisible: true,
        distance: '20px',

        railVisible: true,
        wheelStep :'0px',
        railColor: '#222',
        railOpacity: 0.15,
        wheelStep: 10,
        allowPageScroll: true,
        disableFadeOut: true
        });
    $('.explore  .grid').height(h);
    }

Number.prototype.format = function(n, x) {
    var re = '(\\d)(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$1,');
    };
function compare(a,b) {
    if (a.name < b.name) return -1;
    if (a.name > b.name) return 1;
    return 0;
    }
function my_merger(_old,_new, in_config){
    var arr3 = [];
    for(var i in _old){
        var shared = false;
        for (var j in _new) {

            if(in_config){
                new_name = _new[j].name;
                old_name = _old[i].config.name;
                }
            else{
                new_name = _new[j].config.name;
                old_name = _old[i].name;
                }
            if ( new_name == old_name) {
                shared = true;
                break;
                }
            }
        var new_marker;
        if(in_config)
            new_marker = {
                latLng : _old[i].config.latLng,
                name : _old[i].config.name,
                style : {
                    fill: _old[i].config.style.fill,
                    r: 0
                }
                        };
        else
            new_marker = {
                latLng : _old[i].latLng,
                name : _old[i].name,
                style : {
                    fill: _old[i].style.fill,
                    r: 0
                }
                        };

        if(!shared) arr3.push(new_marker)
    }
    arr3 = arr3.concat(_new);
    return arr3;
    }
(function($) {
    $.fn.sorted = function(customOptions) {
        var options = {
            reversed: false,
            by: function(a) {
                return a.text();
            }
        };
        $.extend(options, customOptions);
        $data = $(this);
        arr = $data.get();
        arr.sort(function(a, b) {
            var valA = options.by($(a));
            var valB = options.by($(b));
            if (options.reversed) {
                return (valA < valB) ? 1 : (valA > valB) ? -1 : 0;
            } else {
                return (valA < valB) ? -1 : (valA > valB) ? 1 : 0;
            }
        });
        return $(arr);
    };
    })(jQuery);
Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) 
        if (obj.hasOwnProperty(key)) size++;
    return size;
    };
