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

        <div class="dashboard-group flex-wrap" id="dashboard-group">
            @php
                $arrBGColor = ['bg-dark','bg-warning','bg-primary','bg-secondary','bg-success','bg-danger','bg-info'];
            @endphp
            @foreach($report as $key=>$value)

                {{--                <div class="dashboard-item mb-2">--}}
                {{--                    <span class="dashboard-item_title">{{$value->name}} ({{$value->payment_transaction->count()}})</span>--}}
                {{--                    <span class="dashboard-item_number">{{number_format($value->payment_transaction->sum('amount'), 0, '', ',')}}</span>--}}
                {{--                </div>--}}

                <div class="col-xl-3">
                    <!--begin::Statistics Widget 5-->
                    <a href="#"
                       class="card {{$arrBGColor[$key>=count($arrBGColor)?($key%count($arrBGColor)):$key]}} hoverable card-xl-stretch mb-xl-8">
                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm008.svg-->
                            <span class="svg-icon svg-icon-gray-100 svg-icon-3x ms-n1">
								<img style="max-height: 50px;height: 50px" src="{{url('/payment_method/'.$value->icon)}}"
                                     alt="{{$value->name}}">
                            </span>
                            <!--end::Svg Icon-->
                            <div class="text-gray-100 fw-bold fs-2 mb-2 mt-5">
                                +{{number_format($value->payment_transaction->sum('amount'), 0, '', ',')}}</div>
                            <div class="fw-semibold text-gray-100">{{$value->name}}
                                ({{$value->payment_transaction->count()}})
                            </div>
                        </div>
                        <!--end::Body-->
                    </a>
                    <!--end::Statistics Widget 5-->
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
        const arrBGColor = ['bg-dark', 'bg-warning', 'bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-info'];

        // $("#daterangepicker_dashboard").daterangepicker();
        var start = moment().startOf('month');
        var end = moment().endOf('month');

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#daterangepicker_dashboard').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

        $('#daterangepicker_dashboard').change(function () {
            let time = $('#daterangepicker_dashboard').val();
            let date = time.split("-");
            let timeStart = date[0].replaceAll('/', '-').trim();
            let timeEnd = date[1].replaceAll('/', '-').trim();
            let html = ``;
            $.ajax({
                url: '{{route('dashboard.report_payment.date')}}',
                type: 'POST',
                data: {
                    'timeStart': timeStart,
                    'timeEnd': timeEnd,
                },
                datatype: 'json',
                success: function (data) {
                    if (data.type == 'success') {
                        $.each(data.content, function (key, value) {
                            let amount = 0;
                            if (value.payment_transaction) {
                                amount = value.payment_transaction.reduce((accumulator, currentValue) => {
                                    return accumulator += parseInt(currentValue.amount);
                                }, 0)
                            }
                            //             html += `<div class="dashboard-item mb-2">
                            //     <span class="dashboard-item_title">${value?.name} (${value.payment_transaction.length})</span>
                            //     <span class="dashboard-item_number">${amount.toLocaleString('en-US')}</span>
                            // </div>`;

                            html += `<div class="col-xl-3">
                    <a href="#"
                       class="card ${arrBGColor[key >= (arrBGColor.length) ? (key % (arrBGColor.length)) : key]} hoverable card-xl-stretch mb-xl-8">
                        <div class="card-body">
                            <span class="svg-icon svg-icon-gray-100 svg-icon-3x ms-n1">
								<img style="height: 50px" src="${'/payment_method/' + value.icon}" alt="${value.name}">
                            </span>
                            <div class="text-gray-100 fw-bold fs-2 mb-2 mt-5">+${amount.toLocaleString('en-US')}</div>
                            <div class="fw-semibold text-gray-100">${value?.name} (${value.payment_transaction.length})</div>
                        </div>
                    </a>
                </div>`;
                        });
                        $('#dashboard-group').html(html);
                        overwriteToats(data.type, data.title);
                    } else {
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
