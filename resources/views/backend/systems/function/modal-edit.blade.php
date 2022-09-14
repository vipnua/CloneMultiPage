<input type="hidden" name="id" value="{{$function->id}}" />
<!--begin::Modal body-->
<div class="modal-body py-10 px-lg-17">
    <!--begin::Scroll-->
    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_modal_scroll" data-kt-scroll="true"
        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
        data-kt-scroll-dependencies="#kt_modal_add_modal_header" data-kt-scroll-wrappers="#kt_modal_add_modal_scroll"
        data-kt-scroll-offset="300px">
        <!--begin::Input group-->
        <div id="kt_modal_add_user_info" class="collapse show row g-9 mb-15">
            <!--begin::Col-->
            <div class="col-md-6 fv-row">
                <!--begin::Label-->
                <label class="fs-6 fw-bold mb-2">Name</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" class="form-control form-control-solid" name="name" placeholder="Enter text..." value="{{$function->name}}"/>
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
                        <option value="{{ $item->id }}" @if ($item->id == $function->parent_id)
                            selected
                        @endif>{{ $item->name }}</option>
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
                <input type="text" class="form-control form-control-solid" name="route" placeholder="Enter text..." value="{{$function->route}}"/>
                <!--end::Input-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-md-6 fv-row">
                <!--begin::Label-->
                <label class="fs-6 fw-bold mb-2">Controller</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class="form-control form-control-solid" name="controller" placeholder="Enter text..." value="{{$function->controller}}"/>
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
                    placeholder="Enter icon tag..." value="{{html_entity_decode($function->icon)}}"/>
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
                    <option value="1" @if ($function->status == 1)
                        selected
                    @endif>Active</option>
                    <option value="2" @if ($function->status == 2)
                        selected
                    @endif>Inactive</option>
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
                <input type="number" class="form-control form-control-solid" name="ordering" min="0" value="{{$function->ordering}}"/>
                <!--end::Input-->
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->
        <div class="fv-row mb-7" id="navbar_example">
            <a href="javascript:0" class="menu-link" data-bs-toggle="tooltip" data-bs-trigger="hover"
                data-bs-dismiss="click" data-bs-placement="right" data-bs-original-title="" title="">
                <span class="menu-icon">
                    <span class="svg-icon svg-icon-2" id="menu_icon">
                        {!! html_entity_decode($function->icon) !!}
                    </span>
                </span>
                <span class="menu-title" id="menu_title">{{$function->name}}</span>
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
                placeholder="Enter text...">{{$function->description}}</textarea>
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
