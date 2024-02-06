@extends($theme.'layouts.app')
@section('page_title',__('Sign Up'))

@section('banner_main_heading')
	@lang('Sign Up')
@endsection

@section('banner_heading')
	@lang('register')
@endsection

@section('content')
	<!-- login_signup_area_start -->
	<section class="login_signup_page">
		<div class="container">
			<div class="row gy-5">
				<div class="col-lg-6 d-none d-lg-block">
					<div class="section_left">
						<div class="image_area">
							<img src="{{ asset($themeTrue.'images/messanging_fun.gif') }}" alt="">
						</div>
					</div>
				</div>

				<div class="col-lg-6">
					<div class="login_signup_form p-4">
						<form action="{{ route('register') }}" method="post">
							@csrf
							<div class="row">
								<div class="form_title pb-2">
									<h4>@lang(optional($template->description)->title)</h4>
								</div>
								<div class="col-sm-12">
									<div class="mb-4">
										<input type="text" class="form-control" name="name" value="{{ old('name') }}"
											   placeholder="@lang('Enter Full Name')">
										<div class="text-danger">@error('name') @lang($message) @enderror</div>
									</div>
								</div>


								<div class="col-sm-6">
									<div class="mb-4">
										<input type="email" name="email" value="{{ old('email') }}" class="form-control"
											   placeholder="@lang('Enter Email')">
										<div class="text-danger">@error('email') @lang($message) @enderror</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="mb-4">
										<input type="text" name="username" value="{{ old('username') }}"
											   class="form-control" placeholder="@lang('Enter Username')">
										<div class="text-danger">@error('username') @lang($message) @enderror</div>
									</div>
								</div>


								@if($referral)
									<div class="col-sm-6">
										<div class="mb-4">
											<input type="text" name="referral" value="{{ old('referral',$referral) }}"
												   class="form-control" placeholder="@lang('Enter Username')">
											<div class="text-danger">@error('referral') @lang($message) @enderror</div>
										</div>
									</div>
								@endif

								<div class="col-sm-6">
									<div class="mb-4">
										<select name="phone_code" class="form-select country_code">
											@foreach($countries as $value)
												<option value="{{$value['phone_code']}}" data-name="{{$value['name']}}"
														data-code="{{$value['code']}}" {{$country_code == $value['code'] ? 'selected' : ''}}>{{ __($value['phone_code']) }}
													<strong> ({{ __($value['name']) }}
														)</strong></option>
											@endforeach
										</select>
									</div>
								</div>

								<div class="col-sm-6">
									<div class="mb-4">
										<input type="text" name="phone" value="{{old('phone')}}" class="form-control"
											   placeholder="@lang('Your Phone Number')"
											   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')">
										@error('phone')
										<p class="text-danger  mt-1">@lang($message)</p>
										@enderror
									</div>

								</div>

								<div class="col-sm-6">
									<div class="mb-3">
										<input type="password" name="password" value="{{ old('password') }}"
											   class="form-control" placeholder="@lang('Password')">
										<div class="text-danger">@error('password') @lang($message) @enderror</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="mb-3">
										<input type="password" name="password_confirmation" class="form-control"
											   placeholder="@lang('Re-type Password')">
									</div>
								</div>

								@if(basicControl()->google_reCaptcha_status == 1 && basicControl()->reCaptcha_status_registration )
									<div class="form-group">
										{!! NoCaptcha::renderJs() !!}
										{!! NoCaptcha::display() !!}
										@error('g-recaptcha-response')
										<div class="text-danger">@lang($message)</div>
										@enderror
									</div>
								@endif

								@if(basicControl()->manual_reCaptcha_status == 1  && basicControl()->reCaptcha_status_registration)
									<div class="form-group mb-4 mt-4">
										<label class="form-label" for="captcha">@lang('Captcha Code')</label>
										<input type="text" tabindex="2"
											   class="form-control  @error('captcha') is-invalid @enderror"
											   name="captcha" value="{{old('captcha')}}" id="captcha"
											   autocomplete="off"
											   placeholder="Enter Captcha" >
										@error('captcha')
										<span class="invalid-feedback">{{ $message }}</span>
										@enderror
									</div>

									<div class="mb-4">
										<div class="input-group input-group-merge d-flex justify-content-between">
											<img src="{{ route('captcha').'?rand='. rand() }}" id='captcha_image'>
											<a class="input-group-append input-group-text"
											   href='javascript: refreshCaptcha();'>
												<i class="fas fa-sync  text-primary"></i>
											</a>
										</div>
									</div>
								@endif

								<div class="input-box col-12">
									<input type="hidden"
										   name="timezone"
										   class="form-control timezone"
										   placeholder="@lang('timezone')"/>
								</div>

								<div class="col">
									<div class="mb-3 form-check d-flex justify-content-between">
										<div class="check">
											<input type="checkbox" class="form-check-input" id="exampleCheck1">
											<label class="form-check-label" for="exampleCheck1">@lang('I Agree with the Terms
												&amp; conditions')</label>
										</div>
									</div>
								</div>

								<div class="col-12">
									<button type="submit"
											class="btn cmn_btn mt-30 w-100">@lang('Create Account')</button>
									<div class="pt-20 text-center">
										@lang('Already have an account?')
										<p class="mb-0 highlight"><a href="{{ route('login') }}">@lang('Login Here')</a>
										</p>
									</div>
								</div>

							</div>
						</form>
					</div>
				</div>
			</div>

		</div>
	</section>
	<!-- login_signup_area_start -->
@endsection

@push('script')
	<script>
		'use strict';
		function refreshCaptcha() {
			let img = document.images['captcha_image'];
			img.src = img.src.substring(
				0, img.src.lastIndexOf("?")
			) + "?rand=" + Math.random() * 1000;
		}

		$('.timezone').val(Intl.DateTimeFormat().resolvedOptions().timeZone);
	</script>
@endpush
