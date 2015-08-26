var carousel_item_width = 100;
var carousel_item_height = 100;
var circle_area_w = $(".circle_area").width();
var num = $(".sriwriw .user-avatar").length;
var num_visible = parseInt(( (circle_area_w-50) / 100))  ;
$('.carousel').css('padding-left','50px');
$( ".sriwriw" ).rcarousel({
                    width: carousel_item_width,
                    height: carousel_item_height,
                    step : 1,
                    visible: (num_visible < num)?num_visible:num
                    });
                
$( "#ui-carousel-next" )
    .add( "#ui-carousel-prev" )
    .hover(
        function() {
            $( this ).css( "opacity", 0.7 );
        },
        function() {
            $( this ).css( "opacity", 1.0 );
        }
    );  


                