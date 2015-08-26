active_chart = '';
years = [];
quarters = [];
active_segments = 'year';
segments_switch = 0;
plotbands = [{ // Dangerous
            from: -100,
            to: 1.8,
            color: 'rgba(0,0, 0, 0.7)',
            label: {
              text: 'Dangerous',
              style: {
                color: '#606060'
              }
            }
          }, { // On Alert
            from: 1.8,
            to: 3,
            color: 'rgba(0,0,0, 0.5)',
            label: {
              text: 'On Alert',
              style: {
                color: '#606060'
              }
            }
          }, { // Safe
            from: 3,
            to: 100,
            color: 'rgba(0,0,0,0.3)',
            label: {
              text: 'Safe',
              style: {
                color: '#606060'
              }
            }
          }];
$.ajax({
  url :  site_url + 'card/get_all_periods',
  type:"GET",
  dataType: 'json',
  error : function(){
    years = ['2009', '2010', '2011', '2012', '2013'];
    quarters = ['2009Q1', '2009Q2', '2009Q3', '2009Q4',
                '2010Q1', '2010Q2', '2010Q3', '2010Q4',
                '2011Q1', '2011Q2', '2011Q3', '2011Q4',
                '2012Q1', '2012Q2', '2012Q3', '2012Q4',
                '2013Q1', '2013Q2', '2013Q3', '2013Q4',
                '2014Q1', '2014Q2'];
  },
  success : function(data) {
    for(var i = 0; i < data.length; i++)
    {
      if(data[i].indexOf('Q') == -1)
        years.push(data[i]);
      else
        quarters.push(data[i]);
    }
  }
});

$(function() {
    $('body').on('click', 'button#year', function(){
      $(this).addClass('disabled');
      $('button#quarter').removeClass('disabled');
      active_segments = 'year';
      buildChart(active_chart);
    });
    $('body').on('click', 'button#quarter', function(){
      $(this).addClass('disabled');
      $('button#year').removeClass('disabled');
      active_segments = 'quarter';
      buildChart(active_chart);
    });
    $('#layout-condensed-toggle').click(function(){
      $(window).resize();
    });
    $('body').on('change', 'input#disable_toggle', function(){
      if($('input#disable_toggle').is(':checked')){
        $('#period_buttons').addClass('disabled');
        $('input#hide_toggle').val('1');
        if($('input.ios').prop('checked')) {
          active_segments = 'year';
          segments_switch.toggle();
          buildChart(active_chart);
        }
      }
      else{
        $('#period_buttons').removeClass('disabled');
        $('input#hide_toggle').val('0');
      }
    });

    $('body').on('click', 'ul.companies_list a', function(e){
		//alert('there');
        e.preventDefault();
		
        var active_a = $('ul.companies_list li a.company.active');
        if(active_a.parent().is( "strong" ) ) 
            active_a.unwrap();
        $('ul.companies_list li a.company').removeClass('active');

        var message;
        var myRegExp =/^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
        var urlToValidate = $(this).attr('href');
        if (myRegExp.test(urlToValidate)){
            window.open($(this).attr('href'));
            return false;
        }

        $(this).addClass('active').wrap('<strong></strong>');
        $(this).focus();
        $('#company_text').text(cut_string($(this).text(), 20));
        if($('input#active_company').val()!=$(this).attr('data'))
        {
            $('input#active_company').val($(this).attr('data'));
            buildChart(active_chart);
        }
    });
    
    $('body').on('click', '#kpi_options ul.dropdown-menu', function(e){
        e.stopPropagation();
    });
    $('body').on('click change', '#kpi_options ul.dropdown-menu input[type="checkbox"]', function(e){
      e.stopPropagation();
      if($(this).is(':checked')) {
        var type = $(this).val();
        if(type == 'column')
          var other_checkbox = $(this).parent().next().find('input[type="checkbox"]');
        if(type == 'line')
          var other_checkbox = $(this).parent().prev().find('input[type="checkbox"]');
        if(other_checkbox.is(':checked'))
          other_checkbox.prop('checked', false);
      }
      update_kpis_types();
	});
});

