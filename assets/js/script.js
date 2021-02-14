function removeValidationError()
{
  $('form').addClass('was-validated');
  $('.errorMsg').remove();
  $('.error').removeClass('error');
}
String.prototype.dotToBracket = function()
{
  return this.replace(/\.(.+?)(?=\.|$)/g, (m, s) => `[${s}]`)
}
String.prototype.alphaDash = function()
{
  return this.replace(/[^A-Za-z0-9-_\s]/g, '').replace(/[\s]/g, '-');
}
function showValidationError(o, admin = false) {
    removeValidationError();
    if (o.oValidationErrors) {
        $.each(o.oValidationErrors, function (k, v) {
            let k2 = k;
            k = (k).dotToBracket();
            let oDef = {
                el: $(':input[name="' + k + '"]'),
                eTrgt: $(':input[name="' + k + '"]'),
                elTrgt: $(':input[name="' + k + '"]').parents().parents().eq(0),
            };
            console.log(oDef['elTrgt']);
            if (typeof (oValidationAlt) != 'undefined' && oValidationAlt[k2]) {
                oDef = $.extend(oDef, oValidationAlt[k2]);
            } else {
                if (oDef['el'].attr('e-trgt')) {
                    oDef['eTrgt'] = oDef['el'].attr('e-trgt');
                }
                if (oDef['el'].attr('e-ltrgt')) {
                    oDef['elTrgt'] = oDef['el'].attr('e-ltrgt');
                }
                if (oDef['el'].attr('e-laftrtrgt')) {
                    oDef['elAftrTrgt'] = oDef['el'].attr('e-laftrtrgt');
                }
            }
            if (oDef['eTrgt'] != '0') {
                $(oDef['eTrgt']).addClass('error');
            }
            if (typeof (oDef['elAftrTrgt']) != 'undefined' && oDef['elAftrTrgt'] != '0') {
                $(oDef['elAftrTrgt']).after('<div class="errorMsg" id="ve_' + k.alphaDash() + '">' + v + '</div>');
            } else if (oDef['elTrgt'] != '0') {
                $(oDef['elTrgt']).append('<div class="errorMsg" id="ve_' + k.alphaDash() + '">' + v + '</div>');
            }
        });
        if ($('.error').length > 0) {
            $('.was-validated').removeClass('was-validated');
            let offsetTopError = $('.error').eq(0).offset().top;
            let offsetTopErrorMsg = $('.errorMsg').eq(0).offset().top;
            var offsetTop = offsetTopErrorMsg;
            if (offsetTopError < offsetTopErrorMsg) {
                offsetTop = offsetTopError;
            }
            $('body,html').animate({ scrollTop: offsetTop });
        }
    } else {
        response(o.msg, 'error');
    }
}
let response = (message, status) => {
    Swal.fire({
        icon: status,
        text: message,
    })
}
$(document).ready(function(){
     $.ajax({
        url: apiUrl + "pr/paidMail",
        type: "PUT",
    })
})