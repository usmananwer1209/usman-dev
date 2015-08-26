<?php
if ( !empty($op) && $op == "edit_") {
  $id = $obj->id;
  $description = $obj->description;
  $card_name = $obj->name;
  $type_chart = $obj->type;
  $period = $obj->period;
  
} else {
  $card_name = '';
  $id = "";
  $description = "";
  $type_chart = "rank";
  $period = "2014";
}
?>
<?php   $folder = dirname(dirname(__FILE__));  ?>

<div class=" add-cards-form">
  	<div class="clearfix"></div>
  	<div class="content">
        <?php if (!empty($API_ERROR)) { ?>

        <div class="alert alert-error API_ERROR">
          <button data-dismiss="alert" class="close"></button>
          The <a class="link" href="#">API</a> signals errors as part of the JSON response.
        </div>

    <?php } ?>
  		<div class="page-title"> <a href="<?php echo site_url('/card/my_cards');?>"><i class="icon-custom-left"></i></a>
        <?php 
        if($op == "edit_" && !empty($obj->name)){
          ?><h3>Edit <span class="semi-bold">Card</span>: <?php echo $obj->name; ?></h3>
                <?php
            } else {
          ?><h3>Add <span class="semi-bold">New Card</span></h3>
        <?php } ?>
  		</div>
        <div class="row card_form_core" >
  			<div class="grid simple">
  				<div class="grid-title no-border">
  					<h4></h4>
  				</div>
  				<div class="grid-body no-border">
            <div id="messages">
  					 <?php echo validation_errors(); ?>
              <?php if(!empty($message)) { ?> <div class="alert alert-success"><?php echo $message;  ?></div><?php } ?>
            </div>


