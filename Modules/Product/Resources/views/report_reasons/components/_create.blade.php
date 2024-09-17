<div class="main-title d-md-flex form_div_header">
    <h3 class="mb-3 mr-30 mb_xs_15px mb_sm_20px">{{__('product.add_report_reason')}} </h3>
    {{-- @if (permissionCheck('product.bulk_category_upload_page'))
        <ul class="d-flex">
            <li><a class="primary-btn radius_30px mr-10 fix-gr-bg" href="{{ route('product.bulk_category_upload_page') }}"><i class="ti-plus"></i>{{ __('product.bulk_category_upload') }}</a></li>
        </ul>
    @endif --}}
</div>
@if(isModuleActive('FrontendMultiLang'))
@php
$LanguageList = getLanguageList();
@endphp
@endif
<form method="POST" action="{{ route('product.report.store') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" id="add_category_form">
    <div class="white-box">
        <div class="add-visitor">
            <div class="row">
                <div class="col-lg-12">
                    <div class="primary_input mb-25">
                        <label class="primary_input_label" for="name">
                            {{__('common.name')}}
                            <span class="text-danger">*</span>
                        </label>
                        <input class="primary_input_field name" type="text" id="name" name="name" autocomplete="off"  placeholder="{{__('common.name')}}">
                        @csrf
                        <span class="text-danger" id="error_name"></span>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="primary_input">
                        <label class="primary_input_label" for="">Status</label>
                        <ul id="theme_nav" class="permission_list sms_list ">
                            <li>
                                <label data-id="bg_option" class="primary_checkbox d-flex mr-12 extra_width">
                                    <input name="status" id="status_active" value="1" checked="true" class="active" type="radio">
                                    <span class="checkmark"></span>
                                </label>
                                <p>Active</p>
                            </li>
                            <li>
                                <label data-id="color_option" class="primary_checkbox d-flex mr-12 extra_width">
                                    <input name="status" value="0" id="status_inactive" class="de_active" type="radio">
                                    <span class="checkmark"></span>
                                </label>
                                <p>Inactive</p>
                            </li>
                        </ul>
                        <span class="text-danger" id="error_status"></span>
                    </div>
                </div>
            </div>
            <div class="row mt-40">
                <div class="col-lg-12 text-center">
                    <button id="create_btn" type="submit" class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip" data-original-title=""><span class="ti-check"></span>{{__('common.save')}} </button>
                </div>
            </div>
        </div>
    </div>
</form>
