var AnimationNumBarTimerId;
var opacity_bar = 0;
var opacity_num = 0;
var bar_mode;
var aimation_bar = {
    step : 10,
    duration:400
    }

var tmp_opacity = {
    num : -1,
    bar : -1
}
var arr;

$(document).ready(function(){
    $('#reporting_period_edit').hide();
    $('#view_card_reporting_period').hide();
    $('#filters').hide();

    //fill card infromation coming from the API
    $('#sources').attr('value', $('#source').attr('datasources'));
    update_data_points();

    arr = calc_arr();

    dimCompanies = [];

    $('body').on('click', '#item_nbr', function(e) {

        animation_bar_to_num(arr);
        return false;
    });

    $('body').on('click', '#item_chart', function(e) {
        animation_num_to_bar(arr);
        return false;
    });

    $('body').on('click', 'ul#reporting_period a', function(e){
        e.preventDefault();
        $('span#error_data').remove();
        $('ul#reporting_period input[name="reporting_period"]').val($(this).attr('data'));;
        //$('ul#reporting_period input[name="reporting_period"]').trigger('change');
        if($('#view_card_reporting_period').length){
			//alert('there');
            update_card_data(true);
		}else{
			//alert('there1');
            //validation, we need to have companies and kpis
            var card_data = get_card_data(); 
            obj = card_data['type_chart'];
            $(this).parent().find('.error').remove();
            if($('select[name="companies"] option').length && ($('select[name="kpis"] option').length || obj == 'combo')){
                $('.card_description').find('.error').remove();
                explore_rank(true);
                calc_arr();
            }
            else
            {
              var elmt = $('#show_chart');
              if($('select[name="companies"] option').length)
                $('<span class="error" id="error_data">You need to add at least one KPI to generate a card</span>').insertBefore(elmt);
              else
                $('<span class="error" id="error_data">You need to add at least one Company to generate a card</span>').insertBefore(elmt);
            }
        }
    });

    $('body').on('click', 'ul.kpis_select a', function(e){
        e.preventDefault();
        var active_a = $('ul.kpis_select li a.kpi.active');
        if(active_a.parent().is( "strong" ) ) 
            active_a.unwrap();
        $('ul.kpis_select li a.kpi').removeClass('active');
        $(this).addClass('active').wrap('<strong></strong>');
        //$('#kpi_text').text(cut_string($(this).text(), 20));
		var filtername = '';
		if($(this).attr('title')!="")
		{
			filtername = $(this).attr('title');
		}
		else
		{
			filtername = $(this).attr('alt');
		}
        //$('#kpi_text').text(cut_string($(this).text(), 20));
		$('#kpi_text').text(cut_string(filtername, 20));
		//$('#kpi_text').text(cut_string($(this).attr('title'), 20));
        //$('#kpi_text').parent().attr('title', $(this).html());
        $('#active_kpi_desc').attr('data-original-title', $(this).attr('data-desc'));
        $('ul.kpis_select input[name="kpis_select"]').val($(this).attr('data'));
        $('ul.kpis_select input[name="kpis_select"]').trigger('change');
    });

    //for rank, we create the fixed part div -- TO DO
    /*if($('.control.explore.rank').attr('islist') == 'true')
    {
        var fixed_div = $('<div id="fixed_elemt"></div>');
        $('.list_view .ident').each(function(i){
            fixed_div.append($(this).clone());
        });
        fixed_div.appendTo($('#explore_rank'));
    }*/
    
    $("#kpi_text").text($('ul.kpis_select li a.kpi.active').html());
    
});

$('body').on('change', "input[name='drilldown_cb']", function(){

    //
    if ( $( "input[name='drilldown_cb']:checked" ).length > 4) {

        $('#drilldown_msg_Modal', window.parent.document).modal('show');

        this.checked = false;
    }

});

$('body').on('click', '#show_drilldown_btn', function(){

    if ($( "input[name='drilldown_cb']:checked" ).length > 0) {

        var kpi = $("input[name='kpis_select']").val();
        var year = $("input[name='reporting_period']").val();

        var fiscal_type = 'FY';
        if (year.length == 6) {
            fiscal_type = year.substring(4);
            year = year.substring(0, 4);
        }

        var dimCompanies = [];

        $("input[name='drilldown_cb']:checked").each(function() {
            dimCompanies.push($(this).val());
        });

        draw_drilldown(dimCompanies.join(','),kpi, year, fiscal_type );
    }
});

function calc_arr()
{
    $('#source .list_view .scores .positive').each(function(i){ 
        var str =$(this).find("div.num").html();
        if (str && str.indexOf('-') >= 0){
            $(this).removeClass('positive').addClass('negative');
        }
    });
    
    $('#source .list_view .scores .negative .progress-bar-success').each(function(i){ 
        $(this).removeClass('progress-bar-success').addClass('progress-bar-danger');
    });
    
    var arr  = [[]];
    $('#ul_control_sort li').each(function(i){ 
        var _current_kpi = $(this).attr('name');
        arr[i] = [];
        $('#source .list_view .scores .num[data-'+_current_kpi+']').each(function(j){ 
            arr[i][j] = $(this).siblings('.progress').attr('data-'+_current_kpi);
        });

        arr[i] = percentify(arr[i]);    
    });

    $('#ul_control_sort li').each(function(i){ 
        var _current_kpi = $(this).attr('name');
        $('#source .list_view .scores .num[data-'+_current_kpi+']').each(function(j){ 
            $(this).siblings('.progress').find('.progress-bar').attr('data-percentage',arr[i][j]+'%').css('width','0%');
        //$(this).siblings('.progress').find('.progress-bar').attr('data-percentage',arr[i][j]+'%').css('width',arr[i][j]+'%');
        //$(this).siblings('.progress').find('.progress-bar').css('width','0%');
        });
    });

    return arr;
}


