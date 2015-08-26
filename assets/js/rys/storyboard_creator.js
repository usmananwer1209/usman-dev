var thumb_width = 190,
slide_status = 'create_new',
current_slide = '',
current_left = 0,
baraja = '',
DEFAULT_TEMPLATE = 2, //1:top, 2:right, 3:bottom, 4:left
filter_timer = false,
deck_height = 0,
deck_state = 'open',
deck_switch = 0;

$(function() {
	
  //deck creator
  baraja = create_cards_deck();
  //default active card
  
  active_slide('start_slide');
	
  //fix sizes dynamically
  set_sequencer_size();
  
  set_default_filter();
  filters_actions();
  $( window ).resize(function() {
    set_sequencer_size();
  });

  //storyboard creator events
  //start and end slide
  start_end_slide_actions();

  //slides in the sequencer actions
  add_new_slide_actions();
  edit_slide_actions();
  remove_slide_actions();
  slide_position_actions();
  slide_details_actions();
  add_card_to_sequencer(); //add cards from the deck to the sequencer actions
    //preview_button_actions(); /* preview_storyboard_code Begin */
  save_button_actions();
  image_upload_actions();
  preview_slide_actions();
  add_link_enable_button();

    //general
  $('#storyboard_canvas').on('click', function(){

    return hide_elements();
  });

    $('#storyboard_canvas').on('change', 'input[name=wc_type]:radio', function(){
        //cur_wc_type = $("input[name='rdio']:checked").val();

        parseText();

        update_slides_data();
    });

});

function preview_slide_actions() {

    $('#storyboard_canvas').on('mouseenter', '.slide_thumb', function () {
        var content = $(this).find('input.content').val();
        var cid = $(this).find('input.content').val();

        if (content || ($(this).attr('id') == 'start_slide') || ($(this).attr('id') == 'end_slide'))
            $(this).find('a.preview_slide').show();
    });

    $('#storyboard_canvas').on('mouseleave', '.slide_thumb', function () {
        $(this).find('a.preview_slide').hide();
    });

    $('#storyboard_canvas').on('click', '.preview_slide', function (e) {
        e.preventDefault();
        hide_elements();
        $('#preview_slide_modal .modal-body .row').empty();

        var slide = $(this).parent();
        var indicator_iframe = false;
        if (slide.attr('id') == 'start_slide' || slide.attr('id') == 'end_slide') {
            var type = slide.attr('id');
            var number = $('#start_slide input#start_end_slide').val();
            if (type == 'start_slide') {
                var title = $('#start_slide input#start_title').val();
                var description = $('#start_slide input#start_description').val();
                var image = $('#start_slide input#start_image').val();

                $('<div style="text-align:center"><span id="start_frame" class="template_' + number + '"><img class="full_width" src="' + $('#select_start_end_template_preview #select_template_' + number + ' img').eq(0).attr('src').replace('template_start', 'empty_template_start') + '" /></span></div>').appendTo('#preview_slide_modal .modal-body .row');
                $('<span class="title">' + title + '</span>').appendTo('#start_frame');
                $('<span class="description">' + description + '</span>').appendTo('#start_frame');
                $('<img src="' + image + '" class="start_image" />').appendTo('#start_frame');
                /*$("#preview_slide_modal #start_frame .description").mCustomScrollbar({
                 axis:"y",
                 theme:'minimal-dark',
                 });*/
            }
            else {
                var end_text = $('#end_slide input#end_text').val();
                var end_avatar = $('#end_slide input#end_avatar').val();
                var full_name = $('#end_slide input#full_name').val();
                var avatar_path = $('#end_slide input#avatar_path').val();
                if ($("#end_slide input.has_link").val() == 'true') {
                    var link = $("#end_slide input.link_url").val();
                    var link_name = $("#end_slide input.link_name").val();
                }

                $('<div style="text-align:center"><span id="end_frame" class="template_' + number + '"><img class="full_width" src="' + $('#select_start_end_template_preview #select_template_' + number + ' img').eq(1).attr('src').replace('template_end', 'empty_template_end') + '" /></span></div>').appendTo('#preview_slide_modal .modal-body .row');
                $('<span class="end_text">' + end_text + '</span>').appendTo('#end_frame');
                $('<span class="full_name">' + full_name + '</span>').appendTo('#end_frame');
                if (end_avatar == 'true')
                    $('<p class="avatar_frame"><img src="' + avatar_path + '" class="avatar" /></p>').appendTo('#end_frame');
                if (link && link_name) {
                    $('<a class="end_link" href="' + link + '" taget="_blank">' + link_name + '</a>').appendTo('#end_frame');
                }
            }
        }
        else {
            var type = slide.find('input.template_type').val();
            var style = $('input[name=setstyle]').val();

            if (type == 'card') {
                var title = slide.find('input.new_title').val();
                var description = slide.find('input.new_description').val();
                var content = slide.find('input.guid').val();
                var stylechart = slide.find('input.stylechart').val();
            }
            else {
                var title = slide.find('input.title').val();
                var description = slide.find('input.description').val();
                var content = slide.find('input.content').val();
            }
            var template = slide.find('input.template_number').val();
            var content_to_display = '';
            var content_area = $('#slide_preview_content_area');

            if (type == 'card') {
                update_slides_data();
                var new_title = slide.find('input.new_title').val();
                var new_description = slide.find('input.new_description').val();
                $.ajax({
                    url: site_url + 'storyboard/setDescription',
                    data: {'preview_description': new_description, 'preview_title': new_title},
                    type: "POST",
                    success: function (data) {
                        return true;
                    }
                });
                var sid = $('#sb_id').val();
                //var src = $('li#card_'+content+' a.preview_deck_card').attr('data-remote');
                var src = $('input#get_cards_url').val().replace('/storyboard/get_cards', '') + '/card/embed/' + content;
                content_to_display = '<iframe src="' + src + '/preview/' + sid + '/' + style + '" frameborder="0" scrolling="no" ></iframe>';
            }
            else if (type == 'wordcloud') {

                var slide_id = slide.attr('id');
                var graphNode = wordcloud_img_cmds[slide_id];
                //var bounds = wordcloud_bounds[slide_id];

                if (!graphNode) {
                    var wc_content = $('#' + slide_id + ' input.wc_content').val();
                    // only do this on an existing wc slide
                    if (wc_content) {

                        var svg = $(create_svg_and_vis()).get(0);
                        var w = 867;
                        var h = 600;

                        $(svg).attr('width', w + 'px');
                        $(svg).attr('height', h + 'px');

                        var vis = $('#svgG').get(0);

                        // children get appended to the vis element

                        build_wc_commands(slide_id, wc_content, vis, w, h);

                        //wordcloud_img_cmds[id] = content_to_display.cloneNode(true);
                        graphNode = wordcloud_img_cmds[slide_id] = svg;
                    }
                }
                else if (wordcloud_bounds[slide_id]) {
                    wc_set_transform_and_scale($(graphNode[0]), 867, 600, slide_id);
                }

                content_to_display = $(graphNode[0]).clone(true,true);

                $(content_to_display).attr('id', 'pv_svgClone');
            }
            else if (type == 'indicator') {
                var src = $('#indicator_id option[value="' + content + '"]').attr('data-url');
                content_to_display = '<iframe src="' + src + '" frameborder="0" ></iframe>';
                indicator_iframe = true;
                //-ms-zoom: 1.5; -moz-transform: scale(1.5);-moz-transform-origin: 0 0;-o-transform: scale(1.5);-o-transform-origin: 0 0; -webkit-transform: scale(1.5);-webkit-transform-origin: 0 0;
            }
            else if (type == 'media') {
                if (is_img(content))
                    content_to_display = '<img src="' + content + '" alt="' + title + '" class="full_width" style="width:100%;" />';
                if (get_youtube_id_from_url(content))
                    content_to_display = '<iframe style="" src="//www.youtube.com/embed/' + get_youtube_id_from_url(content) + '" frameborder="0"></iframe>';
                if (get_vimeo_id_from_url(content))
                    content_to_display = '<iframe style="" src="//player.vimeo.com/video/' + get_vimeo_id_from_url(content) + '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
            }
            if (template == 2) 
			{
                if (type == 'card') {
                    /*			var cid = slide.find('input.content').val();
                     var src = $('input#get_cards_url').val().replace('get_cards', '') + 'gettags';
                     getTags(cid,src)*/
                    if (stylechart == 'combo_new' || stylechart == 'column' || stylechart == 'area' || stylechart == 'line' || stylechart == 'tree' || stylechart == 'explore' || stylechart == 'map') {
                        $('<div id="slide_preview_content_area" class="col-md-12"></div>').appendTo('#preview_slide_modal .modal-body .row');
                    }
                    else {
                        $('<div id="slide_preview_content_area" class="col-md-9 "></div><div id="slide_preview_desc_area" class="col-md-3 card_des ' + style + '"></div>').appendTo('#preview_slide_modal .modal-body .row');
                    }
                }
                else {
                    $('<div id="slide_preview_content_area" class="col-md-9 "></div><div id="slide_preview_desc_area" class="col-md-3 card_des ' + style + '"></div>').appendTo('#preview_slide_modal .modal-body .row');
                }
            }
            // content panel on left
            if (template == 4)
                $('<div id="slide_preview_desc_area" class="col-md-3 card_des ' + style + '"></div><div id="slide_content_area" class="col-md-9"></div>').appendTo('#preview_slide_modal .modal-body .row');

            $(content_to_display).appendTo('#slide_preview_content_area');

            if (type == 'card' && stylechart != 'combo_new' || stylechart != 'column') {
                $('<h1>' + title + '</h1><p>' + description + '</p>').appendTo('#slide_preview_desc_area');
            }
            else 
			{
                $('<h1>' + title + '</h1><p>' + description + '</p>').appendTo('#slide_preview_desc_area');
            }

            if (indicator_iframe) {
                var original_h = 482, original_w = 700;
                var W = $('html').width() * 0.8;
                if (W > 1200)
                    W = 1200;
                W *= 0.75;
                var scale = (W - 20) / original_w;
                scale_iframe($('#preview_slide_modal #slide_preview_content_area iframe'), scale);
            }
        }


        $('#preview_slide_modal').modal();
        $('#preview_slide_modal').on('hidden.bs.modal', function (e) {
            $('#preview_slide_modal .modal-body .row').empty();
        })

        if (type == 'wordcloud') {
            $('#slide_preview_content_area').css('background-image', 'none');
        }

        return false;
    });

    /*$('body').on('click', '#remove_slide_modal button.removeSlide', function(e){
     hide_elements();
     $('#'+$(this).attr('data-id')).remove();
     set_sequencer_size();
     active_slide();
     $('#remove_slide_modal').modal('hide');
     });*/
}