<input  id="card_id" type="hidden" value="<?php echo $id;?>" />
<div class="row">
    <div class="col-md-3 col-sm-4 aside_section">
      <div class="add_company">
        <div class="title">
          Select <span class="green">Companies</span>
        </div>
        <div>
          <input name="company_name" for="companies" 
                data-autocomplete="companies" data-label="company_name" data-value="entity_id"  
                data-action="<?php echo site_url("/autocomplete/") ?>" class="form-control autocomplete"  type="text" placeholder="Search Companies">
          <input name="company_value" for="companies" type="hidden"/>
                                    <a class="add_to_select fa fa-plus-circle" for="companies" href="javascript:"></a>
        </div>
        <div>
          <select name="companies" class="form-control" data-id="entity_id" data-txt="company_name" multiple="multiple">
            <?php
              if(!empty($list_companies)){
                                            foreach ($list_companies as $c) {
                                                ?>
                  <option value="<?php echo $c->entity_id;?>" title="<?php echo $c->company_name;?>"><?php echo $c->company_name;?></option>
                                                <?php
                                            }
              }
            ?>
          </select>
                                    <a id="delete_comp" class="select_all p-l-25 fa fa-minus-circle" href="javascript:"></a>
        </div>
        <div class="list_builder">
          <select class="s_list" for="companies">
          </select>
                                    <a class="fa fa-wrench" data-toggle="modal" data-target="#company_list_builder" href="javascript:"></a>
        </div>          
      </div>
	  <a id="delete_list_companies" for="companies" class="delete_list" href="javascript:" style="display:none">Delete list</a>
      <div class="add_kpi">
        <div class="title">
          Select <span class="green">KPI's</span>
        </div>
        <div>
          <input name="kpi_name" for="kpis" 
                data-autocomplete="kpis"  data-label="name" data-value="term_id"  
                data-action="<?php echo site_url("/autocomplete/") ?>"   class="form-control autocomplete" type="text" placeholder="Search kpis" >
          <input name="kpi_value"  for="kpis" type="hidden"/>
          <a class="add_to_select fa fa-plus-circle" for="kpis" href="javascript:"></a>

        </div>

        <div>
          <select name="kpis" class="form-control" data-id="term_id" data-txt="name" multiple="multiple"  >
            <?php
              if(!empty($list_kpis)){
                foreach ($list_kpis as $c) {
             ?>
                  <option value="<?php echo $c->term_id;?>" title="<?php echo $c->name; ?>" data-desc="<?php echo $c->description; ?>"><?php echo $c->name;?></option>
              <?php
                }
              }
            ?>
          </select>
        </div>

        <div id="selector_kpi_description" style="min-height:50px; background:#E3EBF5; border-radius:8px; padding:8px 10px; width:86%;">
        </div>
        
        <a id="del_kpis" class="select_all p-l-25  fa fa-minus-circle" href="javascript:"></a>
        <div class="list_builder">
          <select class="s_list" for="kpis">
          </select>
                                    <a class="fa fa-wrench" data-toggle="modal" data-target="#kpi_list_builder" href="javascript:"></a>
        </div>
      </div>
	  <a id="delete_list_kpis"  for="kpis" class="delete_list"  href="javascript:" style="display:none">Delete list</a>
      <div class="clearfix"></div>
      <button id="show_chart" class="btn btn-success btn-cons" type="button">
        Refresh Data
      </button>
    </div>
    <div class="col-md-9 col-sm-8">
      <div class="add_card">
        <div class="title col-md-12 col-sm-12">
          <div class="title-text"><?php if ( !empty($op) && $op == "edit_") { ?><a class="flip_card_toggle_edit_mode green" href="javascript();"> <i class="fa fa-retweet"></i> </a><?php }?> Select <span class="green">Chart Type</span></div>
			<div class="squares-container">
            <div id="is_over_lay" class=""></div>
            <div for="rank" class="square <?php echo $type_chart=="rank"?'active':''; ?>">
              <div></div>
              <span>Rank</span>
            </div>
            <div for="explore" class="square <?php echo $type_chart=="explore"?'active':''; ?>">
              <div></div>
              <span>Explore</span>
            </div>
            <div for="map" class="square <?php echo $type_chart=="map"?'active':''; ?>">
              <div></div>
              <span>Map</span>
            </div>
            <div for="tree" class="square <?php echo $type_chart=="tree"?'active':''; ?>">
              <div></div>
              <span>Tree</span>
            </div>
            <div for="line" class="square <?php echo $type_chart=="line"?'active':''; ?>">
              <div></div>
              <span>Line</span>
            </div>
            <div for="column" class="square <?php echo $type_chart=="column"?'active':''; ?>">
              <div></div>
              <span>Column</span>
            </div>
            <div for="area" class="square <?php echo $type_chart=="area"?'active':''; ?>">
              <div></div>
              <span>Area</span>
            </div>
            <div for="combo_new" class="square <?php echo $type_chart=="combo_new"?'active':''; ?>">
              <div></div>
              <span>Combo</span>
            </div>
            <div for="combo" class="square <?php echo $type_chart=="combo"?'active':''; ?>">
              <div></div>
              <span>Stock</span>
            </div>
          </div>

        </div>
        <div class="clearfix"></div>
		<div class="card_description card_save">
		<div id="card_core" class="front">
        <input class="card_flip_mode_edit" type="hidden" data-card-flipped="no" />
          <?php
            require_once $folder.'/explore/container.php';
          ?>

        </div>
        <div id="card_cored" class="back" style="display: none" data-card-flipped="no">
        <input class="card_flip_mode_edit" type="hidden" data-card-flipped="no" />	
        		<p><strong>Card Name : </strong> <?php echo $card_name; ?><br/></p>
                <p><strong>Author : </strong> <?php echo "$user->first_name $user->last_name"; ?><br/></p>
				<?php
                	if (!empty($description)) { ?>
                      <p><strong>Description : </strong> <?php echo $description; ?></p><br/>
				<?php
                 }
                 ?>
                 <div id="data_points_div">
                 </div>
        </div>
        <div class="clearfix"></div>

        <div class="card_description card_save">
          <button id="save_card" class="btn btn-success btn-cons" type="button">
            Save Story Card
          </button>
                                        <a href="#" id="flip_card_descr" data-toggle="modal" data-target="#card_descr" ><i class="fa fa-edit"></i></a>
        </div>


      </div>
    </div>
</div>



  				</div>
  			</div>
  		</div>
  	</div>
</div>

<?php
require_once 'company_list_builder.php';
require_once 'kpi_list_builder.php';
require_once 'edit_card_description.php';
?>

</div>


<div class="modal fade notif_modals" id="replace_listModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
                <div class="alert alert-error hide">The operation could not be completed.</div>
                <p>This list name already exists.
                    <br/>Do you want to replace it ?
                    <i class="fa-li fa fa-spinner fa-spin loading hide"></i>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger ajax_submit">Replace</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade notif_modals" id="delete_listModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
                <div class="alert alert-error hide">The operation could not be completed.</div>
                <p>Do you want to delete this list ?
                    <i class="fa-li fa fa-spinner fa-spin loading hide"></i>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger ajax_submit">Delete</button>
            </div>
        </div>
    </div>
</div>

<?php require_once 'dd_dialogs.php'; ?>
