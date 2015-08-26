$.fn.modal.Constructor.prototype.enforceFocus = function() {
};

$(document).ajaxStop($.unblockUI);

$(".shareModal select").select2();

$('body').tooltip(
        {
            'selector': '[data-original-title]',
            'container':'body'
            }
        );

$('body').on('click','.notification-container [data-modal-id], #parks [data-modal-id], .profile_area [data-modal-id]', function(){
    var circle_id = $(this).attr("data-circel-id");
    var user_id = $(this).attr("data-user-id");
    var circle_name = $(this).attr("data-circel-name");
    var user_name = $(this).attr("data-user-name");
    var circle_description = $(this).attr("data-circel-description");
    var modal_id = $(this).attr("data-modal-id");

    var modal = $(modal_id);
    $(modal).find('.circle_id').text(circle_id);
    $(modal).find('.circle_name').text(circle_name);
    $(modal).find('.user_name').text(user_name);
    $(modal).find('.circle_description').text(circle_description);
    $(modal).find('button.ajax_submit').attr("data-circle",circle_id);
    $(modal).find('button.ajax_submit').attr("data-user",user_id);
    $(modal).modal();
});


/*******************  Cards/Storyboards  **********************/
/**************************************************************/
$('body').on('click','.my-cards [data-modal-id]', function(){
    var type = $(this).attr("data-object");
    var obj_id  = "";
    var obj_public ="";
    if(type == "card"){
        obj_id = $(this).attr("data-card-id");
        obj_public = $(this).attr("data-card-public");
    }else if(type == "storyboard"){
        obj_id = $(this).attr("data-sb-id");
        obj_public = $(this).attr("data-sb-public");
    }
    var share_circles = $(this).attr("data-share-circles");
    var modal_id = $(this).attr("data-modal-id");

    var public_status = "public";
    if(obj_public == "1")
        public_status = "private";

    var modal = $(modal_id);
    $(modal).find('.public_status').text(public_status);
    $(modal).find('button.ajax_submit').attr("data-type",type);
    $(modal).find('button.ajax_submit').attr("data-obj",obj_id);
    $(modal).find('button.ajax_submit').attr("data-public",obj_public);
    $(modal).find('button.ajax_submit').attr("data-share-circles",share_circles);
    $(modal).modal();
});


$('body').on('click','#publishModal .ajax_submit', function(){
    var type = $(this).attr("data-type");
    var obj = $(this).attr("data-obj");
    var _public = $(this).attr("data-public");
    var modal = $(this).parents('.modal');
    if(_public == "1")
        _public = "0";
    else
        _public = "1";
    if(type == "card" || type == "storyboard")
        update_status_object(type, obj,_public,modal);    
});
$('body').on('click','#removeModal .ajax_submit', function(){
    var obj = $(this).attr("data-obj");
    var modal = $(this).parents('.modal');
    var type = $(this).attr("data-type");
    if(type == "card")
        delete_card(obj,modal);
    if(type == "storyboard")
        delete_object(type, obj,modal);
});
$('body').on('click','.shareModal .ajax_submit', function(){
    var obj = $(this).attr("data-obj");
    var modal = $(this).parents('.modal');
    var circles = $(modal).find(".select_circles").select2("val").toString();
    
    var type = $(this).attr("data-type");
    if(type == "card" || type == "storyboard")
        share_object(type, obj,circles,modal);
});
$('body').on('click','#removeModal button[data-dismiss="modal"]', function(){
    var modal = $(this).parents('.modal');
    $(modal).find('.alert-error').addClass('hide');
    $(modal).find('.alert-error-number').addClass('hide');
});

function share_object(type, obj,circles,modal){
    var path  = site_url + type+"/share/"+obj;
    var param = "circles="+circles;
    $.ajax( {
        url : path,
        data: param,
        type:"POST",
        success : function(data) {
            success_update(modal)
            },
        error :function(data) {
            error_update_status(modal)
            },
        });
    }
//RCH    
function delete_object(type, obj,modal){
    var path  = site_url + type+"/delete/"+obj;
    $.ajax( {
        url : path,
        type:"GET",
        success : function(data) {
            if($.trim(data)=="ok")
                success_update_delete_object(type, modal,obj);
            else
                error_update_status(modal)
            },
        error :function(data) {
            error_update_status(modal)
            }
        });
    }
function delete_card(obj,modal){
    var path  = site_url + "card/delete/"+obj;
    $.ajax( {
        url : path,
        type:"GET",
        success : function(data) {
            var response = (data.indexOf('ok') != -1)? true : false;
            if(response){
                var n = data.replace('ok', '');
                if(n == 0)
                    success_update_delete_object('card', modal,obj);
                else
                    number_error_update_status(modal)
            }
            else
                error_update_status(modal)
        },
        error :function(data) {
            error_update_status(modal)
            }
        });
    }