function update_kpis_types() {
  var line_kpis = '';
  var column_kpis = '';
  $('#kpi_options table tbody tr').each(function(i) {
    var checked_elemt = $(this).find('input:checked');
    if(checked_elemt.val()) {
      var type = checked_elemt.val();
      var kpi = checked_elemt.attr('name').replace('option_', '');
      if(type == 'line')
        line_kpis += kpi+',';
      if(type == 'column')
        column_kpis += kpi+',';
    }
  });
  //alert(line_kpis+ ' ---- ' + column_kpis);
  $('input#line_kpis').val(line_kpis);
  $('input#column_kpis').val(column_kpis);
}

function update_kpi_options_checkboxes() {
  $('#kpi_options table input[type="checkbox"]').prop('checked', false);
  var line_kpis = $('input#line_kpis').val();
  var column_kpis = $('input#column_kpis').val();

  //we already have the inputs filled
  if(line_kpis != '' || column_kpis != '') {
    var line_kpis_array = line_kpis.split(',');
    var column_kpis_array = column_kpis.split(',');
    for(var i = 0; i < line_kpis_array.length; i++) {
      if(line_kpis_array[i])
        $('#kpi_options table input[value="line"][name="option_'+line_kpis_array[i]+'"]').prop('checked', true);
    }
    for(var i = 0; i < column_kpis_array.length; i++) {
      if(column_kpis_array[i])
        $('#kpi_options table input[value="column"][name="option_'+column_kpis_array[i]+'"]').prop('checked', true);
    }
  }
  //inputs are empty - we fill them
  else {
    var n = $('#kpi_options table tbody tr').length, i = 0;
    $('#kpi_options table tbody tr').each(function(i) {
      if(i < n/2) 
        $(this).find('input[value="column"]').prop('checked', true);
      else
        $(this).find('input[value="line"]').prop('checked', true);
    });
    update_kpis_types();
  }
}

function get_kpi_type(kpi){
  var line_kpis = $('input#line_kpis').val();
  var column_kpis = $('input#column_kpis').val();
  var r = false;
  if(line_kpis.indexOf(kpi) != -1)
    r = 'line';
  if(column_kpis.indexOf(kpi) != -1)
    r = 'column';
  return r;
}

