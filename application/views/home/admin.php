<?php
	$folder =   dirname(dirname(__FILE__));
 	require_once $folder."/commun/navbar.php";
 ?>
<div class="page-container row-fluid">
  <?php require_once $folder."/commun/main-menu.php";?>
    <div class="page-content">


  		
<div class="content">

		<div class="col-md-8">

          	<!-- BEGIN MINI WEATHER WIDGET -->
			<div class="col-md-7 m-b-20 col-lg-6 col-sm-6    single-colored-widget">
              	<div class="tiles-container">
	                <div class="col-md-5  col-xs-5 no-padding">
                  		<div class="tiles green p-t-20">

        		            <a id="sync_companies" href="#"  data-toggle="modal" data-modal-id="#modalSynch" 
        		            	data-object="companies" data-operation="sync" data-action="<?php echo  site_url("/home/sync/companies");?>">
				                <i id="icon-resize" class="fa fa-5x fa-cloud-upload white h-align-middle custom-icon-space"></i>
			                </a>
			                <h6 class="bold text-white text-center p-b-15">Synchronize</h6>

	                  </div>
	                </div>
	                <div class="col-md-7 col-xs-7 no-padding">
	                  	<div class="tiles white text-center">
	                    	<h2 class="semi-bold text-success weather-widget-big-text no-margin p-t-20 p-b-10"><?php echo $count_companies;?></h2>
	                    	<div class="tiles-title blend m-b-5">Companies</div>
		                    <div class="pull-left m-l-15 ">
	                        	<h5 class="semi-bold no-margin "><?php echo $companies_last_sync;?></h5>
	                        	<p class="bold text-extra-small ">last update</p>
		                    </div>
	                    	<div class="clearfix"></div>
	                  	</div>
	                </div>
              	</div>
				<div class="heading">
					<div class="pull-left">
						<h3><span class="semi-bold">Companies</span></h3>
					</div>
					<div class="clearfix"> </div>
				</div>
			</div>
			  <!-- END WEATHER DETAIL VIEW WIDGET -->	
          	<!-- BEGIN MINI WEATHER WIDGET -->
			<div class="col-md-7 m-b-20 col-lg-6 col-sm-6  single-colored-widget">
              	<div class="tiles-container">
                	<div class="col-md-5  col-xs-5 no-padding">
                  		<div class="tiles blue p-t-20">
        		            <a id="sync_kpis"  href="#"  data-toggle="modal"
        		            		data-object="KPIs" data-operation="sync" data-action="<?php echo  site_url("/home/sync/kpis");?>">
				                <i id="icon-resize" class="fa fa-5x fa-cloud-upload h-align-middle custom-icon-space"></i>
			                </a>
			                <h6 class="bold text-white text-center p-b-15">Synchronize</h6>
                  		</div>
                	</div>
	            	<div class="col-md-7 col-xs-7 no-padding">
	                  	<div class="tiles white text-center">
	                    	<h2 class="semi-bold text-primary weather-widget-big-text no-margin p-t-20 p-b-10"><?php echo $count_kpis;?></h2>
	                    	<div class="tiles-title blend m-b-5">KPIs</div>
	                    	<div class="pull-left m-l-15 ">
		                      	<div class="inline">
		                        	<h5 class="semi-bold no-margin "><?php echo $kpis_last_sync;?></h5>
		                        	<p class="bold text-extra-small ">last update</p>
		                      	</div>
		                    </div>
	                    	<div class="clearfix"></div>
	                  	</div>
	                </div>
              	</div>
				<div class="heading">
					<div class="pull-left">
						<h3> <span class="semi-bold">KPIs</span></h3>
					</div>
					<div class="clearfix"> </div>
				</div>
			</div>
			<!-- END WEATHER DETAIL VIEW WIDGET -->	




		</div>
</div>




<div  id="modalSynch" class="modal fade admin_modals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Synchronization</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
        <div class="alert alert-error hide">The operation could not be completed.</div>
	    <p>Are you sure you want to <span class="obj_action"></span> <span class="obj_sync"></span> ?
	    	<i class="fa-li fa fa-spinner fa-spin loading hide"></i>
	    </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary ajax_submit">
            Submit 
        </button>
      </div>
    </div>
  </div>
</div>






	</div>
 </div>
<?php //require_once $folder."../commun/chat-window.php";?>