//RCH
function update_status_object(type, obj,_public,modal){
    var path  = site_url + type+"/publish/"+obj+"/"+_public;
    var jqxhr = $.ajax(path)
        .done(function(data) {
            if($.trim(data)=="ok"){
                    if(_public == "1")
                        _public = "0";
                    else
                        _public = "1";
                success_update_status_object(type, modal,obj,_public);
            }
            else
                error_update_status(modal);                 
            }
        )
        .fail(function(data) {
                error_update_status(modal);                
        });
    }




function success_update_status_object(type, modal,id,_public){
    if(type=="card") {
        obj = "#publish-card";
        obj_id = "data-card-id";
        obj_public = "data-card-public";
    }
    else if(type=="storyboard"){ 
        obj = "#publish-sb";
        obj_id = "data-sb-id";
        obj_public = "data-sb-public";
    }

    $(modal).find('.loading').addClass("hide");
    $(modal).find('.alert-success').show();
    $(modal).find('.alert-success').show();
    $(modal).find('.alert-success').removeClass('hide');
    if(_public == "0"){
        $(obj+'['+obj_id+'="'+id+'"]').removeClass('fa-unlock-alt').addClass('fa-unlock');
        $(obj+'['+obj_id+'="'+id+'"]').attr(obj_public,"1");
    }
    else{
        $(obj+'['+obj_id+'="'+id+'"]').removeClass('fa-unlock').addClass('fa-unlock-alt');
        $(obj+'['+obj_id+'="'+id+'"]').attr(obj_public,"0");
    }
    setTimeout(function() {
            $(modal).find('.alert-success').addClass('hide');
            $(modal).modal('hide');
            }, 1800);
    }
function success_update_delete_object(type, modal,id){
    if(type=="card") {
        obj = "#publish-card";
        obj_id = "data-card-id";
        obj_public = "data-card-public";
    }
    else if(type=="storyboard"){ 
        obj = "#publish-sb";
        obj_id = "data-sb-id";
        obj_public = "data-sb-public";
    }
    $(modal).find('.loading').addClass("hide");
    $(modal).find('.alert-success').show();
    $(modal).find('.alert-success').show();
    $(modal).find('.alert-success').removeClass('hide');
    //$('#cards_isotope2 > li.element-item[data-id="'+id+'"]').hide('slow');
    elem = $('#cards_isotope2 .element-item[data-id="'+id+'"]');
    $('#cards_isotope2').isotope( 'remove', elem);
    setTimeout(function() {
            $(modal).find('.alert-success').addClass('hide');
            $(modal).modal('hide');
            }, 1800);
    }

/*******************  End Cards/Storyboards  ******************/
/**************************************************************/



$('body').on('click','#modalJoin .ajax_submit', function(){
    var circle = $(this).attr("data-circle");
    var user = $(this).attr("data-user");
    var status = "1";
    var modal = $(this).parents('.modal');
    update_status_user_circle(circle,user,status,modal);
});
$('body').on('click','#modalUnjoin .ajax_submit', function(){
    var circle = $(this).attr("data-circle");
    var user = $(this).attr("data-user");
    var status = "3";
    var modal = $(this).parents('.modal');
    update_status_user_circle(circle,user,status,modal);
});
$('body').on('click','#notif_accept', function(){
    var circle = $(this).attr("data-circle");
    var user = $(this).attr("data-user");
    var status = "2";
    var modal = $(this).parents('.modal');
    update_status_user_circle(circle,user,status,modal);
    });
$('body').on('click','#notif_deny', function(){
    var circle = $(this).attr("data-circle");
    var user = $(this).attr("data-user");
    var status = "3";
    var modal = $(this).parents('.modal');
    update_status_user_circle(circle,user,status,modal);
    });

function update_status_user_circle(circle,user,status,modal){
    var path  = site_url + "circle/join/"+circle+"/"+user+"/"+status;
    var jqxhr = $.ajax(path)
        .done(function(data) {
            if($.trim(data)=="ok")
                success_update_status(modal,circle,user);
            else
                error_update_status(modal);                 
            }
        )
        .fail(function(data) {
                error_update_status(modal);                
        });
    }

function success_update_status(modal,circle,user) {
    $(modal).find('.loading').addClass("hide");
    $(modal).find('.alert-success').show();
    $(modal).find('.alert-success').show();
    $(modal).find('.alert-success').removeClass('hide');
    delete_notification(circle,user);
    setTimeout(function() {
            $(modal).find('.alert-success').addClass('hide');
            $(modal).modal('hide');
            }, 1800);
    }
