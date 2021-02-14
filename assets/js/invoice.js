$(document).ready(function () {
    if(clientData == 1){
        $.ajax({
            type: 'PUT',
            url: apiUrl + 'pu/invoiceView',
            dataType: 'json',
            data: { id: id },
            success: function (r) {
                if (r.success) {
    
                } else {
                    console.error(r);
                }
            }
        });
    }
});
$(document).on('click', '#paybtn', function () {
    let self = $(this)
    $.ajax({
        url: apiUrl + 'pu/payInvoice',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (r) {
            if (r.success) {
                $.each(r.data, function (k, v) {
                    $(`[name='${k}']`).val(v)
                    console.log(v)
                })
                $('#paypal').submit();
                // $('#btn-subscribe').addClass(' d-none');
                // var data = r.data;
                // console.log(data)
                // $('.right_col').append(data);
                // setTimeout(function () {
                // },1000)
                // response('Package has been subscribe', 'success');
                // $('#btn-subscribe').addClass(' d-none');
            } else {
                response(r.msg, 'danger');
                $('.subscribe').attr('disabled', false)
                $('#btn-subscribe').addClass(' d-none');
            }
        }
    })
    return false;
})