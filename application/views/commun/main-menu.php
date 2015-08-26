 <?php
 /* echo '<pre>';
  var_dump($my_circles);
  var_dump($cards);
  echo '</pre>';*/
 ?>
  <div class="page-sidebar" id="main-menu">
    <div class="page-sidebar-wrapper" id="main-menu-wrapper">
      <div class="user-info-wrapper clearfix">
        <div class="profile-wrapper"> 
                <a href="<?php echo site_url('/profile/view/' . $user->id); ?>">
          <img 
              <?php $cache_id=uniqid(); ?>
              src="<?php echo $avatar."?id=".$cache_id; ?>"  alt="" 
              data-src="<?php echo $avatar."?id=".$cache_id; ?>" 
              data-src-retina="<?php echo $avatar."?id=".$cache_id; ?>" 
              width="69" height="69" /> 
                </a>
        </div>
        <div class="user-info">
          <div class="greeting">Welcome</div>
          <div class="username">
            <?php echo $user->first_name; ?>
            <span class="semi-bold">
              <?php echo $user->last_name; ?>
            </span>
          </div>
          
        </div>
      </div>
      <ul>
        <li class="<?php echo (module_active($current, 'Dashboard'))?'start active open':''; ?>"> 
        	<a href="home"> 
            <i class="icon-custom-home"></i> 
            <span class="title">Dashboard</span>
          </a> 
        </li>
        <li class="<?php echo (module_active($current, 'Browse Cards'))?'start active open':''; ?>"> 
          <a href="<?php echo site_url('card/all');?>"> 
            <i class="fa fa-th"></i> 
            <span class="title">Browse Cards</span>
            <span class="arrow"></span>
          </a> 
            <ul class="sub-menu">
                <li>
                  <a href="<?php echo  site_url('card/all/'); ?>" title="contains <?php echo $all_accessible_cards_number; ?> card(s)">
                    <i class="fa fa-th-large m-r-10 green" style="width:14px;"></i>
                    all
                  </a> 
                </li>  
              <?php foreach ($my_circles as $c) { ?>
                  <li>
                    <a href="<?php echo  site_url('card/all/'.$c['id']); ?>" title="contains <?php echo $c['cards']; ?> card(s)">
                      <i class="fa fa-circle-o m-r-10 green" style="width:14px;"></i>
                      <?php echo $c['name']; ?>
                    </a> 
                  </li>  
              <?php } ?>
            </ul>
        </li>
        <li class="<?php echo (module_active($current, 'Browse Storyboards'))?'start active open':''; ?>"> 
                <a href="<?php echo site_url('storyboard/all'); ?>"> 
                    <i class="fa fa-film"></i>
                    <span class="title">Browse Storyboards</span>
                    <span class="arrow"></span>
                </a> 
                <ul class="sub-menu">
                    <li>
                        <a href="<?php echo site_url('storyboard/all/'); ?>" title="contains <?php echo $all_accessible_storyboards_number; ?> storyboard(s)">
                            <i class="fa fa-th-large m-r-10 green" style="width:14px;"></i>
                            all
                        </a> 
                    </li>  
                    <?php foreach ($my_circles as $c) { ?>
                        <li>
                            <a href="<?php echo site_url('storyboard/all/' . $c['id']); ?>" title="contains <?php echo $c['storyboards']; ?> storyboard(s)">
                                <i class="fa fa-circle-o m-r-10 green" style="width:14px;"></i>
                                <?php echo $c['name']; ?>
                            </a> 
                        </li>  
                    <?php } ?>
                </ul>
        </li>

        <li class="<?php echo (module_active($current, 'Browse Circles'))?'start active open':''; ?>"> 
          <a href="home"> 
            <i class="fa fa-users"></i> 
            <span class="title">Browse Circles</span>
            <span class="arrow"></span>
          </a> 
          <ul class="sub-menu">
                    <?php
                    if (!empty($my_circles)) {
                        foreach ($my_circles as $c) {
                            ?>
                <li>
                  <a href="<?php echo  site_url('circle/view/'.$c['id']); ?>">
                    <i class="fa fa-dot-circle-o m-r-10 green" style="width:14px;"></i>
                    <?php echo $c['name']; ?>
                  </a> 
                </li>
                            <?php
                        }
                    }
                    ?>
                    <?php
                    if (!empty($all_circles)) {
              foreach ($all_circles as $c) { 
                            if (!is_id_in_elmts_array($c['id'], $my_circles)) {
                  echo '<li>';
                  echo '  <a href="'.site_url('circle/view/'.$c['id']).'">';
                                echo '    <i class="fa fa-circle-o m-r-10 orange" style="width:14px;"></i>' . $c['name'];
                                ;
                  echo '  </a>';
                  echo '</li>';
                }
              }
                    }
                    ?>
          </ul>
        </li>
        <?php if(has_acces($user,"/card/")){ ?>
          <li class="<?php echo (module_active($current, 'My Cards'))?'start active open':''; ?>"> 
            <a href="<?php echo site_url('/card/'); ?>">
              <i class="fa fa-th-large"></i>
              <span class="title">My Cards</span>
            </a>
          </li>
        <?php } ?>
        <li class="<?php echo (module_active($current, 'My Storyboards'))?'start active open':''; ?>"> 
          <a href="<?php echo site_url('/storyboard/'); ?>"> <i class="fa fa-briefcase"></i> <span class="title">My Storyboards</span></a> 
        </li>
        <?php if(!empty($my_circles )){ ?>
        <li class="<?php echo (module_active($current, 'My Circles'))?'start active open':''; ?>"> 
          <li class="<?php echo is_active($current, '/circle/all/'.$user->id); ?>"> 
            <a href="<?php echo site_url('/circle/all/'.$user->id); ?>">
              <i class="fa fa-sitemap"></i> 
              <span class="title">My Circles</span>
              <span class="arrow"></span>
            </a> 
            <ul class="sub-menu">
                        <?php
                        foreach ($my_circles as $c) {
                            if ($c['is_admin'] == 'true') {
                                ?>
                  <li>
                                    <a href="<?php echo site_url('circle/view/' . $c['id']); ?>">
                      <i class="fa fa-circle-o m-r-10 green" style="width:14px;"></i>
                      <?php echo $c['name']; ?> (Admin)
                    </a> 
                  </li>  
        <?php } else { ?>
                  <li>
                    <a href="<?php echo  site_url('circle/view/'.$c['id']); ?>">
                      <i class="fa fa-circle-o m-r-10 green" style="width:14px;"></i>
                      <?php echo $c['name']; ?>
                    </a> 
                  </li>  
                <?php }?>
              <?php } ?>
            </ul>
          </li>
        </li>
        <?php } ?>
      </ul>

      <ul>
            <li class="<?php echo is_active($current, '/circle/all/' . $user->id); ?>"> 
                <a class="menu-title" href="javascript:void(0)">
                    <span class="title">Recently Created Cards</span>
                    <span class="arrow"></span>
                </a>

                <ul class="folders sub-menu" >
          <?php 
          $i = 1;
          foreach ($my_recent_5_cards as $c) {  
            if($i<=5){
            ?>
              <li>
                <a href="<?php echo  site_url('card/edit/'.$c['id']); ?>">
                  <i class="fa fa-square-o m-r-10"></i> 
                  <span title="<?php echo strip_tags($c['description']); ?>">

                    <?php echo cut_string($c['name'],25); ?>
                  </span>
                </a>
              </li>
            <?php 
            }
            $i++;
          } 
          ?>
        </ul>
            </li>
      </ul>

        <ul>
            <li class="<?php echo is_active($current, '/circle/all/' . $user->id); ?>"> 
                <a class="menu-title" href="javascript:void(0)">
                    <span class="title">Recently Created Storyboards</span>
                    <span class="arrow"></span>
                </a>

                <ul class="folders sub-menu" >
                    <?php
                    $i = 1;
                    foreach ($my_recent_5_storyboards as $c) {
                        if ($i <= 5) {
                            ?>
                            <li>
                                <a href="<?php echo site_url('storyboard/edit/' . $c['id']); ?>">
                                    <i class="fa fa-square-o m-r-10"></i> 
                                    <span title="<?php echo strip_tags($c['description']); ?>">
                                        <?php echo cut_string($c['title'], 25); ?>
                                    </span>
                                </a>
                            </li>
                            <?php
                        }
                        $i++;
                    }
                    ?>
                </ul>
            </li>
        </ul>

      <div class="clearfix"></div>
    </div>
  </div>
  <div class="footer-widget">
    <p class="pull-left">Copyright &copy; <?php echo date('Y'); ?> idaciti, Inc.<br/> <a target="_blank" href="http://terms.idaciti.com/">Terms &amp; Privacy</a> </p>
    
    <div class="pull-right" style="padding-top: 10px;">
      <a href="<?php echo site_url('/login/logout');?>"><i class="fa fa-power-off"></i></a></div>
    </div>
  </div>
