<?php

$folder =   dirname(dirname(__FILE__));

$has_link = 'false';
$link_name = '';
$link_url = '';
if ( !empty($op) && $op == "edit_") {
  $id = $obj->id;
  $title = $obj->title;
  $description = $obj->description;
  $start_end_template = $obj->start_end_template;
  $start_image = $obj->start_image;
  $end_text = $obj->end_text;
  $end_avatar = ($obj->end_avatar)? 'true':'false';
  if(!empty($obj->end_link) && !empty($obj->end_link_name)) {
    $has_link = 'true';
    $link_name = $obj->end_link_name;
    $link_url = $obj->end_link;
  }
  $style = $obj->style;
} else {
  $obj = '';
  $id = '';
  $title ='';
  $description = '';
  $start_end_template = 1;
  $start_image = '';
  $end_text = '';
  $end_avatar = 'false';
  $style = 'link-scaleup';
}

//var_dump($all_cards);
?>


<div class="storyboard-form">
  <div class="clearfix"></div>
  <div class="content">

  	<div class="page-title">
      <a href="<?php echo site_url('/storyboard/my_storyboards');?>"><i class="icon-custom-left"></i></a>
      <?php 
        if($op == "edit_"){
          echo '<h3>Edit <span class="semi-bold">Storyboard</span>: '.$title.'</h3>';
        } else {
          echo '<h3>Add <span class="semi-bold">New Storyboard</span></h3>';
            }
            ?>
  	</div>

    <div id="storyboard_canvas" class="row <?php echo ($op == 'edit_')?'edit':'add';   ?>" >
      <input type="hidden" id="get_cards_url" value="<?php echo site_url('storyboard/get_cards/'); ?>" />
            <!--
                preview_storyboard_code
            <input type="hidden" id="sb_preview_id" value="0" />; -->
      <?php
        if($op == "edit_"){
          echo ' <input type="hidden" id="sb_id" value="'.$id.'" />';
        }
      ?>
  		<div class="grid simple">
  			<!--<div class="grid-title no-border">
  				<h4></h4>
  			</div>-->
  			<div class="grid-body no-border">
          <div id="messages">
					  <?php echo validation_errors(); ?>
            <?php if(!empty($message)) { ?>
              <div class="alert alert-success"><?php echo $message; ?></div>
            <?php } ?>
          </div>

          <div id="cards_deck" style="position:relative;">
            <a id="toggle_collapse" href="#"><i class="fa fa-chevron-up"></i></a>

            <nav class="actions light">
              <div class="center" style="margin-bottom: -40px;">
                <span id="nav-prev">&lt;</span>
                <span id="nav-next">&gt;</span>
              </div>
              <!--<span id="close">close</span>
              <span id="spread_cards">Spread Cards</span>-->
              <div id="spread_cards">
                <span>Close deck</span>
                <div class="slide-success">
                  <input type="checkbox" class="iosblue" name="switch">
                </div>
                <span>Open deck</span>
              </div>
            </nav>

            <div id="cards_control" style="position: absolute; top: 15px; left: 20px; background:#fff; padding: 10px 0px 0px 10px; border-radius: 5px; width:210px;">

              <div class="checkbox check-primary checkbox-circle">
                <input id="my_cards" type="checkbox" class="checkbox" checked="checked" value="my_cards">
                <label for="my_cards">My Cards (<?php echo $my_cards_number; ?>)</label>
              </div>

              <div class="checkbox check-primary checkbox-circle">
                <input id="shared_cards" type="checkbox" class="checkbox" value="shared_cards">
                <label for="shared_cards">Cards Shared With Me (<?php echo $shared_cards_number; ?>)</label>
              </div>

              <div class="checkbox check-primary checkbox-circle">
                <input id="public_cards" type="checkbox" class="checkbox" value="public_cards">
                <label for="public_cards">Public Cards (<?php echo $public_cards_number; ?>)</label>
              </div>

              <div class="input-group transparent" style="padding:0 5px 5px 0">
                <span class="input-group-addon" style="padding: 8px 8px 10px 9px;">
                  <i class="fa fa-instagram"></i>
                </span>
                <input class="form-control" type="text" placeholder="Filter Cards" id="filter_cards">
              </div>

            </div>

            <section class="main">
              <!--<nav class="actions">
                <span id="add">Add items</span>
              </nav>-->

              <div class="baraja-demo">
                <ul id="baraja-el" class="baraja-container">
                  <?php
                    $view_data['cards'] = $cards;
                    $view_data['type'] = $type;
                    $this->load->view('storyboard/deck_cards', $view_data);
                  ?>
                </ul>
              </div>
              <ul id="baraja_removed_cards" class="hide">
              </ul>
            </section>

          </div>

          <div id="sequencer" style="">
            <div id="start_slide" class="slide_thumb active">
              <a href="#" id="select_start_end_templates">
                <img src="<?php echo img_url('template_start_'.$start_end_template.'.png')?>" width="178" height="115" />
              </a>
              <a class="edit_template" href="#" title="Edit start and slides template" ><i class="fa fa-gear"></i></a>
              <a href="#" title="preview slide" class="preview_slide" style="display: none;"><i class="fa fa-external-link-square"></i></a>
              <input type="hidden" name="start_end_slide" value="<?php echo $start_end_template; ?>" id="start_end_slide" />
              <input type="hidden" class="title" value="<?php echo $title; ?>" id="start_title" />
              <input type="hidden" class="description" value="<?php echo htmlentities($description); ?>" id="start_description" />
              <input type="hidden" class="image" value="<?php echo $start_image; ?>" id="start_image" />
              <span class="preview_title"><?php echo cut_string($title, 25); ?></span>

              <div id="select_start_end_template_preview">
                <a href="#" id="select_template_1" class="<?php echo ($start_end_template == 1)?'active':'' ?> select_template">
                  <img src="<?php echo img_url('template_start_1.png')?>" width="178" height="115" />
                  <img src="<?php echo img_url('template_end_1.png')?>" width="178" height="115" />
                </a>
                <a href="#" id="select_template_2" class="<?php echo ($start_end_template == 2)?'active':'' ?> select_template">
                  <img src="<?php echo img_url('template_start_2.png')?>" width="178" height="115" />
                  <img src="<?php echo img_url('template_end_2.png')?>" width="178" height="115" />
                </a>
                <a href="#" id="select_template_3" class="<?php echo ($start_end_template == 3)?'active':'' ?> select_template">
                  <img src="<?php echo img_url('template_start_3.png')?>" width="178" height="115" />
                  <img src="<?php echo img_url('template_end_3.png')?>" width="178" height="115" />
                </a>
                <span class="up_triangle triangle">&#x25B2;</span>
              </div>
            </div>

            <a href="#" class="triangle_left triangle" id="scrollLeft">&#9668;</a>

            <div id="other_slides">
              <div id="other_slides_container" style="position:relative;">
                <?php
                if(!empty($obj)) {
					echo '<input type="hidden" class="style" id="style" name="style" value="'.$style.'">';
                    foreach ($obj->slides as $k => $slide) {
					 

                        $tmp_img = '';
                        $guid_line = '';

                        $card_type = '';
                        if($slide->type == 'card') {
                            foreach ($all_cards as $card) {
                                if ($card->id == $slide->content) {
                                    $card_type = $card->type;
                                }
                            }
                            $guid = id_to_guid($slide->content).md5('idaciti');
                            $guid_line = '<input type="hidden" class="guid" value="' . $guid . '">';

                            $tmp_img = '<img src="' . img_url($card_type . '.png') . '" id="temp_img" style="position: absolute; width: 120px; height: 102px;" class="pos_' . $slide->template . '">';
                        }
                        else {

                            // content sometimes holds a 'fake' id of the slide WTF?
                            // not sure where/how it is generated
                            // this is appending idaciti to the end of the 'guid'
                            $guid = id_to_guid($slide->id);
                            $guid_line = '<input type="hidden" class="guid" value="' . $guid . '">';
                        }


                      echo '<div id="order_'.$k.'" class="slide_thumb created_thumb">
                            <a href="#"> 
                              <img width="180" height="116" alt="'.$slide->type.'" src="'.img_url('template_'.$slide->type.'_'.$slide->template.'.png').'">'.$tmp_img.'
                            </a>
                            <input type="hidden" class="template_type" value="'.$slide->type.'">
                            <input type="hidden" class="template_number" value="'.$slide->template.'">
                            <input type="hidden" class="content" value="'.$slide->content.'">
                            <input type="hidden" class="title" value="'.$slide->title.'">
							<input type="hidden" class="stylechart" value="'.$card_type.'">
                            <input type="hidden" class="description" value="'.htmlentities($slide->description).'">
                            <input type="hidden" class="new_title" value="'.$slide->title.'">
                            <input type="hidden" class="wordtext" value="'.$slide->wc_words.'">
                            <input type="hidden" class="wc_type" value="'.$slide->wc_type.'">
                            <input type="hidden" class="wc_content" value="'.$slide->wc_content.'">
                            <input type="hidden" class="new_description" value="'.htmlentities($slide->description).'">'
								.$guid_line.
                            '<a data-toggle="modal" data-target="#remove_slide_modal" title="remove this slide from storyboard" href="#" class="remove_slide" style="display: none;"><i class="fa fa-minus-circle"></i></a>
                            <a href="#" title="move the left to re-order" class="move_slide_left" style="display: none;"><i class="fa fa-caret-square-o-left"></i></a>
                            <a class="edit_template" href="#" title="Select Template for the Slide" ><i class="fa fa-gear"></i></a>
                            <a href="#" title="move slide to the left" class="move_slide_left" style="display: none;"><i class="fa fa-caret-square-o-left"></i></a>
                            <a href="#" title="move slide to the right" class="move_slide_right" style="display: none;"><i class="fa fa-caret-square-o-right"></i></a>
                            <a href="#" title="preview slide" class="preview_slide" style="display: none;"><i class="fa fa-external-link-square"></i></a>
                            <span class="preview_title">'.cut_string(strip_tags($slide->title), 25).'</span>
                          </div>';
                  }
                }
                ?>

                <div id="add_new_slide" class="slide_thumb">
                  <a href="#"><i class="fa fa-plus-circle"></i></a>
                </div>
              </div>

              <div id="select_slide_template_preview">

                <!-- wordcloud cards -->
                  <!-- I think the number 2/4 determines whether the title is displayed right or left -->
                <a href="#" id="select_wordcloud_2" class="select_template wordcloud_template">
                  <img src="<?php echo img_url('word_card_1.png')?>" width="178" height="115" />
                </a>

                 <a href="#" id="select_wordcloud_4" class="select_template wordcloud_template">
                  <img src="<?php echo img_url('word_card_2.png')?>" width="178" height="115" />
                </a>

                  <!-- graph cards -->
                <a href="#" id="select_indicator_2" class="select_template indicator_template">
                  <img src="<?php echo img_url('template_indicator_2.png')?>" width="178" height="115" />
                </a>

                <a href="#" id="select_indicator_4" class="select_template indicator_template">
                  <img src="<?php echo img_url('template_indicator_4.png')?>" width="178" height="115" />
                </a>

                  <!-- media cards -->
                <a href="#" id="select_media_2" class="select_template media_template">
                  <img src="<?php echo img_url('template_media_2.png')?>" width="178" height="115" />
                </a>

                <a href="#" id="select_media_4" class="select_template media_template">
                  <img src="<?php echo img_url('template_media_4.png')?>" width="178" height="115" />
                </a>

                <span class="up_triangle triangle">&#x25B2;</span>

              </div>

            </div>

            <a href="#" class="triangle_right triangle" id="scrollRight">&#9658;</a>

            <div id="end_slide" class="slide_thumb">
              <a href="#" id="edit_end_slide">
                <img src="<?php echo img_url('template_end_'.$start_end_template.'.png')?>" width="178" height="115" />
              </a>
              <a href="#" title="preview slide" class="preview_slide" style="display: none;"><i class="fa fa-external-link-square"></i></a>
              <input type="hidden" class="callToAction" value="<?php echo htmlentities($end_text); ?>" id="end_text" />
              <input type="hidden" class="avatar" value="<?php echo $end_avatar; ?>" id="end_avatar" />
              <input type="hidden" value="<?php echo $user->first_name.' '.$user->last_name; ?>" id="full_name" />
              <input type="hidden" value="<?php echo $avatar; ?>" id="avatar_path" />
              <input type="hidden" value="<?php echo $has_link; ?>" class="has_link" />
              <input type="hidden" value="<?php echo $link_url; ?>" class="link_url" />
              <input type="hidden" value="<?php echo $link_name; ?>" class="link_name" />
            </div>

            <div id="slide_index">&#x25BC;</div>

          </div>

          <div id="form_area" style="">
            <form class="form-horizontal" role="form">

            </form>

            <div id="tagCompanies" class="col-md-7" style="display:none;" />
          </div>

          <div id="action_buttons">
            <button class="btn btn-success btn-cons right" id="save_storyboard" type="button">Save</button>
                        <!-- preview_storyboard_code
                        <button class="btn btn-success btn-cons right" id="preview_storyboard" type="button">Preview</button>
                        -->
              <button type="button" class="btn btn-success btn-cons right hidden" id="gen_wordcloud">Create WordCloud</button>

          </div>

          <div class="clearfix"></div>
  			</div>
  		</div>
  	</div>
  </div>
