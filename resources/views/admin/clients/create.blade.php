@extends('admin.layouts.master')

@section('page_title')
	@lang('Create Customer')
@endsection

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang("Create New Customer")</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active"><a href="{{ route('admin.home') }}">@lang("Dashboard")</a></div>
					<div class="breadcrumb-item"><a href="{{route('clientList')}}">@lang("Customers")</a></div>
					<div class="breadcrumb-item">@lang("Create Customer")</div>
				</div>
			</div>
		</section>

		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-12 col-lg-12">
					<div class="card mb-4 card-primary shadow-sm">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h5>@lang("Create Customer")</h5>

							<a href="{{route('clientList')}}" class="btn btn-sm  btn-primary mr-2">
								<span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
							</a>
						</div>

						<div class="card-body">
							<form method="post" action="{{ route('clientStore') }}"
								  class="mt-4" enctype="multipart/form-data">
								@csrf

								<div class="row">
									<div class="col-sm-12 col-md-3 mb-3">
										<label for="branch_id"> @lang('Select Branch')</label>
										<select name="branch_id"
												class="form-control @error('branch_id') is-invalid @enderror select2">
											<option value="" disabled selected>@lang('Select Branch')</option>
											@foreach($allBranches as $branch)
												<option
													value="{{ $branch->id }}">@lang($branch->branch_name)</option>
											@endforeach
										</select>

										<div class="invalid-feedback">
											@error('branch_id') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>
									<div class="col-sm-12 col-md-3 mb-3">
										<label for="name"> @lang('Name')</label>
										<input type="text" name="name"
											   class="form-control @error('name') is-invalid @enderror managerEmail"
											   value="{{ old('name') }}">
										<div class="invalid-feedback">
											@error('name') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>

									<div class="col-sm-12 col-md-3 mb-3">
										<label for="username"> @lang('Username')</label>
										<input type="text" name="username"
											   class="form-control @error('username') is-invalid @enderror managerEmail"
											   value="{{ old('username') }}">
										<div class="invalid-feedback">
											@error('username') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>

									<div class="col-sm-12 col-md-3 mb-3">
										<label for="email"> @lang('Email')</label>
										<input type="text" name="email"
											   class="form-control @error('email') is-invalid @enderror"
											   value="{{ old('email') }}">
										<div class="invalid-feedback">
											@error('email') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>

									<div class="col-sm-12 col-md-4 mb-3">
										<label for="phone"> @lang('Phone')</label>
										<input type="text" name="phone"
											   class="form-control @error('phone') is-invalid @enderror"
											   value="{{ old('phone') }}">
										<div class="invalid-feedback">
											@error('phone') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>

									<div class="col-sm-12 col-md-4 mb-3">
										<label for="national_id"> @lang('National Id') <span class="font-weight-bold"><sub>(@lang('optional'))</sub></span></label>
										<input type="text" name="national_id"
											   class="form-control @error('national_id') is-invalid @enderror"
											   value="{{ old('national_id') }}">
										<div class="invalid-feedback">
											@error('national_id') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>

									<div class="col-sm-12 col-md-4 mb-3">
										<label for="password"> @lang('Password') </label>
										<input type="password" name="password"
											   class="form-control @error('password') is-invalid @enderror"
											   value="{{ old('password') }}">
										<div class="invalid-feedback">
											@error('password') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>

									<div class="col-sm-12 col-md-3 mb-3">
										<label for="country_id"> @lang('Select Country')</label>
										<select name="country_id"
												class="form-control @error('country_id') is-invalid @enderror selectedCountry select2">
											<option value="" disabled selected>@lang('Select Country')</option>
											@foreach($allCountires as $country)
												<option value="{{ $country->id }}">@lang($country->name)</option>
											@endforeach
										</select>

										<div class="invalid-feedback">
											@error('country_id') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>

									<div class="col-sm-12 col-md-3 mb-3">
										<label for="state_id"> @lang('Select State')</label>
										<select name="state_id"
												class="form-control @error('state_id') is-invalid @enderror selectedState select2"></select>

										<div class="invalid-feedback">
											@error('state_id') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>

									<div class="col-sm-12 col-md-3 mb-3">
										<label for="city_id"> @lang('Select city') <span class="font-weight-bold"><sub>(@lang('optional'))</sub></span></label>
										<select name="city_id"
												class="form-control @error('city_id') is-invalid @enderror selectedCity select2"></select>

										<div class="invalid-feedback">
											@error('city_id') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>

									<div class="col-sm-12 col-md-3 mb-3">
										<label for="area_id"> @lang('Select Area') <span class="font-weight-bold"><sub>(@lang('optional'))</sub></span></label>
										<select name="area_id"
												class="form-control @error('area_id') is-invalid @enderror selectedArea select2">

										</select>

										<div class="invalid-feedback">
											@error('area_id') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>

								</div>

								<div class="row">
									<div class="col-sm-12 col-md-12 mb-3">
										<label for="address"> @lang('Address') </label>
										<textarea class="form-control @error(' ') is-invalid @enderror" name="address"
												  rows="5" value="{{ old('address') }}">{{old('address')}}</textarea>

										<div class="invalid-feedback d-block">
											@error('address') @lang($message) @enderror
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12 col-md-3">
										<div class="mb-4">
											<label class="col-form-label">@lang("Photo")</label>
											<div id="image-preview" class="image-preview"
												 style="background-image: url({{ getFile(config('location.category.path'))}}">
												<label for="image-upload"
													   id="image-label">@lang('Choose File')</label>
												<input type="file" name="image" class=""
													   id="image-upload"/>
											</div>
											@error('image')
											<span class="text-danger">{{ $message }}</span>
											@enderror
										</div>
									</div>

									<div class="col-md-4">
										<label>@lang('User Type')</label>
										<div class="selectgroup w-100">
											<label class="selectgroup-item">
												<input type="radio" name="client_type" value="1"
													   class="selectgroup-input"
													   checked {{ old('client_type') == 1 ? 'checked' : ''}}>
												<span class="selectgroup-button">@lang('Sender')</span>
											</label>
											<label class="selectgroup-item">
												<input type="radio" name="client_type" value="2"
													   class="selectgroup-input" {{ old('client_type') == 2 ? 'checked' : ''}}>
												<span class="selectgroup-button">@lang('Receiver')</span>
											</label>
										</div>
									</div>

									<div class="col-md-4">
										<label>@lang('Status')</label>
										<div class="selectgroup w-100">
											<label class="selectgroup-item">
												<input type="radio" name="status" value="0"
													   class="selectgroup-input" {{ old('status') == 0 ? 'checked' : ''}}>
												<span class="selectgroup-button">@lang('OFF')</span>
											</label>
											<label class="selectgroup-item">
												<input type="radio" name="status" value="1"
													   class="selectgroup-input"
													   checked {{ old('status') == 1 ? 'checked' : ''}}>
												<span class="selectgroup-button">@lang('ON')</span>
											</label>
										</div>
									</div>
								</div>

								<button type="submit"
										class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">@lang('Save')</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endsection

		@push('extra_scripts')
			<script src="{{ asset('assets/dashboard/js/jquery.uploadPreview.min.js') }}"></script>
		@endpush

		@section('scripts')
			@include('partials.locationJs')
			<script>
				'use strict';
				$(document).ready(function () {
					$.uploadPreview({
						input_field: "#image-upload",
						preview_box: "#image-preview",
						label_field: "#image-label",
						label_default: "Choose File",
						label_selected: "Change File",
						no_label: false
					});
				});


			</script>
@endsection
