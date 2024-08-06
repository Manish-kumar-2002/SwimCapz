<form id="geniusform" action="{{ route('admin-prod-update', $data->id) }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    @include('alerts.admin.form-both')
    <div class="row">
        <div class="col-lg-8">
            <div class="add-product-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product-description">
                            <div class="body-area">
                                <div class="gocover"
                                    style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Product Name') }}* </h4>
                                            <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <input type="text" class="input-field"
                                            placeholder="{{ __('Enter Product Name') }}" name="name" required=""
                                            value="{{ $data->name }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Product Sku') }}* </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <input type="text" class="input-field"
                                            placeholder="{{ __('Enter Product Sku') }}" name="sku" required=""
                                            value="{{ $data->sku }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Category') }}*</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <select id="cat" name="category_id" required="">
                                            <option>{{ __('Select Category') }}</option>
                                            @foreach ($cats as $cat)
                                                <option data-href="{{ route('admin-subcat-load', $cat->id) }}"
                                                    value="{{ $cat->id }}"
                                                    {{ $cat->id == $data->category_id ? 'selected' : '' }}>
                                                    {{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Sub Category') }}*</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <select id="subcat" name="subcategory_id">
                                            <option value="">{{ __('Select Sub Category') }}</option>
                                            @if ($data->subcategory_id == null)
                                                @foreach ($data->category->subs as $sub)
                                                    <option data-href="{{ route('admin-childcat-load', $sub->id) }}"
                                                        value="{{ $sub->id }}">{{ $sub->name }}</option>
                                                @endforeach
                                            @else
                                                @foreach ($data->category->subs as $sub)
                                                    <option data-href="{{ route('admin-childcat-load', $sub->id) }}"
                                                        value="{{ $sub->id }}"
                                                        {{ $sub->id == $data->subcategory_id ? 'selected' : '' }}>
                                                        {{ $sub->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div> --}}

                                @php
                                    $selectedAttrs = json_decode($data->attributes, true);
                                @endphp

                                {{-- Attributes of category starts --}}
                                <div id="catAttributes">
                                    @php
                                        $catAttributes = !empty($data->category->attributes) ? $data->category->attributes : '';
                                    @endphp
                                    @if (!empty($catAttributes))
                                        @foreach ($catAttributes as $catAttribute)
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ $catAttribute->name }} *</h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    @php
                                                        $i = 0;
                                                    @endphp
                                                    @foreach ($catAttribute->attribute_options as $optionKey => $option)
                                                        @php
                                                            $inName = $catAttribute->input_name;
                                                            $checked = 0;
                                                        @endphp
                                                        <div class="row">
                                                            <div class="col-lg-5">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox"
                                                                        id="{{ $catAttribute->input_name }}{{ $option->id }}"
                                                                        name="{{ $catAttribute->input_name }}[]"
                                                                        value="{{ $option->name }}"
                                                                        class="custom-control-input attr-checkbox"
                                                                        @if (is_array($selectedAttrs) && array_key_exists($catAttribute->input_name, $selectedAttrs)) 
                                                                            @if (is_array($selectedAttrs["$inName"]['values']) && in_array($option->name, $selectedAttrs["$inName"]['values']))
                                                                                checked
                                                                                @php
                                                                                    $checked = 1;
                                                                                @endphp
                                                                            @endif
                                                                        @endif
                                                                    >
                                                                    <label class="custom-control-label"
                                                                        for="{{ $catAttribute->input_name }}{{ $option->id }}">{{ $option->name }}</label>
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="col-lg-7 {{ $catAttribute->price_status == 0 ? 'd-none' : '' }}">
                                                                <div class="row">
                                                                    <div class="col-2">
                                                                        +
                                                                    </div>
                                                                    <div class="col-10">
                                                                        <div class="price-container">
                                                                            <span
                                                                                class="price-curr">{{ $sign->sign }}</span>
                                                                            <input type="text"
                                                                                class="input-field price-input"
                                                                                id="{{ $catAttribute->input_name }}{{ $option->id }}_price"
                                                                                data-name="{{ $catAttribute->input_name }}_price[]"
                                                                                placeholder="0.00 (Additional Price)"
                                                                                value="{{ !empty($selectedAttrs["$inName"]['prices'][$i]) && $checked == 1 ? round($selectedAttrs["$inName"]['prices'][$i] * $sign->value, 2) : '' }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @php
                                                            if ($checked == 1) {
                                                                $i++;
                                                            }
                                                        @endphp
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                {{-- Attributes of category ends --}}

                                {{-- Attributes of subcategory starts --}}
                                <div id="subcatAttributes">
                                    @php
                                        $subAttributes = !empty($data->subcategory->attributes) ? $data->subcategory->attributes : '';
                                    @endphp
                                    @if (!empty($subAttributes))
                                        @foreach ($subAttributes as $subAttribute)
                                            <div class="row">
                                                <div class="col-lg-12 mb-2">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ $subAttribute->name }} *</h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    @php
                                                        $i = 0;
                                                    @endphp
                                                    @foreach ($subAttribute->attribute_options as $option)
                                                        @php
                                                            $inName = $subAttribute->input_name;
                                                            $checked = 0;
                                                        @endphp

                                                        <div class="row">
                                                            <div class="col-lg-5">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox"
                                                                        id="{{ $subAttribute->input_name }}{{ $option->id }}"
                                                                        name="{{ $subAttribute->input_name }}[]"
                                                                        value="{{ $option->name }}"
                                                                        class="custom-control-input attr-checkbox"
                                                                        @if (is_array($selectedAttrs) && array_key_exists($subAttribute->input_name, $selectedAttrs)) @php
																							$inName = $subAttribute->input_name;
																							@endphp
																							@if (is_array($selectedAttrs["$inName"]['values']) && in_array($option->name, $selectedAttrs["$inName"]['values']))
																							checked
																							@php
																								$checked = 1;
																							@endphp @endif
                                                                        @endif
                                                                    >

                                                                    <label class="custom-control-label"
                                                                        for="{{ $subAttribute->input_name }}{{ $option->id }}">{{ $option->name }}</label>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-lg-7 {{ $subAttribute->price_status == 0 ? 'd-none' : '' }}">
                                                                <div class="row">
                                                                    <div class="col-2">
                                                                        +
                                                                    </div>
                                                                    <div class="col-10">
                                                                        <div class="price-container">
                                                                            <span
                                                                                class="price-curr">{{ $sign->sign }}</span>
                                                                            <input type="text"
                                                                                class="input-field price-input"
                                                                                id="{{ $subAttribute->input_name }}{{ $option->id }}_price"
                                                                                data-name="{{ $subAttribute->input_name }}_price[]"
                                                                                placeholder="0.00 (Additional Price)"
                                                                                value="{{ !empty($selectedAttrs["$inName"]['prices'][$i]) && $checked == 1 ? round($selectedAttrs["$inName"]['prices'][$i] * $sign->value, 2) : '' }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @php
                                                            if ($checked == 1) {
                                                                $i++;
                                                            }
                                                        @endphp
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                {{-- Attributes of subcategory ends --}}
                                <div class="">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Type') }}*</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <select name="product_type[]" multiple id="type" class="select2">
                                                @php
                                                    $product_type = App\Models\ProductType::get();
                                                    $product_type_id = explode(',', $data->product_type);
                                                @endphp
                                                @foreach ($product_type as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ in_array($item->id, $product_type_id) ? 'selected' : '' }}>
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <ul class="list">
                                            <li>
                                                <input class="checkclick1" name="product_condition_check"
                                                    type="checkbox" id="conditionCheck" value="1"
                                                    {{ $data->product_condition != 0 ? 'checked' : '' }}>
                                                <label
                                                    for="conditionCheck">{{ __('Allow Product Condition') }}</label>
                                            </li>
                                        </ul>
                                    </div>
                                </div> --}}

                                {{-- <div class="{{ $data->product_condition == 0 ? 'showbox' : '' }}">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Product Condition') }}*</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <select name="product_condition">
                                                <option value="2"
                                                    {{ $data->product_condition == 2 ? 'selected' : '' }}>
                                                    {{ __('New') }}</option>
                                                <option value="1"
                                                    {{ $data->product_condition == 1 ? 'selected' : '' }}>
                                                    {{ __('Used') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <ul class="list">
                                            <li>
                                                <input class="checkclick1" name="shipping_time_check" type="checkbox"
                                                    id="check1" value="1"
                                                    {{ $data->ship != null ? 'checked' : '' }}>
                                                <label
                                                    for="check1">{{ __('Allow Estimated Shipping Time') }}</label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>


                                <div class="{{ $data->ship != null ? '' : 'showbox' }}">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Product Estimated Shipping Time') }}* </h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" class="input-field"
                                                placeholder="{{ __('Estimated Shipping Time') }}" name="ship"
                                                value="{{ $data->ship == null ? '' : $data->ship }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <ul class="list">
                                            <li>
                                                <input class="checkclickc" name="color_check" type="checkbox"
                                                    id="check3" value="1"
                                                    {{ !empty($data->color_all) ? 'checked' : '' }}>
                                                <label for="check3">{{ __('Allow Product Colors') }}</label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="{{ !empty($data->color_all) ? '' : 'showbox' }}">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="left-area">
                                                <h4 class="heading">
                                                    {{ __('Product Colors') }}*
                                                </h4>
                                                <p class="sub-heading">
                                                    {{ __('(Choose Your Favorite Colors)') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <table class="table color-table" style="width:100%;">
                                                <tbody>
                                                    @if (!empty($data->color_all))
                                                        @foreach (array_unique(explode(',', $data->color_all)) as $key => $ct)
                                                            <tr>
                                                                <th style="width:50%;padding:2px;">
                                                                    <input
                                                                        type="color"
                                                                        name="color_all[]"
                                                                        class="input-field"
                                                                        value="{{$ct }}"
                                                                    />
                                                                </th>
                                                                @if($key == 0)
                                                                    <th style="width:50%;padding:2px;">
                                                                        <button class="btn btn-success color-add-more" type="button">+ Add More</button>
                                                                    </th>
                                                                @else
                                                                    <th style="width:50%;padding:2px;">
                                                                        <button
                                                                            class="btn btn-danger"
                                                                            type="button"
                                                                            onclick="$(this).closest('tr').remove()"
                                                                        ><i class="fas fa-trash-alt"></i></button>
                                                                    </th>
                                                                @endif
                                                                
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <th style="width:50%;padding:2px;">
                                                                <input type="color" name="color_all[]"
                                                                    class="input-field"/>
                                                            </th>
                                                            <th style="width:50%;padding:2px;">
                                                                <button class="btn btn-success color-add-more" type="button">+ Add More</button>
                                                            </th>
                                                        </tr>
                                                    @endif

                                                </tbody>
                                            </table>
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <ul class="list">
                                            <li>
                                                <input class="checkclicks" name="size_check" type="checkbox"
                                                    id="tcheck" value="1"
                                                    {{ !empty($data->size_all) ? 'checked' : '' }}>
                                                <label for="tcheck">{{ __('Allow Product Sizes') }}</label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="{{ !empty($data->size_all) ? '' : 'showbox' }}">
                                    @if (!empty($data->size_all))
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">
                                                        {{ __('Product Size') }}*
                                                    </h4>
                                                    <p class="sub-heading">
                                                        {{ __('(eg. S,M,L,XL,XXL,3XL,4XL)') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="select-input-tsize" id="tsize-section">
                                                    @foreach (array_unique(explode(',', $data->size_all)) as $dt)
                                                        <div class="tsize-area dipr">
                                                            <!-- <span class="btn-danger remove tsize-remove"><i
                                                                    class="fas fa-trash-alt"></i></span> -->
                                                            <input type="text" name="size_all[]"
                                                                class="input-field tsize"
                                                                placeholder="{{ __('Enter Product Size') }}"
                                                                value="{{ $dt }}" required="">
                                                                <a href="javascript:;" id="tsize-btn" class="add-more ml-4 mb-4 btn-success"><i
                                                                class="fas fa-plus"></i>{{ __('Add More') }} </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                              
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">
                                                        {{ __('Product Size') }}*
                                                    </h4>
                                                    <p class="sub-heading">
                                                        {{ __('(eg. S,M,L,XL,XXL,3XL,4XL)') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="select-input-tsize" id="tsize-section">
                                                    <div class="tsize-area">
                                                        <span class="remove tsize-remove"><i
                                                                class="fas fa-times"></i></span>
                                                        <input type="text" name="size_all[]"
                                                            class="input-field tsize"
                                                            placeholder="{{ __('Enter Product Size') }}">

                                                    </div>
                                                </div>
                                                <a href="javascript:;" id="tsize-btn" class="add-more mt-4 mb-3"><i
                                                        class="fas fa-plus"></i>{{ __('Add More Size') }} </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Size Chart (Optional)') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <input type="file" class="form-control" name="size_chart">
                                        @if ($data->size_chart)
                                            <a target="_blank" href="{{asset('storage/productChart/' . $data->size_chart)}}"
                                            >{{asset('storage/productChart/' . $data->size_chart)}}</a>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Product Description') }}*
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="text-editor">
                                            <textarea name="details" class="nic-edit-p">{{ $data->details }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Product Buy/Return Policy') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="text-editor">
                                            <textarea name="policy" class="nic-edit-p">{{ $data->policy }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="checkbox-wrapper">
                                            <input type="checkbox" name="seo_check" value="1"
                                                class="checkclick" id="allowProductSEO"
                                                {{ $data->meta_tag != null || strip_tags($data->meta_description) != null ? 'checked' : '' }}>
                                            <label for="allowProductSEO">{{ __('Allow Product SEO') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="{{ $data->meta_tag == null && strip_tags($data->meta_description) == null ? 'showbox' : '' }}">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Meta Tags') }} </h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <ul id="metatags" class="myTags">
                                                @if (!empty($data->meta_tag))
                                                    @foreach ($data->meta_tag as $element)
                                                        <li>{{ $element }}</li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="left-area">
                                                <h4 class="heading">
                                                    {{ __('Meta Description') }} 
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="text-editor">
                                                <textarea name="meta_description" class="input-field" placeholder="{{ __('Details') }}">{{ $data->meta_description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="add-product-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product-description">
                            <div class="body-area">
                                {{-- <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Youtube Video URL') }}</h4>
                                            <p class="sub-heading">{{ __('(Optional)') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <input name="youtube" type="text" class="input-field"
                                            placeholder="Enter Youtube Video URL" value="{{ $data->youtube }}">
                                    </div>
                                </div> --}}

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="featured-keyword-area">
                                            <div class="left-area">
                                                <h4 class="title">{{ __('Feature Tags') }}</h4>
                                            </div>

                                            <div class="feature-tag-top-filds" id="feature-section">
                                                @if (!empty($data->features))

                                                    @foreach ($data->features as $key => $data1)
                                                        <div class="feature-area">
                                                            <span class="remove feature-remove"><i
                                                                    class="fas fa-times"></i></span>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <input type="text" name="features[]"
                                                                        class="input-field"
                                                                        placeholder="{{ __('Enter Your Keyword') }}"
                                                                        value="{{ $data->features[$key] }}">
                                                                </div>

                                                                <div class="col-lg-6">
                                                                    <div class="input-group colorpicker-component cp">
                                                                        <input type="text" name="colors[]"
                                                                            value="{{ $data->colors[$key] }}"
                                                                            class="input-field cp" />
                                                                        <span class="input-group-addon"><i></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="feature-area">
                                                        <span class="remove feature-remove"><i
                                                                class="fas fa-times"></i></span>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <input type="text" name="features[]"
                                                                    class="input-field"
                                                                    placeholder="{{ __('Enter Your Keyword') }}">
                                                            </div>

                                                            <div class="col-lg-6">
                                                                <div class="input-group colorpicker-component cp">
                                                                    <input type="text" name="colors[]"
                                                                        value="#000000" class="input-field cp" />
                                                                    <span class="input-group-addon"><i></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endif
                                            </div>

                                            <a href="javascript:;" id="feature-btn" class="add-fild-btn"><i
                                                    class="icofont-plus"></i> {{ __('Add More Field') }}</a>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Tags') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <ul id="tags" class="myTags">
                                            @if (!empty($data->tags))
                                                @foreach ($data->tags as $element)
                                                    <li>{{ $element }}</li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div> --}}

                                <div class="row text-center">
                                    <div class="col-6 offset-3">

                                        @if(request()->view != 'true')
                                            <button class="addProductSubmit-btn"
                                                type="submit">{{ __('Save') }}</button>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
