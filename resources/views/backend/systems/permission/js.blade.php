<script>
    $.fn.dataTable.ext.errMode = 'none';
    let Table = $('#kt_permissions_table').DataTable({
        processing: true,
        serverSide: true,
        ordering: false,
        select: {
            style: 'os',
            selector: 'td:first-child',
            className: 'row-selected'
        },
        ajax: {
            url: '{{ route($func->route.".show","") }}' + "/get-permissions-datatable",
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
                data: 'key',
                className: 'text-center td-limit',
                render: function(data, type, row, meta) {
                    return data;
                }
            },
            {
                data: 'roles',
                className: 'text-center td-roles td-limit',
                render: function(data, type, row, meta) {
                    let list = '';
                    if (data.length > 0) {
                        data.forEach(element => {
                            list += split_string(element.name, false);
                        });
                    }
                    return list;
                }
            },
            {
                data: 'permissions',
                className: 'text-center td-permissions td-limit',
                render: function(data, type, row, meta) {
                    let list = '';
                    if (data.length > 0) {
                        data.forEach(element => {
                            list += split_string(element.name, true);
                        });
                    }
                    return list;
                }
            },
            {
                data: null,
                className: 'text-center',
                render: function(data, type, row) {
                    return '<button class="btn btn-icon btn-active-light-primary w-30px h-30px me-3" name="btn-edit" data-id='+row.id+'>\n'+
                                '<span class="svg-icon svg-icon-3">\n'+
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">\n'+
                                        '<path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="black"></path>\n'+
                                        '<path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="black"></path>\n'+
                                    '</svg>\n'+
                                '</span>\n'+
                            '</button>\n'+
                            '<button class="btn btn-icon btn-active-light-primary w-30px h-30px" name="btn-delete" data-id='+row.id+'>\n'+
                                '<span class="svg-icon svg-icon-3">\n'+
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">\n'+
                                        '<path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black"></path>\n'+
                                        '<path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black"></path>\n'+
                                        '<path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black"></path>\n'+
                                    '</svg>\n'+
                                '</span>\n'+
                            '</button>';
                },
            },
        ],
    });

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
        $('#kt_modal_add_modal_header h2').text('Add permission');
        $('#kt_modal_add_modal').modal('show');
    });

    //open modal edit
    $(document).on('click', 'button[name=btn-edit]', function() {
        form_reset();
        let id = $(this).data('id');
        let url = '{{ route($func->route.".edit",":id") }}';
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            data: {id:id},
            success: function(data) {
                if (data.type == 'success') {
                    let form = $('#kt_modal_add_modal_form');
                    original_form = form.html();
                    form.html(data.content);
                    $('#kt_modal_add_modal_header h2').text('Edit permission');
                    $('#kt_modal_add_modal').modal('show');
                } else {
                notification(data.type, data.title, data.content);
                }
            },
            error: function(data) {
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
                notification('warning', 'Warning!', 'Have trouble. Try again later');
            },
            complete: function() {
                $('#kt_modal_add_modal_submit').attr("data-kt-indicator", "off");
                $('#kt_modal_add_modal_submit').prop('disabled', false);
            }
        });
    });

    // delete a row
    $(document).on('click', 'button[name=btn-delete]', function() {
        let id = $(this).data('id');
        Swal.fire({
            text: "Are you sure you want to delete this function?",
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
                        notification('warning', 'Warning!',
                            'Have trouble, try again later.');
                    }
                });
            } else if (result.dismiss === 'cancel') {
                Swal.fire({
                    text: "This function was not deleted.",
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

    //reset form
    function form_reset() {
        $('#kt_modal_add_modal_form, #kt_modal_edit_modal').trigger('reset');
        $('#kt_modal_add_modal, #kt_modal_edit_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    var key_name = $('input[name=permission_key]').val();
    var name = '';
    var count_add_per = 5;
    // enter key then change permission's name
    $(document).on('change', 'input[name=permission_key]', function () {
        name = $(this).val().toLowerCase();
        let per_view = $('#per_1');
        let per_create = $('#per_2');
        let per_edit = $('#per_3');
        let per_delete = $('#per_4');
        per_view.text(name + '_'+ per_view.text().split(/_(.+)/)[1]);
        per_create.text(name + '_'+ per_create.text().split(/_(.+)/)[1]);
        per_edit.text(name + '_'+ per_edit.text().split(/_(.+)/)[1]);
        per_delete.text(name + '_'+ per_delete.text().split(/_(.+)/)[1]);
        for (let i = 5; i <= count_add_per; i++) {
            let per_n = $('#per_'+i);
            per_n.text(name + '_'+ per_n.text().split(/_(.+)/)[1]);
        }
    });

    var add_more_per = '<div class="row">\n'+
                            '<input class="form-control form-control-solid col-md-9 w-75" placeholder="Ex: export" name="more_per" />\n'+
                                '<div class="check-add-permission col-md-3">\n'+
                                    '<i class="las la-check m-1" id="accept_create"></i>\n'+
                                    '<i class="las la-times m-1" id="close_create"></i>\n'+
                                '</div>\n'+
                        '</div>';
    
    // add more permission
    $(document).on('click', '#btn_add_more_permission', function () {
        $('#form_add_more_per').html(add_more_per);
    });

    var btn_more_per = '<button type="button" class="btn btn-sm btn-light-primary btn-add-more" id="btn_add_more_permission">\n'+
                            '<span class="svg-icon svg-icon-muted svg-icon-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">\n'+
                                    '<path opacity="0.3" d="M3 13V11C3 10.4 3.4 10 4 10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14H4C3.4 14 3 13.6 3 13Z" fill="black" />\n'+
                                    '<path d="M13 21H11C10.4 21 10 20.6 10 20V4C10 3.4 10.4 3 11 3H13C13.6 3 14 3.4 14 4V20C14 20.6 13.6 21 13 21Z" fill="black" />\n'+
                                '</svg></span>\n'+
                            'Add more permission\n'+
                        '</button>';
    // create new permission
    $(document).on('click', '#accept_create', function () {
        let per = $('input[name=more_per]').val();
        let new_checkbox = '<label class="form-check form-check-custom form-check-solid mb-5 col-md-6">\n'+
                                '<input class="form-check-input" type="checkbox" value="'+per+'" name="permissions_core[]" checked/>\n'+
                                '<span class="form-check-label" id="per_'+count_add_per+'">'+name+'_'+per+'</span>\n'+
                            '</label>';
        count_add_per++;
        $('#checkbox_permission').append(new_checkbox);
        $('#form_add_more_per').html(btn_more_per);
    });

    $(document).on('click', '#close_create', function () {
        $('#form_add_more_per').html(btn_more_per);
    });

    //block special char
    $('input[name=permission_key]').bind('keypress', function(event) {
        console.log(123);
        var regex = new RegExp("^[a-zA-Z0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
        event.preventDefault();
        return false;
        }
    });

    function split_string(params, special = true) {
        let split = params;
        if (special == true) {
            split = params.split(/_(.+)/)[1];
        }
        let list_array = {
            'view' : 'badge-light-primary',
            'create' : 'badge-light-success',
            'edit' : 'badge-light-info',
            'delete' : 'badge-light-danger',
        };
        if (list_array.hasOwnProperty(split)) {
            return '<span class="badge '+list_array[split]+' ms-2 mb-2">'+split+'</span>';
        }
        return '<span class="badge badge-light-dark ms-2 mb-2">'+split+'</span>';
    }
</script>
