$(document).ready(function() 
{
    //display kpi descripton
    $('body').on('click', '.add_kpi select[name="kpis2"] option', function() {
        var id = $(this).attr('value');
        var desc = $('#kpis_tree input[value="'+id+'"]').attr('data-desc');
        $('#builder_kpi_description').text(desc);
    });
    $('body').on('click', '.add_kpi select[name="kpis"] option', function() {
        var desc = $(this).attr('data-desc');
        $('#selector_kpi_description').text(desc);
    });

    //uncheck all companies on load
    $('#companies_tree input').prop('checked', false);
    $('#kpis_tree input').prop('checked', false);
    create_tree($('#companies_tree'));
    create_tree($('#kpis_tree'));
    manage_companies_checkboxes();
    manage_kpis_checkboxes();

    //uncheck companies_tree link action
    $('body').on('click', '#uncheck_all_companies', function(){
        $('#companies_tree input:checked').each(function(i){
            $(this).prop('checked', false);
        });
        return false;
    });

    //uncheck kpis_tree link action
    $('body').on('click', '#uncheck_all_kpis', function(){
        $('#kpis_tree input:checked').each(function(i){
            $(this).prop('checked', false);
        });
        return false;
    });

    //empty compaies_list link action select_all_companies_list
    $('body').on('click', '#empty_companies_list', function(){
        $('select[name="companies2"]').empty();
        $('#companies_tree input:checked').each(function(i){
            $(this).prop('checked', false);
        });
        return false;
    });
    
    //delete selected companies
    selected = [];
    $('body').on('click', '#delete_companies', function(){
        $('select[name="companies2"] option:selected').each(function(i){
            selected[i] = $(this).val();
        }); 
       
        $('#companies_tree input:checked').each(function(i){
            if(selected.indexOf($(this).val())!= '-1'){
                $(this).prop('checked', false);
                update_checkboxes($(this).parents('ul.fourth_lvl'), false, 'comp_checkbox', 'sic_checkbox');
            }
        });
        
        $('select[name="companies2"] option:selected').remove();
        selected = [];
        return false;
    });

    //delete selected companies
    $('body').on('click', '#delete_comp', function(){
        $('select[name="companies"] option:selected').remove();
        return false;
    });

    //empty kpis_list link action 
    $('body').on('click', '#empty_kpis_list', function(){
        $('select[name="kpis2"]').empty();
        $('#kpis_tree input:checked').each(function(i){
            $(this).prop('checked', false);
        });
        return false;
    });

    //delete kpis 

    selected = [];
    $('body').on('click', '#delete_kpis', function(){
        $('select[name="kpis2"] option:selected').each(function(i){
            selected[i] = $(this).val();
        }); 
       
        $('#kpis_tree input:checked').each(function(i){
            if(selected.indexOf($(this).val())!= '-1'){
                $(this).prop('checked', false);
            }
        });
        
        $('select[name="kpis2"] option:selected').remove();
    
        selected = [];
    
        return false;
    });

   
   //delete kpis 1 
    $('body').on('click', '#del_kpis', function(){
        $('select[name="kpis"] option:selected').remove();
        $('#selector_kpi_description').text('');
        return false;
    });
    /*
$('body').on('click', '#delete_kpis', function(){
        $('select[name="kpis2"] option').prop('selected', true);
        return false;
    });
*/

    $('body').on('click', '#decision_category', function(event ){
        event.preventDefault();
        $(this).parent().siblings('.active').removeClass('active');
        $(this).parent().addClass('active');
        update_kpi_tree('decision_category');
        return false;
    });
    $('body').on('click', '#financial_category', function(event ){
        event.preventDefault();
        $(this).parent().siblings('.active').removeClass('active');
        $(this).parent().addClass('active');
        update_kpi_tree('financial_category');
        return false;
    });

    //$('#description').wysihtml5();

});