$(window).on('load', function () {
    //$('select[name="period"]').select2();    
    //$('select.kpis_select').select2();   
    $('#period_buttons').remove(); 

    var obj = ($('.control.explore.rank').attr('islist') == 'true')? 'rank':'explore';
    if(obj == 'rank')
        $('#explore_rank .grid').css('width', $('#explore_rank').attr('data-width'));
    if(obj == 'explore')
        $('#explore_rank .grid').css('width', '100%');


    $('body').on('change', '#view_card_reporting_period input', function(){
        //explore_rank();
        update_card_data(true);

    });

    var w = $("#companies_animation_container").width();
    /*$('#filters').slimScroll({
        height: '42px',
        width: w
    });*/
    get_companies(true);
    var card_data = get_card_data();
    $('#reporting_period_edit').show();
    if(card_data['type_chart'] == "explore" || card_data['type_chart'] == "rank" ){
        $("#filters button i").show();
        get_companies();
    }
    else if(card_data['type_chart'] == "tree" ){
        buildTreemapKpi();
        $('#filters').css('display', 'inline-block');
        if($('#filters ul.kpis_select').length)
            $("#filters button").hide();
        else
            $("#filters button i").hide();
        $('.controls_rank').hide();            
    }
    else if(card_data['type_chart'] == "map" ){
        $('#filters').css('display', 'inline-block');
        if($('#filters ul.kpis_select ').length)
            $("#filters button").hide();
        else
            $("#filters button i").hide();
        $('.controls_rank').hide();            
        createMap();
    }
    else if(card_data['type_chart'] == "line" || card_data['type_chart'] == "column" || card_data['type_chart'] == "area" || card_data['type_chart'] == "combo" || card_data['type_chart'] == "combo_new"){
        $('#filters').css('display', 'inline-block');
        if($('#filters ul.kpis_select').length)
            $("#filters button").hide();
        else
            $("#filters button i").hide();
        $('.controls_rank').hide();            
        buildChart(card_data['type_chart']);
		
    }
    $('#companies_animation_container').css("visibility", "visible");

    $('body').on('mouseenter', '#source .list_view', function() {
        if (bar_mode) {
            $(this).find('.scores .progress').css('opacity', '0');
            $(this).find('.scores .progress-bar').css('opacity', '0');
            $(this).find('.scores .num').css('opacity', '1');
        }
    });

    $('body').on('mouseleave', '#source .list_view', function() {
        if (bar_mode) {
            $(this).find('.scores .progress').css('opacity', '1');
            $(this).find('.scores .progress-bar').css('opacity', '1');
            $(this).find('.scores .num').css('opacity', '0');
        }
    });


});

function get_active_chart(){
	
    var card_data = get_card_data(); 
    $('#period_buttons').remove(); 
    obj = card_data['type_chart'];

    $("#card_core #views > div").hide();
    $("#card_core #views > div."+(obj=="rank"?"explore":obj)).show();
    $('#reporting_period_edit').show();

    // 15-Mar-25 efj added
    $("#drilldown_btn_div").hide();

    if(obj=="rank" || obj=="explore"){
        var is_rank = (obj=="explore"?"false":"true");
        $(".control.explore.rank").attr("islist",is_rank);
		
        get_companies(true);
    }
    else if(obj=="map"){
        //$("#filters button i").hide();

        $('#filters').css('display', 'inline-block');
        if($('#filters ul.kpis_select').length)
            $("#filters button").hide();
        else
            $("#filters button i").hide();
        $('.controls_rank').hide();       


        createMap();
    }
    else if(obj=="tree"){
        buildTreemapKpi();
        $('#filters').css('display', 'inline-block');
        if($('#filters ul.kpis_select').length)
            $("#filters button").hide();
        else
            $("#filters button i").hide();
        $('.controls_rank').hide();       
    }
    else if(obj == "line" || obj == "column" || obj == "area" || obj == "combo" || obj == "combo_new"){
        $('#filters').css('display', 'inline-block');
        if ($('#filters ul.kpis_select').length)
            $("#filters button").hide();
        else
            $("#filters button i").hide();
        $('.controls_rank').hide(); 
        buildChart(obj);
		
    }
}
$("#show_chart").click(function(){
    //validation, we need to have companies and kpis
    var card_data = get_card_data(); 
    obj = card_data['type_chart'];
    $(this).parent().find('.error').remove();
    if($('select[name="companies"] option').length && ($('select[name="kpis"] option').length || obj == 'combo')){
        $('.card_description').find('.error').remove();
		
        explore_rank(true);
		
        calc_arr();
    }
    else
    {
        if($('select[name="companies"] option').length)
            $('<span class="error" id="error_data">You need to add at least one KPI to generate a card</span>').insertBefore($(this));
        else
            $('<span class="error" id="error_data">You need to add at least one Company to generate a card</span>').insertBefore($(this));
    }
});

$('body').on('change', '#reporting_period_edit', function(){
    $(this).parent().find('.error').remove();
    if($('select[name="companies"] option').length && $('select[name="kpis"] option').length){
        $('.card_description').find('.error').remove();
        explore_rank(true);
    }
    else
    {
        if($('select[name="companies"] option').length)
            $('<span class="error" id="error_data">You need to add at least one KPI to generate a card</span>').insertBefore($(this));
        else
            $('<span class="error" id="error_data">You need to add at least one Company to generate a card</span>').insertBefore($(this));
    }
});

