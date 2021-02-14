$(document).on("submit", "form#info", function () {
    $(".preloader").removeClass(" d-none");
    var oFormData = new FormData(this);
    if(formId){
        oFormData.append('_method', 'PUT');
    }
    $.ajax({
        url: apiUrl + "pr/settings",
        type: "POST",
        data: oFormData,
        processData: false,
        contentType: false,
        cache: false,
        dataType: "json",
        success: function (r) {
            if (r.success) {
                $(".preloader").addClass("d-none")
                response("Settings has been saved", "success");
               // setTimeout(function () { location.reload(); }, 1000);
            } else {
                $(".preloader").addClass("d-none")
                showValidationError(r)
            }
        }
    })
    return false;
})
$(document).ready(function () {
    if (formId) {
        $.each(formData, function (k, v) {
            if (k == 'country') {
                $(".country").val(v).trigger('change');
            } else {
                $('[name=' + k + ']').val(v)
            }
        })
    }
})