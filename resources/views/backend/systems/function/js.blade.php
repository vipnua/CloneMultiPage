<script>
    $.fn.dataTable.ext.errMode = 'none';
    let Table = $('#kt_function_table').DataTable({
        processing: true,
        serverSide: true,
        ordering: false,
        select: {
            style: 'os',
            selector: 'td:first-child',
            className: 'row-selected'
        },
        ajax: {
            url: '{{ route($func->route.".show","") }}' + "/get-function-datatable",
            data: function(d) {}
        },
        columns: [{
                data: 'id',
                className: 'text-center td-limit',
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'name',
                className: 'text-center td-limit',
                render: function(data, type, row, meta) {
                    return data;
                }
            },
            {
                data: 'route',
                className: 'text-center td-limit',
                render: function(data, type, row, meta) {
                    return data;
                }
            },
            {
                data: 'controller',
                className: 'text-center td-limit',
                render: function(data, type, row, meta) {
                    return data;
                }
            },
            {
                data: 'icon',
                className: 'text-center td-limit',
                render: function(data, type, row, meta) {
                    return '<span class="menu-icon">' + decodeEntities(data) + '</span>';
                }
            },
            {
                data: 'status',
                className: 'text-center td-limit',
                render: function(data, type, row, meta) {
                    if (data == 1) {
                        return '<span class="badge badge-light-success">Active</span>';
                    }
                    return '<span class="badge badge-light-danger">Inactive</span>';
                }
            },
            {
                data: null,
                className: 'text-center',
                render: function(data, type, row) {
                    return '<a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions' +
                        '<span class="svg-icon svg-icon-5 m-0">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">' +
                        '<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" />' +
                        '</svg>' +
                        '</span>' +
                        '</a>' +
                        '<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">' +
                        '<div class="menu-item px-3">' +
                        '<span name="btn-edit" data-id="' + row.id +
                        '" class="menu-link px-3">Edit</span>' +
                        '</div>' +
                        '<div class="menu-item px-3">' +
                        '<span name="btn-delete" data-id="' + row.id +
                        '" class="menu-link px-3">Delete</span>' +
                        '</div>' +
                        '</div>';
                },
            },
        ],
        "createdRow": function(row, data, index) {
            if (data.parent_id == 0) {
                $(row).addClass('table-active');
            }
        },
    });


    Table.on('draw', function() {
        KTMenu.createInstances();
    });

    $(document).on('click', '#add_modal', function() {
        form_reset();
        $('#kt_modal_add_modal_header h2').text('Add function');
        $('#kt_modal_add_modal').modal('show');
    });


    $(document).on('click', 'span[name=btn-edit]', function() {
        form_reset();
        let id = $(this).data('id');
        let url = '{{ route($func->route.".edit",":id") }}';
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            data: {id:id},
            success: function(data) {
                if (data.type == 'success') {
                    $('#kt_modal_add_modal_form').html(data.content);
                    $('#kt_modal_add_modal_header h2').text('Edit function');
                    $('#kt_modal_add_modal').modal('show');
                } else {
                notification(data.type, data.title, data.content);
                }
            },
            error: function(data) {
                notification('warning', 'Warning!', 'Have trouble. Try again later');
            }
        });
    });

    function format_date(date_need) {
        const d = new Date(date_need);
        const ye = new Intl.DateTimeFormat('en', {
            year: 'numeric'
        }).format(d);
        const mo = new Intl.DateTimeFormat('en', {
            month: '2-digit'
        }).format(d);
        const da = new Intl.DateTimeFormat('en', {
            day: '2-digit'
        }).format(d);
        return da + '-' + mo + '-' + ye;
    }

    $(document).on('click', '#kt_modal_add_modal_close, #kt_modal_add_modal_cancel', function() {
        Swal.fire({
            text: "Are you sure you would like to cancel?",
            icon: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            confirmButtonText: "Yes, cancel it!",
            cancelButtonText: "No, return",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-active-light"
            }
        }).then((function(t) {
            t.value ? ($('#kt_modal_add_modal').modal('hide')) : "cancel" === t.dismiss;
        }))
    });

    $(document).on('submit', '#kt_modal_add_modal_form', function(e) {
        e.preventDefault();
        $('#kt_modal_add_modal_submit').attr("data-kt-indicator", "on");
        $('#kt_modal_add_modal_submit').prop('disabled', true);
        let data = $(this).serialize(),
            type = 'POST',
            url = $(this).attr('action'),
            id = $('#kt_modal_add_modal_form input[name=id]').val();
        if (parseInt(id)) {
            type = 'PUT';
            url = url + '/' + id;
        }
        $.ajax({
            url: url,
            type: type,
            dataType: 'json',
            data: data,
            success: function(data) {
                notification(data.type, data.title, data.content);
                if (data.type === "success") {
                    Table.ajax.reload(null, false);
                    $('#kt_modal_add_modal').modal('hide');
                }
            },
            error: function(data) {
                notification('warning', 'Warning!', 'Have trouble. Try again later');
            },
            complete: function() {
                $('#kt_modal_add_modal_submit').attr("data-kt-indicator", "off");
                $('#kt_modal_add_modal_submit').prop('disabled', false);
            }
        });
    });

    $(document).on('click', 'span[name=btn-delete]', function() {
        let id = $(this).data('id');
        Swal.fire({
            text: "Are you sure you want to delete this function?",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes, delete!",
            cancelButtonText: "No, cancel",
            customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: "btn fw-bold btn-active-light-primary"
            }
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: '{{ route($func->route.".destroy","") }}/' + id,
                    type: 'DELETE',
                    dataType: 'json',
                    success: function(data) {
                        notification(data.type, data.title, data.content);
                        Table.ajax.reload(null, false);
                    },
                    error: function(data) {
                        notification('warning', 'Warning!',
                            'Have trouble, try again later.');
                    }
                });
            } else if (result.dismiss === 'cancel') {
                Swal.fire({
                    text: "This function was not deleted.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                    }
                });
            }
        });
    });

    function form_reset() {
        $('#kt_modal_add_modal_form').trigger('reset');
        $('#kt_modal_add_modal_form').find("input, textarea").val("");
        $('#kt_modal_add_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    $(document).on('change', 'input[name=icon]', function() {
        let icon_tag = $(this).val();
        $('#navbar_example #menu_icon').html(icon_tag);
    });

    $(document).on('change', 'input[name=name]', function() {
        let menu_title = $(this).val();
        $('#navbar_example #menu_title').text(menu_title);
    });

    function decodeEntities(encodedString) {
        var textArea = document.createElement('textarea');
        textArea.innerHTML = encodedString;
        return textArea.value;
    }

</script>
