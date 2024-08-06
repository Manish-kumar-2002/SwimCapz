<style>
	.error{
		display: block;
		margin-top:6px;
		font-size:15px;
	}
	.text-tell{
		border: 1px solid #ccc;
	}
</style>
@extends('layouts.front')
@section('content')
    @include('partials.global.common-header')

    <div class="full-row">
        <div class="container">
			<form action="{{route('request.quotes')}}" method="post" enctype="multipart/form-data" id="form">
				@csrf
				<div class="row">
					<div class="col-lg-8 mx-auto mt-10">
						<fieldset class="reset fieldset">
							<legend class="reset legend">Contact Info</legend>

							<div class="row mt-3 mb-3">
								<div class="col-md-12">
									<div class="input-wrap">
										<label for="fullname">Name <span class="required">*</span></label>
										<input type="text" name="name" id="name"
											placeholder="{{ __('Name') }}">
										<span id="nameError" class="error"></span>
									</div>
								</div>
							</div>

							<div class="row mt-3 mb-3">
								<div class="col-md-6">
									<div class="input-wrap">
										<label for="fullname">Email <span class="required">*</span></label>
										<input type="text" name="email" id="email"
											placeholder="{{ __('Email') }}">
										<span id="emailError" class="error"></span>
									</div>
								</div>

								<div class="col-md-6">
									<div class="input-wrap">
										<label for="fullname">Phone <span class="required">*</span></label>
										<input type="text" name="phone" id="phone"
											placeholder="{{ __('Phone') }}">
										<span id="phoneError" class="error"></span>
									</div>
								</div>
							</div>

						</fieldset>
						<fieldset class="reset fieldset">
							<legend class="reset legend">Details</legend>

							<div class="row mt-3 mb-3">
								<div class="col-md-12">
									<div class="input-wrap">
										<label for="fullname">Tell us about your project</label>
										<textarea
											class="form-control text-tell"
											rows="10"
											name="project_desc"
											id="project_desc"
										></textarea>
										<span id="project_descError" class="error"></span>
									</div>
								</div>
							</div>

							<div class="row mt-3 mb-3">
								<div class="col-md-6">
									<div class="input-wrap">
										<label for="fullname">How many items do you need?</label>
										<input type="text" name="noi" id="noi">
										<span id="noiError" class="error"></span>
									</div>
								</div>

								@php
									$today = date('Y-m-d');
								@endphp
								<div class="col-md-6">
									<div class="input-wrap">
										<label for="fullname">When do you need it?</label>
										<input type="date" name="date" id="date" min="{{ $today }}" >
										<span id="dateError" class="error"></span>
									</div>
								</div>
							</div>
							<div class="row mt-3 mb-3">
								<div class="col-md-12">
									<div class="input-wrap">
										<label for="fullname">What is your budget?  (in $) </label>
										<input type="text" name="budget" id="budget">
										<span id="budgetError" class="error"></span>
									</div>
								</div>
							</div>

						</fieldset>

						<fieldset class="reset fieldset">
							<legend class="reset legend">Tell Us About Your Art</legend>

							<div class="row mt-3 mb-3">
								<div class="col-md-12">
									<h5 style=" font-size: 15px; ">Number of Print Colors</h5>
									<p>Enter the number of ink colors per side below.</p>
									<div class="col-sm-12">
										<table class="table">
											<tr>
												<td>Front</td>
												<td>
													<div class="number">
														<span class="minus" data-class="front">-</span>
														<input
															name="front"
															class="inputMinusPlus front numbers"
															type="text"
															value="0"
														/>
														<span class="plus" data-class="front">+</span>
													</div>
												</td>
											</tr>
											<tr>
												<td>Back</td>
												<td>
													<div class="number">
														<span class="minus" data-class="back">-</span>
														<input
															name="back"
															class="inputMinusPlus back numbers"
															type="text"
															value="0"
														/>
														<span class="plus" data-class="back">+</span>
													</div>
												</td>
											</tr>
										</table>
										<hr>
										<h4>Upload Art</h4>
										<p>Add art files related to this project below.</p>
										<span style="color: red;font-size: 14px;margin-bottom: 10px;display: block;" >Accepted file types: JPG, JPEG, PNG, GIF</span>
        

										<div class="form-group">
											<input type="file" name="file" id="demo-2" accept="image/jpeg,image/png,image/gif">
										</div>
										<div class="form-group button-wrapper2">
											<button type="reset" class="btn">Reset</button>
										</div>


									</div>
								</div>
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
    <script src="{{ asset('assets/dropzone') }}/dist/js/bs-dropzone.js"></script>
    <script>
        $('#demo-2').bs_dropzone();
        $(document).on('click', '.plus', function() {
            let inputClass = $(this).attr('data-class');
            let value = $('.' + inputClass).val();

            $('.' + inputClass).val(parseInt(value) + 1);
        });

        $(document).on('click', '.minus', function() {
            let inputClass = $(this).attr('data-class');
            let value = $('.' + inputClass).val();
            if (parseInt(value) - 1 < 0) {
                return;
            }
            $('.' + inputClass).val(parseInt(value) - 1);
        });

		$(document).on('keydown', '#phone', function(event) {
		// Allow: backspace, delete, tab, escape, enter and .
			if ($.inArray(event.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
				// Allow: Ctrl/cmd+A, Ctrl/cmd+C, Ctrl/cmd+X
			(event.keyCode === 65 && (event.ctrlKey === true || event.metaKey === true)) ||
			(event.keyCode === 67 && (event.ctrlKey === true || event.metaKey === true)) ||
			(event.keyCode === 88 && (event.ctrlKey === true || event.metaKey === true)) ||
				// Allow: home, end, left, right, down, up
			(event.keyCode >= 35 && event.keyCode <= 40)) {
				// let it happen, don't do anything
				return;
			}
		// Ensure that it is a number and stop the keypress
			if ((event.shiftKey || (event.keyCode < 48 || event.keyCode > 57)) && (event.keyCode < 96 || event.keyCode > 105)) {
			event.preventDefault();
			}
			});

		$(document).on('input', '#phone', function() {
			// Remove any non-numeric characters on input
			this.value = this.value.replace(/[^0-9]/g, '');
		});


		$(function(){
			$("#form").submit(function (e) {

				var name = $("#name").val();
				var email = $("#email").val();
				var phone = $("#phone").val();
				
				let isSubmit=true;
				if (name.trim() === "") {
					$("#nameError").text("Name is required");
					isSubmit=false;
				} else {
					$("#nameError").text("");
				}
				setTimeout(() => {
					$("#nameError").text("");
				}, 3000);


				if (email.trim() === "") {
					$("#emailError").text("Email is required");
					isSubmit=false;
				} else if (!isValidEmail(email)) {
					$("#emailError").text("Please enter a valid email address.");
					isSubmit=false;
				} else {
					$("#emailError").text("");
				}
				setTimeout(() => {
					$("#emailError").text("");
				}, 3000);

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
				setTimeout(() => {
					$("#phoneError").text("");
				}, 3000);

				if(!isSubmit) {
					e.preventDefault(0);
				}

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

		$('.numbers').keyup(function(e) {
			if (/\D/g.test(this.value))
			{
				this.value = this.value.replace(/\D/g, '');
			}
		});
    </script>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/dropzone') }}/dist/css/bs-dropzone.css">
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

        .minus,
        .plus {
            width: 52px;
            height: 36px;
            background: #f2f2f2;
            border-radius: 4px;
            padding: 4px 5px 5px 5px;
            border: 1px solid #ddd;
            display: inline-block;
            vertical-align: middle;
            text-align: center;
            font-size: 20px;
        }

        .inputMinusPlus {
            height: 34px;
            width: 100px;
            text-align: center;
            font-size: 26px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: inline-block;
            vertical-align: middle;
        }

        .fieldset {
            padding: 42px;
        }
    </style>
@endsection

