thumb_width = 190;
current_left = 0;
slider = null;
ratio = null;
viewer_resize = null;
view_expanded = true;
$( window ).load( function(){
  //setTimeout(function() {
    //alert('ready');
    update_index_position();
  //}, 1000);
});


$(function() {
  update_index_position();

  slider = $('.bxslider').bxSlider({
    pagerCustom: '#bx-pager',
    onSliderLoad: function(){
      update_index_position();
    },
    onSlideAfter: function(){
/*	console.log($(this));
	$("iframe").each(function() { 
        var src= $(this).attr('src');
		console.log(src);
        $(this).attr('src',src);  
	});*/

      update_index_position();
    }
  });

  $('#storyboard_viewer').on('click', '#scrollRight', function(){
    scroll_slides('right');
    return false;
  });

  $('#storyboard_viewer').on('click', '#scrollLeft', function(){
    scroll_slides('left');
    return false;
  });

  set_sequencer_size();
  $( window ).resize(function() {
    clearTimeout(viewer_resize);
    viewer_resize = setTimeout(function(){
      set_sequencer_size();
    }, 200);
    //set_sequencer_size();
  });

  $('#layout-condensed-toggle').click(function(){
    slider.redrawSlider();
    set_sequencer_size();
  });

  $('#storyboard_viewer #toggle_collapse').click(function(){
    if($('#storyboard_viewer #toggle_collapse').hasClass('expanded')) {
      $('#storyboard_viewer #toggle_collapse').removeClass('expanded').addClass('collapsed');
        $('#bx-pager').addClass('collapsed');
      $('#primary_pager').slideUp(500, function(){
        $('.bx-controls-direction a').fadeIn(400);
        view_expanded = false;
        set_sequencer_size();
        $('#secondary_pager #storyboard_title').fadeIn(300);
      });
      $('#bx-pager').animate({'height':30}, 500);
      $('#secondary_pager').slideDown(500);
    }
    else {
      $('#storyboard_viewer #toggle_collapse').removeClass('collapsed').addClass('expanded');
      $('#bx-pager').removeClass('collapsed');
      $('#secondary_pager #storyboard_title').hide();
      $('#primary_pager').slideDown(500, function(){
        view_expanded = true;
        set_sequencer_size();
      });
      $('#secondary_pager').slideUp(500);
      $('#bx-pager').animate({'height':175}, 500);
      $('.bx-controls-direction a').fadeOut(400);
    }
    return false;
  });

  $("#start_frame .description, #slide_preview_desc_area").mCustomScrollbar({
    axis:"y",
    theme:'minimal-dark'
  });

});

function scroll_slides(direction) {
  var view_port_width =  $('#other_slides').width();
  var slides_width =  $('#other_slides_container').width();
  var new_left = parseInt($('#other_slides_container').css('left').replace('px', ''));
  if(!new_left)
    new_left = 0;
  if(direction == 'right') {
    new_left -= 180;
    if(slides_width + new_left <= view_port_width)   
      new_left = view_port_width - slides_width;
    $('#other_slides_container').animate({'left': new_left+'px'}, 400);
  }
  if(direction == 'left') {
    new_left += 180;
    if(new_left >= 0)
      new_left = 0;
    $('#other_slides_container').animate({'left': new_left+'px'}, 400);
  }
  update_scroll_buttons(new_left, view_port_width, slides_width);
}

function update_scroll_buttons(pos, view_port, elmt_width) {
  var max  = view_port - elmt_width;
  $('#other_slides').removeClass('scrollLeft scrollRight');
  if(max >= 0){
      $('#scrollLeft').hide();
      $('#scrollRight').hide();
  }
  else {
    current_left = pos;
    if(pos == 0)
      $('#scrollLeft').hide();
    else {
      $('#scrollLeft').show();
      $('#other_slides').addClass('scrollLeft');
    }
    if(pos == max)
      $('#scrollRight').hide();
    else {
      $('#scrollRight').show();
      $('#other_slides').addClass('scrollRight');
    }
  }
  update_index_position();
}

function set_sequencer_size() {
  var n = $('#other_slides_container .slide_thumb').length;
  var slides_width = n*thumb_width;
  var view_port_width = $('#bx-pager').width() - 440;
  $('#other_slides_container').css('width', slides_width+'px');
  $('#other_slides').css('width', view_port_width+'px');
  update_scroll_buttons(current_left, view_port_width, slides_width);
}

