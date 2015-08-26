<?php
    if(empty($period))
    {
        if(!empty($obj->period))
            $period = $obj->period;
    }
    elseif(empty($period))
        $period = '2013';

    $id="reporting_period_edit";
    if(!empty($view) && $view)
        $id="view_card_reporting_period"
?>

<div class="" id="<?php echo $id; ?>"  style="display:none;">
  <div class="btn-group"> <a class="btn btn-primary dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#" style="width:120px;"><?php echo $period; ?><span class="caret"></span> </a>
    <ul class="dropdown-menu" id="reporting_period">
    <input type="hidden" name="reporting_period" value="<?php echo $period; ?>" />
    <?php 
    foreach ($periods as $k => $p) {
      $open = ($period == $p->reporting_period) ? '<strong>' : '';
      $close = ($period == $p->reporting_period) ? '</strong>' : '';
      echo '<li><a href="#" data="'.$p->reporting_period.'" > '.$open.$p->reporting_period.$close.'</a></li>';
    }
    /*
  <select name="period">
  <?php 
    foreach ($periods as $k => $p) {
      $selected = ($period == $p->reporting_period) ? 'selected="selected"' : '';
      echo '<option value="'.$p->reporting_period.'" '.$selected.'> '.$p->reporting_period.'</option>';
    }
/*
    <option value="2013Q1" <?php echo ($period == "2013Q1") ? 'selected="selected"' : ''; ?>>2013 Q1</option>
    <option value="2013Q2" <?php echo ($period == "2013Q2") ? 'selected="selected"' : ''; ?>>2013 Q2</option>
    <option value="2013Q3" <?php echo ($period == "2013Q3") ? 'selected="selected"' : ''; ?>>2013 Q3</option>
    <option value="2013Q4" <?php echo ($period == "2013Q4") ? 'selected="selected"' : ''; ?>>2013 Q4</option>

    <option value="2012" <?php echo ($period == "2012") ? 'selected="selected"' : ''; ?>> 2012</option>
    <option value="2012Q1" <?php echo ($period == "2012Q1") ? 'selected="selected"' : ''; ?>>2012 Q1</option>
    <option value="2012Q2" <?php echo ($period == "2012Q2") ? 'selected="selected"' : ''; ?>>2012 Q2</option>
    <option value="2012Q3" <?php echo ($period == "2012Q3") ? 'selected="selected"' : ''; ?>>2012 Q3</option>
    <option value="2012Q4" <?php echo ($period == "2012Q4") ? 'selected="selected"' : ''; ?>>2012 Q4</option>

    <option value="2011" <?php echo ($period=="2011")?'selected="selected"':''; ?>> 2011</option>
    <option value="2011Q1" <?php echo ($period=="2011Q1")?'selected="selected"':''; ?>>2011 Q1</option>
    <option value="2011Q2" <?php echo ($period=="2011Q2")?'selected="selected"':''; ?>>2011 Q2</option>
    <option value="2011Q3" <?php echo ($period=="2011Q3")?'selected="selected"':''; ?>>2011 Q3</option>
    <option value="2011Q4" <?php echo ($period=="2011Q4")?'selected="selected"':''; ?>>2011 Q4</option>

    <option value="2010" <?php echo ($period == "2010") ? 'selected="selected"' : ''; ?>> 2010</option>
    <option value="2010Q1" <?php echo ($period == "2010Q1") ? 'selected="selected"' : ''; ?>>2010 Q1</option>
    <option value="2010Q2" <?php echo ($period == "2010Q2") ? 'selected="selected"' : ''; ?>>2010 Q2</option>
    <option value="2010Q3" <?php echo ($period == "2010Q3") ? 'selected="selected"' : ''; ?>>2010 Q3</option>
    <option value="2010Q4" <?php echo ($period == "2010Q4") ? 'selected="selected"' : ''; ?>>2010 Q4</option>

    <option value="2009" <?php echo ($period == "2009") ? 'selected="selected"' : ''; ?>> 2009</option>
    <option value="2009Q1" <?php echo ($period == "2009Q1") ? 'selected="selected"' : ''; ?>>2009 Q1</option>
    <option value="2009Q2" <?php echo ($period == "2009Q2") ? 'selected="selected"' : ''; ?>>2009 Q2</option>
    <option value="2009Q3" <?php echo ($period == "2009Q3") ? 'selected="selected"' : ''; ?>>2009 Q3</option>
    <option value="2009Q4" <?php echo ($period == "2009Q4") ? 'selected="selected"' : ''; ?>>2009 Q4</option>
*/
    ?>
</ul>
</div>
</div>