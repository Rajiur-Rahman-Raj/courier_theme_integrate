@extends($theme.'layouts.app')
@section('page_title',__('Login'))

@section('banner_main_heading')
	@lang('Sing In')
@endsection

@section('banner_heading')
	@lang('Login')
@endsection

@section('content')
	<!-- login_signup_area_start -->
	<section class="login_signup_page">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 d-none d-lg-block">
					<div class="section_left">
						<div class="image_area">
							<img src="{{ asset($themeTrue.'images/messanging_fun.gif') }}" alt="">
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="login_signup_form p-4">
						<form action="{{ route('login') }}" method="post">
							@csrf
							<div class="form_title pb-2">
								<h4>@lang(optional($template->description)->title)</h4>
							</div>
							<div class="mb-4">
								<input type="text" name="identity" value="{{ old('identity','demouser') }}"
									   class="form-control   @error('email') is-invalid @enderror" autocomplete="off"
									   placeholder="@lang('Username or Email')">
								<div class="text-danger">
									@error('username') @lang($message) @enderror
									@error('email') @lang($message) @enderror
								</div>
							</div>

							<div class="mb-3">
								<input type="password" name="password" value="{{old('password','demouser')}}" class="form-control @error('password') is-invalid @enderror" autocomplete="off"
									   placeholder="@lang('Password')">

								<div class="text-danger">
									@error('password') @lang($message) @enderror
								</div>
							</div>

							@if(basicControl()->google_reCaptcha_status == 1 && basicControl()->reCaptcha_status_login )
								<div class="form-group">
									{!! NoCaptcha::renderJs() !!}
									{!! NoCaptcha::display() !!}
									@error('g-recaptcha-response')
									<div class="text-danger mt-1 mb-1">@lang($message)</div>
									@enderror
								</div>
							@endif

							@if(basicControl()->manual_reCaptcha_status == 1  && basicControl()->reCaptcha_status_login)
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

							<div class="mb-3 form-check d-flex justify-content-between">
								<div class="check">
									<input class="form-check-input" type="checkbox"
										   id="flexCheckDefault">
									<label class="form-check-label" for="exampleCheck1">@lang('Remember me')</label>
								</div>
								<div class="forgot highlight">
									@if (Route::has('password.request'))
										<a href="{{ route('password.request') }}">@lang('Forgot Password')?</a>
									@endif
								</div>
							</div>

							<button type="submit" class="btn cmn_btn mt-30 w-100">@lang('Log In')</button>
							<div class="pt-20 text-center">
								@lang("Don't have an account?")
								<p class="mb-0 highlight">	<a href="{{ route('register') }}">@lang('Create account')</a></p>
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
