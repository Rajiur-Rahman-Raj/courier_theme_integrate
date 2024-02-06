<script type="text/javascript">
	'use strict';

	let oldPackingValue = "{{ $oldPackingCounts }}"


	let oldFromStateIdPresent = "{{ $oldFromStateIdPresent }}"
	let oldFromCityIdPresent = "{{ $oldFromCityIdPresent }}"
	let oldFromAreaIdPresent = "{{ $oldFromAreaIdPresent }}"

	let oldToStateIdPresent = "{{ $oldToStateIdPresent }}"
	let oldToCityIdPresent = "{{ $oldToCityIdPresent }}"
	let oldToAreaIdPresent = "{{ $oldToAreaIdPresent }}"

	if(oldFromStateIdPresent == 1 && oldFromCityIdPresent == 1){
		let oldFromCountryId = $('.selectedFromCountry').val();
		let oldFromStateId = $('.selectedFromState').data('oldfromstateid');
		let oldFromCityId = $('.selectedFromCity').data('oldfromcityid');
		getOldFromState(oldFromCountryId, oldFromStateId);
		getOldFromCity(oldFromStateId, oldFromCityId);
	}else if (oldFromCityIdPresent == 1 && oldFromAreaIdPresent == 1) {
		let oldFromStateId = $('.selectedFromState').val();
		let oldFromCityId = $('.selectedFromCity').data('oldfromcityid');
		let oldFromAreaId = $('.selectedFromArea').data('oldfromareaid');
		getOldFromCity(oldFromStateId, oldFromCityId);
		getOldFromArea(oldFromCityId, oldFromAreaId);
	} else if (oldFromCityIdPresent == 1 && oldFromAreaIdPresent == 0) {
		let oldFromStateId = $('.selectedFromState').val();
		let oldFromCityId = $('.selectedFromCity').data('oldfromcityid');
		getOldFromCity(oldFromStateId, oldFromCityId);
	}

	if(oldToStateIdPresent == 1 && oldToCityIdPresent == 1){
		let oldToCountryId = $('.selectedToCountry').val();
		let oldToStateId = $('.selectedToState').data('oldtostateid');
		let oldToCityId = $('.selectedToCity').data('oldtocityid');
		getOldToState(oldToCountryId, oldToStateId);
		getOldToCity(oldToStateId, oldToCityId);
	}else if (oldToCityIdPresent == 1 && oldToAreaIdPresent == 1) {
		let oldToStateId = $('.selectedToState').val();
		let oldToCityId = $('.selectedToCity').data('oldtocityid');
		let oldToAreaId = $('.selectedToArea').data('oldtoareaid');
		getOldToCity(oldToStateId, oldToCityId);
		getOldToArea(oldToCityId, oldToAreaId);
	} else if (oldToCityIdPresent == 1 && oldToAreaIdPresent == 0) {
		let oldToStateId = $('.selectedToState').val();
		let oldToCityId = $('.selectedToCity').data('oldtocityid');
		getOldToCity(oldToStateId, oldToCityId);
	}

	function getOldFromState(oldFromCountryId, oldFromStateId) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: "{{ route('getSeletedCountryState') }}",
			method: 'POST',
			data: {
				id: oldFromCountryId,
			},
			success: function (response) {
				let responseData = response;
				responseData.forEach(res => {
					$('.selectedFromState').append(`<option value="${res.id}" ${res.id == oldFromStateId ? 'selected' : ''}>${res.name}</option>`)
				})
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}

	function getOldFromCity(oldFromStateId, oldFromCityId) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: "{{ route('getSeletedStateCity') }}",
			method: 'POST',
			data: {
				id: oldFromStateId,
			},
			success: function (response) {
				let responseData = response;
				responseData.forEach(res => {
					$('.selectedFromCity').append(`<option value="${res.id}" ${res.id == oldFromCityId ? 'selected' : ''}>${res.name}</option>`)
				})
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}

	function getOldFromArea(oldFromCityId, oldFromAreaId) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: "{{ route('getSeletedCityArea') }}",
			method: 'POST',
			data: {
				id: oldFromCityId,
			},
			success: function (response) {
				let responseData = response;
				responseData.forEach(res => {
					$('.selectedFromArea').append(`<option value="${res.id}" ${res.id == oldFromAreaId ? 'selected' : ''}>${res.name}</option>`)
				})
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}


	function getOldToState(oldToCountryId, oldToStateId) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: "{{ route('getSeletedCountryState') }}",
			method: 'POST',
			data: {
				id: oldToCountryId,
			},
			success: function (response) {
				let responseData = response;
				responseData.forEach(res => {
					$('.selectedToState').append(`<option value="${res.id}" ${res.id == oldToStateId ? 'selected' : ''}>${res.name}</option>`)
				})
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}

	function getOldToCity(oldToStateId, oldToCityId) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: "{{ route('getSeletedStateCity') }}",
			method: 'POST',
			data: {
				id: oldToStateId,
			},
			success: function (response) {
				let responseData = response;
				responseData.forEach(res => {
					$('.selectedToCity').append(`<option value="${res.id}" ${res.id == oldToCityId ? 'selected' : ''}>${res.name}</option>`)
				})
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}

	function getOldToArea(oldToCityId, oldToAreaId) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: "{{ route('getSeletedCityArea') }}",
			method: 'POST',
			data: {
				id: oldToCityId,
			},
			success: function (response) {
				let responseData = response;
				responseData.forEach(res => {
					$('.selectedToArea').append(`<option value="${res.id}" ${res.id == oldToAreaId ? 'selected' : ''}>${res.name}</option>`)
				})
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}


	if (oldPackingValue) {
		for (let i = 0; i < oldPackingValue; i++) {
			let oldPackageId;
			let oldVariantId;
			if (i == 0) {
				oldPackageId = $(`.selectedPackage`).val();
				oldVariantId = $(`.selectedVariant`).data('oldvariant');
			} else {
				oldPackageId = $(`.selectedPackage_${i}`).val();
				oldVariantId = $(`.selectedVariant_${i}`).data('oldvariant');
			}

			getOldSelectedPackageVariant(oldPackageId, oldVariantId, i);
		}

		function getOldSelectedPackageVariant(value, oldVariantId, i) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			$.ajax({
				url: "{{ route('getSelectedPackageVariant') }}",
				method: 'POST',
				data: {
					id: value,
				},
				success: function (response) {
					let responseData = response;
					let selectedVariantClass;
					if (i == 0) {
						selectedVariantClass = '.selectedVariant';
					} else {
						selectedVariantClass = `.selectedVariant_${i}`
					}


					responseData.forEach(res => {
						$(selectedVariantClass).append(`<option value="${res.id}" ${res.id == oldVariantId ? 'selected' : ''}>${res.variant}</option>`)
					})

				},
				error: function (xhr, status, error) {
					console.log(error)
				}
			});
		}
	}


	let oldParcelValue = "{{ $oldParcelCounts }}"

	if (oldParcelValue) {
		for (let i = 0; i < oldParcelValue; i++) {
			let oldParcelTypeId;
			let oldParcelUnitId;
			if (i == 0) {
				oldParcelTypeId = $(`.selectedParcelType`).val();
				oldParcelUnitId = $(`.selectedParcelUnit`).data('oldparcelunitid');
			} else {
				oldParcelTypeId = $(`.selectedParcelType_${i}`).val();
				oldParcelUnitId = $(`.selectedParcelUnit_${i}`).data('oldparcelunitid');
			}

			getOldSelectedParcelTypeUnit(oldParcelTypeId, oldParcelUnitId, i);
		}

		function getOldSelectedParcelTypeUnit(oldParcelTypeId, oldParcelUnitId, i) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			$.ajax({
				url: "{{ route('getSelectedParcelTypeUnit') }}",
				method: 'POST',
				data: {
					id: oldParcelTypeId,
				},
				success: function (response) {
					let responseData = response;
					let selectedParcelUnitClass;
					if (i == 0) {
						selectedParcelUnitClass = '.selectedParcelUnit';
					} else {
						selectedParcelUnitClass = `.selectedParcelUnit_${i}`
					}

					responseData.forEach(res => {
						$(selectedParcelUnitClass).append(`<option value="${res.id}" ${res.id == oldParcelUnitId ? 'selected' : ''}>${res.unit}</option>`)
					})
				},
				error: function (xhr, status, error) {
					console.log(error)
				}
			});
		}
	}


	OCFormHandlingByShipmentType();

	$('input[name="shipment_type"]').on("change",function () {
		OCFormHandlingByShipmentType();
	});

	function OCFormHandlingByShipmentType() {
		if ($('input[name="shipment_type"]:checked').val() === "drop_off") {
			$('.pickup').addClass('d-none');
			$('.OCPickupCost').val(0);
			$('.OCSupplyCost').val(0);
			$('.get_receive_amount').addClass('d-none');
			$('.add_cod_parcel_info').addClass('d-none');
			$('.addParcelFieldButton').removeClass('d-none');
			$('.addedParcelField').removeClass('d-none')
			$('.parcelField').removeClass('d-none')
			finalTotalAmountCalculation();

			$('input[name="receive_amount"]').prop('required', false);
			$('textarea[name="parcel_details"]').prop('required', false);
			$('input[name="parcel_name"]').prop('required', true);
			$('input[name="parcel_quantity"]').prop('required', true);
			$('select[name="parcel_type_id"]').prop('required', true);
			$('select[name="parcel_unit_id"]').prop('required', true);
			$('input[name="total_unit"]').prop('required', true);

		} else if ($('input[name="shipment_type"]:checked').val() === "pickup") {
			let dataResouce = $('#shipmentTypePickup').data('resource');
			$('.pickup').removeClass('d-none');
			$('.OCPickupCost').val(dataResouce.pickup_cost);
			$('.OCSupplyCost').val(dataResouce.supply_cost);
			$('.get_receive_amount').addClass('d-none');
			$('.add_cod_parcel_info').addClass('d-none');
			$('.addParcelFieldButton').removeClass('d-none');
			$('.addedParcelField').removeClass('d-none')
			$('.parcelField').removeClass('d-none')
			finalTotalAmountCalculation();

			$('input[name="receive_amount"]').prop('required', false);
			$('textarea[name="parcel_details"]').prop('required', false);
			$('input[name="parcel_name"]').prop('required', true);
			$('input[name="parcel_quantity"]').prop('required', true);
			$('select[name="parcel_type_id"]').prop('required', true);
			$('select[name="parcel_unit_id"]').prop('required', true);
			$('input[name="total_unit"]').prop('required', true);

		} else if ($('input[name="shipment_type"]:checked').val() === "condition") {
			$('.pickup').addClass('d-none');
			$('.OCPickupCost').val(0);
			$('.OCSupplyCost').val(0);
			$('.get_receive_amount').removeClass('d-none');
			$('.add_cod_parcel_info').removeClass('d-none');
			$('.addParcelFieldButton').addClass('d-none');
			$('.addedParcelField').addClass('d-none')
			$('.parcelField').addClass('d-none')
			finalTotalAmountCalculation();

			$('input[name="receive_amount"]').prop('required', true);
			$('textarea[name="parcel_details"]').prop('required', true);

			$('input[name="parcel_name"]').prop('required', false);
			$('input[name="parcel_quantity"]').prop('required', false);
			$('select[name="parcel_type_id"]').prop('required', false);
			$('select[name="parcel_unit_id"]').prop('required', false);
			$('input[name="total_unit"]').prop('required', false);
		}
	}


	formHandlingByPackingService();

	$('input[name="packing_service"]').on("change",function () {
		formHandlingByPackingService();
	});

	function formHandlingByPackingService() {
		if ($('input[name="packing_service"]:checked').val() === "yes") {
			$('.packingField').removeClass('d-none')
			$('.addPackingFieldButton').removeClass('d-none')

			$('select[name="package_id"]').prop('required', true);
			$('select[name="variant_id"]').prop('required', true);
			$('input[name="variant_quantity"]').prop('required', true);

		} else if ($('input[name="packing_service"]:checked').val() === "no") {
			$('.packingField').addClass('d-none')
			$('.addPackingFieldButton').addClass('d-none')

			$('select[name="package_id"]').prop('required', false);
			$('select[name="variant_id"]').prop('required', false);
			$('input[name="variant_quantity"]').prop('required', false);
		}
	}


	window.calculateCashOnDeliveryShippingCost = function calculateCashOnDeliveryShippingCost() {
		if ($('input[name="shipment_type"]:checked').val() === "condition") {
			let fromStateId = $('.selectedFromState').val();
			let fromCityId = $('.selectedFromCity').val();
			let fromAreaId = $('.selectedFromArea').val();

			let toStateId = $('.selectedToState').val();
			let toCityId = $('.selectedToCity').val();
			let toAreaId = $('.selectedToArea').val();

			let parcelTypeId = null;

			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			$.ajax({
				url: "{{ route('OCGetSelectedLocationShipRate') }}",
				method: 'POST',
				data: {
					parcelTypeId: parcelTypeId,
					fromStateId: fromStateId,
					fromCityId: fromCityId,
					fromAreaId: fromAreaId,

					toStateId: toStateId,
					toCityId: toCityId,
					toAreaId: toAreaId,
				},
				success: function (response) {
					$('.OCShippingCost').val(response.cash_on_delivery_cost);
					$('.OCReturnShipmentCost').val(response.return_shipment_cost);
					finalTotalAmountCalculation(response.cash_on_delivery_cost);
				},
				error: function (xhr, status, error) {
					console.log(error)
				}
			});
		}
	}


	$(document).ready(function () {
		$(".flatpickr").flatpickr({
			wrap: true,
			altInput: true,
			dateFormat: "Y-m-d H:i",
			minDate: new Date(new Date().getTime() - 24 * 60 * 60 * 1000) // today - 1 day
		});

		$('#variantQuantity').on('keyup', function () {
			let quantity = $(this).val();
			let variantPrice = $('.variantPrice').val();

			let totalPackingCost = quantity * variantPrice;
			$('.totalPackingCost').val(totalPackingCost);
		});

		$("#packingGenerate").on('click', function () {
			formHandlingByPackingService();
			const id = Date.now();
			var form = `<div class="row">
										<div class="col-md-12">
											<div class="form-group input-box mb-3">
												<div class="input-group">
													<select name="package_id[]" class="form-control selectedPackage_${id}" onchange="selectedPackageVariantHandel(${id})" required>
														<option value="" disabled selected>@lang('Select package')</option>
														@foreach($packageList as $package)
			<option value="{{ $package->id }}">@lang($package->package_name)</option>
														@endforeach
			</select>

<select name="variant_id[]" class="form-control selectedVariant_${id} newVariant" onchange="selectedVariantServiceHandel(${id})" required>
														<option value="">@lang('Select Variant')</option>
													</select>

													<input type="text" name="variant_price[]" class="form-control newVariantPrice variantPrice_${id}" placeholder="@lang('price')" onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')" readonly>
													<div class="input-group-append" readonly="">
														<div class="form-control currency_symbol">
															{{ config('basic.currency_symbol') }}
			</div>
		</div>

		<input type="text" name="variant_quantity[]" class="form-control newVariantQuantity" id="variantQuantity_${id}" onkeyup="variantQuantityHandel(${id})" min="0" placeholder="@lang('quantity')" required>
													<input type="text" name="package_cost[]" class="form-control totalPackingCost_${id} packingCostValue" readonly placeholder="@lang('total cost')">
													<div class="input-group-append">
														<div class="form-control currency_symbol">
															{{ config('basic.currency_symbol') }}
			</div>
		</div>

		<span class="input-group-btn">
			<button class="btn btn-danger  delete_packing_desc custom_delete_desc_padding" type="button">
			<i class="fa fa-times"></i>
			</button>
		</span>
	</div>
</div>
</div>
</div>`;
			$('.addedPackingField').append(form)
		});

		$(document).on('click', '.delete_packing_desc', function () {
			$(this).closest('.input-group').parent().remove();
		});


		$("#parcelGenerate").on('click', function () {
			formHandlingByPackingService();
			const id = Date.now();
			var form = `<div class="row addMoreParcelBox" id="removeParcelField${id}">
										<div class="col-md-12 d-flex justify-content-end ">
											<button
												class="btn btn-danger  delete_parcel_desc custom_delete_desc_padding mt-4"
												type="button" onclick="deleteParcelField(${id})">
												<i class="fa fa-times"></i>
											</button>
										</div>
										<div class="col-sm-12 col-md-3 mb-3 input-box">
						<label for="parcel_name"> @lang('Parcel Name') </label>
											<input type="text" name="parcel_name[]"
												   class="form-control" required>

					</div>

					<div class="col-sm-12 col-md-3 mb-3 input-box">
					<label for="parcel_quantity"> @lang('Parcel Quantity')</label>
					<input type="text" name="parcel_quantity[]"
						   class="form-control" up="this.value = this.value.replace (/^\.|[^\d\.]/g, '')" required>
					</div>

											<div class="col-sm-12 col-md-3 mb-3 input-box">
												<label for="parcel_type_id"> @lang('Parcel Type') </label>
											<select name="parcel_type_id[]" class="form-control OCParcelTypeWiseShippingRate select2 selectedParcelType_${id}" onchange="selectedParcelTypeHandel(${id})" required>
												<option value="" disabled selected>@lang('Select Parcel Type')</option>
												@foreach($parcelTypes as $parcel_type)
			<option value="{{ $parcel_type->id }}">@lang($parcel_type->parcel_type)</option>
												@endforeach
			</select>
		</div>

		<div class="col-sm-12 col-md-3 mb-3 input-box">
			<label for="parcel_unit_id"> @lang('Select Unit') </label>
											<select name="parcel_unit_id[]"
													class="form-control selectedParcelUnit_${id}" onchange="selectedParcelServiceHandel(${id})" required>
												<option value="" disabled
														selected>@lang('Select Parcel Unit')</option>
											</select>


					</div>


					<div class="col-sm-12 col-md-4 mb-3 input-box">
													<label for="cost_per_unit"> @lang('Cost per unit')</label>
													<div class="input-group">
														<input type="text" name="cost_per_unit[]"
															   class="form-control newCostPerUnit unitPrice_${id}"
															   readonly>
														<div class="input-group-append" readonly="">
															<div class="form-control currency_symbol">
																{{ $basic->currency_symbol }}
			</div>
		</div>
		</div>
	</div>

	<div class="col-sm-12 col-md-4 mb-3 new_total_weight_parent input-box">
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

		<div class="col-sm-12 col-md-4 mb-3 input-box">
			<label for="parcel_total_cost"> @lang('Total Cost')</label>
													<div class="input-group">
														<input type="text" name="parcel_total_cost[]"
															   class="form-control totalParcelCost" readonly>
														<div class="input-group-append" readonly="">
															<div class="form-control currency_symbol">
																{{ $basic->currency_symbol }}
			</div>
		</div>
	</div>

		</div>

<div class="col-sm-12 col-md-12 input-box">
<label> @lang('Dimensions') [Length x Width x Height] (cm)
											<span class="text-dark font-weight-bold">(optional)</span></label>
										</div>

										<div class="col-sm-12 col-md-4 mb-3 input-box">
											<input type="text" name="parcel_length[]" class="form-control">
					</div>

					<div class="col-sm-12 col-md-4 mb-3 input-box">
						<input type="text" name="parcel_width[]" class="form-control">

					</div>

					<div class="col-sm-12 col-md-4 mb-3 input-box">
						<input type="text" name="parcel_height[]" class="form-control">
					</div>
				</div>`;

			$('.addedParcelField').append(form)

		});
	});


	function deleteParcelField(id) {
		$(`#removeParcelField${id}`).remove();
	}

	$(document).on('input', '.newVariantQuantity', function () {
		window.calculatePackingTotalPrice();
	})


	window.calculatePackingTotalPrice = function calculatePackingTotalPrice() {
		let subTotal = 0;
		$('.newVariantQuantity').each(function (key, value) {
			let quantity = parseFloat($(value).val()).toFixed(2);
			let price = parseFloat($(value).siblings('.newVariantPrice').val()).toFixed(2);
			let cost = (isNaN(quantity) || isNaN(price)) ? 0 : quantity * price;
			subTotal += cost;
		})
		let updateSubTotal = subTotal.toFixed(2);
		$('.OCSubTotal').val(updateSubTotal);
		$('.lastFiv').val(updateSubTotal);
		totalSubCount(subTotal);
		calculateOCDiscount();
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
		$('.OCSubTotal').val(updateSubTotal);
		$('.firstFiv').val(updateSubTotal);
		totalSubCount(subTotal);
		calculateOCDiscount();
	}


	function totalSubCount() {
		let total = parseFloat($('.firstFiv').val()) + parseFloat($('.lastFiv').val());
		$('.OCSubTotal').val(total.toFixed(2));
		finalTotalAmountCalculation();
		return total;
	}

	$(document).on('input', '.OCDiscount', function () {
		calculateOCDiscount();
	})

	function calculateOCDiscount() {
		let discount = parseFloat($('.OCDiscount').val());

		let OCSubTotal = totalSubCount();
		if (!discount) {
			$('.OCSubTotal').val(OCSubTotal);
			$('.OCDiscountAmount').val(0);
			finalTotalAmountCalculation()
			return;
		}
		let OCDiscountAmount = OCSubTotal * discount / 100;
		let OCSubTotalAfterDiscount = OCSubTotal - parseFloat(OCDiscountAmount);
		$('.OCDiscountAmount').val(OCDiscountAmount);
		$('.OCSubTotal').val(OCSubTotalAfterDiscount);
		finalTotalAmountCalculation();
	}

	function finalTotalAmountCalculation(conditionShippingCost = null) {
		let OCSubTotalAfterDiscount = parseFloat($('.OCSubTotal').val());
		let OCShippingCost = 0;
		if(conditionShippingCost != null){
			OCShippingCost = conditionShippingCost;
		}else{
			OCShippingCost = parseFloat($('.OCShippingCost').val());
		}
		let OCTax = parseFloat($('.OCTax').val());
		let OCInsurance = parseFloat($('.OCInsurance').val());
		let OCPickupCost = parseFloat($('.OCPickupCost').val());
		let OCSupplyCost = parseFloat($('.OCSupplyCost').val());
		let OCtotalPay = OCSubTotalAfterDiscount + OCShippingCost + OCTax + OCInsurance + OCPickupCost + OCSupplyCost;
		$('.OCtotalPay').val(OCtotalPay);
	}

	function variantQuantityHandel(id) {

		const variantQuantityId = `#variantQuantity_${id}`;
		let quantity = $(variantQuantityId).val();
		let variantPrice = $(`.variantPrice_${id}`).val();

		let totalPackingCost = quantity * variantPrice;
		$(`.totalPackingCost_${id}`).val(totalPackingCost);
	}

