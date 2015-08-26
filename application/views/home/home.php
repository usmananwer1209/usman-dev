<?php
  //var_dump($card_otd);
	$folder =   dirname(dirname(__FILE__));
 	require_once $folder."/commun/navbar.php";
 ?>
<div class="page-container row-fluid">
  <?php require_once $folder."/commun/main-menu.php";?>
    <div class="page-content">
      <div class="clearfix"></div>
      <div class="content">

    		<div class="row">

          <div class="col-md-4">

            <div class="row tiles-container tiles white m-b-20 grey_tile" id="community_creations">
              <div class="tile_head">
                <h2>Community Creations</h2>
                <div class="row">
                  <div class="col-xs-4 border_right">
                    <p>
                      Total Cards<br/>
                      <?php echo $total_cards; ?>
                    </p>
                  </div>
                  <div class="col-xs-8">
                    <p>
                      Total Storyboards<br/>
                      <?php echo $total_storyboards; ?>
                    </p>
                  </div>
                </div>
              </div>
              <div id="cards_sparkline"></div>
              <input type="hidden" id="cards_10days_data" value="<?php echo $cards_10days; ?>" />
              <p class="caption"># of Cards Created Last 10 Days</p>
              <div id="sb_sparkline"></div>
              <input type="hidden" id="sb_10days_data" value="<?php echo $sbs_10days; ?>" />
              <p class="caption"># of Storyboards Created Last 10 Days</p>
            </div>

            <div class="row">
              <div class="col-md-7 m-b-20 col-lg-6 col-sm-6">
                <div class="row tiles-container tiles white m-b-20 grey_tile">
                  <div id="cards_public_percent" class="easy-pie-custom" data-percent="<?php echo $cards_public_shared_percent; ?>">
                    <span class="easy-pie-percent"><?php echo $cards_public_shared_percent; ?>%</span>
                  </div>
                  <p class="caption">% of Cards Public/Shared</p>
                </div>
              </div>

              <div class="col-md-7 m-b-20 col-lg-6 col-sm-6">
                <div class="row tiles-container tiles white m-b-20 grey_tile">
                  <div id="sb_public_percent" class="easy-pie-custom" data-percent="<?php echo $storyboards_public_shared_percent; ?>">
                    <span class="easy-pie-percent"><?php echo $storyboards_public_shared_percent; ?>%</span>
                  </div>
                  <p class="caption">% of Storyboards Public/Shared</p>
                </div>
              </div>
            </div>

          </div>

          <div class="col-md-8">
            <div class="row">

              <div class="col-md-4 col-sm-6">
                <?php if(!empty($card_otd)) { ?>
                <div class="row">
                  <!-- BEGIN Card of the Day-->
                  <div class="col-md-12 m-b-20">
                    <div class="widget-item narrow-margin">
                      <div style="max-height:214px" class="tiles white  overflow-hidden full-height">
                        <div class="overlayer bottom-right fullwidth">
                          <div class="overlayer-wrapper">
                            <div class="tiles gradient-black p-l-20 p-r-20 p-b-20 p-t-20">
                              <div class="pull-right"> <a class="hashtags transparent" href="<?php echo site_url('card/view/'.$card_otd->id); ?>"> Card of the Day </a> </div>
                              <div class="clearfix"></div>
                            </div>
                          </div>
                        </div>
                        <img class="lazy hover-effect-img" alt="" src="<?php echo img_url($card_otd->type.'.png'); ?>" style="width:100%;"> 
                      </div>
                      <div class="tiles white ">
                        <div class="tiles-body">
                          <div class="row">
                            <div class="user-profile-pic text-left">
                              <a href="<?php echo site_url('profile/view/'.$card_otd->user) ?>"><img width="69" height="69" alt="" src="<?php echo avatar_url($card_otd->user_obj)."?id=".$cache_id ;?>" data-src="<?php echo avatar_url($card_otd->user_obj)."?id=".$cache_id ; ?>" data-src-retina="<?php echo avatar_url($card_otd->user_obj)."?id=".$cache_id ; ?>"> 
                            </div></a>
                          </div>
                          <div class="row">
                            <div class="user-comment-wrapper">
                              <div class="comment">
                                <div class="user-name text-black bold"> <a href="<?php echo site_url('profile/view/'.$card_otd->user) ?>"><?php echo $card_otd->user_obj->first_name; ?> <span class="semi-bold"><?php echo $card_otd->user_obj->last_name; ?></span></a> </div>
                              </div>  
                            </div>
                          </div>
                          <div class="row">
                            <div class="m-r-20 m-t-20 m-b-10  m-l-10">
                              <p><strong><a href="<?php echo site_url('card/view/'.$card_otd->id); ?>"><?php echo $card_otd->name; ?></a></strong></p>
                              <p class="p-b-10"><?php echo cut_string(strip_tags(str_replace('<br>', ' ', $card_otd->description)), 80); ?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- END Card of the Day-->
                </div>
                <?php } ?>
                <div class="row">
                  <!-- BEGIN Most Viewed Cards -->
                  <div class="col-md-12 m-b-20">
                    <div class="widget-item narrow-margin">
                      <div class="tiles green_bg ">
                        <div class="tiles-body">
                          <h2>Most Viewed Cards</h2>
                          <ul>
                            <?php
                            foreach($most_viewed_cards as $card) {
                              $date = new datetime($card['creation_time']);
                              echo '
                                <li>
                                  <i class="fa fa-eye"></i> 
                                  <span class="date">'.$date->format("m/d/Y").'</span>
                                  <span class="views_nb">(# Views : '.$card['viewed'].') </span><br/>
                                  <a class="author" href="'.site_url('profile/view/'.$card['user']).'">'.$card['autor'].'</a>
                                  <span>created</span><br/>
                                  <a class="card_title" href="'.site_url('card/view/'.$card['id']).'">'.$card['name'].'</a>
                                </li>';
                            }
                            ?>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- END Most Viewed Cards -->
                </div>

                <div class="row">
                  <!-- BEGIN Recently Published Storyboards -->
                  <div class="col-md-12 m-b-20">
                    <div class="widget-item narrow-margin">
                      <div class="tiles blue_bg ">
                        <div class="tiles-body">
                          <h2>Recently Published Storyboards</h2>
                          <ul>
                            <?php
                            foreach($recently_published_storyboards as $card) {
                              $date = new datetime($card['creation_time']);
                              echo '
                                <li>
                                  <i class="fa fa-clock-o"></i> 
                                  <span class="date">'.$date->format("m/d/Y").'</span>
                                  <span class="views_nb">(# Views : '.$card['viewed'].') </span><br/>
                                  <a class="author" href="'.site_url('profile/view/'.$card['user']).'">'.$card['author'].'</a>
                                  <span>created</span><br/>
                                  <a class="card_title" href="'.site_url('storyboard/view/'.$card['id']).'">'.$card['title'].'</a>
                                </li>';
                            }
                            ?>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- END Recently Published Storyboards -->
                </div>
              </div>

              <div class="col-md-4 col-sm-6">
                <?php if(!empty($storyboard_otd)) { ?>
                <div class="row">
                  <!-- BEGIN Storyboard of the Day-->
                  <div class="col-md-12 m-b-20">
                    <div class="widget-item narrow-margin">
                      <div style="max-height:214px" class="tiles white overflow-hidden full-height">
                        <div class="overlayer bottom-right fullwidth">
                          <div class="overlayer-wrapper">
                            <div class="tiles gradient-black p-l-20 p-r-20 p-b-20 p-t-20">
                              <div class="pull-right"> <a class="hashtags transparent" href="<?php echo site_url('storyboard/view/'.$storyboard_otd->id); ?>"> Storyboard of the Day </a> </div>
                              <div class="clearfix"></div>
                            </div>
                          </div>
                        </div>
                        <img class="lazy hover-effect-img" alt="" src="<?php echo $storyboard_otd->start_image; ?>" style="width:100%;"> 
                      </div>
                      <div class="tiles white ">
                        <div class="tiles-body">
                          <div class="row">
                            <div class="user-profile-pic text-left"> 
                              <a href="<?php echo site_url('profile/view/'.$storyboard_otd->user) ?>"><img width="69" height="69" alt="" src="<?php echo avatar_url($storyboard_otd->user_obj)."?id=".$cache_id ; ?>" data-src="<?php echo avatar_url($storyboard_otd->user_obj)."?id=".$cache_id ; ?>" data-src-retina="<?php echo avatar_url($storyboard_otd->user_obj)."?id=".$cache_id ; ?>"> 
                            </div></a>
                          </div>
                          <div class="row">
                            <div class="user-comment-wrapper">
                              <div class="comment">
                                <div class="user-name text-black bold"> <a href="<?php echo site_url('profile/view/'.$storyboard_otd->user) ?>"><?php echo $storyboard_otd->user_obj->first_name; ?> <span class="semi-bold"><?php echo $storyboard_otd->user_obj->last_name; ?></span></a> </div>
                              </div>  
                            </div>
                          </div>
                          <div class="row">
                            <div class="m-r-20 m-t-20 m-b-10  m-l-10">
                              <p><strong><a href="<?php echo site_url('storyboard/view/'.$storyboard_otd->id); ?>"><?php echo $storyboard_otd->title; ?></a></strong></p>
                              <p class="p-b-10"><?php echo cut_string(strip_tags($storyboard_otd->description), 80) ?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- END Storyboard of the Day-->
                </div>
                <?php } ?>
                <div class="row">
                  <!-- BEGIN Recently Published Cards -->
                  <div class="col-md-12 m-b-20">
                    <div class="widget-item narrow-margin">
                      <div class="tiles blue_bg ">
                        <div class="tiles-body">
                          <h2>Recently Published Cards</h2>
                          <ul>
                            <?php
                            foreach($recently_published_cards as $card) {
                              $date = new datetime($card['creation_time']);
                              echo '
                                <li>
                                  <i class="fa fa-clock-o"></i> 
                                  <span class="date">'.$date->format("m/d/Y").'</span>
                                  <span class="views_nb">(# Views : '.$card['viewed'].') </span><br/>
                                  <a class="author" href="'.site_url('profile/view/'.$card['user']).'">'.$card['author'].'</a>
                                  <span>created</span><br/>
                                  <a class="card_title" href="'.site_url('card/view/'.$card['id']).'">'.$card['name'].'</a>
                                </li>';
                            }
                            ?>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- END Recently Published Cards -->
                </div>

                <div class="row">
                  <!-- BEGIN Most Viewed Storyboards -->
                  <div class="col-md-12 m-b-20">
                    <div class="widget-item narrow-margin">
                      <div class="tiles green_bg ">
                        <div class="tiles-body">
                          <h2>Most Viewed Storyboards</h2>
                          <ul>
                            <?php
                            foreach($most_viewed_storyboards as $card) { $date = new datetime($card['creation_time']);
                              echo '
                                <li>
                                  <i class="fa fa-eye"></i> 
                                  <span class="date">'.$date->format("m/d/Y").'</span>
                                  <span class="views_nb">(# Views : '.$card['viewed'].') </span><br/>
                                  <a class="author" href="'.site_url('profile/view/'.$card['user']).'">'.$card['author'].'</a>
                                  <span>created</span><br/>
                                  <a class="card_title" href="'.site_url('storyboard/view/'.$card['id']).'">'.$card['title'].'</a>
                                </li>';
                            }
                            ?>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- END Most Viewed Storyboards -->
                </div>
              </div>
              
              <div class="col-md-4 col-sm-6">
                <div class="row">

<!-- TradingView Widget BEGIN -->
<div class="col-md-12 m-b-20">
<div class="widget-item narrow-margin">
</div>
<div id="tv-miniwidget-8bd58"></div>
</div>
<script type="text/javascript" src="https://d33t3vvu2t2yu5.cloudfront.net/tv.js"></script>
<script type="text/javascript">
new TradingView.MiniWidget({
  "container_id": "tv-miniwidget-8bd58",
  "tabs": [
    "Equities",
    "Commodities",
    "Bonds",
    "Forex"
  ],
  "symbols": {
    "Equities": [
      [
        "S&P500",
        "SPX500"
      ],
      [
        "NQ100",
        "NAS100"
      ],
      [
        "Dow30",
        "DOWI"
      ],
      [
        "NYSE",
        "INDEX:NYA"
      ]
    ],
    "Commodities": [
      [
        "Euro",
        "E61!"
      ],
      [
        "Gold",
        "GC1!"
      ],
      [
        "Oil",
        "CL1!"
      ],
      [
        "Gas",
        "NG1!"
      ]
    ],
    "Bonds": [
      [
        "US 2YR",
        "TUZ2013"
      ],
      [
        "US 10YR",
        "TYZ2013"
      ],
      [
        "US 30YR",
        "USZ2013"
      ],
      [
        "Euro Bund",
        "FX:BUND"
      ]
    ]
  },
  "gridLineColor": "#E9E9EA",
  "fontColor": "#83888D",
  "underLineColor": "#d8d8d8",
  "timeAxisBackgroundColor": "#b6d7a8",
  "trendLineColor": "#FF7965",
  "activeTickerBackgroundColor": "#EDF0F3",
  "large_chart_url": "https://www.tradingview.com/e/",
  "noGraph": false,
  "width": "100%",
  "height": "400px"
});
</script>
<!-- TradingView Widget END -->


                  <!-- BEGIN DOW30
                  <div class="col-md-12 m-b-20">
                    <div class="widget-item narrow-margin">
                      <iframe src="http://www.kimonolabs.com/kimonoblock/?apiid=agvidstk&apikey=535acc1e516e081e918a7ff90da54006&title=DOW 30&titleColor=ffffff&titleBgColor=962c24&bgColor=D6D6D6&textColor=962c24&linkColor=659fc0&propertyColor=615b5b" style="width:100%;height:300px;border:1px solid #efefef"></iframe>
                    </div>
                  </div>
                  END DOW30-->

                </div>
                <div class="row">

                  <!-- BEGIN Nasdaq 100 
                  <div class="col-md-12 m-b-20">
                    <div class="widget-item narrow-margin">
                      <iframe src="http://www.kimonolabs.com/kimonoblock/?apiid=a0n6wi1u&apikey=535acc1e516e081e918a7ff90da54006&title=NASDAQ 100&titleColor=ffffff&titleBgColor=962c24&bgColor=D6D6D6&textColor=962c24&linkColor=659fc0&propertyColor=615b5b" style="width:100%;height:300px;border:1px solid #efefef"></iframe>
                    </div>
                  </div>
                  END Nasdaq 100 -->

                </div>

                <div class="row">
                  <!-- BEGIN Latest Sec -->
                  <div class="col-md-12 m-b-20">
                    <div class="widget-item narrow-margin">
                      <!-- start feedwind code -->


                     



                      <!-- end feedwind code -->
                    </div>
                  </div>
                  <!-- END Latest Sec -->
                </div>
              </div>

          </div>
        </div>

      </div>

	  </div><!-- end content -->
 </div>