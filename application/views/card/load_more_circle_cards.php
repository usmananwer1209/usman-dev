<?php
if($type =='overwrite'){ ?>
<div id="overlay_div" class="overlay2" style="display:none"><img src="<?php echo site_url('assets/img/AjaxLoader2.gif');?>" /></div>
<?php }?>
<?php
$count = 1;
        foreach ($cards as &$obj) {
          $obj = (object) $obj;
          ?>
            <li class="cell element-item transition <?php if($count==1 && $type !='overwrite'){ echo 'first_of_new'; } $count=2;?>"
                data-id="<?php echo $obj->id; ?>"
                data-name="<?php echo strip_tags($obj->name); ?>"
                data-description="<?php echo strip_tags($obj->name); ?>"
                data-period="<?php echo $obj->period; ?>"
                data-kpi="<?php echo $obj->kpi; ?>"
                data-order="<?php echo $obj->order; ?>"
                data-autor="<?php echo $obj->autor; ?>"
                data-creation_time="<?php echo $obj->creation_time; ?>"
                data-viewed="<?php echo $obj->viewed; ?>"
           		data-display-count = "<?php echo $total_result_count;?>"
                data-count-start = "<?php echo count($cards);?>"    
            >
				<div class="m-l-10 flip_card" data-card-id="<?php echo $obj->id; ?>">
				<a href="<?php echo site_url('card/view/' . $obj->id); ?>" > 
            <div class="tiles white cards text-center pagination-centered <?php echo $obj->type; ?>">
            </div>
                            </a>
            <div class="tiles gray p-t-5 p-b-5  m-b-20" >
                <p class="text-center text-white semi-bold  small-text"> 
                    <a class="white" href="<?php echo site_url('card/view/'.$obj->id);?>">
                        <?php 
                         $title = cut_string($obj->name, 25);
                         if ($obj->disqus_post_count > 0) {
                            $leftB = ' [';
                            $rightB = ']';
                            $comments = $obj->disqus_post_count;
                            echo $title.$leftB.$comments.$rightB;
                         }
                         else
                         {
                            echo $title;
                         }
                       ?>
                </p>
                    </a>
                <?php
                    $card_privacy = ($obj->public == 0)? 'private':'public' ;
                    $circles = "";
                    $info = "";
                    $info .= "Viewed: ".$obj->viewed."<br/>";
                    $info .= "Privacy: ".$card_privacy."<br/>";
                    $info .= "Autor: ".$obj->autor."<br/>";
                    $info .= "Date Created: ".$obj->creation_time."<br/>";

                                    if (!empty($obj->circles)) {
                  $num_circle = count($obj->circles);
                  $i = 0;
                  foreach ($obj->circles as $circle) {
                    if($i < 3)
                      $circles .= cut_string($circle['name'],20)."<br/>";
                    if($i > 3){
                      $circles .= "...";
                      break;
                      }
                    $i++;
                  }
                                    } else {
                  $circles = "Not shared with any circle";
                }
                ?>
                                    <a class="fa fa-retweet flipper" 
                                       data-card-id="<?php echo $obj->id; ?>"
                                       title="flip card" href="#"></a>

                                                <!--<a title="<?php //echo $info;   ?>" class="fa fa-info-circle tooltip-toggle tooltip-left" data-toggle="tooltip" data-placement="bottom"></a>-->
                    <a title="<?php echo $circles;?>" class="fa fa-circle-o tooltip-toggle tooltip-right" data-toggle="tooltip" data-placement="bottom"></a>
                    <?php 
                      if($obj->public)
                        echo '<i  class="fa fa-unlock tooltip-toggle tooltip-right" title="Public Card" data-toggle="tooltip" data-placement="bottom" style="margin-right: 20px;"></i>';
                    ?>
                </p>
            </div>

                            <div class="m-l-10 card_flipped" style="display: none">
                                <div class="flip_card"
                                   data-card-id="<?php echo $obj->id; ?>"
                                   data-card-flipped="no"
                                   title="flip card" href="#">
                                    <div class="white text-left flip_div pagination-centered <?php echo $obj->type; ?>">
                                        <p><strong>Author : </strong> <?php echo $obj->autor; ?><br/></p>
                                        <p><strong>Name : </strong> <?php echo $obj->name; ?><br/></p>
                                        <p><strong>Date Created : </strong> <?php echo $obj->creation_time; ?><br/></p>
                                        <p><strong># Viewed : </strong> <?php echo $obj->viewed; ?> </p>
                                    </div>
                                    <div class="tiles gray p-t-5 p-b-5  m-b-20">
                                        <p class="text-center text-white semi-bold  small-text"> 
                                            <?php echo $obj->name; ?>
                                            <a class="fa fa-retweet flipper" 
                                               data-card-id="<?php echo $obj->id; ?>"
                                               title="flip card" href="#"></a>
                                                <!--<a title="<?php //echo $info;   ?>" class="fa fa-info-circle tooltip-toggle tooltip-left" data-toggle="tooltip" data-placement="bottom"></a>-->
                                            <a title="<?php echo $circles; ?>" class="fa fa-circle-o tooltip-toggle tooltip-right" data-toggle="tooltip" data-placement="bottom"></a>                             

                   <!--<a title="<?php //echo $info;   ?>" class="fa fa-info-circle tooltip-toggle tooltip-left" data-toggle="tooltip" data-placement="bottom"></a>-->
                                        </p>    
                                    </div>
                                </div>
                            </div>
                        </div>


            </li>
<?php } ?>
     