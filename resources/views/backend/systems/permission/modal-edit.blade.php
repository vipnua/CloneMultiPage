<input type="hidden" name="id" value="{{$permission->id}}">
<!--begin::Input group-->
<div class="fv-row mb-7">
    <!--begin::Label-->
    <label class="fs-6 fw-bold form-label mb-2">
        <span class="required">Group Permission Name</span>
    </label>
    <!--end::Label-->
    <!--begin::Input-->
    <input class="form-control form-control-solid" placeholder="Enter a group permission name" name="permission_name" value="{{$permission->name}}"/>
    <!--end::Input-->
</div>
<!--end::Input group-->
<!--begin::Input group-->
<div class="fv-row mb-7">
    <!--begin::Label-->
    <label class="fs-6 fw-bold form-label mb-2">
        Description
    </label>
    <!--end::Label-->
    <!--begin::Input-->
    <textarea class="form-control form-control-solid" name="description" cols="1" rows="3" placeholder="Enter a description">{{$permission->description}}</textarea>
    <!--end::Input-->
</div>
<!--end::Input group-->
<!--begin::Input group-->
<div class="fv-row mb-7">
    <!--begin::Label-->
    <label class="fs-6 fw-bold form-label mb-2">
        <span>New permission</span>
    </label>
    <!--end::Label-->
    <!--begin::Input-->
    <input class="form-control form-control-solid" placeholder="Enter permission name" name="permission_key" />
    <!--end::Input-->
</div>
<!--end::Input group-->
<!--begin::Actions-->
<div class="text-center pt-15">
    <button type="reset" class="btn btn-light me-3" data-kt-permissions-modal-action="cancel"
        id="kt_modal_add_modal_cancel">Discard</button>
    <button type="submit" class="btn btn-primary" data-kt-permissions-modal-action="submit"
        id="kt_modal_add_modal_submit">
        <span class="indicator-label">Submit</span>
        <span class="indicator-progress">Please wait...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
    </button>
</div>
<!--end::Actions-->
