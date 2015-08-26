<?php
  $folder =   dirname(dirname(__FILE__));
  $back_url = site_url('/storyboard/my_storyboards');
  if ( !empty($op) && $op == "view_") {
    $id = $obj->id;
    $guid = id_to_guid($id);
    $back_url = site_url('/storyboard/all');
  }
  $user_avatar = avatar_url($sb_user);
  $this->load->view('storyboard/content');
 ?>
<div style="margin: -40px 25px 0; padding:10px; background:#fff;">
  <div>
    <a target="_blank" href="https://twitter.com/home?status=<?php echo rawurlencode('Check out this story on #idaciti: '.$obj->title.' ').site_url('storyboard/embed/'.$guid); ?>" style="color:#fff; padding: 10px 16px; font-weight: bold; border-radius: 17px; background:#46aeed; margin-right:10px; float:right; margin-top:-30px;"><i class="fa fa-twitter"></i> TWEET THIS STORY</a>
  </div>
</div>


<a href="http://www.idaciti.com" target="_blank" style="border-radius: 20px; position: absolute; left: 10px; padding: 5px 15px; background: #22262e; bottom: 20px;">
  <img src="<?php echo img_url('logo.png'); ?>" alt="Idaciti" width="100" />
</a>

<?php require_once $folder.'/card/dd_dialogs.php'; ?>

