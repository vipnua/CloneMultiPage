<script>
    $.fn.dataTable.ext.errMode = 'none';
    let Table = $('#kt_resource_table').DataTable({
        processing: true,
        serverSide: true,
        ordering: false,
        select: {
            style: 'os',
            selector: 'td:first-child',
            className: 'row-selected'
        },
        ajax: {
            url: '{{ route($func->route.".show","") }}' + "/get-datatable",
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
                data: 'version',
                className: '',
                render: function(data, type, row, meta) {
                    return data;
                }
            },
            {
                data: 'description',
                className: 'text-center',
                render: function(data, type, row, meta) {
                    return data;
                }
            },
            {
                data: 'path',
                className: 'text-center',
                render: function(data, type, row, meta) {
                    return '<a href="{{route($func->route.".download","")}}/'+data+'" target="_blank">Click to download</a>';
                }
            },
            {
                data: 'status',
                className: 'text-center',
                render: function(data, type, row, meta) {
                    switch (data) {
                        case 0:
                            return '<span name="btn_status" class="badge badge-primary btn-status" data-id="'+row.id+'">New</span>';
                            break;
                        case 1:
                            return '<span class="badge badge-success">Active</span>';
                            break;
                        case 2:
                            return '<span class="badge badge-light">Used</span>';
                            break;
                    }
                }
            },
            {
                data: null,
                className: 'text-center',
                render: function(data, type, row) {
                    let edit_button = '';
                    let delete_button = '';
                    @can('update', 'App\Model\Browser\Resource')
                    edit_button = '<span name="btn-edit" data-data=\'' + JSON.stringify(row) + '\' class="menu-link px-3">Edit</span>';
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
        $('#kt_modal_add_admin h2').text('Add resource');
        $('#kt_modal_add_admin').modal('show');
    });


    $(document).on('click', 'span[name=btn-edit]', function() {
        form_reset();
        $('#kt_modal_add_admin h2').text('Edit resource');
        $('.upload-file').css('display','none');
        let data = $(this).data('data');
        let form = $('#kt_modal_add_admin_form');
        form.find('input[name=id]').val(data.id);
        form.find('input[name=name]').val(data.name);
        form.find('input[name=version]').val(data.version);
        form.find('textarea[name=description]').text(data.description);
        $('#kt_modal_add_admin').modal('show');
    });

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
        var formData = new FormData();
        // Attach file
        formData.append('file', $('input[type=file]')[0].files[0]); 
        let data = $(this).serializeArray(),
            type = 'POST',
            url = $(this).attr('action');
        $.each(data, function(key, el) {
            formData.append(el.name, el.value);
        });
        let processData = false;
        let contentType = false;
        id = $('#kt_modal_add_admin_form input[name=id]').val();
        if (parseInt(id)) {
            type = 'PUT';
            url = url + '/' + id;
            formData = $(this).serialize(); 
            processData = true;
            contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
        }
        $.ajax({
            url: url,
            type: type,
            dataType: 'json',
            processData: processData,
            contentType: contentType,
            data: formData,
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

    $(document).on('click', 'span[name=btn_status]', function() {
        let status = $(this).data('status');
        let id = $(this).data('id');
        Swal.fire({
            text: "Are you sure you would like to change status this resource?",
            icon: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            confirmButtonText: "Yes, change it!",
            cancelButtonText: "No, return",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-active-light"
            }
        }).then((function(t) {
            if (t.value) {
                $.ajax({
                    url: '{{ route($func->route.".status") }}',
                    type: 'PUT',
                    dataType: 'json',
                    data: {
                        id: id,
                        status: status
                    },
                    success: function(data) {
                        notification(data.type, data.title, data.content);
                        Table.ajax.reload(null, false);
                    },
                    error: function(data) {
                        notification('warning', 'Warning!', 'Have trouble, try again later.');
                    }
                });
            }
        }));
    });

    function form_reset() {
        $('#kt_modal_add_admin_form').trigger('reset');
        $('#kt_modal_add_admin_form input[name=id]').val('');
        $('#kt_modal_add_admin_form textarea[name=description]').text('');
        $('#kt_modal_add_admin').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('.upload-file').css('display','block');
    }
</script>
