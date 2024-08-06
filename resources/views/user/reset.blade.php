@extends('layouts.front')
@section('content')
@include('partials.global.common-header')

<style>
.validator{
   position: relative;
}
.validator .fa{
   position: absolute;
   top: 50%;
   transform: translateY(-50%);
   right: 20px;
   font-size: 14px;
   cursor: pointer;
}
</style>

<!-- breadcrumb -->
<div
   class="full-row bg-light overlay-dark py-5"
   style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-left text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white">{{ __('Reset Password') }}</h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="{{ route('user-dashboard') }}">{{ __('Dashboard') }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ __('Reset Password') }}</li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<!-- breadcrumb -->
<!--==================== Blog Section Start ====================-->
<div class="full-row admin-row">
   <div class="container">
        <div class="mb-4 d-xl-none">
            <button class="dashboard-sidebar-btn btn bg-primary rounded">
                <i class="fas fa-bars"></i>
            </button>
        </div>
      <div class="row">
         <div class="col-xl-4">
            @include('partials.user.dashboard-sidebar')
         </div>
         <div class="col-xl-8">
            <div class="row">
               <div class="col-lg-12">
                  <div class="widget border-0 p-40 widget_categories bg-light account-info">
                     <h4 class="widget-title down-line mb-30">{{ __('Reset Password') }}
                     </h4>
                     <div class="body">
                        <div class="edit-info-area-form">
                           <div class="gocover" style="background: url({{ asset('assets/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                           <form id="userform" action="{{route('user-reset-submit')}}" method="POST" enctype="multipart/form-data">
                              @csrf
                              @include('alerts.admin.form-both')
                              <div class="row">
                                 <div class="col-lg-12 mb-4">
                                    <div class="validator">
                                    <input type="password" id="cpass" name="cpass"  class="form-control  border" placeholder="{{ __('Current Password') }}" value="" required="">
                                    <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon" onclick="togglePasswordVisibility('cpass')"></span>
                                    </div>
                                    <span id="currentError" class="error"></span>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-lg-12 mb-4">
                                    <div class="validator">
                                    <input type="password"  id="password"  name="newpass"  class="input-field form-control  border" placeholder="{{ __('New Password') }}" value="" required="">
                                    <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon" onclick="togglePasswordVisibility('newpass')"></span>
                                    </div>
                                    <span id="passcheck" class="error"> Please Fill the password</span>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-lg-12 mb-4">
                                    <div class="validator">
                                    <input type="password" name="renewpass" id="conpassword"  class="input-field form-control border" placeholder="{{ __('Confirm New Password') }}" value="" required="">
                                    <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon" onclick="togglePasswordVisibility('renewpass')"></span>
                                    </div>
                                    <span id="conpasscheck" class="error"> Password didn't match </span>
                                 </div>
                              </div>
                              <div class="form-links">
                                 <button class="submit-btn btn btn-primary" id="submitbtn" type="button">{{ __('Submit') }}</button>
                              </div>
                           </form>
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
<!--==================== Blog Section End ====================-->
{{-- Modal --}}
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header d-block text-center">
            <h4 class="modal-title d-inline-block">{{ __('License Key') }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <p class="text-center">{{ __('The Licenes Key is :') }} <span id="key"></span></p>
         </div>
         <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
         </div>
      </div>
   </div>
</div>
@includeIf('partials.global.common-footer')
@endsection
@section('script')
<script>
function togglePasswordVisibility(fieldName) {
    var input = document.getElementsByName(fieldName)[0];
    var eyeIcon = input.nextElementSibling;

    if (input.type === "password") {
        input.type = "text";
        eyeIcon.className = "fa fa-fw fa-eye field-icon";
    } else {
        input.type = "password";
        eyeIcon.className = "fa fa-fw fa-eye-slash field-icon";
    }
}
// Document is ready
$(document).ready(function () {
    // Validate Password
    $("#passcheck").hide();
    let passwordError = true;
    $("#password").keyup(function () {
        validatePassword();
    });
    function validatePassword() {
        let passwordValue = $("#password").val();
        if (passwordValue.length == "") {
            $("#passcheck").show();
            $("#passcheck").html("Please enter a new password");
            $("#passcheck").css({'color': 'red','font-size': '14px'});
            passwordError = false;
            return false;
        }
        if (passwordValue.length < 8 ) {
            $("#passcheck").show();
            $("#passcheck").html("your password contains at least 8 characters.");
            $("#passcheck").css({'color': 'red','font-size': '14px'});
            passwordError = false;
            return false;
        }

        // New password complexity checks
        let hasUppercase = /[A-Z]/.test(passwordValue);
        let hasLowercase = /[a-z]/.test(passwordValue);
        let hasNumbers = /[0-9]/.test(passwordValue);
        let hasSpecialChars = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/.test(passwordValue);

        if (!(hasUppercase && hasLowercase && hasNumbers && hasSpecialChars)) {
            $("#passcheck").show();
            $("#passcheck").html("Your password is weak.<br>1.your password contains both upper and lower case letters symbols like @, _, #, * and numbers.");
            passwordError = false;
            return false;
        } else {
            $("#passcheck").hide();
            passwordError = true;
            return passwordError;
        }
    }

    // Validate Confirm Password
    $("#conpasscheck").hide();
    let confirmPasswordError = true;
    $("#conpassword").keyup(function () {
        validateConfirmPassword();
    });
    function validateConfirmPassword() {
        let confirmPasswordValue = $("#conpassword").val();
        let passwordValue = $("#password").val();
        if (passwordValue != confirmPasswordValue) {
            $("#conpasscheck").show();
            $("#conpasscheck").html("Passwords do not match. Please enter the same password in both fields");
            $("#conpasscheck").css({'color': 'red','font-size': '14px'});
            confirmPasswordError = false;
            return false;
        } else {
            $("#conpasscheck").hide();
            confirmPasswordError = true;
            return confirmPasswordError;
        }
    }

    //Submit button
    $("#submitbtn").click(function () {
        var passwordError = validatePassword();
        var confirmPasswordError = validateConfirmPassword();
        var cpass = $("#cpass").val();
        var currentError = false;

        // Validate name
        if (cpass.trim() === "") {
            $("#currentError").text("Current password is required");
            $("#currentError").css({'color': 'red','font-size': '14px'});
            currentError = true;
        } else {
            $("#currentError").text("");
        }
        if (passwordError && confirmPasswordError && !currentError) {
            $('#submitbtn').attr('type', 'submit');
        } else {
            $('#submitbtn').attr('type', 'button');
        }
    });

   $(document).on('focus', 'input,textarea', function() {
      $('.error').html('');
   });
});
</script>
@endsection
