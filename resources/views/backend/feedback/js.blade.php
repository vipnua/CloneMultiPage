<script>
    let name_search = null;
    let status_search = null;
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
        columns: [
            {
            data: 'id',
            className: 'text-center',
            render: function (data, type, row, meta) {
                return data;
            }
            },
            { 
                data: 'uuid',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return data +'<i title="Copy" data-clipboard-text="'+ data +'" class="copy_infor la la-copy la-lg aaaaa" style="float:right;"></i>';
                }
            },
            {
                data: 'user_id',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return (data ?? 'Undefine');
                }
            },
            {
                data: 'type',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return (data ?? 'Undefine');
                }
            },
            {
                data: 'content',
                className: 'text-center td-uuid',
                render: function (data, type, row, meta) {
                    return (data);
                }
            },
            {
                data: 'status',
                className: 'text-center td-uuid',
                render: function (data, type, row, meta) {
                    switch (data) {
                        case 1:
                            return '<span class="badge badge-success btn-status cursor-none" data-id="' + row.id + '" data-status="1">Read</span>';
                            break;
                        case 0:
                            return '<span class="badge badge-danger btn-status cursor-none" data-id="' + row.id + '" data-status="0">Unread</span>';
                            break;
                    }
                    return data;
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
                render: function(data, type, row) {
                    let view_button = '';
                    @can('view', 'App\Model\Plan')
                    view_button = '<span name="btn-view" data-data=\'' + JSON.stringify(row) + '\' class="menu-link px-3">View</span>';
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

    //     function form_reset() {
    //     $('#textAreaPlan').summernote('reset');
    //     $('#kt_modal_add_modal_form').trigger('reset');
    //     $('#kt_modal_add_modal_form input[name=id]').val('');
    //     $('#kt_modal_add_modal_form input[name=uuid]').val('');
    //     $('#kt_modal_add_modal').modal({
    //         backdrop: 'static',
    //         keyboard: false
    //     });
    //     $('#kt_modal_bin').modal({
    //         backdrop: 'static',
    //         keyboard: false
    //     });
    // }
    
    $(document).on('click', 'span[name=btn-view]', function () {
       
        // form_reset();
        let data = $(this).data('data');
        $('#kt_modal_add_modal h2').text('View feedback');
        $('#kt_modal_add_modal_form input[name=id]').val(data.user_id);
        $('#kt_modal_add_modal_form input[name=uuid]').val(data.uuid);
        $('#textAreaPlan').val(data.content);
        $('#kt_modal_add_modal_form input[name=user_id]').val(data.user_id);
        $('#kt_modal_add_modal_form input[name=type]').val(data.type);
        $('#kt_modal_add_modal_form input[name=status]').val(data.status);
        $('#kt_modal_add_modal_form input[name=date_from]').val(data.date_from);
        $('#kt_modal_add_modal_form input[name=date_to]').val(data.date_to);
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
        // let data = $(this).serialize() ,
        //     type = 'POST',
        //     url = $(this).attr('action'),
        //     id = $("#kt_modal_add_modal_form input[name=id]").val();
        //     console.log(data);
        // if (parseInt(id)) {
        //     type = 'PATCH';
        //     url = url + '/' + id; 
        //  }
            let status = $('#kt_modal_add_modal_form input[name=status]').val();
            // console.log(status); 
            if(status == "0"){
                status ='1';
                var data = {
                "status" : status
            };
            }else{
                return false;
            }
            id = $("#kt_modal_add_modal_form input[name=id]").val();
            url = $(this).attr('action')+'/'+id;
        $.ajax({
            url: url = $(this).attr('action')+'/'+id,
            type: "PATCH",
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

</script>
