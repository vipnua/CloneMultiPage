<script>
    $(document).ready(function(){
        $.fn.dataTable.ext.errMode = 'none';
        let Table = $('#kt_users_assign_table').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            select: {
                style: 'os',
                selector: 'td:first-child',
                className: 'row-selected'
            },
            ajax: {
                url: '{{ route("userassign.show",["userassign" => "get-roles-datatable"])}}',
                data: function(d) {
                    d.role_id = {{$role->id}}
                }
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
                        let info =  '<div class="d-flex flex-column">\n'+
                                        '<a href="{{route("userassign.index")}}/'+row.id+'" class="text-gray-800 text-hover-primary mb-1">'+row.name+'</a>\n'+
                                    '</div>';
                        return info;
                    }
                },
                {
                    data: 'email',
                    className: 'text-center td-limit',
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    data: 'created_at',
                    className: 'text-center td-limit',
                    render: function(data, type, row, meta) {
                        return format_date(data);
                    }
                },
                {
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row) {
                        let checked = '';
                        @if($role->name == config('custom.role'))
                            @if(Auth::user()->is_admin())
                            $.each(row.roles, function (key, value) {
                                    if (value.id == {{$role->id}} || value.name == '{{config('custom.role')}}') {
                                        checked = 'checked';
                                        if (row.id == '{{Auth::user()->id}}') {
                                            checked += ' disabled';
                                        }
                                        return false;
                                    }
                                });
                                return  '<div class="form-check form-check-sm form-check-custom form-check-solid justify-content-around">\n'+
                                            '<input class="form-check-input" type="checkbox" name="assign_role" value="'+row.id+'" '+checked+'>\n'+
                                        '</div>';
                            @endif
                        @else
                        $.each(row.roles, function (key, value) {
                                if (value.id == {{$role->id}} || value.name == '{{config('custom.role')}}') {
                                    checked = 'checked';
                                        if (value.name == '{{config('custom.role')}}') {
                                            checked += ' disabled';
                                        }
                                    return false;
                                }
                            });
                            return  '<div class="form-check form-check-sm form-check-custom form-check-solid justify-content-around">\n'+
                                        '<input class="form-check-input" type="checkbox" name="assign_role" value="'+row.id+'" '+checked+'>\n'+
                                    '</div>';
                        @endif
                    },
                },
            ],
            "createdRow": function( row, data, dataIndex ) {
                $.each(data.roles, function (key, value) {
                    if (value.name == '{{config('custom.role')}}') {
                        $(row).css( 'background', 'aliceblue');
                    }
                });
            }
        });
        Table.on('draw', function() {
            KTMenu.createInstances();
        });

        //open modal edit
        $(document).on('click', 'button[name=btn-edit]', function() {
            form_reset();
            $('#kt_modal_edit_modal h2').text('Edit role');
            $('#kt_modal_edit_modal').modal('show');
        });

        // close modal
        $(document).on('click', '#kt_modal_edit_modal_close, #kt_modal_edit_modal_cancel', function() {
            console.log(1);
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

        //submit form edit
        $(document).on('submit', '#kt_modal_edit_modal_form', function(e) {
            e.preventDefault();
            $('#kt_modal_edit_modal_submit').attr("data-kt-indicator", "on");
            $('#kt_modal_edit_modal_submit').prop('disabled', true);
            let data = $(this).serialize(),
                type = 'PUT',
                url = $(this).attr('action'),
                id = $('#kt_modal_edit_modal_form input[name=id]').val();
            $.ajax({
                url: url+'/'+id,
                type: type,
                dataType: 'json',
                data: data,
                success: function(data) {
                    notification(data.type, data.title, data.content);
                    if (data.type === "success") {
                        $('.card-title h2').text(data.data.name);
                        $('.card-body p').text(data.data.description);
                        $('#kt_modal_edit_modal').modal('hide');
                    }
                },
                error: function(data) {
                    if (data.status == 403)
                        notification('error', 'Error!', 'This action is unauthorized.');
                    else
                        notification('warning', 'Warning!', 'Have trouble. Try again later');
                },
                complete: function() {
                    $('#kt_modal_edit_modal_submit').attr("data-kt-indicator", "off");
                    $('#kt_modal_edit_modal_submit').prop('disabled', false);
                }
            });
        });

        //assign role
        $(document).on('change', 'input[name=assign_role]', function () {
            let target = $(this);
            let select = target.is(':checked');
            let user_id = target.val();
            let id = {{$role->id}};
            $.ajax({
                url: '{{route("userassign.index")}}/assign-role',
                type: 'PUT',
                dataType: 'json',
                data: {id:id, select:select, user_id:user_id},
                success: function(data) {
                    notification(data.type, data.title, data.content);
                    if (data.type == 'error') {
                        reset_checkbox(target);
                    }
                },
                error: function(data) {
                    reset_checkbox(target);
                    if (data.status == 403)
                        notification('error', 'Error!', 'This action is unauthorized.');
                    else
                        notification('warning', 'Warning!', 'Have trouble. Try again later');
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
            let group_input = document.querySelectorAll('#kt_modal_edit_modal_form input[type=checkbox]');
            let select_all = $(this).is(":checked");
            group_input.forEach(element => {
                element.checked = select_all;
            });
        });
    });

    function format_date(date_need) {
        const d = new Date(date_need);
        options = {
            year: 'numeric', month: 'numeric', day: 'numeric',
        };
        return new Intl.DateTimeFormat('vn', options).format(d);
    }

    function reset_checkbox(target) {
        if (target.is(':checked')) {
            target.prop('checked', false);
        } else {
            target.prop('checked', true);
        }
    }
</script>
