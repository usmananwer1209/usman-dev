<!--<div id="search_filters" class="pull-left">
	<input type="text" aria-controls="example" class="input-medium">
	<a id="Search" href="javascript:;">Search</a>
</div>-->
<div class="pull-right">
	<?php if($user->is_root == 1){?>
		<a href="<?php echo site_url('/circle/add');?>">
			<button class="btn btn-success btn-cons" type="button">
	        	<i class="fa fa-check"></i>&nbsp;Add</button>	
	    </a>  
    <?php } ?>
</div>
<div class="clearfix"></div>
<br/>
<div id="parks" class="just">
	<!-- "TABLE" HEADER CONTAINING SORT BUTTONS (HIDDEN IN GRID MODE)-->
	<div class="list_header">
		<div class="meta name active desc" id="SortByName">
			Circle &nbsp; 
			<span class="sort anim150 asc active" data-sort="data-name" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-name" data-order="desc"></span>
		</div>
		<div class="meta admin">
			Administrator &nbsp; 
			<span class="sort anim150 asc" data-sort="data-admin" data-order="asc"></span>
			<span class="sort anim150 desc" data-sort="data-admin" data-order="desc"></span>
		</div>
		<div class="meta users">
			Status
		</div>

	</div>
	<ul>

<?php 
  $i = 0;
  foreach ($objs as $obj) {
  	?>

            <li class="circle_list mix" 
		data-name="<?php echo $obj->name; ?>" 
		data-admin="<?php echo $obj->admin; ?>"
                data-circle-id="<?php echo $obj->id; ?>"
		>

		<div class="meta name">
			<div class="titles">
				<h2>
                                    <span><?php echo $obj->name ; ?></span>
					<?php if($user->is_root == 1){?>
				        <a href="<?php echo site_url('/circle/edit/'.$obj->id);?>">
          					<i class="fa fa-edit"></i>
        				</a>
                                <a class="fa fa-minus-circle"
                                   id="delete-circle"
                                   action=<?php echo site_url('/circle/remove/' . $obj->id); ?>
                                   data-toggle="modal"
                                   data-modal-id="#removeCircleModal"
                                   data-circle-id="<?php echo $obj->id; ?>"
                                   title="delete circle"></a>

    				<?php } ?>
				</h2>
				<p class="description">
					<?php echo substr(trim($obj->description),0,200); ?>
				</p>
			</div>
		</div>
		<div class="meta admin">
      		<code><?php echo user_full_name($obj->admin); ?></code>
		</div>
		<div class="meta users">
			<?php if( $obj->admin != $user->id ) { ?>
				<p>
		        <?php if($obj->user_circle==user_circle_status::not_fount){ ?>
		        	<em>
		            <a href="#"  data-toggle="modal" data-modal-id="#modalJoin"  
		                data-circel-id="<?php echo $obj->id;?>"
		                data-user-id="<?php echo $user->id;?>"
		                data-user-name="<?php echo $user->first_name.' '.$user->last_name;?>"
		                data-circel-name="<?php echo $obj->name;?>"
		                data-circel-description="<?php echo $obj->description;?>"
		                ><code class="text-success">join</code></a>
	                </em><br/>
		        <?php } else if($obj->user_circle==user_circle_status::request_wait){ ?>
		        	<em>
		            <code class="text-warning">wait</code>
	                </em><br/>
		        <?php } else if($obj->user_circle==user_circle_status::request_accept){ ?>
		        	<em>
		            <a href="#"  data-toggle="modal" data-modal-id="#modalUnjoin"
		                data-circel-id="<?php echo $obj->id;?>"
		                data-user-id="<?php echo $user->id;?>"
		                data-user-name="<?php echo $user->first_name.' '.$user->last_name;?>"
		                data-circel-name="<?php echo $obj->name;?>"
		                data-circel-description="<?php echo $obj->description;?>"
		                ><code class="text-error">leave</code></a>
	                </em><br/>
		        <?php } else if($obj->user_circle==user_circle_status::request_reject){ ?>
		        	<em>
	          		<span><code class="text-error">reject</code></span>
	                </em><br/>
		        <?php } ?>
		        </p>
			<?php }  else { ?>
				<span class="label label-important">ADMIN</span>
			<?php } ?>
		</div>
  	</li>
<?php } ?>
	</ul>
</div>




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
        <h4 class="modal-title" id="myModalLabel"><span>leave Circle</span></h4>
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

<div class="modal fade notif_modals" id="removeCircleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
                <div class="alert alert-error hide">The operation could not be completed.</div>
                <p>Are you sure you want to remove this circle ?
                    <i class="fa-li fa fa-spinner fa-spin loading hide"></i>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger ajax_submit">remove</button>
            </div>
        </div>
    </div>
</div>