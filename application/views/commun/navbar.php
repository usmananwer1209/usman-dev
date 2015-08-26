<div class="header navbar navbar-inverse ">
    <div class="navbar-inner">
    <div class="header-seperation">
      <a href="<?php echo  site_url('/home');?>">
        <img src="assets/img/logo.png" class="logo" alt=""  data-src="assets/img/logo.png" data-src-retina="assets/img/logo.png" width="126" />
      </a>
      <ul class="nav pull-right notifcation-center">
        
        <li class="dropdown" id="portrait-chat-toggler" style="display:none"> <a href="#sidr" class="chat-menu-toggle">
          <div class="iconset top-chat-white "></div>
        </a> </li>
      </ul>
    </div>
    <div class="header-quick-nav" >
      <div class="pull-left">
        <ul class="nav quick-section">
          <li class="quicklinks"> <a href="javascript:;" class="" id="layout-condensed-toggle" >
            <div class="iconset top-menu-toggle-dark"></div>
            </a> 
          </li>

          <?php if(!empty($active_search)){ ?>
          <li class="m-r-10 input-prepend inside search-form no-boarder">
                            <input id="search_input" name="" type="text"  class="no-boarder " placeholder="Search" style="width:250px;">
          </li>
          <?php } ?>

        </ul>
      </div>
      <div class="pull-right">
        <div class="chat-toggler"> 
          <a href="<?php echo site_url('/card/add'); ?>">
            <button id="main_menu_add_card" class="btn btn-success btn-cons" type="button">
                <i class="fa fa-square-o"></i>Add Card
            </button>
          </a>
            <a href="<?php echo site_url('storyboard/add'); ?>">
                <button id="main_menu_add_storyboard" class="btn btn-success btn-cons" type="button">
                  <i class="fa fa-film"></i>Add Storyboard
                </button>
            </a>
          <a href="#" class="dropdown-toggle" id="my-task-list" data-placement="bottom"  data-content='' data-toggle="dropdown" data-original-title="Notifications">
          <div class="user-details">
            <div class="username"> 
                <?php if(count($notifications)>0){ ?>
                    <span class="badge badge-important"><?php echo count($notifications);?></span> 
                  <?php } ?>
                  <?php echo $user->first_name;?>
                  <span class="bold">
                    <?php echo $user->last_name;?>
                  </span> 
                <input id="user_id" name="" type="hidden" value="<?php echo $this->session->userdata('user')->id;  ?>">
            </div>
          </div>
          <div class="iconset top-down-arrow"></div>
          </a>
          <!--TODO Notification-->
          <div id="notification-list" style="display:none">
            <div class="notification-container" style="width: 300px;">
                            <?php
                            $i=-1;
                            foreach ($notifications as $arr) {
                              $user_obj = (object) array('id' => $arr['user_id']);
                              ?>
                              <div class="notification" data-circel-id="<?php echo $arr['circle'];?>" data-user-id="<?php echo $arr['user'];?>">
                                  <div class="notification-messages <?php echo ((($i++)%2)?'info':'danger') ?>">
                                      <div class="user-profile"> <img src="<?php echo avatar_url($user_obj); ?>"  
                                            alt="" 
                                            data-src="<?php echo avatar_url($user_obj);?>" 
                                            data-src-retina="<?php echo avatar_url($user_obj); ?>" 
                                            width="35" height="35"> 
                                      </div>
                                      <div class="message-wrapper">
                                          <div class="heading"><?php echo $arr['user_name']; ?></div>
                                          <div class="description"> 
                                            send request to join to 
                                            <span><?php echo $arr['circle_name']; ?></span>
                                          </div>
                                        
                                          <div class="date"><?php echo ago(strtotime($arr['modification_time'])); ?></div>
                                      </div>
                                      <div class="buttons_group pull-right">
                                        <button class="btn btn-primary btn-xs btn-mini" type="button"
                                        data-toggle="modal" data-modal-id="#acceptModal"
                                        data-circel-id="<?php echo $arr['circle'];?>"
                                        data-user-id="<?php echo $arr['user'];?>"
                                        data-status="<?php echo $arr['status'];?>">
                                        Accept</button>
                                        <button class="btn btn-xs btn-mini btn-danger" type="button"
                                        data-toggle="modal" data-modal-id="#denyModal"
                                        data-circel-id="<?php echo $arr['circle'];?>"
                                        data-user-id="<?php echo $arr['user'];?>"
                                        data-status="<?php echo $arr['status'];?>">
                                        Deny</button>
                                    </div>
                              
                                    <div class="clearfix"></div>
                                  </div>
                              </div>
                            <?php 
                              }
                            ?>
              </div>
          </div>

          <div class="profile-pic"> 
                        <a href="<?php echo site_url('/profile/view/' .$user->id); ?>">
            <img 
                <?php $cache_id=uniqid(); ?>
                src="<?php echo  $avatar."?id=".$cache_id; ?>"  alt="" 
                data-src="<?php echo  $avatar."?id=".$cache_id; ?>" 
                data-src-retina="<?php echo  $avatar."?id=".$cache_id; ?>" 
                                 width="35" height="35" /> 
                        </a>
                    </div>
        </div>
        <ul class="nav quick-section ">
          

          <li class="quicklinks"> <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">
            <div class="iconset top-settings-dark "></div>
            </a>
            <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
              <li><a href="<?php echo site_url('/profile/edit');?>"> My Account</a> </li>
              <?php if($user->is_root){ ?>
                <li><a href="<?php echo site_url('/profile/all'); ?>">Manage Users</a></li>
                <li><a href="<?php echo site_url('/circle/all'); ?>">Manage Circles</a></li>
                <li><a href="<?php echo site_url('/home/admin');?>">Manage Companies & KPI’s</a> </li>
                <li><a href="<?php echo site_url('/home/of_the_day');?>">Select Card/Storyboard of the Day</a> </li>
              <?php } ?>
              <li class="divider"></li>
              <li><a href="<?php echo site_url('/login/logout');?>"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Log Out</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="modal fade notif_modals" id="acceptModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
                <div class="alert alert-error hide">The operation could not be completed.</div>
                <p>Are you sure you want to accept the user's request ?
                  <i class="fa-li fa fa-spinner fa-spin loading hide"></i>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary ajax_submit" id="notif_accept">Accept</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade notif_modals" id="denyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4></h4>
            </div>
            <div class="modal-body">
              <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
              <div class="alert alert-error hide">The operation could not be completed.</div>
              <p>Are you sure you want to deny the user's request ?
                <i class="fa-li fa fa-spinner fa-spin loading hide"></i>
              </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger ajax_submit" id="notif_deny">Deny</button>
            </div>
        </div>
    </div>
</div>


