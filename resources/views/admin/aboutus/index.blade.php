@extends('layouts.admin')

@section('styles')

<link href="{{asset('assets/admin/css/jquery-ui.css')}}" rel="stylesheet" type="text/css">

@endsection

@section('content')

            <div class="content-area">

              <div class="mr-breadcrumb">
                <div class="row">
                  <div class="col-lg-12">
                      <h4 class="heading">{{ __('About Us') }}</h4>
                      <ul class="links">
                        <li>
                          <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                          <a href="javascript:;">{{ __('Content Management') }} </a>
                        </li>
                        <li>
                          <a href="{{ route('admin-aboutus-edit',$data->id) }}">{{ __('About Us') }}</a>
                        </li>
                        <li>
                          <a href="{{ route('admin-aboutus-edit',$data->id) }}">{{ __('Edit About us') }}</a>
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
                        @include('includes.admin.form-both')
                        <form id="geniusformdata" action="{{route('admin-aboutus-update',$data->id)}}" method="POST" enctype="multipart/form-data">
                          {{csrf_field()}}
                          <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                  <h4 class="heading">{{ __('Title') }} *</h4>
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <input type="text" value="{{$data->title}}" class="input-field" name="title" placeholder="{{ __('Enter the title') }}" required="" value="">
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                  <h4 class="heading">{{ __('Description') }} *</h4>
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <textarea class="nic-edit-p" name="description" placeholder="Enter the description" rows="15" required="" style="display:none">{{$data->description}}</textarea>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <button class="addProductSubmit-btn" type="submit">{{ __('Update About us') }}</button>
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