@extends('layouts.admin')
     
@section('styles')

<style type="text/css">
    .order-table-wrap table#example2 {
    margin: 10px 20px;
}

</style>

@endsection


@section('content')
    <div class="content-area">
                        <div class="mr-breadcrumb">
                            <div class="row">
                                <div class="col-lg-12">
                                        <h4 class="heading">{{ __('Product Details') }} <a class="add-btn" href="javascript:history.back();"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                                        <ul class="links">
                                            <li>
                                                <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">{{ __('Products') }}</a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">{{ __('Product Details') }}</a>
                                            </li>
                                        </ul>
                                </div>
                            </div>
                        </div>

                        <div class="order-table-wrap">
                            @include('alerts.admin.form-both')
                            @include('alerts.form-success')
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="special-box">
                                        <div class="heading-area">
                                            <h4 class="title">
                                            Order Details
                                            </h4>
                                        </div>
                                        <div class="table-responsive-sm">
                                            <span class="badge badge-danger">Unpaid</span><table class="table">
                                                <tbody>
                                                <tr>
                                                    <th class="45%" width="45%">Order ID</th>
                                                    <td width="10%">:</td>
                                                    <td class="45%" width="45%">e5Pz1685264634</td>
                                                </tr>
                                                <tr>
                                                    <th width="45%">Total Product</th>
                                                    <td width="10%">:</td>
                                                    <td width="45%">1</td>
                                                </tr>
                                                                                                    <tr>
                                                        <th width="45%">Shipping Method</th>
                                                        <td width="10%">:</td>
                                                        <td width="45%">Free Shipping</td>
                                                    </tr>
                                                
                                                
                                                
                                                                                                    <tr>
                                                        <th width="45%">Packaging Method</th>
                                                        <td width="10%">:</td>
                                                        <td width="45%">Default Packaging</td>
                                                    </tr>
                                                
                                                

                                                
                                                <tr>
                                                    <th width="45%">Total Cost</th>
                                                    <td width="10%">:</td>
                                                    <td width="45%">34$</td>
                                                </tr>
                                                <tr>
                                                    <th width="45%">Ordered Date</th>
                                                    <td width="10%">:</td>
                                                    <td width="45%">28-May-2023 03:03:54 am</td>
                                                </tr>
                                                <tr>
                                                    <th width="45%">Payment Method</th>
                                                    <td width="10%">:</td>
                                                    <td width="45%">Cash On Delivery</td>
                                                </tr>
                
                                                

                                                    <tr><th width="45%">Payment Status</th>
                                                    <th width="10%">:</th>

                                                                                                            
                                                    
                                                
                                                </tr></tbody>
                                            </table>
                                        </div>
                                        <div class="footer-area">
                                            <a href="http://localhost/admin/order/270/invoice" class="mybtn1"><i class="fas fa-eye"></i> View Invoice</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    </div>

