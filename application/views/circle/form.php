<?php
if ($op == "edit_") {
	$id = $obj -> id;
	$name = $obj -> name;
	$description = $obj -> description;
	$admin = $obj -> admin;
} else {
	$id = "";
	$id = "";
	$name = "";
	$description = "";
	$admin = "";
}
?>
	<div class="clearfix"></div>
  	<div class="content">
  		<ul class="breadcrumb">
  			<li>
  				<a href="<?php echo site_url('/home'); ?>">HOME</a>
  			</li>
  			<li><a href="#" class="active">Circle</a> </li>
  		</ul>
  		<div class="page-title"> <a href="<?php echo site_url('/home'); ?>"><i class="icon-custom-left"></i></a>
  			<?php 
  			if($op == "edit_"){
  				?><h3>Edit Circle:<span class="semi-bold"><?php echo $name; ?></span></h3>
          <?php }
		    else{
  				?><h3>Add<span class="semi-bold"> New Circle</span></h3>
        <?php } ?>
  		</div>
  		<div class="row">
  			<div class="grid simple">
  				<div class="grid-title no-border">
  					<h4></h4>
  					<div class="tools"> 
  						<a class="collapse" href="javascript:;"></a> 
  						<a class="reload" href="javascript:;"></a> 
  						<a class="remove" href="javascript:;"></a> 
  					</div>
  				</div>
  				<div class="grid-body no-border">
  					<?php echo validation_errors(); ?>
            <?php if(!empty($message)) { ?> <div class="alert alert-success"><?php echo $message; ?></div><?php } ?>

  					<form id="submit_circle" action="<?php echo site_url('/circle/submit'); ?>"   method="POST" enctype="multipart/form-data"
                  class="form-horizontal col-sm-12"
                  >
  						  <input id="op" name="op" type="hidden" value="<?php echo $op; ?>">
  						  <input id="id" name="id" type="hidden" value="<?php echo $id; ?>">                             
    						

                <div class="form-group">
    							<label class="col-sm-2 control-label" for="name">Name</label>
    							<div class="input-with-icon col-sm-10">                                       
                    <i class="fa fa-circle-o"></i>
    								<input id="name" name="name" type="text" class="form-control" value="<?php echo $name; ?>">                                 
                    <input type="hidden" name="h_name" value="<?php echo $name; ?>" >
    							</div>
    						</div>

                <div class="form-group">
                  <label class="col-sm-2 control-label" for="description">Description</label>
                  <div class="input-with-icon col-sm-10">                                       
                    <i class="fa fa-info"></i>
                    <textarea id="description" name="description" type="text" class="form-control" value="<?php echo trim($description); ?>"><?php echo trim($description); ?></textarea>
                  </div>
                </div>

          			<div class="form-group">
          					<label class="col-sm-2 control-label"  for="admin">Administrator</label>
          					<div class="input-with-icon col-sm-10">
          						  <i class="fa fa-user"></i>
      			            <div class="bfh-selectbox" data-name="selectbox3" data-value="<?php echo $admin; ?>" data-filter="true">
      			              <?php
							  	$count = 1;
							   foreach ($users as $u) {  
							    if($count == 1){
									$admin_id = $u -> id;
								}
							   ?>
      			                <div data-value="<?php echo $u -> id; ?>"><?php echo $u -> first_name . ' ' . $u -> last_name; ?></div>
      			              <?php
							  	$count = 2;
							   } ?>
      			            </div>
          					</div>
          			</div> 						

							<input type="hidden" id="admin_id" value="<?php echo $admin_id;?>"/>
    						<div class="form-actions">  
    							<div class="pull-right">
    								<button class="btn btn-white btn-cons" type="button">Cancel</button>
                    <button class="btn btn-success btn-cons" type="submit"><i class="icon-ok"></i> Save</button>
    							</div>
    						</div>
  					</form>

  				</div>
  			</div>
  		</div>


      <div class="row">
        <div class="grid simple">
          <div class="grid-title no-border">
            Members of the circle
            <h4></h4>
            <div class="tools"> 
              <a class="collapse" href="javascript:;"></a> 
              <a class="reload" href="javascript:;"></a> 
              <a class="remove" href="javascript:;"></a> 
            </div>
          </div>
          <div class="grid-body no-border">
          <?php
            $folder =   dirname(dirname(__FILE__));
            require_once $folder."/profile/grid2.php";
          ?>
          </div>
        </div>
      </div>
  	</div>
    
  
  