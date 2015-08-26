<?php
  foreach ($cards as $k => $card) {
	  $card_type = $card->type;
    $guid = id_to_guid($card->id).md5('idaciti');
    $date = date_create($card->creation_time);
    echo '<li class="deck_card '.$card->open_type.'" id="card_'.$card->id.'">';
    echo  img($card->type.'.png', $card->type);
    echo '  <h4 data-title="'.$card->name.'">'.cut_string($card->name, 25).'</h4>
            <p class="description" data-desc="'.str_replace('"','\"',strip_tags($card->description)).'">'.cut_string(strip_tags($card->description), 50).'</p>
            <p class="card_footer">Created: '.date_format($date, "m/d/Y").'<br/><span class="author">'.$card->first_name.' '.$card->last_name.'</span></p>';
    echo '<div class="deck_card_action">
            <a href="#" class="select_deck_card"><i class="fa fa-plus-circle"></i></a> 
            <a href="#" class="preview_deck_card"  data-target="#largeModal" data-toggle="modal" data-remote="'.site_url('card/embed/'.$guid).'" ><i class="fa fa-info-circle"></i></a>
            <input type="hidden" name="deck_card_'.$k.'" class="deck_card_id" value="'.$card->id.'" />
            <input type="hidden" name="deck_card_guid_'.$k.'" class="deck_card_guid" value="'.$guid.'" />
			<input type="hidden" name="deck_card_type" class="deck_card_type" value="'.$card_type.'" />
          </div>';
    echo '</li>';
  }

?>