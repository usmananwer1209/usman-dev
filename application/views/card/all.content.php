   <div class="row" >
        <div class="col-md-12">
        <div class="row">


            <div class="page-title browse_circle_title">

                <?php
                if (!empty($circle)) {
                    echo "<h3><span class='semi-bold'>$circle->name</span></h3>";
                } else {
                    ?><h3><span class="semi-bold">All Circles</span></h3>
                <?php } ?>
            </div>


            <?php
            //*
            //*/
            ?>

<div id="filters_isotope">
  <button data-name="viewed" type="button" class="btn btn-white btn-cons ">
    <i class="fa "></i>Viewed
  </button>
                <button data-name="creation_time" type="button" class="btn btn-white btn-cons active">
    <i class="fa fa-sort-amount-desc"></i>Date Created
  </button>
  <button data-name="autor" type="button" class="btn btn-white btn-cons ">
                    <i class="fa "></i>Author
  </button>
  <button data-name="description" type="button" class="btn btn-white btn-cons ">
    <i class="fa "></i>Name
  </button>
  
</div>
<input type="hidden" id="site_url" value="<?php echo site_url();?>">
<input type="hidden" id="circle_id" value="<?php echo $this->uri->segment(3);?>">
        <ul id="cards_isotope"  class="isotope2 transition">
        <div id="overlay_div" class="overlay2" style="display:none"><img src="<?php echo site_url('assets/img/AjaxLoader2.gif');?>" /></div>
                <?php
        foreach ($cards as &$obj) {
          $obj = (object) $obj;
          ?>
          
            <li class="cell element-item transition"
                data-id="<?php echo $obj->id; ?>"
                        data-name="<?php echo strip_tags($obj->name); ?>"
                        data-description="<?php echo strip_tags($obj->name); ?>"
                data-period="<?php echo $obj->period; ?>"
                data-kpi="<?php echo $obj->kpi; ?>"
                data-order="<?php echo $obj->order; ?>"
                data-autor="<?php echo $obj->autor; ?>"
                data-creation_time="<?php echo $obj->creation_time; ?>"
                data-viewed="<?php echo $obj->viewed; ?>"
                data-count-start="<?php echo count($cards);?>"
                >

                        <div class="m-l-10 flip_card" data-card-id="<?php echo $obj->id; ?>">

                            <a href="<?php echo site_url('card/view/' . $obj->id); ?>" > 
            <div class="tiles white cards text-center pagination-centered <?php echo $obj->type; ?>">
            </div>
                            </a>
            <div class="tiles gray p-t-5 p-b-5  m-b-20" >
                <p class="text-center text-white semi-bold  small-text"> 
                    <a class="white" href="<?php echo site_url('card/view/'.$obj->id);?>">
                        <?php 
                         $title = cut_string($obj->name, 25);
                         if ($obj->disqus_post_count > 0) {
                            $leftB = ' [';
                            $rightB = ']';
                            $comments = $obj->disqus_post_count;
                            echo $title.$leftB.$comments.$rightB;
                         }
                         else
                         {
                            echo $title;
                         }
                       ?>
                </p>
                    </a>
                <?php
                    $card_privacy = ($obj->public == 0)? 'private':'public' ;
                    $circles = "";
                    $info = "";
                    $info .= "Viewed: ".$obj->viewed."<br/>";
                    $info .= "Privacy: ".$card_privacy."<br/>";
                    $info .= "Autor: ".$obj->autor."<br/>";
                    $info .= "Date Created: ".$obj->creation_time."<br/>";

                                    if (!empty($obj->circles)) {
                  $num_circle = count($obj->circles);
                  $i = 0;
                  foreach ($obj->circles as $circle) {
                    if($i < 3)
                      $circles .= cut_string($circle['name'],20)."<br/>";
                    if($i > 3){
                      $circles .= "...";
                      break;
                      }
                    $i++;
                  }
                                    } else {
                  $circles = "Not shared with any circle";
                }
                ?>
                                    <a class="fa fa-retweet flipper" 
                                       data-card-id="<?php echo $obj->id; ?>"
                                       title="flip card" href="#"></a>

                                                <!--<a title="<?php //echo $info;   ?>" class="fa fa-info-circle tooltip-toggle tooltip-left" data-toggle="tooltip" data-placement="bottom"></a>-->
                    <a title="<?php echo $circles;?>" class="fa fa-circle-o tooltip-toggle tooltip-right" data-toggle="tooltip" data-placement="bottom"></a>
                    <?php 
                      if($obj->public)
                        echo '<i  class="fa fa-unlock tooltip-toggle tooltip-right" title="Public Card" data-toggle="tooltip" data-placement="bottom" style="margin-right: 20px;"></i>';
                    ?>
                </p>
            </div>

                            <div class="m-l-10 card_flipped" style="display: none">
                                <div class="flip_card"
                                   data-card-id="<?php echo $obj->id; ?>"
                                   data-card-flipped="no"
                                   title="flip card" href="#">
                                    <div class="white text-left flip_div pagination-centered <?php echo $obj->type; ?>">
                                        <p><strong>Author : </strong> <?php echo $obj->autor; ?><br/></p>
                                        <p><strong>Name : </strong> <?php echo $obj->name; ?><br/></p>
                                        <p><strong>Date Created : </strong> <?php echo $obj->creation_time; ?><br/></p>
                                        <p><strong># Viewed : </strong> <?php echo $obj->viewed; ?> </p>
                                    </div>
                                    <div class="tiles gray p-t-5 p-b-5  m-b-20">
                                        <p class="text-center text-white semi-bold  small-text"> 
                                            <?php echo $obj->name; ?>
                                            <a class="fa fa-retweet flipper" 
                                               data-card-id="<?php echo $obj->id; ?>"
                                               title="flip card" href="#"></a>
                                                <!--<a title="<?php //echo $info;   ?>" class="fa fa-info-circle tooltip-toggle tooltip-left" data-toggle="tooltip" data-placement="bottom"></a>-->
                                            <a title="<?php echo $circles; ?>" class="fa fa-circle-o tooltip-toggle tooltip-right" data-toggle="tooltip" data-placement="bottom"></a>                             

                   <!--<a title="<?php //echo $info;   ?>" class="fa fa-info-circle tooltip-toggle tooltip-left" data-toggle="tooltip" data-placement="bottom"></a>-->
                                        </p>    
                                    </div>
                                </div>
                            </div>
                        </div>


            </li>

        <?php } ?>
      </ul>
       <div class="col-md-12 text-center" id="loader_img" style="display:none">
      <img src="<?php echo site_url('assets/img/AjaxLoader2.gif');?>" />
      <div class="clear10"></div>		
      </div>
      <?php if(count($cards)< $total_result_count){?>
      <div class="col-md-12 text-center" id="load_more_btn" style="display:none">
      			<button type="button" class="btn btn-success btn-large font20 border-radious-none" onclick="load_more_allcards();">
                  <i class="fa fa-spinner"></i> Load More
                </button>
      </div>
      <?php }?>
	<div class="clear30"></div>
        </div>
   </div>
