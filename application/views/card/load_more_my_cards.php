<?php
if($type =='overwrite'){ ?>
<div id="overlay_div" class="overlay2" style="display:none"><img src="<?php echo site_url('assets/img/AjaxLoader2.gif');?>" /></div>
	<li class="cell element-item transition stamp"
                data-id="0"
                data-description="0"
                data-type="0"
                data-creation_time="0"
                data-public="0"
                data-period="0"
                >
        <a href="<?php echo site_url("card/add"); ?>">
          <div class="m-l-10 ">
            <div class="tiles white cards add-cards text-center pagination-centered">
                <span class="black">+ Add </span>
                <span class="green"> Card</span>
            </div>
            <div class="tiles gray  p-t-5 p-b-5  m-b-20">
                <p class="text-center text-white semi-bold  small-text"> 
                  New
                </p>
            </div>
          </div>
        </a>
        <input type="hidden" name="rec_count_start" id="rec_count_start" value="<?php echo count($objs);?>">
   </li>
<?php }
$count = 1;
foreach ($objs as $obj) {
          $info = "";
          $info .= "Period:".$obj->period."<br/>";
          $info .= "Type:".$obj->type."<br/>";
          $info .= "Date Created:".$obj->creation_time."<br/>";
          ?>
          <li class="cell element-item transition <?php if($count==1 && $type !='overwrite'){ echo 'first_of_new'; } $count=2;?>"
                data-id="<?php echo $obj->id; ?>"
                data-name="<?php echo strip_tags($obj->name); ?>"
                data-description="<?php echo strip_tags($obj->name); ?>"
                data-type="<?php echo $obj->type; ?>"
                data-creation_time="<?php echo $obj->creation_time; ?>"
                data-public="<?php echo ($obj->public)?'public':'private'; ?>"
                data-period="<?php echo $obj->period; ?>"
                data-display-count = "<?php echo $total_result_count;?>"
          >


			<a class="fa fa-external-link-square"  
              id="view-card"
              title="view card" href="<?php echo site_url("card/view/".$obj->id);?>" ></a>
            <a class="fa <?php echo ($obj->public)?'fa-unlock':'fa-unlock-alt'; ?>"  
              id="publish-card"
              data-toggle="modal" 
              data-object="card"
              data-modal-id="#publishModal"
              data-card-id="<?php echo $obj->id;?>"
              data-card-public="<?php echo $obj->public; ?>"
              title="publish card" ></a>
          <a class="fa fa-bullhorn" 
              id="share-card"
              data-toggle="modal" 
              data-object="card"
              data-modal-id="#shareModal<?php echo $obj->id;?>"
              data-card-id="<?php echo $obj->id;?>"
              title="share with..."></a>
          <a class="fa fa-minus-square" 
              id="delete-card"
              data-toggle="modal" 
              data-object="card" 
              data-modal-id="#removeModal"
              data-card-id="<?php echo $obj->id;?>"
              title="delete card"></a>
                        <a class="fa fa-retweet flipper"
                           data-card-id="<?php echo $obj->id; ?>"
                           title="flip card" href="#"></a>

                        <div class="m-l-10 flip_card" data-card-id="<?php echo $obj->id; ?>">

                            <a href="<?php echo site_url('card/edit/' . $obj->id); ?>"> 
              <div class="tiles white cards text-center pagination-centered <?php echo $obj->type;?>">
              </div>

            <div class="tiles gray p-t-5 p-b-5  m-b-20">

                <p class="text-center text-white semi-bold  small-text"> 
                                        <?php echo cut_string($obj->name, 25);?>
                </p>
                                </div>
                                </a>

                            <div class="m-l-10 card_flipped" style="display: none">
                                <a class="flip_card"
                                   data-card-id="<?php echo $obj->id; ?>"
                                   data-card-flipped="no"
                                   title="flip card" href="#">
                                    <div class="white text-left flip_div pagination-centered <?php echo $obj->type; ?>">
                                        <p><strong>Author : </strong> <?php echo $obj->author; ?><br/></p>
                                        <p><strong>Name : </strong> <?php echo $obj->name; ?><br/></p>
                                        <p><strong>Date Created : </strong> <?php echo $obj->creation_time; ?><br/></p>
                                        <p><strong># Viewed : </strong> <?php echo $obj->viewed; ?><br/></p>

                                        <!-- <p><strong>Description : </strong> <?php //echo strip_tags((cut_string($obj->description, 150))); ?></p><br/> -->
                                    </div>
                                    <div class="tiles gray p-t-5 p-b-5  m-b-20">
                                        <p class="text-center text-white semi-bold  small-text"> 
                                            <?php echo $obj->name; ?>
                                        </p>    
                                    </div>
                                </a>
            </div>

          </div>


</li>
<div class="modal shareModal fade notif_modals" id="shareModal<?php echo $obj->id;?>" data-id="<?php echo $obj->id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>Share your card</h4>
            </div>
            <div class="modal-body">
              <div class="alert alert-success hide">Your Changes have been Successfully Submitted.</div>
              <div class="alert alert-error hide">The operation could not be completed.</div>
              <p>Share this with :
                <i class="fa-li fa fa-spinner fa-spin loading hide"></i>
              </p>
              <select class="select_circles" multiple="multiple">
                <?php foreach ($my_circles as $circle) {  ?>
                  <option
                    <?php echo (card_shared($obj->id,$circle['id']))? 'selected="selected"': '' ?>
                  value="<?php echo $circle['id'];?>"><?php echo $circle['name'];?></option>
                <?php } ?>
              </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary ajax_submit">Share</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>