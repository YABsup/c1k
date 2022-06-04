$(document).ready(function () {
    var form = $('#formId');

    form.submit(function (event) {
        validationFieldForm(event);

        $.ajax({
            type: 'POST',
            url: '/signup',
            data: form.serialize(),
            success: function(data, response){
                if(data.url){
                    document.location = data.url;
                 }             
            },
            error: function(xhr, status){
                var errorEmail = xhr.responseJSON.message.email; 
                var errorPassword = xhr.responseJSON.message.password;
                if (typeof errorEmail !== "undefined" ) {
                    $('.input-exist-email_phone').html(errorEmail.join(' '))  
                }else{
                    $('.input-exist-email_phone').empty()
                }
                
                if (typeof errorPassword !== "undefined" ){
                    var newErrorPassword = errorPassword.join('<br>')
                    $('.input-invalid-request-password').html(newErrorPassword);
                } else{
                    $('.input-invalid-request-password').empty()
                } 
            }
        });
        return false;
    });

    function validationFieldForm(event) {

        function validationEmail(event) {
            var email = $('#input_signup_email').val();
            var regEmail = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$/;

            if (!regEmail.test(email)) {
                event.preventDefault();
                $('.input-invalid-email').css('display', 'block');
                $('#input_signup_email').addClass('input-border-invalid');
                return true;
            } else {
                return false;
            }
        }

        validationEmail(event);

        function validationPassword(event) {
            var password = $('#input_signup_pass').val();
            var passwordConfirm = $('#input_signup_passcon').val();


            if (password.length < 8) {
                event.preventDefault();
                $('#input_signup_pass').addClass('input-border-invalid');
                $('.input-invalid-password').css('display', 'block');
            }
            if (password !== passwordConfirm) {
                event.preventDefault();
                $('.input-invalid-password_confirm').css('display', 'block');
                $('#input_signup_pass').addClass('input-border-invalid');
                $('#input_signup_passcon').addClass('input-border-invalid');
            }
        }

        validationPassword(event);

    }

    $('.form_control-signup').focus(function () {
        $(this).css('border-bottom', '2px solid #306088')
        $(this).prev('label').css({
            'top': '9px', 
            'color': '#306088'
        });
    });

    $('.form_control-signup').focusout(function () {
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
