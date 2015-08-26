<?php
	$folder =   dirname(dirname(__FILE__));
 	require_once $folder."/commun/navbar.php";
 ?>
<div class="page-container row-fluid">
  	<?php require_once $folder."/commun/main-menu.php";?>
  	<div class="page-content">

  		<div class="content">
		  <ul class="breadcrumb">
		    <li>
		      <a href="<?php echo site_url('/home');?>">HOME</a>
		    </li>
		    <li><a href="#" class="active">List Circles</a> </li>
		  </ul>
		
		  <div class="page-title"> <a href="<?php echo site_url('/home');?>"><i class="icon-custom-left"></i></a>
		    <h3>List <span class="semi-bold">Circles</span></h3>
		  </div>
		
		
		  <div class="row-fluid">
		    <div class="span12">
		      <div class="grid simple ">
		        <div class="grid-title no-border">
		          <h4></h4>
		        </div>
		        <div class="grid-body no-border">
		          
		            <?php require_once "grid.php";?>
		          
		        </div>
		      </div>
		    </div>
		  </div>
		</div>
  		
  		
	</div>
 </div>
<?php //require_once $folder."../commun/chat-window.php";?>
