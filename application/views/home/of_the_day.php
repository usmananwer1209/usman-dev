<?php
  $folder =   dirname(dirname(__FILE__));
  require_once $folder."/commun/navbar.php";
 ?>
<div class="page-container row-fluid">
  <?php require_once $folder."/commun/main-menu.php";?>
    <div class="page-content">


      
  <div class="content of_the_day_container">
    <div class="row">
      <div class="col-md-6 m-b-20 col-lg-6 col-sm-12">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h3><span class="semi-bold">Card</span> of the day</h3>
          </div>
          <div class="grid-body no-border">
            <div class="row">
              <div class="col-md-12">
                <div class="alert alert-error hidden" id="card_server_error">
                  <button data-dismiss="alert" class="close"></button>
                  Error: An error occured while contacting the server, please check your connection and try again, or contact the developer.
                </div>
                <div class="alert alert-error hidden" id="card_select_error">
                  <button data-dismiss="alert" class="close"></button>
                  Error: You need to select a card to update the card of the day!
                </div>
                <div class="alert alert-success hidden" id="card_success_message">
                  <button data-dismiss="alert" class="close"></button>
                  Success: The card of the day has been successfully updated!
                </div>
                <?php 
                  if(count($public_cards) > 0) {
                    $current_card = '';
                    if(!empty($card_otd[0]))
                      $current_card = $card_otd[0]->card;
                    echo '<p>Select the card of the day to display on the dashboard:</p>';
                    echo '<select id="card_of_the_day" name="card_of_the_day" style="width:90%;">';
                    echo '<option></option>';
                    foreach ($public_cards as $card) {
                      $selected = ($current_card == $card->id) ? 'selected="selected"' : '';
                      echo '<option value="'.$card->id.'" '.$selected.'>'.$card->name.'</option>';
                    }
                    echo '</select>';
                    echo '<p style="padding:20px;">
                            <button class="btn btn-primary btn-lg btn-large right otd" id="update_otd_card" type="button">Update !</button>
                          </p>';
                  }
                  else
                    echo '<p>No public cards are available</p>';
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-7 m-b-20 col-lg-6 col-sm-6  single-colored-widget">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h3><span class="semi-bold">Storyboard</span> of the day</h3>
          </div>
          <div class="grid-body no-border">
            <div class="row">
              <div class="col-md-12">
                <div class="alert alert-error hidden" id="storyboard_server_error">
                  <button data-dismiss="alert" class="close"></button>
                  Error: An error occured while contacting the server, please check your connection and try again, or contact the developer.
                </div>
                <div class="alert alert-error hidden" id="storyboard_select_error">
                  <button data-dismiss="alert" class="close"></button>
                  Error: You need to select a storyboard to update the card of the day!
                </div>
                <div class="alert alert-success hidden" id="storyboard_success_message">
                  <button data-dismiss="alert" class="close"></button>
                  Success: The storyboard of the day has been successfully updated!
                </div>
                <?php 
                  if(count($public_storyboards) > 0) {
                    $current_sb = '';
                    if(!empty($storyboard_otd[0]))
                      $current_sb = $storyboard_otd[0]->storyboard;
                    echo '<p>Select the storyboard of the day to display on the dashboard:</p>';
                    echo '<select id="storyboard_of_the_day" name="sb_of_the_day" style="width:90%;">';
                    echo '<option></option>';
                    foreach ($public_storyboards as $card) {
                      $selected = ($current_sb == $card->id) ? 'selected="selected"' : '';
                      echo '<option value="'.$card->id.'" '.$selected.'>'.$card->title.'</option>';
                    }
                    echo '</select>';
                    echo '<p style="padding:20px;">
                            <button class="btn btn-primary btn-lg btn-large right otd" id="update_otd_storyboard" type="button">Update !</button>
                          </p>';
                  }
                  else
                    echo '<p>No public storyboards are available</p>';
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <input type="hidden" id="site_url" value="<?php echo site_url('home/of_the_day') ?>" />

    </div>
  </div>
</div>


</div>