function buildChart(type){
  segments = active_segments;
  $('#company_selector').remove();
  $('.companies').remove();
  $('#kpi_options').remove();
  
  if(type == 'column'){
    active_chart = 'column';
    var card_data = get_card_data();
    $('.add_card').block({ message: null }); 
    if(segments != 'quarter')
      segments = 'year';
    $.ajax( {
        url :  site_url + 'card/get_data_json_all_periods/'+segments,
        data: card_data,
        type:"GET",
        dataType: 'json',
        error : function(){
            $('.add_card').unblock();
        },
        success : function(data) {
				/////////////////// Get details /////////////////////
			 
          $('.add_card').unblock();
          $('#reporting_period_edit').hide();
          $('#view_card_reporting_period').hide();

          if($('#period_buttons').length == 0) {
            var checked = '';
            if(active_segments == 'quarter')
              checked = ' checked="checked"';

            //$('<div id="period_buttons"><button class="btn btn-success btn-cons" type="button" id="year">Annual Data</button> <button class="btn btn-success btn-cons" type="button" id="quarter">Quarterly Data</button></div>').insertAfter($('#filters'));
            $('<div id="period_buttons"><span class="ios_label">Annual</span> <div class="slide-primary"><input type="checkbox" name="switch" class="ios" '+checked+'/></div> <span class="ios_label">Quarterly</span></div>').insertAfter($('#filters'));

            if($('#save_card').length) {
              var checked = '';
              if($('input#hide_toggle').val() == 1){
                checked = 'checked="checked"';
                $('#period_buttons').addClass('disabled');
              }
              $('<div id="disable_toggle_container" class="checkbox check-primary checkbox"><input id="disable_toggle" type="checkbox" class="checkbox" value="0" '+checked+'><label for="disable_toggle">Disable Quarterly Toggle</label></div>').appendTo('#period_buttons');
            }
            else{
              if($('input#hide_toggle').val() == 1)
                $('#period_buttons').hide();
            }

          //$('#period_buttons #year, #period_buttons #quarter').removeClass('disabled');
          //$('#period_buttons #'+segments).addClass('disabled');
            var Switch = require('ios7-switch'), checkbox = document.querySelector('.ios');
            segments_switch = new Switch(checkbox);
            if(active_segments == 'quarter')
              segments_switch.toggle();
            segments_switch.el.addEventListener('click', function(e){
              e.preventDefault();
              segments_switch.toggle();
              if($('input.ios').prop('checked'))
                active_segments = 'quarter';
              else
                active_segments = 'year';
              buildChart(active_chart);
            }, false);
          }
          
          $('.control.chart').css('display', 'block');

          if(segments == 'quarter')
            var periods = quarters;
          else
            var periods = years;
          
          var displayed_data = [];
          for(var i = 0; i < data.length; i++)
          {
            var obj = {};
            obj.name = data[i].company_name;
            obj.data = [];
            for(var j = 0; j < periods.length; j++)
            {
              if(data[i][periods[j]][card_data.kpi])
                obj.data.push(parseFloat(data[i][periods[j]][card_data.kpi]));
              else
                obj.data.push(0);
            }
            displayed_data.push(obj);
          }
			
		  //console.log(displayed_data);
		  //console.log(periods);
		  //console.log(card_data);
          createColumnChart(periods, displayed_data, card_data);
          update_datatable(periods, displayed_data, card_data);
					
			/////////////////////////////////////////////////////
			//console.log(getCardDetails(card_data)); return false;
			
        }
    });
  }

  if(type == 'line'){
    active_chart = 'line';
    var card_data = get_card_data();
    $('.add_card').block({ message: null }); 
    if(segments != 'quarter')
      segments = 'year';
    $.ajax( {
        url :  site_url + 'card/get_data_json_all_periods/'+segments,
        data: card_data,
        type:"GET",
        dataType: 'json',
        error : function(){
            $('.add_card').unblock();
        },
        success : function(data) {
			
          $('.add_card').unblock();
          $('#reporting_period_edit').hide();
          $('#view_card_reporting_period').hide();

          if($('#period_buttons').length == 0) {
            var checked = '';
            if(active_segments == 'quarter')
              checked = ' checked="checked"';

            $('<div id="period_buttons"><span class="ios_label">Annual</span> <div class="slide-primary"><input type="checkbox" name="switch" class="ios" '+checked+'/></div> <span class="ios_label">Quarterly</span></div>').insertAfter($('#filters'));

            if($('#save_card').length) {
              var checked = '';
              if($('input#hide_toggle').val() == 1) {
                checked = 'checked="checked"';
                $('#period_buttons').addClass('disabled');
              }
              $('<div id="disable_toggle_container" class="checkbox check-primary checkbox"><input id="disable_toggle" type="checkbox" class="checkbox" value="0" '+checked+'><label for="disable_toggle">Disable Quarterly Toggle</label></div>').appendTo('#period_buttons');
            }
            else{
            if($('input#hide_toggle').val() == 1)
              $('#period_buttons').hide();
           }

            var Switch = require('ios7-switch'), checkbox = document.querySelector('.ios'); 
            segments_switch = new Switch(checkbox);
            if(active_segments == 'quarter')
              segments_switch.toggle();
            segments_switch.el.addEventListener('click', function(e){
              e.preventDefault();
              segments_switch.toggle();
              if($('input.ios').prop('checked'))
                active_segments = 'quarter';
              else
                active_segments = 'year';
              buildChart(active_chart);
            }, false);
          }
          
          $('.control.chart').css('display', 'block');

          if(segments == 'quarter')
            var periods = quarters;
          else
            var periods = years;
          
          var displayed_data = [];
          for(var i = 0; i < data.length; i++)
          {
            var obj = {};
            obj.name = data[i].company_name;
            obj.data = [];
            for(var j = 0; j < periods.length; j++)
            {
              if(data[i][periods[j]][card_data.kpi])
                obj.data.push(parseFloat(data[i][periods[j]][card_data.kpi]));
              else
                obj.data.push(0);
            }
            displayed_data.push(obj);
          }

          createLineChart(periods, displayed_data, card_data);
          update_datatable(periods, displayed_data, card_data);
        }
    });
  }

  if(type == 'area'){
    active_chart = 'area';
    var card_data = get_card_data();
    $('.add_card').block({ message: null }); 
    if(segments != 'quarter')
      segments = 'year';
    $.ajax( {
        url :  site_url + 'card/get_data_json_all_periods/'+segments,
        data: card_data,
        type:"GET",
        dataType: 'json',
        error : function(){
            $('.add_card').unblock();
        },
        success : function(data) {
          $('.add_card').unblock();
          $('#reporting_period_edit').hide();
          $('#view_card_reporting_period').hide();

          if($('#period_buttons').length == 0) {
            var checked = '';
            if(active_segments == 'quarter')
              checked = ' checked="checked"';

            $('<div id="period_buttons"><span class="ios_label">Annual</span> <div class="slide-primary"><input type="checkbox" name="switch" class="ios" '+checked+'/></div> <span class="ios_label">Quarterly</span></div>').insertAfter($('#filters'));

          if($('#save_card').length) {
              var checked = '';
              if($('input#hide_toggle').val() == 1){
                checked = 'checked="checked"';
                $('#period_buttons').addClass('disabled');
              }
              $('<div id="disable_toggle_container" class="checkbox check-primary checkbox"><input id="disable_toggle" type="checkbox" class="checkbox" value="0" '+checked+'><label for="disable_toggle">Disable Quarterly Toggle</label></div>').appendTo('#period_buttons');
            }
            else{
            if($('input#hide_toggle').val() == 1)
              $('#period_buttons').hide();
           }


            var Switch = require('ios7-switch'), checkbox = document.querySelector('.ios'); 
            segments_switch = new Switch(checkbox);
            if(active_segments == 'quarter')
              segments_switch.toggle();
            segments_switch.el.addEventListener('click', function(e){
              e.preventDefault();
              segments_switch.toggle();
              if($('input.ios').prop('checked'))
                active_segments = 'quarter';
              else
                active_segments = 'year';
              buildChart(active_chart);
            }, false);
          }
          
          $('.control.chart').css('display', 'block');

          if(segments == 'quarter')
            var periods = quarters;
          else
            var periods = years;
          
          var displayed_data = [];
          for(var i = 0; i < data.length; i++)
          {
            var obj = {};
            obj.name = data[i].company_name;
            obj.data = [];
            for(var j = 0; j < periods.length; j++)
            {
              if(data[i][periods[j]][card_data.kpi])
                obj.data.push(parseFloat(data[i][periods[j]][card_data.kpi]));
              else
                obj.data.push(0);
            }
            displayed_data.push(obj);
          }

          createAreaChart(periods, displayed_data, card_data);
          update_datatable(periods, displayed_data, card_data);
        }
    });
  }

  if(type == 'combo'){
    active_chart = 'combo';
    var card_data = get_card_data();
    $('.add_card').block({ message: null }); 
    $.ajax( {
        url :  site_url + 'card/get_symbols_json_by_post',
        data: card_data,
        type:"GET",
        dataType: 'json',
        error : function(){
            $('.add_card').unblock();
        },
        success : function(data) {
          $('.add_card').unblock();
          if(data.length)
          {
            $('#filters').hide();
            $('#reporting_period_edit').hide();
            $('#view_card_reporting_period').hide();

            $('.control.chart').css('display', 'block');
            
            $(function () {
              new TradingView.MediumWidget({
                "container_id": "chart_container",
                "symbols": data,
                "gridLineColor": "#E9E9EA",
                "fontColor": "#83888D",
                "underLineColor": "#F0F0F0",
                "timeAxisBackgroundColor": "#E9EDF2",
                "trendLineColor": "#FF7965",
                "width": "100%",
                "height": "500px"
              });
            });
          }
        }
    });
  }

  if(type == 'combo_new'){
	 
    active_chart = 'combo_new';
    var card_data = get_card_data();
	//console.log(card_data);
    $('.add_card').block({ message: null }); 
    if(segments != 'quarter')
      segments = 'year';
    $.ajax( {
        url :  site_url + 'card/get_data_json_all_periods_combo/'+segments,
        data: card_data,
        type:"GET",
        dataType: 'json',
        error : function(){
            $('.add_card').unblock();
        },
        success : function(data) {
		//$('#reporting_period').remove();
			$('#company_selector').remove();
          $('.add_card').unblock();
          $('#reporting_period_edit').hide();
          $('#view_card_reporting_period').hide();
          $('#filters').hide();

          if($('#period_buttons').length == 0) {
            var checked = '';
            if(active_segments == 'quarter')
              checked = ' checked="checked"';

            $('<div id="period_buttons"><span class="ios_label">Annual</span> <div class="slide-primary"><input type="checkbox" name="switch" class="ios" '+checked+'/></div> <span class="ios_label">Quarterly</span></div>').insertAfter($('#filters'));

            if($('#save_card').length) {
              var checked = '';
              if($('input#hide_toggle').val() == 1){
                checked = 'checked="checked"';
                $('#period_buttons').addClass('disabled');
              }
              $('<div id="disable_toggle_container" class="checkbox check-primary checkbox"><input id="disable_toggle" type="checkbox" class="checkbox" value="0" '+checked+'><label for="disable_toggle">Disable Quarterly Toggle</label></div>').appendTo('#period_buttons');
}
            else{
              if($('input#hide_toggle').val() == 1)
                $('#period_buttons').hide();
            }


            var Switch = require('ios7-switch'), checkbox = document.querySelector('.ios'); 
            segments_switch = new Switch(checkbox);
            if(active_segments == 'quarter')
              segments_switch.toggle();
            segments_switch.el.addEventListener('click', function(e){
              e.preventDefault();
              segments_switch.toggle();
              if($('input.ios').prop('checked'))
                active_segments = 'quarter';
              else
                active_segments = 'year';
              buildChart(active_chart);
            }, false);
          }
          
          $('.control.chart').css('display', 'block');

          if(segments == 'quarter')
            var periods = quarters;
          else
            var periods = years;

          var companies_list = data['companies_list'];
          var list_markup = '';
          var active_company;
          if(companies_list.length > 0) {
            list_markup += '<div class="btn-group companies" id="company_selector"><a class="btn btn-primary dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#" > <span id="company_text"></span> <span class="caret"></span> </a> <ul class="dropdown-menu companies_list">';
            for(var i = 0; i < companies_list.length; i++) {
              var active = (card_data.active_company == companies_list[i].entity_id)?"active":"";
              var pre = (card_data.active_company == companies_list[i].entity_id)?"<strong>":'';
              var suf = (card_data.active_company == companies_list[i].entity_id)?"</strong>":'';
              var selected = '';
              if (active == "active") 
                selected = 'selected="selected"';


              if(card_data.active_company == companies_list[i].entity_id)
                active_company = companies_list[i];
              list_markup += '<li>'+pre+'<a href="#" class="company '+active+'" '+selected+' data-company-id="'+companies_list[i].entity_id+'" data="'+companies_list[i].entity_id+'" >'+companies_list[i].company_name+'</a>'+suf+'</li>';
			  

            }
            list_markup += '</ul></div>';

            $(list_markup).insertAfter('#filters');
			
            $('#company_selector span#company_text').text(cut_string(active_company.company_name, 20));
          }  

          var yaxis = [];
          var values = data['data'].data;
          var n = values.length;

          if($('#save_card').length) {
            var markup = '<div class="btn-group" id="kpi_options"><a class="btn btn-primary dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#" >KPI Options<span class="caret"></span> </a> <ul class="dropdown-menu"><li><table style="margin:10px; width:240px;"><thead><tr><th></th><th style="width:40px;"><img src="'+$('html head base').attr('href')+'assets/img/column_icon.png" title="column" width="36" /></th><th  style="width:40px;"><img src="'+$('html head base').attr('href')+'assets/img/line_icon.png" title="line" width="36" /></th></tr></thead><tbody>';
            for(var i = 0; i < n; i++) {
              markup += '<tr><td>'+$('ul.kpis_select li a[data="'+values[i]['kpi']+'"]').text()+'</td><td style="text-align:center;"><input type="checkbox" value="column" name="option_'+values[i]['kpi']+'"></td><td style="text-align:center;"><input type="checkbox" value="line" name="option_'+values[i]['kpi']+'"></td></tr>';
            }
            markup += '</tbody></table></li></ul></div>';
            $(markup).insertAfter('#period_buttons');
            update_kpi_options_checkboxes();
          }

          //y axis
          for(var i = 0; i < 2; i++) {
            var y = {
                labels: {
                    formatter: function() {
                        return format_number(this.value);
                    }
                },
                title: {
                    text: ''
                },
                opposite: (i == 0)? false : true,
            };
            yaxis.push(y);
          }
          
          //data

          var displayed_data = [];
          for(var i = 0; i < n; i++) {
            var type = get_kpi_type(values[i]['kpi']);
            if(type != false) {
              if(type == 'line')
                type = "spline";
              var obj = {};
              obj.name = $('ul.kpis_select li a[data="'+values[i]['kpi']+'"]').text();
              obj.type = type;
              obj.yAxis = (type == 'column')? 0 : 1;
              obj.data = [];
              for(var j = 0; j < periods.length; j++)
{
                if(values[i]['vals'][j])
                  obj.data.push(parseFloat(values[i]['vals'][j]));
                else
                  obj.data.push(0);
              }
              if(type == 'column')
                displayed_data.unshift(obj);
              else
                displayed_data.push(obj);
            }
          }
          createComboChart(periods, yaxis, displayed_data, card_data);
          update_datatable_combo(periods, displayed_data, card_data);
        }
    });
  }
}

