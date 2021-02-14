var table = "";
$(document).ready(function () {
    console.log($("#admin"));
    table = $("#admin").DataTable({
        // "processing": true,
        // "serverSide": true,
        "ajax": {
            "url": apiUrl + "pr/admin",
            complete: function (settings, json) {
                table.destroy();
                table = $("#admin").DataTable({
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
                                return meta.row + 1; // adds id to serial no
                            }
                        },
                        {
                            "targets": -1,
                            "searchable": false,
                            "orderable": false,
                            "className": "dt-body-center",
                            "render": function (data, type, row, meta) {
                                let lastRow = '';
                                lastRow += `<i data-id='${data}' class="material-icons edit">mode_edit</i>`;
                                lastRow += `<i data-id='${data}' data-action="individual" class="material-icons danger">delete</i>`;
                                return lastRow
                            }
                        }
                    ]
                });
            },
        },
    });

});
$(document).on('click', '.danger', function () {
    let self = $(this);
    let id = self.attr('data-id');
    Swal.fire({
        title: 'Do you want to delete the admin?',
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: `Delete`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.ajax({
                url: apiUrl + "pr/deleteAdmin",
                type: "DELETE",
                data: { id: id },
                dataType: "json",
                success: function (r) {
                    if (r.success) {
                        Swal.fire('Admin Deleted!', '', 'success')
                        self.parent().parent().remove()
                    } else {
                        showValidationError(r)
                    }
                }
            })
        }
    })
})
$(document).on('click', '.edit', function () {
    let self = $(this);
    $.ajax({
        url: apiUrl + "pr/adminData",
        type: "GET",
        data: { id: self.attr('data-id') },
        dataType: "json",
        success: function (r) {
            if (r.success) {
                setData(r.data)
                $('#updateModal').modal('show')
            } else {
                showValidationError(r)
            }
        }
    })
})
function setData(aData) {
    $.each(aData, function (k, v) {
        $('[name=' + k + ']').val(v)
        if(k = 'email'){
            $('[name=' + k + ']').attr('disabled',true);
        }
    })
}
$(document).on('submit', '#admin', function () {
    var aData = $(this).serializeArray();
    $('.preloader ').removeClass('d-none')
    $.ajax({
        url: apiUrl + "pr/Admin",
        type: "PUT",
        data: aData,
        dataType: "json",
        success: function (r) {
            if (r.success) { 
                $('.preloader ').addClass('d-none')
                response('Admin has been saved', 'success');
                setTimeout(function () { location.reload(); }, 1000);
            } else {
                $('.preloader ').addClass('d-none')
                showValidationError(r)
            }
        }
    })
    return false;
})