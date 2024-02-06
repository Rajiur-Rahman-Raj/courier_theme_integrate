@extends('admin.layouts.master')

@section('page_title')
	@lang('Default Shipping Rates')
@endsection

@section('content')
	@if(Session::has('active-tab'))
		@php
			$active_tab= \Illuminate\Support\Facades\Session::get('active-tab');
		@endphp
	@endif

	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang("Default Shipping Rate")</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active"><a href="{{ route('admin.home') }}">@lang("Dashboard")</a></div>
					<div class="breadcrumb-item">@lang("Shipping Rates")</div>
					<div class="breadcrumb-item">@lang("Default Shipping Rate")</div>
				</div>
			</div>
		</section>
		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-12 col-lg-12">
					<div class="card mb-4 card-primary shadow-sm">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h4>@lang('Update Shipping Rate')</h4>
						</div>

						<div class="card-body">
							@include('errors.error')
							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active show" data-toggle="tab"
									   href="#tab1" role="tab"
									   aria-controls="tab1"
									   id="operatorCountry"
									   aria-selected="true">@lang('Operator Country')</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" data-toggle="tab"
									   href="#tab2" role="tab"
									   aria-controls="tab2"
									   id="internationalCountry"
									   aria-selected="false">@lang('Internationally')</a>
								</li>
							</ul>

							<div class="tab-content mt-2" id="myTabContent">
								<div class="tab-pane fade show active"
									 id="tab1" role="tabpanel">
									<form method="post" action="{{ route('defaultShippingRateOperatorCountryUpdate', $defaultShippingRateOperatorCountry->id) }}" class="mt-4">
										@csrf
										@method('put')
										<div class="row">

											<div class="col-sm-12 col-md-3 mb-3">
												<label for="operator_country_id">@lang('Operator Country') </label>
												<input type="hidden" name="operator_country_id" value="{{ optional($basicControl->operatorCountry)->id }}" class="form-control @error('operator_country_id') is-invalid @enderror">
												<input type="text" class="form-control" value="{{ optional($basicControl->operatorCountry)->name }}" readonly>
												<div class="invalid-feedback">
													@error('operator_country_id') @lang($message) @enderror
												</div>
											</div>

											<div class="col-sm-12 col-md-3 mb-3">
												<label for="shipping_date_id"> @lang('Shipping Date') </label>

												<select name="shipping_date_id"
														class="form-control @error('shipping_date_id') is-invalid @enderror select2 form-select select-shipping-date" required>
													<option value="" disabled selected>@lang('Select date')</option>
													@foreach($allShippingDates as $shippingDate)
														<option
															value="{{ $shippingDate->id }}" {{ $shippingDate->id == $defaultShippingRateOperatorCountry->shipping_date_id ? 'selected' : '' }}> {{ $shippingDate->shipping_days == 0 ? 'Same ' : $shippingDate->shipping_days }} {{ $shippingDate->shipping_days == 0 || $shippingDate->shipping_days == 1 ? __('Day') : __('Days') }}</option>
													@endforeach
												</select>

												<div class="invalid-feedback">
													@error('shipping_date_id') @lang($message) @enderror
												</div>
											</div>

											<div class="col-sm-12 col-md-3 mb-3">
												<label for="pickup_cost"> @lang('Pickup Cost') </label>
												<div class="input-group">
													<input type="text" name="pickup_cost"
														   class="form-control @error('pickup_cost') is-invalid @enderror"
														   placeholder="0.00"
														   value="{{ old('pickup_cost', $defaultShippingRateOperatorCountry->pickup_cost) }}"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0" required>
													<div class="input-group-append">
														<div class="form-control">
															@lang(config('basic.currency_symbol'))
														</div>
													</div>
													<div class="invalid-feedback">
														@error('pickup_cost') @lang($message) @enderror
													</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-3 mb-3">
												<label for="supply_cost"> @lang('Supply Cost') </label>
												<div class="input-group">
													<input type="text" name="supply_cost"
														   class="form-control @error('supply_cost') is-invalid @enderror"
														   placeholder="0.00"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('supply_cost', $defaultShippingRateOperatorCountry->supply_cost) }}">

													<div class="input-group-append">
														<div class="form-control">
															@lang(config('basic.currency_symbol'))
														</div>
													</div>
													<div class="invalid-feedback">
														@error('supply_cost') @lang($message) @enderror
													</div>
												</div>

											</div>

											<div class="col-sm-12 col-md-3 mb-3">
												<label for="shipping_cost"> @lang('Shipping Cost') </label>

												<div class="input-group">
													<input type="text" name="shipping_cost"
														   class="form-control @error('shipping_cost') is-invalid @enderror"
														   placeholder="0.00"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('shipping_cost', $defaultShippingRateOperatorCountry->shipping_cost) }}">

													<div class="input-group-append">
														<div class="form-control">
															@lang(config('basic.currency_symbol'))
														</div>
													</div>

													<div class="invalid-feedback">
														@error('shipping_cost') @lang($message) @enderror
													</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-3 mb-3">
												<label for="return_shipping_cost"> @lang('Returned Shipment Cost')
												</label>
												<div class="input-group">
													<input type="text" name="return_shipment_cost"
														   class="form-control @error('return_shipment_cost') is-invalid @enderror"
														   placeholder="0.00"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('return_shipment_cost', $defaultShippingRateOperatorCountry->return_shipment_cost) }}">

													<div class="input-group-append">
														<div class="form-control">
															@lang(config('basic.currency_symbol'))
														</div>
													</div>

													<div class="invalid-feedback">
														@error('return_shipment_cost') @lang($message) @enderror
													</div>
												</div>

											</div>

											<div class="col-sm-12 col-md-3 mb-3">
												<label for="default_tax"> @lang('Tax') </label>
												<div class="input-group">
													<input type="text" name="default_tax"
														   class="form-control @error('default_tax') is-invalid @enderror"
														   placeholder="0"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('default_tax', $defaultShippingRateOperatorCountry->default_tax) }}">

													<div class="input-group-append">
														<div class="form-control">
															@lang('%')
														</div>
													</div>
													<div class="invalid-feedback">
														@error('default_tax') @lang($message) @enderror
													</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-3 mb-3">
												<label for="default_insurance"> @lang('Insurance') </label>
												<div class="input-group">
													<input type="text" name="default_insurance"
														   class="form-control @error('default_insurance') is-invalid @enderror"
														   placeholder="0"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('default_insurance', $defaultShippingRateOperatorCountry->default_insurance) }}">

													<div class="input-group-append">
														<div class="form-control">
															@lang(config('basic.currency_symbol'))
														</div>
													</div>

													<div class="invalid-feedback">
														@error('default_insurance') @lang($message) @enderror
													</div>
												</div>
											</div>
										</div>

										<button type="submit" class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">@lang('Save')</button>
									</form>
								</div>

								<div class="tab-pane fade"
									 id="tab2" role="tabpanel">
									<form method="post" action="{{ route('defaultShippingRateInternationallyUpdate', $defaultShippingRateInternationally->id) }}" class="mt-4">
										@csrf
										@method('put')

										<input type="hidden" class="internationalTab" name="tab" value="@if(\Illuminate\Support\Facades\Session::has('active-tab')){{ $active_tab }} @else 0 @endif ">

										<div class="row">
											<div class="col-sm-12 col-md-3 mb-3">
												<label for="shipping_date_id"> @lang('Shipping Date') </label>
												<select name="shipping_date_id"
														class="form-control @error('shipping_date_id') is-invalid @enderror select2 form-select select-shipping-date">
													<option value="" disabled selected>@lang('Select date')</option>
													@foreach($allShippingDates as $shippingDate)
														<option
															value="{{ $shippingDate->id }}" {{ $shippingDate->id == $defaultShippingRateInternationally->shipping_date_id ? 'selected' : '' }}> {{ $shippingDate->shipping_days == 0 ? 'Same ' : $shippingDate->shipping_days }} {{ $shippingDate->shipping_days == 0 || $shippingDate->shipping_days == 1 ? __('Day') : __('Days') }}</option>
													@endforeach
												</select>

												<div class="invalid-feedback">
													@error('shipping_date_id') @lang($message) @enderror
												</div>
												<div class="valid-feedback"></div>
											</div>

											<div class="col-sm-12 col-md-3 mb-3">
												<label for="pickup_cost"> @lang('Pickup Cost') </label>
												<div class="input-group">
													<input type="text" name="pickup_cost"
														   class="form-control @error('pickup_cost') is-invalid @enderror"
														   placeholder="0.00"
														   value="{{ old('pickup_cost', $defaultShippingRateInternationally->pickup_cost) }}"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0" required>
													<div class="input-group-append">
														<div class="form-control">
															@lang(config('basic.currency_symbol'))
														</div>
													</div>
													<div class="invalid-feedback">
														@error('pickup_cost') @lang($message) @enderror
													</div>
													<div class="valid-feedback"></div>
												</div>
											</div>

											<div class="col-sm-12 col-md-3 mb-3">
												<label for="supply_cost"> @lang('Supply Cost') </label>
												<div class="input-group">
													<input type="text" name="supply_cost"
														   class="form-control @error('supply_cost') is-invalid @enderror"
														   placeholder="0.00"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('supply_cost', $defaultShippingRateInternationally->supply_cost) }}">

													<div class="input-group-append">
														<div class="form-control">
															@lang(config('basic.currency_symbol'))
														</div>
													</div>
													<div class="invalid-feedback">
														@error('supply_cost') @lang($message) @enderror
													</div>
												</div>

											</div>

											<div class="col-sm-12 col-md-3 mb-3">
												<label for="shipping_cost"> @lang('Shipping Cost') </label>

												<div class="input-group">
													<input type="text" name="shipping_cost"
														   class="form-control @error('shipping_cost') is-invalid @enderror"
														   placeholder="0.00"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('shipping_cost', $defaultShippingRateInternationally->shipping_cost) }}">

													<div class="input-group-append">
														<div class="form-control">
															@lang(config('basic.currency_symbol'))
														</div>
													</div>

													<div class="invalid-feedback">
														@error('shipping_cost') @lang($message) @enderror
													</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-4 mb-3">
												<label for="return_shipment_cost"> @lang('Returned Shipment Cost')</label>
												<div class="input-group">
													<input type="text" name="return_shipment_cost"
														   class="form-control @error('return_shipment_cost') is-invalid @enderror"
														   placeholder="0.00"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('return_shipment_cost', $defaultShippingRateInternationally->return_shipment_cost) }}">

													<div class="input-group-append">
														<div class="form-control">
															@lang(config('basic.currency_symbol'))
														</div>
													</div>

													<div class="invalid-feedback">
														@error('return_shipment_cost') @lang($message) @enderror
													</div>
												</div>

											</div>

											<div class="col-sm-12 col-md-4 mb-3">
												<label for="default_tax"> @lang('Tax') </label>
												<div class="input-group">
													<input type="text" name="default_tax"
														   class="form-control @error('default_tax') is-invalid @enderror"
														   placeholder="0"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('default_tax', $defaultShippingRateInternationally->default_tax) }}">

													<div class="input-group-append">
														<div class="form-control">
															@lang('%')
														</div>
													</div>
													<div class="invalid-feedback">
														@error('default_tax') @lang($message) @enderror
													</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-4 mb-3">
												<label for="default_insurance"> @lang('Insurance') </label>
												<div class="input-group">
													<input type="text" name="default_insurance"
														   class="form-control @error('default_insurance') is-invalid @enderror"
														   placeholder="0"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('default_insurance', $defaultShippingRateInternationally->default_insurance) }}">

													<div class="input-group-append">
														<div class="form-control">
															@lang(config('basic.currency_symbol'))
														</div>
													</div>

													<div class="invalid-feedback">
														@error('default_insurance') @lang($message) @enderror
													</div>
												</div>
											</div>
										</div>
										<button type="submit" class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">@lang('Save')</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- Create New Shipping Date --}}
		<div id="add-package-modal" class="modal fade" tabindex="-1" role="dialog"
			 aria-labelledby="primary-header-modalLabel"
			 aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title text-dark font-weight-bold"
							id="primary-header-modalLabel">@lang('Create New Date')</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					</div>
					<form action="{{ route('shippingDateStore') }}" method="post">
						@csrf
						<div class="modal-body">
							<div class="col-12">
								<label for="shipping_days">@lang('Shipping Date') <span class="text-danger">*</span>
									( @lang('0 means same day') )</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<div class="form-control">
											@lang('After')
										</div>
									</div>
									<input type="text" onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
										   class="form-control @error('shipping_days') is-invalid @enderror"
										   name="shipping_days" placeholder="@lang('1')" min="0" required/>

									<div class="input-group-append">
										<div class="form-control">
											@lang('Day(s)')
										</div>
									</div>
									<div class="invalid-feedback">
										@error('shipping_days') @lang($message) @enderror
									</div>
								</div>
							</div>


							<div class="col-md-12 my-3">
								<label for="">@lang('Status') </label>
								<div class="selectgroup w-100">
									<label class="selectgroup-item">
										<input type="radio" name="status" value="0" class="selectgroup-input">
										<span class="selectgroup-button">@lang('OFF')</span>
									</label>
									<label class="selectgroup-item">
										<input type="radio" name="status" value="1" class="selectgroup-input" checked>
										<span class="selectgroup-button">@lang('ON')</span>
									</label>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
							<button type="submit" class="btn btn-primary">@lang('save')</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		@endsection

		@push('extra_scripts')
			<script src="{{ asset('assets/dashboard/js/jquery.uploadPreview.min.js') }}"></script>
		@endpush

		@section('scripts')
			@include('partials.getParcelUnit')
			@include('partials.select2Create')
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


					let data = $('.internationalTab').val();

					if (data != 0){
						$('#operatorCountry').removeClass('active show');
						$('#internationalCountry').addClass('active show');
					}

				});

			</script>
@endsection
