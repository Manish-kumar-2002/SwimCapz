@extends('layouts.admin')

@section('content')

<div class="content-area">
              <div class="mr-breadcrumb">
                <div class="row">
                  <div class="col-lg-12">
                      <h4 class="heading">{{ __('Website Maintenance') }}</h4>
                    <ul class="links">
                      <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                      </li>
                      <li>
                        <a href="javascript:;">{{ __('General Settings') }}</a>
                      </li>
                      <li>
                        <a href="javascript:;">{{ __('Website Maintenance') }}</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="add-product-content1 add-product-content2">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                        <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                        <form id="geniusformdata" action="{{ route('admin-gs-update') }}" method="POST" enctype="multipart/form-data">
                          @csrf

                        @include('alerts.admin.form-both')  


                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                              <div class="left-area">
                                <h4 class="heading">
                                    {{ __('Website Maintenance') }}
                                </h4>
                              </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="action-list">
                                    <select class="process select droplinks status-change {{ $gs->is_maintain  == 1 ? 'drop-success' : 'drop-danger' }}">
                                      <option data-val="1" value="{{route('admin-gs-status',['is_maintain',1])}}" {{ $gs->is_maintain == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                                      <option data-val="0" value="{{route('admin-gs-status',['is_maintain',0])}}" {{ $gs->is_maintain == 0 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                    </select>
                                  </div>
                            </div>
                          </div>

                          <div class="row justify-content-center">
                              <div class="col-lg-3">
                                <div class="left-area">
                                  <h4 class="heading">
                                      {{ __('Maintenance Text') }} *
                                      <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                                  </h4>
                                </div>
                              </div>
                              <div class="col-lg-6">
                                  <div class="tawk-area">
                                    <textarea class="nic-edit" name="maintain_text" required="">{{ $gs->maintain_text }}</textarea>
                                  </div>
                              </div>
                            </div>


                        <div class="row justify-content-center">
                          <div class="col-lg-3">
                            <div class="left-area">
                              
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
                          </div>
                        </div>
                     </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

@endsection
@section('scripts')
<script>
$(document).ready(function() {
    $('.status-change').on('change', function() {
        var selectedOption = $(this).find('option:selected').data('val');
        if (selectedOption == 1) {
            $(this).removeClass('drop-danger').addClass('drop-success');
        } else if (selectedOption == 0) {
            $(this).removeClass('drop-success').addClass('drop-danger');
        }
    });

    // Initial check to apply class based on initial value
    var initialSelectedOption = $('.status-change').find('option:selected').data('val');
    if (initialSelectedOption == 1) {
        $('.status-change').removeClass('drop-danger').addClass('drop-success');
    } else if (initialSelectedOption == 0) {
        $('.status-change').removeClass('drop-success').addClass('drop-danger');
    }
});
</script>
@endsection