function wc_set_transform_and_scale(parentNode, w, h, slide_id) {
    var g = $(parentNode).find('#svgG');

    $(g).parent().attr('width', w);
    $(g).parent().attr('height', h);

    var scale = find_scale(wordcloud_bounds[slide_id], w, h);

    //content_to_display.setAttribute('viewBox','0 0 600 600');

    //var g = $(content_to_display).children(0);//.childNodes[1];

    $(g).attr('transform', "translate(" + [ w >> 1, h >> 1] + ") scale("+ scale +")");

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

function update_media_preview(from_url) {
  if(from_url)
    var img = $('#form_area form #url').val();
  else
    var img = $('#form_area form input#uploaded_image').val();
  if(is_img(img))
    $('#upload_image_preview img').attr('src', img);
  else {
    var vid_id = get_youtube_id_from_url(img);
    if(vid_id != false)
      $('#upload_image_preview img').attr('src', '//img.youtube.com/vi/'+vid_id+'/0.jpg');
    else{
      var vid_id = get_vimeo_id_from_url(img);
      if(vid_id != false){
        $('#upload_image_preview img').attr('src', '');
        $('#upload_image_preview img').attr('id', 'vimeo-'+vid_id);
        vimeoLoadingThumb(vid_id);
      }
    else
      $('#upload_image_preview img').attr('src', $('input#get_cards_url').val().replace('/storyboard/get_cards', '')+'/assets/img/upload_img.png');
    }
  }
}

function image_upload_actions(from_url) {
  update_media_preview(from_url);
  
  $('#upload').fileupload({
    // This element will accept file drag/drop uploading
    dropZone: $('#drop'),
    // This function is called when a file is added to the queue;
    // either via the browse button, or via drag/drop:
    add: function (e, data) {
      $('#upload .message').text('uploading...');
      // Automatically upload the file once it is added to the queue
      var jqXHR = data.submit().success(function (result, textStatus, jqXHR) {
        var response = eval( '('+result+')' );
        $('#upload .message').text('');
        if(response.url) {
          $('#upload_image_preview img').attr('src', response.url);
          $('#upload_image_preview input#uploaded_image').val(response.url);

          update_slides_data(true);
        }
      });
    },
    fail:function(e, data){
      // Something has gone wrong!
      data.context.addClass('error');
    }

  });

  // Prevent the default action when a file is dropped on the window
  $(document).on('drop dragover', function (e) {
    e.preventDefault();
  });
}


/* preview_storyboard_code Begin 

function preview_button_actions() {
    $('#storyboard_canvas').on('click', '#preview_storyboard', function() {
        hide_elements();
        var data = collect_storyboard_data();
        console.log(data);
        if (data.valid) {
            if ($('input#sb_preview_id').val() == 0)
                preview_storyboard(data, 'add');
            else
                preview_storyboard(data, 'edit');
        }
        else {
            $('#save_sb_errors').html(data.errors);
            $('#save_sb_error_modal').modal();
        }

        return false;
    });
}

function preview_storyboard(data, action) {
    var site_url = $('input#get_cards_url').val().replace('/storyboard/get_cards', '');
    if ($('input#sb_id').val()){
        data['parent_id']=$('input#sb_id').val();
    }else{
        data['parent_id']=0;
    }

    $.blockUI({
        message: '<h1><img src="' + site_url + '/assets/img/AjaxLoader.gif" /></h1>'
    });
    $.ajax({
        url: site_url + '/storyboard/submit_preview_' + action,
        data: data,
        type: "POST",
        success: function(data) {
            if (data != "ko") {
                $('input#sb_preview_id').val(data);
            }
            //   window.location.href = site_url+"/storyboard/my_storyboards";
        }
    });
}

 // preview_storyboard_code End  */

function save_button_actions () {
	
  $('#storyboard_canvas').on('click', '#save_storyboard', function(){
    hide_elements();
	update_slides_data();
    
	var data = collect_storyboard_data();
    
	if(data.valid) {
      if($('#storyboard_canvas').hasClass('add'))
        save_storyboard(data, 'add');
      else
        save_storyboard(data, 'edit');
    }
    else {
      $('#save_sb_errors').html(data.errors);
      $('#save_sb_error_modal').modal();
    }

    return false;
  });

    $('#storyboard_canvas').on('click', '#gen_wordcloud', function(){

        parseText();

        update_slides_data();
    });
}

function build_wc_commands(id, wc_content, parent, wArg, hArg) {

    // append everything to the parent
    //wcComms = data;

    var unpacked = window.atob(wc_content);
    var data = JSON.parse(unpacked);

    var bounds = wordcloud_bounds[id] = data.bounds;
    //var words = wc_content;

    $.each(data.words, function (key, item) {
        var textNode = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        $(parent).append(textNode);

        $(textNode).attr('text-anchor', item.text_anchor)
            .attr('transform', item.transform)
            .attr('style', item.style)
            .attr('id', item.id);

        textNode.textContent = item.the_word;

        //$(textNode).appendTo(parent);
    });

    var w = wArg ? wArg : parseInt($(parent).parent().width(), 10);
    var h = hArg ? hArg : parseInt($(parent).parent().height(), 10);

    if (!w) {
        w = parseInt($(parent).parent().attr('width'), 10);
    }

    if (!h) {
        h = parseInt($(parent).parent().attr('height'), 10);
    }

    var scaleFac = find_scale(bounds, w, h);
    $(parent).attr('transform', "translate(" + [w >> 1, h >> 1] + ") scale(" + scaleFac + ")");
}

function save_storyboard(data, action) {
  var site_url = $('input#get_cards_url').val().replace('/storyboard/get_cards', '');
  $.blockUI({
      message: '<h1><img src="'+site_url+'/assets/img/AjaxLoader.gif" /></h1>'
  }); 
  $.ajax({
      url : site_url + '/storyboard/submit_'+action,
      data: data,
      type:"POST",
      success : function(data){
            if (data != "ko"){
                if(action=="edit"){
                    $("#messages").html('<div class="alert alert-success">Your Changes have been Successfully Submitted</div>');
                    $("#messages").show();
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                }else{
                    window.location.href = site_url + "/storyboard/edit/"+data+"/succes";
                }
                
            }
                //window.location.href = site_url + "/storyboard/my_storyboards";
      }
  });
}

function collect_storyboard_data() {
  var result = {};
  //start and end slide data
  result.valid = true;
  result.errors = '';
  if($('input#sb_id').length)
    result.sb_id = $('input#sb_id').val();
  result.title = $('#start_slide input#start_title').val();
  if(!result.title) {
    result.valid = false;
    result.errors += 'You need to specify a title for your storyboard<br/>';
  }
  result.description = $('#start_slide input#start_description').val();
  if(!result.description) {
    result.valid = false;
    result.errors += 'You need to add a description for your storyboard<br/>';
  }
  result.userstyle = $('input[name=setstyle]').val();
  
	// 14-Jan-15 efj wordcloud doesn't have userstyle
    if (!result.userstyle) {
        result.userstyle = 'link-border';
    }

  result.start_image = $('#start_slide input#start_image').val();
  result.start_end_template = $('#start_slide input#start_end_slide').val();
  result.end_text = $('#end_slide input#end_text').val();
  if(!result.end_text) {
    result.valid = false;
    result.errors += 'You need to specify a call for action or quote for your storyboard (end slide)<br/>';
  }
  result.end_link = '';
  result.end_link_name = '';
  if ($("#end_slide input.has_link").val() == 'true') {
      result.end_link = $("#end_slide input.link_url").val();
      if (!result.end_link) {
          result.valid = false;
          result.errors += 'You need to specify the link url for the end slide<br/>';
      }
      if(!valid_url(result.end_link)) {
        result.valid = false;
        result.errors += 'You need to specify a valid url for the end slide<br/>';
      }
      result.end_link_name = $("#end_slide input.link_name").val();
      if (!result.end_link_name) {
          result.valid = false;
          result.errors += 'You need to specify the link name in the end slide<br/>';
      }
  }
  result.end_text = $('#end_slide input#end_text').val();
  if (!result.end_text) {
      result.valid = false;
      result.errors += 'You need to specify a call for action or quote for your storyboard (end slide)<br/>';
  }

  result.end_avatar = $('#end_slide input#end_avatar').val();
  result.slides_number = $('#other_slides_container .created_thumb').length;
  
  //other slides data
  var slide;
  result.slides = [];
  $('#other_slides_container .created_thumb').each(function(i){
    slide = {};
    slide.type = $(this).find('input.template_type').val();
    slide.template = $(this).find('input.template_number').val();
    slide.content = $(this).find('input.content').val();
      slide.guid = $(this).find('input.guid').val();

    if(!slide.content) {
      result.valid = false;
      result.errors += 'You added the slide number '+(i+1)+' ('+slide.type+') without specifying its content<br/>';
    }
    if(slide.type == 'card'){
      slide.title = $(this).find('input.new_title').val();
      slide.description = $(this).find('input.new_description').val();
    }
    else {
      slide.title = $(this).find('input.title').val();
      slide.description = $(this).find('input.description').val();
    }  
    if(slide.type == 'media'){
      if(!is_img(slide.content) && !get_youtube_id_from_url(slide.content) && !get_vimeo_id_from_url(slide.content)) {
        result.valid = false;
        result.errors += 'The content for slide number '+(i+1)+' ('+slide.type+') should either be a Youtube video, Vimeo video or an image<br/>';
      }
    }
    else if (slide.type == 'wordcloud') {

        slide.wc_words = $(this).find('input.wordtext').val().replace('"', '');

        if (!slide.wc_words) {
            result.valid = false;
            result.errors += 'You must specify words to use in the wordcloud image for slide number ' + (i + 1) + ' (' + slide.title + ')<br/>';
        }

        slide.wc_type = $(this).find('input.wc_type').val();

        // this is a bit tricky, but if the type is 'wordcloud'
        // but there is no wc_content, then don't delete the slide or wc_content
        // as the user never retrieved it
        //if (wordcloud_img_cmds['order_' + i]) {

        //16-Jan-15 always get wc content, as it's always put
        var gElement = $(wordcloud_img_cmds['order_' + i]).find('g#svgG');

        if ($(gElement).children().length == 0) {

            var wc_content = $(this).find('input.wc_content').val();

            if (wc_content) {
                var unpacked = window.atob(wc_content);
                slide.wc_content = JSON.parse(unpacked);
            }
            else {
                result.valid = false;
                result.errors += 'You must generate a wordcloud image for slide number ' + (i + 1) + ' (' + slide.title + ') before saving.<br/>';
            }
        }
        else {
            var wc_content = {};

            wc_content.bounds = wordcloud_bounds['order_' + i];
            wc_content.words = [];

            $(gElement).children().each(function (i, item) {
                var wc_word = {};

                wc_word.the_word = $(item).text();
                wc_word.text_anchor = $(item).attr('text-anchor');
                wc_word.transform = $(item).attr('transform');
                wc_word.style = $(item).attr('style');

                wc_content.words.push(wc_word);

            });

            slide.wc_content = wc_content;
        }
    }

    if(!slide.title) {
      result.valid = false;
      result.errors += 'You added the slide number '+(i+1)+' ('+slide.type+') without specifying its title<br/>';
    }
    result.slides.push(slide);
  });

  return result;
}

function fix_textarea() {
  var editor = $('#form_area .wysiwyg_description').wysihtml5({
    "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
    "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
    "html": true, //Button which allows you to edit the generated HTML. Default false
    "link": true, //Button to insert a link. Default true
    "image": false, //Button to insert an image. Default true,
    "color": false, //Button to change color of font  
    "events": {
      "load": function() {
        $('.wysihtml5-sandbox').contents().find('body').on("keyup", function(event) {
          update_textarea_limit(editor);
          update_slides_data();
        });
		
        $('.wysihtml5-sandbox').contents().find('body').on("change", function(event) {
          update_slides_data();
        });
        $('.wysihtml5-sandbox').contents().find('body').on("mousedown", function(event) {
          update_slides_data();
        });
      }
    }
  });

  if($('#form_area select#indicator_id').length)
    $('#form_area select#indicator_id').select2({placeholder: "Select an Indicator"});
  $('input[maxlength]').maxlength();
}

function update_textarea_limit(editor) {
  var limit = $('#form_area textarea').attr('maxlength');
  var current = $('#form_area textarea').val().length;
  $('span.textarea_limit').removeClass('error');
  /*if(current > limit) {
    $('span.textarea_limit').addClass('error');
    //current = limit;
    //$('#form_area textarea').val( $('#form_area textarea').val().substr(0, limit) );
    //$('#form_area').find('iframe').contents().find('.wysihtml5-editor').html($('#form_area textarea').val().substr(0, limit));
  }*/
  $('.textarea_limit').remove();
  if(current > 0.8*limit)
    $('<span class="textarea_limit">'+current+' out of '+limit+' characters used!</span>').insertAfter('#form_area textarea');
  if(current > limit) {
    $('span.textarea_limit').text(current+' out of '+limit+' characters used! (the rest will be truncated)');
    $('span.textarea_limit').addClass('error');
  }
}

function slide_details_actions() {
	
  $('#storyboard_canvas').on('click', '.slide_thumb', function(e){
	  
	 var stylecheckboxupdate  = $('input[name=setstyle]').val();
	if(stylecheckboxupdate!=null){
		
		 $('input:radio[name="userstyle"]').removeAttr('checked');
		 if(stylecheckboxupdate=='link-border')
		 {
			 $('input:radio[value="link-border"]').attr('checked','checked');
		}
		else if(stylecheckboxupdate=='link-arrow')
		{
			 $('input:radio[value="link-arrow"]').attr('checked','checked');
		}
		else if(stylecheckboxupdate=='link-scaleup')
		{
			 $('input:radio[value="link-scaleup"]').attr('checked','checked');
		}
	}
	 $('#tagCompanies').html('');
	 var new_title = $(this).find('input.new_title').val();
        var new_description = $(this).find('input.new_description').val();
		   $.ajax({
      url : site_url + 'storyboard/setDescription',
      data: {'preview_description':new_description,'preview_title':new_title},
      type:"POST",
      success : function(data){
		  return true;
	  }
	  });
		var type	= $(this).find('input.template_type').val();
		var url		= $('input#get_cards_url').val().replace('get_cards', '')+'gettags';
		var cid		= $(this).find('input.content').val();
		var sid		= $('#sb_id').val();
		var styleclass	= 1;
		var taghtml = '';
	  if(type=='card') {
	  	  $.ajax( {
			url :  url,
			data: { "data":cid,"sid":sid},
			type:"POST",
			dataType: 'json',
				error : function(){
					$('.add_card').unblock();
				},
				success : function(data) {
					if(data!=null) {
					if(data.style=='link-arrow')
					{
						styleclass = 2;
					}
					else if(data.style=='link-scaleup')
					{
						styleclass = 3;
					}
					if(data.type=='column' || data.type=='area' || data.type=='line' || data.type=='tree' || data.type=='explore' || data.type=='map') {
					for(var i = 0; i < data.tags.length; i++) {
						 taghtml +="<a href='javascript:void(0);' data-wysihtml5-command='createLink' id='"+data.tags[i].term_id+"' class='btn hotspot-btn btn-primary' onclick='return select_val("+data.tags[i].term_id+",1,"+styleclass+");'>"+data.tags[i].name+"</a>"
					}
					}
					if(data.type=='combo_new') {
					for(var i = 0; i < data.tags.length; i++) {
						 taghtml +='<a href="javascript:void(0);" data-wysihtml5-command="createLink" id="'+data.tags[i].entity_id+'" class="btn hotspot-btn btn-primary" onclick="return select_val('+data.tags[i].entity_id+',2,'+styleclass+');">'+data.tags[i].company_name+'</a>'
					}
					}
					$('#tagCompanies').html(taghtml);
					
				} }			
				});
			
			
			
		//var taghtml = '<a href="javascript:void(0);" rel="<?php echo $c['info']['entity_id']; ?>"  data-wysihtml5-command='createLink' class="btn hotspot-btn btn-primary" onclick="return select_val('<?php echo $c['info']['entity_id']; ?>');"><?php echo $c['info']['company_name']; ?></a>'
	  }
    e.preventDefault();
    active_slide($(this).attr('id'));
    return false;
  });
  $('#storyboard_canvas').on('change', '#form_area form select', function(){
    $('#form_area #title').val( $('#form_area select#indicator_id option[value="'+$('#form_area select#indicator_id').val()+'"]').text() );
    update_slides_data();
  });
  $('#storyboard_canvas').on('keyup', '#form_area form input, #form_area form textarea', function(){
    update_slides_data();
  });
  $('#storyboard_canvas').on('change', '#form_area form input, #form_area form textarea', function(){
    update_slides_data();
  });
  $('#storyboard_canvas').on('mousedown', '#form_area form input, #form_area form select, #form_area form textarea', function(){
    update_slides_data();
  });
}

function update_slides_data(update_media_url) {
  //alert('changing');
  //we update the active slide data from the form
  var active_slide = $('.slide_thumb.active');
  var active_slide_type = false;
  if(active_slide.attr('id') == 'start_slide')
    active_slide_type = 'start_slide';
  else if(active_slide.attr('id') == 'end_slide')
    active_slide_type = 'end_slide';
  else if(active_slide.find('input.template_type').val())
    active_slide_type = active_slide.find('input.template_type').val();

  if(active_slide_type == 'start_slide') {
    active_slide.find('span.preview_title').text( cut_string($('#form_area form #title').val(), 25) );
    active_slide.find('input.title').val( $('#form_area form #title').val() );
    active_slide.find('input.description').val( $('#form_area form #description').val() );
    active_slide.find('input.image').val( $('#form_area form input#uploaded_image').val() );
  }
  if(active_slide_type == 'end_slide') {
    active_slide.find('input.callToAction').val( $('#form_area form #description').val() );
    var avatar = 'false';
    if ($('#form_area form #show_avatar').attr("checked"))
        avatar = 'true';
    active_slide.find('input.avatar').val( avatar );
    var add_link = 'false';
    if ($('#form_area form #add_link_checkbox').attr("checked"))
        add_link = 'true';
    active_slide.find('input.has_link').val( add_link );
    active_slide.find('input.link_url').val( $('#form_area form #link_url').val() );
    active_slide.find('input.link_name').val( $('#form_area form #link_name').val() );
  }
  if(active_slide_type == 'card') {
    active_slide.find('span.preview_title').text( cut_string($('#form_area form #title').val(), 25) );
    active_slide.find('input.new_title').val( $('#form_area form #title').val() );
    active_slide.find('input.new_description').val( $('#form_area form #description').val() );
  }
  if(active_slide_type == 'wordcloud') {
    active_slide.find('span.preview_title').text( cut_string($('#form_area form #title').val(), 25) );
    active_slide.find('input.title').val( $('#form_area form #title').val() );
    active_slide.find('input.content').val( $('#form_area form #wordtext').val() );
    active_slide.find('input.description').val( $('#form_area form #description').val() );
    active_slide.find('input.wordtext').val( $('#form_area form #wordtext').val() );

    active_slide.find('input.wc_type').val( $('#form_area form input[name=wc_type]:checked').val() );

  }
  if(active_slide_type == 'media') {
    var uploaded_image = $('#form_area form input#uploaded_image').val();
    if(uploaded_image && update_media_url) 
      $('#form_area form #url').val($('#form_area form input#uploaded_image').val());

    active_slide.find('span.preview_title').text( cut_string($('#form_area form #title').val(), 25) );
    active_slide.find('input.content').val( $('#form_area form #url').val() );
    active_slide.find('input.title').val( $('#form_area form #title').val() );
    active_slide.find('input.description').val( $('#form_area form #description').val() );
    if(!update_media_url)
      update_media_preview(true);
  }
  if(active_slide_type == 'indicator') {
    active_slide.find('span.preview_title').text( cut_string($('#form_area form #title').val(), 25) );
    active_slide.find('input.content').val( $('#form_area form #indicator_id').val() );
    active_slide.find('input.title').val( $('#form_area form #title').val() );
    active_slide.find('input.description').val( $('#form_area form #description').val() );
  }
}

function update_form(id) {
	$('#tagCompanies').hide();
  var index = false;
  if(id == 'start_slide')
    index = 'start';
  if(id == 'end_slide')
    index = 'end';
  if($('#'+id).find('input.template_type').val() == 'card')
    index = 'card';
  if($('#'+id).find('input.template_type').val() == 'indicator')
    index = 'indicator';
  if($('#'+id).find('input.template_type').val() == 'media')
    index = 'media';
    if($('#'+id).find('input.template_type').val() == 'wordcloud')
        index = 'wordcloud';

  if(index) {

    $('#form_area form').empty().append($('#slides_forms #'+index+'_slide_form').html());

    if(index == 'start') {
      $('#form_area form input#title').val($('#start_slide input.title').val());
      $('#form_area form textarea#description').val($('#start_slide input.description').val());
      $('#form_area form input#uploaded_image').val($('#start_slide input.image').val());
      image_upload_actions();
    }
    if(index == 'end') {
      $('#form_area form textarea#description').val($('#end_slide input.callToAction').val());
      if($('#end_slide input.avatar').val() == 'true')
        $('#form_area form input#show_avatar').attr('checked', 'checked');
      else
        $('#form_area form input#show_avatar').removeAttr('checked');
      if($('#end_slide input.has_link').val() == 'true')
        $('#form_area form input#add_link_checkbox').attr('checked', 'checked');
      else
        $('#form_area form input#add_link_checkbox').removeAttr('checked');
      $('#form_area form input#link_name').val($('#end_slide input.link_name').val());
      $('#form_area form input#link_url').val($('#end_slide input.link_url').val());
      add_link_enable_button();
    }

      //var genBtn = document.getElementById('gen_wordcloud');

      if(index == 'wordcloud') {
        $('#form_area form input#title').val($('#'+id+' input.title').val());
        $('#form_area form textarea#description').val($('#'+id+' input.description').val());
        $('#form_area form textarea#wordtext').val($('#'+id+' input.wordtext').val());

        var wc_type_val = $('#'+id+' input.wc_type').val();
        $('#form_area form input[name=wc_type][value='+wc_type_val+']').attr('checked', true);

        $('#action_buttons #gen_wordcloud').removeClass('hidden');

          var wc_content = $('#'+id+' input.wc_content').val();

          // only do this on an existing wc slide
          if (wc_content && !wordcloud_img_cmds[id]) {

              svg = create_svg_and_vis();

              $('#form_area #vis').append(svg);

              g = $('#svgG').get(0);

              // children get appended to the vis element
              build_wc_commands(id, wc_content, g);

              wordcloud_img_cmds[id] = svg;
          }
          else if (wordcloud_bounds[id]) {
              wc_set_transform_and_scale(wordcloud_img_cmds[id], $('#form_area #vis').width(), $('#form_area #vis').height(), id);
          }

          $('#form_area #vis').append(wordcloud_img_cmds[id]);
      }
      else {
          $('#action_buttons #gen_wordcloud').addClass('hidden');
      }

    if(index == 'card') {
	 $('#tagCompanies').show();
      $('#form_area form input#title').val($('#'+id+' input.new_title').val());
      $('#form_area form textarea#description').val($('#'+id+' input.new_description').val());
      var src= $('input#get_cards_url').val().replace('/storyboard/get_cards', '') + '/card/embed/'+$('#'+id).find('input.guid').val();
      $('#form_area #iframe_preview').html('<iframe frameborder="0" src="'+src+'/edit" style="width:100%; height:590px;" />');

    }
    if(index == 'indicator') {
      $('#form_area form #indicator_id').val($('#'+id+' input.content').val());
      $('#form_area form input#title').val($('#'+id+' input.title').val());
      $('#form_area form textarea#description').val($('#'+id+' input.description').val());
    }
    if(index == 'media') {
      $('#form_area form input#title').val($('#'+id+' input.title').val());
      $('#form_area form textarea#description').val($('#'+id+' input.description').val());
      $('#form_area form input#url').val($('#'+id+' input.content').val());
      if(is_img($('#'+id+' input.content').val()))
        $('#form_area form input#uploaded_image').val($('#'+id+' input.content').val());
      image_upload_actions(true);
    }
    fix_textarea();
  }
  else
    $('#form_area form').empty();
}

function update_index_position(id) {
  if($('.slide_thumb.active').length && $('#'+id).length) {
    $('#slide_index').show();
    var parent_pos = $('#sequencer').offset();
    var slide_pos = $('#'+id).offset();
    $('#slide_index').show().css({
      'left': (slide_pos.left - parent_pos.left + 62) + 'px'
    });
  }
  else
    $('#slide_index').hide();
}

function add_card_to_sequencer() {
  $('#storyboard_canvas').on('click', '.select_deck_card', function(e){
    if($('input.iosblue').prop('checked'))
      deck_switch.toggle();
    e.preventDefault();
    var card_id = $(this).parents('li').attr('id');
    var id = card_id.replace('card_', '');
    var guid = $('#'+card_id).find('input.deck_card_guid').val();
    //if the card is not used
    var cards = $('#other_slides_container .created_thumb input.template_type[value="card"]').parent();
    if(cards.find('input.content[value="'+id+'"]').length == 0) {
      var existing_cards = $('#other_slides_container .created_thumb input.template_type[value="card"]');
      if(existing_cards.length == 0) {
        insert_new_card_slide(card_id, guid);
      }
      else {
        var empty_cards = existing_cards.parent().find('input.content[value=""]');
        if(empty_cards.length) {
          var slide_id = $(empty_cards[0]).parent().attr('id');
          edit_existing_card_slide(card_id, slide_id, guid);
        }
        else
          insert_new_card_slide(card_id, guid); 
      }
    }
    else { //we show a modal window
      $('#add_card_error_modal').modal();
    }
    return false;
  });
}

function insert_new_card_slide(card_id, guid) {
	
  var type = 'card';
  var img_src = $('#'+card_id+' img:first-child').attr('src');
  var card_type = $('#'+card_id+' .deck_card_type').val();
  var start_pos = $('#'+card_id+' img:first-child').offset();
  var slide = create_slide_in_sequenecr(type, DEFAULT_TEMPLATE, $('#'+card_id+' h4').text(), guid,card_type,card_id);
  var end_pos = slide.offset();

  slide.css('opacity', 0);
  //simulating the animation
  var start_w = 190, start_h = 128, end_w = 120, end_h = 102;

  var temp_img = $('<img id="temp_img" src="'+img_src+'" />').css({
      'position':'absolute', 
      'top': start_pos.top+'px', 
      'left': start_pos.left+'px', 
      'width': start_w+'px', 
      'height': start_h+'px'
    }).appendTo('body').animate({
      'top': (end_pos.top+8)+'px', 
      'left': (end_pos.left+4)+'px', 
      'width': end_w+'px', 
      'height': end_h+'px'
    }, 800, function(){
      slide.find('input.content').val(card_id.replace('card_', ''));
      slide.find('input.title, input.new_title').val($('#'+card_id+' h4').attr('data-title'));
      slide.find('input.description, input.new_description').val($('#'+card_id+' p.description').attr('data-desc'));
      temp_img.appendTo(slide.find('a:first-child')).css({'top': '', 'left': ''}).addClass('pos_'+DEFAULT_TEMPLATE);
      active_slide(slide.attr('id'));
    });
    slide.animate({'opacity':1}, 800);
  return false;
}

function edit_existing_card_slide(card_id, slide_id, guid) {
  var type = 'card';
  var img_src = $('#'+card_id+' img:first-child').attr('src');
  var start_pos = $('#'+card_id+' img:first-child').offset();
  var slide = $('#'+slide_id);
  var end_pos = slide.offset();
  var template_number = slide.find('input.template_number').val(), l = 4;
  if(template_number == 4)
    l = 58;

  slide.css('opacity', 0);
  //simulating the animation
  var start_w = 190, start_h = 128, end_w = 120, end_h = 101;

  var temp_img = $('<img id="temp_img" src="'+img_src+'" />').css({
      'position':'absolute', 
      'top': start_pos.top+'px', 
      'left': start_pos.left+'px', 
      'width': start_w+'px', 
      'height': start_h+'px'
    }).appendTo('body').animate({
      'top': (end_pos.top+8)+'px', 
      'left': (end_pos.left+l)+'px', 
      'width': end_w+'px', 
      'height': end_h+'px'
    }, 800, function(){
      slide.find('input.content').val(card_id.replace('card_', ''));
      slide.find('input.title').val($('#'+card_id+' h4').attr('data-t'));
      slide.find('input.description').val($('#'+card_id+' p.description').attr('data-desc'));
      temp_img.appendTo(slide.find('a:first-child')).css({'top': '', 'left': ''}).addClass('pos_'+template_number);
    });
    slide.animate({'opacity':1}, 800);
  return false;
}

function remove_slide_actions() {
  $('#storyboard_canvas').on('mouseenter', '.created_thumb', function(){
    $(this).find('a.remove_slide').show();
  });

  $('#storyboard_canvas').on('mouseleave', '.created_thumb', function(){
    $(this).find('a.remove_slide').hide();
  });

  $('#storyboard_canvas').on('click', '.remove_slide', function(e){
    hide_elements();
    var slide_id = $(this).parent().attr('id');
    $('#remove_slide_modal button.removeSlide').attr('data-id', slide_id);
    $('#remove_slide_modal').modal();
    return false;
  });

  $('body').on('click', '#remove_slide_modal button.removeSlide', function(e){
    hide_elements();
    $('#'+$(this).attr('data-id')).remove();
    update_slides_ids();
    set_sequencer_size();
    active_slide();
    $('#remove_slide_modal').modal('hide');
  });
}

function edit_slide_actions() {
  $('#storyboard_canvas').on('mouseenter', '.created_thumb', function(){
    $(this).find('a.edit_template').show();
  });

  $('#storyboard_canvas').on('mouseleave', '.created_thumb', function(){
    $(this).find('a.edit_template').hide();
  });

  $('#storyboard_canvas').on('click', '.created_thumb', function(){
    active_slide($(this).attr('id'));
    if($('#select_slide_template_preview').hasClass('visible'))
      $('#select_slide_template_preview').hide().removeClass('visible');
    
    if($('#select_start_end_template_preview').hasClass('visible'))
      $('#select_start_end_template_preview').hide().removeClass('visible');

    return false;
  });

  $('#storyboard_canvas').on('click', '.created_thumb a.edit_template', function(){
    active_slide($(this).parent().attr('id'));
    if($('#select_slide_template_preview').hasClass('visible'))
      $('#select_slide_template_preview').hide().removeClass('visible');
  
    calculate_select_slide_position($(this).parent());
    slide_status = 'overwrite_existing';
    current_slide = $(this).parent().attr('id');
    current_slide_type = $(this).parent().find('input.template_type').val();
  
    $('#select_slide_template_preview').removeClass('bottom');
    var top = $(this).parent().offset();
    top = top.top;
    if(top < 400)
      $('#select_slide_template_preview').addClass('bottom');
    $('#select_slide_template_preview').show().addClass('visible');
    //we only display the cards of the same type
    $('#select_slide_template_preview .select_template').hide();
    $('#select_slide_template_preview .select_template.'+current_slide_type+'_template').show();

    var type = current_slide_type;
    var number = parseInt($(this).parent().find('input.template_number').val());
    $('#select_slide_template_preview a').removeClass('active');
    $('#select_slide_template_preview a#select_'+type+'_'+number).addClass('active');
    

    if($('#select_start_end_template_preview').hasClass('visible'))
      $('#select_start_end_template_preview').hide().removeClass('visible');

    return false;
  });
}

function add_new_slide_actions() {
  $('#storyboard_canvas').on('click', '#add_new_slide a', function(){
    calculate_select_slide_position($(this).parent());

    slide_status = 'create_new';
    $('#select_slide_template_preview a').removeClass('active');
    if($('#select_slide_template_preview').hasClass('visible'))
      $('#select_slide_template_preview').hide().removeClass('visible');
    else {
      $('#select_slide_template_preview').show().addClass('visible');
      $('#select_slide_template_preview .select_template').show();
      $('#select_slide_template_preview .select_template.card_template').hide();
      $('#select_slide_template_preview').removeClass('bottom');
      var top = $(this).parent().offset();
      top = top.top;
      if(top < 400)
        $('#select_slide_template_preview').addClass('bottom');
    }

    if($('#select_start_end_template_preview').hasClass('visible'))
      $('#select_start_end_template_preview').hide().removeClass('visible');

    return false;
  });

  $('#storyboard_canvas').on('click', '#select_slide_template_preview a.select_template', function(){
    //we get the chosen template number and type
    var number_type = $(this).attr('id').replace('select_','').split('_');
    var type = number_type[0];
    var number = number_type[1];
    var src = $(this).find('img').attr('src');
    var current_index = $('#other_slides_container .slide_thumb').length - 1;

    $('#select_slide_template_preview a').removeClass('active');

    if(slide_status == 'create_new' || current_slide == '')
    {
      //we create the new slide
      create_slide_in_sequenecr(type, number);
    } else {
      // we update the current slide
      if(type == 'card')
        $('#'+current_slide+' img#temp_img').removeClass('pos_2').removeClass('pos_4').addClass('pos_'+number);
      $('#'+current_slide+' img:first-child').attr('src', src);
      $('#'+current_slide+' input.template_type').val(type);
      $('#'+current_slide+' input.template_number').val(number);
      current_slide = '';
    }
    $('#select_slide_template_preview').hide().removeClass('visible');
    return false;
  });
}

function create_slide_in_sequenecr(type, number, title, guid,card_type,card_id) {
  if(!title)
    title = '';
  if(!guid)
    guid = '';
  var src = $('#select_slide_template_preview #select_'+type+'_'+number+' img').attr('src');
  var current_index = $('#other_slides_container .slide_thumb').length - 1;
  var slide = '<div class="slide_thumb created_thumb" id="order_'+current_index+'">'+
                '<a href="#">'+
                ' <img src="'+src+'" alt="'+type+'" width="180" height="116" />'+
                '</a>'+
                '<input type="hidden" value="'+type+'" class="template_type"/>'+
                '<input type="hidden" value="'+number+'" class="template_number"/>'+
                '<input type="hidden" value="" class="content"/>'+
                '<input type="hidden" value="'+guid+'" class="guid"/>'+
                '<input type="hidden" value="" class="title"/>'+
				'<input type="hidden" class="stylechart" value="'+card_type+'">'+
                '<input type="hidden" value="" class="description"/>'+
                '<input type="hidden" value="" class="wordtext"/>'+
                '<input type="hidden" value="" class="new_title"/>'+
                '<input type="hidden" value="" class="new_description"/>'+
                '<input type="hidden" value="" class="wc_type"/>'+
                '<a class="remove_slide" href="#" title="remove this slide from storyboard" data-target="#remove_slide_modal" data-toggle="modal" ><i class="fa fa-minus-circle"></i></a>'+
                '<a class="edit_template" href="#" title="Select Template for the Slide" ><i class="fa fa-gear"></i></a>'+
                '<a class="move_slide_left" title="move slide to the left" href="#" ><i class="fa fa-caret-square-o-left"></i></a>'+
                '<a class="move_slide_right" title="move slide to the right" href="#" ><i class="fa fa-caret-square-o-right"></i></a>'+
                '<a class="preview_slide" title="preview slide" href="#" ><i class="fa fa-external-link-square"></i></a>'+
                '<span class="preview_title">'+title+'</span>'+
              '</div>';
			 
		var url		= $('input#get_cards_url').val().replace('get_cards', '')+'gettags';
		if(card_id!== undefined)
		{
		var cid		= card_id.replace('card_', '');
		}
		var sid		= $('#sb_id').val();
		var styleclass	= 1;
		var taghtml = '';
	  if(type=='card') {
	  	  $.ajax( {
			url :  url,
			data: { "data":cid,"sid":sid},
			type:"POST",
			dataType: 'json',
				error : function(){
					$('.add_card').unblock();
				},
				success : function(data) {
					if(data!=null) {
					if(data.style=='link-arrow')
					{
						styleclass = 2;
					}
					else if(data.style=='link-scaleup')
					{
						styleclass = 3;
					}
						if(data.type=='column' || data.type=='area' || data.type=='line' || data.type=='tree' || data.type=='explore' || data.type=='map') {
							for(var i = 0; i < data.tags.length; i++) {
								 taghtml +="<a href='javascript:void(0);' data-wysihtml5-command='createLink' id='"+data.tags[i].term_id+"' class='btn hotspot-btn btn-primary' onclick='return select_val("+data.tags[i].term_id+",1,"+styleclass+");'>"+data.tags[i].name+"</a>"
							}
						}
						if(data.type=='combo_new') {
							for(var i = 0; i < data.tags.length; i++) {
								 taghtml +='<a href="javascript:void(0);" data-wysihtml5-command="createLink" id="'+data.tags[i].entity_id+'" class="btn hotspot-btn btn-primary" onclick="return select_val('+data.tags[i].entity_id+',2,'+styleclass+');">'+data.tags[i].company_name+'</a>'
							}
						}
					$('#tagCompanies').html(taghtml);
					
				} 
				else{
				
					$('#tagCompanies a').remove();
				}
				}
							
				});
			
			
			
		//var taghtml = '<a href="javascript:void(0);" rel="<?php echo $c['info']['entity_id']; ?>"  data-wysihtml5-command='createLink' class="btn hotspot-btn btn-primary" onclick="return select_val('<?php echo $c['info']['entity_id']; ?>');"><?php echo $c['info']['company_name']; ?></a>'
	  }
  slide = $(slide).insertBefore('#add_new_slide');
  set_sequencer_size();
  active_slide('order_'+current_index);
  return slide;
}

function slide_position_actions() {
  //scroll arrows
  $('#storyboard_canvas').on('click', '#scrollRight', function(){
    scroll_slides('right');
    hide_elements();
    active_slide();
    return false;
  });
  $('#storyboard_canvas').on('click', '#scrollLeft', function(){
    scroll_slides('left');
    hide_elements();
    active_slide();
    return false;
  });

  $('#storyboard_canvas').on('mouseenter', '.created_thumb', function(){
    var n = $('.created_thumb').length;
    var pos = $(this).attr('id').replace('order_', '');
    if(n > 1) {
      if(pos  == 0) {
        $(this).find('a.move_slide_left').hide();
        $(this).find('a.move_slide_right').show();
      }
      if(pos == n - 1) {
        $(this).find('a.move_slide_left').show();
        $(this).find('a.move_slide_right').hide(); 
      }
      if(pos > 0 && pos < n-1) {
        $(this).find('a.move_slide_left').show();
        $(this).find('a.move_slide_right').show();
      }
    }
  });

  $('#storyboard_canvas').on('mouseleave', '.created_thumb', function(){
    $(this).find('a.move_slide_left').hide();
    $(this).find('a.move_slide_right').hide();
  });

  $('#storyboard_canvas').on('click', '.move_slide_left', function(e){
    hide_elements();
    var elm = $(this).parent();
    elm.insertBefore(elm.prev());
    update_slides_ids();
    return false;
  });

  $('#storyboard_canvas').on('click', '.move_slide_right', function(e){
    hide_elements();
    var elm = $(this).parent();
    elm.insertAfter(elm.next());
    update_slides_ids();
    return false;
  });
}

function update_slides_ids() {
  $('.created_thumb').each(function(i){
    $(this).attr('id', 'order_'+i);
    $(this).find('a.move_slide_left').hide();
    $(this).find('a.move_slide_right').hide();
    if($(this).hasClass('active'))
      active_slide($(this).attr('id'));
  });
}

function start_end_slide_actions() {
  $('#storyboard_canvas').on('mouseenter', '#start_slide', function(){
    $(this).find('a.edit_template').show();
  });

  $('#storyboard_canvas').on('mouseleave', '#start_slide', function(){
    $(this).find('a.edit_template').hide();
  });

  $('#storyboard_canvas').on('click', '#start_slide', function(){
    active_slide($(this).attr('id'));
    if($('#select_slide_template_preview').hasClass('visible'))
      $('#select_slide_template_preview').hide().removeClass('visible');
    
    if($('#select_start_end_template_preview').hasClass('visible'))
      $('#select_start_end_template_preview').hide().removeClass('visible');

    return false;
  });

  $('#storyboard_canvas').on('click', '#start_slide a.edit_template', function(){
    if($('#select_start_end_template_preview').hasClass('visible'))
      $('#select_start_end_template_preview').hide().removeClass('visible');

    $('#select_start_end_template_preview').removeClass('bottom');
    var top = $(this).parent().offset();
    top = top.top;
    if(top < 400)
      $('#select_start_end_template_preview').addClass('bottom');
    $('#select_start_end_template_preview').show().addClass('visible');

    if($('#select_slide_template_preview').hasClass('visible')) 
      $('#select_slide_template_preview').hide().removeClass('visible');

    active_slide($(this).parent().attr('id'));
    return false;
  });

  $('#storyboard_canvas').on('click', '#select_start_end_template_preview a.select_template', function(){
    //we get the chosen template number
    var template_number = $(this).attr('id').replace('select_template_','');
    $('input#start_end_slide').val(template_number);
    $('#select_start_end_template_preview a').removeClass('active');
    $(this).addClass('active');
    //we update the start slide
    var src = $('#start_slide a#select_start_end_templates img').attr('src');
    src = src.replaceIndex(src.lastIndexOf('.'), template_number);
    $('#start_slide a#select_start_end_templates img').attr('src', src);
    //we update the end slide
    src = $('#end_slide a#edit_end_slide img').attr('src');
    src = src.replaceIndex(src.lastIndexOf('.'), template_number);
    $('#end_slide a#edit_end_slide img').attr('src', src);

    $('#select_start_end_template_preview').hide().removeClass('visible');
    return false;
  });
}

function filters_actions() {
  //filter cards while typing
  $('#storyboard_canvas').on('keyup', 'input#filter_cards', function(){
    clearTimeout(filter_timer);
    filter_timer = setTimeout(function(){
      filter_cards($('input#filter_cards').val().toLowerCase());
    }, 500);
  });

  //checkboxes filters
  $('#storyboard_canvas').on('change', '#cards_control input.checkbox', function(){
    if($(this).attr('checked')) {
      fill_cards_deck($(this).attr('id'));
    }
    else{
      $('#baraja-el li.deck_card.'+$(this).attr('id') + ', #baraja_removed_cards li.deck_card.'+$(this).attr('id')).each(function(){
        baraja.remove('#'+$(this).attr('id'));
        $('#baraja_removed_cards #'+$(this).attr('id')).remove();
		console.log(baraja.itemsCount);
		if(baraja.itemsCount<=10){
		   $('#spread_cards').show();
	    }else{
	      $('#spread_cards').hide();
	    }
      });
    }
	if($('input.iosblue').prop('checked'))
      deck_switch.toggle();
  });
}

function fill_cards_deck(type) {
  var url = $('input#get_cards_url').val();
  $('#cards_control input.checkbox').attr('disabled', 'disabled');

  $.ajax({
      type: "GET",
      url: url+"/"+type,
      success: function(response) {
        if(response) {
          var wait_time = $(response).length * 30;
          baraja.add($(response));
          filter_timer = setTimeout(function(){
            $('#cards_control input.checkbox').removeAttr('disabled');
			//$('input#filter_cards').val('');
			$('input#filter_cards').keyup();
          }, wait_time);
        }
        else
          $('#cards_control input.checkbox').removeAttr('disabled');
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        $('#cards_control input.checkbox').removeAttr('disabled');
      }
  });
}

function set_default_filter() {
  $('#cards_control #filter_cards').val('');
  $('#cards_control #my_cards').attr('checked', 'checked');
  //$('#cards_control #shared_cards').attr('checked', 'checked');
  //$('#cards_control #public_cards').attr('checked', 'checked');
}

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
}

