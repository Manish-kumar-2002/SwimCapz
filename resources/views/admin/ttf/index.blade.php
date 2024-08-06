@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('TTF') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('TTF Files') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('ttf.index') }}">{{ __('TTF Files') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">

                        @if (Session::has('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                {{ Session::get('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Font Name') }}</th>
                                        <th>{{ __('Preview') }}</th>
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
@endsection

@section('scripts')
    {{-- DATA TABLE --}}

    <script type="text/javascript">
        (function($) {
            "use strict";

            var table = $('#geniustable').DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('ttf.index') }}',
                dom: '<"top" <"row" <"col-md-6" f><"col-md-6"<"btn-area">>>>rt<"bottom"lp>',
                columns: [
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'font_name',
                        name: 'title'
                    },
                    {
                        data: 'preview',
                        name: 'preview'
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
                    '<a class="add-btn" href="{{ route('ttf.create') }}">' +
                    '<i class="fas fa-plus"> <span class="remove-mobile">{{ __('Add New') }}<span>' +
                    '</a>' +
                    '</div>');
            });

            {{-- DATA TABLE ENDS --}}

        })(jQuery);
    </script>
@endsection
