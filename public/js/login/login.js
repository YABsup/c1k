$(document).ready(function(){

    var login = $('#login_id');

    login.submit(function(event) {
        $.ajax({
            type: 'POST',
            url: '/login',
            data: login.serialize(),
            success: function(data, response){
                 if(data.url){
                    document.location = data.url;
                 }
            },
            error: function(xhr, status){
                var error = xhr.responseJSON.message
                if(status == 'error'){
                    $('.msg-error-login').html(error);
                }
            }
        });
        return false;
    });


    $('.form_control-login').focus(function() {
        $(this).css('border-bottom', '2px solid #306088')
        $(this).prev('label').css({
            'top': '9px', 
            'color': '#306088'
        });
    });

    $('.form_control-login').focusout(function() {
        var val = $(this).val();
        if(val.length === 0){
            $(this).css('border-bottom', '1px solid #b8b8b8')
            $(this).prev('label').css({
                'top': '28px', 
                'color': '#b8b8b8'
            });
        } else {
            $(this).css('border-bottom', '2px solid #306088')
            $(this).prev('label').css({
                    'top': '9px', 
                    'color': '#306088'
                }); 
        }
    });
});
