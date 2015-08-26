<?php $filter_file = true; ?>

<script src="<?php echo js_lib('jquery-1.8.3.min')."?v=".$this->config->item('plugins_version'); ?>"></script>
<!--<script src="<?php echo js_lib('jquery-1.8.3')."?v=".$this->config->item('plugins_version'); ?>"></script>-->
<script src="<?php echo js_lib('js/bootstrap.min','bootstrap')."?v=".$this->config->item('plugins_version'); ?>"></script>
<script src="<?php echo js_lib('pace.min','pace')."?v=".$this->config->item('plugins_version'); ?>"></script>
<script src="<?php echo js_lib('jquery.sidr.min','jquery-slider')."?v=".$this->config->item('plugins_version'); ?>"></script>
<script src="<?php echo js_lib('jquery.unveil.min','jquery-unveil')."?v=".$this->config->item('plugins_version'); ?>"></script>
<script src="<?php echo js_lib('breakpoints')."?v=".$this->config->item('plugins_version'); ?>"></script>
<script src="<?php echo js_lib('jquery.slimscroll.min','jquery-slimscroll')."?v=".$this->config->item('plugins_version'); ?>"></script>
<script src="<?php echo js_lib('jqueryblockui','jquery-block-ui')."?v=".$this->config->item('plugins_version'); ?>"></script>
<script src="<?php echo js_lib('js/jquery.validate.min','jquery-validation')."?v=".$this->config->item('plugins_version'); ?>"></script>
<script src="<?php echo js_lib('select2.min','bootstrap-select2/')."?v=".$this->config->item('plugins_version'); ?>"></script>
<script src="<?php echo js_lib('js/bootstrap-formhelpers.min','vlamanna-BootstrapFormHelpers')."?v=".$this->config->item('plugins_version'); ?>"></script>
<script src="<?php echo js_lib('ios7-switch','ios-switch')."?v=".$this->config->item('plugins_version'); ?>" type="text/javascript"></script>

<script src="<?php echo js_url('d3.min'); ?>"></script>
<script src="<?php echo js_url('unicode'); ?>"></script>
<script src="<?php echo js_url('d3.layout.cloud'); ?>"></script>
<script src="<?php echo js_url('cloud'); ?>"></script>

<?php if( active_plugin('jasny',$current) ) { ?>
	<script src="<?php echo js_lib('js/jasny-bootstrap.min','jasny-bootstrap-3.1.0-dist')."?v=".$this->config->item('plugins_version'); ?>"></script>
<?php } ?>
<?php if( active_plugin('mixitup',$current) ) { ?>
	<script src="<?php echo js_lib('jquery.mixitup.min','jquery-mixitup')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_url('search_results')."?v=".$this->config->item('files_version'); ?>"></script>
<?php } ?>
<?php if( active_plugin('rcarousel',$current) ) { ?>
	<script src="<?php echo js_lib('js/jquery.ui.core.min','rcarousel')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_lib('js/jquery.ui.widget.min','rcarousel')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_lib('js/jquery.ui.rcarousel.min','rcarousel')."?v=".$this->config->item('plugins_version'); ?>"></script>
<?php } ?>



<script src="<?php echo js_lib('jquery.flippy.min','jquery-flippy')."?v=".$this->config->item('plugins_version'); ?>"></script>
<script src="<?php echo js_url('core')."?v=".$this->config->item('files_version'); ?>"></script>
<script src="<?php echo js_url('rys/core')."?v=".$this->config->item('files_version'); ?>"></script>
<?php if( active_plugin('rcarousel',$current) ) { ?>
	<script src="<?php echo js_url('rys/circle')."?v=".$this->config->item('files_version'); ?>"></script>
    
<?php } ?>

<?php if( active_plugin('isotope',$current) ) { ?>
<script type="text/javascript" src="<?php echo js_lib('jquery-css-transform','rotate3Di')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script type="text/javascript" src="<?php echo js_lib('rotate3Di','rotate3Di')."?v=".$this->config->item('plugins_version'); ?>"></script>

	<script src="<?php echo js_lib('isotope.pkgd')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_url('rys/card_isotop')."?v=".$this->config->item('files_version'); ?>"></script>

<?php } ?>

