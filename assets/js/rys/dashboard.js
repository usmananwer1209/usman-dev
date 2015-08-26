$(document).ready(function() { 
  loadCardsSparkline();
  loadStoryboardsSparkline();
  loadCardsPie();
  of_the_day_actions();
  $('#card_of_the_day').select2({
    placeholder: "Select a Card"
  });
  $('#storyboard_of_the_day').select2({
    placeholder: "Select a Storyboard"
  });
});

function loadCardsSparkline(){
  if($('input#cards_10days_data').length) {
    $(function() {
   
      var cards_sparkline = function() {
        var data = $('input#cards_10days_data').val().split(',');
        $("#cards_sparkline").sparkline(data, {
          type: 'line',
          width: '100%',
          height: '200px',
          lineColor: '#ffffff',
          lineWidth: 2,
          fillColor: '#CBCBCB',
          spotColor: '#ffffff',
          minSpotColor: '#f35958',
          maxSpotColor: '#f35958',
          highlightLineColor: '#fff',
          spotRadius: 5,
          valueSpots:{'0:':'#fff'}
        });
      }
      var sparkResize;
   
      $(window).resize(function(e) {
          clearTimeout(sparkResize);
          sparkResize = setTimeout(cards_sparkline, 500);
      });
      cards_sparkline();
    });
  }
}

function loadStoryboardsSparkline(){
  if($('input#sb_10days_data').length) {
    $(function() {
   
      var sb_sparkline = function() {
        var data = $('input#sb_10days_data').val().split(',');
        $("#sb_sparkline").sparkline(data, {
          type: 'line',
          width: '100%',
          height: '200px',
          lineColor: '#ffffff',
          lineWidth: 2,
          fillColor: '#CBCBCB',
          spotColor: '#ffffff',
          minSpotColor: '#f35958',
          maxSpotColor: '#f35958',
          highlightLineColor: '#fff',
          spotRadius: 5,
          valueSpots:{'0:':'#fff'}
        });
      }
      var sparkResize;
   
      $(window).resize(function(e) {
          clearTimeout(sparkResize);
          sparkResize = setTimeout(sb_sparkline, 500);
      });
      sb_sparkline();
    });
  }
}

function loadCardsPie() {
  $('#cards_public_percent').easyPieChart({
    lineWidth:9,
    barColor:'#EC6E69',
    trackColor:'#e5e9ec',
    scaleColor:false
  });
  $('#sb_public_percent').easyPieChart({
    lineWidth:9,
    barColor:'#8DD1F1',
    trackColor:'#e5e9ec',
    scaleColor:false
  });
}

function of_the_day_actions() {
  $('button.otd').click(function(e){
    e.preventDefault();
    var site_url = $('input#site_url').val();
    var action = $(this).attr('id').replace('update_otd_', '');
    var selected_val = $('select#'+action+'_of_the_day').val();
    if(selected_val) {
      $.blockUI({
          message: '<h1><img src="'+site_url+'/assets/img/AjaxLoader.gif" /></h1>'
      }); 
      
      
      $.ajax( {
          url :  site_url + '/' + action,
          data: {
                  'action' : action,
                  'val' : selected_val
                },
          type:"POST",
          success : function(data) {
            if(data == 'ok') {
              $('.of_the_day_container alert').addClass('hidden');
              $('#'+action+'_success_message').removeClass('hidden');
            }
            else {
              $('.of_the_day_container alert').addClass('hidden');
              $('#'+action+'_server_error').removeClass('hidden');
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            $('.of_the_day_container alert').addClass('hidden');
            $('#'+action+'_server_error').removeClass('hidden');
          }
      });
    }
    else {
      $('.of_the_day_container alert').addClass('hidden');
      $('#'+action+'_select_error').removeClass('hidden');
    }

  });
}