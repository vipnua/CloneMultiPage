@extends('backend.layouts.main')
@section('title')
    Profile
@endsection
@section('link-style')
    <style>
        i.fab {
            color: #474747;
            font-size: 20px;
        }
        textarea {
            resize: none;
        }
        .td-uuid {
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection
@section('header_title')
    <!--begin::Title-->
    <h1 class="d-flex flex-column text-dark fw-bolder fs-3 mb-0">Customer Details</h1>
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
        @if ($status_delete == 1)
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('user.index') }}" class="text-muted text-hover-primary">Customer</a>
            </li>
            <!--end::Item-->
        @else
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('bin.index') }}" class="text-muted text-hover-primary">Recycle bin</a>
            </li>
            <!--end::Item-->
        @endif
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-200 w-5px h-2px"></span>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item text-dark">Customer Details</li>
        <!--end::Item-->
    </ul>
    <!--end::Breadcrumb-->
@endsection
@section('link-style')
    <!-- DataTables -->
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    @include('backend.customer.css')
@endsection
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Layout-->
                <div class="d-flex flex-column flex-xl-row">
                    <!--begin::Sidebar-->
                    <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                        <!--begin::Card-->
                        <div class="card mb-5 mb-xl-8">
                            <!--begin::Card body-->
                            <div class="card-body pt-15">
                                <!--begin::Summary-->
                                <div class="d-flex flex-center flex-column mb-5">
                                    <!--begin::Avatar-->
                                    <div class="symbol symbol-100px symbol-circle mb-7">
                                        <img src="{{ asset('assets/custom/image/avatar/nv-khac-1.png') }}" alt="image" />
                                    </div>
                                    <!--end::Avatar-->
                                    <!--begin::Name-->
                                    <a href="#"
                                        class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-1">{{ $user->name }}</a>
                                    <!--end::Name-->
                                    <!--begin::Position-->
                                    <div class="fs-5 fw-bold text-muted mb-6">{{ $user->email }}</div>
                                    <!--end::Position-->
                                </div>
                                <!--end::Summary-->
                                <!--begin::Details toggle-->
                                <div class="d-flex flex-stack fs-4 py-3">
                                    <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse"
                                        href="#kt_customer_view_details" role="button" aria-expanded="false"
                                        aria-controls="kt_customer_view_details">Details
                                        <span class="ms-2 rotate-180">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                        fill="black" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </div>
                                    <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit customer details">
                                        <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_update_customer">Edit</a>
                                    </span>
                                </div>
                                <!--end::Details toggle-->
                                <div class="separator separator-dashed my-3"></div>
                                <!--begin::Details content-->
                                <div id="kt_customer_view_details" class="collapse show">
                                    <div class="py-5 fs-6">
                                        <!--begin::Badge-->
                                        <div class="badge badge-light-info d-inline">Premium user</div>
                                        <!--begin::Badge-->
                                        <!--begin::Details item-->
                                        <div class="fw-bolder mt-5">Account ID</div>
                                        <div class="text-gray-600">{{ $user->uuid }}</div>
                                        <!--begin::Details item-->
                                        <!--begin::Details item-->
                                        <div class="fw-bolder mt-5">Billing Email</div>
                                        <div class="text-gray-600">
                                            <a href="#" class="text-gray-600 text-hover-primary">info@keenthemes.com</a>
                                        </div>
                                        <!--begin::Details item-->
                                        <!--begin::Details item-->
                                        <div class="fw-bolder mt-5">Billing Address</div>
                                        <div class="text-gray-600">101 Collin Street,
                                            <br />Melbourne 3000 VIC
                                            <br />Australia
                                        </div>
                                        <!--begin::Details item-->
                                        <!--begin::Details item-->
                                        <div class="fw-bolder mt-5">Language</div>
                                        <div class="text-gray-600">English</div>
                                        <!--begin::Details item-->
                                        <!--begin::Details item-->
                                        <div class="fw-bolder mt-5">Upcoming Invoice</div>
                                        <div class="text-gray-600">54238-8693</div>
                                        <!--begin::Details item-->
                                        <!--begin::Details item-->
                                        <div class="fw-bolder mt-5">Tax ID</div>
                                        <div class="text-gray-600">TX-8674</div>
                                        <!--begin::Details item-->
                                    </div>
                                </div>
                                <!--end::Details content-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Sidebar-->
                    <!--begin::Content-->
                    <div class="flex-lg-row-fluid ms-lg-15">
                        <!--begin:::Tabs-->
                        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4 active" data-kt-countup-tabs="true"
                                    data-bs-toggle="tab" href="#kt_customer_view_overview_statements">Profiles</a>
                            </li>
                            <!--end:::Tab item-->
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true"
                                   data-bs-toggle="tab" href="#kt_customer_view_payment">Plan</a>
                            </li>
                            <!--end:::Tab item-->
                            <!--begin:::Tab item-->
                            <li class="nav-item ms-auto box-status-profile">
                                <!--begin::Action menu-->
                                <form id="status-form" action="{{route("user.changestatus")}}" method="post">
                                    @method('PUT')
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $user->id }}">
                                    <input type="hidden" name="status" value="">
                                    @if ($status_delete == 1)
                                        <div class="block-box d-inline">
                                            @if ($status_block == 1)
                                                <a href="#" class="btn btn-warning ps-7 me-3" name="btn-status-block" data-status="1" data-id="{{ $user->id }}">Block</a>
                                            @else
                                                <a href="#" class="btn btn-success ps-7 me-3" name="btn-status-block" data-status="2" data-id="{{ $user->id }}">Unblock</a>
                                            @endif
                                        </div>
                                            <button type="submit" class="btn btn-danger ps-7 me-3" name="btn-status" data-status="delete">Delete</button>
                                    @else
                                        <button type="submit" class="btn btn-info ps-7 me-3" name="btn-status" data-status="restore">Restore</button>
                                        <button type="submit" class="btn btn-danger ps-7 me-3" name="btn-status" data-status="forcedelete">Force Delete</button>
                                    @endif
                                </form>
                                <!--end::Menu-->
                            </li>
                            <!--end:::Tab item-->
                        </ul>
                        <!--end:::Tabs-->
                        <!--begin:::Tab content-->
                        <div class="tab-content" id="myTabContent">
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show active" id="kt_customer_view_overview_statements"
                                role="tabpanel">
                                <!--begin::Statements-->
                                <div class="card mb-6 mb-xl-9">
                                    <!--begin::Header-->
                                    <div class="card-header">
                                        <!--begin::Title-->
                                        <div class="card-title">
                                            <h2>Profiles</h2>
                                        </div>
                                        <!--end::Title-->
                                        <!--begin::Toolbar-->
                                        <div class="card-toolbar">
                                            <!--begin::Tab nav-->
                                            <!--begin::Filter-->
                                            <button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click"
                                                data-kt-menu-placement="bottom-end">
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                                                <span class="svg-icon svg-icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                                            fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->Filter
                                            </button>
                                            <!--begin::Menu 1-->
                                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px"
                                                data-kt-menu="true" id="kt-toolbar-filter">
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
                                                        <label class="form-label fs-5 fw-bold mb-3">Search name:</label>
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
                                                        <label class="form-label fs-5 fw-bold mb-3">OS Type:</label>
                                                        <!--end::Label-->
                                                        <!--begin::Options-->
                                                        <div class="d-flex flex-column flex-wrap fw-bold"
                                                            data-kt-customer-table-filter="os_search">
                                                            <!--begin::Option-->
                                                            <label
                                                                class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                                <input class="form-check-input" type="radio"
                                                                    name="os_search" value="all" checked="checked" />
                                                                <span class="form-check-label text-gray-600">All</span>
                                                            </label>
                                                            <!--end::Option-->
                                                            <!--begin::Option-->
                                                            <label
                                                                class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                                <input class="form-check-input" type="radio"
                                                                    name="os_search" value="lin" />
                                                                <span class="form-check-label text-gray-600">Linux</span>
                                                            </label>
                                                            <!--end::Option-->
                                                            <!--begin::Option-->
                                                            <label
                                                                class="form-check form-check-sm form-check-custom form-check-solid mb-3">
                                                                <input class="form-check-input" type="radio"
                                                                    name="os_search" value="win" />
                                                                <span class="form-check-label text-gray-600">Window</span>
                                                            </label>
                                                            <!--end::Option-->
                                                            <!--begin::Option-->
                                                            <label
                                                                class="form-check form-check-sm form-check-custom form-check-solid">
                                                                <input class="form-check-input" type="radio"
                                                                    name="os_search" value="mac" />
                                                                <span class="form-check-label text-gray-600">MacOS</span>
                                                            </label>
                                                            <!--end::Option-->
                                                        </div>
                                                        <!--end::Options-->
                                                    </div>
                                                    <!--end::Input group-->
                                                    <!--begin::Actions-->
                                                    <div class="d-flex justify-content-end">
                                                        <button type="reset"
                                                            class="btn btn-light btn-active-light-primary me-2"
                                                            data-kt-menu-dismiss="true"
                                                            data-kt-customer-table-filter="reset">Reset</button>
                                                        <button type="submit" class="btn btn-primary"
                                                            data-kt-menu-dismiss="true"
                                                            data-kt-customer-table-filter="filter">Apply</button>
                                                    </div>
                                                    <!--end::Actions-->
                                                </form>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Menu 1-->
                                            <!--end::Filter-->
                                            <!--end::Tab nav-->
                                        </div>
                                        <!--end::Toolbar-->
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pb-5">
                                        <!--begin::Tab Content-->
                                        <div id="kt_customer_view_statement_tab_content" class="tab-content">
                                            <!--begin::Tab panel-->
                                            <div id="kt_customer_view_statement_1" class="py-0 tab-pane fade show active"
                                                role="tabpanel">
                                                <!--begin::Table-->
                                                <table id="kt_customer_view_profile_table"
                                                    class="table align-middle table-row-dashed fs-6 text-gray-600 fw-bold gy-4">
                                                    <!--begin::Table head-->
                                                    <thead class="border-bottom border-gray-200">
                                                        <!--begin::Table row-->
                                                        <tr
                                                            class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                            <th class="w-50px">#</th>
                                                            <th class="w-200px">name</th>
                                                            <th class="w-100px">browser</th>
                                                            <th class="w-100px">version</th>
                                                            <th class="w-100px">os</th>
                                                            <th class="w-100px text-between pe-7">action</th>
                                                        </tr>
                                                        <!--end::Table row-->
                                                    </thead>
                                                    <!--end::Table head-->
                                                    <!--begin::Table body-->
                                                    <tbody>
                                                    </tbody>
                                                    <!--end::Table body-->
                                                </table>
                                                <!--end::Table-->
                                            </div>
                                            <!--end::Tab panel-->
                                            <!--begin::Tab panel-->
                                            <div id="kt_customer_view_statement_2" class="py-0 tab-pane fade"
                                                role="tabpanel">
                                                <!--begin::Table-->
                                                <table id="kt_customer_view_statement_table_2"
                                                    class="table align-middle table-row-dashed fs-6 text-gray-600 fw-bold gy-4">
                                                    <!--begin::Table head-->
                                                    <thead class="border-bottom border-gray-200">
                                                        <!--begin::Table row-->
                                                        <tr
                                                            class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                            <th class="w-125px">Date</th>
                                                            <th class="w-100px">Order ID</th>
                                                            <th class="w-300px">Details</th>
                                                            <th class="w-100px">Amount</th>
                                                            <th class="w-100px text-end pe-7">Invoice</th>
                                                        </tr>
                                                        <!--end::Table row-->
                                                    </thead>
                                                    <!--end::Table head-->
                                                    <!--begin::Table body-->
                                                    <tbody>
                                                    </tbody>
                                                    <!--end::Table body-->
                                                </table>
                                                <!--end::Table-->
                                            </div>
                                            <!--end::Tab panel-->
                                        </div>
                                        <!--end::Tab Content-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Statements-->
                            </div>
                            <!--end:::Tab pane-->
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show" id="kt_customer_view_payment"
                                 role="tabpanel">
                                <!--begin::Card-->
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <!--begin::Card header-->
                                    <div class="card-header border-0">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h2>Plan Records</h2>
                                        </div>
                                        <!--end::Card title-->
                                        <!--begin::Card toolbar-->
                                        <div class="card-toolbar">
                                            <!--begin::Filter-->
                                            <button type="button" class="btn btn-sm btn-flex btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_plan_modal" id="add_plan">
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen035.svg-->
                                                <span class="svg-icon svg-icon-3">
																<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																	<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="black" />
																	<rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="black" />
																	<rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="black" />
																</svg>
															</span>
                                                <!--end::Svg Icon-->Add plan</button>
                                            <!--end::Filter-->
                                        </div>
                                        <!--end::Card toolbar-->
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-0 pb-5">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed gy-5" id="kt_customer_view_charge_table">
                                            <!--begin::Table head-->
                                            <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
                                            <!--begin::Table row-->
                                            <tr class="text-start text-muted text-uppercase gs-0">
                                                <th class="w-10px pe-2">#</th>
{{--                                                <th class="min-w-100px">Invoice No.</th>--}}
                                                <th class="min-w-100px">Name</th>
{{--                                                <th>Status</th>--}}
                                                <th>Amount</th>
{{--                                                <th>Currency</th>--}}
                                                <th class="text-center min-w-100px">Payment date</th>
                                                <th class="text-center min-w-100px">Expires on</th>
                                                <th class="text-end min-w-100px pe-4">Actions</th>
                                            </tr>
                                            <!--end::Table row-->
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody class="fs-6 fw-bold text-gray-600">
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Card-->
                            </div>
                            <!--end:::Tab pane-->
                        </div>
                        <!--end:::Tab content-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Layout-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->
    @include('backend.customer.profile.modal')
    @include('backend.customer.profile.plan.modal-add-plan')
    @include('backend.customer.profile.plan.modal-detail')
@endsection
@section('link-script')
    <!-- DataTables -->
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/clipboard/dist/clipboard.min.js') }}"></script>
    <!--begin::Page Custom Javascript(used by this page)-->
    {{-- <script src="{{asset('assets/js/custom/apps/customers/view/add-payment.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/customers/view/adjust-balance.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/customers/view/invoices.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/customers/view/payment-method.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/customers/view/payment-table.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/customers/update.js')}}"></script>
    <script src="{{asset('assets/js/custom/modals/new-card.js')}}"></script>
    <script src="{{asset('assets/js/custom/widgets.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/chat/chat.js')}}"></script> --}}
    <!--end::Page Custom Javascript-->
    @include('backend.customer.profile.js')
    @include('backend.customer.profile.plan.plan-js')
@endsection
