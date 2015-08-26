<?php
  $folder =   dirname(dirname(__FILE__));
  require_once $folder."/commun/navbar.php";
  $back_url = site_url('/storyboard/my_storyboards');
  if ( !empty($op) && $op == "view_") {
    $id = $obj->id;
    $guid = id_to_guid($id);
    $back_url = site_url('/storyboard/all');
  }
  $user_avatar = avatar_url($sb_user);
 ?>
<div class="page-container row-fluid">
  <?php require_once $folder."/commun/main-menu.php";?>
  <div class="page-content storyboard_container">


  	<div class="storyboard_view" class="row">	
      <div class="clearfix"></div>
      
      <div class="content" style="padding-top:65px;">

        <div class="page-title" style="margin-bottom:0;">
            <a href="<?php echo $back_url; ?>"><i class="icon-custom-left"></i></a>
          <?php 
            if($op == "edit_"){
              echo '<h3>Edit <span class="semi-bold">Storyboard</span>: '.$title.'</h3>';
            } 
            elseif($op == "view_") {
              echo '<h3>View <span class="semi-bold">Storyboard</span>: '.$title.'</h3>';
            }  
            else {
              echo '<h3>Add <span class="semi-bold">New Storyboard</span></h3>';
            } 
          ?>
        </div>

        <?php $this->load->view('storyboard/content'); ?>
  				
  		</div>

    </div>
    <?php     
    if($obj->public == 1) {
    ?>
    <div style="margin: -40px 25px 0; padding:10px; background:#fff;">
      <div>
        <a target="_blank" href="https://twitter.com/home?status=<?php echo rawurlencode('Check out this story on #idaciti: '.$obj->title.' ').site_url('storyboard/embed/'.$guid); ?>" style="color:#fff; padding: 10px 16px; font-weight: bold; border-radius: 17px; background:#46aeed; margin-right:10px; float:left;"><i class="fa fa-twitter"></i> TWEET THIS STORY</a>


</BR></BR></BR>

        <a target="_blank" href="<?php echo site_url('storyboard/embed/'.$guid); ?>" style="color:#fff; padding: 10px 16px; font-weight: bold; border-radius: 17px; background:#46aeed; margin-rigt:10px; float:left;"><i class="fa fa-external-link"></i> EMBED THIS STORY</a>


        <div style="margin-left: 180px;"><p style="background:#ddd; font-family:courier; padding: 2px 10px;">&lt;iframe  src="<?php echo site_url('storyboard/embed/'.$guid); ?>" frameborder="0" style="min-width:980px; min-height:720px;"&gt;&lt;/iframe&gt;</p></div>
      </div>
    </div>
    <?php 
    }
    ?>
    <div class="disqus_container" >
      <?php $this->load->view('general/disqus'); ?>
    </div>

  </div>
</div>

<?php require_once $folder.'/card/dd_dialogs.php'; ?>