function delete_notification(circle,user){
    var notif = $('.notification-container [data-circel-id="'+circle+'"][data-user-id="'+user+'"]');
    $(notif).fadeOut('slow', function(){
        $(notif).remove();
        var num_notif = $('#notification-list .notification-container > .notification ').length;
        $('#my-task-list span.badge ').text(num_notif);
        if(num_notif==0)
            $('#my-task-list span.badge ').hide("slow");
    });
    }
function error_update_status(modal) {
    $(modal).find('.loading').addClass("hide");
    $(modal).find('.alert-error').show();
    $(modal).find('.alert-error').removeClass('hide');
    setTimeout(function() {
        $(modal).find('.alert-error').addClass('hide');
        $(modal).modal('hide');
        }, 1800);         
    }
function number_error_update_status(modal) {
    $(modal).find('.loading').addClass("hide");
    $(modal).find('.alert-error').hide();
    $(modal).find('.alert-error-number').show();
    $(modal).find('.alert-error-number').removeClass('hide');
    setTimeout(function() {
        $(modal).find('.alert-error-number').addClass('hide');
        $(modal).modal('hide');
        }, 1800);         
    }

function success_update(modal) {
    $(modal).find('.loading').addClass("hide");
    $(modal).find('.alert-success').show();
    $(modal).find('.alert-success').show();
    $(modal).find('.alert-success').removeClass('hide');
    setTimeout(function() {
            $(modal).find('.alert-success').addClass('hide');
            $(modal).modal('hide');
            }, 1800);
    }

/****************** FORM VALIDATION *************************/
$(".select2").select2();

$('form#submit_profile.edit').validate({
    errorElement: 'span', 
    errorClass: 'error', 
    focusInvalid: false, 
    ignore: "",
    rules: {
        first_name: {
            minlength: 2,
            required: true
        },
        last_name: {
            minlength: 2,
            required: true,
        },
        email: {
            required: true,
            email: true
        },
        password: {
            minlength: 2,
            required: true
        },
        repassword: {
            minlength: 2,
            required: true,
            equalTo: "#password"
        },                
        confirm_password: {
            minlength: 2,
            required: true
        },
    },
    invalidHandler: function (event, validator) {
    },
    errorPlacement: function (error, element) { 
        var icon = $(element).parent('.input-with-icon').children('i');
        var parent = $(element).parent('.input-with-icon');
        icon.removeClass('fa fa-check').addClass('fa fa-exclamation');  
        parent.removeClass('success-control').addClass('error-control');  
    },
    highlight: function (element) {
        var parent = $(element).parent();
        parent.removeClass('success-control').addClass('error-control'); 
    },
    unhighlight: function(element) {
    },
    success: function (label, element) {
        var icon = $(element).parent('.input-with-icon').children('i');
        var parent = $(element).parent('.input-with-icon');
        icon.removeClass("fa fa-exclamation").addClass('fa fa-check');
        parent.removeClass('error-control').addClass('success-control'); 
    },
    submitHandler: function (form) {
        var country = $("#country > input").val();
        $('<input />').attr('type', 'hidden')
            .attr('name', "country")
            .attr('value', country)
            .appendTo(form);
        form.submit();
    }
});
$('form#submit_profile.add').validate({
    errorElement: 'span', 
    errorClass: 'error', 
    focusInvalid: false, 
    ignore: "",
    rules: {
        first_name: {
            minlength: 2,
            required: true
        },
        last_name: {
            minlength: 2,
            required: true,
        },
        email: {
            required: true,
            email: true
        },
    },
    invalidHandler: function (event, validator) {
    },
    errorPlacement: function (error, element) { 
        var icon = $(element).parent('.input-with-icon').children('i');
        var parent = $(element).parent('.input-with-icon');
        icon.removeClass('fa fa-check').addClass('fa fa-exclamation');  
        parent.removeClass('success-control').addClass('error-control');  
    },
    highlight: function (element) {
        var parent = $(element).parent();
        parent.removeClass('success-control').addClass('error-control'); 
    },
    unhighlight: function(element) {
    },
    success: function (label, element) {
        var icon = $(element).parent('.input-with-icon').children('i');
        var parent = $(element).parent('.input-with-icon');
        icon.removeClass("fa fa-exclamation").addClass('fa fa-check');
        parent.removeClass('error-control').addClass('success-control'); 
    },
    submitHandler: function (form) {
        var country = $("#country > input").val();
        $('<input />').attr('type', 'hidden')
            .attr('name', "country")
            .attr('value', country)
            .appendTo(form);
        form.submit();
    }
});
$('form#submit_circle').validate({
        errorElement: 'span', 
        errorClass: 'error', 
        focusInvalid: false, 
        ignore: "",
        rules: {
            name: {
                minlength: 2,
                required: true
                },
            description: {
                minlength: 10,
                required: true
                },
            },
    invalidHandler: function(event, validator) {
    },
        errorPlacement: function (error, element) { 
            var icon = $(element).parent('.input-with-icon').children('i');
            var parent = $(element).parent('.input-with-icon');
            icon.removeClass('fa fa-check').addClass('fa fa-exclamation');  
            parent.removeClass('success-control').addClass('error-control');  
            },
        highlight: function (element) { 
            var parent = $(element).parent();
            parent.removeClass('success-control').addClass('error-control'); 
            },
    unhighlight: function(element) {
    },
        success: function (label, element) {
            var icon = $(element).parent('.input-with-icon').children('i');
            var parent = $(element).parent('.input-with-icon');
            icon.removeClass("fa fa-exclamation").addClass('fa fa-check');
            parent.removeClass('error-control').addClass('success-control'); 
        },
        submitHandler: function (form) {
            var admin = $("input[name='selectbox3']").val();
            $('<input />').attr('type', 'hidden')
                .attr('name', "admin")
                .attr('value', admin)
                .appendTo(form);
            form.submit();
        }
    });
