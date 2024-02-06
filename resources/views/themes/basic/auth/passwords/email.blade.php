@extends($theme.'layouts.app')
@section('page_title',__('Reset Password'))

@section('banner_main_heading')
	@lang('Reset password')
@endsection

@section('banner_heading')
	@lang('Forget password')
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
					@if (session('status'))
						<div class="alert alert-success alert-dismissible fade show w-100" role="alert">
							{{ trans(session('status')) }}
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					@endif

					<div class="login_signup_form p-4">
						<form action="{{ route('password.email') }}" method="post">
							@csrf
							<div class="form_title pb-2">
								<h4>@lang(optional($template->description)->title)</h4>
							</div>
							<div class="mb-4">
								<input type="email" name="email" @error('email') is-invalid
									   @enderror class="form-control"
									   placeholder="@lang('Email address')">
								<div class="text-danger">
									@error('email') @lang($message) @enderror
								</div>
							</div>
							<button type="submit" class="btn cmn_btn mt-30 w-100">@lang('Submit')</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- login_signup_area_start -->
@endsection