<?php if( active_js('card',$current) ) { ?>
	<script src="<?php echo js_lib('browserdetect')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_lib('jquery-ui-1.10.4.custom.min','jquery-ui')."?v=".$this->config->item('plugins_version'); ?>"></script>
	
	<script src="<?php echo js_lib('jquery-jvectormap-1.2.2.min','jvectormap')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_lib('jquery-jvectormap-us-aea-en','jvectormap')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_lib('jquery.qtip.min','qtip2')."?v=".$this->config->item('plugins_version'); ?>"></script>
    <script src="<?php echo js_lib('jquery.dataTables.min','jquery-datatable/js/')."?v=".$this->config->item('plugins_version'); ?>"></script>
    <script src="<?php echo js_lib('TableTools.min','jquery-datatable/extra/js/')."?v=".$this->config->item('plugins_version'); ?>"></script>
    <script type="text/javascript" src="<?php echo js_lib('datatables.responsive','datatables-responsive/js/')."?v=".$this->config->item('plugins_version'); ?>"></script>
    <script type="text/javascript" src="<?php echo js_lib('lodash.min','datatables-responsive/js/')."?v=".$this->config->item('plugins_version'); ?>"></script>

	<!--<script src="//cdnjs.cloudflare.com/ajax/libs/d3/2.8.1/d3.v2.min.js"></script>-->
	<!--<script src="http://davidstutz.github.io/bootstrap-multiselect/js/bootstrap-multiselect.js"></script>-->

    <script src="<?php echo js_lib('wysihtml5-0.3.0','bootstrap-wysihtml5')."?v=".$this->config->item('plugins_version'); ?>"></script>
    <script src="<?php echo js_lib('bootstrap-wysihtml5','bootstrap-wysihtml5')."?v=".$this->config->item('plugins_version'); ?>"></script>
        
	<script src="<?php echo js_url('highcharts')."?v=".$this->config->item('plugins_version'); ?>"></script>
    <script type="text/javascript" src="https://d33t3vvu2t2yu5.cloudfront.net/tv.js"></script>
	<script src="<?php echo js_url('rys/card')."?v=".$this->config->item('files_version'); ?>"></script>
	<script src="<?php echo js_url('datatable_script')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_url('rys/explore_rank')."?v=".$this->config->item('files_version'); ?>"></script>
	<script src="<?php echo js_url('rys/map')."?v=".$this->config->item('files_version'); ?>"></script>
	<script src="<?php echo js_url('rys/treemap')."?v=".$this->config->item('files_version'); ?>"></script>
	<script src="<?php echo js_url('rys/chart')."?v=".$this->config->item('files_version'); ?>"></script>
	<script src="<?php echo js_url('rys/list_builder')."?v=".$this->config->item('files_version'); ?>"></script>

    <!-- drilldown requirements-->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/numeral.js/1.4.5/numeral.min.js"></script>
    <script src="http://d3js.org/d3.v3.min.js"></script>
    <script src="<?php echo js_url('drilldown')?>"></script>
<?php } ?>

<?php if( active_js('storyboard',$current) ) { ?>
	<script src="<?php echo js_lib('browserdetect')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_lib('jquery-ui-1.10.4.custom.min','jquery-ui')."?v=".$this->config->item('plugins_version'); ?>"></script>
	
	<script src="<?php echo js_lib('jquery.qtip.min','qtip2')."?v=".$this->config->item('plugins_version'); ?>"></script>
  <script src="<?php echo js_lib('jquery.dataTables.min','jquery-datatable/js/')."?v=".$this->config->item('plugins_version'); ?>"></script>
  <script src="<?php echo js_lib('TableTools.min','jquery-datatable/extra/js/')."?v=".$this->config->item('plugins_version'); ?>"></script>
    <script src="<?php echo js_url('bootstrap-multiselect'); ?>"></script>
    <script src="<?php echo js_lib('wysihtml5-0.3.0','bootstrap-wysihtml5')."?v=".$this->config->item('plugins_version'); ?>"></script>
  <!--  /* Usman Code*/-->
  <script src="<?php echo js_url('rys/wysihtmleditor'); ?>"></script>
 <!-- /*Usman Ends*/-->

	<script src="<?php echo js_lib('js/modernizr.custom.79639', 'baraja')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_lib('js/jquery.baraja', 'baraja')."?v=".$this->config->item('plugins_version'); ?>"></script>

	<script src="<?php echo js_lib('jquery.knob','fileupload')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_lib('jquery.ui.widget','fileupload')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_lib('jquery.iframe-transport','fileupload')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_lib('jquery.fileupload','fileupload')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_lib('jquery.bxslider.min','bxslider')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_lib('bootstrap-maxlength.min')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_url('rys/storyboard_creator')."?v=".$this->config->item('files_version'); ?>"></script>
        
<?php } ?>

