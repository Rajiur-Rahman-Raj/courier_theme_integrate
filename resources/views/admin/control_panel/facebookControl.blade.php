@extends('admin.layouts.master')
@section('page_title', __('Facebook Control'))
@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Facebook Control')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('settings') }}">@lang('Settings')</a>
					</div>
					<div class="breadcrumb-item active">
						<a href="{{ route('socialite.index') }}">@lang('Socialite')</a>
					</div>

					<div class="breadcrumb-item">@lang('Facebook Control')</div>
				</div>
			</div>

			<div class="section-body">
				<div class="row mt-sm-4">
					<div class="col-12 col-md-4 col-lg-3">
						@include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.Socialite'), 'suffix' => ''])
					</div>
					<div class="col-12 col-md-8 col-lg-9">
						<div class="container-fluid" id="container-wrapper">
							<div class="card mb-4 card-primary shadow">
								<div
									class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
									<h6 class="m-0 font-weight-bold text-primary">@lang('Facebook Control')</h6>
								</div>
								<div class="card-body">
									<div class="row justify-content-center">
										<div class="col-md-6">
											<form action="{{ route('facebook.control') }}" method="post">
												@csrf
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label for="facebook_client_id">@lang('Client ID')</label>
															<input type="text" name="facebook_client_id"
																   value="{{ old('facebook_client_id',env('FACEBOOK_CLIENT_ID')) }}"
																   placeholder="@lang('Client ID')"
																   class="form-control @error('facebook_client_id') is-invalid @enderror">
															<div
																class="invalid-feedback">@error('facebook_client_id') @lang($message) @enderror</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="form-group">
															<label
																for="google_client_secret">@lang('Client Secret')</label>
															<input type="text" name="facebook_client_secret"
																   value="{{ old('facebook_client_secret',env('FACEBOOK_CLIENT_SECRET')) }}"
																   placeholder="@lang('Client Secret')"
																   class="form-control @error('facebook_client_secret') is-invalid @enderror">
															<div
																class="invalid-feedback">@error('facebook_client_secret') @lang($message) @enderror</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="form-group">
															<label for="Redirect Url">@lang('Redirect Url')</label>
															<div class="input-group">
																<input type="text" id="webhook"
																	   value="{{ route('socialiteCallback','facebook') }}"
																	   class="form-control" readonly>
																<div class="input-group-prepend">
																	<button type="button" onclick="webhookCopy()"
																			class="btn btn-sm btn-success">@lang('copy')</button>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label>@lang('Status')</label>
															<div class="selectgroup w-100">
																<label class="selectgroup-item">
																	<input type="radio" name="facebook_status" value="0"
																		   class="selectgroup-input" {{ old('facebook_status', config('basic.facebook_status')) == 0 ? 'checked' : ''}}>
																	<span class="selectgroup-button">@lang('OFF')</span>
																</label>
																<label class="selectgroup-item">
																	<input type="radio" name="facebook_status" value="1"
																		   class="selectgroup-input" {{ old('facebook_status', config('basic.facebook_status')) == 1 ? 'checked' : ''}}>
																	<span class="selectgroup-button">@lang('ON')</span>
																</label>
															</div>
															@error('facebook_status')
															<span class="text-danger" role="alert">
																<strong>{{ __($message) }}</strong>
															</span>
															@enderror
														</div>
													</div>
												</div>
												<div class="form-group">
													<button type="submit" name="submit"
															class="btn btn-primary btn-sm btn-block">@lang('Save changes')</button>
												</div>
											</form>
										</div>
										<div class="col-md-6">
											<h6 class="m-0 font-weight-bold text-primary">@lang('How To Set Up')</h6>
											<ul class="mt-3">
												<li>
													@lang('Go to the Facebook Developer Dashboard:') <a
														href="https://developers.facebook.com/" target="_blank">https://developers.facebook.com/</a>
												</li>
												<li>
													@lang('Click on "Get Started" and then "Create App" in the top right
													corner.')
												</li>
												<li>
													@lang('Choose the "For Everything Else" option to create an app for
													integrating with websites or apps.')
												</li>
												<li>
													@lang('Once your app is created, go to the "Settings" tab on the left-hand
													side and then click on "Basic."')
												</li>
												<li>
													@lang('Fill in the required information, such as App Display Name, App
													Contact Email, Privacy Policy URL, and Terms of Service URL.')
												</li>
												<li>
													@lang('Under the "Settings" tab, click on "Add Platform."')
												</li>
												<li>
													@lang('Choose the platform that matches your use case. For a website,
													select "Website."')
												</li>
												<li>
													@lang("Enter your website's URL and save the changes.")
												</li>
												<li>
													@lang("After adding a platform, you will see the 'App ID' and 'App Secret'
													on the Basic Settings page. These are the credentials you'll use to
													authenticate with Facebook.")
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
		</section>
	</div>
@endsection
@section('scripts')
	<script>
		'use strict'

		function webhookCopy() {
			var copyText = document.getElementById("webhook");
			copyText.select();
			copyText.setSelectionRange(0, 99999);
			navigator.clipboard.writeText(copyText.value);
			Notiflix.Notify.success(`${copyText.value} Copied`);
		}
	</script>
@endsection