$(".add_card .square > div").click(function(){   
    $('#company_selector').remove();
    $('#kpi_options').remove();
    var obj = $(this).parent().attr("for");
    $('.add_card .square').removeClass("active");
    $('.add_card .square[for="'+obj+'"]').addClass("active");

    if(obj == 'rank')
        $('#explore_rank .grid').css('width', $('#explore_rank').attr('data-width'));
    if(obj == 'explore')
        $('#explore_rank .grid').css('width', '100%');

    get_active_chart();
});

$('body').on('click', '#companies_animation_container #filters button', function(e) { 
    $('#period_buttons').remove(); 
    $(this).siblings('button.active').find('i').removeClass("fa-sort-amount-asc");
    $(this).siblings('button.active').find('i').removeClass("fa-sort-amount-desc");
    $(this).siblings('button.active').removeClass("active");
    $(this).addClass("active");
    if($(this).find('i').hasClass("fa-sort-amount-desc"))
        $(this).find('i').removeClass("fa-sort-amount-desc").addClass("fa-sort-amount-asc");
    else
        $(this).find('i').removeClass("fa-sort-amount-asc").addClass("fa-sort-amount-desc");
    var card_data = get_card_data();
    if(card_data['type_chart'] == "explore" || card_data['type_chart'] == "rank" )
        get_companies();
    else if(card_data['type_chart'] == "map" )
        createMap(true);
    else if(card_data['type_chart'] == "tree" )
        buildTreemapKpi();
    else if(card_data['type_chart'] == "line" || card_data['type_chart'] == "column" || card_data['type_chart'] == "area" || card_data['type_chart'] == "combo" || card_data['type_chart'] == "combo_new")
        buildChart(card_data['type_chart']);
		
    init_cell();
});
$('body').on('change', '#companies_animation_container #filters ul.kpis_select input[name="kpis_select"]', function(e) { 
	if(checkurls()){
    $('#period_buttons').remove(); 
    $('#reporting_period_edit').show();
    //$(this).find('option').removeClass('active');
    $('#reporting_period_edit').show();
    var kpi = $(this).val();
    //$(this).find('option[value="'+kpi+'"]').addClass('active');
    var card_data = get_card_data();
    if(card_data['type_chart'] == "explore" || card_data['type_chart'] == "rank" )
        get_companies();
    else if(card_data['type_chart'] == "map" )
        createMap(true);
    else if(card_data['type_chart'] == "tree" )
        buildTreemapKpi();
    else if(card_data['type_chart'] == "line" || card_data['type_chart'] == "column" || card_data['type_chart'] == "area" || card_data['type_chart'] == "combo" || card_data['type_chart'] == "combo_new")
        buildChart(card_data['type_chart']);
		//alert(3);
        //get_active_chart();
        //explore_rank(true);
    init_cell();
	}
});

function reloadChart(e) {
    var kpi = $('#active_company').val();
    //$(this).find('option[value="'+kpi+'"]').addClass('active');
    var card_data = get_card_data();
    if(card_data['type_chart'] == "explore" || card_data['type_chart'] == "rank" )
        get_companies();
    else if(card_data['type_chart'] == "map" )
        createMap(true);
    else if(card_data['type_chart'] == "tree" )
        buildTreemapKpi();
    else if(card_data['type_chart'] == "line" || card_data['type_chart'] == "column" || card_data['type_chart'] == "area" || card_data['type_chart'] == "combo" || card_data['type_chart'] == "combo_new")
        buildChart(card_data['type_chart']);
		
    //get_active_chart();
    //explore_rank(true);
    init_cell();
};

$('body').on('click', '#ul_control_sort li', function(e) {
    var kpi = $(this).attr("name");
    var order = "asc";
    var txt = $(this).html();
    if (txt.toLowerCase().indexOf('<i class="fa fa-level-up"></i>') >= 0){
        order = "desc";
    }
    set_filters(kpi, order);
    get_companies();
});

