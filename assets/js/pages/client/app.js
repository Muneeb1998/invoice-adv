var table = "";
$(document).ready(function () {
    $('form .btn-primary').text('Save Client')
    table = $("#admin").DataTable({
        // "processing": true,
        // "serverSide": true,
        "ajax": {
            "url": apiUrl + "pr/client",
            complete: function (settings, json) {
                table.destroy();
                table = $("#admin").DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            text: '<i style="cursor: pointer;font-size: 16px;margin-right: 4px;" class="fa fa-trash danger"></i> Delete',
                            attr: { class: 'danger dt-button', "data-action": "multiple" },
                        },
                        {
                            extend: 'excel',
                            text: 'Save as Excel',
                            exportOptions: {
                                columns: ':not(:last-child)',
                            },
                            // "action": newexportaction
                        },
                        {
                            extend: 'pdf',
                            text: 'Save as pdf',
                            exportOptions: {
                                columns: ':not(:last-child)',
                            },
                            // "action": newexportaction
                        },
                    ],
                    //     'csv', 'pdf',

                    // ],

                    "language": {
                        "paginate": {
                            "next": "<i class=\'fa fa-chevron-right\'></i>",
                            "previous": "<i class=\'fa fa-chevron-left\'></i>"
                        }
                    },
                    "columnDefs": [
                        {
                            "targets": 0,
                            "searchable": false,
                            "className": "dt-body-center",
                            "render": function (data, type, full, meta) {
                                // dataSet[meta.row].id = meta.row + 1; // adds id to dataset
                                let firstRow = '<input type="checkbox" name="delete[]" value="' + data + '" id="basic_checkbox_' + data + '" class="filled-in"  /><label class="m-0" for="basic_checkbox_' + data + '"></label>'
                                // return meta.row + 1; // adds id to serial no
                                return firstRow;
                            }
                        },
                        {
                            "targets": -1,
                            "searchable": false,
                            "orderable": false,
                            "className": "dt-body-center",
                            "render": function (data, type, row, meta) {
                                let lastRow = '';
                                // lastRow += `<i data-id='${data}' class="material-icons view">remove_red_eye</i>`;
                                lastRow += `<i data-id='${data}' class="material-icons edit">mode_edit</i>`;
                                lastRow += `<i data-id='${data}' data-action="individual" class="material-icons danger">delete</i>`;
                                // if(is_edit){
                                //     lastRow += `<a href="${baseUrl + '/staffedit/index/' + data}"><i class='fas fa-pencil-alt table-icon' aria-hidden='true'></i><a>`
                                // }
                                // if(is_delete){
                                //     lastRow +=  `<i  data-id=\'${data}\' class=\'fas fa-trash-alt table-icon danger\' aria-hidden=\'true\'></i>`
                                // }
                                return lastRow
                            }
                        }
                    ]
                });
            },
        },
    });

    // jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
    //     console.log(this.context);
    //     console.log(options);
    //     if ( this.context.length ) {
    //         var jsonResult = $.ajax({
    //             url: apiUrl + "pr/getClientData",
    //             success: function (result) {
    //                 //Do nothing
    //             },
    //             async: false
    //         });

    //     //     return {body: jsonResult.responseJSON.data, header: $("#myTable thead tr th").map(function() { return this.innerHTML; }).get()};
    //      }
    // } );
    /* For Export Buttons available inside jquery-datatable "server side processing" - Start
    - due to "server side processing" jquery datatble doesn't support all data to be exported
    - below function makes the datatable to export all records when "server side processing" is on */

    function newexportaction(e, dt, button, config) {
        // console.log(e);
        // console.log(settings);
        // var self = this;
        // var oldStart = dt.settings()[0]._iDisplayStart;
        // dt.one('preXhr', function (e, s, data) {
        //     // Just this once, load all data from the server...
        //     data.start = 0;
        //     data.length = 2147483647;
        //     dt.one('preDraw', function (e, settings) {
        //         // Call the original action function
        //         if (button[0].className.indexOf('buttons-copy') >= 0) {
        //             $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
        //         } else if (button[0].className.indexOf('buttons-excel') >= 0) {
        //             $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
        //                 $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
        //                 $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
        //         } else if (button[0].className.indexOf('buttons-csv') >= 0) {
        //             $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
        //                 $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
        //                 $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
        //         } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
        //             $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
        //                 $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
        //                 $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
        //         } else if (button[0].className.indexOf('buttons-print') >= 0) {
        //             $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
        //         }
        //         dt.one('preXhr', function (e, s, data) {
        //             // DataTables thinks the first item displayed is index 0, but we're not drawing that.
        //             // Set the property to what it was before exporting.
        //             settings._iDisplayStart = oldStart;
        //             data.start = oldStart;
        //         });
        //         // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
        //         setTimeout(dt.ajax.reload, 0);
        //         // Prevent rendering of the full data to the DOM
        //         return false;
        //     });
        // });
        // // Requery the server with the new one-time export settings
        // dt.ajax.reload();
    };
    //For Export Buttons available inside jquery-datatable "server side processing" - End
});
$(document).on('click', '.view', function () {
    let self = $(this);
    $.ajax({
        url: apiUrl + "pr/getClientData",
        type: "GET",
        data: { id: self.attr('data-id'), 'view': true },
        dataType: "json",
        success: function (r) {
            if (r.success) {
                $('.view-body').html(viewData(r.aData))
                $('#viewModal').modal('show')
            } else {
                showValidationError(r)
            }
        }
    })
})
function viewData(aData) {
    let sHtml = '';
    row = 0;
    sHtml += '<table class="table table-condensed"><tbody>'
    $.each(aData, function (k, v) {
        if (k != 'id') {

            if ((row % 2) == 0) {
                sHtml += '<tr>';
            }
            sHtml += '<td>' + k + '</td>'
            if (v != null && v != '') {
                sHtml += '<td>' + v + '</td>'
            } else {
                sHtml += '<td>-</td>'
            }
            // sHtml += '<div class="col-sm-6 values">' + v + '</div>'
            sHtml += '</div>'
            if ((row % 2) != 0) {
                sHtml += '</tr><tr>';
            }
            row++;
        }
    })
    sHtml += '<tbody></table>'
    return sHtml;
}
$(document).on('click', '.edit', function () {
    let self = $(this);
    $.ajax({
        url: apiUrl + "pr/getClientData",
        type: "GET",
        data: { id: self.attr('data-id') },
        dataType: "json",
        success: function (r) {
            if (r.success) {
                setData(r.aData)
                $('#updateModal').modal('show')
            } else {
                showValidationError(r)
            }
        }
    })
})
function setData(aData) {
    $.each(aData, function (k, v) {
        if (k == 'country') {
            $(".country").val(v).trigger('change');
        } else {
            $('[name=' + k + ']').val(v)
        }
    })

}
$(document).on('submit', '#client', function () {
    var aData = $(this).serializeArray();
    $('.preloader ').removeClass('d-none')
    $.ajax({
        url: apiUrl + "pr/updateclient",
        type: "PUT",
        data: aData,
        dataType: "json",
        success: function (r) {
            if (r.success) {
                $('.preloader ').addClass('d-none')
                response('Client has been updated', 'success');
                $('#updateModal').modal('hide')
            } else {
                $('.preloader ').addClass('d-none')
                showValidationError(r)
            }
        }
    })
    return false;
})
$(document).on('click', '.danger', function () {
    let self = $(this);
    let id;
    if (self.attr('data-action') == 'multiple') {
        id = $("input[name='delete[]']:checked")
            .map(function () { return $(this).val(); }).get();
    } else {
        id = self.attr('data-id');
    }
    Swal.fire({
        title: 'Do you want to delete the client(s)?',
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: `Delete`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.ajax({
                url: apiUrl + "pr/deleteClient",
                type: "DELETE",
                data: { id: id, action: self.attr('data-action') },
                dataType: "json",
                success: function (r) {
                    if (r.success) {
                        Swal.fire('Client Deleted!', '', 'success')
                        setTimeout(function () { location.reload(); }, 1000);
                    } else {
                        showValidationError(r)
                    }
                }
            })
        }
    })
})
$(document).on('click', '.check-all', function () {
    $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
})