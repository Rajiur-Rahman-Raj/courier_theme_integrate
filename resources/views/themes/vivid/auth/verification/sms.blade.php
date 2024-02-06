@extends($theme.'layouts.app')
@section('page_title',__('SMS Verification'))

@section('banner_main_heading')
	@lang('Sms Verification')
@endsection

@section('banner_heading')
	@lang('sms verification')
@endsection

@section('content')
	<!-- Sms Varification section start -->
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
						<form action="{{ route('user.smsVerify') }}" method="post">
							@csrf
							<div class="form_title pb-2">
								<h4>@lang(optional($template->description)->title)</h4>
							</div>
							<div class="mb-4">
								<input type="text" name="code"
									   class="form-control"
									   placeholder="@lang('Code')">

								<div class="text-danger">
									@error('code') @lang($message) @enderror
									@error('error') @lang($message) @enderror
								</div>
							</div>


							<button type="submit" class="btn cmn_btn mt-30 w-100">@lang('Submit')</button>

							@if (Route::has('user.resendCode'))
								<div class="pt-20 text-center">
									@lang("Didn't get code?") <a href="{{route('user.resendCode')}}?type=phone">@lang('Resend code')</a>
									@error('resend')
									<p class="text-danger mt-1">@lang($message)</p>
									@enderror
								</div>
							@endif
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Sms Varification section end -->
@endsection