function explore_rank(reporting_period_changed) {
	
	
    var tmp = get_card_data();
    //$('#card_core #companies_animation_container').css('background', 'url("'+site_url+'/assets/img/AjaxLoader.gif") no-repeat center center  rgba(0, 0, 0, 0.15)');
    //*
    
	$('.add_card').block({
        message: '<img src="'+site_url+'/assets/img/AjaxLoader.gif" />'
    }); 
    
    /*if(tmp.type == 'line' || tmp.type == 'column' || tmp.type == 'area' || tmp.type == 'combo')*/


    $.ajax( {
        url :  site_url + 'explore/explore_rank',
        data: tmp,
        type:"POST",
        success : function(data) {
            //$('#kpis_tree').css('background', 'none');
            $('#card_core .grid').isotope('destroy');
            $('#card_core #companies_animation_container').replaceWith(data);
			//console.log(data);
            $('#card_core #companies_animation_container .grid').isotope({
                itemSelector: '.element-item'
            });
            
            var obj = ($('.control.explore.rank').attr('islist') == 'true')? 'rank':'explore';
            if(obj == 'rank')
                $('#explore_rank .grid').css('width', $('#explore_rank').attr('data-width'));
            if(obj == 'explore')
                $('#explore_rank .grid').css('width', '100%');
            if(obj == 'map'){
                $('.control.map').show();
                $('#usa_map').show();
            }

            //fill card infromation coming from the API
            $('#sources').attr('value', $('#source').attr('datasources'));
            
            //$('select.kpis_select').select2(); 
            //$('#reporting_period_edit select').select2();
            var card_data = get_card_data();
                if(!(card_data['type_chart'] == "line" || card_data['type_chart'] == "column" || card_data['type_chart'] == "area" || card_data['type_chart'] == "combo" || card_data['type_chart'] == "combo_new"))
					
                    get_active_chart();
					

            $('#companies_animation_container').css("visibility", "visible");
			
            if (reporting_period_changed) {
				
                if (card_data['type_chart'] == "line" || card_data['type_chart'] == "column" || card_data['type_chart'] == "area" || card_data['type_chart'] == "combo" || card_data['type_chart'] == "combo_new") {
                    $('#filters').css('display', 'inline-block');
                    if ($('#filters ul.kpis_select').length)
                        $("#filters button").hide();
                    else
                        $("#filters button i").hide();
                    $('.controls_rank').hide();
					
                    buildChart(card_data['type_chart']);
					
                }
                else {

                    setTimeout(function() {
                    //*
                        get_companies(true);
                        var card_data = get_card_data();
                        $('#reporting_period_edit').show();
                        if (card_data['type_chart'] == "explore" || card_data['type_chart'] == "rank") {
                            $("#filters button i").show();
                            get_companies();
                        }
                        else if (card_data['type_chart'] == "tree") {
                            //buildTreemapKpi();
                            $('#filters').css('display', 'inline-block');
                            if ($('#filters ul.kpis_select').length)
                                $("#filters button").hide();
                            else
                                $("#filters button i").hide();
                            $('.controls_rank').hide();
                        }
                        else if (card_data['type_chart'] == "map") {
                            $('#filters').css('display', 'inline-block');
                            if ($('#filters ul.kpis_select ').length)
                                $("#filters button").hide();
                            else
                                $("#filters button i").hide();
                            $('.controls_rank').hide();
                            createMap();
                        }
                        else 
                        arr = calc_arr();
                        $('#companies_animation_container').css("visibility", "visible");
                        //*=/
                        if(tmp['type']=="explore" && tmp['order']=='desc'){
                            $('.kpis_select_button').trigger("click");
                        }
                    }, 1000);
                }
            }
			
            $('body').tooltip(
                {
                    'selector': '.tooltip-toggle',
                    'container': 'body'
                }
            );

			$('.add_card').unblock();
        }
    });
    
    //*/
}

function get_card_data(){
	
    var companies = ""; 
    $('select[name="companies"] option').each(function(i){ 
        companies += $(this).val()+',';
    });

    var kpis = "", kpi = ''; 
    //in edit mode, we always get kpis from the list on the right
    if($('.add_kpi select[name="kpis"]').length) {
        $('select[name="kpis"] option').each(function(i){
            kpis += $(this).val()+',';
        });
    }
    else if($('#filters ul.kpis_select').length) {
        $('ul.kpis_select a').each(function(i){
            kpis += $(this).attr('data')+',';
        });
    }

    if($("#companies_animation_container #filters ul.kpis_select").length)
    {
        //active kpi
        var kpi = $('#companies_animation_container #filters ul.kpis_select input[name="kpis_select"]').val(); 
        if(kpis.indexOf(kpi) == -1){
            kpi = '';
            var kpis_array = kpis.split(',');
            kpi = kpis_array[0];
        }
        //$(this).find('option').removeClass('active');
        //$(this).find('option[value="'+kpi+'"]').addClass('active');
    }
    /*else
        var kpi = $("#companies_animation_container #filters button.active").attr("data-kpi-id"); 
    if(!kpi)
        kpi = $('#companies_animation_container #filters ul.kpis_select input[name="kpis_select"]').val(); */

    var order = $("#companies_animation_container #filters button.active i").hasClass("fa-sort-amount-asc"); 
    order = (order?"asc":"desc");

    var card_id = $("#card_id").val();
    
    var name = $("#name").val();
    //var sources = $("#sources").val();
    var author = $("#author").val();
    var description = $("#description").val();
    var type = $(".add_card .square.active").attr("for");

    if(type != 'combo'){
        $('#reporting_period_edit').show();
        $('#view_card_reporting_period').show();
    }
    if(type == 'combo_new') {
        line_kpis = $('input#line_kpis').val();
        column_kpis = $('input#column_kpis').val();
    }
    else {
        line_kpis = '';
        column_kpis = '';
    }
	 var sid = $('input#sid').val();
    var period = $('ul#reporting_period input[name="reporting_period"]').val();
    var quarterly_toggle = 0;
    if($('#disable_toggle').is(':checked'))
        quarterly_toggle = 1;
    var active_company = $('input#active_company').val();
    if(!active_company)
        active_company = companies.substr(0, companies.indexOf(','));
    //var period = $('select[name="period"]').val();
    var tmp = {
        id :card_id,
        companies: companies,
        kpis: kpis,
        kpi: kpi,
        order: order,
        card_id: card_id,
        name: name,
        sid : sid,
        //sources: sources,
        user: author,
        description: description,
        type_chart:type,
        type:type,
        period: period,
        quarterly_toggle: quarterly_toggle,
        active_company: active_company,
        line_kpis: line_kpis,
        column_kpis: column_kpis
		
    };
    return tmp;
}

