@extends('layouts.admin')

@section('styles')
    <link href="{{ asset('assets/admin/css/jquery-ui.css') }}" rel="stylesheet" type="text/css">
@endsection


@section('content')
    <div class="content-area">

        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Add New Coupon') }} 
						<a
							class="add-btn"
                            href="{{ route('admin-coupon-index') }}"
						>
							<i class="fas fa-arrow-left"></i>
                            {{ __('Back') }}
						</a>
					</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-coupon-index') }}">{{ __('Coupons') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-coupon-create') }}">{{ __('Add New Coupon') }}</a>
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
                            <div
								class="gocover"
                                style="background: url({{ asset('assets/images/' . $gs->admin_loader) }})
								no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            @include('includes.admin.form-both')
                            <form id="coupon" action="{{ route('admin-coupon-create') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('admin.coupon.common', ['coupon' => null])

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <button class="addProductSubmit-btn"
                                            type="submit">{{ __('Create Coupon') }}</button>
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
    <script type="text/javascript">
        {{-- Coupon Type --}}

        $('#type').on('change', function() {
            var val = $(this).val();
            var selector = $(this).parent().parent().next();
            if (val == "") {
                selector.hide();
            } else {
                if (val == 0) {
                    selector.find('.heading').html('{{ __('Percentage') }} *');
                    selector.find('input').attr("placeholder", "{{ __('Enter Percentage') }}").next().html('%');
                    selector.css('display', 'flex');
                } else if (val == 1) {
                    selector.find('.heading').html('{{ __('Amount') }} *');
                    selector.find('input').attr("placeholder", "{{ __('Enter Amount') }}").next().html('$');
                    selector.css('display', 'flex');
                }
            }
        });


        {{-- Coupon Qty --}}
        $(document).on("change", "#times", function() {
            var val = $(this).val();
            var selector = $(this).parent().parent().next();
            if (val == 1) {
                selector.css('display', 'flex');
            } else {
                selector.find('input').val("");
                selector.hide();
            }
        });
    </script>

    <script type="text/javascript">
        var dateToday = new Date();
        var dates = $("#from,#to").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            minDate: dateToday,
            onSelect: function(selectedDate) {
                var option = this.id == "from" ? "minDate" : "maxDate",
                    instance = $(this).data("datepicker"),
                    date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults
                        .dateFormat, selectedDate, instance.settings);
                dates.not(this).datepicker("option", option, date);
            }
        });
    </script>

    @include('admin.coupon.commonJs',['coupon' => null])
@endsection
