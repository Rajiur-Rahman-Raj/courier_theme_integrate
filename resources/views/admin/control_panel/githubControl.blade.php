@extends('admin.layouts.master')
@section('page_title', __('Github Control'))
@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Github Control')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('settings') }}">@lang('Settings')</a>
					</div>
					<div class="breadcrumb-item active">
						<a href="{{ route('socialite.index') }}">@lang('Socialite')</a>
					</div>

					<div class="breadcrumb-item">@lang('Github Control')</div>
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
									<h6 class="m-0 font-weight-bold text-primary">@lang('Github Control')</h6>
								</div>
								<div class="card-body">
									<div class="row justify-content-center">
										<div class="col-md-6">
											<form action="{{ route('github.control') }}" method="post">
												@csrf
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label for="github_client_id">@lang('Client ID')</label>
															<input type="text" name="github_client_id"
																   value="{{ old('github_client_id',env('GITHUB_CLIENT_ID')) }}"
																   placeholder="@lang('Client ID')"
																   class="form-control @error('github_client_id') is-invalid @enderror">
															<div
																class="invalid-feedback">@error('github_client_id') @lang($message) @enderror</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="form-group">
															<label
																for="github_client_secret">@lang('Client Secret')</label>
															<input type="text" name="github_client_secret"
																   value="{{ old('github_client_secret',env('GITHUB_CLIENT_SECRET')) }}"
																   placeholder="@lang('Client Secret')"
																   class="form-control @error('github_client_secret') is-invalid @enderror">
															<div
																class="invalid-feedback">@error('github_client_secret') @lang($message) @enderror</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="form-group">
															<label for="Redirect Url">@lang('Redirect Url')</label>
															<div class="input-group">
																<input type="text" id="webhook"
																	   value="{{ route('socialiteCallback','github') }}"
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
																	<input type="radio" name="github_status" value="0"
																		   class="selectgroup-input" {{ old('github_status', config('basic.github_status')) == 0 ? 'checked' : ''}}>
																	<span class="selectgroup-button">@lang('OFF')</span>
																</label>
																<label class="selectgroup-item">
																	<input type="radio" name="github_status" value="1"
																		   class="selectgroup-input" {{ old('github_status', config('basic.github_status')) == 1 ? 'checked' : ''}}>
																	<span class="selectgroup-button">@lang('ON')</span>
																</label>
															</div>
															@error('github_status')
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
													@lang('Go to the GitHub Developer Settings page:') <a
														href=" https://github.com/settings/developers" target="_blank"> https://github.com/settings/developers</a>
												</li>
												<li>
													@lang('Click on "New OAuth App" to register a new application.')
												</li>
												<li>
													@lang('Fill in the required details for your application:')
												</li>
												<li>
													@lang('Application Name: A user-friendly name for your application.')
												</li>
												<li>
													@lang("Homepage URL: The URL to your application's website or landing page.")
												</li>
												<li>
													@lang('Authorization callback URL: The URL to which GitHub will redirect users after they grant or deny access to your application.')
												</li>
												<li>
													@lang("After registering your application, GitHub will provide you with a Client ID and a Client Secret. These are the credentials you'll use to authenticate with GitHub")
												</li>
											</ul>
										</div>
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
	@if ($errors->any())
		@php
			$collection = collect($errors->all());
			$errors = $collection->unique();
		@endphp
		<script>
			"use strict";
			@foreach ($errors as $error)
			Notiflix.Notify.failure("{{trans($error)}}");
			@endforeach
		</script>
	@endif
@endsection
