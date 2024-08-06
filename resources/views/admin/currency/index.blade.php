@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('CURRENCY') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Currencies') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>

                        <li>
                            <a href="javascript:;">{{ __('Payment Settings') }}</a>
                        </li>

                        <li>
                            <a href="{{ route('admin-currency-index') }}">{{ __('Currencies') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="heading-area d-flex">
                        <h4 class="title">
                            {{ __('Currency') }} :
                        </h4>
                        <div class="action-list">
                            <select
                                class="process select droplinks mt-2 status-change {{ $gs->is_currency == 1 ? 'drop-success' : 'drop-danger' }}">
                                <option data-val="1" value="{{ route('admin-gs-status', ['is_currency', 1]) }}"
                                    {{ $gs->is_currency == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option data-val="0" value="{{ route('admin-gs-status', ['is_currency', 0]) }}"
                                    {{ $gs->is_currency == 0 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="mr-table allproduct">
                        @include('alerts.admin.form-success')
                        <div class="table-responsive">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Sign') }}</th>
                                        <th>{{ __('Value') }}</th>
										<th>{{ __('Icon') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ADD / EDIT MODAL --}}

    <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">


        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="submit-loader">
                    <img src="{{ asset('assets/images/' . $gs->admin_loader) }}" alt="">
                </div>
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ADD / EDIT MODAL ENDS --}}


    {{-- DELETE MODAL --}}

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
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
                    <p class="text-center">{{ __('You are about to delete this Currency.') }}</p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <form action="" class="d-inline delete-form" method="POST">
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- DELETE MODAL ENDS --}}
@endsection



@section('scripts')
    <script type="text/javascript">
        (function($) {
            "use strict";

            var table = $('#geniustable').DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin-currency-datatables') }}',
                dom: '<"top" <"row" <"col-md-6" f><"col-md-6"<"btn-area">>>>rt<"bottom"lp>',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'sign',
                        name: 'sign'
                    },
                    {
                        data: 'value',
                        name: 'value'
                    },
					{
                        data: 'icon',
                        name: 'icon'
                    },
                    {
                        data: 'action',
                        searchable: false,
                        orderable: false
                    }

                ],
                language: {
                    processing: '<img src="{{ asset('assets/images/' . $gs->admin_loader) }}">'
                }
            });

            $(function() {
                $(".btn-area").append('<div class="table-contents">' +
                    '<a class="add-btn" data-href="{{ route('admin-currency-create') }}" id="add-data" data-toggle="modal" data-target="#modal1">' +
                    '<i class="fas fa-plus"></i> {{ __('Add New Currency') }}' +
                    '</a>' +
                    '</div>');
            });

        })(jQuery);


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
