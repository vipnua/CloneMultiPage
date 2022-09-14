<script>
    $.fn.dataTable.ext.errMode = 'none';
    let Table = $('#kt_admin_table').DataTable({
        processing: true,
        serverSide: true,
        ordering: false,
        select: {
            style: 'os',
            selector: 'td:first-child',
            className: 'row-selected'
        },
        ajax: {
            url: '{{ route($func->route.".show","") }}' + "/get-admin-datatable",
            data: function(d) {}
        },
        columns: [{
                data: 'id',
                className: 'text-center',
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'name',
                className: '',
                render: function(data, type, row, meta) {
                    return '<a href="#" class="text-gray-800 text-hover-primary mb-1">' + data + '</a>';
                }
            },
            {
                data: 'email',
                className: '',
                render: function(data, type, row, meta) {
                    return data;
                }
            },
            {
                data: 'infor.address',
                className: 'text-center',
                render: function(data, type, row, meta) {
                    return data;
                }
            },
            {
                data: 'infor.gender',
                className: 'text-center',
                render: function(data, type, row, meta) {
                    switch (data) {
                        case 1:
                            return 'Male'
                            break;
                        case 2:
                            return 'Female'
                            break;
                        default:
                            return 'Other'
                            break;
                    }
                }
            },
            {
                data: 'roles',
                className: 'text-center',
                render: function(data, type, row, meta) {
                    let roles = '';
                    $.each(data, function (k,v) {
                        roles += '<span class="badge badge-light me-2">'+v.name+'</span>';
                    });
                    return roles;
                }
            },
            {
                data: null,
                className: 'text-center',
                render: function(data, type, row) {
                    let edit_button = '';
                    let delete_button = '';
                    @can('update', 'App\Model\Admin')
                    edit_button = '<span name="btn-edit" data-data=\'' + JSON.stringify(row) + '\' class="menu-link px-3">Edit</span>';
                    @endcan
                    @can('delete', 'App\Model\Admin')
                    delete_button = '<span name="btn-delete" data-id="' + row.id +
                        '" class="menu-link px-3">Delete</span>';
                    @endcan
                    return '<a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions' +
                        '<span class="svg-icon svg-icon-5 m-0">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">' +
                        '<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" />' +
                        '</svg>' +
                        '</span>' +
                        '</a>' +
                        '<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">' +
                        '<div class="menu-item px-3">' +
                            edit_button +
                        '</div>' +
                        '<div class="menu-item px-3">' +
                            delete_button +
                        '</div>' +
                        '</div>';
                },
            },
        ],
        "createdRow": function(row, data, index) {},
    });


    Table.on('draw', function() {
        KTMenu.createInstances();
    });

    $(document).on('click', '#add_admin', function() {
        form_reset();
        $('#kt_modal_add_admin h2').text('Add employee');
        $('#kt_modal_add_admin').modal('show');
    });


    $(document).on('click', 'span[name=btn-edit]', function() {
        form_reset();
        $('#kt_modal_add_admin h2').text('Edit employee');
        let data = $(this).data('data');
        let form = $('#kt_modal_add_admin_form');
        form.find('input[name=id]').val(data.id);
        form.find('input[name=email]').val(data.email);
        if (data.infor) {
            let infor = data.infor;
            form.find('input[name=first_name]').val(infor.first_name);
            form.find('input[name=last_name]').val(infor.last_name);
            form.find('input[name=birthday]').val(infor.birthday);
            form.find('input[name=address]').val(infor.address);
            form.find('select[name=gender] option[value=' + infor.gender + ']').prop('selected', true);
        }
        $.each(data.roles, function (k,v) {
            console.log(v.id);
            form.find('select[name="roles[]"] option[value=' + v.id + ']').prop('selected', true);
        });
        $('select[name="roles[]"]').select2();
        $('#kt_modal_add_admin').modal('show');
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

    $(document).on('click', '#kt_modal_add_admin_close, #kt_modal_add_modal_cancel', function() {
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
            t.value ? ($('#kt_modal_add_admin').modal('hide')) : "cancel" === t.dismiss;
        }))
    });

    $(document).on('submit', '#kt_modal_add_admin_form', function(e) {
        e.preventDefault();
        $('#kt_modal_add_admin_submit').attr("data-kt-indicator", "on");
        $('#kt_modal_add_admin_submit').prop('disabled', true);
        let data = $(this).serialize(),
            type = 'POST',
            url = $(this).attr('action'),
            id = $('#kt_modal_add_admin_form input[name=id]').val();
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
                    $('#kt_modal_add_admin').modal('hide');
                }
            },
            error: function(data) {
                notification('warning', 'Warning!', 'Have trouble, try again later.');
            },
            complete: function() {
                $('#kt_modal_add_admin_submit').attr("data-kt-indicator", "off");
                $('#kt_modal_add_admin_submit').prop('disabled', false);
            }
        });
    });

    $(document).on('click', 'span[name=btn-delete]', function() {
        let id = $(this).data('id');
        Swal.fire({
            text: "Are you sure you want to delete this employee?",
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
                        notification('warning', 'Warning!', 'Have trouble, try again later.');
                    }
                });
            } else if (result.dismiss === 'cancel') {
                Swal.fire({
                    text: "This employee was not deleted.",
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
        $('#kt_modal_add_admin_form').trigger('reset');
        $('#kt_modal_add_admin').modal({
            backdrop: 'static',
            keyboard: false
        });
    }
</script>