$(document).ready(function() {
    var $sfield = $('input.autocomplete').autocomplete({
        source: function(request, response){
            var obj =    this.element.attr("data-autocomplete");
            var action = this.element.attr("data-action");
            var label = this.element.attr("data-label");
            var value = this.element.attr("data-value");
            var url = action +'/'+ obj;
            $.post(url, {
                data:request.term
            }, function(data){
                response($.map(data, function(results) {
                    return {
                        label:results[label],
                        value: results[value],
                        descr: results['description']
                    };
                }));
            }, "json");  
        },
        focus: function(event, ui) {
            event.preventDefault();
            $(this).val(ui.item.label);
        },
        select: function(event, ui) {
            event.preventDefault();
            var hidden_name = $(this).attr("for");
            var hidden = $('[type=hidden][for="'+hidden_name+'"]');
            $(this).val(ui.item.label);
            $(this).attr('data-desc', ui.item.descr);
            $(hidden).val(ui.item.value);
        },
        minLength: 2,
        autofocus: false
    });
    $(".clear_all").click(function(){
        var _for = $(this).attr("for");
        $('select[clear="'+_for+'"] option').remove();
        $('input[clear="'+_for+'"]').val("");
    });
    $(".add_to_select").click(function(){
        var _for = $(this).attr("for");
        var val  = $(this).siblings('[type="hidden"][for="'+_for+'"]').val();
        var txt  = $(this).siblings('[data-autocomplete][for="'+_for+'"]').val();
        var desc  = $(this).siblings('[data-autocomplete][for="'+_for+'"]').attr('data-desc');
        
        var option = '<option value="'+val+'" data-desc="'+desc+'" >'+txt+'</option>';
        var options = $('select[name="'+_for+'"] option[value="'+val+'"]').length;
        if(options == 0 && val)
            $('select[name="'+_for+'"]').append(option);
        //else if(options == 1)
         //   $('select[name="'+_for+'"] option[value="'+val+'"]').attr("selected","selected");
        
        $(this).siblings('[type="hidden"][for="'+_for+'"]').val("");
        $(this).siblings('[data-autocomplete][for="'+_for+'"]').val("");
        $(this).siblings('[data-autocomplete][for="'+_for+'"]').attr("data-desc", "");
    });

    show_list('companies');
    show_list('kpis');
    
    //fill card infromation coming from the API
    $('#sources').html($('#source').attr('datasources'));
    
    
    $("body").on("change",".s_list", function(){
        var this_obj = $(this);
        var _for   = $(this_obj).attr("for");
        var select = $(this_obj).parent().parent().find('select[name="'+_for+'"]');
        var _val   = $(this_obj).val();
        $(select).parent().css('background', 'url("'+site_url+'/assets/img/AjaxLoader.gif") no-repeat center center');
        if(_val != ""){
            $.ajax( {
                url :  site_url + 'card/get_'+_for+'_list/'+_val,
                type:"GET",
                dataType:"json",
                success : function(data) {
                    $(select).find('option').remove();
                    for(var i=data.length-1;i>= 0;i--){
                        var _id_name  = $(select).attr("data-id");
                        var _txt_name = $(select).attr("data-txt");                        
                        var id = data[i][_id_name];
                        var name = data[i][_txt_name];
                        var desc = data[i]['description'];
                        var option = '<option value="'+id+'" data-desc="'+desc+'">'+name+'</option>';
                        $(select).append(option);
                    }
                    $(select).parent().css('background', 'none');
					
					var _user_id=$('#user_id').val();
					var _user = $(this_obj).find('option[value="'+_val+'"]').attr('user');
					if(_user == _user_id){
						$('#delete_list_'+_for).attr('list_id',_val);
						$('#delete_list_'+_for).attr('list_for',_for);
						$('#delete_list_'+_for).show();
					}else{
						$('#delete_list_'+_for).hide();
					}
                }
            });
        }
    });
    
	$(".delete_list").click(function(){
		$('#delete_listModal').modal('show');
		$('#delete_listModal').find('.ajax_submit').attr('data-list_id', $(this).attr('list_id'));
		$('#delete_listModal').find('.ajax_submit').attr('data-list_for', $(this).attr('list_for'));
	});	
	
	$('body').on('click','#delete_listModal .ajax_submit', function(){
		var list_id = $(this).attr("data-list_id");
		var _obj = $(this).attr("data-list_for");

		var _data = {
			'list_id': list_id
		};

		var modal = $(this).parents('.modal');
		
		delete_list(list_id, modal, _obj, _data);
	});
	
    $(".save_list").click(function(){
        
        $('.name_container .error').remove();
        var target = $(this).attr('for');
        var parent = $(this).parent();
        if($.trim(parent.find('input[name="list_name"]').val()) && parent.find('select[name="'+target+'"] option').length )
        {
            save_list(this);
            $('#delete_list_kpis').hide();
            $('#delete_list_companies').hide();
        }
        else
        {
            if($.trim(parent.find('input[name="list_name"]').val()))
                $('<span class="error">You need to add an element to your list</span>').prependTo($(this).prev().prev());
            else
                $('<span class="error">You need to add a name for your list</span>').prependTo($(this).prev().prev());
        }
    });
    $("#save_card").click(function(){
        //validation, we need to have companies and kpis
        var card_data = get_card_data(); 
        obj = card_data['type_chart'];
        $(this).parent().find('.error').remove();
              
        if($('select[name="companies"] option').length && ( $('select[name="kpis"] option').length || obj == 'combo') && $('#name').val()!=''){
          save_card();
        }
        else
        {
            if(! $('select[name="companies"] option').length) {
              $('<span class="error">You need to add at least one Company to generate a card</span>').insertBefore($(this));
            }
            else if($('#name').val() == '') {
              $('<span class="error">You need to add a title for the card from the card description link bellow</span>').insertBefore($(this));
            }
            else if ( ! $('select[name="kpis"] option').length){
              $('<span class="error">You need to add at least one KPI to generate a card</span>').insertBefore($(this));
            }
            
        }
    });
    
    $("#save_desc").click(function(){
        if($('#name').val()!=''){
            $('.card_description').find('.error').remove();
        }

        $(this).parents('.modal.fade').modal('hide');
    });
    
    /*
    $('#source .list_view').hover(function(event){
        if(bar_mode){

            $(this).find('.scores .progress').css('opacity','0');
            $(this).find('.scores .progress-bar').css('opacity','0');
            $(this).find('.scores .num').css('opacity','1');
        }

    }, function(event){
        if(bar_mode){
    
            $(this).find('.scores .progress').css('opacity','1');
            $(this).find('.scores .progress-bar').css('opacity','1');
            $(this).find('.scores .num').css('opacity','0');
        }
    });
    //*/
    
}); 