function createColumnChart(periods, displayed_data, card_data){
  $(function () {
    //console.log(displayed_data);
    var kpi_name = $('.kpis_select li a[data="'+card_data.kpi+'"]').attr('title'); 
    var yaxis = { title:{text:kpi_name} };
    if(card_data.kpi == '283050')
      yaxis = {
                title:{text:kpi_name},
                plotBands: plotbands,
              };
    if(card_data.kpi)
    //console.log(displayed_data);
    Highcharts.setOptions({
      lang: {
          numericSymbols: [ "k" , "M" , "B" , "T" , "P" , "E"]
        },
    });
    $('.control.chart').highcharts({
        chart: {
            type: 'column', zoomType: 'x'
        },
        title: {
            text: ' '
        },
        subtitle: {
            text: ' '
        },
        xAxis: {
            categories: periods
        },
        yAxis: yaxis,
        legend: {
              layout: 'vertical',
              align: 'right',
              verticalAlign: 'middle',
              borderWidth: 0
          },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: displayed_data
    });
  });
}

function createLineChart(periods, displayed_data, card_data){
  $(function () {
      //console.log(displayed_data);
      var kpi_name = $('.kpis_select li a[data="'+card_data.kpi+'"]').attr('title'); 
      var yaxis = {
              title: {
                  text: kpi_name
              },
              plotLines: [{
                  value: 0,
                  width: 1,
                  color: '#808080'
              }]
          };
      if(card_data.kpi == '283050')
        yaxis = {
                  title:{text:kpi_name},
                  plotLines: [{
                      value: 0,
                      width: 1,
                      color: '#808080'
                  }],
                  plotBands: plotbands,
                };
      //console.log(displayed_data);
      Highcharts.setOptions({
        lang: {
            numericSymbols: [ "k" , "M" , "B" , "T" , "P" , "E"]
          },
      });

      $(function () {
        $('.control.chart').highcharts({
          chart: {
            type: 'line', zoomType: 'x'
          },
          title: {
              text: '',
          },
          subtitle: {
              text: '',
          },
          xAxis: {
              categories: periods
          },
          yAxis: yaxis,
         /* tooltip: {
            formatter: function() {
                  return kpi_name+' in '+periods[this.point.x]+': <b>'+ format_number(this.point.y) +'</b>';
              }
          },*/
          legend: {
              layout: 'vertical',
              align: 'right',
              verticalAlign: 'middle',
              borderWidth: 0
          },
          series: displayed_data,
        });
      });

    });   
}