function calculate_select_slide_position(elmt) {
  var elmt_pos = elmt.position();
  elmt_pos.left += 225 + current_left;
  if(elmt_pos.left + 398 < $('#sequencer').width()) {
    $('#select_slide_template_preview').css('left', elmt_pos.left+'px');
    $('#select_slide_template_preview .up_triangle').css('left', (75)+'px');
  }
  else {
    var new_left = $('#sequencer').width() - 398;
    if(new_left < 0)
      new_left = 0;
    $('#select_slide_template_preview').css('left', new_left+'px');  
    $('#select_slide_template_preview .up_triangle').css('left', (75+elmt_pos.left-new_left)+'px');  
  }
}

function create_cards_deck(baraja) {

  var $el = $( '#baraja-el'),
  baraja = $el.baraja();
  if(baraja.itemsCount<=10){
	  baraja.fan( {
		  speed : 500,
		  easing : 'ease-out',
		  range : 100,
		  direction : 'right',
		  origin : { x : 50, y : 150 },
		  center : true
	  });
  }
  // navigation
  $( '#nav-prev' ).on( 'click', function( event ) {
    baraja.previous();
    if($('input.iosblue').prop('checked'))
      deck_switch.toggle();
  } );

  $( '#nav-next' ).on( 'click', function( event ) {
    baraja.next();
    if($('input.iosblue').prop('checked'))
      deck_switch.toggle();
  } );

  $( '#storyboard_canvas' ).on( 'click', '#baraja-el li.deck_card', function( event ) {
    if($('input.iosblue').prop('checked'))
      deck_switch.toggle();
  } );

  var Switch = require('ios7-switch'), checkbox = document.querySelector('.iosblue');
  deck_switch = new Switch(checkbox);
  if(baraja.itemsCount<=10){
 	deck_switch.toggle();
  }else{
	  $('#spread_cards').hide();
  }
  deck_switch.el.addEventListener('click', function(e){
    e.preventDefault();
    deck_switch.toggle();
    if($('input.iosblue').prop('checked')) { // we open the deck
      baraja.fan( {
        speed : 500,
        easing : 'ease-out',
        range : 100,
        direction : 'right',
        origin : { x : 50, y : 150 },
        center : true
      } );
    }  
    else
      baraja.close();   
  });

    //hovering on cards
  $('#storyboard_canvas').on('mouseenter', '#baraja-el li.deck_card', function(){
    if($(this).css('transform') == 'none')
      $(this).find('.deck_card_action').show();
  });
  /*
  $('#storyboard_canvas').on('mouseleave', '#baraja-el li.deck_card', function(){
      $(this).find('.deck_card_action').hide();
  });*/

  $('#storyboard_canvas').on('click', '.preview_deck_card', function(e){
    e.stopPropagation();
    var src = $(this).attr('data-remote');
    var height = 0.8*$(window).height();
    $('#preview_card_modal .modal-body').css('height', height).empty();
    $('<iframe src="'+src+'" frameborder="0" style="width:100%; height:100%;"></iframe>').appendTo('#preview_card_modal .modal-body');
    $('#preview_card_modal').modal();
    //baraja.close();
    return false;
  });

  //collapse/expand deck
  $('#storyboard_canvas').on('click', 'a#toggle_collapse', function(){
    hide_elements();
    if($('a#toggle_collapse i.fa').hasClass('fa-chevron-up')) {
      deck_height = $('#cards_deck').height();
      deck_state = 'closed';
      $('#cards_deck').animate({'paddingTop':'40px', 'height':'40px'}, 500, function(){
        $('#cards_control').css('top','45px');
        $('a#toggle_collapse i.fa').removeClass('fa-chevron-up').addClass('fa-chevron-down');
      });
    }
    else {
      $('#add_new_slide').show();
      deck_state = 'open';
      $('#cards_deck').animate({'paddingTop':'0px', 'height':deck_height+'px'}, 500, function(){
        $('#cards_control').css('top','15px');
        $('a#toggle_collapse i.fa').removeClass('fa-chevron-down').addClass('fa-chevron-up');
      });
    }
    return false;
  });

  return baraja;
}

