@extends($theme.'layouts.app')
@section('title',trans('Shipping Calculator'))

@section('banner_main_heading')
	@lang('Shipping Calculator')
@endsection

@section('banner_heading')
	@lang('Calculator')
@endsection

@section('content')
	<!-- ride_details_area_start -->
	<div class="ride_details_area pt-100">
		<div class="progress_area">
			<div class="container">
				<div class="row gy-4 justify-content-center">
					<div class="col-md-4 col-sm-6">
						<div class="progress_box text-center">
							<div
								class="number number1 {{ lastUriSegment() == 'operator-country' ? 'bg_highlight active' : '' }} mx-auto">
								<a href="{{ route('shippingCalculator.operatorCountry') }}"><i
										class="fas fa-truck"></i> {{ optional(basicControl()->operatorCountry)->name }} </a></div>
						</div>
					</div>

					<div class="col-md-4 col-sm-6">
						<div class="progress_box text-center">
							<div
								class="number number2 {{ lastUriSegment() == 'internationally' ? 'bg_highlight active' : '' }} mx-auto">
								<a href="{{ route('shippingCalculator.internationally') }}"><i
										class="fas fa-plane-departure"></i> @lang('Internatonally')</a></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- ride_details_area_end -->

	<!-- cmn_form_area_start -->
	<div class="cmn_form form1 mb-50">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<form action="">
						<div
							class="section_header country-ratio d-flex align-items-center justify-content-lg-around justify-content-between flex-wrap">
							<div class="form-check">
								<input class="form-check-input" type="radio" id="shipmentTypeDropOff"
									   name="shipment_type"
									   value="drop_off" checked @if(old('shipment_type') === 'drop_off') checked @endif>
								<label class="form-check-label" for="shipmentTypeDropOff">
									@lang($shipmentTypeList[0]['shipment_type'])
									<small>(@lang($shipmentTypeList[0]['title']))</small>
								</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" id="shipmentTypePickup"
									   name="shipment_type"
									   data-resource="{{ $defaultShippingRateInternationally }}"
									   value="pickup" @if(old('shipment_type') === 'pickup') checked @endif>
								<label class="form-check-label" for="shipmentTypePickup">
									@lang($shipmentTypeList[1]['shipment_type'])
									<small>(@lang($shipmentTypeList[1]['title']))</small>
								</label>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 mt-20">
								<label for="from_country_id">@lang('From Country') <span
										class="text-danger">*</span></label>
								<select name="from_country_id"
										class="form-select @error('from_country_id') is-invalid @enderror selectedFromCountry">
									<option value="" selected disabled>@lang('Select Country')</option>
									@foreach($allCountries as $country)
										<option
											value="{{ $country->id }}" {{ old('from_country_id') == $country->id ? 'selected' : ''}}>@lang($country->name)</option>
									@endforeach
								</select>
								<div class="invalid-feedback">
									@error('from_country_id') @lang($message) @enderror
								</div>
							</div>

							<div class="col-sm-6 mt-20">
								<label for="from_state_id">@lang('Select State') <span
										class="text-dark font-weight-bold">(@lang('optional'))</span></label>
								<select name="from_state_id"
										class="form-select @error('from_state_id') is-invalid @enderror selectedFromState"
										data-oldfromstateid="{{ old('from_state_id') }}">
								</select>
								<div class="invalid-feedback">
									@error('from_state_id') @lang($message) @enderror
								</div>
							</div>

							<div class="col-sm-6 mt-20">
								<label for="from_city_id">@lang('Select City')<span class="text-dark font-weight-bold">(@lang('optional'))</span></label>
								<select name="from_city_id"
										class="form-select @error('from_city_id') is-invalid @enderror select2 selectedFromCity"
										data-oldfromcityid="{{ old('from_city_id') }}">
								</select>
								<div class="invalid-feedback">
									@error('from_city_id') @lang($message) @enderror
								</div>
							</div>

							<div class="col-sm-6 mt-20">
								<label for="to_country_id">@lang('To Country') <span
										class="text-danger">*</span></label>
								<select name="to_country_id"
										class="form-select @error('to_country_id') is-invalid @enderror selectedToCountry">
									<option value="" selected disabled>@lang('Select Country')</option>
									@foreach($allCountries as $country)
										<option
											value="{{ $country->id }}" {{ old('to_country_id') == $country->id ? 'selected' : ''}}>@lang($country->name)</option>
									@endforeach
								</select>
								<div class="invalid-feedback">
									@error('to_country_id') @lang($message) @enderror
								</div>
							</div>

							<div class="col-sm-6 mt-20">
								<label for="to_state_id">@lang('Select State') <span
										class="text-dark font-weight-bold">(@lang('optional'))</span></label>
								<select name="to_state_id"
										class="form-select @error('to_state_id') is-invalid @enderror selectedToState"
										data-oldfromstateid="{{ old('to_state_id') }}">
								</select>
								<div class="invalid-feedback">
									@error('to_state_id') @lang($message) @enderror
								</div>
							</div>

							<div class="col-sm-6 mt-20">
								<label for="to_city_id">@lang('Select City') <span
										class="text-dark font-weight-bold">(@lang('optional'))</span></label>
								<select name="to_city_id"
										class="form-select @error('to_city_id') is-invalid @enderror selectedToCity"
										data-oldfromstateid="{{ old('to_city_id') }}">
								</select>
								<div class="invalid-feedback">
									@error('to_city_id') @lang($message) @enderror
								</div>
							</div>
						</div>


						<div class="row mt-4">
							<div class="col-md-12 d-flex justify-content-end">

								<div class="addParcelFieldButton">
									<div class="form-group">
										<a href="javascript:void(0)"
										   class="btn view_cmn_btn2 float-right"
										   id="parcelGenerate"><i
												class="fa fa-plus-circle"></i> {{ trans('Add More') }}
										</a>
									</div>
								</div>
							</div>
						</div>

						<div class="parcelField">
							<div class="row">
								<div class="col-sm-3 mt-20">
									<label for="parcel_type_id"> @lang('Parcel Type') <span class="text-danger">*</span></label>
									<select name="parcel_type_id[]"
											class="form-select @error('parcel_type_id.0') is-invalid @enderror selectedParcelType OCParcelTypeWiseShippingRate">
										<option value="" disabled
												selected>@lang('Select Parcel Type')</option>
										@foreach($parcelTypes as $parcel_type)
											<option
												value="{{ $parcel_type->id }}" {{ old('parcel_type_id.0') == $parcel_type->id ? 'selected' : '' }}>@lang($parcel_type->parcel_type)</option>
										@endforeach
									</select>

									<div class="invalid-feedback">
										@error('parcel_type_id.0') @lang($message) @enderror
									</div>
								</div>

								<div class="col-sm-3 mt-20">
									<label for="parcel_unit_id"> @lang('Select Unit') <span class="text-danger">*</span></label>
									<select name="parcel_unit_id[]"
											class="form-select @error('parcel_unit_id.0') is-invalid @enderror selectedParcelUnit"
											data-oldparcelunitid='{{ old("parcel_unit_id.0") }}'>
										<option value="" disabled
												selected>@lang('Select Parcel Unit')</option>
									</select>

									<div class="invalid-feedback">
										@error('parcel_unit_id.0') @lang($message) @enderror
									</div>
								</div>

								<div class="col-sm-3 mt-20 d-none">
									<label for="cost_per_unit"> @lang('Cost per unit')</label>
									<div class="input-group">
										<input type="text" name="cost_per_unit[]"
											   class="form-select @error('cost_per_unit.0') is-invalid @enderror unitPrice newCostPerUnit"
											   value="{{ old('cost_per_unit.0') }}" readonly>
										<div class="input-group-append" readonly="">
											<div class="form-control currency_symbol">
												{{ $basic->currency_symbol }}
											</div>
										</div>

										<div class="invalid-feedback">
											@error('cost_per_unit.0') @lang($message) @enderror
										</div>

									</div>
								</div>

								<div class="col-sm-3 mt-20 new_total_weight_parent">
									<label for="total_unit"> @lang('Total Unit')</label>
									<div class="input-group">
										<input type="text" name="total_unit[]"
											   class="form-select @error('total_unit.0') is-invalid @enderror newTotalWeight"
											   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
											   value="{{ old('total_unit.0') }}">
										<div class="input-group-append" readonly="">
											<div class="form-control currency_symbol">
												@lang('kg')
											</div>
										</div>
										<div class="invalid-feedback d-block">
											@error('total_unit.0') @lang($message) @enderror
										</div>
									</div>
								</div>

								<div class="col-sm-3 mt-20">
									<label for="parcel_total_cost"> @lang('Total Cost')</label>
									<div class="input-group">
										<input type="text" name="parcel_total_cost[]"
											   class="form-select @error('parcel_total_cost.0') is-invalid @enderror totalParcelCost"
											   value="{{ old('parcel_total_cost.0') }}" readonly>
										<div class="input-group-append" readonly="">
											<div class="form-control currency_symbol">
												{{ $basic->currency_symbol }}
											</div>
										</div>
									</div>

									<div class="invalid-feedback">
										@error('parcel_total_cost.0') @lang($message) @enderror
									</div>
								</div>

							</div>
						</div>

						<div class="addedParcelField">

						</div>

						<div class="costing_area mt-70">
							<div class="row gy-4">
								<div class="col-lg-3 col-sm-6 mx-auto shippingCostBox">
									<div class="costing_box text-center">
										<input type="hidden" name="sub_total" value="{{ old('sub_total') ?? '0' }}"
											   class="form-control bg-white text-dark subTotal"
											   data-subtotal="{{ old('sub_total') }}"
											   readonly>

										<p>@lang('SHIPPING COST')</p>
										<input type="hidden" class="calculateShippingCostInput"
											   name="calculate_shipping_cost_input"
											   value="{{ old('calculate_shipping_cost_input') ?? '0' }}">

										<span class="calculateShippingCost">{{ $basic->currency_symbol }}0</span>
									</div>
								</div>

								<div class="col-lg-3 col-sm-6 mx-auto d-none pickupCostBox">
									<div class="costing_box text-center">
										<p>@lang('PICKUP COST')</p>
										<input type="hidden" class="calculatePickupCostInput"
											   name="calculate_pickup_cost_input"
											   value="{{ old('calculate_pickup_cost_input') ?? '0' }}">
										<span class="calculatePickupCost">{{ $basic->currency_symbol }}0</span>
									</div>
								</div>

								<div class="col-lg-3 col-sm-6 mx-auto d-none supplyCostBox">
									<div class="costing_box text-center">

										<p>@lang('SUPPLY COST')</p>
										<input type="hidden" class="calculateSupplyCostInput"
											   name="calculate_supply_cost_input"
											   value="{{ old('calculate_supply_cost_input') ?? '0' }}">
										<span class="calculateSupplyCost">{{ $basic->currency_symbol }}0</span>
									</div>
								</div>

								<div class="col-lg-3 col-sm-6 mx-auto taxCostBox">
									<div class="costing_box text-center">
										<p>@lang('TAX COST')</p>
										<input type="hidden" class="calculateTaxInput" name="calculate_tax_cost_input"
											   value="{{ old('calculate_tax_cost_input') ?? '0' }}">
										<span class="calculateTax">{{ $basic->currency_symbol }}0</span>
									</div>
								</div>
								<div class="col-lg-3 col-sm-6 mx-auto insuranceCostBox">
									<div class="costing_box text-center">
										<p>@lang('INSURANCE COST')</p>
										<input type="hidden" class="calculateInsuranceInput"
											   name="calculate_insurance_cost_input"
											   value="{{ old('calculate_insurance_cost_input') ?? '0' }}">
										<span class="calculateInsurance">{{ $basic->currency_symbol }}0</span>
									</div>
								</div>
								<div class="col-lg-3 col-sm-6 mx-auto totalCostBox">
									<div class="costing_box text-center">
										<p>@lang('TOTAL COST')</p>
										<span class="totalCost">{{ $basic->currency_symbol }}0</span>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- cmn_form_area_end -->

	<!-- prev_next_btn_area_start -->
	<div class="prev_next_btn_area mb-100">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="btn_area d-flex justify-content-end">
						<a href="{{route('user.createShipment', ['shipment_type' => 'internationally', 'shipment_status' => 'all'])}}"
						   class="cmn_btn">@lang('Create Shipment')</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- prev_next_btn_area_end -->
