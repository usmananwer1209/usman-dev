<div class="content">
	<div class="row">
		<div class="profile_area  col-md-3">
				<div class="administrator">
					<div class="admin_img m-t-5 clearfix">
                    <a href="<?php echo site_url('/profile/view/' . $obj->id); ?>">
						<img class="img-circle pull-left "  width="75" height="75" 
						src="<?php echo  avatar_url($obj)."?id=".uniqid(); ?>">
                    </a>
					</div>
					<div class="admin_info m-t-5">
						<div class="h3">
						<?php echo $obj->first_name.'.'.$obj->last_name; ?>
						<?php if($obj->id == $user->id){ ?>
							<a href="<?php echo site_url('profile/edit') ?>">
                                <i class="fa fa-edit"></i>
							</a>
						<?php } ?>
						</div>
						<small class="text-muted"> <i class="fa fa-map-marker"></i>
							<?php echo $obj->country; ?>
						</small>
					</div>
				</div>
				<div class="statistic clearfix">
					<div class="col-xs-4 statistic-members">
						<!--<a href="<?php //echo site_url('profile/view/'.$obj->id.'/all'); ?>">-->
							<span class="m-b-xs h4 block">
								<?php echo count($cards); ?>
							</span><br/>
							<small class="text-muted">Cards</small>
						<!--</a>-->
					</div>
					<div class="col-xs-4 statistic-sb">
                                            <span class="m-b-xs h4 block">
                                                    <?php echo count($storyboards); ?>
                                            </span><br/>
                                            <small class="text-muted">Storyboards</small>
					</div>
					<div class="col-xs-4 statistic-cards">
						<span class="m-b-xs h4 block">
							<?php echo count($circles); ?>
						</span><br/>
						<small class="text-muted">Circles</small>
					</div>
				</div>
				<div class="m-t-10 join-btn">
				<!--
					<a class="btn2 btn2-success btn2-rounded" href="javascript:;">
						<i class="fa fa-plus"></i> Ask to Join
					</a>
				-->
				</div>
				<div class="m-t-5 trusted-circle">
					<span class="text-uc text-xs text-muted">My Trusted circles</span><br/>
					<?php foreach ($circles as $circle) { ?>
						<small class="text-uc text-xs text-muted">
							<?php echo $circle['name']; ?>
						</small><br/>
					<?php } ?>
				</div>
				<div class="m-t-10 more-about">
					<span class="text-uc text-xs text-muted">More about me</span>
					<p>
                    <?php
                    $order = array("\r\n", "\n", "\r");
                    $replace = '<br />';
                    echo str_replace($order, $replace, $obj->about); //echo cut_string($obj->about, 100);
                    ?>
					</p>
				</div>
				<div class="m-t-10 connecting">
					<small class="text-uc text-xs text-muted">
						Connecting with <?php echo $obj->first_name.'.'.$obj->last_name; ?>
					</small>
					<p class="m-t-sm">
                    <?php
                    if (!empty($obj->linkedin_profile)) {
                        ?>
                        <a class="btn btn-rounded btn-twitter btn-icon" target="_blank"
                           href="<?php echo "http://" . $obj->linkedin_profile ?>">
							<i class="fa fa-linkedin"></i>
						</a>

                        <?php
                    }
                    ?>
                    <?php
                    /*if (!empty($obj->google_profile)) {
                        ?>
                        <a class="btn btn-rounded btn-gplus btn-icon" target="_blank"
                           href="<?php echo "http://" . $obj->google_profile ?>">
							<i class="fa fa-google-plus"></i>
						</a>

                        <?php
                    }*/
                    ?>
                    <?php
                    /*if (!empty($obj->facebook_profile)) {
                        ?>
                        <a class="btn btn-rounded btn-facebook btn-icon" target="_blank"
                           href="<?php echo "http://" . $obj->facebook_profile ?>">
							<i class="fa fa-facebook"></i>
						</a>
                        <?php
                    }*/
                    ?>
					<?php
                    if (!empty($obj->twitter_profile)) {
                        ?>
                        <a class="btn btn-rounded btn-twitter btn-icon" target="_blank"
                           href="<?php echo "http://" . $obj->twitter_profile ?>">
                            <i class="fa fa-twitter"></i>
                        </a>
                        <?php
                    }
                    ?>
					</p>
				</div>
		</div>
		<div class="col-md-9">

			<div class="circle_area">
				
				
				<div class="most-viewed clearfix">
					<span class="block">Most Viewed Cards:</span>
			      <div class="most-vieweds">
              <ul id="cards_isotope"  class="isotope2 transition">
              <?php
              $n = count($cards);
              $n = ($n > 6)? 6 : $n;

              for($k = 0; $k < $n; $k++) {
                $card = $cards[$k];
                $card = (object) $card;
              ?>
                <li class="cell element-item transition">
                <div class="m-l-10 flip_card" data-card-id="<?php echo $card->id; ?>">
                      <a href="<?php echo site_url('card/view/'.$card->id); ?>">
                        <div class="tiles white cards text-center pagination-centered <?php echo $card->type; ?>"></div>
                      </a>
                    <div class="tiles gray p-t-5 p-b-5  m-b-20" >
                      <p class="text-center text-white semi-bold  small-text"> 
                        <a class="white" href="<?php echo site_url('card/view/'.$card->id);?>"><?php echo cut_string($card->name, 32);?></a>
                          <?php
                              $card_privacy = ($card->public == 0)? 'private':'public' ;
                              $circles = "";
                              $info = "";
                              $info .= "Viewed: ".$card->viewed."<br/>";
                              $info .= "Privacy: ".$card_privacy."<br/>";
                              $info .= "Autor: ".$card->author."<br/>";
                              $info .= "Date Created: ".$card->creation_time."<br/>";

                                              if (!empty($card->circles)) {
                            $num_circle = count($card->circles);
                            $i = 0;
                            foreach ($card->circles as $c_circle) {
                              if($i < 3)
                                $circles .= cut_string($c_circle['name'],20)."<br/>";
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
                        <a class="fa fa-retweet flipper" data-card-id="<?php echo $card->id; ?>" title="flip card" href="#"></a>
                        <a title="<?php echo $circles;?>" class="fa fa-circle-o tooltip-toggle tooltip-right" data-toggle="tooltip" data-placement="bottom"></a>
                      </p>
                    </div>

                    <div class="m-l-10 card_flipped" style="display: none">
                      <div class="flip_card" data-card-id="<?php echo $card->id; ?>" data-card-flipped="no"  title="flip card" href="#">
                        <div class="white text-left flip_div pagination-centered <?php echo $card->type; ?>">
                            <p><strong>Author : </strong> <?php echo $card->author; ?><br/></p>
                            <p><strong>Name : </strong> <?php echo $card->name; ?><br/></p>
                            <p><strong>Date Created : </strong> <?php echo $card->creation_time; ?><br/></p>
                            <p><strong># Viewed : </strong> <?php echo $card->viewed; ?><br/></p>
                        </div>
                        <div class="tiles gray p-t-5 p-b-5  m-b-20">
                          <p class="text-center text-white semi-bold  small-text"> 
                            <?php echo $card->name; ?>
                              <a class="fa fa-retweet flipper" data-card-id="<?php echo $card->id; ?>" title="flip card" href="#"></a>
                              <a title="<?php echo $circles; ?>" class="fa fa-circle-o tooltip-toggle tooltip-right" data-toggle="tooltip" data-placement="bottom"></a>   
                          </p>    
                        </div>
                      </div>
                    </div>

                </div>
                </li>

                                
              <?php 
              } 
              ?>
              </ul>
            </div>
				</div>

        <div class="most-viewed clearfix">
          <span class="block">Most Viewed Stories:</span>
          <div class="most-vieweds">
              <ul id="cards_isotope"  class="isotope2 transition">
              <?php
              $n = count($storyboards);
              $n = ($n > 6)? 6 : $n;

              for($k = 0; $k < $n; $k++) {
                $sb = $storyboards[$k];
                $obj = (array) $sb;

                    $info = "";
                    $circles = "";
                    $info .= "Date Created:" . $obj['creation_time'] . "<br/>";

                    if (!empty($obj['circles'])) {
                        
                        $num_circle = count($obj['circles']);
                        $i = 0;
                        foreach ($obj['circles'] as $s_circle) {
                            if ($i < 3)
                                $circles .= cut_string(strip_tags($s_circle['name']), 20) . "<br/>";
                            if ($i > 3) {
                              $circles .= "...";
                              break;
                            }
                            $i++;
                        }
                    } else {
                        $circles = "Not shared with any circle";
                    }
                    ?>
                    <li class="cell element-item transition">

                        <div class="m-l-10 flip_sb" data-card-id="<?php echo $obj['id']; ?>">
                            <a href="<?php echo site_url('storyboard/view/' . $obj['id']); ?>"> 
                                <div class="tiles white cards text-center pagination-centered" style="position:relative;">
                                  <?php
                                    echo '<img src="' . img_url('empty_template_start_' . $obj["start_end_template"] . '.png') . '" style="width:100%; height:100%;" />';
                                    if ($obj['start_end_template'] == 1) {
                                        if (!empty($obj['start_image']))
                                            echo '<img src="' . $obj["start_image"] . '" style="width:90px; height:80px; position:absolute; position:absolute; top:27px; left:1px" />';
                                        if (!empty($obj['title']))
                                            echo '<span style="position:absolute; position:absolute; top:56px; left:100px; font-family:verdana; font-size:12px; line-height:15px; color:#fff; text-align:center; width:190px; display:inline-block;">' . $obj['title'] . '</span>';
                                        if (!empty($obj['description']))
                                            echo '<div style="position:absolute; position:absolute; top:114px; left:130px; font-family:verdana; font-size:9px; line-height:12px; color:#666; text-align:left; width:156px; height:75px; overflow:hidden; display:inline-block;">' . $obj['description'] . '</div>';
                                    }
                                    if ($obj['start_end_template'] == 2) {
                                        if (!empty($obj['start_image']))
                                            echo '<img src="' . $obj['start_image'] . '" style="width:135px; height:135px; border-radius:50%; position:absolute; top:45px; left:15px" />';
                                        if (!empty($obj['title']))
                                            echo '<span style="position:absolute; position:absolute; top:12px; left:10px; font-family:verdana; font-size:12px; line-height:15px; color:#fff; text-align:left; width:290px; display:inline-block;">' . $obj['title'] . '</span>';
                                        if (!empty($obj['description']))
                                            echo '<div style="position:absolute; position:absolute; top:50px; left:168px; font-family:verdana; font-size:9px; line-height:12px; color:#fff; text-align:left; width:128px; height:130px; overflow:hidden; display:inline-block;">' . $obj['description'] . '</div>';
                                    }
                                    if ($obj['start_end_template'] == 3) {
                                        if (!empty($obj['title']))
                                            echo '<span style="position:absolute; position:absolute; top:22px; left:104px; font-family:verdana; font-size:12px; line-height:15px; color:#fff; text-align:left; width:190px; display:inline-block;">' . $obj['title'] . '</span>';
                                        if (!empty($obj['description']))
                                            echo '<div style="position:absolute; position:absolute; top:56px; left:134px; font-family:verdana; font-size:9px; line-height:12px; color:#fff; text-align:left; width:156px; height:100px; overflow:hidden; display:inline-block;">' . $obj['description'] . '</div>';
                                    }
                                  ?>
                                </div>
                            </a>
                            <div class="tiles gray p-t-5 p-b-5  m-b-20">
                              <p class="text-center text-white semi-bold  small-text"> 
                                <?php echo cut_string($obj['title'], 32); ?>
                                  <a class="fa fa-retweet flipper" data-card-id="<?php echo  $obj['id']; ?>" title="flip Storyboard" href="#"></a>
                                  <a title="<?php echo $circles; ?>" class="fa fa-circle-o tooltip-toggle tooltip-right" data-toggle="tooltip" data-placement="bottom"></a>   
                              </p>  
                            </div>
                            

                            <div class="m-l-10 card_flipped" style="display: none">
                                <div class="flip_sb"
                                   data-card-id="<?php echo $obj['id']; ?>"
                                   data-card-flipped="no"
                                   title="flip Storyboard" >
                                    <div class="white text-left flip_div pagination-centered">
                                        <p><strong>Author : </strong> <?php echo $obj['author']; ?><br/></p>
                                        <p><strong>Title : </strong> <?php echo $obj['title']; ?><br/></p>
                                        <p><strong>Date Created : </strong> <?php echo $obj['creation_time']; ?><br/></p>
                                        <p><strong># Viewed : </strong> <?php echo $obj['viewed'];    ?><br/></p>
                                    </div>
                                     <div class="tiles gray p-t-5 p-b-5  m-b-20">
                                      <p class="text-center text-white semi-bold  small-text"> 
                                        <?php echo cut_string($obj['title'], 32); ?>
                                          <a class="fa fa-retweet flipper" data-card-id="<?php echo $obj['id']; ?>" title="flip Storyboard" href="#"></a>
                                          <a title="<?php echo $circles; ?>" class="fa fa-circle-o tooltip-toggle tooltip-right" data-toggle="tooltip" data-placement="bottom"></a>   
                                      </p>  
                                    </div>
                                    
                                </div>

                            </div>
                        </div>
                    </li>

                <?php } ?>
              </ul>
            </div>
        </div>

			</div>


		</div>
	</div>
</div>
