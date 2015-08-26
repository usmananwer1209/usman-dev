<table class="table" id="example3" >
  <thead>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Organization</th>
      <th>Country</th>
      <th>Is admin</th>

		

      	<?php 
		$parent_circle = (strpos($current, '/circle/edit') === 0);
      	if( !$parent_circle ){ ?>
  			<th>Circles</th>	
			<th>Cards</th>	
			<th>Edit</th>
      	<?php }
				else {
 ?>
      		<th class="hide">Status</th>
  		<?php } ?>
      
    </tr>
  </thead>
  <tbody>


<?php 
  $i = 0;
  if(!empty($objs)){
  foreach ($objs as $u) {
  	$u = (object)$u;
  	?>
    <tr class="<?php echo ($i%2==0)?'even':'odd';?> gradeA">
      <td class="center"><?php echo $u -> first_name . ' ' . $u -> last_name; ?></td>
      <td class="center"><?php echo $u -> email; ?></td>
      <td class="center"><?php echo $u -> organization; ?></td>
      <td class="center">
        <?php if(!empty($u->country)){?>
          <span class="bfh-countries" data-country="<?php echo $u -> country; ?>"  title="<?php echo $u -> country; ?>" data-flags="true" data-blank="false"></span>
        <?php } ?>
      </td>
      <td class="center">
        <?php if($u->is_root == 1){?>
          <span class="label label-important">ADMIN</span>
        <?php } ?>
      </td>
      
      	<?php if( !$parent_circle ){ ?>
	      <td class="center">
	        <?php if($u->circle_count==0){ ?> 
	                <span class="inactive_url">0</span> 
            <?php }
			else { ?>
	                <a href="<?php echo site_url('/circle/all/' . $u -> id); ?>"><?php echo $u -> circle_count; ?></a>
	        <?php } ?>
	      </td>
	      <td class="center">0</td>
	      <td class="center">
	        <a href="<?php echo site_url('/profile/edit/' . $u -> id); ?>">
	          <i class="fa fa-edit"></i>
	        </a>
	      </td>        
      	<?php } else { ?>
  			<td class="center hide">
  				
  				


			<?php 
				$user_in_circle_status = $this->user_circles->user_in_circle_status((object)$obj,(object)$u);
			?>
			<?php if($user_in_circle_status==user_circle_status::request_wait){ ?>
	            <code href="#"  data-toggle="modal" data-modal-id="#modalJoin"
	                data-circel-id="<?php echo $obj->id;?>"
	                data-circel-name="<?php echo $obj->name;?>"
	                data-circel-description="<?php echo $obj->description;?>"
	                data-user-id="<?php echo $u->id;?>"
	                data-user-name="<?php echo $u->name;?>"
	                >wait</code>
	        <?php } else if($user_in_circle_status==user_circle_status::request_accept){ ?>
	            <code href="#"  data-toggle="modal" data-modal-id="#modalLeave"
	                data-circel-id="<?php echo $obj->id;?>"
	                data-circel-name="<?php echo $obj->name;?>"
	                data-circel-description="<?php echo $obj->description;?>"
	                data-user-id="<?php echo $u->id;?>"
	                data-user-name="<?php //echo $u->name;?>"
	                >leave</code>
	        <?php } else if($user_in_circle_status==user_circle_status::request_reject){ 
            //var_dump($u);
            //die();
            ?>
	            <code href="#"  data-toggle="modal" data-modal-id="#modalJoin"
	                data-circel-id="<?php echo $obj->id;?>"
	                data-circel-name="<?php echo $obj->name;?>"
	                data-circel-description="<?php echo $obj->description;?>"
	                data-user-id="<?php echo $u->id;?>"
	                data-user-name="<?php echo $u->first_name.' '.$u->last_name;?>"
	                >reject</code>
	        <?php } ?>
  				

  				
        	</td>
    	<?php } ?>
    </tr>
<?php
$i++;
}
}
?>


  </tbody>
</table>





<div  id="modalJoin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">join Circle</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
        <div class="alert alert-error hide">The operation could not be completed.</div>
      Are you sure you want to <code><span class="user_name"></span></code> join <code><span class="circle_name"></span></code> ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary ajax_submit">
            Join
        </button>
      </div>
    </div>
  </div>
</div>

<div  id="modalUnjoin" class="modal fade"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">leave Circle</span></h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
        <div class="alert alert-error hide">The operation could not be completed.</div>
        Are you sure you want to <code><span class="user_name"></span></code> leave <code><span class="circle_name"></code></span> ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger ajax_submit">leave</button>
      </div>
    </div>
  </div>
</div>



