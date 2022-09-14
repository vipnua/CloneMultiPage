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
                data: 'transaction_id',
                className: '',
                render: function (data, type, row, meta) {
                    return (data?? 'Undefine');
                }
            },
            {
                data: 'user_name',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return (data ?? 'Undefine');
                }
            },
            {
                data: 'plan_name',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return (data ?? 'Undefine');
                }
            },
            {
                data: null,
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return (`${row.amount} ${row.currency}`);
                }
            },
            {
                data: null,
                className: 'text-center',
                render: function (data, type, row, meta) {
                    if (row.payment_date) {
                        if (row.charge_id) {
                            return `<span class="badge badge-success btn-status" data-id="'+row.id+'" data-status="1">Success</span>`
                        }else{
                            return `<span class="badge badge-warning btn-status" data-id="'+row.id+'" data-status="1">Not Charge</span>`
                        }
                    }else{
                        return `<span class="badge badge-danger btn-status" data-id="'+row.id+'" data-status="1">Pending</span>`
                    }
                }
            },
            {
                data: null,
                className: 'text-center',
                render: function (data, type, row, meta) {
                    switch (row.employee_id) {
                        case 0:
                            return 'Autobank'

                        case -1:
                            return 'Paypal'

                        default:
                            return row.employee_name
                    }
                }
            },
            {
                data: null,
                className: '',
                render: function (data, type, row) {
                    return row.charge_id?row.note:row.system_note??""
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
        status_search = $('input[name=status_search]:checked').val();
        Table.ajax.reload();
    });

    $('#filter_form').bind('reset', function () {
        name_search = null;
        status_search = null;
        Table.ajax.reload();
    });



</script>