function createAreaChart(periods, displayed_data, card_data){
  
  $(function () {
    var kpi_name = $('.kpis_select li a[data="'+card_data.kpi+'"]').attr('title'); 
    //console.log(displayed_data);
    Highcharts.setOptions({
      lang: {
          numericSymbols: [ "k" , "M" , "B" , "T" , "P" , "E"]
        },
    });
        $('.control.chart').highcharts({
            chart: {
                type: 'area', zoomType: 'x'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: periods,
                tickmarkPlacement: 'on',
                title: {
                    enabled: false
                }
            },
            yAxis: {
                title: {
                    text: kpi_name
                },
                labels: {
                    formatter: function() {
                        return format_number(this.value);
                    }
                }
            },
            tooltip: {
                shared: true,
                valueSuffix: ' ',
            },
            legend: {
              layout: 'vertical',
              align: 'right',
              verticalAlign: 'middle',
              borderWidth: 0
            },
            plotOptions: {
                area: {
                    stacking: 'normal',
                    lineColor: '#666666',
                    lineWidth: 1,
                    marker: {
                        lineWidth: 1,
                        lineColor: '#666666'
                    }
                }
            },
            series: displayed_data,
        });
    });
}

function createComboChart(periods, yaxis, displayed_data, card_data){
  $(function () {
    var kpi_name = $('.kpis_select li a[data="'+card_data.kpi+'"]').attr('title'); 
    //console.log(displayed_data);
    Highcharts.setOptions({
      lang: {
          numericSymbols: [ "k" , "M" , "B" , "T" , "P" , "E"]
        },
    });

    $('.control.chart').highcharts({
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: [{
          categories: periods,
          tickmarkPlacement: 'on',
          title: {
              enabled: false
          }
        }],
        yAxis: yaxis,
        tooltip: {
            shared: true
        },
        legend: {
            
        },
        series: displayed_data
    });
  });
}

