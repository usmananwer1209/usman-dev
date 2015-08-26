<?php $filter_file = true ?>
<link href="<?php echo img_url('icon.png'); ?>" rel="shortcut icon" />
<link href="<?php echo css_lib('css/bootstrap.min','boostrapv3')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet" />
<link href="<?php echo css_lib('css/bootstrap-theme.min','boostrapv3')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet" />

<link href="<?php echo css_lib('pace-theme-flash.min','pace')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet" />
<link href="<?php echo css_lib('css/jquery.sidr.light.min','jquery-slider')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet" />
<link href="<?php echo css_lib('css/font-awesome.min','font-awesome')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet" />
<link href="<?php echo css_url('custom-icon-set.min')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet" />
<link href="<?php echo css_lib('css/bootstrap-formhelpers','vlamanna-BootstrapFormHelpers')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet" />
<link href="<?php echo css_lib('select2','bootstrap-select2')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet" />
<link href="<?php echo css_lib('ios7-switch','ios-switch')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet" type="text/css" media="screen">


<?php if( active_plugin('jasny',$current) ) { ?>
	<link href="<?php echo css_lib('css/jasny-bootstrap.min','jasny-bootstrap-3.1.0-dist')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet" />
<?php } ?>
<?php if( active_plugin('rcarousel',$current) ) { ?>
	<link href="<?php echo css_lib('css/rcarousel','rcarousel')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet" />
<?php } ?>

<?php if( active_js('card',$current) ) { ?>
	<link href="<?php echo css_lib('jquery.qtip','qtip2')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet"/>
	<link href="<?php echo css_lib('bootstrap-wysihtml5','bootstrap-wysihtml5')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet"/>
  <link href="<?php echo css_lib('jquery.dataTables', 'jquery-datatable/css')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet"/>
  <link href="<?php echo css_lib('datatables.responsive', 'datatables-responsive/css')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet" type="text/css" media="screen"/>
    <link href="<?php echo css_url('drilldown'); ?>" rel="stylesheet" />


<?php } ?>

<?php if( active_js('storyboard',$current) ) { ?>
    
    

  <link href="<?php echo css_lib('bootstrap-wysihtml5','bootstrap-wysihtml5')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet"/>
  <link rel="stylesheet" type="text/css" href="<?php echo css_lib('css/baraja','baraja')."?v=".$this->config->item('plugins_version'); ?>" />
  <link rel="stylesheet" type="text/css" href="<?php echo css_lib('css/custom','baraja')."?v=".$this->config->item('plugins_version'); ?>" />

    <link href="<?php echo css_url('drilldown'); ?>" rel="stylesheet" />

<?php } ?>

<?php if( active_js('storyboard_view',$current) ) { ?>
  <link rel="stylesheet" type="text/css" href="<?php echo css_lib('jquery.bxslider','bxslider')."?v=".$this->config->item('plugins_version'); ?>" />
  <link rel="stylesheet" href="<?php echo css_lib('jquery.mCustomScrollbar', 'jquery-custom-scrollbar')."?v=".$this->config->item('plugins_version'); ?>" />

    <link href="<?php echo css_url('drilldown'); ?>" rel="stylesheet" />

<?php } ?>
<link href="<?php echo css_url('style')."?v=".$this->config->item('files_version'); ?>" rel="stylesheet" />
<link href="<?php echo css_url('responsive.min')."?v=".$this->config->item('plugins_version'); ?>" rel="stylesheet" />
<link href="<?php echo css_url('rys.explore')."?v=".$this->config->item('files_version'); ?>" rel="stylesheet" />

<link href="<?php echo css_url('damond/component') ?>" rel="stylesheet" />

<link href="<?php echo css_url('damond/demo') ?>" rel="stylesheet" />





