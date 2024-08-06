<div class="row mb-4">
    <div class="col-lg-8">
        <label for="name" class="mb-2">Name <span style="color:red;">*</span></label>
        <input name="name" type="text" class="input-field form-control border" placeholder="{{ __('Name') }}"
            value="{{ @$address->name ?? '' }}">
        <strong class="errors error-name" id="error-name">
            <span style="color:red;font-weight:bold"></span>
        </strong>
    </div>
    <div class="col-lg-4">
        <label for="name" class="mb-2">Email <span style="color:red;">*</span></label>
        <input
            name="email"
            type="email"
            class="input-field form-control border email-validate"
            placeholder="{{ __('Email Address') }}"
            value="{{ @$address->email ?? '' }}"
            error-class="error-email"
        />
        <strong class="errors error-email" id="error-email">
            <span style="color:red;font-weight:bold"></span>
        </strong>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-4">
        <label for="name" class="mb-2">Phone<span style="color:red;">*</span></label>
        <input
            name="phone"
            type="text"
            class="input-field form-control border only-numeric"
            placeholder="{{ __('Phone Number') }}"
            value="{{ @$address->phone ?? '' }}"
            max="10"
        />
        <strong class="errors error-phone" id="error-phone">
            <span style="color:red;font-weight:bold"></span>
        </strong>
    </div>
    <div class="col-lg-4">
        <label for="name" class="mb-2">City<span style="color:red;">*</span></label>
        <input name="city" type="text" class="input-field form-control border"
                placeholder="{{ __('City') }}" value="{{ @$address->city ?? '' }}">
        <strong class="errors error-city" id="error-city">
            <span style="color:red;font-weight:bold"></span>
        </strong>
    </div>
    <div class="col-lg-4">
        <label for="name" class="mb-2">{{__('Zipcode')}} <span style="color:red;">*</span></label>
        <input
            name="zip"
            type="text"
            class="input-field form-control border only-numeric"
            placeholder="{{ __('Zip') }}"
            value="{{ @$address->zip ?? '' }}"
            max="6"
        />
        <strong class="errors error-zip" id="error-zip">
            <span style="color:red;font-weight:bold"></span>
        </strong>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-4">
      <label for="name" class="mb-2">Address Type <span style="color:red;">*</span></label>
      <div class="">
        <select class="form-control border" name="address_type" id="address_type">
            <option value="">{{ __('Select') }}</option>
            <option
                value="Home"
                {{ @$address && $address->address_type == 'Home' ? 'selected' : '' }}
            >{{ __('Home') }}</option>
            <option
                value="Office"
                {{ @$address && $address->address_type == 'Office' ? 'selected' : '' }}
            >{{ __('Office') }}</option>
            <option
                value="Other"
                {{ @$address && $address->address_type == 'Other' ? 'selected' : '' }}
            >{{ __('Other') }}</option>
        </select>
      </div>

        <strong class="errors error-address_type" id="error-address_type">
            <span style="color:red;font-weight:bold"></span>
        </strong>
    </div>
    <div class="col-lg-4">
      <label for="name" class="mb-2">Country <span style="color:red;">*</span></label>
      <div class="select-box-wrap">
      <select class="input-field form-control border" name="country" id="country">
            <option value="">{{ __('Select Country') }}</option>
            @foreach (DB::table('countries')->get() as $data)
                <option value="{{ $data->id }}"
                    {{ @$address && $address->country == $data->id ? 'selected' : '' }}>
                    {{ $data->country_name }}
                </option>
            @endforeach
        </select>
      </div>

        <strong class="errors error-country" id="error-country">
            <span style="color:red;font-weight:bold"></span>
        </strong>
    </div>
    <div class="col-lg-4">
        <label for="name" class="mb-2">{{ __('State') }} <span style="color:red;">*</span></label>
        <div class="select-box-wrap">
        <select name="state" id="state" class="input-field form-control border">
            <option value="">-select-</option>
            @if (@$address && $address->state)
                <option value="{{$address->state}}" selected>{{$address->getState->state}}</option>
            @endif
        </select>
        </div>

        <strong class="errors error-state" id="error-state">
            <span style="color:red;font-weight:bold"></span>
        </strong>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-12">
        <label for="name" class="mb-2">{{__('Address')}} <span style="color:red;">*</span></label>
        <textarea class="input-field form-control border" name="address" placeholder="{{ __('Address') }}" cols="30"
                rows="10">{{ @$address->address ?? '' }}</textarea>

        <strong class="errors error-address" id="error-address">
            <span style="color:red;font-weight:bold"></span>
        </strong>
    </div>
</div>