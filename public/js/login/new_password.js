$(document).ready(function () {
    var newPass = $('#forget_pass_id');

    newPass.submit(function (event) {
        validationFieldForm(event);
    });

    function validationFieldForm(event) {

        function validationPassword(event) {
            var password = $('#input_signup_pass').val();
            var passwordConfirm = $('#input_signup_passcon').val();
            console.log('pass', password);
            console.log('passwordConfirm', passwordConfirm)

            if (password.length < 8) {
                event.preventDefault();
                console.log('pass is shot');
                $('#input_signup_pass').addClass('input-border-invalid');
                $('.msg_new_password').css('display', 'block');
            } else {
                $('.msg_new_password').css('display', 'none');
            }
            if (password !== passwordConfirm) {
                console.log('pass !== confirm');
                event.preventDefault();
                $('.msg_new_password-confirm').css('display', 'block');
                $('#input_signup_pass').addClass('input-border-invalid');
                $('#input_signup_passcon').addClass('input-border-invalid');
            } else {
                $('.msg_new_password-confirm').css('display', 'none');
            }
        }

        validationPassword(event);

    }
    // $('#input_signup_pass').keyup(validationFieldForm)

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
