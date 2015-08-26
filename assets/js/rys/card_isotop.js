cards_isotope_sortAscending = false;
cards_isotope_sort = "creation_time";
cards_isotope_sortAscending2 = false;
cards_isotope_sort2 = "creation_time";
$(window).on('load', function () {
    cards_isotope();
    cards_isotope2();
    $('.back').hide();
});

function cards_isotope(){
    $('#cards_isotope').isotope({
        itemSelector: '.element-item',
        transitionDuration: '1s',
        sortAscending: cards_isotope_sortAscending,
        sortBy: cards_isotope_sort ,
        layoutMode: 'masonry',
        getSortData: sort_fct,
        filter: function(){
            var is_ok  = true;
            var creation_time = $(this).attr("data-creation_time");
            var autor = $(this).attr("data-autor");
            //var description = $(this).attr("data-description");
            var name = $(this).attr("data-name");

            var search_val = $("#search_input") .val();
            var _creation_time = (creation_time.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
            var _autor = (autor.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
            var _name = (name.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
            //var _description = (description.indexOf(search_val) > -1)?true:false;
            return _creation_time || _autor || _name; //|| _description;
            }
        });
}
function cards_isotope2(){
    $('#cards_isotope2').isotope({
        itemSelector: '.element-item',
        stamp: '.stamp',
        transitionDuration: '1s',
        sortAscending: cards_isotope_sortAscending2,
        sortBy: cards_isotope_sort2,
        layoutMode: 'masonry',
        getSortData: sort_fct2,
        filter: function(){
            var is_ok  = true;
            var card_new = $(this).attr("data-id");
            if(card_new == "0" || card_new == 0)
                return true
            var creation_time = $(this).attr("data-creation_time");
            var name = $(this).attr("data-name");
            //var description = $(this).attr("data-description");
            var public_ = $(this).attr("data-public");
            var period = $(this).attr("data-period");

            var card_new_  = (card_new_ == "0")?true:false;
            var search_val = $("#search_input") .val();
            var _creation_time = (creation_time.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
            var _name = (name.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
            //var _description = (description.indexOf(search_val) > -1)?true:false;
            var _public_ = (public_.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
            var _period = (period.toLowerCase().indexOf(search_val.toLowerCase()) > -1)?true:false;
            return _creation_time || _name  || _public_  || _period; //|| _description  ;
            }
        });
}

var sort_fct = {
    description: function( itemElem ) {
        var _description = $( itemElem ).attr('data-name');
        return _description;
        },
    autor: function( itemElem ) {
        var _autor = $( itemElem ).attr('data-autor');
        return _autor;
        },
    creation_time: function( itemElem ) {
        var _date = $( itemElem ).attr('data-creation_time');
        return Date.parse(convertDateTime(_date));
        },
    viewed: function( itemElem ) {
        var _viewed = $( itemElem ).attr('data-viewed');
        return parseFloat( _viewed.replace( /[\(\)]/g, '') );
        }             
    };
var sort_fct2 = {
    name: function( itemElem ) {
        var _name = $( itemElem ).attr('data-name');
        if(_name)
            _name = _name.toLowerCase();
        return _name;
        },
    creation_time: function( itemElem ) {
        var _date = $( itemElem ).attr('data-creation_time');
        return Date.parse(convertDateTime(_date));
        },
    viewed: function( itemElem ) {
        var _viewed = $( itemElem ).attr('data-viewed');
        if(_viewed){
        return parseFloat( _viewed.replace( /[\(\)]/g, '') );
        }
        },
    author: function( itemElem ) {
        var _autor = $( itemElem ).attr('data-author');
        return _autor;
    }          
    };

function convertDateTime(dateTime){
    
    if(dateTime==0)
        return 0;
        
    dateTime = dateTime.split(" ");

    var date = dateTime[0].split("-");
    var yyyy = date[0];
    var mm = date[1]-1;
    var dd = date[2];

    var time = dateTime[1].split(":");
    var h = time[0];
    var m = time[1];
    var s = parseInt(time[2]); //get rid of that 00.0;

    return new Date(yyyy,mm,dd,h,m,s);
}

$('body').on('click', '#search_span', function(e) {
    cards_isotope();
    cards_isotope2();
});
$('body').on('keyup', '#search_input', function(e) {
	var search_value = $('#search_input').val();
	if(search_value.length >= 3){
		sorted_data_load();
	}else if(search_value == ''){
		sorted_data_load();
	}
    //cards_isotope();
    //cards_isotope2();
});


$('.content').on('click', '#filters_isotope button', function(e) {
    /*
    if(cards_isotope_sort == $(this).attr("data-name")){
    cards_isotope_sortAscending = !cards_isotope_sortAscending;
    }else{
        cards_isotope_sortAscending =true;
    }
    */
    cards_isotope_sortAscending = !cards_isotope_sortAscending;

    cards_isotope_sort = $(this).attr("data-name");

    var asc = $(this).find("i").hasClass("fa-sort-amount-asc");
    $("#filters_isotope button i").removeClass("fa-sort-amount-asc");
    $("#filters_isotope button i").removeClass("fa-sort-amount-desc");
    $("#filters_isotope button").removeClass("active");

    if(cards_isotope_sortAscending){
        $(this).find("i").addClass("fa-sort-amount-asc");
    }
    else{
        $(this).find("i").addClass("fa-sort-amount-desc");
    }

    $(this).addClass("active");
    sorted_data_load();
   // cards_isotope();
    });
$('.content').on('click', '#filters_isotope2 button', function(e) {
    
    /*
    if(cards_isotope_sort2 != $(this).attr("data-name")){
    cards_isotope_sortAscending2 = !cards_isotope_sortAscending2;
    }else{
        cards_isotope_sortAscending2 =true;
    }
    */
    cards_isotope_sortAscending2 = !cards_isotope_sortAscending2;
    
    cards_isotope_sort2 = $(this).attr("data-name");

    var asc = $(this).find("i").hasClass("fa-sort-amount-asc");
    $("#filters_isotope2 button i").removeClass("fa-sort-amount-asc");
    $("#filters_isotope2 button i").removeClass("fa-sort-amount-desc");
    $("#filters_isotope2 button").removeClass("active");

    if(cards_isotope_sortAscending2){
        $(this).find("i").addClass("fa-sort-amount-asc");
    }
    else{
        $(this).find("i").addClass("fa-sort-amount-desc");
    }
    $(this).addClass("active");
	sorted_data_load();
    //cards_isotope2();
    });
    $('body').on('click', '.flipper', function(e) {
        var id = $(this).attr("data-card-id");
        if($(this).attr('title') == 'flip Storyboard')
            var _obj = $('.flip_sb[data-card-id="'+id+'"]');
        else
            var _obj = $('.flip_card[data-card-id="'+id+'"]');
        
        if($('.card_flipped [data-card-id="'+id+'"]').attr("data-card-flipped") == 'no'){
            _obj.flippy({
                duration: "500",
                verso:  $('.card_flipped [data-card-id="'+id+'"]').html(),
                direction : "LEFT",
                depth: 1,
                onStart: function(){
                    $('.card_flipped [data-card-id="'+id+'"]').attr("data-card-flipped","yes");
                },
                onReverseFinish : function(){
                    $('.card_flipped [data-card-id="'+id+'"]').attr("data-card-flipped","no");
                    //$('#view_card_reporting_period select').destroy();
                    //alert('ok');
                },
                onFinish :  function(){
                    //alert('done');
                }
            });
        }
        else{
            _obj.flippyReverse();
        }
        
        return false;
    });
    $('body').on('click', '.flip_card_toggle', function(e) {
        e.preventDefault();
        var _obj = $('#card_id');
        if(_obj.attr("data-card-flipped") == 'no'){
             _obj.parent().stop().rotate3Di('flip', 800, {direction: 'clockwise', sideChange: flipToBack});
             _obj.attr("data-card-flipped",  'yes');
        }
        else{

            _obj.parent().stop().rotate3Di('unflip', 800, {sideChange: flipToFront});
            _obj.attr("data-card-flipped",  'no');
        }
        return false;
    });
	$('body').on('click', '.flip_card_toggle_edit_mode', function(e) {
        e.preventDefault();
        var _obj = $('.card_flip_mode_edit');
        if(_obj.attr("data-card-flipped") == 'no'){
             _obj.parent().stop().rotate3Di('flip', 800, {direction: 'clockwise', sideChange: flipToBack});
             _obj.attr("data-card-flipped",  'yes');
			 $('#is_over_lay').addClass('overlay');
        }
        else{

            _obj.parent().stop().rotate3Di('unflip', 800, {sideChange: flipToFrontEditMode});
            _obj.attr("data-card-flipped",  'no');
			$('#is_over_lay').removeClass('overlay');
        }
        return false;
    });


function flipToBack()
{
   $('.front').hide();
    $('.back').show();
}
function flipToFront()
{

   $('.front').show();
   $('.back').hide();
    reloadChart();
}
function flipToFrontEditMode()
{
   $('.front').show();
   $('.back').hide();
}