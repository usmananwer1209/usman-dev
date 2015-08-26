<!--<div id="search_filters" class="pull-left" >
	<input type="text" aria-controls="example" class="input-medium">
	<a id="Search" href="javascript:;">Search</a>
</div>-->
<div class="pull-right">
	<?php if($user->is_root == 1){?>
	    <a href="<?php echo site_url('/profile/add');?>">
			<button class="btn btn-success btn-cons" type="button">
	        	<i class="fa fa-check"></i>&nbsp;Add
		   	</button>
	    </a>  
	<?php } ?>
	
</div>

<div class="clearfix"></div>
<br/>

<div id="parks" class="just">
	<!-- "TABLE" HEADER CONTAINING SORT BUTTONS (HIDDEN IN GRID MODE)-->
	<div class="list_header controls">
		<div class="meta name active desc">
			Name/Email &nbsp; 
			<span class="sort asc " data-sort="data-name" data-order="asc"></span>
			<span class="sort desc" data-sort="data-name" data-order="desc" ></span>
		</div>
		<div class="meta company">
			Company &nbsp; 
			<span class="sort asc" data-sort="data-company" data-order="asc"></span>
			<span class="sort desc" data-sort="data-company" data-order="desc"></span>
		</div>
		<div class="meta country">
			Country &nbsp; 
			<span class="sort asc" data-sort="data-country" data-order="asc"></span>
			<span class="sort desc" data-sort="data-country" data-order="desc"></span>
		</div>
		<?php if($user->is_root == 1){?>
		<div class="meta cards">
			Cards
			<span class="sort asc active" data-sort="data-cards" data-order="asc"></span>
			<span class="sort desc" data-sort="data-cards" data-order="desc"></span>
		</div>
		<div class="meta circles">
			Circles
			<span class="sort asc active" data-sort="data-circles" data-order="asc"></span>
			<span class="sort desc" data-sort="data-circles" data-order="desc"></span>
		</div>
		<div class="meta pending_requests">
			Requests
			<span class="sort asc active" data-sort="data-pending" data-order="asc"></span>
			<span class="sort desc" data-sort="data-pending" data-order="desc"></span>
		</div>
		<?php } ?>
	</div>

	<ul>
		
	<?php 
  		foreach ($objs as $obj) {
  			$obj = (object)$obj;
  			?>

            <li class="user_list mix" 
				data-filter="[data-last],[data-first],[data-company],[data-country]" 
				data-first="<?php echo $obj->first_name; ?>" 
				data-name="<?php echo $obj->first_name . ' ' . $obj->last_name; ?>" 
				data-company="<?php echo $obj->organization; ?>"
				data-country="<?php echo $obj->country; ?>"
				data-last="<?php echo $obj->last_name; ?>" 
				data-cards="<?php echo $obj->cards; ?>"
				data-circles="<?php echo $obj->circle_accept; ?>"
				data-pending="<?php echo $obj->circle_wait; ?>"
                data-user-id="<?php echo $obj->id; ?>"
				>
			<div class="meta name">
				<div class="img_wrapper">
					<img src="<?php echo avatar_url($obj)."?id=".uniqid(); ?>" alt="" class="avatar mix-image"/>
				</div>
				<div class="titles">
					<h2 class="<?php echo  ($obj->is_root == 1)?'is_admin':'';?>" >
                                            <span><?php echo $obj -> first_name . ' ' . $obj -> last_name; ?></span>
						<?php if($user->is_root == 1){?>
					        <a href="<?php echo site_url('/profile/edit/'.$obj->id);?>">
	          					<i class="fa fa-edit"></i>
	        				</a>

                                <?php if ($obj->is_root != 1) { ?>
                                    <a class="fa fa-minus-circle"
                                       id="delete-user"
                                       action=<?php echo site_url('/profile/remove/' . $obj->id); ?>
                                       data-toggle="modal"
                                       data-modal-id="#removeProfileModal"
                                       data-user-id="<?php echo $obj->id; ?>"
                                       title="delete user"></a>
                                   <?php } ?>


        				<?php } ?>
						<?php if($obj->is_root == 1){?>
							<span class="label label-important">ADMIN</span>
						<?php } ?>
					</h2>
					<p>
						<em><?php echo $obj->email; ?></em><br/>
					</p>

				</div>
			</div>

			<div class="meta company">
		        </p>
			        <?php if($obj->organization){ ?>
		        	<em> 
		                <code class="text-info"><?php echo $obj->organization; ?></code> 
			        </em>
			        <?php } ?>
		        </p>
			</div>

			<div class="meta country">
				<p>
			        <?php if($obj->country){?>
			        	<em>
		          			<span class="bfh-countries" 
		          				data-country="<?php echo $obj->country; ?>"  
		          				title="<?php echo $obj->country; ?>" 
		          				data-available="<?php echo $obj->country; ?>" 
		          				data-flags="true" 
		          				data-blank="false"></span>
          				</em>
			        <?php } ?>
		        </p>
			</div>

			<?php if($user->is_root == 1){?>
			<div class="meta cards">
				<p>
			        <?php if($obj->cards > 0){ ?> 
					<em>
						<code class="text-info"><?php echo $obj->cards; ?> cards</code><br/>
			        </em>
			        <?php } ?>
		        </p>
			</div>
			<div class="meta circles">
				<p>
					<?php if($obj->circle_accept > 0){ ?>
	            	<em>
	            	    <a href="<?php echo site_url('/circle/all/'.$obj->id.'/2');?>"><code class="text-success">
	            	    	in <?php echo $obj->circle_accept; ?> circles</code> 
	            	    </a>  
			        </em>
			        <?php } ?>
		        </p>
		    </div>
			<div class="meta pending_requests">
				<p>
				    <?php if($obj->circle_wait > 0){ ?>
		        	<em> 
		                <a href="<?php echo site_url('/circle/all/'.$obj->id.'/1');?>"><code class="text-warning">
		                	<?php echo $obj->circle_wait; ?></code> 
	                	</a>
			        </em>
			        <?php } ?>
		        </p>
			</div>
	        <?php } ?>
		</li>

		<?php } ?>
	</ul>
	<div class="pager-list">
		<!-- Pagination buttons will be generated here -->
	</div>
</div>

<div class="modal fade notif_modals" id="removeProfileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
                <div class="alert alert-error hide">The operation could not be completed.</div>
                <p>Deleting a user deletes all the his cards and circles.
                    <br/>Are you sure you want to remove this user ?
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