<?php if( active_js('storyboard_view',$current) ) { ?>
	<script src="<?php echo js_lib('browserdetect')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_lib('jquery.bxslider.min','bxslider')."?v=".$this->config->item('plugins_version'); ?>"></script>
  <script src="<?php echo js_lib('jquery.mCustomScrollbar.concat.min', 'jquery-custom-scrollbar')."?v=".$this->config->item('plugins_version'); ?>"></script>
	<script src="<?php echo js_url('rys/storyboard_viewer')."?v=".$this->config->item('files_version'); ?>"></script>
<?php } ?>
<?php if( active_js('home',$current) ) { ?>
  <script src="<?php echo js_lib('jquery-sparkline', 'jquery-sparkline')."?v=".$this->config->item('plugins_version'); ?>"></script>
  <script src="<?php echo js_lib('jquery.easypiechart.min', 'jquery-easy-pie-chart/js')."?v=".$this->config->item('plugins_version'); ?>"></script>
  <script src="<?php echo js_url('rys/dashboard')."?v=".$this->config->item('files_version'); ?>"></script>
<?php } ?>

<?php if(empty($is_embed) || !$is_embed ) { ?>
  <script>
  // Include the UserVoice JavaScript SDK (only needed once on a page)
  UserVoice=window.UserVoice||[];(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/kc0C7PrxxWiF71IzaK0QoQ.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})();

  //
  // UserVoice Javascript SDK developer documentation:
  // https://www.uservoice.com/o/javascript-sdk
  //

  // Set colors
  UserVoice.push(['set', {
    accent_color: '#6aba2e',
    trigger_color: 'white',
    trigger_background_color: '#6aba2e'
  }]);

  // Identify the user and pass traits
  // To enable, replace sample data with actual user traits and uncomment the line
  UserVoice.push(['identify', {
  	<?php if(!empty($user->email)) echo "email: '".$user->email."',"; ?>
  	<?php if(!empty($user->first_name) && !empty($user->last_name)) echo "name: '".$user->first_name." ".$user->last_name."',"; ?>
    //email:      'john.doe@example.com', // Userâ€™s email address
    //name:       'John Doe', // Userâ€™s real name
    //created_at: 1364406966, // Unix timestamp for the date the user signed up
    //id:         123, // Optional: Unique id of the user (if set, this should not change)
    //type:       'Owner', // Optional: segment your users by type
    //account: {
    //  id:           123, // Optional: associate multiple users with a single account
    //  name:         'Acme, Co.', // Account name
    //  created_at:   1364406966, // Unix timestamp for the date the account was created
    //  monthly_rate: 9.99, // Decimal; monthly rate of the account
    //  ltv:          1495.00, // Decimal; lifetime value of the account
    //  plan:         'Enhanced' // Plan name for the account
    //}
  }]);

  // Add default trigger to the bottom-right corner of the window:
  UserVoice.push(['addTrigger', { mode: 'contact', trigger_position: 'bottom-right' }]);

  // Or, use your own custom trigger:
  //UserVoice.push(['addTrigger', '#id', { mode: 'contact' }]);

  // Autoprompt for Satisfaction and SmartVote (only displayed under certain conditions)
  UserVoice.push(['autoprompt', {}]);
  </script>
<?php } ?>
<?php if($current == '/circle/add'){?>
<script>
$(document).ready(function(e) {
    $('input[name=selectbox3]').val($('#admin_id').val());
});
</script>
<?php }?>
<script>
$(document).ready(function(e) {
	setTimeout(
    function() {
      $('#load_more_btn').show();
    }, 5000);
});
</script>