function update_kpi_tree(group_by) 
{
    $('#kpis_tree').empty()
    $('#kpis_tree').css('background', 'url("'+site_url+'/assets/img/AjaxLoader.gif") no-repeat center center');
    var url = site_url+"card/group_by_"+group_by;
    $.ajax({
        type: "POST",
        url: url,
        success: function(response)
        {
            if(response) 
            {
                $('#kpis_tree').css('background', 'none');
                $('#kpis_tree').append(response);
                $('#kpis_tree input').prop('checked', false);
                create_tree($('#kpis_tree'));
                manage_kpis_checkboxes();
            }
            else
            {
                $('#kpis_tree').css('background', 'none');
                $('#kpis_tree').append('<span class="warning">try again!</span>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            $('#kpis_tree').css('background', 'none');
            $('#kpis_tree').append('<span class="warning">try again!</span>');
        }
    });
}


function manage_kpis_checkboxes()
{
    $('#kpis_tree').on('click', 'input.cat_checkbox', function(){
        //check/uncheck sic checkbox and children checkboxes, if collapsed => expand
        if($(this).is(':checked'))
        {
            $(this).parent().next().find('input.kpi_checkbox').prop('checked', true);
            expand($(this).prev(), $('#kpis_tree'));
        }
        else
        {
            $(this).parent().next().find('input.kpi_checkbox').prop('checked', false);
        }
        update_checkboxes($(this).parent().next(), true, 'kpi_checkbox', 'cat_checkbox');
    });

    $('#kpis_tree').on('click', 'input.kpi_checkbox', function(){
        update_checkboxes($(this).parents('ul.sec_lvl'), true, 'kpi_checkbox', 'cat_checkbox');
    });
    
    //*
    $('body').on('click', 'input.kpi_checkbox', function(){
        //check/uncheck sic checkbox and children checkboxes, if collapsed => expand
        if($(this).is(':checked'))
        {}
        else
        {
            $('select[name="kpis2"] option[value="'+$(this).val()+'"]').remove();
        }
    });


    
}

function manage_companies_checkboxes()
{
    $('#companies_tree').on('click', 'input.sic_checkbox', function(){
        //check/uncheck sic checkbox and children checkboxes, if collapsed => expand
        if($(this).is(':checked'))
        {
            $(this).parent().next().find('input.comp_checkbox').prop('checked', true);
            expand($(this).prev(), $('#companies_tree'));
        }
        else
        {
            $(this).parent().next().find('input.comp_checkbox').prop('checked', false);
        }
        update_checkboxes($(this).parent().next(), true, 'comp_checkbox', 'sic_checkbox');
    });

    $('#companies_tree').on('click', 'input.comp_checkbox', function(){
		update_checkboxes($(this).parents('ul.fourth_lvl'), true, 'comp_checkbox', 'sic_checkbox');
    });
    
    //*
    $('body').on('click', 'input.comp_checkbox', function(){
        //check/uncheck sic checkbox and children checkboxes, if collapsed => expand
        if($(this).is(':checked'))
        {}
        else
        {
            $('select[name="companies2"] option[value="'+$(this).val()+'"]').remove();
        }
    });
//*/ 
}

function update_checkboxes(elmt, bool, child_class, parent_class)
{
    //all checkboxes are -checked -unchecked - some are, some aren't
    var all_comp = elmt.find('input.'+child_class).length;
    var checked_comp = elmt.find('input.'+child_class+':checked').length;
    var unchecked_comp = elmt.find('input.'+child_class+':not(:checked)').length;
    if(checked_comp == all_comp) //all checkboxes are chekced, we check the parent
    {
        elmt.prev().find('input.'+parent_class).prop('checked', true);
        elmt.prev().removeClass('check-warning').addClass('check-danger');
    }
    else
    {
        if(unchecked_comp == all_comp) //all checkboxes are unchekced, we uncheck the parent
        {
            elmt.prev().find('input.'+parent_class).prop('checked', false);
            elmt.prev().removeClass('check-warning').addClass('check-danger');
        }
        else
        {
            elmt.prev().find('input.'+parent_class).prop('checked', true);
            elmt.prev().removeClass('check-danger').addClass('check-warning');
        }
    }
    if(bool!==false){
        if(child_class == 'comp_checkbox')
            update_companies_list();
        if(child_class == 'kpi_checkbox')
            update_kpis_list();
    }
}

function update_companies_list()
{
    //$('select[name="companies2"] .tree_added').remove();
    $('#companies_tree input.comp_checkbox:checked').each(function(i){
        
        if($('select[name="companies2"] option[value="'+$(this).val()+'"]').length == 0)
        {
            $('select[name="companies2"]').append('<option class="tree_added" value="'+$(this).val()+'" >'+$(this).next().text()+'</option>');
        //$('select[name="companies2"]').append('<option class="tree_added" value="'+$(this).val()+'" selected="selected">'+$(this).next().text()+'</option>');
        }

    });
    
//    alert($('select[name="companies2"]').toSource());
}

function update_kpis_list()
{
    //$('select[name="companies2"] .tree_added').remove();
    $('#kpis_tree input.kpi_checkbox:checked').each(function(i){
        
        if($('select[name="kpis2"] option[value="'+$(this).val()+'"]').length == 0)
        {
            $('select[name="kpis2"]').append('<option class="tree_added" data-desc="'+$(this).attr('data-desc')+'" value="'+$(this).val()+'" >'+$(this).next().text()+'</option>');
        }

    });
    
}

function create_tree(list)
{
    list.on('click', 'a.expand', function(){
        expand($(this), list);
        return false;
    });
    list.on('click', 'a.collapse', function(){
        collapse($(this), list);
        return false;
    });
    update(list);
}

function update(list)
{
    list.find('ul.expanded').show();
    list.find('ul.collapsed').hide();
}

function expand(elmt, list)
{
    to_expand = elmt.parent().next();
    if(to_expand.hasClass('collapsed'))
    {
        to_expand.removeClass('collapsed').addClass('expanded');
        elmt.removeClass('expand').addClass('collapse');
        if(elmt.children('i').hasClass('fa-plus-square'))
        {
            elmt.children('i').removeClass('fa-plus-square').addClass('fa-minus-square'); 
        }
        if(elmt.children('i').hasClass('fa-plus-square-o'))
        {
            elmt.children('i').removeClass('fa-plus-square-o').addClass('fa-minus-square-o'); 
        }
        if(elmt.children('i').hasClass('fa-plus-circle'))
        {
            elmt.children('i').removeClass('fa-plus-circle').addClass('fa-minus-circle');
        }
        update(list);
    }
}

function collapse(elmt, list)
{
    to_collapse = elmt.parent().next();
    if(to_collapse.hasClass('expanded'))
    {
        to_collapse.removeClass('expanded').addClass('collapsed');
        elmt.removeClass('collapse').addClass('expand');
        if(elmt.children('i').hasClass('fa-minus-square'))
        {
            elmt.children('i').removeClass('fa-minus-square').addClass('fa-plus-square'); 
        }
        if(elmt.children('i').hasClass('fa-minus-square-o'))
        {
            elmt.children('i').removeClass('fa-minus-square-o').addClass('fa-plus-square-o'); 
        }
        if(elmt.children('i').hasClass('fa-minus-circle'))
        {
            elmt.children('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
        }
        update(list);
    }
}
function loading(){

}
function load_sic_companies(industry){
   // console.info(industry);
    industry = encodeURI(industry);
   // console.info(industry);
    $('#companies_tree').empty();
    $('#companies_tree').css('background', 'url("'+site_url+'/assets/img/AjaxLoader.gif") no-repeat center center');
    var url = site_url + 'card/company_industry/'+industry;
    $.ajax({
        type: "POST",
        url: url,
        success: function(response){
            if(response) {
                $('#companies_tree').css('background', 'none');
                $('#companies_tree').append(response);
                $('#companies_tree input').prop('checked', false);
                create_tree($('#companies_tree'));
                manage_kpis_checkboxes();
            }
            else{
                $('#companies_tree').css('background', 'none');
                $('#companies_tree').append('<span class="warning">try again!</span>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            $('#companies_tree').css('background', 'none');
            $('#companies_tree').append('<span class="warning">try again!</span>');
        }
    });
}

function load_sic_companies_search(industry, company_name, sector_name, sic){
   // console.info(industry);
    industry = encodeURI(industry);
   // console.info(industry);
    $('#companies_tree').empty();
    $('#companies_tree').css('background', 'url("'+site_url+'/assets/img/AjaxLoader.gif") no-repeat center center');
    var url = site_url + 'card/company_industry/'+industry;
    $.ajax({
        type: "POST",
        url: url,
        success: function(response){
            if(response) {
                $('#companies_tree').css('background', 'none');
                $('#companies_tree').append(response);
                $('#companies_tree input').prop('checked', false);
                create_tree($('#companies_tree'));
                manage_kpis_checkboxes();
				setTimeout(
    			function() {
				$('#sectors_name option[value="'+sector_name+'"]').attr('selected', true);
				$('.company label[title="'+company_name+'"]').prev('input[type="checkbox"]').prop('checked', true);
				$('.company label[title="'+company_name+'"]').parents('ul.fourth_lvl').removeClass('collapsed').addClass('expanded').show();
				$('.company label[title="'+company_name+'"]').parents('ul.fourth_lvl').prev('.tree_element').children('a').removeClass('expand').addClass('collapse');
				$('.company label[title="'+company_name+'"]').parents('ul.fourth_lvl').prev('.tree_element').children('a').children('i').removeClass('fa-plus-circle').addClass('fa-minus-circle');
				update_checkboxes($('.company label[title="'+company_name+'"]').parents('ul.fourth_lvl'), true, 'comp_checkbox', 'sic_checkbox');
				$('#companies_tree').animate({
						scrollTop: $('.fourth_lvl.expanded').offset().top-200
				}, 1000);
				}, 1500);
            }
            else{
                $('#companies_tree').css('background', 'none');
                $('#companies_tree').append('<span class="warning">try again!</span>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            $('#companies_tree').css('background', 'none');
            $('#companies_tree').append('<span class="warning">try again!</span>');
        }
    });
}

