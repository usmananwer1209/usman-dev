<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 5/8/2015
 * Time: 3:02 PM
 */
?>

<!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js msie ie6 lte9 lte8 lte7"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js msie ie7 lte9 lte8 lte7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js msie ie8 lte9 lte8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js msie ie9 lte9"> <![endif]-->
<html lang="en">
    <head>
        <?php
        if (empty($title))
            $title = app_name();
        if (empty($description))
            $description = app_name();
        if (empty($keywords))
            $keywords = app_name();
        if (empty($author))
            $author = app_name();
        if (empty($google_verification))
            $google_verification = app_name();
        ?>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta charset="utf-8" />
<title><?php echo app_name(); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="description" content="<?php echo $description; ?>" />
<meta name="keywords" content="<?php echo $keywords; ?>" />
<meta name="author" content="<?php echo $author; ?>" />
<meta name="google-site-verification" content="<?php echo $google_verification; ?>" />
<!--[if IE 8 ]>    <script src="<?php echo js_url('PIE_IE678'); ?>"></script> <![endif]-->
<!--[if IE 9 ]>    <script src="<?php echo js_url('PIE_IE9'); ?>"></script> <![endif]-->
<link href="<?php echo img_url('icon.png'); ?>" rel="shortcut icon" />
<link href="http://res.cloudinary.com/hrscywv4p/image/upload/c_limit,h_64,w_64/ptxyaphihrv8vqqzjjtr.png" rel="shortcut icon" type="image/x-icon">
<link href="<?php echo css_lib('pace-theme-flash', 'pace'); ?>" rel="stylesheet" />
<link href="<?php echo css_lib('css/bootstrap.min', 'boostrapv3'); ?>" rel="stylesheet" />
<link href="<?php echo css_lib('css/bootstrap-theme.min', 'boostrapv3'); ?>" rel="stylesheet" />
<link href="<?php echo css_lib('css/font-awesome', 'font-awesome'); ?>" rel="stylesheet" />
<link href="<?php echo css_url('animate.min'); ?>" rel="stylesheet" />
<!-- END CORE CSS FRAMEWORK -->
<!-- BEGIN CSS TEMPLATE -->
<link href="<?php echo css_url('style'); ?>" rel="stylesheet" />
<link href="<?php echo css_url('responsive'); ?>" rel="stylesheet" />
<link href="<?php echo css_url('magic_space'); ?>" rel="stylesheet" />
<link href="<?php echo css_url('custom-icon-set'); ?>" rel="stylesheet" />
<link href="http://lipis.github.io/bootstrap-social/bootstrap-social.css" rel="stylesheet" />
</head>
<body class="error-body no-top lazy pace-done"
      style="background-image: url('<?php echo site_url("assets/img/work.jpg"); ?>'); display: block;"
    >
<div class="container">
    <div class="row login-container animated fadeInUp">
        <div class="col-md-7 col-md-offset-2 tiles gray no-padding">
            <div class="p-t-30 p-l-40 p-b-20 xs-p-t-10 xs-p-l-10 xs-p-b-10 loginlogo">
                <img src="<?php echo site_url("assets/img/logo.png"); ?>" alt=""  data-src="<?php echo site_url("assets/img/logo.png"); ?>"
                     data-src-retina="<?php echo site_url("assets/img/logo.png"); ?>" width="140" height="50"/></a>
                <span>Preview</span>
            </div>


            <div class=" grey p-t-20 p-b-20 text-black"><!--colorgrey-->
                <div class="col-md-8 ">

                    <div class="row form-row m-l-10 m-r-10 xs-m-l-5 xs-m-r-5">
                        <div class="col-md-12 col-sm-12 ">
                            The application is down for maintenance. Please try back later.
                        </div>
                    </div>

                </div>

                <div class="clearfix"></div>
            </div>



        </div>
    </div>
</div>


</body>
</html>