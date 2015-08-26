<?php 
	if(empty($title)) $title = app_name();
	if(empty($description)) $description = app_name();
	if(empty($keywords)) $keywords = app_name();
	if(empty($author)) $author = '';
	if(empty($google_verification)) $google_verification = '';
	header("Cache-Control: no-cache, must-revalidate");
?>

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta charset="utf-8" />
<title><?php echo app_name(); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="description" content="<?php echo $description; ?>" />
<meta name="keywords" content="<?php echo $keywords; ?>" />
<meta name="author" content="<?php echo $author; ?>" />
<meta name="google-site-verification" content="<?php echo $google_verification; ?>" />
<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE" />
<meta HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE" />	
<link href="http://res.cloudinary.com/hrscywv4p/image/upload/c_limit,h_64,w_64/ptxyaphihrv8vqqzjjtr.png" rel="shortcut icon" type="image/x-icon">


