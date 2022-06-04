$(function() {
    var accountWithdraw = $('#account_withdraw_id');

    accountWithdraw.submit(function (event) {

        // $.ajax({
        //     type: 'POST',
        //     data: accountWithdraw.serialize(),
        //     url: '/withdraw'+str(data.slug),
        //     success: function(data, response){
        //         console.log('data', data);
        //         console.log('response', response);
        //     },
        //     error: function(xhr, status){
        //         console.log('xhr', xhr);
        //         console.log('status', status);
        //         if(status == 'error'){
        //             event.preventDefault();
        //             console.log('error')
        //
        //         }
        //     }
        // });
        // return false;

        var inputEnterSum = +$('#inputAmount').val();
        var maxSum = +$("input[type='text'][name='max_amount']").val();
        var minSum = +$("input[type='text'][name='min_amount']").val();
        if(inputEnterSum < minSum || inputEnterSum > maxSum){
          event.preventDefault();
          $('.msg-min_max_value').css('display', 'block');
          $('.account_min_value').html(minSum)
          $('.account_max_value').html(maxSum)
          console.log('false')
          console.log($("input[type='text'][name='amount']").val());
        } else {

            console.log('good');
            console.log($("input[type='text'][name='amount']").val());
        }
    });
});
