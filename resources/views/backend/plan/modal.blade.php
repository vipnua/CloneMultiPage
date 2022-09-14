<div class="modal fade" id="kt_modal_add_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-1000px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_modal_header">
                <h2 class="fw-bolder">Add a Employee</h2>
                <div id="kt_modal_add_modal_close" class="btn btn-icon btn-sm btn-active-icon-primary close"
                     data-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                  transform="rotate(-45 6 17.3137)" fill="black"/>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"/>
                        </svg>
                    </span>
                </div>
            </div>

            <form class="form" action="{{ route($func->route.".index") }}" id="kt_modal_add_modal_form">
                @csrf
                <input type="hidden" name="id" value=""/>
                <input type="hidden" name="uuid" value=""/>
                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_modal_scroll" data-kt-scroll="true"
                         data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                         data-kt-scroll-dependencies="#kt_modal_add_modal_header"
                         data-kt-scroll-wrappers="#kt_modal_add_modal_scroll" data-kt-scroll-offset="300px">
                        {{-- input name--}}
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">
                                <span class="required">Name</span>
                            </label>
                            <input type="text" class="form-control form-control-solid" name="name" placeholder="Enter name..."/>
                        </div>
                        {{--input price--}}
                        {{-- input status--}}
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">
                                <span class="required">Status</span>
                            </label>
                            <select class="form-select form-select-solid" aria-label="Select example" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
{{--                            <input type="text" class="form-control form-control-solid" name="status" placeholder="Enter status..."/>--}}
                        </div>
                        {{--input status--}}
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">
                                <span class="required">Price</span>
                                {{-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                    title="Email address must be active"></i> --}}
                            </label>
                            <input type="number" class="form-control form-control-solid" step="0.01" name="price" placeholder="Enter price..."/>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">
                                <span class="required">Price Vnd</span>
                                {{-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                    title="Email address must be active"></i> --}}
                            </label>
                            <input type="number" class="form-control form-control-solid" min="0" name="price_vn" placeholder="Enter price vnd..."/>
                        </div>
                        {{--input interval--}}
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">
                                <span class="required">Interval</span>
                            </label>
                            <input type="number" class="form-control form-control-solid" min="0" name="interval" placeholder="Enter interval..."/>
                        </div>
                        {{--input profile--}}
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">
                                <span class="required">Profile</span>
                            </label>
                            <input type="number" class="form-control form-control-solid" min="0" name="profile" placeholder="Enter profile..."/>
                        </div>
                        {{--input profile share--}}
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">
                                <span class="required">Profile Share</span>
                            </label>
                            <input type="number" class="form-control form-control-solid" min="0" name="profile_share" placeholder="Enter profile share..."/>
                        </div>

                        {{--input interval type--}}
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">
                                <span class="required">Interval Type</span>
                            </label>
                            <input type="number" class="form-control form-control-solid" min="0" name="interval_type" placeholder="Enter interval type..."/>
                        </div>
                        <div class="fv-row mb-7">
{{--                            <div class="rounded border d-flex flex-column p-10">--}}
                                <label for="" class="form-label">Describe</label>
{{--                                <textarea id="textAreaPlan" class="form-control form-control-solid" name="describe" data-kt-autosize="true" style="overflow: hidden; overflow-wrap: break-word; resize: none; height: 65px;" data-kt-initialized="1"></textarea>--}}
{{--                            </div>--}}
{{--                            <textarea name="kt_docs_ckeditor_classic" id="ckeditor_add_plan"></textarea>--}}
{{--                            <textarea id="textAreaPlan" name="editordata"></textarea>--}}
{{--                            <div id="textAreaPlan"></div>--}}
                            <textarea id="textAreaPlan" name="describe"></textarea>
                        </div>
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
