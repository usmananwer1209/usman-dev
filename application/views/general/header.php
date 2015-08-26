<?php 
	if(empty($current)) $current = 'home';
	//header("Cache-Control: max-age=2592000");
?>
<!doctype html>
<html lang="en">
<head>
	<base href="<?php echo  site_url('/');?>">  
	<script type="text/javascript">
	var site_url = "<?php echo site_url(); ?>";
	</script>
  <?php require "meta.php";?>
  <?php require "css.php";?>
  <?php require "js_header.php";
  if($this->uri->segment(2)=='view')
  {
  	$_SESSION['pagename'] = 'show';
  }
  if($this->uri->segment(2)=='edit' || $this->uri->segment(2)=='add')
  {
  	$_SESSION['pagename'] = 'hide';
  }
  ?>
</head>

<body class="" ondragstart="return false" draggable="false" style="<?php if(!empty($is_embed) && $is_embed ) echo 'background:#fff;'; ?>">

<?php require "analyticstracking.php";?>





