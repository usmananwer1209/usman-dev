<?php 
  $user_avatar = avatar_url($sb_user);
  
?>
        <div id="storyboard_viewer">

          <div>
            <!--<div>
              <a id="start_slide" data-slide-index="0" href="" class="slide_thumb active">
            </div>-->
            <div id="bx-pager" style="">
            <div class="pager_container" id="primary_pager">
              <a id="start_slide" data-slide-index="0" href="" class="slide_thumb active">
                <img src="<?php echo img_url('empty_template_start_'.$obj->start_end_template.'.png'); ?>" class="full_width" />
                <?php 
                    if($obj->start_end_template == 1){
                      if(!empty($obj->start_image))
                       echo '<img src="'.$obj->start_image.'" style="width:55px; height:46px; position:absolute; position:absolute; top:15px; left:0.5px" />';
                      if(!empty($obj->title))
                        echo '<span style="position:absolute; position:absolute; top:26px; left:60px; font-family:verdana; font-size:9px; line-height:15px; color:#fff; text-align:center; width:115px; display:inline-block;">'.$obj->title.'</span>';
                      if(!empty($obj->description))
                        echo '<span style="position:absolute; position:absolute; top:64px; left:70px; font-family:verdana; font-size:7px; line-height:12px; color:#666; text-align:left; width:106px; height:45px; overflow:hidden; display:inline-block;">'.strip_tags($obj->description).'</span>';
                    } 
                    if($obj->start_end_template == 2){
                      if(!empty($obj->start_image))
                       echo '<img src="'.$obj->start_image.'" style="width:80px; height:80px; border-radius:50%; position:absolute; top:25px; left:10px" />';
                      if(!empty($obj->title))
                        echo '<span style="position:absolute; position:absolute; top:3px; left:5px; font-family:verdana; font-size:10px; line-height:15px; color:#fff; text-align:left; width:160px; display:inline-block; overflow:hidden; height:15px;">'.$obj->title.'</span>';
                      if(!empty($obj->description))
                        echo '<span style="position:absolute; position:absolute; top:25px; left:100px; font-family:verdana; font-size:8px; line-height:12px; color:#fff; text-align:left; width:75px; height:80px; overflow:hidden; display:inline-block;">'.strip_tags($obj->description).'</span>';
                    } 
                    if($obj->start_end_template == 3){
                      if(!empty($obj->title))
                        echo '<span style="position:absolute; position:absolute; top:5px; left:62px; font-family:verdana; font-size:9px; line-height:15px; color:#fff; text-align:left; width:111px; display:inline-block;">'.$obj->title.'</span>';
                      if(!empty($obj->description))
                        echo '<span style="position:absolute; position:absolute; top:35px; left:80px; font-family:verdana; font-size:8px; line-height:12px; color:#fff; text-align:left; width:96px; height:70px; overflow:hidden; display:inline-block;">'.strip_tags($obj->description).'</span>';
                    } 
                ?>
              </a>

              <span class="triangle_left triangle" id="scrollLeft">&#9668;</span>

              <div id="other_slides">
                <div id="other_slides_container" style="position:relative;">
                <?php
                if(!empty($obj)) {
                  foreach ($obj->slides as $k => $slide) {
                    $tmp_img = '';
                    if($slide->type == 'card') {
                      $card_type = '';
                      foreach ($all_cards as $card) {
                        if($card->id == $slide->content) {
                          $card_type = $card->type;
                          break;
                        }
                      }
                      $tmp_img = '<img src="'.img_url($card_type.'.png').'" id="temp_img" style="position: absolute; width: 120px; height: 102px;" class="pos_'.$slide->template.'">';
                    }
                    if($slide->type == 'media') {
                      if(is_img($slide->content))
                        $tmp_img = '<img src="'.$slide->content.'" id="temp_img" style="position: absolute; width: 120px; height: 102px;" class="pos_'.$slide->template.'">';
                      else{
                        $vid_id = get_youtube_id_from_url($slide->content);
                        if(!empty($vid_id))
                          $tmp_img = '<img src="//img.youtube.com/vi/'.$vid_id.'/0.jpg" id="temp_img" style="position: absolute; width: 120px; height: 102px;" class="pos_'.$slide->template.'">';
                        else{
                          $vid_id = get_vimeo_id_from_url($slide->content);
                          if(!empty($vid_id))
                          $tmp_img = '<img src="'.getVimeoThumb($vid_id).'" id="temp_img" style="position: absolute; width: 120px; height: 102px;" class="pos_'.$slide->template.'">';
                        }
                      }
                    }

                    echo '<div id="order_'.$k.'" class="slide_thumb created_thumb">
                            <a href="#" data-slide-index="'.($k+1).'"> 
                              <img width="180" height="116" alt="'.$slide->type.'" src="'.img_url('template_'.$slide->type.'_'.$slide->template.'.png').'">'.$tmp_img.'
                            </a>
                            
                            <span class="preview_title">'.cut_string(strip_tags($slide->title), 25).'</span>
                          </div>';
                  }
                }
                ?>
                </div>
              </div>

              <span class="triangle_right triangle" id="scrollRight">&#9658;</span>

              <a id="end_slide" data-slide-index="<?php echo $k + 2; ?>" class="slide_thumb"  href="#">
                <img src="<?php echo img_url('empty_template_end_'.$obj->start_end_template.'.png'); ?>" class="full_width" />
                <?php 
                    if($obj->start_end_template == 1){
                      if(!empty($obj->end_avatar))
                       echo '<img src="'.$user_avatar.'" style="width:auto; height:27px; position:absolute; position:absolute; top:65px; left:80px" />';
                      if(!empty($obj->end_text))
                        echo '<span style="position:absolute; top:26px; left:60px; font-family:verdana; font-size:9px; line-height:11px; color:#fff; text-align:center; width:115px; display:inline-block; overflow: hidden; height:23px;">'.strip_tags($obj->end_text).'</span>';
                      echo '<span style="position:absolute; top:64px; left:10px; font-family:verdana; font-size:7px; line-height:12px; color:#fff; text-align:center; width:68px; overflow:hidden; display:inline-block;">'.$sb_user->first_name.' '.$sb_user->last_name.'</span>';
                    } 
                    if($obj->start_end_template == 2){
                       if(!empty($obj->end_avatar))
                       echo '<img src="'.$user_avatar.'" style="width:auto; height:25px; position:absolute; position:absolute; top:50px; left:76px" />';
                      if(!empty($obj->end_text))
                        echo '<span style="position:absolute; top:27px; left:45px; font-family:verdana; font-size:9px; line-height:11px; color:#fff; text-align:center; width:95px; height:30px; display:inline-block; overflow: hidden; height:23px;">'.strip_tags($obj->end_text).'</span>';
                      echo '<span style="position:absolute; top:88px; left:60px; font-family:verdana; font-size:7px; line-height:12px; color:#fff; text-align:center; width:68px; overflow:hidden; display:inline-block;">'.$sb_user->first_name.' '.$sb_user->last_name.'</span>';
                    } 
                    if($obj->start_end_template == 3){
                      if(!empty($obj->end_avatar))
                       echo '<img src="'.$user_avatar.'" style="width:auto; height:25px; position:absolute; position:absolute; top:70px; left:86px" />';
                      if(!empty($obj->end_text))
                        echo '<span style="position:absolute; top:35px; left:10px; font-family:verdana; font-size:9px; line-height:11px; color:#fff; text-align:center; width:115px; display:inline-block; overflow: hidden; height:23px;">'.strip_tags($obj->end_text).'</span>';
                      echo '<span style="position:absolute; top:70px; left:18px; font-family:verdana; font-size:7px; line-height:12px; color:#fff; text-align:center; width:78px; overflow:hidden; display:inline-block;">'.$sb_user->first_name.' '.$sb_user->last_name.'</span>';
                    }
                ?>
              </a>
            </div>

            <div class="pager_container" id="secondary_pager" style="text-align:right; padding-right:50px;">
              <h2 id="storyboard_title" style="position:absolute; top:-13px; left:10px; display:none;"><?php echo $title; ?></h2>
              <a data-slide-index="0" href="#"></a>
              <?php
                if(!empty($obj)) {
                  foreach ($obj->slides as $k => $slide)
                    echo '<a href="#" data-slide-index="'.($k+1).'"></a> ';
                }
                ?>
              <a data-slide-index="<?php echo $k + 2; ?>" href="#"></a>
            </div>
            
            </div>
            <a id="toggle_collapse" class="expanded" href="#"><i class="fa fa-chevron-up"></i><i class="fa fa-chevron-down"></i></a>
          </div>
          
          <ul class="bxslider" id="storyboard_view_slides">
          <?php 
            echo '<li>
                    <div id="start_frame" class="template_'.$obj->start_end_template.'">
                      <img class="full_width auto_height" src="'.img_url('empty_template_start_'.$obj->start_end_template.'.png').'" />
                      <span class="title">'.$obj->title.'</span>
                      <div class="description">'.$obj->description.'</div>
                      <img src="'.$obj->start_image.'" class="start_image" />
                    </div>
                  </li>';
				  $count	= 1;
            foreach($obj->slides as $k => $slide) {
                $content_to_display = '';
                $cssBorderClass = 'border ';
                echo '<li class="border ' . $slide->type . '">';
                if ($slide->type == 'card') {
				  	$rand	= rand(0,100);
                
					$src = site_url('card/embed/'.id_to_guid($slide->content).md5('idaciti')).'/sid/'.$obj->id.'/'.$rand;
                	$content_to_display = '<div class="card_iframe"><iframe src="'.$src.'" frameborder="0" id="cardframe_'.$count.'" ></iframe></div>';
                    //$content_to_display = '<input type="hidden" class="src" value="'.$src.'" />';
                }
                else if ($slide->type == 'indicator') {
                    $src = '';
                    foreach ($indicators as $k => $ind) {
                        if ($ind->id == $slide->content)
                            $src = $ind->link;
                    }
                    $content_to_display = '<iframe src="' . $src . '" frameborder="0" ></iframe>';
                    //$content_to_display = '<input type="hidden" class="src" value="'.$src.'" />';
                }
                else if ($slide->type == 'media') {
                    if (is_img($slide->content))
                        $content_to_display = '<img src="' . $slide->content . '" alt="' . $slide->title . '" class="full_width" />';
                    else {
                        $vid_id = get_youtube_id_from_url($slide->content);
                        if (!empty($vid_id)) {
                            $content_to_display = '<iframe style="" src="//www.youtube.com/embed/' . $vid_id . '" frameborder="0"></iframe>';
                            //$content_to_display = '<input type="hidden" class="src" value="//www.youtube.com/embed/'.$vid_id.'" />';
                        } 
						else {
                            $vid_id = get_vimeo_id_from_url($slide->content);
                            if (!empty($vid_id)) {
                                $content_to_display = '<iframe style="" src="//player.vimeo.com/video/' . $vid_id . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
                            }
                        }
                    }

                    //$all_content = '<div id="slide_preview_content_area" class="col-sm-8 col-md-9">' . $content_to_display . '</div><div id="slide_preview_desc_area" class="col-sm-4 col-md-3 ' . $obj->style . '"><h1>' . $slide->title . '</h1><p>' . remove_unicode($slide->description) . '</p></div>';
                }
                else if ($slide->type == 'wordcloud') {

                    $winWidth = 800;
                    $winHeight = 600;

                    $width = $slide->word_content->bounds[1]->x - $slide->word_content->bounds[0]->x;
                    $height = $slide->word_content->bounds[1]->y - $slide->word_content->bounds[0]->y;

                    $scale = min($winWidth / $width, $winHeight / $height);

                    $transHeight = ($winHeight / 2)+25;
                    // build the string representation of the text nodes
                    $textNodes = '<g transform="translate(' . $winWidth / 2 . ',' . $transHeight . ') scale(' . $scale . ')" id="svgG">';

                    foreach ($slide->word_content->words as $j => $jd) {
                        //$textNodes .= '<svg:text xmlns:svg=\'http://www.w3.org/2000/svg\'' .
                        $textNodes .= '<text' .
                            ' text-anchor="' . $jd->text_anchor .
                            '" transform="' . $jd->transform .
                            '" style="' . $jd->style .
                            //'\' id=\'' . $jd->id . no id at this time
                            '">' .
                            trim($jd->the_word, '"') . '</text>';
                    }

                    $textNodes .= '</g>';

                    $content_to_display = '<svg width="867px" height="600px" id="svgMain"><g></g>' . $textNodes . '</svg>';

                    //$all_content = '<div id="preview_content_area" class="col-sm-8 col-md-9">' . $content_to_display . '</div><div id="slide_preview_desc_area" class="col-sm-4 col-md-3 ' . $obj->style . '"><h1>' . $slide->title . '</h1><p>' . remove_unicode($slide->description) . '</p></div>';
                }

                if($slide->template == 2) {
                    if ($slide->type == 'card') {

                        $cardtype = '';
                        $ctypes = $this->storyboards->getcardtype($data = $slide->content);

                        foreach ($ctypes as $ctype) {
                            $cardtype = $ctype->type;
                        }
                        if ($cardtype != "rank" && $cardtype != "combo") {
                            $all_content = '<div id="slide_preview_content_area" class="col-sm-12 col-md-12"><input id="charttype_' . $count . '" value="' . $cardtype . '" type="hidden"><input id="isreload_' . $count . '" value="0" type="hidden">' . $content_to_display . '</div>';
                        } else {
                            $all_content = '<div id="slide_preview_content_area" class="col-sm-8 col-md-9">' . $content_to_display . '</div><div id="slide_preview_desc_area" class="col-sm-4 col-md-3 ' . $obj->style . '"><h1>' . $slide->title . '</h1><p>' . remove_unicode($slide->description) . '</p></div>';
                        }
                    } else if ($slide->type == 'wordcloud') {
                        $all_content = '<div id="slide_content_area" class="col-sm-8 col-md-9">' . $content_to_display . '</div><div id="slide_preview_desc_area" class="col-sm-4 col-md-3 ' . $obj->style . '"><h1>' . $slide->title . '</h1><p>' . remove_unicode($slide->description) . '</p></div>';
                    }
                    else {
                        $all_content = '<div id="slide_preview_content_area" class="col-sm-8 col-md-9">' . $content_to_display . '</div><div id="slide_preview_desc_area" class="col-sm-4 col-md-3 ' . $obj->style . '"><h1>' . $slide->title . '</h1><p>' . remove_unicode($slide->description) . '</p></div>';
                    }
                }
                else if ($slide->template == 4) {
                    if ($slide->type == 'wordcloud') {
                        $all_content = '<div id="slide_preview_desc_area" class="col-sm-4 col-md-3 ' . $obj->style . '"><h1>' . $slide->title . '</h1><p>' . $slide->description . '</p></div><div id="slide_content_area" class="col-sm-8 col-md-9 ">' . $content_to_display . '</div>';
                    }
                    else {
                        $all_content = '<div id="slide_preview_desc_area" class="col-sm-4 col-md-3 ' . $obj->style . '"><h1>' . $slide->title . '</h1><p>' . $slide->description . '</p></div><div id="slide_preview_content_area" class="col-sm-8 col-md-9 ">' . $content_to_display . '</div>';
                    }
                }
                echo $all_content;
                echo '</li>';
				$count++;
            } // end foreach

            $end_avatar = ($obj->end_avatar == 1)?'<p class="avatar_frame"><img src="'.$user_avatar.'" class="avatar" /></p>':'';
            echo '<li>
                    <div id="end_frame" class="template_'.$obj->start_end_template.'">
                      <img class="full_width auto_height" src="'.img_url('empty_template_end_'.$obj->start_end_template.'.png').'" />
                      <div class="end_text"><p>'.$obj->end_text.'</p></div>
                      <span class="full_name">'.$sb_user->first_name.' '.$sb_user->last_name.'</span>
                      '.$end_avatar.'
                      <p class="end_link"><a href="'.$obj->end_link.'" target="_blank">' .$obj->end_link_name. '</a></p>
                    </div>
                  </li>';
          ?>
            
          </ul>
        </div>