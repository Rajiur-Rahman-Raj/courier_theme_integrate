@extends('admin.layouts.master')
@section('page_title')
	@lang('Create Shipping Rate')
@endsection

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Create Shipping Rate For') (@lang(optional(basicControl()->operatorCountry)->name))</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active"><a href="{{ route('admin.home') }}">@lang("Dashboard")</a></div>
					<div class="breadcrumb-item"><a
							href="{{ route('operatorCountryRate', 'state') }}">@lang("Shipping Rate List")</a>
					</div>
					<div class="breadcrumb-item">@lang("Create Shipping Rate")</div>
				</div>
			</div>
		</section>

		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-12 col-lg-12">
					<div class="card mb-4 card-primary shadow-sm">
						<div class="pt-4 px-4 d-flex flex-row align-items-center justify-content-between">
							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active show" data-toggle="tab"
									   href="#tab1" role="tab"
									   aria-controls="tab1"
									   aria-selected="true">@lang('State Wise')</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab"
									   href="#tab2" role="tab"
									   aria-controls="tab2"
									   aria-selected="false">@lang('City Wise')</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab"
									   href="#tab3" role="tab"
									   aria-controls="tab3"
									   aria-selected="false">@lang('Area Wise')</a>
								</li>
							</ul>

							<a href="{{ route('operatorCountryRate', 'state') }}"
							   class="btn btn-sm  btn-primary mr-2 justify-content-end">
								<span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
							</a>

						</div>
						<div class="card-body pt-0">
							{{--							@include('errors.error')--}}
							<div class="tab-content mt-2" id="myTabContent">
								<div class="tab-pane fade show active"
									 id="tab1" role="tabpanel">
									<form action="{{ route('shippingRateOperatorCountry.store', 'state-wise') }}"
										  method="post"
										  action="" class="mt-4">
										@csrf
										<div class="row">
											<input type="hidden" name="operator_country_id"
												   value="{{ optional($basicControl->operatorCountry)->id }}">

											<div class="col-sm-12 col-md-6 mb-3">
												<label for="state_id">@lang('From State') </label>
												<select name="from_state_id"
														class="form-control @error('from_state_id') is-invalid @enderror select2 select2State">
													<option value="" selected disabled>@lang('Select State')</option>
													@foreach(optional($basicControl->operatorCountry)->state() as $state)
														<option value="{{ $state->id }}">@lang($state->name)</option>
													@endforeach
												</select>
												<div class="invalid-feedback">
													@error('from_state_id') @lang($message) @enderror
												</div>
											</div>

											<div class="col-sm-12 col-md-6 mb-3">
												<label for="to_state_id">@lang('To State') </label>
												<select name="to_state_id"
														class="form-control @error('to_state_id') is-invalid @enderror select2 select2State">
													<option value="" selected disabled>@lang('Select State')</option>
													@foreach(optional($basicControl->operatorCountry)->state() as $state)
														<option value="{{ $state->id }}">@lang($state->name)</option>
													@endforeach
												</select>
												<div class="invalid-feedback">
													@error('to_state_id') @lang($message) @enderror
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-12 mt-4 mb-1">
												<h6>@lang('Costs For The First ') <span
														class="cost-per-unit">(@lang('UNIT'))</span></h6>
												<hr>
											</div>


											<div class="col-sm-12 col-md-3 mb-3">
												<label for="parcel_type_id">@lang('Parcel Type') </label>
												<div class="input-group">
													<select name="parcel_type_id"
															class="form-control @error('parcel_type_id') is-invalid @enderror select2 select2ParcelType selectedParcelType">
														<option value="" selected
																disabled>@lang('Select Parcel Type')</option>
														@foreach($allParcelTypes as $parcel_type)
															<option
																value="{{ $parcel_type->id }}">@lang($parcel_type->parcel_type)</option>
														@endforeach
													</select>

													<div class="invalid-feedback">
														@error('parcel_type_id') @lang($message) @enderror
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
														   value="{{ old('shipping_cost') }}">
													<div class="input-group-append">
														<div class="form-control">
															{{ config('basic.currency_symbol') }}
														</div>
													</div>
													<div class="invalid-feedback">
														@error('shipping_cost') @lang($message) @enderror
													</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-2 mb-3">
												<label for="return_shipment_cost"> @lang('Returned Shipment Cost')</label>
												<div class="input-group">
													<input type="text" name="return_shipment_cost"
														   class="form-control @error('return_shipment_cost') is-invalid @enderror"
														   placeholder="0.00"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('return_shipment_cost') }}">
													<div class="input-group-append">
														<div class="form-control">
															{{ config('basic.currency_symbol') }}
														</div>
													</div>
													<div class="invalid-feedback">
														@error('return_shipment_cost') @lang($message) @enderror
													</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-2 mb-3">
												<label for="tax"> @lang('Tax') </label>
												<div class="input-group">
													<input type="text" name="tax"
														   class="form-control @error('tax') is-invalid @enderror"
														   placeholder="0"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('tax') }}">

													<div class="input-group-append">
														<div class="form-control">
															@lang('%')
														</div>
													</div>
													<div class="invalid-feedback">
														@error('tax') @lang($message) @enderror
													</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-2 mb-3">
												<label for="insurance"> @lang('Insurance')</label>
												<div class="input-group">
													<input type="text" name="insurance"
														   class="form-control @error('insurance') is-invalid @enderror"
														   placeholder="0"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('insurance') }}">
													<div class="input-group-append">
														<div class="form-control">
															{{ config('basic.currency_symbol') }}
														</div>
													</div>
													<div class="invalid-feedback">
														@error('insurance') @lang($message) @enderror
													</div>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-12 mt-4 mb-1">
												<h6>@lang('Cost For Cash on Delivary')</h6>
												<hr>
											</div>

											<div class="col-sm-12 col-md-3 mb-3">
												<label for="cash_on_delivery_cost"> @lang('Shipping Cost') </label>

												<div class="input-group">
													<input type="text" name="cash_on_delivery_cost"
														   class="form-control @error('cash_on_delivery_cost') is-invalid @enderror"
														   placeholder="0.00"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('cash_on_delivery_cost') }}">
													<div class="input-group-append">
														<div class="form-control">
															{{ config('basic.currency_symbol') }}
														</div>
													</div>
													<div class="invalid-feedback">
														@error('cash_on_delivery_cost') @lang($message) @enderror
													</div>
												</div>
											</div>
										</div>

										<button type="submit"
												class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">@lang('Save')</button>
									</form>
								</div>

								<div class="tab-pane fade"
									 id="tab2" role="tabpanel">
									<form action="{{ route('shippingRateOperatorCountry.store', 'city-wise') }}"
										  method="post"
										  action="" class="mt-4">
										@csrf
										<div class="row">

											<input type="hidden" name="operator_country_id"
												   value="{{ optional($basicControl->operatorCountry)->id }}">

											<div class="col-sm-12 col-md-6 mb-3">
												<label for="state_id">@lang('From State') </label>
												<select name="from_state_id"
														class="form-control @error('from_state_id') is-invalid @enderror select2 select2State selectedFromState">
													<option value="" selected disabled>@lang('Select State')</option>
													@foreach(optional($basicControl->operatorCountry)->state() as $state)
														<option value="{{ $state->id }}">@lang($state->name)</option>
													@endforeach
												</select>
												<div class="invalid-feedback">
													@error('from_state_id') @lang($message) @enderror
												</div>
											</div>

											<div class="col-sm-12 col-md-6 mb-3">
												<label for="from_city_id">@lang('Select City')</label>
												<select name="from_city_id"
														class="form-control @error('from_city_id') is-invalid @enderror select2 select2City selectedFromCity">

												</select>
												<div class="invalid-feedback">
													@error('from_city_id') @lang($message) @enderror
												</div>
											</div>

											<div class="col-sm-12 col-md-6 mb-3">
												<label for="to_state_id">@lang('To State') </label>
												<select name="to_state_id"
														class="form-control @error('to_state_id') is-invalid @enderror select2 select2State selectedToState">
													<option value="" selected disabled>@lang('Select State')</option>
													@foreach(optional($basicControl->operatorCountry)->state() as $state)
														<option value="{{ $state->id }}">@lang($state->name)</option>
													@endforeach
												</select>
												<div class="invalid-feedback">
													@error('to_state_id') @lang($message) @enderror
												</div>
											</div>

											<div class="col-sm-12 col-md-6 mb-3">
												<label for="to_city_id">@lang('Select City')</label>
												<select name="to_city_id"
														class="form-control @error('to_city_id') is-invalid @enderror select2 select2City selectedToCity">
												</select>
												<div class="invalid-feedback">
													@error('to_city_id') @lang($message) @enderror
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-12 mt-4 mb-1">
												<h6>@lang('Costs For The First ') <span
														class="cost-per-unit">(@lang('UNIT'))</span></h6>
												<hr>
											</div>


											<div class="col-sm-12 col-md-3 mb-3">
												<label for="parcel_type_id">@lang('Parcel Type') </label>
												<div class="input-group">
													<select name="parcel_type_id"
															class="form-control @error('parcel_type_id') is-invalid @enderror select2 select2ParcelType selectedParcelType">
														<option value="" selected
																disabled>@lang('Select Parcel Type')</option>
														@foreach($allParcelTypes as $parcel_type)
															<option
																value="{{ $parcel_type->id }}">@lang($parcel_type->parcel_type)</option>
														@endforeach
													</select>

													<div class="invalid-feedback">
														@error('parcel_type_id') @lang($message) @enderror
													</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-3 mb-3">
												<label for="shipping_cost"> @lang('Shipping Cost')</label>

												<div class="input-group">
													<input type="text" name="shipping_cost"
														   class="form-control @error('shipping_cost') is-invalid @enderror"
														   placeholder="0.00"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('shipping_cost') }}">
													<div class="input-group-append">
														<div class="form-control">
															{{ config('basic.currency_symbol') }}
														</div>
													</div>
													<div class="invalid-feedback">
														@error('shipping_cost') @lang($message) @enderror
													</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-2 mb-3">
												<label for="return_shipment_cost"> @lang('Returned Shipment Cost')
												<div class="input-group">
													<input type="text" name="return_shipment_cost"
														   class="form-control @error('return_shipment_cost') is-invalid @enderror"
														   placeholder="0.00"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('return_shipment_cost') }}">
													<div class="input-group-append">
														<div class="form-control">
															{{ config('basic.currency_symbol') }}
														</div>
													</div>
													<div class="invalid-feedback">
														@error('return_shipment_cost') @lang($message) @enderror
													</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-2 mb-3">
												<label for="tax"> @lang('Tax') </label>
												<div class="input-group">
													<input type="text" name="tax"
														   class="form-control @error('tax') is-invalid @enderror"
														   placeholder="0"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('tax') }}">

													<div class="input-group-append">
														<div class="form-control">
															@lang('%')
														</div>
													</div>
													<div class="invalid-feedback">
														@error('tax') @lang($message) @enderror
													</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-2 mb-3">
												<label for="insurance"> @lang('Insurance')</label>
												<div class="input-group">
													<input type="text" name="insurance"
														   class="form-control @error('insurance') is-invalid @enderror"
														   placeholder="0"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('insurance') }}">
													<div class="input-group-append">
														<div class="form-control">
															{{ config('basic.currency_symbol') }}
														</div>
													</div>
													<div class="invalid-feedback">
														@error('insurance') @lang($message) @enderror
													</div>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-12 mt-4 mb-1">
												<h6>@lang('Cost For Cash on Delivary')</h6>
												<hr>
											</div>

											<div class="col-sm-12 col-md-3 mb-3">
												<label for="cash_on_delivery_cost"> @lang('Shipping Cost')</label>

												<div class="input-group">
													<input type="text" name="cash_on_delivery_cost"
														   class="form-control @error('cash_on_delivery_cost') is-invalid @enderror"
														   placeholder="0.00"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('cash_on_delivery_cost') }}">
													<div class="input-group-append">
														<div class="form-control">
															{{ config('basic.currency_symbol') }}
														</div>
													</div>
													<div class="invalid-feedback">
														@error('cash_on_delivery_cost') @lang($message) @enderror
													</div>
												</div>
											</div>
										</div>

										<button type="submit"
												class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">@lang('Save')</button>
									</form>
								</div>
								<div class="tab-pane fade"
									 id="tab3" role="tabpanel">
									<form action="{{ route('shippingRateOperatorCountry.store', 'area-wise') }}"
										  method="post"
										  action="" class="mt-4">
										@csrf
										<div class="row">
											<input type="hidden" name="operator_country_id"
												   value="{{ optional($basicControl->operatorCountry)->id }}">

											<div class="col-sm-12 col-md-4 mb-3">
												<label for="from_state_id">@lang('From State') </label>
												<select name="from_state_id"
														class="form-control @error('from_state_id') is-invalid @enderror select2 select2State selectedFromState">
													<option value="" selected disabled>@lang('Select State')</option>
													@foreach(optional($basicControl->operatorCountry)->state() as $state)
														<option value="{{ $state->id }}">@lang($state->name)</option>
													@endforeach
												</select>
												<div class="invalid-feedback">
													@error('from_state_id') @lang($message) @enderror
												</div>
											</div>

											<div class="col-sm-12 col-md-4 mb-3">
												<label for="from_city_id">@lang('Select City')</label>
												<select name="from_city_id"
														class="form-control @error('from_city_id') is-invalid @enderror select2 select2City selectedFromCity">
												</select>
												<div class="invalid-feedback">
													@error('from_city_id') @lang($message) @enderror
												</div>
											</div>

											<div class="col-sm-12 col-md-4 mb-3">
												<label for="from_area_id">@lang('Select Area')</label>
												<select name="from_area_id"
														class="form-control @error('from_area_id') is-invalid @enderror select2 select2Area selectedFromArea">

												</select>
												<div class="invalid-feedback">
													@error('from_area_id') @lang($message) @enderror
												</div>
											</div>

											<div class="col-sm-12 col-md-4 mb-3">
												<label for="to_state_id">@lang('To State')</label>
												<select name="to_state_id"
														class="form-control @error('to_state_id') is-invalid @enderror select2 select2State selectedToState">
													<option value="" selected disabled>@lang('Select State')</option>
													@foreach(optional($basicControl->operatorCountry)->state() as $state)
														<option value="{{ $state->id }}">@lang($state->name)</option>
													@endforeach
												</select>
												<div class="invalid-feedback">
													@error('to_state_id') @lang($message) @enderror
												</div>
											</div>

											<div class="col-sm-12 col-md-4 mb-3">
												<label for="to_city_id">@lang('Select City')</label>
												<select name="to_city_id"
														class="form-control @error('to_city_id') is-invalid @enderror select2 select2City selectedToCity">
												</select>
												<div class="invalid-feedback">
													@error('to_city_id') @lang($message) @enderror
												</div>
											</div>


											<div class="col-sm-12 col-md-4 mb-3">
												<label for="to_area_id">@lang('To Area')</label>
												<select name="to_area_id"
														class="form-control @error('to_area_id') is-invalid @enderror select2 select2Area selectedToArea">
												</select>
												<div class="invalid-feedback">
													@error('to_area_id') @lang($message) @enderror
												</div>
											</div>

										</div>

										<div class="row">
											<div class="col-12 mt-4 mb-1">
												<h6>@lang('Costs For The First ') <span
														class="cost-per-unit">(@lang('UNIT'))</span></h6>
												<hr>
											</div>


											<div class="col-sm-12 col-md-3 mb-3">
												<label for="parcel_type_id">@lang('Parcel Type') </label>
												<div class="input-group">
													<select name="parcel_type_id"
															class="form-control @error('parcel_type_id') is-invalid @enderror select2 select2ParcelType selectedParcelType">
														<option value="" selected
																disabled>@lang('Select Parcel Type')</option>
														@foreach($allParcelTypes as $parcel_type)
															<option
																value="{{ $parcel_type->id }}">@lang($parcel_type->parcel_type)</option>
														@endforeach
													</select>

													<div class="invalid-feedback">
														@error('parcel_type_id') @lang($message) @enderror
													</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-3 mb-3">
												<label for="shipping_cost"> @lang('Shipping Cost')</label>

												<div class="input-group">
													<input type="text" name="shipping_cost"
														   class="form-control @error('shipping_cost') is-invalid @enderror"
														   placeholder="0.00"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('shipping_cost') }}">
													<div class="input-group-append">
														<div class="form-control">
															{{ config('basic.currency_symbol') }}
														</div>
													</div>
													<div class="invalid-feedback">
														@error('shipping_cost') @lang($message) @enderror
													</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-2 mb-3">
												<label for="return_shipment_cost"> @lang('Returned Shipment Cost')</label>
												<div class="input-group">
													<input type="text" name="return_shipment_cost"
														   class="form-control @error('return_shipment_cost') is-invalid @enderror"
														   placeholder="0.00"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('return_shipment_cost') }}">
													<div class="input-group-append">
														<div class="form-control">
															{{ config('basic.currency_symbol') }}
														</div>
													</div>
													<div class="invalid-feedback">
														@error('return_shipment_cost') @lang($message) @enderror
													</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-2 mb-3">
												<label for="tax"> @lang('Tax')</label>
												<div class="input-group">
													<input type="text" name="tax"
														   class="form-control @error('tax') is-invalid @enderror"
														   placeholder="0"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('tax') }}">

													<div class="input-group-append">
														<div class="form-control">
															@lang('%')
														</div>
													</div>
													<div class="invalid-feedback">
														@error('tax') @lang($message) @enderror
													</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-2 mb-3">
												<label for="insurance"> @lang('Insurance')</label>
												<div class="input-group">
													<input type="text" name="insurance"
														   class="form-control @error('insurance') is-invalid @enderror"
														   placeholder="0"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('insurance') }}">
													<div class="input-group-append">
														<div class="form-control">
															{{ config('basic.currency_symbol') }}
														</div>
													</div>
													<div class="invalid-feedback">
														@error('insurance') @lang($message) @enderror
													</div>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-12 mt-4 mb-1">
												<h6>@lang('Cost For Cash on Delivary')</h6>
												<hr>
											</div>

											<div class="col-sm-12 col-md-3 mb-3">
												<label for="cash_on_delivery_cost"> @lang('Shipping Cost') </label>

												<div class="input-group">
													<input type="text" name="cash_on_delivery_cost"
														   class="form-control @error('cash_on_delivery_cost') is-invalid @enderror"
														   placeholder="0.00"
														   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
														   min="0"
														   value="{{ old('cash_on_delivery_cost') }}">
													<div class="input-group-append">
														<div class="form-control">
															{{ config('basic.currency_symbol') }}
														</div>
													</div>
													<div class="invalid-feedback">
														@error('cash_on_delivery_cost') @lang($message) @enderror
													</div>
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
			</div>
		</div>
		@endsection

		@push('extra_scripts')
			<script src="{{ asset('assets/dashboard/js/jquery.uploadPreview.min.js') }}"></script>
	@endpush

	@section('scripts')
		@include('partials.getParcelUnit')
		@include('partials.locationJs')
		@include('partials.select2Create')

	@endsection