function filter_cards(text) {
  //get all elements ID and text
  var existing_elmts = $('#baraja-el li.deck_card');
  var removed_elmts = $('#baraja_removed_cards li.deck_card');
  var all_elmts = [];
  var elements_to_add = '';
  var obj;
  for(var i = 0; i < existing_elmts.length; i++) {
    var cur_elmt = existing_elmts.get(i);
    obj = {};
    obj.id = $(cur_elmt).attr('id');
    obj.exists = true;
    obj.text = $(cur_elmt).find('h4').attr('data-title') + ' ' + $(cur_elmt).find('p.description').attr('data-desc') + ' ' + $(cur_elmt).find('span.author').text();
    obj.text = obj.text.toLowerCase();
    all_elmts.push(obj);
  }
  for(var i = 0; i < removed_elmts.length; i++) {
    var cur_elmt = removed_elmts.get(i);
    obj = {};
    obj.id = $(cur_elmt).attr('id');
    obj.exists = false;
    obj.text = $(cur_elmt).find('h4').attr('data-title') + ' ' + $(cur_elmt).find('p.description').attr('data-desc').toLowerCase();
    all_elmts.push(obj);
  }
  //var filtered_elmts = [];
  //filter all elements for the desired text
  for(var i = 0; i < all_elmts.length; i++) {
    //if text doesn't exist
    if(all_elmts[i].text.search(text) == -1 && all_elmts[i].exists == true) {
      var domElmt = $('#'+all_elmts[i].id).wrap('<p/>').parent().html();
      $('#'+all_elmts[i].id).unwrap();
      baraja.remove('#'+all_elmts[i].id);
      $('#baraja_removed_cards').append(domElmt);
    }
    if(all_elmts[i].text.search(text) != -1 && all_elmts[i].exists == false) {
      var domElmt = $('#'+all_elmts[i].id).removeAttr('style').wrap('<p/>').parent().html();
      $('#'+all_elmts[i].id).unwrap().remove();
      elements_to_add += domElmt;
    }
  }
  if(elements_to_add){
    baraja.add($(elements_to_add));
  }
  if($('input.iosblue').prop('checked')){
      deck_switch.toggle();
  }
  setTimeout(function(){
	  console.log(baraja.itemsCount);
	  if(baraja.itemsCount<=10){
		$('#spread_cards').show();
	  }else{
		$('#spread_cards').hide();
	  }
  }, 1000);
}

