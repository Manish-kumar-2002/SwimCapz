<div class="row">
    <div class="col-lg-4">
        <div class="left-area">
            <h4 class="heading">{{ __('Code') }} *</h4>
            <p class="sub-heading">{{ __('(In Any Language)') }}</p>
        </div>
    </div>
    <div class="col-lg-7">
        <input
            type="text" class="input-field" name="code" placeholder="{{ __('Enter Code') }}"
            value="{{ $coupon ? $coupon->code : '' }}"
        >

        @include('admin.errorPanel', ['name' => 'code'])
    </div>
</div>

<div class="row" id="category">
    <div class="col-lg-4">
        <div class="left-area">
            <h4 class="heading">{{ __('Category') }}*</h4>
        </div>
    </div>
    <div class="col-lg-7">
        <select name="category" id="category-product">
            <option value="">{{ __('Select Category') }}</option>
            @foreach ($categories as $cat)
                @php
                    $sel=$cat->id == ($coupon ? $coupon->category : null) ? 'selected' : '';
                @endphp
                <option value="{{ $cat->id }}" {{$sel}}>{{ $cat->name }}</option>
            @endforeach
        </select>
        @include('admin.errorPanel', ['name' => 'category'])
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="left-area">
            <h4 class="heading">{{ __('Product') }}</h4>
        </div>
    </div>
    <div class="col-lg-7">
        <select name="product" id="product">
            <option value="">{{ __('Select Product') }}</option>
        </select>
        @include('admin.errorPanel', ['name' => 'product'])
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="left-area">
            <h4 class="heading">{{ __('Type') }} *</h4>
        </div>
    </div>
    <div class="col-lg-7">
        <select id="type" name="type">
            <option value="">{{ __('Choose a type') }}</option>
            <option
                value="0"
                {{$coupon ? ($coupon->type == 0 ? 'selected' : '') : ''}}
            >{{ __('Discount By Percentage') }}</option>
            <option
                value="1"
                {{$coupon ? ($coupon->type == 1 ? 'selected' : '') : ''}}
            >{{ __('Discount By Amount') }}</option>
        </select>
        @include('admin.errorPanel', ['name' => 'type'])
    </div>
</div>
<div class="row {{$coupon ? '' : 'hidden'}}">
    <div class="col-lg-4">
        <div class="left-area">
            <h4 class="heading">{{ __('Value') }} *</h4>
        </div>
    </div>
    <div class="col-lg-7">
        <input
            type="text"
            class="input-field less-width only-numeric"
            name="value"
            placeholder="{{ __('Enter Values') }}"
            value="{{$coupon ? $coupon->price : ''}}"
        ><span></span><br>
        @include('admin.errorPanel', ['name' => 'value'])
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="left-area">
            <h4 class="heading">{{ __('Allow No of Times') }} *</h4>
        </div>
    </div>
    <div class="col-lg-7">
        <input type="text" class="input-field only-numeric" name="times"
            placeholder="{{ __('Enter Value') }}"
            value="{{ $coupon ? $coupon->times : '' }}">

        @include('admin.errorPanel', ['name' => 'times'])
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="left-area">
            <h4 class="heading">{{ __('Description') }} *</h4>
        </div>
    </div>
    <div class="col-lg-7">
        <textarea class="input-field" name="description"
            placeholder="{{ __('Enter Description') }}">{{ $coupon ? $coupon->description : '' }}</textarea>

        @include('admin.errorPanel', ['name' => 'description'])
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="left-area">
            <h4 class="heading">{{ __('Start Date') }} *</h4>
        </div>
    </div>
    <div class="col-lg-7">
        <input type="text" class="input-field" name="start_date"
            autocomplete="off" id="from" value="{{$coupon ? date('m/d/Y', strtotime($coupon->start_date)) : ''}}"
            placeholder="{{ __('Select a date') }}">

        @include('admin.errorPanel', ['name' => 'start_date'])
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="left-area">
            <h4 class="heading">{{ __('End Date') }} *</h4>
        </div>
    </div>
    <div class="col-lg-7">
        <input type="text" class="input-field" name="end_date"
            autocomplete="off" id="to" value="{{$coupon ? date('m/d/Y', strtotime($coupon->end_date)) : ''}}"
            placeholder="{{ __('Select a date') }}">
        @include('admin.errorPanel', ['name' => 'start_date'])
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        
    </div>
    <div class="col-lg-7">
        <div class="server-info"></div>
    </div>
</div>