function update_datatable(periods, displayed_data, card_data){
  if($('table#example2').length) {
    $("#example2").dataTable().fnDestroy();
    $('table#example2').empty();
    $('<thead><tr></tr></thead>').appendTo('table#example2');
    $('<th class="text-center">company Name</th>').appendTo('table#example2 thead tr');
    var row = '';
    for(var i=0; i < periods.length; i++) {
      row += '<th class="text-center">'+periods[i]+'</th>';
    }
    $(row).appendTo('table#example2 thead tr');

    $('<tbody></tbody>').appendTo('table#example2');

    row = '';
    for(var i = 0; i < displayed_data.length; i++) {
      var even = (i % 2) ? 'odd' : 'even';
      row += '<tr class="'+even+'"><td class="center">'+displayed_data[i].name+'</td>';
      for(var j = 0; j < periods.length; j++){
        var val = (displayed_data[i].data[j])? addCommas(displayed_data[i].data[j]) : 'N/A';
        row += '<td class="text-center">'+val+'</td>';
      }
      row += '</tr>';
    }
    $(row).appendTo('table#example2 tbody');
    var oTable = $('#example2').dataTable( {
     "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
     "aaSorting": [],
     "oLanguage": {
          "sLengthMenu": "_MENU_ ",
          "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
      }
    });
    $('#example2_wrapper .dataTables_filter input').addClass("input-medium ");
    $('#example2_wrapper .dataTables_length select').addClass("select2-wrapper span12");
    $(".select2-wrapper").select2({
        minimumResultsForSearch: -1
    });
    $('#example2').css('width','');
    /*console.log(periods);
    console.log(displayed_data);
    console.log(card_data);*/
  }
}

