$(document).on('submit', '#admin', function () {
    var aData = $(this).serializeArray();
    $('.preloader ').removeClass('d-none')
    $.ajax({
        url: apiUrl + "pr/addAdmin",
        type: "POST",
        data: aData,
        dataType: "json",
        success: function (r) {
            if (r.success) { 
                $('#admin ')[0].reset()
                $('.preloader ').addClass('d-none')
                response('Admin has been added', 'success');
            } else {
                $('.preloader ').addClass('d-none')
                showValidationError(r)
            }
        }
    })
    return false;
})