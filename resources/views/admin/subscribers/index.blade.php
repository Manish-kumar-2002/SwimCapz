@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Subscribers') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Others') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-subs-index') }}">{{ __('Subscribers') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        @include('alerts.admin.form-both')
                        <div class="table-responsive">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 20px !important;">{{ __('SN') }}</th>
                                        <th>{{ __('Email') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('scripts')
    <script type="text/javascript">
        (function($) {
            "use strict";

            $('#geniustable').DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin-subs-datatables') }}',
                dom: '<"top" <"row" <"col-md-6" f><"col-md-6"<"btn-area">>>>rt<"bottom"lp>',
                columns: [{
                        data: null,
                        name: 'id',
                        render: function(data, type, row, meta) {
                            var table = $('#geniustable').DataTable();
                            var pageInfo = table.page.info();
                            return pageInfo.start + meta.row +
                            1;
                        }
                    },
                    {
                        data: 'email',
                        name: 'email'
                    }
                ],
                language: {
                    processing: '<img src="{{ asset('assets/images/' . $gs->admin_loader) }}">'
                }
            });

            $(function() {
                $(".btn-area").append('<div class="table-contents">' +
                    '<a class="add-btn" href="{{ route('admin-subs-download') }}">' +
                    '<i class="fa fa-download"></i> {{ __('Download') }}' +
                    '</a>' +
                    '</div>');
            });

        })(jQuery);
    </script>
@endsection
