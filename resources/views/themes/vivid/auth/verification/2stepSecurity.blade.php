@extends($theme.'layouts.app')
@section('title',__('2 Step Verification'))

@section('banner_main_heading')
	@lang('2 Step Verification')
@endsection

@section('banner_heading')
	@lang('twoFA security')
@endsection

@section('content')
	<!-- 2FA Varification section start -->
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
						<form action="{{ route('user.twoFA-Verify') }}" method="post">
							@csrf
							<div class="form_title pb-2">
								<h4>@lang('Varification Code')</h4>
							</div>
							<div class="mb-4">
								<input type="text" name="code" value="{{ old('code') }}"
									   class="form-control"
									   placeholder="@lang('2 FA Code')">

								<div class="text-danger">
									@error('code') @lang($message) @enderror
									@error('error') @lang($message) @enderror
								</div>
							</div>

							<button type="submit" class="btn cmn_btn mt-30 w-100">@lang('Submit')</button>

						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- 2FA Varification section end -->
@endsection
