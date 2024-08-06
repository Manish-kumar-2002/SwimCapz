@extends('layouts.admin')

@section('styles')

<link href="{{asset('assets/admin/css/jquery-ui.css')}}" rel="stylesheet" type="text/css">

@endsection


@section('content')

            <div class="content-area">

              <div class="mr-breadcrumb">
                <div class="row">
                  <div class="col-lg-12">
                      <h4 class="heading">{{ __('Featured') }} <a class="add-btn" href="{{route('admin-featured-index')}}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                      <ul class="links">
                        <li>
                          <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                          <a href="javascript:;">{{ __('Content Management') }} </a>
                        </li>
                        <li>
                          <a href="{{ route('admin-featured-index') }}">{{ __('Featured') }}</a>
                        </li>
                        <li>
                          <a href="{{ route('admin-featured-edit',$data->id) }}">{{ __('Edit Featured') }}</a>
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
                      <form id="geniusformdata" action="{{route('admin-featured-update',$data->id)}}" method="POST" enctype="multipart/form-data">
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
                            <textarea  class="form-control" name="description" placeholder="{{ __('Enter the description') }}" rows="15" required="" value="">{{$data->description}}</textarea>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Link') }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" value="{{$data->link}}" class="input-field" name="link" placeholder="{{ __('Enter the link') }}" required="" value="">
                          </div>
                        </div>
                    
                        <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                <h4 class="heading"> {{ __('Image') }} *</h4>
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <div class="img-upload">
                                <div id="image-preview" class="img-preview" style="background: url({{ $data->image ? asset('assets/featuredon/'.$data->image):asset('assets/images/noimage.png') }});">
                                  <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                  <input type="file" name="image" class="img-upload" id="image-upload">
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Article date') }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="date" value="{{$data->article_date}}" class="input-field" name="article_date" placeholder="" required="" >
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Status') }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                              <select id="type" name="status" required>
                                <option value="">{{ __('Choose a Status') }}</option>
                                <option value="1" {{ ($data->status ==1) ? 'selected':''}}>{{ __('Active') }}</option>
                                <option value="0" {{ ($data->status ==0) ? 'selected':''}}>{{ __('Deactive') }}</option>
                              </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                              
                            </div>
                          </div>
                          <div class="col-lg-7">
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