</div>
<script>
var start = 24;
var record_count = 24;
var limit = 24;
var total_result_count = <?php echo $total_result_count; ?>;
function load_more_allcards(){
	$('#loader_img').show();
	var site_url = $('input#site_url').val();
	var circle_id = $('input#circle_id').val();
    var action = 'card/load_more_circle_cards/'+circle_id;
	var sort_by = $('#filters_isotope .active').attr('data-name');
	var search_val = $("#search_input") .val();
	cards_isotope_sort = sort_by;
	if($('#filters_isotope .active .fa').hasClass('fa-sort-amount-desc')){
		var sort_order = 'DESC';
		cards_isotope_sortAscending = false;
	}else if($('#filters_isotope .active .fa').hasClass('fa-sort-amount-asc')){
		var sort_order = 'ASC';
		cards_isotope_sortAscending = true;
	}
	else{
		var sort_order = 'DESC';
		cards_isotope_sortAscending = false;
	}
	 $.ajax( {
          url :  site_url + action,
          data: {
                  'sort_by' : sort_by,
                  'sort_order' : sort_order,
				  'start' : start,
				  'title' : search_val,
                  'limit' : limit,
				  'type' : 'append'
                },
          type:"POST",
          success : function(data) {
			  if(data != 'ko'){
				  start +=24;
				  record_count = record_count+24;
			  $('#cards_isotope').isotope('destroy');
			  $('#cards_isotope').append( data );
			  $('#cards_isotope').isotope({
					itemSelector: '.element-item',
					transitionDuration: '1s',
					sortAscending: cards_isotope_sortAscending,
					sortBy: cards_isotope_sort ,
					layoutMode: 'masonry',
					getSortData: sort_fct,
					filter: function(){
						var is_ok  = true;
						var creation_time = $(this).attr("data-creation_time");
						var autor = $(this).attr("data-autor");
						//var description = $(this).attr("data-description");
						var name = $(this).attr("data-name");
			
						var search_val = $("#search_input") .val();
						var _creation_time = (creation_time.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						var _autor = (autor.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						var _name = (name.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						//var _description = (description.indexOf(search_val) > -1)?true:false;
						return _creation_time || _autor || _name; //|| _description;
						}
        		});
				$('html, body').animate({
					scrollTop: $(".first_of_new:last").offset().top
				}, 2000);
				total_result_count = $(".element-item:last").attr('data-display-count');
				if(record_count<total_result_count){
					$('#load_more_btn').show();
				}else{
					$('#load_more_btn').hide();
				}
				$(".shareModal select").select2();
			  }else{
				  $('#load_more_btn').hide();
			  }
			  $('#loader_img').hide(); 
		  }
      });
}
function sorted_data_load(){
	$('#overlay_div').show();
	var site_url = $('input#site_url').val();
    var circle_id = $('input#circle_id').val();
    var action = 'card/load_more_circle_cards/'+circle_id;
	var sort_by = $('#filters_isotope .active').attr('data-name');
	var search_val = $("#search_input") .val();
	cards_isotope_sort = sort_by;
	if($('#filters_isotope .active .fa').hasClass('fa-sort-amount-desc')){
		var sort_order = 'DESC';
		cards_isotope_sortAsc = true;
	}else if($('#filters_isotope .active .fa').hasClass('fa-sort-amount-asc')){
		var sort_order = 'ASC';
		cards_isotope_sortAsc = false;
	}
	else{
		var sort_order = 'DESC';
		cards_isotope_sortAsc = true;
	}
	 $.ajax( {
          url :  site_url + action,
          data: {
                  'sort_by' : sort_by,
                  'sort_order' : sort_order,
				  'start' : 0,
				  'title' : search_val,
                  'limit' : record_count,
				  'type' : 'overwrite'
                },
          type:"POST",
          success : function(data) {
			  if(data != 'ko'){
			  $('#cards_isotope').isotope('destroy');
			  $('#cards_isotope').html( data );
			  $('#cards_isotope').isotope({
					itemSelector: '.element-item',
					transitionDuration: '1s',
					sortAscending: cards_isotope_sortAsc,
					sortBy: cards_isotope_sort ,
					layoutMode: 'masonry',
					getSortData: sort_fct,
					filter: function(){
						var is_ok  = true;
						var creation_time = $(this).attr("data-creation_time");
						var autor = $(this).attr("data-autor");
						//var description = $(this).attr("data-description");
						var name = $(this).attr("data-name");
			
						var search_val = $("#search_input") .val();
						var _creation_time = (creation_time.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						var _autor = (autor.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						var _name = (name.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
						//var _description = (description.indexOf(search_val) > -1)?true:false;
						return _creation_time || _autor || _name; //|| _description;
						}
        	});
			start = parseInt($('.element-item:last').attr('data-count-start'));
			total_result_count = $(".element-item:last").attr('data-display-count');
				if(record_count<total_result_count){
					$('#load_more_btn').show();
				}else{
					$('#load_more_btn').hide();
				}
				$(".shareModal select").select2();
				cards_isotope();
			}else{
				  $('#load_more_btn').hide();
			}
			$('#overlay_div').hide();
		  }
      });
}
</script>