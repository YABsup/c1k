$(document).ready(function () {
    var profileSave = $('#profile-save-id');

    profileSave.submit(function (event) {
        $.ajax({
            type: 'POST',
            url: '/account/profile',
            dataType: 'json',
            data: profileSave.serialize(),
            success: function (data, response) {
                console.log(data);
                window.location = "/account/profile";
            },
            error: function (xhr, status) {
                console.log('xhr ', xhr);
                console.log('error ', status)
                var error = xhr.responseJSON.message;
                $('.account_profile_errors').empty();

                for (var key in error){
                    var res = error[key];
                    $('.account_profile_errors').append(
                        '<p>'+res +'</p>'
                    )
                    console.log('error ', status)
                }


            }
        });
        return false;
    });
});