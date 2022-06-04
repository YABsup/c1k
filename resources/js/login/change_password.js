$(document).ready(function () {

    var changePassword = $('#change-password-id');


    changePassword.submit(function (event) {

        var newPassWord = $('#inputNewPassword').val();
        var newConfirmPassword = $('#inputConfirmNewPassword').val();
        if (newPassWord === newConfirmPassword) {
            $('.change_password_newpass').css('display', 'none');
            $('.change_password_newpassconfirm').css('display', 'none');
            $.ajax({
                type: 'POST',
                url: '/account/change_password',
                dataType: 'json',
                data: changePassword.serialize(),
                success: function (data, response) {
                    console.log('data', data);
                    console.log('response', response);
                    if (response == 'success') {
                        $('.change_update_newpassword').css('display', 'block');
                    } else {
                        $('.change_update_newpassword').css('display', 'none');
                    }
                    changePassword[0].reset();
                    // window.location = "/account";
                },
                error: function (xhr, status) {
                    console.log('xhr ', xhr);
                    var error = xhr.responseJSON.message;
                    if (status == 'error') {
                        $('.change_password_oldpass').html(error);
                    }
                }
            });
            return false;
        } else if (newPassWord.length < 8) {
            event.preventDefault();
            $('.change_password_newpass').css('display', 'block');
        } else {
            event.preventDefault();
            $('.change_password_newpassconfirm').css('display', 'block');
        }
    });


});
