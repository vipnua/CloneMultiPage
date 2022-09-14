@extends('backend.layouts.main')
@section('title')
    {{ $func ? $func->name : '' }}
@endsection
@section('header_title')
    <!--begin::Title-->
    <h1 class="d-flex flex-column text-dark fw-bolder fs-3 mb-0">Dashboard</h1>
    <!--end::Title-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 pt-1">
        <!--begin::Item-->
        <li class="breadcrumb-item text-dark">Dashboard</li>
        <!--end::Item-->
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('content')
    <div class="dashboard-page">
        <div class="daterangepicker_dashboard-group">
            <input class="form-control" placeholder="Pick date rage" id="daterangepicker_dashboard"/>
        </div>

        <div class="dashboard-group" id="dashboard-group">
            @foreach($report as $key=>$value)
                <div class="dashboard-item">
                    <span class="dashboard-item_title">{{$key}}</span>
                    <span class="dashboard-item_number">{{$value}}</span>
                </div>
            @endforeach
        </div>
    </div>
    <div class="position-fixed top-0 end-0 p-3 z-index-3" style="z-index: 999 !important;">
        <div id="dashboard_toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <span class="svg-icon svg-icon-2 svg-icon-primary me-3">...</span>
                <strong class="me-auto" id="toast_title">MinhHoangJSC</strong>
                <small id="toast_time">11 mins ago</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toast_message">
                Hello, world! This is a toast message.
            </div>
        </div>
    </div>

@endsection
@section('link-script')
    <script>
        const toastElement = document.getElementById('dashboard_toast');
        const toast = bootstrap.Toast.getOrCreateInstance(toastElement);


        $("#daterangepicker_dashboard").daterangepicker();
        $('#daterangepicker_dashboard').change(function () {
            let time = $('#daterangepicker_dashboard').val();
            let date = time.split("-");
            let timeStart = date[0].replaceAll('/', '-').trim();
            let timeEnd = date[1].replaceAll('/', '-').trim();
            console.log(date);
            let html = ``;
            $.ajax({
                url: '{{route('dashboard.date')}}',
                type: 'POST',
                data: {
                    'timeStart':timeStart,
                    'timeEnd':timeEnd,
                },
                datatype: 'json',
                success: function (data) {
                    if (data.type == 'success') {
                        $.each(data.content, function (key, value) {
                            html += `<div class="dashboard-item">
                    <span class="dashboard-item_title">${key ?? 0}</span>
                    <span class="dashboard-item_number">${value ?? 0}</span>
                </div>`;
                        });
                    $('#dashboard-group').html(html);
                    overwriteToats(data.type, data.title);
                    }else{
                        overwriteToats('error', 'There was an error while executing, please try again.');
                    }
                    toast.show();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    overwriteToats('error', 'There was an error while executing, please try again.');
                    toast.show();
                }
            });
        });

        function overwriteToats(title, message) {
            $('#dashboard_toast').removeClass('success').removeClass('error').addClass(title);
            $('#toast_title').html(capitalizeFirstLetter(title));
            $('#toast_message').html(message);
            $('#toast_time').html(new Date().toLocaleString());
        }

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

    </script>
@endsection