function hide_elements() {
  if($('#select_start_end_template_preview').hasClass('visible')) {
    $('#select_start_end_template_preview').hide().removeClass('visible');
    return false;
  }
  if($('#select_slide_template_preview').hasClass('visible')) {
    $('#select_slide_template_preview').hide().removeClass('visible');
    return false;
  }
}

function set_sequencer_size() {
  var n = $('#other_slides_container .slide_thumb').length;
  var slides_width = n*thumb_width;
  var view_port_width = $('#sequencer').width() - 440;
  $('#other_slides_container').css('width', slides_width+'px');
  $('#other_slides').css('width', view_port_width+'px');
  update_scroll_buttons(current_left, view_port_width, slides_width);
}

function active_slide(id) {
  $('.slide_thumb').removeClass('active');
  if(id && $('#'+id).length) {
    if(id != 'start_slide' && id != 'end_slide') {
      var pos = $('#'+id).position(), w = $('#other_slides_container').width(), vp = $('#other_slides').width(), l = parseInt($('#other_slides_container').css('left').replace('px', ''));
      pos = pos.left;

      if(pos + l + 188 > vp) {
        var new_left = vp - pos - 188;
        $('#other_slides_container').css({'left': new_left+'px'});
        update_scroll_buttons(new_left, vp, w);
      }
    }
    $('#'+id).addClass('active'); 
  }
    
  update_index_position(id);
  update_form(id);
}