function save_card(){
    var tmp = get_card_data();
    var action = "add";
    if(window.location.href.indexOf("edit") > -1) {
         action = "edit";
    }
    //*
    $.blockUI({
        message: '<h1><img src="'+site_url+'/assets/img/AjaxLoader.gif" /></h1>'
    }); 
    $.ajax({
        url : site_url + 'card/submit',
        data: tmp,
        type:"POST",
        success : function(data){
            if (data != "ko"){
                if(action=="edit"){
                    $("#messages").html('<div class="alert alert-success">Your Changes have been Successfully Submitted</div>');
                    $("#messages").show();
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                }else{
                    window.location.href = site_url + "card/edit/"+data+"/succes";
                }   
            }
        }
    });
    //*/
}

function show_list(_for){
    var select = $('.s_list[for="'+_for+'"]');//this_obj.siblings('select');
    
    var loadind_div = select.parent().parent().find('select[name="'+_for+'"]').parent();
    $(loadind_div).css('background', 'url("'+site_url+'/assets/img/AjaxLoader.gif") no-repeat center center');
    $.ajax( {
        url :  site_url + 'card/get_list_'+_for,
        type:"POST",
        dataType:"json",
        success : function(data) {

            $(select).find('option').remove();
            var option = '<option value="" ></option>';
            $(select).append(option);
            for(var i=data.length-1;i>= 0;i--){
                var id = data[i]['id'];
                var name = data[i]['name'];
				var user = data[i]['user'];
				var _public = (data[i]['public']!="0")?'public_list':'';
				
                var option = '<option class="'+_public+'" user="'+user+'" value="'+id+'">'+name+'</option>';
                $(select).append(option);
            }
            
            $(select).show();
            $(select).select2({
                placeholder: "Select List"
            });
    
        }
    });

}

if (typeof String.prototype.startsWith != 'function') {
    // see below for better implementation!
    String.prototype.startsWith = function (str){
        return this.indexOf(str) == 0;
    };
}

function update_card_data(reporting_period_changed)
{
    update_data_points();
    
    var tmp = get_card_data();
     $('.add_card').block({
		message: ' '
	 }); 
    var left = '0';
    if($('.add_card .title a#embed_logo').length)
        left = '100px';
	$('.add_card div.title').append('<img id="image_wait" src="'+site_url+'/assets/img/AjaxLoader2.gif" style="display: block;padding: 14px;position: absolute;left: '+left+';top: 0; z-index: 1000;" />')
    
    $.ajax( {
        url :  site_url + 'card/update_data',
        data: tmp,
        type:"POST",
        success : function(data) {
    
            //console.log(data);
            $('#card_core .grid').isotope('destroy');
            $('#card_core #companies_animation_container').replaceWith(data);
            $('#card_core #companies_animation_container .grid').isotope({
                itemSelector: '.element-item'
            });
            
            //fill card infromation coming from the API
            $('#sources').attr('value', $('#source').attr('datasources'));
            
            var obj = ($('.control.explore.rank').attr('islist') == 'true')? 'rank':'explore';
            if(obj == 'rank')
                $('#explore_rank .grid').css('width', $('#explore_rank').attr('data-width'));
            if(obj == 'explore')
                $('#explore_rank .grid').css('width', '100%');
            
            //$('select.kpis_select').select2();  
            //$('#view_card_reporting_period select').select2();  
            get_active_chart();

            if (reporting_period_changed) {
                $('#companies_animation_container').css("visibility", "visible");
                setTimeout(function() {
                    //*
                    get_companies(true);
                    var card_data = get_card_data();
                    $('#reporting_period_edit').show();
                    if (card_data['type_chart'] == "explore" || card_data['type_chart'] == "rank") {
                        $("#filters button i").show();
                        get_companies();
                    }
                    else if (card_data['type_chart'] == "tree") {
                        //buildTreemapKpi();
                        $('#filters').css('display', 'inline-block');
                        if ($('#filters ul.kpis_select').length)
                            $("#filters button").hide();
                        else
                            $("#filters button i").hide();
                        $('.controls_rank').hide();
                    }
                    else if (card_data['type_chart'] == "map") {
                        $('#filters').css('display', 'inline-block');
                        if ($('#filters ul.kpis_select ').length)
                            $("#filters button").hide();
                        else
                            $("#filters button i").hide();
                        $('.controls_rank').hide();
                        createMap();
                    }
                    else if (card_data['type_chart'] == "line" || card_data['type_chart'] == "column" || card_data['type_chart'] == "area" || card_data['type_chart'] == "combo") {
                        $('#filters').css('display', 'inline-block');
                        if ($('#filters ul.kpis_select').length)
                            $("#filters button").hide();
                        else
                            $("#filters button i").hide();
                        $('.controls_rank').hide();
                        buildChart(card_data['type_chart']);
						
                    }
                    arr = calc_arr();
                    $('#companies_animation_container').css("visibility", "visible");
                    //*/
                }, 1000);
            }
			setTimeout(function() {
				$('#image_wait').remove();
			}, 100);
			$('.add_card').unblock()
			
        }
    });
}

