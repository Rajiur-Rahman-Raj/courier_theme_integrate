@extends('admin.layouts.master')
@section('page_title')
	@lang('Internationally Country Rate List')
@endsection

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Internationally Country Rate List')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang($title)</div>
				</div>
			</div>

			<div class="section-body">
				<div class="row mt-sm-4">
					<div class="col-12 col-md-12 col-lg-12">
						<div class="container-fluid" id="container-wrapper">
							<div class="row">
								<div class="col-lg-12">
									<div class="card mb-4 card-primary shadow-sm">
										<div
											class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
											<h6 class="m-0 font-weight-bold text-primary">@lang('Search')</h6>
										</div>

										<div class="card-body">
											<form action="" method="get">
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<input placeholder="@lang('From Country')"
																   name="from_country"
																   value="{{ old('from_country',request()->from_country) }}"
																   type="text"
																   class="form-control form-control-sm">
														</div>
													</div>

													<div class="col-md-4">
														<div class="form-group">
															<input placeholder="@lang('To State')" name="to_country"
																   value="{{ old('to_country',request()->to_country) }}"
																   type="text"
																   class="form-control form-control-sm">
														</div>
													</div>

													<div class="col-md-4">
														<div class="form-group">
															<button type="submit"
																	class="btn btn-primary btn-sm btn-block"><i
																	class="fas fa-search"></i> @lang('Search')</button>
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>

							<div class="row justify-content-md-center">
								<div class="col-lg-12">
									<div class="card mb-4 card-primary shadow">
										<div
											class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
											<h6 class="m-0 font-weight-bold text-primary">@lang($title)</h6>

											<a href="{{ route('internationallyRate', 'country') }}"
											   class="btn btn-sm  btn-primary mr-2">
												<span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
											</a>
										</div>

										<div class="card-body">
											@include('errors.error')
											<div class="table-responsive">
												<table
													class="table table-striped table-hover align-items-center table-flush"
													id="data-table">
													<thead class="thead-light">
													<tr>
														<th scope="col">@lang('From Country')</th>
														<th scope="col">@lang('To Country')</th>
														<th scope="col">@lang('Shipping Cost')</th>
														<th scope="col">@lang('Return Shipment Cost')</th>
														<th scope="col">@lang('Tax')</th>
														<th scope="col">@lang('Insurance')</th>
														@if(adminAccessRoute(array_merge(config('permissionList.Shipping_Rates.Internationally_Rate.permission.edit'), config('permissionList.Shipping_Rates.Internationally_Rate.permission.delete'))))
															<th scope="col">@lang('Action')</th>
														@endif
													</tr>
													</thead>

													<tbody>
													@forelse($showShippingRateList as $key => $shippingCountry)

														<tr>
															<td data-label="@lang('From Country')">
																@lang(optional($shippingCountry->fromCountry)->name)
															</td>

															<td data-label="@lang('To State')">
																@lang(optional($shippingCountry->toCountry)->name)
															</td>

															<td data-label="@lang('Shipping Cost')">
																{{ config('basic.currency_symbol') }}@lang($shippingCountry->shipping_cost)
															</td>

															<td data-label="@lang('Return Shipment Cost')">
																{{ config('basic.currency_symbol') }}@lang($shippingCountry->return_shipment_cost)
															</td>

															<td data-label="@lang('Tax')">
																@lang($shippingCountry->tax)%
															</td>

															<td data-label="@lang('Insurance')">
																{{ config('basic.currency_symbol') }}@lang($shippingCountry->insurance)
															</td>
															@if(adminAccessRoute(array_merge(config('permissionList.Shipping_Rates.Internationally_Rate.permission.edit'), config('permissionList.Shipping_Rates.Internationally_Rate.permission.delete'))))
																<td data-label="@lang('Action')">
																	@if(adminAccessRoute(config('permissionList.Shipping_Rates.Internationally_Rate.permission.edit')))
																		<button data-target="#editCountryRateModal"
																				data-toggle="modal"
																				data-route="{{route('countryRateUpdateInternationally', $shippingCountry->id)}}"
																				data-property="{{ $shippingCountry }}"
																				class="btn btn-sm btn-outline-primary rounded-circle editCountryRate">
																			<i class="fas fa-edit" data-toggle="tooltip" data-placement="top" title="@lang('Edit')"></i>
																		</button>
																	@endif

																	@if(adminAccessRoute(config('permissionList.Shipping_Rates.Internationally_Rate.permission.delete')))
																		<button data-target="#deleteICountryRateModal"
																				data-toggle="modal"
																				data-route="{{route('deleteICountryRate', $shippingCountry->id)}}"
																				class="btn btn-sm btn-outline-danger rounded-circle deleteICountryRate">
																			<i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="@lang('Delete')"></i>
																		</button>
																	@endif
																</td>
															@endif
														</tr>
													@empty
														<tr>
															<td colspan="100%" class="text-center">
																<img class="not-found-img" src="{{ asset('assets/dashboard/images/empty-state.png') }}" alt="">
																<p class="text-center no-data-found-text">@lang('No found data')</p>
															</td>
														</tr>
													@endforelse
													</tbody>
												</table>
											</div>
											<div
												class="card-footer d-flex justify-content-center">{{ $showShippingRateList->links() }}</div>
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

	{{-- Edit City Modal --}}
	<div id="editCountryRateModal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Edit Country Rate')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="editCountryRateForm">
					@csrf
					@method('put')
					<div class="modal-body">

						<div class="col-12 mt-3">
							<label for="">@lang('From Country')</label>
							<select name="from_country_id"
									class="form-control @error('from_country_id') is-invalid @enderror fromCountry">
								<option value="" selected disabled>@lang('Select Country')</option>
								@foreach($allCountries as $country)
									<option value="{{ $country->id }}">@lang($country->name)</option>
								@endforeach
							</select>
							<div class="invalid-feedback">
								@error('from_country_id') @lang($message) @enderror
							</div>
						</div>

						<div class="col-12 mt-3">
							<label for="">@lang('To Country')</label>
							<select name="to_country_id"
									class="form-control @error('to_country_id') is-invalid @enderror toCountry">
								<option value="" selected disabled>@lang('Select Country')</option>
								@foreach($allCountries as $country)
									<option value="{{ $country->id }}">@lang($country->name)</option>
								@endforeach
							</select>
							<div class="invalid-feedback">
								@error('to_country_id') @lang($message) @enderror
							</div>
						</div>


						<div class="col-12 mt-4 mb-1">
							<h6>@lang('Costs For The First ') <span
									class="cost-per-unit">(@lang('UNIT'))</span></h6>
							<hr>
						</div>

						<div class="col-12 mt-3">
							<label for="parcel_type_id">@lang('Parcel Type') <span
									class="text-danger">*</span></label>
							<div class="input-group">
								<select name="parcel_type_id"
										class="form-control @error('parcel_type_id') is-invalid @enderror parcelType selectedParcelType">
									<option value="" selected
											disabled>@lang('Select Parcel Type')</option>
									@foreach($allParcelTypes as $parcel_type)
										<option value="{{ $parcel_type->id }}">@lang($parcel_type->parcel_type)</option>
									@endforeach
								</select>
								<div class="input-group-append">
									<div class="form-control cost-per-unit">
										X
									</div>
								</div>
								<div class="invalid-feedback">
									@error('parcel_type_id') @lang($message) @enderror
								</div>
							</div>
						</div>

						<div class="col-12 mt-3">
							<label for="shipping_cost"> @lang('Shipping Cost:') <span
									class="text-danger">*</span></label>

							<div class="input-group">
								<input type="text" name="shipping_cost"
									   class="form-control @error('shipping_cost') is-invalid @enderror shippingCost"
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

						<div class="col-12 mt-3">
							<label for="return_shipment_cost"> @lang('Returned Shipment Cost:')
								<span
									class="text-danger">*</span></label>
							<div class="input-group">
								<input type="text" name="return_shipment_cost"
									   class="form-control @error('return_shipment_cost') is-invalid @enderror returnShipmentCost"
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

						<div class="col-12 mt-3">
							<label for="tax"> @lang('Tax:') % <span
									class="text-danger">*</span></label>
							<div class="input-group">
								<input type="text" name="tax"
									   class="form-control @error('tax') is-invalid @enderror tax"
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

						<div class="col-12 mt-3">
							<label for="insurance"> @lang('Insurance') <span
									class="text-danger">*</span></label>
							<div class="input-group">
								<input type="text" name="insurance"
									   class="form-control @error('insurance') is-invalid @enderror insurance"
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

					<div class="modal-footer">
						<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
						<button type="submit" class="btn btn-primary">@lang('Update')</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	{{-- Delete Internationally Country Rate Modal --}}
	<div id="deleteICountryRateModal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Confirmation')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="deleteICountryRateForm">
					@csrf
					@method('delete')
					<div class="modal-body">
						<p>@lang('Are you sure to delete this shipping rate?')</p>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('No')</button>
						<button type="submit" class="btn btn-primary">@lang('Yes')</button>
					</div>
				</form>
			</div>
		</div>
	</div>

@endsection

@section('scripts')
	@include('partials.getParcelUnit')
	<script>
		'use strict'
		$(document).ready(function () {
			$(document).on('click', '.deleteICountryRate', function () {
				let dataRoute = $(this).data('route');
				$('#deleteICountryRateForm').attr('action', dataRoute);
			});
			$(document).on('click', '.editCountryRate', function () {

				let dataRoute = $(this).data('route');
				$('#editCountryRateForm').attr('action', dataRoute)

				let dataProperty = $(this).data('property');

				$('.fromCountry').val(dataProperty.from_country_id);
				$('.toCountry').val(dataProperty.to_country_id);

				$('.parcelType').val(dataProperty.parcel_type_id);

				$('.shippingCost').val(dataProperty.shipping_cost);
				$('.returnShipmentCost').val(dataProperty.return_shipment_cost);
				$('.tax').val(dataProperty.tax);
				$('.insurance').val(dataProperty.insurance);
			});
		})
	</script>

	@if ($errors->any())
		@php
			$collection = collect($errors->all());
			$errors = $collection->unique();
		@endphp
		<script>
			"use strict";
			@foreach ($errors as $error)
			Notiflix.Notify.failure("{{ trans($error) }}");
			@endforeach
		</script>
	@endif
@endsection
