<div class="modal fade" id="kt_modal_add_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-700px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_modal_header">
                <h2 class="fw-bolder">Add a Employee</h2>
                <div id="kt_modal_add_modal_close" class="btn btn-icon btn-sm btn-active-icon-primary close"
                     data-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                  transform="rotate(-45 6 17.3137)" fill="black"/>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                  fill="black"/>
                        </svg>
                    </span>
                </div>
            </div>

            <form class="form" action="{{ route($func->route.".index") }}" id="kt_modal_add_modal_form">
                @csrf
                <input type="hidden" name="id" value=""/>
                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_modal_scroll" data-kt-scroll="true"
                         data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                         data-kt-scroll-dependencies="#kt_modal_add_modal_header"
                         data-kt-scroll-wrappers="#kt_modal_add_modal_scroll" data-kt-scroll-offset="300px">
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="fs-6 fw-bold mb-2">
                                <span class="required">Name</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" class="form-control form-control-solid" name="name"
                                   placeholder="Enter name..."/>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="fs-6 fw-bold mb-2">
                                <span class="required">Code</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Row-->
                            <div class="d-flex">
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid me-5" name="code"
                                       placeholder="Enter code..."/>
                                <!--end::Input-->
                                <!--begin::Button-->
                                <a href="#" class="btn btn-light-success text-nowrap px-3" id="random_key">Random <i
                                        class="las la-sync"></i></a>
                                <!--end::Button-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="fs-6 fw-bold mb-2">
                                <span class="required">Max use</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="number" class="form-control form-control-solid" name="max_use" value="0"/>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin:Option-->
                            <label class="d-flex mb-5 cursor-pointer">
                                <!--begin:Input-->
                                <span class="form-check form-check-custom form-check-solid me-5">
                                    <input class="form-check-input check-data" data-check="profile" type="checkbox" name="profile_cb" value="1"/>
                                </span>
                                <!--end:Input-->
                                <div class="d-flex align-items-center me-5">
                                    <!--begin:Info-->
                                    <input type="number" class="form-control form-control-solid w-300px text-data" name="profile" min="0" step="1"
                                           value="0"/>
                                    <!--end:Info-->
                                </div>
                                <!--begin:Label-->
                                <span class="d-flex align-items-center me-2">
                                    <!--begin:Info-->
                                    <span class="d-flex flex-column">
                                        <span class="fw-bolder fs-6">Profile</span>
                                        <span
                                            class="fs-7 text-muted">Số lượng profile theo %</span>
                                    </span>
                                    <!--end:Info-->
                                </span>
                                <!--end:Label-->
                            </label>
                            <!--end::Option-->
                            <!--begin:Option-->
                            <label class="d-flex mb-5 cursor-pointer">
                                <!--begin:Input-->
                                <span class="form-check form-check-custom form-check-solid me-5">
                                    <input class="form-check-input check-data" data-check="share" type="checkbox" name="share_cb" value="1"/>
                                </span>
                                <!--end:Input-->
                                <div class="d-flex align-items-center me-5">
                                    <!--begin:Info-->
                                    <input type="number" class="form-control form-control-solid w-300px text-data" name="share" min="0" step="1"
                                           value="0"/>
                                    <!--end:Info-->
                                </div>
                                <!--begin:Label-->
                                <span class="d-flex align-items-center me-2">
                                    <!--begin:Info-->
                                    <span class="d-flex flex-column">
                                        <span class="fw-bolder fs-6">Share</span>
                                        <span
                                            class="fs-7 text-muted">Số lượng profile share theo %</span>
                                    </span>
                                    <!--end:Info-->
                                </span>
                                <!--end:Label-->
                            </label>
                            <!--end::Option-->
                            <!--begin:Option-->
                            <label class="d-flex mb-5 cursor-pointer">
                                <!--begin:Input-->
                                <span class="form-check form-check-custom form-check-solid me-5">
                                    <input class="form-check-input check-data" data-check="date" type="checkbox" name="date_cb" value="1"/>
                                </span>
                                <!--end:Input-->
                                <div class="d-flex align-items-center me-5">
                                    <!--begin:Info-->
                                    <input type="number" class="form-control form-control-solid w-300px text-data" name="date" min="0" step="1"
                                           value="0"/>
                                    <!--end:Info-->
                                </div>
                                <!--begin:Label-->
                                <span class="d-flex align-items-center me-2">
                                    <!--begin:Info-->
                                    <span class="d-flex flex-column">
                                        <span class="fw-bolder fs-6">Date</span>
                                        <span
                                            class="fs-7 text-muted">Số ngày sử dụng theo %</span>
                                    </span>
                                    <!--end:Info-->
                                </span>
                                <!--end:Label-->
                            </label>
                            <!--end::Option-->
                            <!--begin:Option-->
                            <label class="d-flex mb-5 cursor-pointer">
                                <!--begin:Input-->
                                <span class="form-check form-check-custom form-check-solid me-5">
                                    <input class="form-check-input check-data" data-check="price" type="checkbox" name="price_cb" value="1"/>
                                </span>
                                <!--end:Input-->
                                <div class="d-flex align-items-center me-5">
                                    <!--begin:Info-->
                                    <input type="number" class="form-control form-control-solid w-300px text-data" name="price" min="0" max="100" step="1"
                                           value="0"/>
                                    <!--end:Info-->
                                </div>
                                <!--begin:Label-->
                                <span class="d-flex align-items-center me-2">
                                    <!--begin:Info-->
                                    <span class="d-flex flex-column">
                                        <span class="fw-bolder fs-6">Price</span>
                                        <span
                                            class="fs-7 text-muted">Số tiền giảm %</span>
                                    </span>
                                    <!--end:Info-->
                                </span>
                                <!--end:Label-->
                            </label>
                            <!--end::Option-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div id="kt_modal_add_admin_info" class="collapse show row g-9 mb-15">
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-bold mb-2">Date from</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="date" class="form-control form-control-solid" name="date_from"
                                       placeholder="Enter text..."/>
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-bold mb-2">Expired on</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="date" class="form-control form-control-solid" name="date_to"
                                       placeholder="Enter text..."/>
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>

                {{--Submit--}}
                <div class="modal-footer flex-center">
                    <button type="reset" id="kt_modal_add_modal_cancel" class="btn btn-light me-3">Discard</button>
                    <button type="submit" id="kt_modal_add_modal_submit" class="btn btn-primary">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
