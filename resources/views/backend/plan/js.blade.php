<script>
    let name_search = null;
    let default_search = null;
    $.fn.dataTable.ext.errMode = 'none';
    let Table = $('#kt_modal_table').DataTable({
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
            data: function (d) {
                d.name_search = name_search,
                    d.default_search = default_search
            }
        },
        columns: [
            {
                data: 'id',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            // {
            //     data: 'uuid',
            //     className: 'td-uuid',
            //     render: function (data, type, row, meta) {
            //         return data + '<i title="Copy" data-clipboard-text="' + data + '" class="copy_infor la la-copy la-lg aaaaa" style="float:right;"></i>';
            //     }
            // },
            {
                data: 'name',
                className: '',
                render: function (data, type, row, meta) {
                    return '<a href="#" class=" mb-1 td-uuid mx-0 px-1'
                        + `${row.default ? ' btn btn-light-success cursor-none' : ' btn text-gray-800 cursor-none'}`
                        + `" ${row.default ? 'data-bs-toggle="tooltip" data-bs-placement="top" title="Plan Default"' : ''}>`
                        + data + '</a>';
                }
            },
            {
                data: 'price',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return data ?? 0;
                }
            },
            {
                data: 'interval',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return data + ' day';
                }
            },
            {
                data: 'profile',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return data;
                }
            },
            {
                data: 'profile_share',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return data;
                }
            },
            {
                data: 'status',
                className: 'text-center td-uuid',
                render: function (data, type, row, meta) {
                    switch (data) {
                        case 1:
                            return '<span class="badge badge-success btn-status cursor-none" data-id="' + row.id + '" data-status="1">Active</span>';
                            break;
                        case 0:
                            return '<span class="badge badge-danger btn-status cursor-none" data-id="' + row.id + '" data-status="2">Inactive</span>';
                            break;
                    }
                    return data;
                }
            },
            {
                data: 'describe',
                className: 'text-center td-uuid',
                render: function (data, type, row, meta) {
                    return data;
                }
            },
            {
                data: null,
                className: 'text-center',
                render: function (data, type, row) {
                    let view_button = '';
                    let edit_button = '';
                    let delete_button = '';
                    @can('setDefault', 'App\Model\Plan')
                        view_button = '<span name="btn-view"  data-id="' + row.id + '" class="menu-link px-3 plan-set-default">Set Default</span>';
                    @endcan
                        @can('update', 'App\Model\Plan')
                        edit_button = '<span name="btn-edit" data-data=\'' + JSON.stringify(row) + '\' class="menu-link px-3">Edit</span>';
                    @endcan
                        @can('delete', 'App\Model\Plan')
                        delete_button = '<span name="btn-delete" data-id="' + row.id + '" class="menu-link px-3">Delete</span>';
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
                        '<div class="menu-item px-3">' +
                        view_button +
                        '</div>' +
                        '</div>';
                },
            },
        ],
        "createdRow": function (row, data, index) {
        },
    });

    Table.on('draw', function () {
        KTMenu.createInstances();
    });

    $(document).on('click', '#add_modal', function () {
        form_reset();
        $('#kt_modal_add_modal h2').text('Add Plan');
        $('#kt_modal_add_modal').modal('show');
    });

    $(document).on('click', '#set_default_modal', function () {
        $('#kt_modal_set_default_plan_modal').modal('show');
    });
    $(document).on('click', '.plan-set-default', function () {
        let id = $(this).data('id');
        Swal.fire({
            text: "Are you sure you want to set default?",
            icon: "success",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes, set default!",
            cancelButtonText: "No, cancel",
            customClass: {
                confirmButton: "btn fw-bold btn-success",
                cancelButton: "btn fw-bold btn-active-light-primary"
            }
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: '{{ route($func->route.".setDefault","") }}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id
                    },
                    success: function (data) {
                        notification(data.type, data.title, data.content);
                        Table.ajax.reload(null, false);
                    },
                    error: function (data) {
                        notification('warning', 'Warning!', 'Have trouble, try again later.');
                    }
                });
            }
        });
    });
    $(document).on('click', '#kt_modal_set_default_plan_modal_header', function () {
        $('#kt_modal_set_default_plan_modal').modal('hide');
    });


    $(document).on('click', 'span[name=btn-edit]', function () {
        form_reset();
        let data = $(this).data('data');
        $('#kt_modal_add_modal h2').text('Edit Plan');
        $('#kt_modal_add_modal_form input[name=id]').val(data.id);
        $('#kt_modal_add_modal_form input[name=uuid]').val(data.uuid);
        $('#kt_modal_add_modal_form input[name=name]').val(data.name);
        $('#kt_modal_add_modal_form input[name=price]').val(data.price);
        $('#kt_modal_add_modal_form input[name=price_vn]').val(data.price_vn);
        $('#kt_modal_add_modal_form input[name=interval]').val(data.interval);
        $('#kt_modal_add_modal_form input[name=profile]').val(data.profile);
        $('#kt_modal_add_modal_form input[name=profile_share]').val(data.profile_share);
        $('#kt_modal_add_modal_form input[name=interval_type]').val(data.interval_type);

        $('#textAreaPlan').summernote('code', data.describe);
        $('#kt_modal_add_modal_form select[name=status]').val(data.status);

        $('#kt_modal_add_modal').modal('show');
    });

    // $("#kt_modal_add_modal").on('shown.bs.modal', function () {
    //     let autosize_element = $('#textAreaPlan');
    //     autosize_element.css(
    //         {
    //             'height': document.getElementById("textAreaPlan").scrollHeight,
    //             'overflow': 'hidden',
    //         }
    //     );
    // });
    // $("#kt_modal_add_modal").on('hidden.bs.modal', function () {
    //     form_reset();
    //     let autosize_element = $('#textAreaPlan');
    //     autosize_element.css(
    //         {
    //             'height': document.getElementById("textAreaPlan").scrollHeight,
    //             'overflow': 'hidden',
    //         }
    //     );
    // });

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

    $(document).on('click', '#kt_modal_add_modal_close, #kt_modal_add_modal_cancel', function () {
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
        }).then((function (t) {
            t.value ? ($('#kt_modal_add_modal').modal('hide')) : "cancel" === t.dismiss;
        }))
    });

    $(document).on('submit', '#kt_modal_add_modal_form', function (e) {
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
            success: function (data) {
                notification(data.type, data.title, data.content);
                if (data.type === "success") {
                    Table.ajax.reload(null, false);
                    $('#kt_modal_add_modal').modal('hide');
                }
            },
            error: function (data) {
                notification('warning', 'Warning!', 'Have trouble, try again later.');
            },
            complete: function () {
                $('#kt_modal_add_modal_submit').attr("data-kt-indicator", "off");
                $('#kt_modal_add_modal_submit').prop('disabled', false);
            }
        });
    });

    $(document).on('click', 'span[name=btn-delete]', function () {
        let id = $(this).data('id');
        Swal.fire({
            text: "Are you sure you want to delete this plan?",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes, delete!",
            cancelButtonText: "No, cancel",
            customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: "btn fw-bold btn-active-light-primary"
            }
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: '{{ route($func->route.".destroy","") }}/' + id,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        notification(data.type, data.title, data.content);
                        Table.ajax.reload(null, false);
                    },
                    error: function (data) {
                        notification('warning', 'Warning!', 'Have trouble, try again later.');
                    }
                });
            }
        });
    });

    var clipboard = new ClipboardJS('.copy_infor');
    clipboard.on('success', function (e) {
        notification('success', 'success', 'Copied');
        e.clearSelection();
    });

    function form_reset() {
        $('#textAreaPlan').summernote('reset');
        $('#kt_modal_add_modal_form').trigger('reset');
        $('#kt_modal_add_modal_form input[name=id]').val('');
        $('#kt_modal_add_modal_form input[name=uuid]').val('');
        $('#kt_modal_add_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#kt_modal_bin').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    $('#filter_form').bind('submit', function (e) {
        e.preventDefault();
        name_search = $('input[name=name_search]').val();
        default_search = $('input[name=default_search]:checked').val();
        Table.ajax.reload();
    });

    $('#filter_form').bind('reset', function () {
        name_search = null;
        default_search = null;
        Table.ajax.reload();
    });

    $('#textAreaPlan').summernote();


</script>
