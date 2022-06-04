$(function(){

    function checkValidate() {

        function validateName(){
            var name = $('#inputFirstName').val();
            var reg =  /^[A-Za-zА-Яа-я]{1}[a-zа-я]{1,14}( [А-Я]{0,1})?([а-я]{0,5})?(  )?$/ ;
            
            if (reg.test(name)){
                // $('#vl').hide();
                return false;
            } else {
                // $('#vl').show();
                return true;
            }
        }

        $('#inputFirstName').keyup(validateName);

    

        // function validatePhone(){
        //     var phone = $('#inputPhoneName').val();
        //     var reg =  /(^[0-9]{5}([-0-9]{0,7})?([-]{0,1})?$)|(^[0-9]{1,4}$)/ ;
            
        //     if (reg.test(phone)){
        //         // $('#vn').hide();
        //         return false;
        //     } else {
        //         // $('#vn').show();
        //         return true;
        //     }
        // }

        // $('#inputPhoneName').keyup(validatePhone);
        
        function validateEmail(){
            var email = $('#inputEmail').val();
            var reg =  /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/ ;
            
            if (reg.test(email)){
                // $('#v').hide();
                return false;
            } else {
                // $('#v').show();
                return true;
            }
        }

        $('#inputEmail').keyup(validateEmail);


    }
    
    checkValidate();
    
    // $('#inputPhoneName').mask("+38 (999) 999 99 99", {placeholder: ""});


    function FillingSomeFields (){
        var inputPhone = $('#inputPhoneName').val();
        var inputViber = $('#inputViberName').val();
        var inputTelegram = $('#inputTelegramName').val();
        var inputWhatApp = $('#inputWhatsAppName').val();
        var msgSomeField = $('.msg-filling-some-field');
	
        if (inputViber.trim() || inputTelegram.trim() || inputWhatApp.trim() !== '') {
            return true;
        } else {
            msgSomeField.css('display', 'block');
            event.preventDefault();
            return false;
        }

    }

//    $('#btn_change').on('click', function (e){
//        FillingSomeFields ();
//    });
    

});

$(function() {
    $('#btn_change')[0].disabled = true;
    $('#xhange_checkbox').on('click', function(){
        if($('#xhange_checkbox').is(':checked')){
            $('#btn_change')[0].disabled = false;
        }else {
            $('#btn_change')[0].disabled = true;
        }
    });
});