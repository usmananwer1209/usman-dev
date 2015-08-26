<div class="content">
	<div class="row">
		<div class="profile_area  col-md-3">
				<div class="administrator">
					<div class="h3 circle_name m-t-5">
					<?php echo $circle->name; ?>
					</div>
					<div class="admin_img m-t-5 clearfix">
						<img class="img-circle pull-left "  width="75" height="75" 
						src="<?php echo  avatar_url($admin); ?>">
						<div class="h4">Administrator</div>
                    <?php if ($admin->id == $user->id) { ?>
                        <a href="<?php echo site_url('circle/edit/' . $circle->id); ?>">
                            <i class="fa fa-edit"></i>
                        </a>
                    <?php } ?>
					</div>

					<div class="admin_info m-t-5">
						<div class="h3">
						<?php echo $admin->first_name.'.'.$admin->last_name; ?>
						</div>
						<small class="text-muted"> <i class="fa fa-map-marker"></i>
							<?php echo $admin->country; ?>
						</small>
					</div>
				</div>
				<div class="statistic clearfix">
					<div class="col-xs-4 statistic-members">
						<span class="m-b-xs h4 block">
							<?php echo count($members); ?>
						</span><br/>
						<small class="text-muted">Members</small>
					</div>
					<div class="col-xs-4 statistic-cards">
						<span class="m-b-xs h4 block">
							<?php echo count($cards); ?>
						</span><br/>
						<small class="text-muted">Cards</small>
					</div>
          <div class="col-xs-4 statistic-cards">
            <span class="m-b-xs h4 block">
              <?php echo count($storyboards); ?>
            </span><br/>
            <small class="text-muted">Storyboards</small>
          </div>
				</div>
				<div class="m-t-10 join-btn">

 					<?php if(enum_user_circle_status($user_circle)==user_circle_status::not_fount && !($circle->admin == $user->id)) { ?>
						<a class="btn2 btn2-success btn2-rounded" href="javascript:;"
							data-toggle="modal" data-modal-id="#modalJoin"  
			                data-circel-id="<?php echo $circle->id;?>"
			                data-user-id="<?php echo $user->id;?>"
			                data-user-name="<?php echo $user->first_name.' '.$user->last_name;?>"
			                data-circel-name="<?php echo $circle->name;?>"
                       data-circel-description="<?php echo strip_tags($circle->description); ?>">
							<i class="fa fa-plus"></i> Ask to Join
						</a>
					<?php } else if(enum_user_circle_status($user_circle)==user_circle_status::request_wait && !($circle->admin == $user->id) ){ ?>
						<a class="btn btn-default  btn2-rounded" href="javascript:;">
							Wait Response
						</a>
					<?php } else if(enum_user_circle_status($user_circle)==user_circle_status::request_accept && !($circle->admin == $user->id) ){ ?>
						<a class="btn btn-danger btn2-danger btn2-rounded" href="javascript:;"
							data-toggle="modal" data-modal-id="#modalUnjoin"  
			                data-circel-id="<?php echo $circle->id;?>"
			                data-user-id="<?php echo $user->id;?>"
			                data-user-name="<?php echo $user->first_name.' '.$user->last_name;?>"
			                data-circel-name="<?php echo $circle->name;?>"
                       data-circel-description="<?php echo strip_tags( $circle->description); ?>">
							Leave
						</a>
					<?php } else if(enum_user_circle_status($user_circle)==user_circle_status::request_reject && !($circle->admin == $user->id) ){ ?>
						<a class="btn btn-default btn2-rounded" href="javascript:;">
							Rejected
						</a>
					<?php } ?>

				</div>
				<div class="m-t-5 trusted-circle">
					<span class="text-uc text-xs text-muted">Trusted circle created on</span><br/>
					<small class="text-uc text-xs text-muted">
						<?php echo $circle->creation_time; ?>
					</small>
				</div>
				<div class="m-t-10 more-about">
					<span class="text-uc text-xs text-muted">More about the circle</span>
					<p>
                    <?php echo strip_tags($circle->description); ?>
					</p>
				</div>
				<div class="m-t-10 connecting">
					<small class="text-uc text-xs text-muted">
						Connecting with <?php echo $admin->first_name.'.'.$admin->last_name; ?>
					</small>
					<p class="m-t-sm">
                    <?php
                    if (!empty($admin->linkedin_profile)) {
                        ?>
                        <a class="btn btn-rounded btn-twitter btn-icon" target="_blank"
                           href="<?php echo "http://" . $admin->linkedin_profile ?>">
							<i class="fa fa-linkedin"></i>
						</a>

                        <?php
                    }
                    ?>
                    <?php
                    /*if (!empty($admin->google_profile)) {
                        ?>
                    <a class="btn btn-rounded btn-gplus btn-icon" target="_blank"
                           href="<?php echo "http://" . $admin->google_profile ?>">
							<i class="fa fa-google-plus"></i>
						</a>

                        <?php
                    }*/
                    ?>
                    <?php
                    /*if (!empty($admin->facebook_profile)) {
                        ?>
                        <a class="btn btn-rounded btn-facebook btn-icon" target="_blank"
                           href="<?php echo "http://" . $admin->facebook_profile ?>">
							<i class="fa fa-facebook"></i>
						</a>
                        <?php
                    }*/
                    ?>
					<?php
                    if (!empty($admin->twitter_profile)) {
                        ?>
                        <a class="btn btn-rounded btn-twitter btn-icon" target="_blank"
                           href="<?php echo "http://" . $admin->twitter_profile ?>">
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
				<div>
					<?php if(enum_user_circle_status($user_circle)==user_circle_status::request_accept  || $circle->admin==$user->id ) {?>
						<span>Members:</span>
						<div class="carousel">
							<div class="sriwriw">
								<?php foreach ($members as $member) { ?>
									<a class="user-avatar" href="<?php echo site_url('profile/view/'.$member['id']); ?>">
										<img class="img-circle"  width="75" height="75" 
										src="<?php echo  avatar_url((object)$member); ?>">
										<small>
										<?php echo $member['first_name'].' '.$member['last_name']; ?>
										</small>
									</a>
								<?php } ?>
							</div>
							<a href="javascript:;" id="ui-carousel-next"></a>					
							<a href="javascript:;" id="ui-carousel-prev"></a>
						</div>
					<?php } ?>

				</div>
				<div class="most-viewed clearfix">
					  <span class="block">Most Viewed Cards from <?php echo $circle->name; ?>:</span>
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
      							<?php if(enum_user_circle_status($user_circle)==user_circle_status::request_accept || ($circle->admin == $user->id) ){ ?>
      								<a href="<?php echo site_url('card/view/'.$card->id); ?>">
                    <?php } ?>
                      <div class="tiles white cards text-center pagination-centered <?php echo $card->type; ?>"></div>
                    <?php if(enum_user_circle_status($user_circle)==user_circle_status::request_accept || ($circle->admin == $user->id) ){ ?>
                      </a>
                      <?php } ?>
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
            <span class="block">Most Viewed Storyboards from <?php echo $circle->name; ?>:</span>
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



<div  id="modalJoin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">join Circle</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
        <div class="alert alert-error hide">The operation could not be completed.</div>
    	Are you sure you want to join <code><span class="circle_name"></span></code> ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary ajax_submit">
            Join
        </button>
      </div>
    </div>
  </div>
</div>

<div  id="modalUnjoin" class="modal fade"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><span>leave Circle</span></h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
      	<div class="alert alert-error hide">The operation could not be completed.</div>
      	Are you sure you want to <code><span class="user_name"></span></code> leave <code><span class="circle_name"></code></span> ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger ajax_submit">leave</button>
      </div>
    </div>
  </div>
</div>