/******************* ADMIN ********************************************/
$('body').on('click','#sync_companies,#sync_kpis,#delete_companies,#delete_kpis', function(){
    var obj = $(this).attr("data-object");
    var action = $(this).attr("data-action");
    var operation = $(this).attr("data-operation");
    $("#modalSynch").find('.obj_sync').text(obj);
    $("#modalSynch").find('.obj_action').text(operation);
    $("#modalSynch").find('button.ajax_submit').attr('action',action);
    $("#modalSynch").modal();
});
$('body').on('click','.admin_modals button.ajax_submit', function(){
    var action= $(this).attr('action');
    var modal = $(this).parents('.modal');
    $(modal).find('.loading ').show();
    $(modal).find('.loading ').removeClass('hide');
    
    $.ajax( {
        url : action,
        type:"POST",
        success : function(data) {
            if($.trim(data)=="ok")
                success_update(modal);
            else
                error_update_status(modal)
            },
        error :function(data) {
            error_update_status(modal)
            },
        });

    });

$('body').on('click','#delete-circle', function(){
    var circle= $(this).attr('data-circle-id');
    var modal = $($(this).attr('data-modal-id'));
    $(modal).find('.ajax_submit').attr('data-circle', circle);
    
    
});

$('body').on('click','#removeCircleModal .ajax_submit', function(){
    var circle = $(this).attr("data-circle");
    var modal = $(this).parents('.modal');
    
    delete_circle(circle,modal);
});
function delete_circle(circle, modal){
    //*
    var path  = site_url + "circle/delete/"+circle;
    $.ajax( {
        url : path,
        type:"GET",
        success : function(data) {
            if($.trim(data)=="ok")
                success_update_delete_circle(modal,circle);
            else
                error_update_status(modal)
        },
        error :function(data) {
            error_update_status(modal)
        }
    });
//*/
}

function success_update_delete_circle(modal,circle){
    $(modal).find('.loading').addClass("hide");
    $(modal).find('.alert-success').show();
    $(modal).find('.alert-success').show();
    $(modal).find('.alert-success').removeClass('hide');
    $('.circle_list[data-circle-id="'+circle+'"]').hide('slow');
    
    setTimeout(function() {
        $(modal).find('.alert-success').addClass('hide');
        $(modal).modal('hide');
    }, 1800);
}

$('body').on('click','#delete-user', function(){
    var user= $(this).attr('data-user-id');
    var modal = $($(this).attr('data-modal-id'));
    $(modal).find('.ajax_submit').attr('data-user', user);
});

$('body').on('click','#removeProfileModal .ajax_submit', function(){
    var profile = $(this).attr("data-user");
    var modal = $(this).parents('.modal');
    
    delete_user(profile,modal);
});
function delete_user(user, modal){
    //*
    var path  = site_url + "profile/delete/"+user;
    $.ajax( {
        url : path,
        type:"GET",
        success : function(data) {
            if($.trim(data)=="ok")
                success_update_delete_user(modal,user);
            else
                error_update_status(modal)
        },
        error :function(data) {
            error_update_status(modal)
        }
    });
//*/
}

function success_update_delete_user(modal,user){
    $(modal).find('.loading').addClass("hide");
    $(modal).find('.alert-success').show();
    $(modal).find('.alert-success').show();
    $(modal).find('.alert-success').removeClass('hide');
    $('.user_list[data-user-id="'+user+'"]').hide('slow');
    
    setTimeout(function() {
        $(modal).find('.alert-success').addClass('hide');
        $(modal).modal('hide');
    }, 1800);
}


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