</div>

<div id="slides_forms" class="hide">

  <div id="start_slide_form">
    <div class="form_content row">

      <div class="col-sm-9 col-md-10">

        <div class="form-group">
          <label for="title" class="col-sm-3 col-md-2 control-label">Storyboard Title:</label>
          <div class="col-sm-9 col-md-10">
            <input type="text" class="form-control" id="title" placeholder="Enter a title" maxlength="40">
          </div>
        </div>

        <div class="form-group">
          <label for="description" class="col-sm-3 col-md-2 control-label">Description:</label>
          <div class="col-sm-9 col-md-10">
            <textarea id="description" class="wysiwyg_description" name="description" type="text" class="form-control" placeholder="Description" rows="10" maxlength="5000"></textarea>
          </div>
        </div>

   		<input name="setstyle" type="hidden" value="<?php echo $style;?>" />
        <div class="form-group">
        <label for="title" class="col-sm-3 col-md-2 control-label">Narrative/ Hotspots Style:</label>
        	<div class="col-sm-4 col-md-3"> 
            <input type="radio" name="userstyle" id="radio6" class="css-checkbox" value="link-scaleup" <?php if($style=="link-scaleup") { echo 'checked="checked"';}  if ( $op != "edit_") { ?> checked="checked" <?php } ?> onclick="return setStyleVal('link-scaleup');"/>
            <label for="radio6" class="css-label radGroup2" >Box Style</label>
			<div class="third_box">
            	<img src="<?php echo img_url('box-style.png')?>" width="170" height="75"  />
            </div>
          	</div>
        	<div class="col-sm-4 col-md-3">
            <input type="radio" name="userstyle" id="radio4" class="css-checkbox" value="link-border"  <?php  if($style=="link-border") { echo 'checked="checked"';} ?>onclick="return setStyleVal('link-border');" />
            <label for="radio4" class="css-label radGroup2" >Border Style</label>
			<div class="first_box">
            <img src="<?php echo img_url('border-style.png')?>" width="170" height="75"  />
            </div>
            </div>
            <div class="col-sm-4 col-md-3"> 
            <input type="radio" name="userstyle" id="radio5" class="css-checkbox" value="link-arrow" <?php if($style=="link-arrow") { echo 'checked="checked"';} ?> onclick="return setStyleVal('link-arrow');"/>
            <label for="radio5" class="css-label radGroup2" >Arrow Style</label>
			<div class="second_box">
            	<img src="<?php echo img_url('arrow-style.png')?>" width="170" height="75"  />
            </div>
            </div>
        </div>
      </div>

      <div class="col-sm-3 col-md-2">

        <!--- ------------------------------- File Upload-------------------------------------------- -->
        <form id="upload" method="post" action="<?php echo site_url('profile/upload_img'); ?>" enctype="multipart/form-data">
          <div id="drop">
            <div id="upload_image_preview">
              <img src="<?php echo img_url('upload_img.png'); ?>" title="upload or drag and drop an image" />
              <input type="hidden" id="uploaded_image" value="" />
            </div>
            <div class="file-wrapper fileinput-button">
              <input type="file" name="upl" class=""  />
              <button type="button" class="btn btn-block btn-success">Choose an Image</button>
              <span class="message"></span>
            </div>
          </div>
        </form>
        <!---------------------------------------------------------------------------------------------->

      </div>

    </div>
  </div>

  <div id="end_slide_form">
    <div class="form_content row">

      <div class="col-sm-12 col-md-12">

        <div class="form-group">
          <label for="description" class="col-sm-3 col-md-2 control-label">Call to action or Quote:</label>
          <div class="col-sm-9 col-md-10">
            <textarea id="description" class="wysiwyg_description" name="description" type="text" class="form-control" placeholder="Description" rows="10" maxlength="200"></textarea>

          </div>
        </div>

                <div class="checkbox check-default ">
                    <input id="add_link_checkbox" class="checkbox" type="checkbox" value="add_link" >
                    <label for="add_link_checkbox">Add link?</label>
        </div>

        <div class="form-group">
          <label for="link" class="col-sm-3 col-md-2 control-label">Link URL:</label>
          <div class="col-sm-9 col-md-10">
            <input type="text" class="form-control" id="link_url" placeholder="Paste a URL" maxlength="100" >
          </div>
        </div>

        <div class="form-group">
          <label for="link_name" class="col-sm-3 col-md-2 control-label">Link Name:</label>
          <div class="col-sm-9 col-md-10">
                        <input type="text" class="form-control" id="link_name" placeholder="Enter a name for the URL" maxlength="40" >
                    </div>
          </div>

                <div class="checkbox check-default ">
                    <input id="show_avatar" class="checkbox" type="checkbox" value="show_avatar" >
          <label for="show_avatar">Show Avatar?</label>
        </div>

      </div>

    </div>
  </div>

  <div id="wordcloud_slide_form">
      <div class="form_content row">
          <div id="wcControls" class="col-xs-6">
              <div class="form-group">
                  <label for="title" class="col-sm-3 col-md-2 control-label">Title:</label>
                  <div class="col-sm-9 col-md-10">
                      <input type="text" class="form-control" id="title" placeholder="Enter a title" maxlength="40">
                  </div>
              </div>

              <div class="form-group">
                  <label for="description" class="col-sm-3 col-md-2 control-label">Description:</label>
                  <div class="col-sm-9 col-md-10">
                      <textarea id="description" class="wysiwyg_description" name="description" type="text" class="form-control" placeholder="Description" rows="3" maxlength="3000"></textarea>

                  </div>
              </div>

              <div class="form-group">
                  <label for="wordtext" class="col-sm-3 col-md-2 control-label">Type:</label>
                      <div id="wc_type_div" class="col-md-10 control-label" style="text-align: left">
                          Spirited&nbsp;
                          <input type="radio" name="wc_type" value="spirited" id="spirited" style=""/>
                          Professional&nbsp;
                          <input type="radio" name="wc_type" value="professional" id="professional" checked />
                      </div>
              </div>

              <div class="form-group">
                  <label for="wordtext" class="col-sm-3 col-md-2 control-label">WordCloud Text:</label>
                  <div class="col-md-10">
                      <textarea id="wordtext" name="wordtext" type="text" class="form-control" placeholder="wordtext" rows="3"></textarea>
                  </div>
              </div>

          </div>

          <div id="vis" class="col-xs-6">
              <!-- wordcloud image-->
          </div>
      </div>
  </div>

  <div id="card_slide_form">
    <div class="form_content row">

      <div class="col-sm-12 col-md-5">

        <div class="form-group">
          <label for="title" class="col-sm-3 col-md-2 control-label">Title:</label>
          <div class="col-sm-9 col-md-10">
            <input type="text" class="form-control" id="title" placeholder="Enter a title" maxlength="40" >
          </div>
        </div>

        <div class="form-group">
          <label for="description" class="col-sm-3 col-md-2 control-label">Description:</label>
          <div class="col-sm-9 col-md-10">
            <textarea id="description" class="wysiwyg_description" name="description" type="text" class="form-control" placeholder="Description" rows="20" maxlength="5000"></textarea>

          </div>
        </div>

      </div>

      <div class="col-sm-12 col-md-7">

        <div id="iframe_preview" style="height:600px;">
          
        </div>

      </div>

    </div>
  </div>

  <div id="media_slide_form">
    <div class="form_content row">

      <div class="col-sm-9 col-md-10">

        <div class="form-group">
          <label for="url" class="col-sm-3 col-md-2 control-label">Link to image or video:</label>
          <div class="col-sm-9 col-md-10">
            <input type="text" class="form-control" id="url" placeholder="Paste the URL here">
          </div>
        </div>

        <div class="form-group">
          <label for="title" class="col-sm-3 col-md-2 control-label">Title:</label>
          <div class="col-sm-9 col-md-10">
            <input type="text" class="form-control" id="title" placeholder="Enter a title" maxlength="40">
          </div>
        </div>

        <div class="form-group">
          <label for="description" class="col-sm-3 col-md-2 control-label">Description:</label>
          <div class="col-sm-9 col-md-10">
            <textarea id="description" class="wysiwyg_description" name="description" type="text" class="form-control" placeholder="Description" rows="10" maxlength="5000"></textarea>

          </div>
        </div>

      </div>

      <div class="col-sm-3 col-md-2">
        <!--- ------------------------------- File Upload-------------------------------------------- -->
        <form id="upload" method="post" action="<?php echo site_url('profile/upload_img'); ?>" enctype="multipart/form-data">
          <div id="drop">
            <div id="upload_image_preview">
              <img src="<?php echo img_url('upload_img.png'); ?>" title="upload or drag and drop an image" />
              <input type="hidden" id="uploaded_image" value="" />
            </div>
            <div class="file-wrapper fileinput-button">
              <input type="file" name="upl" class=""  />
              <button type="button" class="btn btn-block btn-success">Choose an Image</button>
              <span class="message"></span>
            </div>
          </div>
        </form>
        <!---------------------------------------------------------------------------------------------->

      </div>

    </div>
  </div>

  <div id="indicator_slide_form">
    <div class="form_content row">

      <div class="col-sm-9 col-md-10">

        <div class="form-group">
          <label for="url" class="col-sm-3 col-md-2 control-label">Indicator Chart:</label>
          <div class="col-sm-9 col-md-10">
            <select id="indicator_id" style="width:100%;">
              <option></option>
              <optgroup label="FRED">
                <?php 
                  foreach ($indicators as $k => $ind) {
                    echo '<option value="'.$ind->id.'" data-url="'.$ind->link.'">'.$ind->name.'</option>';
                  }
                ?>
              </optgroup>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="title" class="col-sm-3 col-md-2 control-label">Title:</label>
          <div class="col-sm-9 col-md-10">
            <input type="text" class="form-control" id="title" placeholder="Enter a title" maxlength="40">
          </div>
        </div>

        <div class="form-group">
          <label for="description" class="col-sm-3 col-md-2 control-label">Description:</label>
          <div class="col-sm-9 col-md-10">
            <textarea id="description" class="wysiwyg_description" name="description" type="text" class="form-control" placeholder="Description" rows="10" maxlength="5000"></textarea>

          </div>
        </div>

      </div>
    </div>
  </div>

</div>

<div class="modal fade large_modal" id="preview_card_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" style="min-height:600px; max-height:713px;">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> 
<div class="modal fade large_modal" id="preview_slide_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" style="">
              <div class="row">

              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> 

<div class="modal fade notif_modals" id="remove_slide_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>Remove Slide</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this slide from your storyboard?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger removeSlide">Remove</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade notif_modals" id="add_card_error_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>Can't Add this card</h4>
            </div>
            <div class="modal-body">
                <p>It seems you've already added this card to your storyboard, please select a different one!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade notif_modals" id="save_sb_error_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>You can't save this storyboard yet!</h4>
            </div>
            <div class="modal-body">
                <p>It seems your storyboard is not complete yet, please take a look at the notes below:</p>
                <p id="save_sb_errors"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">close</button>
            </div>
        </div>
    </div>
</div>

<?php require_once $folder.'/card/dd_dialogs.php'; ?>