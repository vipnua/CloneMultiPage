
@extends('backend.layouts.main')
@section('title')
    {{ $func ? $func->name : '' }}
@endsection
@section('header_title')
    <!--begin::Title-->
    <h1 class="d-flex flex-column text-dark fw-bolder fs-3 mb-0">Payment Method</h1>
    <!--end::Title-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 pt-1">
        <!--begin::Item-->
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-200 w-5px h-2px"></span>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item text-dark">Payment Method</li>
        <!--end::Item-->
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('link-style')
    <!-- DataTables -->
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    @include('backend.payment_method.css')
@endsection
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                        </div>
                        <!--begin::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                <!--begin::Filter-->
                                <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none">
                                            <path
                                                d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                                fill="black" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->Filter
                                </button>
                                <!--begin::Menu 1-->
                                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true"
                                    id="kt-toolbar-filter">
                                    <!--begin::Header-->
                                    <div class="px-7 py-5">
                                        <div class="fs-4 text-dark fw-bolder">Filter Options</div>
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Separator-->
                                    <div class="separator border-gray-200"></div>
                                    <!--end::Separator-->
                                    <!--begin::Content-->
                                    <form id="filter_form" class="px-7 py-5">
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <!--begin::Label-->
                                            <label class="form-label fs-5 fw-bold mb-3">Search
                                                {{ $func ? $func->name : '' }}:</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" class="form-control" name="name_search"
                                                placeholder="Enter a text...">
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <!--begin::Label-->
                                            <label class="form-label fs-5 fw-bold mb-3">Status:</label>
                                            <!--end::Label-->
                                            <!--begin::Options-->
                                            <div class="d-flex flex-column flex-wrap fw-bold"
                                                data-kt-customer-table-filter="status_search">
                                                <!--begin::Option-->
                                                <label
                                                    class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                    <input class="form-check-input" type="radio" name="status_search"
                                                        value="all" checked="checked" />
                                                    <span class="form-check-label text-gray-600">All</span>
                                                </label>
                                                <!--end::Option-->
                                                <!--begin::Option-->
                                                <label
                                                    class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                    <input class="form-check-input" type="radio" name="status_search"
                                                        value="1" />
                                                    <span class="form-check-label text-gray-600">Enable</span>
                                                </label>
                                                <!--end::Option-->
                                                <!--begin::Option-->
                                                <label
                                                    class="form-check form-check-sm form-check-custom form-check-solid mb-3">
                                                    <input class="form-check-input" type="radio" name="status_search"
                                                        value="0" />
                                                    <span class="form-check-label text-gray-600">Disable</span>
                                                </label>
                                                <!--end::Option-->
                                                <!--begin::Option-->
{{--                                                <label--}}
{{--                                                    class="form-check form-check-sm form-check-custom form-check-solid mb-3">--}}
{{--                                                    <input class="form-check-input" type="radio" name="status_search"--}}
{{--                                                        value="3" />--}}
{{--                                                    <span class="form-check-label text-gray-600">Waiting</span>--}}
{{--                                                </label>--}}
                                                <!--end::Option-->
                                            </div>
                                            <!--end::Options-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Actions-->
                                        <div class="d-flex justify-content-end">
                                            <button type="reset" class="btn btn-light btn-active-light-primary me-2"
                                                data-kt-menu-dismiss="true"
                                                data-kt-customer-table-filter="reset">Reset</button>
                                            <button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true"
                                                data-kt-customer-table-filter="filter">Apply</button>
                                        </div>
                                        <!--end::Actions-->
                                    </form>
                                    <!--end::Content-->
                                </div>
                                <!--end::Menu 1-->
                                <!--end::Filter-->
                                <!--begin::Add customer-->
                                @can('create', 'App\User')
                                    <button type="button" class="btn btn-primary me-3" id="add_modal">Add
                                        {{ $func ? $func->name : '' }}</button>
                                @endcan
                                <!--end::Add customer-->
                                <!--begin::Trash-->
{{--                                @can('forceDelete', 'App\User')--}}
{{--                                    <a href="{{route("bin.index")}}" class="btn btn-warning"><i class="fas fa-trash"></i>Bin</a>--}}
{{--                                @endcan--}}
                                <!--end::Trash-->
                            </div>
                            <!--end::Toolbar-->
                            <!--begin::Group actions-->
                            <div class="d-flex justify-content-end align-items-center d-none"
                                data-kt-customer-table-toolbar="selected">
                                <div class="fw-bolder me-5">
                                    <span class="me-2"
                                        data-kt-customer-table-select="selected_count"></span>Selected
                                </div>
                                <button type="button" class="btn btn-danger"
                                    data-kt-customer-table-select="delete_selected">Delete Selected</button>
                            </div>
                            <!--end::Group actions-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_modal_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">
                                        #
                                    </th>
                                    <th class="text-center min-w-125px">Method</th>
                                    <th class="text-center min-w-125px">Country</th>
                                    <th class="text-center min-w-125px">Icon</th>
                                    <th class="text-center min-w-125px">Name</th>
                                    <th class="text-center min-w-125px">Info</th>
                                    <th class="text-center min-w-125px">Status</th>
                                    <th class="text-center min-w-70px">Actions</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-bold text-gray-600">
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
                <!--begin::Modals-->
                <!--begin::Modal - Customers - Add-->
                @include('backend.payment_method.modal')
                <!--end::Modal - Customers - Add-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
@endsection
@section('link-script')
    <!-- DataTables -->
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/clipboard/dist/clipboard.min.js') }}"></script>
    @include('backend.payment_method.js')
@endsection