function update_data_points()
{
    var tmp = get_card_data();

    $.ajax( {
        url :  site_url + 'card/data_points',
        data: tmp,
        type:"POST",
        success : function(data) {
            //console.log(data);
            //$('#card_core #data_points_div').replaceWith(data);
            $('#card_core #data_points_div').html(data);
			$('#card_cored #data_points_div').html(data);
        }
    });
}

function animation_num_to_bar(arr){

    if($("#source .list_view .scores .num").css('opacity') != 0){
     
        $("#source .list_view .scores .progress").animate({
            opacity: "1"
        }, 800);
        $("#source .list_view .scores .progress-bar").animate({
            opacity: "1"
        }, 800);
        $("#source .list_view .scores .num").animate({
            opacity: "0"
        }, 800);

        $('#ul_control_sort li').each(function(i){ 
            var _current_kpi = $(this).attr('name');
            $('#source .list_view .scores .num[data-'+_current_kpi+']').each(function(j){ 

                $(this).siblings('.progress').find('.progress-bar').css('width',arr[i][j]+'%');
            });
        });
        bar_mode = true;
    }
}

function animation_bar_to_num(arr){
    
    if($("#source .list_view .scores .progress").css('opacity') != 0){
        $("#source .list_view .scores .progress-bar").css('width','0%');
    
        $("#source .list_view .scores .progress").animate({
            opacity: "0"
        }, 800);
        $("#source .list_view .scores .progress-bar").animate({
            opacity: "0"
        }, 800);
        $("#source .list_view .scores .num").animate({
            opacity: "1"
        }, 800);
        bar_mode = false;
    }
}

function percentify(arr){
    var n = arr.length;

    for(var i=0; i<n; i++){
        if(arr[i]==''){
            arr[i]=0;
        }
            arr[i] = Math.abs(arr[i]);
    }

    var max = Math.max.apply(Math, arr);

    for(var i=0; i<n; i++){
        
        arr[i] = (max !=0)?(arr[i]/max)*100:0;
        arr[i] = ((arr[i]<2)&&(arr[i]>0))?2:arr[i];
    }
    
    return arr;
}

function clearAllTimers(){
    clearTimeout(AnimationNumBarTimerId);
    clearTimeout(AnimationNumBarTimerId);
}

$(".use_list").click(function(){
    $('.name_container .error').remove();
    var target = $(this).attr('for');
    var parent = $(this).parent().parent();
    if(parent.find('select[name="'+target+'"] option').length )
    {
        use_list(this);
    }
    else
    {
      $('<span class="error">You need to add an element to your list</span>').prependTo($(this).parent().prev().prev().prev());
    }
});

function use_list(this_btn) {
  var this_obj = $(this_btn);
  var _for   = $(this_obj).attr("for");
  var _obj   = $(this_obj).attr("obj");

  $('select[name="'+_obj+'"] option').remove();
  $('select[name="'+_for+'"] option').each(function(){ 
    $('select[name="'+_obj+'"]').append($(this));
  });

  $(this_obj).parents('.modal.fade').modal('hide');
}

function save_list(this_btn) {
  var this_obj = $(this_btn);
  var _for   = $(this_obj).attr("for");
  var _obj   = $(this_obj).attr("obj");
  var _name  = $(this_obj).prev().prev().find('[name="list_name"]').val();
  if($('input#'+_obj+'_public_list').is(':checked'))
    var _public  = 1;
  else
    var _public  = 0;
  var _objs = ""; 
  

  list_id = list_name_already_exists(_obj, _name);
  
  if(list_id){
    $('#replace_listModal').modal('show');
    $('#replace_listModal').find('.ajax_submit').attr('data-list', list_id);
    $('#replace_listModal').find('.ajax_submit').attr('_for', _for);
    $('#replace_listModal').find('.ajax_submit').attr('_obj', _obj);
    $('#replace_listModal').find('.ajax_submit').attr('_public', _public);
  }
  else{
    $('select[name="'+_obj+'"] option').remove();
    $('select[name="'+_for+'"] option').each(function(){ 
      $('select[name="'+_obj+'"]').append($(this));
      _objs += $(this).val() + ','; 
    });

    var _data = { 
      'name': _name, 
      'public': _public,
      'objs': JSON.stringify(_objs)
    };
      
    $.ajax({
      url :  site_url + 'card/save_list_'+_obj,
      type:"POST",
      data: _data,
      success : function(data) {
        var message;
        if($.trim(data) != "ko")
          message = '<div class="alert alert-success">Your Changes have been Successfully Submitted.</div>';
        else
          message = '<div class="alert alert-error">Your changes have not been saved.</div>';
            
        add_to_existing_lists($.trim(data), _name, _obj, _public);
            
        $(this_obj).parents('.modal.fade').modal('hide');
                //$("#messages").html("");
                //$("#messages").append(message);
        //we empty checked checkboxes
        $('.'+_obj+'_list_name input').val('');
        $('#'+_obj+'_tree input:checked').each(function(i){
            $(this).prop('checked', false);
        });
      }
    });
  }
}

