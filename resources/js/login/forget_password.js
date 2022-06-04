$(document).ready(function(){

    var forgetPasword = $('#forget_pass_id');

    forgetPasword.submit(function(event) {
        $.ajax({
            type: 'POST',
            url: '/forget_password',
            data: forgetPasword.serialize(),
            success: function(data, response){
                console.log('data', data);
                console.log('response', response);
                if(response == 'success'){
                    $('.msg_forget_password').css('display', 'block');
                    $('.msg_forget_password_not-exist').css('display', 'none');
                }

            },
            error: function(xhr, status){
                console.log('xhr', xhr);
                console.log('status', status);
                if(status == 'error'){
                    $('.msg_forget_password_not-exist').css('display', 'block');
                    $('.msg_forget_password').css('display', 'none');
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
