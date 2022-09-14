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
                         {{-- input status--}}
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">
                                <span class="">status</span>
                            </label>
                            <input type="text" class="form-control form-control-solid" name="status" readonly/>
                                
                        </div>
                        {{-- input uuid--}}
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">
                                <span class="">uuid</span>
                            </label>
                            <input type="text" class="form-control form-control-solid" name="uuid" readonly/>
                        </div>
                        {{-- input userid--}}
                        <div class="fv-row mb-7">
                        <label class="fs-6 fw-bold mb-2">
                                <span class="">user_id</span>
                            </label>
                            <input type="text" class="form-control form-control-solid" name="user_id" readonly/>    
                        </div>
                        {{--input type--}}
                        <div class="fv-row mb-7">
                        <label class="fs-6 fw-bold mb-2">
                                <span class="">Type</span>
                            </label>
                            <input type="text" class="form-control form-control-solid" name="type" readonly/>    
                        </div>
                        {{--input content--}}
                        <div class="form-group shadow-textarea fv-row mb-7 fw-bold">
                        <label class="fs-6 fw-bold mb-2" for="content">Content</label>
                      <textarea class="form-control z-depth-1" id="textAreaPlan" name="content" rows="3" placeholder="Here is content" readonly></textarea>
                        </div>   
                        {{--input date from--}}
                        <div class="fv-row mb-7">
                        <label class="fs-6 fw-bold mb-2">
                                <span class="">date_from</span>
                            </label>
                            <input type="text" class="form-control form-control-solid" name="date_from" readonly/>    
                        </div>
                        {{--input date to--}}
                        <div class="fv-row mb-7">
                        <label class="fs-6 fw-bold mb-2">
                                <span class="">date_to</span>
                            </label>
                            <input type="text" class="form-control form-control-solid" name="date_to" readonly/>    
                        </div>
                    </div>
                </div>

                {{--Submit--}}
                <div class="modal-footer flex-center">
                    <button type="reset" id="kt_modal_add_modal_cancel" class="btn btn-light me-3">Discard</button>
                    <button type="submit" id="kt_modal_add_modal_submit" class="btn btn-primary">
                        <span class="indicator-label">Seen</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
