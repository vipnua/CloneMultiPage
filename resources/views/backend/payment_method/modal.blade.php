@php
    $countrys = ['VN', 'ALL'];
@endphp
<div class="modal fade" id="kt_modal_add_modal" data-backdrop="static" data-keyboard="false">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_modal_header">
                <!--begin::Modal title-->
                <h2 class="fw-bolder">Add a Employee</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
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
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Form-->
            <form class="form" action="{{ route($func->route.".index") }}" id="kt_modal_add_modal_form">
                <input type="hidden" name="id" value=""/>
                <!--begin::Modal body-->
                <div class="modal-body py-10 px-lg-17">
                    <!--begin::Scroll-->
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_modal_scroll" data-kt-scroll="true"
                         data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                         data-kt-scroll-dependencies="#kt_modal_add_modal_header"
                         data-kt-scroll-wrappers="#kt_modal_add_modal_scroll" data-kt-scroll-offset="300px">
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            {{--                            <label class="fs-6 fw-bold mb-2">--}}
                            {{--                                <span class="required">Type</span>--}}
                            {{--                            </label>--}}
                            <!--end::Label-->
                            <!--begin::Input-->
                            {{--                            <div class="btn-group w-100" data-kt-buttons="true"--}}
                            {{--                                 data-kt-buttons-target="[data-kt-button]">--}}
                            {{--                                <!--begin::Radio-->--}}
                            {{--                                <label--}}
                            {{--                                    class="btn btn-outline-secondary text-muted text-hover-white text-active-white btn-outline btn-active-success payment_method_type active"--}}
                            {{--                                    data-kt-button="true"--}}
                            {{--                                    for="paymentMethodBank"--}}
                            {{--                                >--}}
                            {{--                                    <!--begin::Input-->--}}
                            {{--                                    <input class="btn-check" type="radio" name="type" value="bank"--}}
                            {{--                                           id="paymentMethodBank">--}}
                            {{--                                    <!--end::Input-->--}}
                            {{--                                    Bank</label>--}}
                            {{--                                <!--end::Radio-->--}}
                            {{--                                <!--begin::Radio-->--}}
                            {{--                                <label--}}
                            {{--                                    class="btn btn-outline-secondary text-muted text-hover-white text-active-white btn-outline btn-active-success payment_method_type"--}}
                            {{--                                    data-kt-button="true"--}}
                            {{--                                    for="paymentMethodPaypal"--}}
                            {{--                                >--}}
                            {{--                                    <!--begin::Input-->--}}
                            {{--                                    <input class="btn-check" type="radio" name="type" id="paymentMethodPaypal"--}}
                            {{--                                           value="paypal">--}}
                            {{--                                    <!--end::Input-->--}}
                            {{--                                    Paypal</label>--}}
                            {{--                                <!--end::Radio-->--}}
                            {{--                            </div>--}}
                            <!--end::Input-->
                            {{--                        </div>--}}
                            <div class="mb-10">
                                <label for="exampleFormControlInput1" class="form-label required">Type</label>
                                <select class="form-select form-select-solid" aria-label="Select example" name="type">
                                    <option value="bank">Bank</option>
                                    <option value="paypal">Paypal</option>
                                </select>
                            </div>

                            <div class="mb-10">
                                <label for="exampleFormControlInput1" class="form-label required">Country</label>
                                <select class="form-select form-select-solid" aria-label="Select example"
                                        name="country">
                                    @foreach($countrys as $country)
                                        <option value={{$country}}>{{$country}}</option>
                                    @endforeach
                                    {{--                                    <option value="ALL">All</option>--}}
                                </select>
                            </div>
                            <!--status-->
                            <div class="mb-10">
                                <label for="exampleFormControlInput1" class="form-label required">Status</label>
                                <select class="form-select form-select-solid" aria-label="Select example" name="status">
                                    <option value="enable">Enable</option>
                                    <option value="disable">Disable</option>
                                </select>
                            </div>

                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">
                                    <span class="required">Bank name</span>
                                    {{-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                        title="Email address must be active"></i> --}}
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" name="bank_name"
                                       placeholder="Enter bank name..."/>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Group type bank-->
                            <div id="group_type_bank">
                                <!--begin::Input group-->

                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2">
                                        <span class="required">Branch name</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid" name="branch_name"
                                           placeholder="Enter bank number..."/>
                                    <!--end::Input-->
                                </div><!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2">
                                        <span class="required">Account number</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="number" class="form-control form-control-solid" name="account_number"
                                           placeholder="Enter account number..."/>
                                    <!--end::Input-->
                                </div>
                                <div class="fv-row mb-15">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2 w-100">
                                        <span class="required">Image Bank</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <!--begin::Image input-->
                                    <div class="image-input image-input-empty" data-kt-image-input="true"
                                         style="background-image: url(/assets/media/avatars/blank.png)">
                                        <!--begin::Image preview wrapper-->
                                        <div id="background_image" class="image-input-wrapper w-125px h-125px"></div>
                                        <!--end::Image preview wrapper-->

                                        <!--begin::Edit button-->
                                        <label
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                                                data-kt-image-input-action="change"
                                                data-bs-toggle="tooltip"
                                                data-bs-dismiss="click"
                                                title="Change avatar">
                                            <i class="bi bi-pencil-fill fs-7"></i>

                                            <!--begin::Inputs-->
                                            <input type="file" name="icon" accept=".png, .jpg, .jpeg"/>
                                            <input type="hidden" name="avatar_remove"/>
                                            <!--end::Inputs-->
                                        </label>
                                        <!--end::Edit button-->

                                        <!--begin::Cancel button-->
                                        <span
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                                                data-kt-image-input-action="cancel"
                                                data-bs-toggle="tooltip"
                                                data-bs-dismiss="click"
                                                title="Cancel avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                        <!--end::Cancel button-->

                                        <!--begin::Remove button-->
                                        <span
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                                                data-kt-image-input-action="remove"
                                                data-bs-toggle="tooltip"
                                                data-bs-dismiss="click"
                                                title="Remove avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                        <!--end::Remove button-->
                                    </div>
                                    <!--end::Image input-->
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <div id="group_type_paypal">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2">
                                        <span class="required">Client key</span>
                                        {{-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                            title="Email address must be active"></i> --}}
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid" name="client_id"
                                           placeholder="Enter client key..."/>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2">
                                        <span class="required">Secret key</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid" name="secret_key"
                                           placeholder="Enter secret key..."/>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold mb-2">
                                        <span class="required">Sandbox</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::select-->
                                    <select class="form-select form-select-solid" aria-label="Select example"
                                            name="paypal_sandbox">
                                        <option value="enable">Enable</option>
                                        <option value="disable">Disable</option>
                                    </select>
                                    <!--end::select-->
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>
                        <!--end::Group type bank-->
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
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
