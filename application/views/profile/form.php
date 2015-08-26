<?php
if($op == "edit_my_" || $op == "edit_" ){
	$id = $obj->id;
	$first_name = $obj->first_name;
	$last_name =  $obj->last_name;
	$organization =    $obj->organization;
	$country = $obj->country;
	$email = $obj->email;
	$password = $obj->password;
  $about = $obj->about;
  $twitter_profile = $obj->twitter_profile;
  $facebook_profile = $obj->facebook_profile;
  $google_profile = $obj->google_profile;
  $linkedin_profile = $obj->linkedin_profile;
	if(!empty($obj->public_profile) && $obj->public_profile =="1" )
		$public_profile = "1";
	else
		$public_profile = "0";


  if(!empty($obj->is_active) && $obj->is_active =="1" )
    $is_active = "1";
  else
    $is_active = "0";

  if(!empty($obj->is_root) && $obj->is_root =="1" )
    $is_root = "1";
  else
    $is_root = "0";

	$_avatar = $obj->avatar;
}
else{
	$id="";
	$first_name = "";
	$last_name = "";
	$organization = "";
	$country = "US";
	$email = "";
	$password  = "";
  $about = "";
  $facebook_profile = "";
  $twitter_profile = "";
  $google_profile = "";
  $linkedin_profile = "";
	$public_profile = "1";
  $is_active = "1";
  $is_root = "0";
	$_avatar = avatar_default_url();
}
?>

  	<div class="clearfix"></div>
  	<div class="content">
  		<ul class="breadcrumb">
  			<li>
  				<a href="<?php echo site_url('/home');?>">HOME</a>
  			</li>
  			<li><a href="#" class="active">Profile</a> </li>
  		</ul>
  		<div class="page-title"> <a href="<?php echo site_url('/home');?>"><i class="icon-custom-left"></i></a>
  			<?php 
  			if($op == "edit_my_"){
  				?><h3>Edit <span class="semi-bold"> My Profile</span></h3><?php
    } elseif ($op == "edit_") {
  				?><h3>Edit <span class="semi-bold"><?php echo $first_name." ".$last_name?></span> Profile</h3><?php
    } else {
  				?><h3>Add<span class="semi-bold"> New Profile</span></h3><?php	
  			}
  			?>
  			
  		</div>
  		<div class="row">
  			<div class="grid simple">
  				<div class="grid-title no-border">
  					<h4></h4>
  				</div>
  				<div class="grid-body no-border">
  					<?php echo validation_errors(); ?>
            <?php if(!empty($message)) { ?> <div class="alert alert-success"><?php echo $message;  ?></div><?php } ?>

            
				<form id="submit_profile" action="<?php echo site_url('/profile/submit');?>"   method="POST" enctype="multipart/form-data" 
              class="form-horizontal col-sm-12  <?php echo ($current=="/profile/add")?"add":"edit"; ?>">
						<input id="op" name="op" type="hidden" value="<?php echo $op; ?>">
						<input id="id" name="id" type="hidden" value="<?php echo $id; ?>">                             
  						


              <div class="form-group">
  							<label class="col-sm-2 control-label" for="first_name">First Name</label>
  							<div class="input-with-icon col-sm-10">                                       
  								<i class="fa fa-user"></i>
  								<input id="first_name" name="first_name" type="text" class="form-control" value="<?php echo $first_name; ?>">                                 
  							</div>
  						</div>

  						<div class="form-group">
  							<label class="col-sm-2 control-label"  for="last_name">Last Name</label>
  							<div class="input-with-icon col-sm-10">                                       
  								<i class="fa fa-user"></i>
  								<input type="text" class="form-control" id="last_name" name="last_name"  value="<?php echo $last_name; ?>">                                 
  							</div>
  						</div>

						  <div class="form-group">
  							<label class="col-sm-2 control-label"  for="email">Email</label>
  							<div class="input-with-icon col-sm-10">                                       
  								<i class="fa fa-envelope"></i>
  								<input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>">                                 
                  <input type="hidden" name="h_email" value="<?php echo $email; ?>" >
  							</div>
  						</div>
						
              <div class="form-group">
  							<label class="col-sm-2 control-label"  for="password">Password</label>
  							<div class="input-with-icon col-sm-10">                                       
  								<i class="fa fa-asterisk"></i>
  								<input id="password" name="password"  type="password" value="<?php echo $password; ?>"  class="form-control"></label>                                
  							</div>
  						</div>
              <div class="form-group">
  							<label class="col-sm-2 control-label"  for="repassword">Confirm Password</label>
  							<div class="input-with-icon col-sm-10">                                       
                  <i class="fa fa-asterisk"></i>
  								<input  name="repassword"  type="password" value="<?php echo $password; ?>"  class="form-control"></label>
  							</div>
  						</div>
  						  						
  						  
  						<div class="form-group">
  							<label class="col-sm-2 control-label"  for="organization">Company</label>
  							<div class="input-with-icon col-sm-10">                                       
                  <i class="fa fa-tag"></i>
  								<input type="text" class="form-control" id="organization" name="organization" value="<?php echo $organization; ?>">                                 
  							</div>
  						</div>

  						<div class="form-group">
  							<label class="col-sm-2 control-label"  for="country">Country</label>
  							<div class="input-with-icon col-sm-10">                                       
  								<div id="country" name="country" class="bfh-selectbox bfh-countries" data-country="<?php echo $country; ?>" data-flags="true"  data-filter="true">
  								</div>              
  							</div>
  						</div>

              <div class="form-group">
                <label class="col-sm-2 control-label"  for="about">About</label>
                <div class="input-with-icon col-sm-10">                                       
                  <textarea id="about" name="about" type="text" class="form-control" 
                      value="<?php echo trim($about); ?>"><?php echo trim($about); ?></textarea>
                </div>              
              </div>

  						<div class="form-group">
                <label class="col-sm-2 control-label"  for="checkbox23">Public Profile</label>
                <div class="input-with-icon col-sm-10">                
                  <div class="checkbox check-success  ">
                      <input id="public_profile" name="public_profile" type="checkbox" <?php echo ($public_profile=='1')?'checked="checked"':''; ?>>
                      <label for="public_profile"></label>
                  </div>
                </div>
  						</div>

              <div class="form-group">
                <label class="col-sm-2 control-label"  for="checkbox23">Active</label>
                <div class="input-with-icon col-sm-10">                
                  <div class="checkbox check-success  ">
                      <input id="is_active" name="is_active" type="checkbox" <?php echo ($is_active=='1')?'checked="checked"':''; ?>>
                      <label for="is_active"></label>
                  </div>
                </div>
              </div>

            <?php  if($op !== "edit_my_"){ ?>
              <div class="form-group">
                <label class="col-sm-2 control-label"  for="checkbox23">Administrator</label>
                <div class="input-with-icon col-sm-10">                
                  <div class="checkbox check-success  ">
                      <input id="is_root" name="is_root" type="checkbox" <?php echo ($is_root=='1')?'checked="checked"':''; ?>>
                      <label for="is_root"></label>
                  </div>
                </div>
              </div>
            <?php } ?>

  						<div class="form-group">
  							<label class="col-sm-2 control-label">Avatar</label>
                <div class="input-with-icon col-sm-10">      
    							<div class="fileinput fileinput-new" data-provides="fileinput">
    							  	<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                         <img data-src="<?php 
                            $cache_id=uniqid();
                              if($op=="add_") 
                                echo avatar_default_url()."?id=".$cache_id;
                              else if($op=="edit_my_") 
                                echo $avatar."?dummy="."?id=".$cache_id;
                              else if($op=="edit_") 
                                echo avatar_url($obj)."?id=".$cache_id ?>" 
                              src="<?php 
                                if($op=="add_") 
                                  echo avatar_default_url()."?id=".$cache_id;
                                else if($op=="edit_my_") 
                                  echo $avatar."?dummy="."?id=".$cache_id;
                                else if($op=="edit_") 
                                  echo avatar_url($obj)."?id=".$cache_id?>"
                          >        
                      </div>
    							  	<div>
                          <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="avatar"></span>
    							    	<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
    						  		</div>
    							</div>
                </div>
  						</div>

                
              <div class="form-group">
                <label class="col-sm-2 control-label"  for="twitter_profile">Twitter</label>
                <div class="input-with-icon col-sm-10">                                       
                  <i class="fa fa-twitter"></i>
                  <input type="text" class="form-control" id="twitter_profile" name="twitter_profile" value="<?php echo $twitter_profile; ?>">                                 
                </div>
              </div>
             <!--    
             <div class="form-group">
                <label class="col-sm-2 control-label"  for="facebook_profile">Facebook</label>
                <div class="input-with-icon col-sm-10">                                       
                  <i class="fa fa-facebook"></i>
                  <input type="text" class="form-control" id="facebook_profile" name="facebook_profile" value="<?php //echo $facebook_profile; ?>">                                 
                </div>
              </div>
                
              <div class="form-group">
                <label class="col-sm-2 control-label"  for="google_profile">Google</label>
                <div class="input-with-icon col-sm-10">                                       
                  <i class="fa fa-google-plus"></i>
                  <input type="text" class="form-control" id="google_profile" name="google_profile" value="<?php //echo $google_profile; ?>">                                 
                </div>
              </div>
                -->
              <div class="form-group">
                <label class="col-sm-2 control-label"  for="linkedin_profile">LinkedIn</label>
                <div class="input-with-icon col-sm-10">                                       
                  <i class="fa fa-linkedin"></i>
                  <input type="text" class="form-control" id="linkedin_profile" name="linkedin_profile" value="<?php echo $linkedin_profile; ?>">                                 
                </div>
              </div>



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

  	</div>
  