function list_name_already_exists(_obj, _name){
    var select = $('.s_list[for="'+_obj+'"]');
    var flag = 0;
    
    $(select).find('option').each(function(i){ 
        
        if(_name.toLowerCase() == $(this).html().toLowerCase()){
            flag =  $(this).val();
        }
    });
        
    return flag;
}

$('body').on('click','#replace_listModal .ajax_submit', function(){
    var list = $(this).attr("data-list");
    var _obj = $(this).attr("_obj");
    var _for = $(this).attr("_for");
    var _public = $(this).attr("_public");
    var this_obj = $('.save_list[obj="'+_obj+'"]');

    var _name  = $(this_obj).prev().prev().find('[name="list_name"]').val();
    var _objs = "";
    
    $('select[name="'+_obj+'"] option').remove();
    $('select[name="'+_for+'"] option').each(function(){ 
      $('select[name="'+_obj+'"]').append($(this));
      _objs += $(this).val() + ','; 
    });

    var _data = {
        'name': _name,
        'public' : _public,
        'objs': JSON.stringify(_objs)
    };

    var modal = $(this).parents('.modal');
    
    replace_list(list, modal, _obj, _data);
});

function replace_list(list, modal, _obj, _data){

    var this_obj = $('.save_list[obj="'+_obj+'"]');

    $.ajax( {
        url :  site_url + 'card/save_list_'+_obj+'/'+list,
        type:"POST",
        data: _data,
        success : function(data) {
            var message;
            if($.trim(data) != "ko")
                message = '<div class="alert alert-success">Your Changes have been Successfully Submitted.</div>';
            else
                message = '<div class="alert alert-error">Your changes have not been saved.</div>';

            $(modal).modal('hide');
            $(this_obj).parents('.modal.fade').modal('hide');
            $("#messages").html("");
            $("#messages").append(message); 
            //reseting fields
            $('.'+_obj+'_list_name input').val('');
            $('#'+_obj+'_tree input:checked').each(function(i){
                $(this).prop('checked', false);
            });
            //make list selected
            $('.s_list[for="'+_obj+'"] option[value="'+list+'"]').attr('selected', 'selected');
            $('.s_list[for="'+_obj+'"]').select2();
        }
    });
}

function delete_list(list, modal, _obj, _data){

            
//*
    $.ajax( {
        url :  site_url + 'card/delete_list_'+_obj+'/'+list,
        type:"POST",
        data: _data,
        success : function(data) {
            var message;
            if($.trim(data) != "ko"){
                var select = $('.s_list[for="'+_obj+'"]');
                var flag_delete = true;

                $('.s_list[for="'+_obj+'"] option').each(function(i){ 
                  if(flag_delete && (list == $(this).val())){
                    $(this).remove();
                    $('.s_list[for="'+_obj+'"]').select2();
                    flag_delete = false;
                  }
                });


                $('select[name="'+_obj+'"]').empty();
                $('#delete_list_'+_obj).hide();
                $(modal).modal('hide');
                message = '<div class="alert alert-success">Your Changes have been Successfully Submitted.</div>';
            }
            else{
                message = '<div class="alert alert-error">Your changes have not been saved.</div>';
            }

            $(modal).modal('hide');
            //$(this_obj).parents('.modal.fade').modal('hide');
            $("#messages").html("");
            $("#messages").append(message);
        }
    });
//*/
}

function add_to_existing_lists(id, name, _obj, _public) {
  _public = (_public)?"public_list":'';
  var _user_id = $("#user_id").val();
  var select = $('.s_list[for="'+_obj+'"]');
  var flag_insert = true;
  
  if($('.s_list[for="'+_obj+'"] option').length > 1) {
    $('.s_list[for="'+_obj+'"] option').each(function(i){ 
      if(flag_insert && (name.toLowerCase() < $(this).html().toLowerCase())){
        var option = '<option class="'+_public+'" user="'+_user_id+'" value="'+id+'" selected="selected">'+name+'</option>';
        $(option).insertBefore($(this));
        $('.s_list[for="'+_obj+'"]').select2();
        flag_insert = false;
      }
    });
  }
  else {
    var option = '<option class="'+_public+'" user="'+_user_id+'" value="'+id+'" selected="selected">'+name+'</option>';
    $(option).appendTo(select);
    select.select2();
    flag_insert = false;
  }
  if(flag_insert){
    var option = '<option class="'+_public+'" user="'+_user_id+'" value="'+id+'" selected="selected">'+name+'</option>';
    $(option).appendTo(select);
    select.select2();
  }
  
  $('#delete_list_'+_obj).attr('list_id',id);
    $('#delete_list_'+_obj).attr('list_for',_obj);
    $('#delete_list_'+_obj).show();
  
}

function cut_string(s, n) {
    if (s)
  return (s.length > n) ? (s.substr(0,(n-3))+'...') : s;
    else
        return '';
}
function checkurls(){
    var active_a = $('ul.card_des li a.company.active');
    if(active_a.parent().is( "strong" ) )
        active_a.unwrap();
    $('ul.card_des li a.company').removeClass('active');
    var message;
    var myRegExp =/^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
    var urlToValidate = active_a.attr('href');
    if (myRegExp.test(urlToValidate)){
        window.open(urlToValidate);
        return false;
    }else{
		return true;
	}
}