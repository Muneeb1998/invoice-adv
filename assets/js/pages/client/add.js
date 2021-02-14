$(document).on('submit', '#client', function () {
    var aData = $(this).serializeArray();
    $('.preloader ').removeClass('d-none')
    $.ajax({
        url: apiUrl + "pr/addclient",
        type: "POST",
        data: aData,
        dataType: "json",
        success: function (r) {
            if (r.success) {
                $('#client ')[0].reset()
                $('.preloader ').addClass('d-none')
                response('Client has been added', 'success');
                setTimeout(function () {
                    location.href = siteUrl + 'client'
                }, 3000);
            } else {
                $('.preloader ').addClass('d-none')
                showValidationError(r)
            }
        }
    })
    return false;
})
$(document).ready(function () {
    setTimeout(function () {
        $("[name='country']").val('US'); // Select the option with a value of 'US'
        $("[name='country']").trigger('change'); // Notify any JS components that the value changed
    }, 500)
})