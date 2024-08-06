@extends('layouts.front')
@section('content')
    @include('partials.global.common-header')

    <div class="full-row">
        <div class="container">
			<form action="{{route('report.problem')}}" method="post" enctype="multipart/form-data" id="form">
				@csrf
				<div class="row">
					<div class="col-lg-8 mx-auto mt-10">
						<h4>How can we improve your shopping experience?</h4>
						<p>If you have found something confusing or have any suggestions to improve your shopping experience, please let us know. If you would like a response, please provide your contact information.</p>
						<fieldset class="reset fieldset">
							<legend class="reset legend">Contact Information</legend>

							<div class="row mt-3 mb-3">
								<div class="col-md-12">
									<div class="input-wrap">
										<label for="fullname">Feedback <span class="required">*</span></label>
										<textarea type="text" name="feedback" id="feedback"
											placeholder="{{ __('Feedback') }}" class="form-control" rows="10"></textarea>
										<span id="feedbackError" class="error"></span>
									</div>
								</div>
							</div>

							<div class="row mt-3 mb-3">
								<div class="col-md-12">
									<div class="input-wrap">
										<label for="fullname">Email <span class="required">*</span></label>
										<input type="text" name="email" id="email"
											placeholder="{{ __('Email') }}">
										<span id="emailError" class="error"></span>
									</div>
								</div>
							</div>
							<div >
								<div class="col-md-12">
									<div class="input-wrap">
										<label for="fullname">Phone <span class="required">*</span></label>
										<input type="text" name="phone" id="phone"
											placeholder="{{ __('Phone') }}">
										<span id="phoneError" class="error"></span>
									</div>
								</div>
							</div>
							<div class="row mt-3 mb-3">
								@if ($gs->is_capcha == 1)
									<div class="form-input mb-3">
										{!! NoCaptcha::display() !!}
										{!! NoCaptcha::renderJs() !!}
										@error('g-recaptcha-response')
											<p class="my-2">{{ $message }}</p>
										@enderror
									</div>
									<span id="g-recaptcha-responseError" class="error"></span>
								@endif
							</div>

						</fieldset>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8 mx-auto mt-10">
						<button type="submit" class="btn btn-success">SUBMIT</button>
					</div>
				</div>
			</form>
        </div>
    </div>
    @include('partials.global.common-footer')
@endsection
@section('script')
    <script>
		$(function(){
			$("#form").submit(function (e) {
				e.preventDefault(0);
				e.stopImmediatePropagation;

				var name = $("#feedback").val();
				var email = $("#email").val();
				var phone = $("#phone").val();
				
				let isSubmit=true;
				if (name.trim() === "") {
					$("#feedbackError").text("Feedback is required");
					isSubmit=false;
				} else {
					$("#feedbackError").text("");
				}


				if (email.trim() === "") {
					$("#emailError").text("Email is required");
					isSubmit=false;
				} else if (!isValidEmail(email)) {
					$("#emailError").text("Please enter a valid email address.");
					isSubmit=false;
				} else {
					$("#emailError").text("");
				}

       			const phoneRegex = /^\d{0,11}$/;
				if (phone.trim() === "") {
					$("#phoneError").text("Phone no. is required");
					isSubmit=false;
				} else if (!phone.match(phoneRegex)) {
					$("#phoneError").text("Phone no. can have a maximum of 11 digits");
					isSubmit=false;
				} else {
					$("#phoneError").text("");
				}
				
				if(!isSubmit) {
					return;
				}
				
                let formData=new FormData(this);
                $.ajax({
                    method: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        console.log('Success', data);
                        toastr.success(data.message);
						$("#form").trigger('reset');
                    },
                    error: function(reject){
                       
                       	var errors = reject.responseJSON.errors;
                        $.each(errors, function (key, val) {
							$('#' + key + 'Error').html(val[0]);
                        });
                    }
                });


    		});
		})

		function isValidEmail(email) {
			// Regular expression for basic email validation
			var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			return emailPattern.test(email);
		}

		$( "input" ).on( "focus", function() {
			$( ".error" ).html( "" );
		});

    </script>
@endsection

@section('css')
    <style>
        .reset {
            all: revert;
            margin-top: 30px;
        }

        .required,
        .error {
            color: red;
        }

        .legend {
            font-size: 30px;
        }

        span {
            cursor: pointer;
        }
        .fieldset {
            padding: 42px;
        }
    </style>
@endsection
