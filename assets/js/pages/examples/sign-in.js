$(document).on('submit', '#login', function () {
    var aData = $(this).serializeArray();
    $.ajax({
        url: apiUrl + "/pu/login",
        type: "POST",
        data: aData,
        dataType: "json",
        success: function (r) {
            if (r.success) {
                window.location.replace(siteUrl);
            } else {
                showValidationError(r)
            }
        }
    })
    return false;
})