//	get sender and receiver from your branch
	$(document).on('change', '.sender_branch', function (){

		let selectedBranchId = $(this).val();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: "{{ route('getSelectedBranchSender') }}",
			method: 'POST',
			data: {
				branchId: selectedBranchId,
			},
			success: function (response) {
				let responseData = response;
				responseData.forEach(res => {
					$('.getSender').append(`<option value="${res.id}">${res.name}</option>`)
				})
				$('.getSender').prepend(`<option value="" selected disabled>@lang('Select Sender')</option>`)
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	})

	$(document).on('change', '.receiver_branch', function (){
		let selectedBranchId = $(this).val();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: "{{ route('getSelectedBranchReceiver') }}",
			method: 'POST',
			data: {
				branchId: selectedBranchId,
			},
			success: function (response) {
				let responseData = response;
				$('.getReceiver').empty();
				responseData.forEach(res => {
					$('.getReceiver').append(`<option value="${res.id}">${res.name} (${res.username})</option>`)
				})
				$('.getReceiver').prepend(`<option value="" selected disabled>@lang('Select Receiver')</option>`)
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	})



	let oldSenderIdPresent = "{{ $oldSenderIdPresent }}"
	let oldReceiverIdPresent = "{{ $oldReceiverIdPresent }}"


	if(oldSenderIdPresent == 1 || oldReceiverIdPresent == 1){
		let oldSenderBranch = $('.sender_branch').val();
		let oldReceiverBranch = $('.receiver_branch').val();
		let oldSenderId = $('.getSender').data('oldsenderid');
		let oldReceiverId = $('.getReceiver').data('oldreceiverid');
		getOldSender(oldSenderBranch, oldSenderId);
		getOldReceiver(oldReceiverBranch, oldReceiverId);
	}

	function getOldSender(oldSenderBranch, oldSenderId) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: "{{ route('getSelectedBranchSender') }}",
			method: 'POST',
			data: {
				branchId: oldSenderBranch,
			},
			success: function (response) {
				let responseData = response;
				responseData.forEach(res => {
					$('.getSender').append(`<option value="${res.id}" ${res.id == oldSenderId ? 'selected' : ''}>${res.name}</option>`)
				})
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}

	function getOldReceiver(oldReceiverBranch, oldReceiverId) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: "{{ route('getSelectedBranchReceiver') }}",
			method: 'POST',
			data: {
				branchId: oldReceiverBranch,
			},
			success: function (response) {
				let responseData = response;
				responseData.forEach(res => {
					$('.getReceiver').append(`<option value="${res.id}" ${res.id == oldReceiverId ? 'selected' : ''}>${res.name} (${res.username})</option>`)
				})
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}

</script>