function update_datatable_combo(periods, displayed_data, card_data){
  if($('table#example2').length) {
    $("#example2").dataTable().fnDestroy();
    $('table#example2').empty();
    $('h3 span#active_company').text($('#company_selector ul.companies_list li a[data="'+card_data.active_company+'"]').text());
    $('<thead><tr></tr></thead>').appendTo('table#example2');
    $('<th class="text-center">KPIs</th>').appendTo('table#example2 thead tr');
    var row = '';
    for(var i=0; i < periods.length; i++) {
      row += '<th class="text-center">'+periods[i]+'</th>';
    }
    $(row).appendTo('table#example2 thead tr');

    $('<tbody></tbody>').appendTo('table#example2');

    row = '';
    for(var i = 0; i < displayed_data.length; i++) {
      var even = (i % 2) ? 'odd' : 'even';
      row += '<tr class="'+even+'"><td class="center">'+displayed_data[i].name+'</td>';
      for(var j = 0; j < periods.length; j++){
        var val = (displayed_data[i].data[j])? addCommas(displayed_data[i].data[j]) : 'N/A';
        row += '<td class="text-center">'+val+'</td>';
      }
      row += '</tr>';
    }
    $(row).appendTo('table#example2 tbody');
    var oTable = $('#example2').dataTable( {
     "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
     "aaSorting": [],
     "oLanguage": {
          "sLengthMenu": "_MENU_ ",
          "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
      }
    });
    $('#example2_wrapper .dataTables_filter input').addClass("input-medium ");
    $('#example2_wrapper .dataTables_length select').addClass("select2-wrapper span12");
    $(".select2-wrapper").select2({
        minimumResultsForSearch: -1
    });
    $('#example2').css('width','');
    /*console.log(periods);
    console.log(displayed_data);
    console.log(card_data);*/
  }
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
    x2 = x2.substring(0, 3);
    if(x2 == '.00')
        x2 = '';
    return x1 + x2;
}

/*$('body').on('click', 'ul.card_des a', function(e){
    e.preventDefault();
    var active_a = $('ul.card_des li a.company.active');
    if(active_a.parent().is( "strong" ) )
        active_a.unwrap();
    $('ul.card_des li a.company').removeClass('active');
    var message;
    var myRegExp =/^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
    var urlToValidate = $(this).attr('href');
    if (myRegExp.test(urlToValidate)){
        window.open($(this).attr('href'));
		
        return false;
    }else{

		$(this).addClass('active').wrap('<strong></strong>');
		$(this).focus();
		$('#company_text').text(cut_string($(this).text(), 20));
		if($('input#active_company').val()!=$(this).attr('data'))
		{
			$('input#active_company').val($(this).attr('data'));
			buildChart(active_chart);
	
		}
	}
});*/
/* $('.card_container').on('click', '.flip_card_toggle', function(e){
 e.preventDefault();
 buildChart(active_chart);

 });*/
