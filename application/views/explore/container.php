<?php 
  $quarterly_toggle = 0;
  if(!empty($obj) && !empty($obj->quarterly_toggle))
    $quarterly_toggle = $obj->quarterly_toggle;
  $line_kpis = '';
  if(!empty($obj) && !empty($obj->line_kpis))
    $line_kpis = $obj->line_kpis;
  $column_kpis = '';
  if(!empty($obj) && !empty($obj->column_kpis))
    $column_kpis = $obj->column_kpis;
  $active_company= '';
  if(!empty($obj) && !empty($obj->active_company))
    $active_company = $obj->active_company;


?>

<input  id="hide_toggle" type="hidden" value="<?php echo $quarterly_toggle;?>" />
<input  id="active_company" type="hidden" value="<?php echo $active_company;?>" />
<input  id="line_kpis" type="hidden" value="<?php echo $line_kpis;?>" />
<input  id="column_kpis" type="hidden" value="<?php echo $column_kpis;?>" />
<div id="companies_animation_container" style="visibility: hidden">
<?php if (!empty($API_ERROR)) { ?>
	<div class="alert alert-error API_ERROR">
	  <button data-dismiss="alert" class="close"></button>
	  The <a class="link" href="#">API</a> signals errors as part of the JSON response.
	</div>
        <?php
    } else {
?>
<?php 
        if (!empty($get_companies_error)) {
            foreach ($get_companies_error as $key => $value) {
                ?>
			<div class="alert alert-error">
	          	<button data-dismiss="alert" class="close"></button>
	          	<a class="link" href="#"><?php echo $key; ?></a>  not found
	        </div>		
	<?php 
			} 
		}
	?>

	<div class="row">
	<?php 
    $folder =   dirname(dirname(__FILE__));
            require_once $folder . '/card/reporting_period.php';
            ?>

    <div id="filters" style="display:none;">
		<?php 
		if(!empty($kpis) && !empty($kpis[0])){
			$sort_int = (int)$sort;
                    if (empty($sort_int)) {
				$sort = $kpis[0];
			}
                    if (count($kpis) <= 0) {
				foreach ($kpis as $kpi) { 
					$kpi_obj = get_kpi($kpi);
					$name = $kpi_obj->name;
					$desc = $kpi_obj->description;
					$abr = cut_string($name, 50);
					//$abr = $name;
					$is_active = ((int)$kpi==(int)$sort)?"active":false;
					?>
					<button class="btn btn-white btn-cons kpi <?php echo $is_active; ?>"  type="button"
						data-toggle="tooltip"
						title="<?php echo $name; ?>"
						data-kpi-id="<?php echo (int)$kpi; ?>" 
						>
						
						<?php if($is_active == "active"){ ?>
							<?php if($sort_ascending){ ?>
								<!--<i class="fa fa-sort-amount-asc"></i>-->
								<i class="fa fa-sitemap"></i>
							<?php } else { ?>
								<!--<i class="fa fa-sort-amount-desc"></i>-->
								<i class="fa fa-sitemap"></i>
							<?php } ?>

						<?php } else { ?>
							<i class="fa "></i>
						<?php } ?>
		    	        
		    	        <?php echo $abr; ?>
			        </button>
					<?php 
				}
                    } else {
						$any_active = false;
						$active_kpi = get_kpi($sort);

						echo '<div class="btn-group">
									<a class="btn btn-primary dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#" > <span id="kpi_text">' . cut_string($active_kpi->name, 20) . '</span> <span class="caret"></span> </a>
                    <ul class="dropdown-menu kpis_select">
                    <input type="hidden" name="kpis_select" value="' . $sort . '" />';

						foreach ($kpis as $kpi) {
							$kpi_obj = get_kpi($kpi);
							if (!empty($kpi_obj->name)) {
								//$name = get_kpi_name($kpi);

								$name = $kpi_obj->name;
								$desc = $kpi_obj->description;
								$is_active = ((int)$kpi == (int)$sort) ? "active" : false;
								$pre = ((int)$kpi == (int)$sort) ? "<strong>" : '';
								$suf = ((int)$kpi == (int)$sort) ? "</strong>" : '';
								$selected = '';
								if ($is_active != false) {
									$any_active = 'active';
									$selected = 'selected="selected"';
								}
								echo '<li>' . $pre . '<a href="#" class="kpi ' . $is_active . ' " ' . $selected . ' data-kpi-id="' . (int)$kpi . '" data="' . (int)$kpi . '" data-desc="' . $name . ' : ' . $desc . '"  title="' . $name . '" alt="' . $name . '">' . $name . '</a>' . $suf . '</li>';
							}
						}
						echo '</ul>
                  </div>';
						echo '<i id="active_kpi_desc" class="fa fa-info-circle tooltip-toggle no_position blue-tooltip" style="margin-left: 9px;color:#1285D1; cursor:pointer; font-size: 20px;" data-placement="top" data-toggle="tooltip" title="" data-original-title="' . $active_kpi->name . ' : ' . $active_kpi->description . '"></i>';
						if ($any_active != false)
							echo '<button class="btn btn-white btn-cons active kpis_select_button" type="button" style="left: 10px; min-width: 50px; position: relative;">';
						else
							echo '<button class="btn btn-white btn-cons" type="button" style="left: 10px; min-width: 50px; position: relative; top: 3px;">';
						if ($sort_ascending) {
							echo '<i class="fa fa-sort-amount-asc"> </i>';
						} else {
							echo '<i class="fa fa-sort-amount-desc"> </i>';
						}
						echo '</button>';

					}
		} 
		?>
	</div>

		<div id="drilldown_btn_div" class="btn-group" style="display:none">
			<button class="btn btn-white btn-cons active" type="button" style="left: 10px; min-width: 50px; position: relative;" id="show_drilldown_btn">
				<i class="fa fa-sitemap" style="transform: rotate(-90deg)"> </i>
			</button>
		</div>
	</div>
	<div id="views">

						<div class="control explore rank 
								<?php echo $type_chart=="explore"?'active':''; ?> 
								<?php echo $type_chart=="rank"?'active':''; ?>"
								islist="<?php echo $type_chart=="explore"?'false':'true'; ?>"
								>
							<?php require_once 'explore.php'; ?>
						</div>
						<div class="control map <?php echo $type_chart=="map"?'active':''; ?>"><?php require_once 'map.php'; ?></div>
						<div class="control tree <?php echo $type_chart=="tree"?'active':''; ?>"><?php require_once 'tree.php'; ?></div>
						<div id="chart_container" class="control chart <?php echo ($type_chart=="line" || $type_chart=="column" || $type_chart=="bar" || $type_chart=="combo" || $type_chart=="area" || $type_chart=="dial")?'active '.$type_chart:''; ?>"></div>
		
		


		<div id="popup" style="position: absolute;" class="hide">
		    <span class="button b-close"><span>X</span></span>
		    <div id="popup_container">
		    </div>
		</div>

	</div>
<?php 
	}
?>
</div>

<script type="text/javascript">
		//$(function () { $("[data-toggle='tooltip']").tooltip( {container: 'body'} ); });
    if (window.jQuery) {
        $('#card_core .grid').attr('first_load', 'first_load');

        bar_mode = false;
        $('body').on('mouseenter','#source .list_view', function(){
            if(bar_mode){
                $(this).find('.scores .progress').css('opacity','0');
                $(this).find('.scores .progress-bar').css('opacity','0');
                $(this).find('.scores .num').css('opacity','1');
             }
        });

        $('body').on('mouseleave','#source .list_view', function(){
            if(bar_mode){
                $(this).find('.scores .progress').css('opacity','1');
                $(this).find('.scores .progress-bar').css('opacity','1');
                $(this).find('.scores .num').css('opacity','0');
            }
        });

        arr = calc_arr();
    }
</script>