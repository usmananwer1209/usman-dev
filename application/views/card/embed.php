<?php

  $folder =   dirname(dirname(__FILE__));

  if ( !empty($op) && $op == "view_") {

  $id = $obj->id;

  $guid = id_to_guid($id);

  $description = $obj->description;

  $type_chart = $obj->type;

  //echo $type_chart;

  $period = $obj->period;

 if($this->uri->segment(4)=='sid' || $this->uri->segment(4)=='preview'){
	  echo '<input  id="sid" type="hidden" value="'.$this->uri->segment(5).'" />';
 }
}

  if($this->uri->segment(4)=='edit') { 
   
  ?>

  <style>

  	.card_des{display:none !important;}

  </style>

  <?php



  }

  if($this->uri->segment(4)!='preview') { 

	  $this->load->library('session');

	$this->session->set_userdata('preveiw_title', '');

	$this->session->set_userdata('preview_description', '');

	}

$user = $this->session->userdata('user');

    if (empty($user)) {

     echo '<input  id="loggedin" type="hidden" value="1" />';

    }

	else

	{

		echo '<input  id="loggedin" type="hidden" value="0" />';

	}

	

	if($this->uri->segment(4)=='preview') { 

	echo '<input  id="preview_style" type="hidden" value="'.$this->uri->segment(6).'" />';

	}

?>



<div class="card_container" style="padding:0 20px; background:#fff;">

      







<div class=" add-cards-form">

    <div class="content">

                



      <div class="row transparent">

        <div class="grid simple transparent" style="margin-bottom:0;">

          

          <div class="grid-body no-border" style="padding: 5px 15px 0 10px;">

           

<input  id="card_id" type="hidden" value="<?php echo $id;?>" data-card-flipped="no" />

<?php if($this->uri->segment(4)=='sid' || $this->uri->segment(4)=='preview') { ?>

  <div class="row front">

    <div class="<?php if($obj->type=="combo_new" || $obj->type=="column" || $obj->type=='area' || $obj->type=='line' || $obj->type=='tree' || $obj->type=='explore' || $obj->type=='map') {?>cardstyle col-md-9 col-sm-9<?php } else {?>col-md-12 col-sm-12 <?php } ?>">

      <div class="add_card">

        <div class="title <?php if($obj->type=="combo_new" || $obj->type=="column" || $obj->type=='area' || $obj->type=='line' || $obj->type=='tree' || $obj->type=='explore' || $obj->type=='map') {?>col-md-9 col-sm-9<?php } else {?>col-md-12 col-sm-12 <?php } ?>">

          <?php 

          if(!$is_internal) 

          {

          ?>

            <a id="embed_logo" href="http://www.idaciti.com" target="_blank" style="border-radius: 20px; position: absolute; left: 10px; padding: 5px 15px 5px 0; background: #22262e; bottom: 12px;">

              <img src="<?php echo img_url('logo.png'); ?>" alt="Idaciti" width="100" />

            </a>

          <?php 

          }

          ?>

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



  <div class="row back" style="display: none" data-card-flipped="no">

       <div class="<?php if($obj->type=="combo_new" || $obj->type=="column" || $obj->type=='area' || $obj->type=='line' || $obj->type=='tree' || $obj->type=='explore' || $obj->type=='map') {?>cardstyle col-md-9 col-sm-9<?php } else {?>col-md-12 col-sm-12 <?php } ?>">

          <div class="add_card">

              <div class="title col-md-12 col-sm-12">

                                            <h1 class="center white"><?php echo $obj->name ?></h1>

                  <a class="flip_card_toggle green" href="javascript:">

                      <i class="fa fa-retweet"></i>

                  </a>

              </div>



              <div id="card_core">



                  <div class="text-left card_view_flip pagination-centered <?php echo $obj->type; ?>">



                                                <p><strong>Card Name:</strong> <?php echo $obj->name; ?><br/></p>

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
<?php } else { ?>
<div class="row front">

    <div class="col-md-12 col-sm-12">

      <div class="add_card">

        <div class="title col-md-12 col-sm-12">

          <?php 

          if(!$is_internal) 

          {

          ?>

            <a id="embed_logo" href="http://www.idaciti.com" target="_blank" style="border-radius: 20px; position: absolute; left: 10px; padding: 5px 15px 5px 0; background: #22262e; bottom: 12px;">

              <img src="<?php echo img_url('logo.png'); ?>" alt="Idaciti" width="100" />

            </a>

          <?php 

          }

          ?>

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

       <div class="col-md-12 col-sm-12">

          <div class="add_card">

              <div class="title col-md-12 col-sm-12">

                                            <h1 class="center white"><?php echo $obj->name ?></h1>

                  <a class="flip_card_toggle green" href="javascript:">

                      <i class="fa fa-retweet"></i>

                  </a>

              </div>



              <div id="card_core">



                  <div class="text-left card_view_flip pagination-centered <?php echo $obj->type; ?>">



                                                <p><strong>Card Name:</strong> <?php echo $obj->name; ?><br/></p>

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
<?php } ?>
<?php 

      if(!$is_internal) 

      {

      ?>

        <div>

          <a target="_blank" href="https://twitter.com/home?status=<?php echo rawurlencode('Check out this card on #idaciti: '.$obj->name.' ').site_url('card/embed/'.$guid); ?>" style="color:#fff; padding: 10px 16px; font-weight: bold; border-radius: 17px; background:#46aeed; margin-top:10px; margin-right:10px; float:right;"><i class="fa fa-twitter"></i> TWEET THIS CARD</a>

        </div>

      <?php 

      }

      ?>



          </div>

        </div>

      </div>

    </div>

</div>



  </div>


<?php require_once $folder.'/card/dd_dialogs.php'; ?>


