$(window).on('load', function () {
    $('#parks .list_header div.meta.name span.sort.desc').trigger('click');
    $('#parks .list_header div.meta.name span.sort.desc').trigger('click');
    
    $('#parks ul li').each(function(i){
        $(this).attr('data-country', BFHCountriesList[$(this).attr('data-country')]);
    });
    $('#parks ul li').each(function(i){
        if($(this).attr('data-company')=="")
        $(this).attr('data-company', "aaaaa");
    });
		});	

        
function search_filter(val)
{
        if(document.URL.indexOf('profile')>=0){
        $('#parks ul li').each(function(i){
                var str = $(this).find('div.meta.name .titles h2 span').html();
                str = $.trim(str);
                str= str.replace(" ", "_").toLowerCase();
                if(str.indexOf(val)==-1){
                    $(this).hide();
                }else{
                    $(this).show();
                }
            
            });
        }
        else if(document.URL.indexOf('circle')>=0){
        $('#parks ul li').each(function(i){
                var str = $(this).find('div.meta.name .titles h2 span').html();
                str = $.trim(str);
                str= str.replace(" ", "_").toLowerCase();

                if(str.indexOf(val)==-1){
                    $(this).hide();
                }else{
                    $(this).show();
                }
            
            });
        }
        
}

$(function() {
    $('#parks').mixitup({
        layoutMode: 'list',
        listClass: 'list',
        gridClass: 'grid',
        effects: ['fade', 'blur'],
        listEffects: ['fade', 'rotateX']
    });
    
    $('#search_input').on('keyup', '', function(){
        var val = $('#search_input').val();
        val = $.trim(val);
        val = val.replace(" ", "_").toLowerCase();
        
        search_filter(val);
        
    });
      /*
	$("#Search").on('click',function(){
		var  val = $('#search_filters input.input-medium').val();
		val  = $.trim(val);
		val = val.replace(" ", "_").toLowerCase();
		if(val){
            $('#parks').mixitup('filter', val);
			}
		else{
            $('#parks').mixitup('filter', 'all');
			}

    });*/
});

//'[data-first],[data-company],[data-country],[data-last]'
