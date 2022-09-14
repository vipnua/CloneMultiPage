<script>
    let name_search = null;
    let status_search = null;
    const TYPE = {
        '1': 'bank',
        '2': 'paypal',
    };
    const COUNTRY = ['VN', 'US', 'SG', 'MY'];
    const STATUS = {
        '1': 'enable',
        '0': 'disable',
    };
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
                    d.status_search = status_search
            }
        },
        columns: [{
            data: 'id',
            className: 'text-center',
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
            {
                data: 'method',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return capitalizeFirstLetter(TYPE[data] ?? 'Undefine');
                }
            },
            {
                data: 'country',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return capitalizeFirstLetter(data ?? 'Undefine');
                }
            },
            {
                data: 'icon',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return `<img style=" max-width: 100px;max-height: 100px; " src="/payment_method/${data}"/>`;
                }
            },
            {
                data: 'name',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return capitalizeFirstLetter(data);
                }
            },
            {
                data: 'info',
                className: '',
                render: function (data, type, row, meta) {
                    let html = '';
                    $.each(data, (key, value) => {
                        if(key!=="token")
                        html += `<p class="m-0">${capitalizeFirstLetter(key.replace('_', ' '))}: ${value}</p>`
                    });
                    // console.log(data)
                    let result;
                    // switch (TYPE[row.type]) {
                    //     case 'bank':
                    //         result = `<p>ST: ${row.bank_number}</p>
                    //          <p>STK: ${row.account_number}</p>`
                    //         break;
                    //     case 'paypal':
                    //         result = `${row.client_id}`
                    //         break;
                    //
                    //     default:
                    //         result = 'Undefine'
                    //         break;
                    // }
                    return html;
                }
            },
            {
                data: 'status',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    // return capitalizeFirstLetter(data ? 'enable' : 'disable');
                    return `<span class="badge badge-${data ? 'success' : 'danger'} btn-status" data-id="'+row.id+'" data-status="1">${data ? 'Enable' : 'Disable'}</span>`
                }
            },
            {
                data: null,
                className: 'text-center',
                render: function (data, type, row) {
                    console.log(row)
                    let view_button = '';
                    let edit_button = '';
                    let delete_button = '';
                    {{--                    @can('viewProfile', 'App\User')--}}
                            {{--                        view_button = '<a name="btn-view"  href="{{route("user.profile","")}}/' + row.uuid + '" class="menu-link px-3">View</a>';--}}
                            {{--                    @endcan--}}
                            @can('update', 'App\User')
                        edit_button = '<span name="btn-edit" data-data=\'' + JSON.stringify(row) + '\' class="menu-link px-3">Edit</span>';
                    @endcan
                            @can('delete', 'App\User')
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
                        // view_button +
                        // '</div>' +
                        // '<div class="menu-item px-3">' +
                        edit_button +
                        '</div>' +
                        '<div class="menu-item px-3">' +
                        delete_button +
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
        $('#kt_modal_add_modal h2').text('Add Payment Method');
        $('#kt_modal_add_modal').modal('show');
        checkTypePaylmetnMethod()
    });

    $("#kt_modal_add_modal").on('hidden.bs.modal', function () {
        form_reset();
    });


    $(document).on('click', 'span[name=btn-edit]', function () {
        // form_reset();
        let data = $(this).data('data');

        $('#kt_modal_add_modal h2').text('Edit Payment Method');
        $('#kt_modal_add_modal_form input[name=id]').val(data.id);

        let method = $('select[name=type]');
        method.val(TYPE[data.method])
        checkTypePaylmetnMethod()


        $('#kt_modal_add_modal_form select[name=country]').val(data.country);
        $('#kt_modal_add_modal_form select[name=status]').val(STATUS[data.status]);
        $('#kt_modal_add_modal_form input[name=bank_name]').val(data.name);
        $('#kt_modal_add_modal_form #background_image').css('background-image', `url(/payment_method/${data.icon})`);

        $.each(data.info, ((item) => {
            $($(`#kt_modal_add_modal_form input[name=${item}]`)[0]).val(data.info[item]);
            console.log(item)
            if (item === "type") {
                $(`#kt_modal_add_modal_form select[name="paypal_sandbox"]`).val(data.info[item] === "live" ? "disable" : "enable");
            }
        }))


        $('#kt_modal_add_modal').modal('show');
    });


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
        // $('#kt_modal_add_modal_submit').attr("data-kt-indicator", "on");
        // $('#kt_modal_add_modal_submit').prop('disabled', true);
        let formData = new FormData(),
            type = 'POST',
            url = $(this).attr('action'),
            id = $('#kt_modal_add_modal_form input[name=id]').val();

        formData.append("method", $('select[name=type]').val());
        formData.append("country", $('select[name=country]').val());
        formData.append("status", $('select[name=status]').val());
        formData.append("name", $('input[name=bank_name]').val());

        switch ($('select[name=type]').val()) {
            case TYPE[1]:
                formData.append("account_number", $('input[name=account_number]').val());
                formData.append("branch_name", $('input[name=branch_name]').val());
                if ($('input[name=icon]')[0].files[0]) {
                    formData.append("icon", $('input[name=icon]')[0].files[0]);
                }
                break;
            case TYPE[2]:
                formData.append("client_id", $('input[name=client_id]').val());
                formData.append("secret_key", $('input[name=secret_key]').val());
                formData.append("paypal_sandbox", $('select[name=paypal_sandbox]').val());
                break;

            default:

                break;
        }

        if (parseInt(id)) {
            url = url + '/update/' + id;
        }
        $.ajax({
            url: url,
            type: type,
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
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
            text: "Are you sure you want to delete this user?",
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


    function form_reset() {
        $('#kt_modal_add_modal_form').trigger('reset');
        $('#kt_modal_add_modal_form input[name=id]').val('')
        $('#background_image').css("background-image", "url(/assets/media/avatars/blank.png)");
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
        status_search = $('input[name=status_search]:checked').val();
        Table.ajax.reload();
    });

    $('#filter_form').bind('reset', function () {
        name_search = null;
        status_search = null;
        Table.ajax.reload();
    });

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    $(document).on('click', 'select[name=type]', function () {
        checkTypePaylmetnMethod();
    })

    checkTypePaylmetnMethod();

    function checkTypePaylmetnMethod() {
        let paymentMethodType = $('select[name=type]');
        switch (paymentMethodType.val()) {
            case 'paypal':
                $('#group_type_bank').hide();
                $('#group_type_paypal').show();
                break;

            default:
                $('#group_type_paypal').hide();
                $('#group_type_bank').show();
                break;
        }
    }

</script>
