<?php 
  foreach ($decision_cats as $k => $d_c) {
    echo '<li class="row-fluid first_lvl">
            <div class="tree_element checkbox check-danger">
              <a href="#" class="expand"><i class="fa fa-plus-square"></i></a>
              <input id="cat_'.$k.'" type="checkbox" class="cat_checkbox" value="'.$d_c->decision_category.'" id="'.$d_c->decision_category.'">
              <label for="cat_'.$k.'">'.$d_c->decision_category.'</label>
            </div>';
      echo '<ul class="sec_lvl collapsed">';
            foreach ($d_c->kpis as $j => $kpi) {
              echo '<li class="row-fluid">
                      <div class="tree_element checkbox check-default kpi">
                        <input id="kpi_'.$kpi->term_id.'" type="checkbox" class="kpi_checkbox" value="'.$kpi->term_id.'" data-desc="'.$kpi->description.'">
                        <label for="kpi_'.$kpi->term_id.'">'.$kpi->name.'</label>
                      </div>
                    </li>';
            }
      echo '</ul>';
    echo '</li>';
  }