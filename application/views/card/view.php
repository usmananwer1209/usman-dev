<?php
	$folder =   dirname(dirname(__FILE__));
 	require_once $folder."/commun/navbar.php";
?>

<div class="page-container row-fluid">
  	<?php require_once $folder."/commun/main-menu.php";?>
  	<div class="page-content">
  		
  		

<?php
if ( !empty($op) && $op == "view_") {
  $id = $obj->id;
  $guid = id_to_guid($id);
  $description = $obj->description;
  $type_chart = $obj->type;
  $period = $obj->period;
}

  if($this->uri->segment(1)=='card' && $this->uri->segment(2)=='view') {
 ?>
  <style>
  	.card_des{display:none !important;}
  </style>
  <?php
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


  		<div class="row transparent">
  			<div class="grid simple transparent">
  				<div class="grid-title no-border">
  					<h4></h4>
  				</div>
  				<div class="grid-body no-border">
            <div id="messages">
  					 <?php echo validation_errors(); ?>
              <?php if(!empty($message)) { ?> <div class="alert alert-success"><?php echo $message;  ?></div><?php } ?>
            </div>
<!--	
                            <?php foreach ($get_companies_error as $key => $value) { ?>
		<div class="alert alert-error">
          	<button data-dismiss="alert" class="close"></button>
          	<a class="link" href="#"><?php echo $key; ?></a>  not found
        </div>		
<?php } ?>
-->
<input  id="card_id" type="hidden" value="<?php echo $id;?>" data-card-flipped="no" />

  <div class="row front">
    <div class="col-md-12 col-sm-12 greyBg">
      <div class="add_card">
        <div class="title col-md-12 col-sm-12">
          <h1 class="center white"><?php echo $obj->name ?></h1>
            <a class="flip_card_toggle green" href="javascript();">
              <i class="fa fa-retweet"></i>
            </a>
        </div>

        <div class="clearfix"></div>
        <div id="card_core">
          <span class="square active" for="<?php echo $obj->type; ?>" style="display:none;"></span>

          <?php
            require_once $folder.'/explore/container.php';
          ?>

        </div>
        <div class="clearfix"></div>
        

      </div>
    </div>
  </div>

  <div class="row back" style="display: none"
       data-card-flipped="no">
      <div class="col-md-12 col-sm-12 greyBg">
          <div class="add_card">
              <div class="title col-md-12 col-sm-12">
                                            <h1 class="center white"><?php echo $obj->name ?></h1>
                  <a class="flip_card_toggle green" href="javascript:">
                      <i class="fa fa-retweet"></i>
                  </a>
              </div>

              <div id="card_core">

                  <div class="text-left card_view_flip pagination-centered <?php echo $obj->type; ?>">

                                                <p><strong>Card Name : </strong> <?php echo $obj->name; ?><br/></p>
                      <p><strong>Author : </strong> <?php echo $obj->author; ?><br/></p>
                                                <p><strong>Data Sources : </strong><span id="sources"></span><br/></p>

                                                <?php
                                                if (!empty($obj->description)) {
                                                    ?>
                      
                      

                      <p><strong>Description : </strong> <?php echo $obj->description; ?></p><br/>

                                                    <?php
                                                }
                                                ?>

                      <div id="data_points_div">
                      </div>


                  </div>

              </div>

          </div>
      </div>
  </div>


  				</div>
           <?php     
    if($obj->public == 1) {
    ?>
    <div style="margin: 0px 15px 0; padding:10px; background:#fff;">
      <div>
        <a target="_blank" href="https://twitter.com/home?status=<?php echo rawurlencode('Check out this card on #idaciti: '.$obj->name.' ').site_url('card/embed/'.$guid); ?>" style="color:#fff; padding: 10px 16px; font-weight: bold; border-radius: 17px; background:#46aeed; margin-right:10px; float:left;"><i class="fa fa-twitter"></i> TWEET THIS CARD</a>

        <a target="_blank" href="<?php echo site_url('card/embed/'.$guid); ?>" style="color:#fff; padding: 10px 16px; font-weight: bold; border-radius: 17px; background:#46aeed; margin-rigt:10px; float:left;"><i class="fa fa-external-link"></i> EMBED THIS CARD</a>


        <div style="margin-left: 370px;"><p style="background:#ddd; font-family:courier; padding: 2px 10px;">&lt;iframe  src="<?php echo site_url('card/embed/'.$guid); ?>" frameborder="0" style="min-width:980px; min-height:720px;"&gt;&lt;/iframe&gt;</p></div>
      </div>
    </div>

                                <?php
    
    }

    ?>

    <div class="disqus_container" >

      <?php $this->load->view('general/disqus'); ?>

  			</div>
  		</div>
  	</div>

  	</div>

</div>

	</div>
 </div>

<?php require_once $folder."/card/dd_dialogs.php";?>
