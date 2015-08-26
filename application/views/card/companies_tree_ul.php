
<?php foreach ($sics as $i => $sic) { ?>
    <li class="row-fluid">
    <div class="tree_element checkbox check-danger">
      <a href="#" class="expand sic"><i class="fa fa-plus-circle"></i></a>
      <input id="sic_<?php echo $sic->sic_code; ?>" type="checkbox" class="sic_checkbox" value="<?php echo $sic->sic_code; ?>">
      <label for="sic_<?php echo $sic->sic_code; ?>" title=" <?php echo $sic->sic; ?>">
        <?php echo cut_string($sic->sic , 43); ?>
      </label>
    </div>
        <ul class="fourth_lvl collapsed">
        <?php foreach ($sic->companies as $c => $comp) { ?>
            <li class="row-fluid">
                  <div class="tree_element checkbox check-default company">
                    <input id="comp_<?php echo $comp->entity_id; ?>" type="checkbox" class="comp_checkbox" value="<?php echo $comp->entity_id; ?>">
                    <label for="comp_<?php echo $comp->entity_id; ?>" title="<?php echo $comp->company_name; ?>">
                    <?php echo cut_string($comp->company_name , 43); ?>
                    </label>
                  </div>
                </li>
        <?php } ?>
        </ul>
    </li>
<?php } ?>