@endsection

@push('script')
	@include('partials.locationJs')
	@include($theme.'partials.getParcelUnit')
	<script>
		'use strict'

		formHandlingByShipmentType();

		$('input[name="shipment_type"]').on("change",function () {
			formHandlingByShipmentType();
		});

		function formHandlingByShipmentType() {
			if ($('input[name="shipment_type"]:checked').val() === "drop_off") {
				$('.pickupCostBox').addClass('d-none');
				$('.supplyCostBox').addClass('d-none');

				$('.shippingCostBox').addClass('col-lg-3');
				$('.shippingCostBox').removeClass('col-lg-2');
				$('.pickupCostBox').addClass('col-lg-3');
				$('.pickupCostBox').removeClass('col-lg-2');
				$('.supplyCostBox').addClass('col-lg-3');
				$('.supplyCostBox').removeClass('col-lg-2');
				$('.taxCostBox').addClass('col-lg-3');
				$('.taxCostBox').removeClass('col-lg-2');
				$('.insuranceCostBox').addClass('col-lg-3');
				$('.insuranceCostBox').removeClass('col-lg-2');
				$('.totalCostBox').addClass('col-lg-3');
				$('.totalCostBox').removeClass('col-lg-2');
			} else if ($('input[name="shipment_type"]:checked').val() === "pickup") {
				@php
					$basic = basicControl();
				@endphp
				let basic = @json($basic);
				let dataResouce = $('#shipmentTypePickup').data('resource');
				$('.calculatePickupCost').text(`${basic.currency_symbol}${dataResouce.pickup_cost}`);
				$('.calculateSupplyCost').text(`${basic.currency_symbol}${dataResouce.supply_cost}`);

				$('.calculatePickupCostInput').val(dataResouce.pickup_cost);
				$('.calculateSupplyCostInput').val(dataResouce.supply_cost);

				$('.pickupCostBox').removeClass('d-none');
				$('.supplyCostBox').removeClass('d-none');
				$('.shippingCostBox').removeClass('col-lg-3');
				$('.shippingCostBox').addClass('col-lg-2');
				$('.pickupCostBox').removeClass('col-lg-3');
				$('.pickupCostBox').addClass('col-lg-2');
				$('.supplyCostBox').removeClass('col-lg-3');
				$('.supplyCostBox').addClass('col-lg-2');
				$('.taxCostBox').removeClass('col-lg-3');
				$('.taxCostBox').addClass('col-lg-2');
				$('.insuranceCostBox').removeClass('col-lg-3');
				$('.insuranceCostBox').addClass('col-lg-2');
				$('.totalCostBox').removeClass('col-lg-3');
				$('.totalCostBox').addClass('col-lg-2');
				finalTotalAmountCalculation();

			} else if ($('input[name="shipment_type"]:checked').val() === "condition") {
				$('.pickupCostBox').addClass('d-none');
				$('.supplyCostBox').addClass('d-none');

				$('.shippingCostBox').addClass('col-lg-3');
				$('.shippingCostBox').removeClass('col-lg-2');
				$('.pickupCostBox').addClass('col-lg-3');
				$('.pickupCostBox').removeClass('col-lg-2');
				$('.supplyCostBox').addClass('col-lg-3');
				$('.supplyCostBox').removeClass('col-lg-2');
				$('.taxCostBox').addClass('col-lg-3');
				$('.taxCostBox').removeClass('col-lg-2');
				$('.insuranceCostBox').addClass('col-lg-3');
				$('.insuranceCostBox').removeClass('col-lg-2');
				$('.totalCostBox').addClass('col-lg-3');
				$('.totalCostBox').removeClass('col-lg-2');
			}
		}

		$("#parcelGenerate").on('click', function () {
			const id = Date.now();
			var form = `<div class="row addMoreParcelBox" id="removeParcelField${id}">
							<div class="col-sm-3">
								<label for="parcel_type_id"> @lang('Parcel Type') </label>
								<select name="parcel_type_id[]" class="form-select OCParcelTypeWiseShippingRate selectedParcelType_${id}" onchange="selectedParcelTypeHandel(${id})" required>
									<option value="" disabled selected>@lang('Select Parcel Type')</option>
									@foreach($parcelTypes as $parcel_type)
			<option value="{{ $parcel_type->id }}">@lang($parcel_type->parcel_type)</option>
									@endforeach
			</select>
		</div>

		<div class="col-sm-3">
			<label for="parcel_unit_id"> @lang('Select Unit') </label>
								<select name="parcel_unit_id[]"
										class="form-select selectedParcelUnit_${id}" onchange="selectedParcelServiceHandel(${id})" required>
									<option value="" disabled selected>@lang('Select Parcel Unit')</option>
								</select>
							</div>

							<div class="col-sm-3 d-none">
								<label for="cost_per_unit"> @lang('Cost per unit')</label>
								<div class="input-group">
									<input type="text" name="cost_per_unit[]" class="form-control newCostPerUnit unitPrice_${id}" readonly>
									<div class="input-group-append" readonly="">
										<div class="form-control currency_symbol">
											{{ $basic->currency_symbol }}
			</div>
		</div>
	</div>
</div>

<div class="col-sm-3 new_total_weight_parent">
	<label for="total_unit"> @lang('Total Unit')</label>
								<div class="input-group">
									<input type="text" name="total_unit[]" class="form-control newTotalWeight" required>
									<div class="input-group-append" up="this.value = this.value.replace (/^\.|[^\d\.]/g, '')" readonly="">
										<div class="form-control currency_symbol">
										@lang('kg')
			</div>
		</div>
	</div>
</div>

<div class="col-sm-3">
	<label for="parcel_total_cost"> @lang('Total Cost')</label>
								<div class="input-group">
									<input type="text" name="parcel_total_cost[]" class="form-control totalParcelCost" readonly>
									<div class="input-group-append" readonly="">
										<div class="form-control currency_symbol">
											{{ $basic->currency_symbol }}
			</div>
		</div>
		<button
									class="btn btn-danger  delete_parcel_desc custom_delete_desc_padding"
									type="button" onclick="deleteParcelField(${id})">
									<i class="fa fa-times"></i>
								</button>
	</div>
</div>
</div>`;

			$('.addedParcelField').append(form)

		});

		function deleteParcelField(id) {
			$(`#removeParcelField${id}`).remove();
		}

		$(document).on('input', '.newTotalWeight', function () {
			window.calculateParcelTotalPrice();
		});

		window.calculateParcelTotalPrice = function calculateParcelTotalPrice() {
			let subTotal = 0;
			$('.newTotalWeight').each(function (key, value) {
				let totalWeight = parseFloat($(this).val()).toFixed(2);
				let costPerUnit = parseFloat($(value).parents('.new_total_weight_parent').siblings().find('.newCostPerUnit').val()).toFixed(2);
				let cost = isNaN(totalWeight) || isNaN(costPerUnit) ? 0 : totalWeight * costPerUnit;
				subTotal += cost;

				$(value).parents('.new_total_weight_parent').siblings().find('.totalParcelCost').val(cost);
			});

			let updateSubTotal = subTotal.toFixed(2);
			$('.subTotal').val(updateSubTotal);
			finalTotalAmountCalculation();

		}


	</script>
@endpush