{{-- LICENSE MODAL --}}

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

    <div class="modal-header d-block text-center">
        <h4 class="modal-title d-inline-block">{{ __('License Key') }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
    </div>

                <div class="modal-body">
                    <p class="text-center">{{ __('The Licenes Key is') }} :  <span id="key"></span> <a href="javascript:;" id="license-edit">{{ __('Edit License') }}</a><a href="javascript:;" id="license-cancel" class="showbox">{{ __('Cancel') }}</a></p>
                    <form method="POST" action="{{route('admin-order-license',$order->id)}}" id="edit-license" style="display: none;">
                        {{csrf_field()}}
                        <input type="hidden" name="license_key" id="license-key" value="">
                        <div class="form-group text-center">
                    <input type="text" name="license" placeholder="{{ __('Enter New License Key') }}" style="width: 40%; border: none;" required=""><input type="submit" name="submit" class="btn btn-primary" style="border-radius: 0; padding: 2px; margin-bottom: 2px;">
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>



{{-- LICENSE MODAL ENDS --}}

{{-- BILLING DETAILS EDIT MODAL --}}

@include('admin.order.partials.billing-details')

{{-- BILLING DETAILS MODAL ENDS --}}

{{-- SHIPPING DETAILS EDIT MODAL --}}

@include('admin.order.partials.shipping-details')

{{-- SHIPPING DETAILS MODAL ENDS --}}

{{-- ADD PRODUCT MODAL --}}

@include('admin.order.partials.add-product')

{{-- ADD PRODUCT MODAL ENDS --}}


{{--  EDIT PRODUCT MODAL --}}

<div class="modal fade" id="edit-product-modal" tabindex="-1" role="dialog" aria-labelledby="edit-product-modal" aria-hidden="true">
																		
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="submit-loader">
                <img  src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">
            </div>
            <div class="modal-header">
                <h5 class="modal-title">
                    {{ __('Edit Item') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ __('Close') }}
                </button>
            </div>
        </div>
    </div>

</div>

{{--  EDIT PRODUCT MODAL ENDS --}}

{{-- DELETE PRODUCT MODAL --}}

<div class="modal fade" id="delete-product-modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
  
      <div class="modal-header d-block text-center">
          <h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
      </div>
  
        <!-- Modal body -->
        <div class="modal-body">
              <p class="text-center">{{ __('You are about to delete this item from this cart.') }}</p>
              <p class="text-center">{{ __('Do you want to proceed?') }}</p>
        </div>
  
        <!-- Modal footer -->
        <div class="modal-footer justify-content-center">
              <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
              <a class="btn btn-danger btn-ok">{{ __('Delete') }}</a>

        </div>
  
      </div>
    </div>
  </div>
  
  {{-- DELETE PRODUCT MODAL ENDS --}}



{{-- MESSAGE MODAL --}}
<div class="sub-categori">
    <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vendorformLabel">{{ __('Send Email') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
            <div class="modal-body">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="contact-form">
                                <form id="emailreply">
                                    {{csrf_field()}}
                                    <ul>
                                        <li>
                                            <input type="email" class="input-field eml-val" id="eml" name="to" placeholder="{{ __('Email') }} *" value="" required="">
                                        </li>
                                        <li>
                                            <input type="text" class="input-field" id="subj" name="subject" placeholder="{{ __('Subject') }} *" required="">
                                        </li>
                                        <li>
                                            <textarea class="input-field textarea" name="message" id="msg" placeholder="{{ __('Your Message') }} *" required=""></textarea>
                                        </li>
                                    </ul>
                                    <button class="submit-btn" id="emlsub" type="submit">{{ __('Send Email') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

{{-- MESSAGE MODAL ENDS --}}

{{-- ORDER MODAL --}}

<div class="modal fade" id="confirm-delete2" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="submit-loader">
            <img  src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">
        </div>
    <div class="modal-header d-block text-center">
        <h4 class="modal-title d-inline-block">{{ __('Update Status') }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
    </div>

      <!-- Modal body -->
      <div class="modal-body">
        <p class="text-center">{{ __("You are about to update the order's status.") }}</p>
        <p class="text-center">{{ __('Do you want to proceed?') }}</p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
            <a class="btn btn-success btn-ok order-btn">{{ __('Proceed') }}</a>
      </div>

    </div>
  </div>
</div>

{{-- ORDER MODAL ENDS --}}


@endsection


@section('scripts')

<script type="text/javascript">

(function($) {
		"use strict";

  function disablekey()
  {
    document.onkeydown = function (e)
    {
        return false;
    }
  }

  function enablekey()
  {
    document.onkeydown = function (e)
    {
        return true;
    }
  }

$('#example2').dataTable( {
  "ordering": false,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,
      'info'        : false,
      'autoWidth'   : false,
      'responsive'  : true
} );

     $(document).on('click','.license' , function(e){
        var id = $(this).parent().find('input[type=hidden]').val();
        var key = $(this).parent().parent().find('input[type=hidden]').val();
        $('#key').html(id);  
        $('#license-key').val(key);    
    });
    $(document).on('click','#license-edit' , function(e){
        $(this).hide();
        $('#edit-license').show();
        $('#license-cancel').show();
    });
    $(document).on('click','#license-cancel' , function(e){
        $(this).hide();
        $('#edit-license').hide();
        $('#license-edit').show();
    });

    @if(Session::has('license'))

    $.notify('{{  Session::get('license')  }}','success');

    @endif

// ADD OPERATION

    $(document).on('click','.edit-product',function(){

        if(admin_loader == 1)
        {
            $('.submit-loader').show();
        }
        $('#edit-product-modal .modal-content .modal-body').html('').load($(this).data('href'),function(response, status, xhr){
            if(status == "success")
            {
                if(admin_loader == 1)
                {
                    $('.submit-loader').hide();
                }
            }
        });
    });

// ADD OPERATION END

// SHOW PRODUCT FORM SUBMIT

$(document).on('submit','#show-product',function(e){
  e.preventDefault();
  if(admin_loader == 1)
  {
    $('.submit-loader').show();
  }
    $('button.addProductSubmit-btn').prop('disabled',true);
    disablekey();
      $.ajax({
       method:"POST",
       url:$(this).prop('action'),
       data:new FormData(this),
       dataType:'JSON',
       contentType: false,
       cache: false,
       processData: false,
       success:function(data)
       {
        if(data[0]){
            $('#product-show').html('').load(mainurl+"/admin/order/product-show/"+data[1],function(response, status, xhr){
                if(status == "success")
                {
                    if(admin_loader == 1)
                    {
                        $('.submit-loader').hide();
                    }
                }
            });
        }
        else{
            if(admin_loader == 1)
            {
                $('.submit-loader').hide();
            }
            $('#product-show').html('<div class="col-lg-12 text-center"><h4>'+data[1]+'.</h4></div>')
        }

        $('button.addProductSubmit-btn').prop('disabled',false);

        enablekey();
       }

      });

});

// SHOW PRODUCT FORM SUBMIT ENDS


$('#delete-product-modal').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
  });

})(jQuery);

    </script>

@endsection