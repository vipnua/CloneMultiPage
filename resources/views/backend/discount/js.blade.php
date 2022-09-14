<script>
    let name_search = null;
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
                d.name_search = name_search
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
            {
                data: 'name',
                className: '',
                render: function (data, type, row, meta) {
                    return data;
                }
            },
            {
                data: 'code',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return data;
                }
            },
            {
                data: 'current_use',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return data + "/" + row.max_use;
                }
            },
            {
                data: 'data',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    console.log(Object.keys(data).length)
                    if (Object.keys(data).length > 0) {
                        let element = '';
                        $.each(data, function (key, val) {
                            element += '<p>'+key+': '+val+'%</p>';
                        });
                        return element;
                    }
                    return '';
                }
            },
            {
                data: 'date_from',
                className: 'text-center td-uuid',
                render: function (data, type, row, meta) {
                    return format_date(data);
                }
            },
            {
                data: 'date_to',
                className: 'text-center td-uuid',
                render: function (data, type, row, meta) {
                    return format_date(data);
                }
            },
            {
                data: null,
                className: 'text-center',
                render: function (data, type, row) {
                    let edit_button = '';
                    let delete_button = '';
                        @can('update', 'App\Model\Discount')
                        edit_button = '<span name="btn-edit" data-data=\'' + JSON.stringify(row) + '\' class="menu-link px-3">Edit</span>';
                    @endcan
                        @can('delete', 'App\Model\Discount')
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


    $(document).on('click', 'span[name=btn-edit]', function () {
        form_reset();
        let data = $(this).data('data');
        console.log('data',data);
        $('#kt_modal_add_modal h2').text('Edit customer');
        let form = $('#kt_modal_add_modal_form');
        form.find('input[name=id]').val(data.id);
        form.find('input[name=name]').val(data.name);
        form.find('input[name=code]').val(data.code);
        form.find('input[name=max_use]').val(data.max_use);
        form.find('input[name=date_from]').val(data.date_from);
        form.find('input[name=date_to]').val(data.date_to);
        if (Object.keys(data).length > 0) {
            $.each(data.data, function (key, val) {
                form.find('input[name='+key+'_cb]').prop('checked', true);
                form.find('input[name='+key+']').prop('disabled', false);
                form.find('input[name='+key+']').val(val);
            });
        }
        $('#kt_modal_add_modal').modal('show');
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

    function form_reset() {
        let modal = $('#kt_modal_add_modal_form');
        modal.trigger('reset');
        modal.find('input[name=id]').val('');
        modal.find('input[name=profile]').prop('disabled', true);
        modal.find('input[name=share]').prop('disabled', true);
        modal.find('input[name=date]').prop('disabled', true);
        $('#kt_modal_add_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    $('#filter_form').bind('submit', function (e) {
        e.preventDefault();
        name_search = $('input[name=name_search]').val();
        Table.ajax.reload();
    });

    $('#filter_form').bind('reset', function () {
        name_search = null;
        Table.ajax.reload();
    });

    $('.check-data').on('click', function () {
        let data = $(this).data('check');
        if ($(this).is(':checked')) {
            $('input[name='+data+']').prop('disabled', false).focus();
        } else {
            $('input[name='+data+']').prop('disabled', true);
        }
    });

    $('#random_key').on('click', function () {
        let key = randomKey(8);
        $('input[name=code]').val(key);
    });

    function randomKey(length) {
        var result           = '';
        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() *
                charactersLength));
        }
        return result;
    }

</script>
