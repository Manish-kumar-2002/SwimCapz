@if ($page == 1)
    <div class="row mt-2 mb-2" style="text-align: right;">
       <a
            href="{{route('addressAdd')}}"
            class="link-modal"
            link-url="{{route('addressAdd')}}"
            link-title="Add Address"
            link-isFooter="1"
        ><u><b style="color:green;font-size:16px;">+ Add Address</b></u></a>
    </div>

    <div class="user-addresses">{{-- load address --}}</div>
    
@elseif($page == 0)
    <form
        class="userform"
        action="{{ route('user-profile-update') }}"
        method="POST" enctype="multipart/form-data"
    >
        @csrf
        <div class="upload-img">
            @if ($user->is_provider == 1)
                <div class="img">
                    <img
                        src="{{ $user->photo ? asset($user->photo) : asset('assets/images/' . $gs->user_image) }}"
                        alt="user-pic"
                    >
                </div>
            @else
                <div class="img">
                    <img
                        src="{{ $user->photo ? asset('assets/images/users/' . $user->photo) :
                            asset('assets/images/' . $gs->user_image) }}"
                        alt="user-pic"
                    >
                </div>
            @endif
            @if ($user->is_provider != 1)
                <div class="file-upload-area">
                    <div class="upload-file">
                        <label>{{ __('Upload') }}
                            <input type="file" size="60" name="photo" class="upload form-control">
                        </label>
                    </div>
                </div>
            @endif
        </div>
        <div class="row mb-4">
            <div class="col-lg-6">
                <input name="name" id="name" type="text" class="input-field form-control border"
                    placeholder="{{ __('User Name') }}" value="{{ $user->name }}">

                <strong class="errors error-name mt-5">
                    <span style="color:red"></span>
                </strong>
            </div>
            <div class="col-lg-6">
                <input
                    name="email"
                    id="email"
                    type="email"
                    class="input-field form-control border email-validate"
                    placeholder="{{ __('Email Address') }}"
                    value="{{ $user->email }}"
                    error-class="error-email"
                />

                <strong class="errors error-email mt-5">
                    <span style="color:red"></span>
                </strong>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-lg-6">
                <input
                    name="phone"
                    id="phone"
                    type="text"
                    class="input-field form-control border only-numeric"
                    placeholder="{{ __('Phone Number') }}"
                    value="{{ $user->phone }}"
                    max="10"
                />

                <strong class="errors error-phone mt-5">
                    <span style="color:red"></span>
                </strong>
            </div>
        </div>
        <div class="form-links mt-4">
            <button class="submit-btn btn btn-primary" type="submit">{{ __('Save') }}</button>
        </div>
    </form>
@endif
