<div class="modal fade" id="kt_modal_add_modal" data-backdrop="static" data-keyboard="false">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_modal_header">
                <!--begin::Modal title-->
                <h2 class="fw-bolder">Add a function</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div id="kt_modal_add_modal_close" class="btn btn-icon btn-sm btn-active-icon-primary close"
                    data-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                transform="rotate(-45 6 17.3137)" fill="black" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                fill="black" />
                        </svg>
                    </span>
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Form-->
            <form class="form" action="{{ route($func->route.".index") }}" id="kt_modal_add_modal_form">
                <input type="hidden" name="id" value="" />
                <!--begin::Modal body-->
                <div class="modal-body py-10 px-lg-17">
                    <!--begin::Scroll-->
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_modal_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_modal_header"
                        data-kt-scroll-wrappers="#kt_modal_add_modal_scroll" data-kt-scroll-offset="300px">
                        <!--begin::Input group-->
                        <div id="kt_modal_add_user_info" class="collapse show row g-9 mb-15">
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">Name</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" name="name"
                                    placeholder="Enter text..." />
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">Parent</label>
                                <!--end::Label-->
                                <!--begin::Select-->
                                <select class="form-select form-select-solid" name="parent">
                                    <option value="0">---</option>
                                    @foreach ($list_parent as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                <!--end::Select-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">Route</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" name="route"
                                    placeholder="Enter text..." />
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">Controller</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control form-control-solid" name="controller"
                                    placeholder="Enter text..." />
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">Icon</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" name="icon"
                                    placeholder="Enter icon tag..." />
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-bold mb-2">Status</label>
                                <!--end::Label-->
                                <!--begin::Select-->
                                <select class="form-select form-select-solid" name="status">
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                                <!--end::Select-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-3 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">Ordering</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="number" class="form-control form-control-solid" name="ordering" min="0"
                                    value="0" />
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <div class="fv-row mb-7" id="navbar_example">
                            <a href="javascript:0" class="menu-link" data-bs-toggle="tooltip"
                                data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right"
                                data-bs-original-title="" title="">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2" id="menu_icon">
                                        <span class="svg-icon svg-icon-muted svg-icon-2"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="19"
                                                viewBox="0 0 16 19" fill="none">
                                                <path
                                                    d="M12 0.400024H1C0.4 0.400024 0 0.800024 0 1.40002V2.40002C0 3.00002 0.4 3.40002 1 3.40002H12C12.6 3.40002 13 3.00002 13 2.40002V1.40002C13 0.800024 12.6 0.400024 12 0.400024Z"
                                                    fill="black"></path>
                                                <path opacity="0.3"
                                                    d="M15 8.40002H1C0.4 8.40002 0 8.00002 0 7.40002C0 6.80002 0.4 6.40002 1 6.40002H15C15.6 6.40002 16 6.80002 16 7.40002C16 8.00002 15.6 8.40002 15 8.40002ZM16 12.4C16 11.8 15.6 11.4 15 11.4H1C0.4 11.4 0 11.8 0 12.4C0 13 0.4 13.4 1 13.4H15C15.6 13.4 16 13 16 12.4ZM12 17.4C12 16.8 11.6 16.4 11 16.4H1C0.4 16.4 0 16.8 0 17.4C0 18 0.4 18.4 1 18.4H11C11.6 18.4 12 18 12 17.4Z"
                                                    fill="black"></path>
                                            </svg>
                                        </span>
                                    </span>
                                </span>
                                <span class="menu-title" id="menu_title">Function</span>
                            </a>
                        </div>
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="fs-6 fw-bold mb-2">Description
                                {{-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                    title="Email address must be active"></i> --}}
                            </label>
                            <!--end::Label-->
                            <!--begin::textarea-->
                            <textarea class="form-control form-control-solid rounded-3" name="description" rows="4"
                                placeholder="Enter text..."></textarea>
                            <!--end::textarea-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Scroll-->
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="reset" id="kt_modal_add_modal_cancel" class="btn btn-light me-3">Discard</button>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" id="kt_modal_add_modal_submit" class="btn btn-primary">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <!--end::Button-->
                </div>
                <!--end::Modal footer-->
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
