@php
$currencies = ['VND', 'USD'];
//$currencies = ['VND', 'USD', 'SGD', 'MYR'];
@endphp

<div class="modal fade" id="kt_modal_add_plan_modal" data-backdrop="static" data-keyboard="false">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-1000px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_plan_modal_header">
                <!--begin::Modal title-->
                <div class="d-flex align-items-center">
                    <h2 class="fw-bolder">Add plan</h2>
                </div>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div id="kt_modal_add_plan_modal_close" class="btn btn-icon btn-sm btn-active-icon-primary close"
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
            <!--begin::Modal body-->
            <div class="modal-body py-10 px-lg-17">
                <div class="row g-5 g-xl-8">
                    <!--begin::Col-->
                    <div class="col-xl-8">
                        <!--begin::Tables Widget 5-->
                        <div class="card card-xl-stretch">
                            <div class="mb-10">
                                <label for="exampleFormControlInput1" class="form-label required">Plan</label>
                                <select class="form-select form-select-solid" aria-label="Select example"
                                        name="select-plan">
                                    <option value="0">Choose a plan</option>
                                    @foreach($plans as $plan)
                                        <option value="{{$plan->id}}" data-data="{{$plan}}">{{$plan->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-10">
                                <label class="form-label required">Transaction ID</label>
                                <div class="d-flex" style="gap: 1rem;">
                                    <input type="text" class="form-control form-control-solid" name="transaction_id"
                                           placeholder="" style=" width: inherit; flex-grow: 1;">
                                    <button id="btn-auto-generate" class="btn btn-light-primary">Auto generate</button>
                                </div>
                            </div>
                            <div class="mb-10">
                                <div class="d-flex" style="gap: 1rem;">
                                    <div style="flex-grow: 1">

                                        <label class="form-label required">Amount</label>
                                        <input type="number" class="form-control form-control-solid" name="amount"
                                               placeholder="1000000">
                                    </div>
                                    <div>
                                        <label class="form-label required">Currency</label>
                                        <select class="form-select form-select-solid" aria-label="Select example"
                                                style="width: inherit"
                                                name="currency">
                                            <option value="0">Choose a currency</option>
                                            @foreach($currencies as $key =>$value)
                                                <option value={{$value}}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-10">
                                <label for="exampleFormControlInput1" class="form-label required">Payment method</label>
                                <select class="form-select form-select-solid" aria-label="Select example"
                                        name="select-paymentMethod">
                                    <option value="0">Choose a payment method</option>
                                    @foreach($paymentMethods as $paymentMethod)
                                        <option value="{{$paymentMethod->id}}"
                                                data-paymentMethod="{{$paymentMethod}}">{{$paymentMethod->name}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="pb-5">
                                <label for="" class="form-label">Note</label>
                                <textarea id="txtNote" class="form-control form-control form-control-solid textarea"
                                          data-kt-autosize="true"
                                          style="overflow: hidden; overflow-wrap: break-word; resize: none; height: 64px;"></textarea>
                            </div>

                        </div>
                        <!--end::Tables Widget 5-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-xl-4" style="border-left: 1px solid #eff2f5;">
                        <!--begin::List Widget 1-->
                        <div class="card card-xl-stretch preview-show">

                            <div class="" style="min-height: 50px;">
                                <label class="form-label">Plan Name</label>
                                <p id="plan-name-preview"></p>
                            </div>
                            <div class="" style="min-height: 50px;">
                                <label class="form-label">Interval</label>
                                <p id="plan-interval-preview"></p>
                            </div>
                            <div class="" style="min-height: 50px;">
                                <label class="form-label">Profile</label>
                                <p id="plan-profile-preview"></p>
                            </div>
                            <div class="" style="min-height: 50px;">
                                <label class="form-label">Profile share</label>
                                <p id="plan-profile-share-preview"></p>
                            </div>
                            <div class="" style="min-height: 50px;">
                                <label class="form-label">Price</label>
                                <p id="plan-price-preview"></p>
                            </div>
                            <div class="" style="min-height: 50px;">
                                <label class="form-label">Price VN</label>
                                <p id="plan-price-vn-preview"></p>
                            </div>
                            <div class="py-5">
                                <label for="" class="form-label">Plan Description</label>
                                <div id="plan-description-preview" style="overflow: hidden;"></div>
                            </div>

                        </div>
                        <div class="card card-xl-stretch preview-hide align-items-center justify-content-center">

                            <h3 class="text-muted">Show info plan</h3>

                        </div>
                        <!--end::List Widget 1-->
                    </div>
                    <!--end::Col-->
                </div>
                <a href="#"
                   class="position-sm-relative m-2 m-sm-0 top-0 end-0 btn ms-sm-auto btn-primary btn_select_plan">
                    <span>Add plan</span>
                </a>
                {{--                @foreach($plans as $plan)--}}
                {{--                    <!--begin::Alert-->--}}
                {{--                    <div class="alert alert-dismissible bg-light-primary d-flex flex-column flex-sm-row p-5 mb-10">--}}
                {{--                        <!--begin::Wrapper-->--}}
                {{--                        <div class="d-flex flex-column pe-0 pe-sm-10">--}}
                {{--                            <!--begin::Title-->--}}
                {{--                            <h4 class="fw-bold">{{$plan->name}}</h4>--}}
                {{--                            <!--end::Title-->--}}
                {{--                            <!--begin::Content-->--}}
                {{--                            <span class="hiden-text-30vw" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{$plan->describe}}">{{$plan->describe}}</span>--}}
                {{--                            <!--end::Content-->--}}
                {{--                        </div>--}}
                {{--                        <!--end::Wrapper-->--}}
                {{--    --}}
                {{--                        <!--begin::Close-->--}}
                {{--                        <a href="#"--}}
                {{--                           class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn ms-sm-auto btn-primary btn_select_plan" data-id="{{$plan->id}}">--}}
                {{--                            <span>Select</span>--}}
                {{--                        </a>--}}
                {{--                        <!--end::Close-->--}}
                {{--                    </div>--}}
                {{--                    <!--end::Alert-->--}}
                {{--                @endforeach--}}
            </div>
            <!--end::Modal body-->
        </div>
    </div>
</div>
