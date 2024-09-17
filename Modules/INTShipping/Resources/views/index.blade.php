@extends('backEnd.master')
@section('styles')

<link rel="stylesheet" href="{{asset(asset_path('modules/intshipping/css/product_add_modal.css'))}}" />

@endsection
@section('mainContent')
    <div id="add_product">
        <section class="admin-visitor-area up_st_admin_visitor">
            <div class="container-fluid p-0">
                <div class="row justify-content-center">
                    <div class="col-lg-12 student-details">
                        <div class="white_box_50px box_shadow_white" id="printableArea">
                            <div class="row pb-30 border-bottom">
                                <div class="col-md-6 col-lg-6">
                                       <h2>{{ __('intshipping.shipping_profile_zone_rate') }}</h2>
                                </div>
                                <div class="col-md-6 col-lg-6 text-right">
                                    @if(permissionCheck('intshipping.create'))
                                      <a href="{{route('intshipping.create')}}" class="primary-btn fix-gr-bg radius_30px mr-10"> <i class="ti-plus"></i> {{ __('common.add') }}</a>
                                    @endif
                                </div>
                            </div>

                            <div class="row mt-30">
                            <div class="col-lg-12">
                                <div class="QA_section">
                                    <div class="QA_table ">
                                        <!-- table-responsive -->
                                        <div class="table-responsive">
                                            <table class="table Crm_table_active3">
                                                <thead>
                                                <tr>
                                                    <th  width="20%" scope="col">{{ __('common.name') }}</th>
                                                    <th  width="20%" scope="col">{{ __('common.products') }}</th>
                                                    <th  width="40%" scope="col">{{ __('intshipping.zone') }}</th>
                                                    <th  width="20%" scope="col">{{ __('common.action') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($intshipping_profile_name as $profile_name)
                                                    <tr class="profile_start">
                                                        <td>{{$profile_name->name}}</td>
                                                        <td>{{$profile_name->products->count()}} {{ __('common.products') }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center flex-wrap">
                                                            @foreach($profile_name->zones as $zone)
                                                            <div class="round_flag_single mb-1"><span class="f_w_500 f_s_15">{{$zone->name}}</span></div>
                                                            @endforeach
                                                        </div>
                                                        </td>
                                                        <td>
                                                            <!-- shortby  -->
                                                            <div class="dropdown CRM_dropdown">
                                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    {{ __('common.select') }}
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
                                                                    @if(permissionCheck('intshipping.edit'))
                                                                        <a class="dropdown-item edit_profile" href="{{route('intshipping.edit',$profile_name->id)}}">{{__('common.edit')}}</a>
                                                                    @endif

                                                                    @if(permissionCheck('intshipping.destroy'))
                                                                        <a class="dropdown-item delete_profile" data-id="{{$profile_name->id}}">{{__('common.delete')}}</a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <!-- shortby  -->
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 student-details">

                    </div>
                </div>
            </div>
        </section>
    </div>
@include('backEnd.partials._deleteModalForAjax',['item_name' => __('intshipping.shipping_profile'),'form_id' =>
'profile_delete_form','modal_id' => 'profile_delete_modal', 'delete_item_id' => 'profile_delete_id'])
@endsection
@push('scripts')
    <script type="text/javascript">
        (function($){
            "use strict";
            $(document).ready(function(){
            let  INTShipProfile = new Object();
                INTShipProfile.deleteProfileNode = '';
             $(document).on('click', '.delete_profile', function(event){
                event.preventDefault();
                let id = $(this).data('id');
                INTShipProfile.deleteProfileNode = $(this);
                 $('#profile_delete_id').val(id);
                 $('#profile_delete_modal').modal('show');
             });
             $(document).on('submit', '#profile_delete_form', function(event) {
                 event.preventDefault();
                 $('#profile_delete_modal').modal('hide');
                 $('#pre-loader').removeClass('d-none');
                 var formData = new FormData();
                 formData.append('_token', "{{ csrf_token() }}");
                 formData.append('id', $('#profile_delete_id').val());
                 $.ajax({
                     url: "{{ route('intshipping.destroy') }}",
                     type: "POST",
                     cache: false,
                     contentType: false,
                     processData: false,
                     data: formData,
                     success: function(response) {
                        let row = INTShipProfile.deleteProfileNode;
                            row.closest('.profile_start').remove();
                         toastr.success("{{__('common.deleted_successfully')}}", "{{__('common.success')}}");
                         $('#pre-loader').addClass('d-none');
                     },
                     error: function(response) {
                         if(response.responseJSON.error){
                             toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                             $('#pre-loader').addClass('d-none');
                             return false;
                         }
                         toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                     }
                 });
             });
            });
        })(jQuery);
    </script>
@endpush
