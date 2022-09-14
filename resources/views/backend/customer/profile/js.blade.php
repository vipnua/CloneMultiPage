<script>
    $.fn.dataTable.ext.errMode = 'none';
    var name_search = null;
    var os_search = null;
    let Table = $('#kt_customer_view_profile_table').DataTable({
        processing: true,
        serverSide: true,
        ordering: false,
        select: {
            style: 'os',
            selector: 'td:first-child',
            className: 'row-selected'
        },
        ajax: {
            url: '{{ route('user.tableprofile', $user->uuid) }}',
            data: function (d) {
                d.name_search = name_search,
                    d.os_search = os_search
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
                data: 'config.name',
                className: 'td-uuid',
                render: function (data, type, row, meta) {
                    return data;
                }
            },
            {
                data: 'config.browser',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return data;
                }
            },
            {
                data: 'config.version',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return data;
                }
            },
            {
                data: 'config.os',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    switch (data) {
                        case 'lin':
                            return '<i class="fab fa-linux"></i>' + ' Linux';
                            break;
                        case 'win':
                            return '<i class="fab fa-windows"></i>' + ' Window';
                            break;
                        case 'mac':
                            return '<i class="fab fa-apple"></i>' + ' MacOS';
                            break;
                        default:
                            return data;
                            break;
                    }
                }
            },
            {
                data: 'config',
                className: 'text-center',
                render: function (data, type, row) {
                    return '<a href="#" name="btn-view" data-id=' + row.id + ' data-data=\'' + JSON.stringify(data) + '\' class="btn btn-sm btn-light btn-active-light-primary">More' +
                        '</a>';
                },
            },
        ]
    });


    Table.on('draw', function () {
        KTMenu.createInstances();
    });

    var form_modal = $('#kt_modal_add_modal');

    $(document).on('click', 'a[name=btn-view]', function () {
        form_reset();
        let id = $(this).data('id');
        let data = $(this).data('data');
        form_modal.find('.copy_infor').attr('data-clipboard-text', JSON.stringify(data, undefined, 2));
        form_modal.find('#json').text(JSON.stringify(data, undefined, 2));
        form_modal.find('#profile_config').text(JSON.stringify(data, undefined, 2));
        form_modal.find('#btn_refresh_config').attr('data-config', JSON.stringify(data, undefined, 2));
        form_modal.find('#profile_config').css('display', 'none');
        $('#btn_edit_config').attr('data-id', id);
        $('#kt_modal_add_modal').modal('show');
    });

    $(document).on('click', '#btn_edit_config', function () {
        let id = $(this).data('id');
        let btn_config = $(this);
        let textarea_config = form_modal.find('#profile_config');
        let show_config = form_modal.find('#json');
        let copy_infor = form_modal.find('.copy_infor');
        if (btn_config.text() == 'Edit') {
            btn_config.text('Confirm');
            show_config.css('display', 'none');
            textarea_config.css('display', 'block');
        } else {
            btn_config.text('Edit');
            let config = textarea_config.val();
            show_config.text(config);
            copy_infor.attr('data-clipboard-text', config);
            $.ajax({
                url: '{{ route($func->route.".config") }}',
                type: 'PUT',
                dataType: 'json',
                data: {
                    id: id,
                    config: config
                },
                success: function (data) {
                    notification(data.type, data.title, data.content);
                    if (data.type == 'success') {
                        Table.ajax.reload(null, false);
                    }
                },
                error: function (data) {
                    notification('warning', 'Warning!', 'Have trouble, try again later.');
                }
            });
            textarea_config.css('display', 'none');
            show_config.css('display', 'block');
        }
    });

    $(document).on('click', '#btn_refresh_config', function () {
        let config = $(this).data('config');
        form_modal.find('.copy_infor').attr('data-clipboard-text', JSON.stringify(config, undefined, 2));
        form_modal.find('#json').text(JSON.stringify(config, undefined, 2));
        form_modal.find('#profile_config').text(JSON.stringify(config, undefined, 2));
        form_modal.find('#btn_refresh_config').attr('data-config', JSON.stringify(config, undefined, 2));
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

    var clipboard = new ClipboardJS('.copy_infor');
    clipboard.on('success', function (e) {
        notification('success', 'success', 'Copied');
        e.clearSelection();
    });

    function form_reset() {
        $('#kt_modal_add_modal_form').trigger('reset');
        $('#kt_modal_add_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    $('#filter_form').bind('submit', function (e) {
        e.preventDefault();
        name_search = $('input[name=name_search]').val();
        os_search = $('input[name=os_search]:checked').val();
        Table.ajax.reload();
    });

    $('#filter_form').bind('reset', function () {
        name_search = null;
        os_search = null;
        Table.ajax.reload();
    });

    $('#status-form').on('submit', function (e) {
        e.preventDefault();
        let status = $(this).children('button[name=btn-status]').data('status');
        if ($(this).children('input[name=status]').val() == '') {
            Swal.fire({
                text: "Are you sure you want to " + status + " this user?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, " + status + "!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-info",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function (result) {
                if (result.value) {
                    $('#status-form').children('input[name=status]').val(status);
                    notification('success', 'Success!', status + ' successfully');
                    setInterval(() => {
                        e.currentTarget.submit();
                    }, 3000);
                }
            });
        }
    });

    $(document).on('click', 'a[name=btn-status-block]', function () {
        let status = $(this).data('status');
        let id = $(this).data('id');
        Swal.fire({
            text: "Are you sure you would like to change status this user?",
            icon: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            confirmButtonText: "Yes, change it!",
            cancelButtonText: "No, return",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-active-light"
            }
        }).then((function (t) {
            if (t.value) {
                $.ajax({
                    url: '{{ route($func->route.".status") }}',
                    type: 'PUT',
                    dataType: 'json',
                    data: {
                        id: id,
                        status: status
                    },
                    success: function (data) {
                        notification(data.type, data.title, data.content);
                        if (data.type == 'success') {
                            switch (status) {
                                case 1:
                                    $('.block-box').html('<a href="#" class="btn btn-success ps-7 me-3" name="btn-status-block" data-status="2" data-id="' + id + '">Unblock</a>');
                                    break;
                                case 2:
                                    $('.block-box').html('<a href="#" class="btn btn-warning ps-7 me-3" name="btn-status-block" data-status="1" data-id="' + id + '">Block</a>');
                                    break;
                            }
                        }
                    },
                    error: function (data) {
                        notification('warning', 'Warning!', 'Have trouble, try again later.');
                    }
                });
            }
        }));
    });

    let tablePlan = $('#kt_customer_view_charge_table').DataTable({
        processing: true,
        serverSide: true,
        ordering: false,
        select: {
            style: 'os',
            selector: 'td:first-child',
            className: 'row-selected'
        },
        ajax: {
            url: '{{ route('plan.show', $user->id) }}',
            data: function (d) {
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
                data: 'status',
                className: 'td-uuid',
                render: function (data, type, row, meta) {
                    let status= '';
                    switch (data) {
                        case 1:
                            if (meta.row + meta.settings._iDisplayStart == 0)
                                status =  'Active';
                            else
                                status =  'Another plan is active';
                            break
                        case 2:
                            status = 'Pending';
                            break
                        case 3:
                            status = 'Expired';
                            break
                        default:
                            status = "";
                            break
                    }
                    let button = `<span class="${meta.row + meta.settings._iDisplayStart == 0?'badge badge-success':'badge badge-primary'}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="${status}">${row.name}</span>`;
                    return button;
                }
            },
            // {
            //     data: 'name',
            //     className: 'text-center',
            //     render: function (data, type, row, meta) {
            //         return data;
            //     }
            // },
            // {
            //     data: 'status',
            //     className: 'text-center',
            //     render: function (data, type, row, meta) {
            //         console.log('meta', row)
            //         switch (data) {
            //             case 1:
            //                 if (meta.row + meta.settings._iDisplayStart == 0)
            //                     return '<span class="badge badge-light-success">Active</span>';
            //                 else
            //                     return '<span class="badge badge-light-info">Another plan is active</span>';
            //                 break
            //             case 2:
            //                 return '<span class="badge badge-light-warning">Pending</span>';
            //                 break
            //             case 3:
            //                 return '<span class="badge badge-light-dark">Expired</span>';
            //                 break
            //             default:
            //                 return "";
            //                 break
            //         }
            //     }
            // },
            {
                data: null,
                className: 'text-center',
                render: function (data, type, row, meta) {
                    if (data != null) {
                        return row['payment_transaction']?`${row['payment_transaction'].amount} ${row['payment_transaction'].currency}`:"";
                    }
                    return 0;
                }
            },
            // {
            //     data: 'currency',
            //     className: 'text-center',
            //     render: function (data, type, row, meta) {
            //         return row['payment_transaction'].currency;
            //     }
            // },
            {
                data: null,
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return row['payment_transaction']?format_date(row['payment_transaction']['payment_date']):'';
                }
            },
            {
                data: 'expires_on',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return format_date(data);
                    // return row['payment_transaction'].expires_on;
                }
            },
            {
                data: null,
                className: 'text-center',
                render: function (data, type, row) {
                    return '<a href="#" name="btn-view-plan" data-data=\'' + JSON.stringify(data) + '\' class="btn btn-sm btn-light btn-active-light-primary">More' +
                        '</a>';
                },
            },
        ]
    });

    tablePlan.on('draw', function () {
        KTMenu.createInstances();
    });

    //format date value
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

    // open add plan
    $(document).on('click', '#add_plan', function () {
        form_reset();
        $('#kt_modal_add_plan_modal').modal('show');
    });

    //select plan
    $(document).on('click', '.btn_select_plan', function () {
        let plan_id = $('select[name = select-plan]').val();
        let payment_method = $('select[name = select-paymentMethod]').val();
        let amount = $('input[name = amount]').val();
        let currency = $('select[name = currency]').val();
        let transaction = $('input[name = transaction_id]').val();
        let note = $('#txtNote').val();

        let data = {
            user: {{($user)?$user->id:null}},
            note,
        }
        if (parseInt(plan_id)) {
            data.plan = plan_id
        }
        if (parseInt(payment_method)) {
            data.payment_method = payment_method
        }
        if (currency) {
            data.currency = currency
        }
        if (amount) {
            data.amount = amount
        }
        if (transaction) {
            data.transaction = transaction
        }


        $.ajax({
            url: '{{ route("plan.store") }}',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (data) {
                if (data.type === "success") {
                    notification(data.type, data.title, data.content);
                    $('#kt_modal_add_plan_modal').modal('hide');
                    tablePlan.ajax.reload();
                }
            },
            error: function (data) {
                notification('warning', 'Warning!', 'Have trouble, try again later.');
            }
        });
    });

    $(document).on('click', '#kt_modal_add_plan_modal_close, #kt_modal_view_plan_modal_close', function () {
        form_reset();
        $('#kt_modal_add_plan_modal').modal('hide');
        $('#kt_modal_view_plan_modal').modal('hide');
    });

    $(document).on('click', 'a[name=btn-view-plan]', function () {
        let data = $(this).data('data');
        $('#kt_modal_view_plan_modal').find('.copy_infor').attr('data-clipboard-text', JSON.stringify(data, undefined, 2));
        $('#kt_modal_view_plan_modal').find('#json').text(JSON.stringify(data, undefined, 2));
        $('#kt_modal_view_plan_modal').modal('show');
    });

    function form_reset() {
        $('#kt_modal_add_modal_form').trigger('reset');
        $('#kt_modal_add_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#kt_modal_bin').modal({
            backdrop: 'static',
            keyboard: false
        });

        $('select[name=select-plan]').prop('selectedIndex', 0);
        $('input[name=transaction_id]').val('');
        $('input[name=amount]').val('');
        $('select[name=currency]').prop('selectedIndex', 0);
        $('select[name=select-paymentMethod]').prop('selectedIndex', 0);
        $('#txtNote').val('');
        $('.preview-hide').show()
        $('.preview-show').hide()
    }
</script>
