<?php
	$folder =   dirname(dirname(__FILE__));
 	require_once $folder."/commun/navbar.php";
 ?>
<div class="page-container row-fluid">
  	<?php require_once $folder."/commun/main-menu.php";?>
  	<div class="page-content">
  		<?php require_once "form.php";?>
	</div>
 </div>
<?php //require_once $folder."../commun/chat-window.php";?>
