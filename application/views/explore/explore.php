<?php
if (!empty($kpis)) {
    $ul_width = ((string) (count($kpis) * 160 + 330)) . "px";
}
else
    $ul_width = '100%';
?>

<div style="overflow:auto;">
    <div id="explore_rank" class="explore chart active" data-width="<?php echo $ul_width; ?>"  style="width:100%; position:relative; padding-top: 30px;">
        <div class="controls controls_rank" style="display:none; overflow: visible; width:auto">
            <ul class="pivot">
                <li>
                    <a id="item_chart" class="item_chart" href="#"></a>
                </li>
                <li>
                    <a id="item_nbr" class="item_nbr" href="#"></a>
                </li>
            </ul>
            <?php
            if (!empty($kpis)) {
                //$width = ((string) (90 / count($kpis))) . "%";
                // $ul_width = ((string) (count($kpis) * (90 / count($kpis)) ))."%";

                $width = "150px";
                ?>
                <ul id="ul_control_sort" class="sort" style="overflow: auto; position:absolute; left:320px; width:<?php if (!empty($kpis)) { echo ((string) (count($kpis) * 160)) . 'px'; } ?>">
                    <?php
                    //var_dump($kpis);
                    foreach ($kpis as $kpi) { 
                      $kpi_obj = get_kpi($kpi);
                      if(!empty($kpi_obj->name)) {
                        $name = $kpi_obj->name;
                        $desc = $kpi_obj->description;
                        $abr = cut_string($name, 30);
                        $is_active = ($kpi==$sort)?"active":false;
                        /*if($i == 0)
                            echo '<li name="'.$kpi.'" class="tooltip-toggle no_position" data-placement="top" data-toggle="tooltip" title="" data-original-title="'.$name.' -- '.$desc.'" style="width:'.$width.'; height: auto;" >';
                        else */ 
                            echo '<li name="'.$kpi.'" class="tooltip-toggle no_position blue-tooltip" data-placement="top" data-toggle="tooltip" title="" data-original-title="'.$name.' -- '.$desc.'" style="width:'.$width.'; height: auto;" >';
                        ?>
                            <?php echo $abr; ?>
                        </li>

                    <?php  
                      }
                    } 
                  } ?>
            </ul>
        </div>
        <div class="grid">
            <?php 
                if(!empty($get_companies))
                    echo $get_companies; 
            ?>  
        </div>
    </div>
</div>

