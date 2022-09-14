<script>
    $(document).ready(function(){
        $.fn.dataTable.ext.errMode = 'none';
        let Table = $('#kt_roles_table').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            select: {
                style: 'os',
                selector: 'td:first-child',
                className: 'row-selected'
            },
            ajax: {
                url: '{{ route($func->route.".show",[$func->route => "get-roles-datatable"])}}',
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
                    data: 'description',
                    className: 'td-limit',
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    data: 'users_count',
                    className: 'text-center td-limit',
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row) {
                        let view_button = '';
                        let edit_button = '';
                        let delete_button = '';
                        @can('viewAssign', 'App\Model\RoleSecond')
                        view_button = '<a name="btn-view" href="{{route("userassign.index")}}/'+row.id+'" class="menu-link px-3">View</a>';
                        @endcan
                        @can('update', 'App\Model\RoleSecond')
                        edit_button = '<span name="btn-edit" data-id="' + row.id +
                            '" class="menu-link px-3">Edit</span>';
                        @endcan
                        @can('delete', 'App\Model\RoleSecond')
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
                                view_button +
                            '</div>' +
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
        });
        Table.on('draw', function() {
            KTMenu.createInstances();
        });

        //permission
        Table.columns(4).visible(true);
        @canany(['update','delete'], 'App\Model\RoleSecond')
        Table.columns(4).visible(true);
        @endcan
        Table.on('draw', function() {
            KTMenu.createInstances();
        });

        var original_form = null;
        //open modal create
        $(document).on('click', '#add_modal', function() {
            if (original_form != null) {
                $('#kt_modal_add_modal_form').html(original_form);
            }
            form_reset();
            $('#kt_modal_add_modal_header h2').text('Add role');
            $('#kt_modal_add_modal').modal('show');
        });

        //open modal edit
        $(document).on('click', 'span[name=btn-edit]', function() {
            form_reset();
            let id = $(this).data('id');
            $.ajax({
                url: '{{ route($func->route.".index") }}/'+ id + '/edit',
                type: 'GET',
                dataType: 'json',
                data: {id:id},
                success: function(data) {
                    if (data.type == 'success') {
                        let form = $('#kt_modal_add_modal_form');
                        original_form = form.html();
                        form.html(data.content);
                        $('#kt_modal_add_modal_header h2').text('Edit role');
                        $('#kt_modal_add_modal').modal('show');
                    } else {
                    notification(data.type, data.title, data.content);
                    }
                },
                error: function(data) {
                    if (data.status == 403)
                        notification('error', 'Error!', 'This action is unauthorized.');
                    else
                        notification('warning', 'Warning!', 'Have trouble. Try again later');
                }
            });
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

        // close modal
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
                t.value ? ($('#kt_modal_add_modal, #kt_modal_edit_modal').modal('hide')) : "cancel" === t.dismiss;
            }))
        });

        //submit form add edit
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
                    if (data.status == 403)
                        notification('error', 'Error!', 'This action is unauthorized.');
                    else
                        notification('warning', 'Warning!', 'Have trouble. Try again later');
                },
                complete: function() {
                    $('#kt_modal_add_modal_submit').attr("data-kt-indicator", "off");
                    $('#kt_modal_add_modal_submit').prop('disabled', false);
                }
            });
        });

        // delete a row
        $(document).on('click', 'span[name=btn-delete]', function() {
            let id = $(this).data('id');
            Swal.fire({
                text: "Are you sure you want to delete this role?",
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
                        url: '{{ route($func->route.".index") }}/' + id,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function(data) {
                            notification(data.type, data.title, data.content);
                            Table.ajax.reload(null, false);
                        },
                        error: function(data) {
                            if (data.status == 403)
                                notification('error', 'Error!', 'This action is unauthorized.');
                            else
                                notification('warning', 'Warning!', 'Have trouble. Try again later');
                        }
                    });
                }
            });
        });

        //reset form
        function form_reset() {
            $('#kt_modal_add_modal_form, #kt_modal_edit_modal').trigger('reset');
            $('#kt_modal_add_modal, #kt_modal_edit_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        }
        
        $(document).on('change','#kt_roles_select_all', function () {
            let group_input = document.querySelectorAll('#kt_modal_add_modal_form input[type=checkbox]');
            let select_all = $(this).is(":checked");
            group_input.forEach(element => {
                element.checked = select_all;
            });
        });
    });
</script>
