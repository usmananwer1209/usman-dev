active_chart = '';
years = [];
quarters = [];
active_segments = 'year';
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
});

function buildChart(type){
  segments = active_segments;
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
          $('.add_card').unblock();
          $('#reporting_period_edit').hide();
          $('#view_card_reporting_period').hide();

          if($('#period_buttons').length == 0)
            $('<div id="period_buttons"><button class="btn btn-success btn-cons" type="button" id="year">Annual Data</button> <button class="btn btn-success btn-cons" type="button" id="quarter">Quarterly Data</button></div>').insertAfter($('#filters'));
          $('#period_buttons #year, #period_buttons #quarter').removeClass('disabled');
          $('#period_buttons #'+segments).addClass('disabled');
          
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
          //console.info(displayed_data);
          createColumnChart(periods, displayed_data, card_data);
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

          if($('#period_buttons').length == 0)
            $('<div id="period_buttons"><button class="btn btn-success btn-cons" type="button" id="year">Annual Data</button> <button class="btn btn-success btn-cons" type="button" id="quarter">Quarterly Data</button></div>').insertAfter($('#filters'));
          $('#period_buttons #year, #period_buttons #quarter').removeClass('disabled');
          $('#period_buttons #'+segments).addClass('disabled');
          
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

          if($('#period_buttons').length == 0)
            $('<div id="period_buttons"><button class="btn btn-success btn-cons" type="button" id="year">Annual Data</button> <button class="btn btn-success btn-cons" type="button" id="quarter">Quarterly Data</button></div>').insertAfter($('#filters'));
          $('#period_buttons #year, #period_buttons #quarter').removeClass('disabled');
          $('#period_buttons #'+segments).addClass('disabled');
          
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
        }
    });
  }

  if(type == 'combo'){
    active_chart = 'combo';
    var card_data = get_card_data();
    $('.add_card').block({ message: null }); 
    $.ajax( {
        url :  site_url + 'card/get_data_json_by_post',
        data: card_data,
        type:"GET",
        dataType: 'json',
        error : function(){
            $('.add_card').unblock();
        },
        success : function(data) {
          $('.add_card').unblock();

         $('.control.chart').css('display', 'block');
          
          var kpi_name = $('.kpis_select option[value="'+card_data.kpi+'"]').attr('title');
          var displayed_data = [];
          var obj = {};
          obj.name = kpi_name;
          obj.data = [];
          var comp_names = [];
            for(var i = 0; i < data.length; i++)
            {
              var val = 0;
              if(data[i][card_data.kpi])
                val = parseFloat(data[i][card_data.kpi]);
              obj.data.push([val]);
              comp_names.push(data[i].company_name);
            }
          displayed_data.push(obj);
          $(function () {
            new TradingView.MediumWidget({
              "container_id": "chart_container",
              "symbols": [
                [
                  "Google",
                  "GOOG"
                ],
               [
                  "Microsoft",
                  "MSFT"
                ]

              ],
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
    });
  }
}

function createColumnChart(periods, displayed_data, card_data)
{
  $(function () {
    //console.log(displayed_data);
    var kpi_name = $('.kpis_select option[value="'+card_data.kpi+'"]').attr('title');
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
        yAxis: {
            title: {
                text: kpi_name
            }
        },        
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

function createLineChart(periods, displayed_data, card_data)
{
  $(function () {
      //console.log(displayed_data);
      var kpi_name = $('.kpis_select option[value="'+card_data.kpi+'"]').attr('title');
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
          yAxis: {
              title: {
                  text: kpi_name
              },
              plotLines: [{
                  value: 0,
                  width: 1,
                  color: '#808080'
              }]
          },
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

function createAreaChart(periods, displayed_data, card_data)
{
  
  $(function () {
    var kpi_name = $('.kpis_select option[value="'+card_data.kpi+'"]').attr('title');
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