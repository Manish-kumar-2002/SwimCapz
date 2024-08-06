@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .nav-tabs .nav-item{
            margin:3px;
        }

        span.verticle-align{
            vertical-align: -moz-middle-with-baseline;
        }

        .break_count{width:50px;}
        .break-size{width:50px;}
        .table td, .table th{
            padding: 8px;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        p.special{
            margin-bottom: -4px;
        }
    </style>
@endsection
@section('content')
    
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Price Structure') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li><a href="javascript:;">{{ __('Manage Categories') }}</a></li>
                        <li>
                            <span>{{ __('Price Structure') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area" style="overflow-x: auto;">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        @include('alerts.admin.form-success')

                        <div class="col-md-12 mt-3">
                            @include('admin.priceStructure.header')
                        </div>
                            
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            
                            @foreach (Helper::categories() as $key => $category)
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link {{$key == 0 ? 'active' : ''}}"
                                        id="home-tab-{{$category->id}}"
                                        data-toggle="tab"
                                        data-target="#home-{{$category->id}}"
                                        type="button"
                                        role="tab"
                                        aria-controls="home-{{$category->id}}"
                                        aria-selected="true"
                                        onclick="loadDetails('{{$category->id}}')"
                                    >{{$category->name}}</button>
                                </li>
                            @endforeach
                                
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            @foreach (Helper::categories() as $key => $category)
                                <div
                                    class="tab-pane fade {{$key == 0 ? 'show active' : ''}}"
                                    id="home-{{$category->id}}"
                                    role="tabpanel"
                                    aria-labelledby="home-tab-{{$category->id}}"
                                >
                                    <form action="{{route('price-structure.store')}}" method="post" class="form">
                                        @csrf
                                        <input type="hidden" name="category_id" value="{{$category->id}}">
                                        <div class="panal"> Loading .. {{$category->name}}</div>
                                        <div class="row">
                                            <div class="col-md-12" style="text-align:center;">
                                                <div
                                                    class="process mt-3 mb-3"
                                                    style="text-align:center;color:green;font-weight:bold;"
                                                ></div>
                                                <button
                                                    type="submit"
                                                    class="btn btn-success save"
                                                >{{__('Save')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
   @include('admin.priceStructure.js')
@endsection