String.prototype.replaceIndex = function( idx, s ) {

    return (this.slice(0,idx-1) + s + this.slice(idx));
};

function cut_string(s, n) {

  return (s.length > n) ? (s.substr(0,(n-3))+'...') : s;
}

function is_img(str) {
  var extension = str.split('.').pop().toLowerCase(); 
  if(extension == 'jpg' || extension == 'jpeg' || extension == 'png' || extension == 'gif')
    return true;
  else
    return false;
}

function get_youtube_id_from_url(url) {
  var videoid = url.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);
  if(videoid != null) {
    return videoid[1];
  } else { 
      return false;
  }
}

function get_vimeo_id_from_url(url) {
  var match = /vimeo.*\/(\d+)/i.exec( url );
  if (match) 
    return match[1];
  else
    return false;
}

function vimeoLoadingThumb(id){    
    var url = "http://vimeo.com/api/v2/video/" + id + ".json?callback=showThumb";
      
    var id_img = "#vimeo-" + id;
    var script = document.createElement( 'script' );
    script.type = 'text/javascript';
    script.src = url;

    $(id_img).before(script);
}

function showThumb(data){
    var id_img = "#vimeo-" + data[0].id;
    $(id_img).attr('src',data[0].thumbnail_medium);
}

function add_link_enable_button() {
    if ($("#add_link_checkbox").attr('checked')) {
        $('.form_content #link_url, .form_content #link_name').removeAttr('disabled');
    }
    else {
        $('.form_content #link_url, .form_content #link_name').attr('disabled', 'disabled');
    }

    $('body').on('change', '#add_link_checkbox', function() {
        if ($(this).attr('checked')) {
            $('.form_content #link_url, .form_content #link_name').removeAttr('disabled');
        }
        else {
            $('.form_content #link_url, .form_content #link_name').attr('disabled', 'disabled');
        }
    });
}

function valid_url(url) {
  if(/^([a-z]([a-z]|\d|\+|-|\.)*):(\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?((\[(|(v[\da-f]{1,}\.(([a-z]|\d|-|\.|_|~)|[!\$&'\(\)\*\+,;=]|:)+))\])|((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=])*)(:\d*)?)(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*|(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)){0})(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url)) {
    return true;
  } else {
    return false;
  }
}

function setStyleVal(val){
	$('input[name=setstyle]').val(val);
	}

