<?php
if (empty($current))
    $current = 'home';
header("Cache-Control: no-cache, must-revalidate");
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
        <title><?php echo $title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="description" content="<?php echo $description; ?>" />
        <meta name="keywords" content="<?php echo $keywords; ?>" />
        <meta name="author" content="<?php echo $author; ?>" />
        <meta name="google-site-verification" content="<?php echo $google_verification; ?>" />
        <!--[if IE 8 ]>    <script src="<?php echo js_url('PIE_IE678'); ?>"></script> <![endif]-->
        <!--[if IE 9 ]>    <script src="<?php echo js_url('PIE_IE9'); ?>"></script> <![endif]-->
        <link href="<?php echo img_url('icon.png'); ?>" rel="shortcut icon" />
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
                        <h2>Reset your password</h2>
                    </div>

                    <div class=" grey p-t-20 p-b-20 text-black"><!--colorgrey-->
                        <div class="col-md-8 form-container">
                            <form id="frm_login" class="animated fadeIn" action="<?php echo site_url('/login/rp_submit'); ?>" method="post">      
                                <div class="row form-row m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
                                    <?php echo validation_errors() ?>
                                </div>
                                <?php if (!empty($message)) { ?> <div class="alert alert-success"><?php echo urldecode($message); ?></div><?php } ?>

                                <div class="row form-row m-l-10 m-r-10 xs-m-l-5 xs-m-r-5">
                                    <div class="col-md-12 col-sm-12 ">
                                        <input name="email" id="email" type="text"  class="form-control" placeholder="Email">
                                    </div>
                                </div>

                                <div class="row form-row m-l-10 m-r-10 xs-m-l-5 xs-m-r-5">
                                    <div class="col-md-6 col-sm-6 col-md-offset-3">
                                        <button type="submit" class="btn btn-primary btn-cons  pull-right" id="login_toggle">Reset password</button> 
                                    </div>
                                </div>

                            </form>
                        </div>

                        <div class="clearfix"></div>
                    </div> 



                </div>   
            </div>
        </div>
        <script src="<?php echo js_lib('jquery-1.8.3.min'); ?>"></script>
        <script src="<?php echo js_lib('js/bootstrap.min', 'bootstrap'); ?>"></script>
        <script src="<?php echo js_lib('pace.min', 'pace'); ?>"></script>
        <script src="<?php echo js_lib('js/jquery.validate.min', 'jquery-validation'); ?>"></script>
        <script src="<?php echo js_lib('jquery.lazyload.min', 'jquery-lazyload'); ?>"></script>
        <script src="https://api.github.com/repos/lipis/bootstrap-social?callback=callback"></script>
        <script src="<?php echo js_url('login_v2'); ?>"></script>

    </body>
</html>