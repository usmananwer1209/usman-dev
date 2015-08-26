$(function(){
    // Initialize the jQuery File Upload plugin
    $('#upload').fileupload({

        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),

        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {
           
            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit().success(function (result, textStatus, jqXHR) {
                                console.info(result);
                                console.info(textStatus);
                                console.info(jqXHR);
                            });
        },

        fail:function(e, data){
            // Something has gone wrong!
            data.context.addClass('error');
        }

    });

    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

});