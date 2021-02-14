var actionStatus = '';
var discountAmount = '';
$(document).on('click', '#addItem', function () {
    lineClone()
})
function lineClone(aDesciption = [], aAmount = [], aQuantity = []) {
    if (aDesciption.length == 0) {
        let oClone = $('#clone').clone().removeAttr('id')
        oClone.find('.delete').removeClass('d-none');
        oClone.find('.note-editor').remove()
        oClone.find('.desciption').removeAttr('style')
        setTimeout(function () {
            editor('.desciption')
        }, 50)
        oClone.find('[name="item_desciption[]"],[name="amount[]"]').val('')
        $('.items').append(oClone)
    } else {
        ;
        $.each(aDesciption, function (k, v) {
            if (k != 0) {
                let oClone = $('#clone').clone().removeAttr('id')
                oClone.find('[name="amount[]"]').val('')
                oClone.find('.delete').removeClass('d-none');
                oClone.find('.note-editor').remove()
                oClone.find('.desciption').removeAttr('style')
                oClone.find('[name="item_desciption[]"]').val(v)
                oClone.find('[name="amount[]"]').val(aAmount[k])
                setTimeout(function () {
                    editor('.desciption')
                }, 50)
                $('.items').append(oClone)
            } else {
                $('.desciption').val(aDesciption[0])
                $('[name="amount[]"]').val(aAmount[0])
            }
        })
    }
}
$(document).on('click', '.delete:visible', function () {
    $(this).parent().remove();
})
$(document).on('change', '#invoice_due', function () {
    let self = $(this)
    if (self.val() == 6) {
        $('#invoice_due_in').removeClass('d-none')
    } else {
        $('#invoice_due_in').addClass('d-none')
    }
})
$(document).on('change', '.currency', function () {
    let self = $(this)
    $('.currencyUnit').text(self.val())
})
$("#client_name").select2({
    ajax: {
        "url": apiUrl + "pr/clientList",
        dataType: "json",
        processResults: function (data, params) {
            params.page = params.page || 1;
            return {
                results: $.map(data.items, function (obj) {
                    return {
                        id: obj.id,
                        text: obj.name,
                        email: obj.email,
                        mobile: obj.mobile,
                        is_archive: obj.is_archive,

                    };
                })
            };
        },
    },
    cache: true,
    templateSelection: function (data, container) {
        // Add custom attributes to the <option> tag for the selected option
        $(data.element).attr('data-email', data.email);
        $(data.element).attr('data-mobile', data.mobile);
        $(data.element).attr('data-is_archive', data.is_archive);
        return data.text;
    }
});
var test = ''
$(document).ready(function () {
    $('#issue_date').datetimepicker({
        format: 'DD/MM/YYYY',
    });
    $("#issue_date").val(getDate());
    $('#invoice_due_in').datetimepicker({
        format: 'DD/MM/YYYY',
    });
    $("#invoice_due_in").val(getDate());
    if (form) {
        let aItemDesciption = JSON.parse(data['item_desciption'])
        let aQuantity = JSON.parse(data['quantity'])
        let aAmount = JSON.parse(data['amount'])
        des = JSON.parse(data['item_desciption'])
        lineClone(aItemDesciption, aAmount)
        if (data['status'] == 3) {
            setTimeout(function () {
                $('.desciption').summernote('disable');
                $('.footer').summernote('disable');
                $('input,select,textarea,button').attr('disabled', true);
                $('.add-discount').addClass('d-none')
            }, 500)
        }
        // console.log(data['clientEmail'])
        // console.log(data['clientMobile'])
        $('#to-data').html(
            '<p id="to-name">' + data['clientName'] + '</p>' +
            '<p id="to">' + data['clientEmail'] + '</p>' +
            '<p>' + data['clientMobile'] + '</p>'
        );
        $('#subTotal').html(data['sub_total'])
        $('[name="quantity[]"]').val(aQuantity[0])
        $('#total,#balance').html(data['total'])
        // $('#discountAmount').summernote ('code', data['footer']);
        if (data['discount'] == 1) {
            setTimeout(function () {
                $('.add-discount').trigger('click')
                $('.discountAmount').html(data['discount_amount'])
            }, 1000)
        }
        // $('#subTotal').html(data['sub_total'])
        $.each(data, function (k, v) {
            // console.log(k == 'issue_date')
            if (k == 'client_name') {
                test = v
                // setTimeout(function () {
                //     $('[name="' + k + '"]').val(v).change();
                // }, 2000)
            }
            // else if(k == 'issue_date'){
            //     console.log($('[name="' + k + '"]'))
            //     setTimeout(function(){
            //         $("#issue_date").val('');
            //     },1000)
            // } 
            else {
                $('[name="' + k + '"]').val(v)
            }
        })
    }
    // $('#client_name').select2({
    //     // ...

    //   });
    // editor('.desciption')
    editor('textarea:not(#msg)')
    setTimeout(function () {
        $("[name='currency']").val('USD'); // Select the option with a value of 'US'
        $("[name='currency']").trigger('change'); // Notify any JS components that the value changed
    }, 500)

    // $('#issue_date').datetimepicker({ format: 'DD/MM/YYYY' });
})
$(document).on('keyup', '[name="amount[]"]', function () {
    let self = $(this);
    let total = 0;
    let selfValue = self.val(); //map(function () { return $(this).val(); }).get();
    $('[name="amount[]"]:visible').each(function (k, v) {
        total = total + parseFloat($(v).val());
    });
    if (!isNaN(total)) {
        let preValue = parseFloat($('#subTotal').text());
        // $('#subTotal,#total,#balance').text(parseFloat(total + preValue).toFixed(2))
        $('#subTotal,#total,#balance').text(parseFloat(total).toFixed(2))
    } else {
        $('#subTotal,#total,#balance').text('0.00')
    }
})
let getDate = () => {
    var now = new Date();
    var date = ("0" + now.getDate()).slice(-2) + '/' + ("0" + (now.getMonth() + 1)).slice(-2) + "/" + now.getFullYear();
    return date
}
$(document).on('click', '.add-discount', function () {
    let oClone = $('#discount').clone().removeAttr('id').removeClass('d-none')
    oClone.find('#discountAmount').addClass('discountAmount')
    $('.discountClone').append(oClone)
    $(this).parent().parent().addClass('d-none')
})
$(document).on('click', '.del-discount', function () {
    let self = $(this);
    $('#total,#balance').text($('#subTotal').text())
    self.parent().parent().remove();
    $('.add-discount').parent().parent().removeClass('d-none')
})
$(document).on('keyup', '#discountRate', function () {
    let total = $('#subTotal').text();
    let self = $(this);
    let discount = self.val();
    if (discount != '') {
        if (discount.includes("%")) {
            let perAmount = (discount.replace(/\%/g, "")) / 100;
            let totalValue = total - (total * perAmount)
            $('#discountAmount').text('-' + parseFloat(total * perAmount).toFixed(2))
            // let amount = total - value;
            if (!isNaN(totalValue)) {
                $('#total,#balance').text(parseFloat(totalValue).toFixed(2))
            }
        } else {
            self.parent().parent().find('#discountAmount').text('-' + parseFloat(discount).toFixed(2))
            discountAmount = '-' + parseFloat(discount).toFixed(2);
            let amount = total - discount;
            if (!isNaN(amount)) {
                $('#total,#balance').text(parseFloat(amount).toFixed(2))
            }
        }
    } else {
        self.parent().parent().find('#discountAmount').text('-0.00')
        $('#quantity').trigger('keyup')
    }
})
$(document).on('change', ' #client_name', function () {
    let name = $("#client_name").find(':selected').text()
    let email = $("#client_name").find(':selected').data('email');
    let mobile = $("#client_name").find(':selected').data('mobile');
    let archive = $("#client_name").find(':selected').data('is_archive');
    if (archive == 1) {
        Swal.fire('Client is in archieve!', '', 'info')
    }
    $('#to-data').html(
        '<p id="to-name">' + name + '</p>' +
        '<p id="to">' + email + '</p>' +
        '<p>' + mobile + '</p>'
    );
})
$(document).on('submit', '#invoice', function () {
    // let des = $(".desciption")
    //         .map(function () { return $(this).summernote('code'); }).get();
    //         console.log(des)
    var oFormData = $(this).serializeArray();
    oFormData.push({ name: 'to', value: $('#to').text() });
    oFormData.push({ name: 'from', value: $('#from').text() });
    oFormData.push({ name: 'sub_total', value: $('#subTotal').text() });
    oFormData.push({ name: 'discount_amount', value: discountAmount });
    oFormData.push({ name: 'total', value: $('#balance').text() });
    oFormData.push({ name: 'client', value: $("#to-name").text() });
    // oFormData.push({ name: 'item_desciption', value: des });
    $('.preloader ').removeClass('d-none')
    let url = '';
    if (iformId !== '') {
        oFormData.push({ name: '_method', value: 'PUT' });
        oFormData.push({ name: 'id', value: iformId });
        url = 'updateInvoice'
    } else {
        url = 'addInvoice'
    }
    $.ajax({
        url: apiUrl + "pr/" + url,
        type: "POST",
        data: oFormData,
        dataType: "json",
        success: function (r) {
            if (r.success) {
                if (iformId == '') {
                    iformId = r.data;
                }
                $('.preloader ').addClass('d-none')
                if (actionStatus == 0) {
                    response('Invoice has been saved in draft', 'success');
                    setTimeout(function () {
                        location.href = siteUrl + 'invoice'
                    }, 3000);
                } else {
                    mailModal()
                }
            } else {
                $('.preloader ').addClass('d-none')
                showValidationError(r)
            }
        }
    })
    return false;
})
function titleCase(str) {
    var splitStr = str.toLowerCase().split(' ');
    for (var i = 0; i < splitStr.length; i++) {
        // You do not need to check if i is larger than splitStr length, as your for does that for you
        // Assign it back to the array
        splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
    }
    // Directly return the joined string
    return splitStr.join(' ');
}
function editor(selector, placeholder = '') {
    $(selector).summernote({
        toolbar: [

        ]
    })
}
function mailModal() {
    $('[name="mailto"]').val($('#to').text())
    $('[name="mailsubject"]').val('Invoice ' + $('[name="invoice_no"]').val() + ' from')
    let msg = `Hi ${$('#to-name').text()},<br><br>
    A new invoice has been generated for you by ${companyName}. Here a quick summary:<br><br>
    Invoice Details: ${$('[name="invoice_no"]').val()}<br><br>
    Total Invoice Amount: ${$('#balance').text() + ' ' + $('#currency').val()}<br><br>
    Due Date: {Due Date}`;
    $('#msg').val(msg);
    editor('#msg')
    setTimeout(function () {
        editor('#mail')
        $('#sentModal').modal('show')
    }, 1000)
}
function setDateFormat(o) {
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];
    var gValSep = o.val.match(/\D/);
    var aGfo = o.gFormat.split(gValSep);
    var aVal = o.val.split(gValSep);
    var ogDate = {};
    ogDate['dd'] = aVal[aGfo.indexOf('dd')];
    ogDate['mm'] = aVal[aGfo.indexOf('mm')];
    ogDate['yyyy'] = aVal[aGfo.indexOf('yyyy')];

    var rValSep = o.rFormat.match(/\W/);
    var aRF = o.rFormat.split(rValSep);
    return ogDate[aRF[0]] + rValSep + ogDate[aRF[1]] + rValSep + ogDate[aRF[2]]
}
$(document).on('click', '#sendInvoiveData', function () {
    if (iformId == '') {
        response('Invoice can\'t send invoice', 'info');
    } else {
        let oDtata = {
            'bcc': $('[name="mailbcc"]').val(),
            'msg': $('#msg').summernote('code'),
            'subject': $('[name="mailsubject"]').val(),
            'id': iformId
        }
        $('.preloader ').removeClass('d-none')
        $.ajax({
            url: apiUrl + "pr/sendInvoice",
            type: "PUT",
            data: oDtata,
            dataType: "json",
            success: function (r) {
                if (r.success) {
                    response('Invoice has been saved and sent', 'success');
                    setTimeout(function () {
                        location.href = siteUrl + 'invoice'
                    }, 3000);
                } else {
                    $('.preloader ').addClass('d-none')
                    showValidationError(r)
                }
            }
        })
    }
})
$(document).on('click', '.action', function () {
    actionStatus = $(this).attr('data-status')
})
//   console.log(setDateFormat({
//       val:'20-01-2021',
//       gFormat:'dd-mm-yyyy',
//     rFormat:'mm dd yyyy '
//   }))