function update_index_position() {
  $('#bx-pager span.bordered').remove();
  $('#bx-pager #primary_pager a.active').append('<span class="bordered"></span>');
  var index = $('#bx-pager a.active').attr('data-slide-index');
  $.browser.chrome = /chrom(e|ium)/.test(navigator.userAgent.toLowerCase()); 
  	if($.browser.chrome){
		if($('#cardframe_'+index).get(0)!==undefined){
			var ctype = $('#charttype_'+index).val();
			if(ctype == 'combo_new' || ctype == 'line' || ctype == 'area' || ctype == 'column') {
			  if($('#isreload_'+index).val()==0) {
				$('#isreload_'+index).val('1');
				$('#cardframe_'+index).get(0).contentWindow.location.reload(true);
			  }
			}
		}
	}
  $('#secondary_pager a').removeClass('active');
  $('#secondary_pager a[data-slide-index="'+index+'"]').addClass('active');

  if(view_expanded)
    var vp_height = $(window).height()-300;
  else
    var vp_height = $(window).height()-150;
  if($('#layout-condensed-toggle').length == 0)
    vp_height += 80;
  var vp_width = $('.bx-viewport').width();
  var H = vp_height;

  if(true){
    var img = $('#start_frame img.full_width.auto_height');
    var pic_real_width, pic_real_height;
    $("<img/>").attr("src", $(img).attr("src")).load(function() {
      pic_real_width = this.width;
      pic_real_height = this.height;
      ratio = pic_real_width/pic_real_height;
      if(vp_height*ratio > vp_width)
        vp_height = vp_width/ratio; 
      $('.bx-viewport').css('height', (vp_height+20)+'px');
      $('.full_width.auto_height').parent().css('height', vp_height+'px');
      $('.full_width.auto_height').parent().css('width', (vp_height*ratio)+'px');
      $('.full_width.auto_height').css('width', (vp_height*ratio)+'px');
      $('.full_width.auto_height').css('height', (vp_height)+'px');
    });
  }
  else{
    if(vp_height*ratio > vp_width)
      vp_height = vp_width/ratio;
    $('.bx-viewport').css('height', (H+20)+'px');
    $('.full_width.auto_height').parent().css('height', vp_height+'px');
    $('.full_width.auto_height').parent().css('width', (vp_height*ratio)+'px');
    $('.full_width.auto_height').css('width', (vp_height*ratio)+'px');
    $('.full_width.auto_height').css('height', (vp_height)+'px');
  }


  $('#start_frame .title').css({'font-size':vp_height*0.05, 'line-height':(vp_height*0.07)+'px'});
  var title_fs = (vp_width*0.025 > 30) ? 30 : vp_width*0.025;
  $('#secondary_pager #storyboard_title').css({'font-size':title_fs});
  $('#start_frame .description').css({'font-size':vp_height*0.032, 'line-height':(vp_height*0.038)+'px'});
  $('#end_frame .end_text').css({'font-size':vp_height*0.04, 'line-height':(vp_height*0.05)+'px'});
  $('#end_frame .full_name').css({'font-size':vp_height*0.045, 'line-height':(vp_height*0.05)+'px'});

  $('#storyboard_view_slides > li').css('height', (H-10)+'px');
  $('#storyboard_view_slides #slide_preview_content_area').css('height', (H-30)+'px');
  $('#storyboard_view_slides #slide_preview_desc_area').css({'height':(H-30)+'px', 'overflow': 'auto'});
  $('li.border .card_iframe').css({'height':(H-30)+'px'});

  var imgs = $('#slide_preview_content_area > img');
  for(var k = 0; k < imgs.length; k++)
    set_image_height(imgs[k], H-50, $('#slide_preview_content_area').width() );

  //fixing iframe
  if($('.bx-viewport li.border.indicator #slide_preview_content_area').length) {
    var original_h = 482, original_w = 700;
    var scale = (H-50)/original_h;
    var indi_w = (scale < 1) ? original_w : original_w*scale;
    var indi_h = (scale < 1) ? original_h : original_h*scale;
    var content_w = $('.bx-viewport li.border.indicator #slide_preview_content_area').width();
    if(indi_w > content_w) {
      //scale = scale * (content_w / indi_w);
      //indi_w = content_w;
      indi_h = (scale < 1) ? original_h : original_h*scale; 
    }
    $('li.border.indicator iframe').css({'width':indi_w+'px', 'height': indi_h+'px', 'margin':'0 auto', 'display':'block'});
    scale_iframe($('li.border.indicator iframe'), scale);
  }
  if($('.bx-viewport li.border.media #slide_preview_content_area').length) {
    /*var y_ratio = 16.0/9.0;
    var yoriginal_w = $('.bx-viewport li.border.media #slide_preview_content_area').width();
    var yoriginal_h = yoriginal_w/y_ratio;
    var y_scale =(H-20)/yoriginal_h;
    var indi_w = (y_scale < 1) ? yoriginal_w : yoriginal_w*scale;*/
    var yoriginal_w = $('.bx-viewport li.border.media #slide_preview_content_area').width();
    $('li.border.media iframe').css({'width':yoriginal_w+'px', 'height':H-30+'px', 'margin':'0 auto', 'display':'block'});
    //scale_iframe($('li.border.media iframe'), y_scale);
  }
}

function set_image_height(elmt, height, max_width) {
  var img = $(elmt);
  var pic_real_width, pic_real_height;
  $("<img/>").attr("src", $(img).attr("src")).load(function() {
    pic_real_width = this.width;
    pic_real_height = this.height;
    ratio = pic_real_width/pic_real_height;
    if(height*ratio > max_width)
      height = max_width/ratio;

    img.css('width', (height*ratio)+'px');
    img.css('height', (height)+'px');
  });

}

function scale_iframe(iframe, scale) {
  iframe.css({
    '-ms-zoom': scale,
    '-moz-transform': 'scale('+scale+')',
    '-moz-transform-origin': '0 0',
    '-o-transform': 'scale('+scale+')',
    '-o-transform-origin': '0 0',
    '-webkit-transform': 'scale('+scale+')',
    '-webkit-transform-origin': '0 0'
  });
  
}
