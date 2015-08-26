<div class="map chart">
    <script type="text/javascript" charset="utf-8" >
        <?php 
            echo "var markers = ";
            if(!empty($get_markers))
            	echo $get_markers;
			else 
				echo "{}";
            echo ";";
        ?>  
    </script>

    <div id="usa_map" style="margin: 0px auto;">
    </div>
</div>