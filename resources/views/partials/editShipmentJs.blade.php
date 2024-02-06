<script>
	'use strict'

	// Manage location for edit shipment
	let singleShipment = @json($singleShipment);
	let fromCountryId = $('.selectedFromCountry').val();
	let fromStateId = $('.selectedFromState').val();
	if (fromStateId == null){
		fromStateId = $('.selectedFromState').data('fromstateid');
	}

	let fromCityId = $('.selectedFromCity').data('fromcityid');

	let toCountryId = $('.selectedToCountry').val();
	let toStateId = $('.selectedToState').val();
	if (toStateId == null){
		toStateId = $('.selectedToState').data('tostateid');
	}
	let toCityId = $('.selectedToCity').data('tocityid');

	if (singleShipment.from_state_id != null && singleShipment.from_city_id == null) {
		getFromCountryState(fromCountryId);
	}else if (singleShipment.from_city_id != null && singleShipment.from_area_id == null) {
		getFromCountryState(fromCountryId);
		getFromStateCity(fromStateId);
	} else if (singleShipment.from_area_id != null) {
		getFromStateCity(fromStateId);
		getFromCityArea(fromCityId);
	}

	if (singleShipment.to_state_id != null && singleShipment.to_city_id == null) {
		getToCountryState(toCountryId);
	}else if (singleShipment.to_city_id != null && singleShipment.to_area_id == null) {
		getToCountryState(toCountryId);
		getToStateCity(toStateId);
	} else if (singleShipment.from_area_id != null) {
		getToStateCity(toStateId);
		getToCityArea(toCityId);
	}


	function getFromCountryState(fromCountryId) {
		$.ajax({
			url: "{{ route('getSeletedCountryState') }}",
			method: 'POST',
			data: {
				id: fromCountryId,
			},
			success: function (response) {
				let responseData = response;
				responseData.forEach(res => {
					$('.selectedFromState').append(`<option value="${res.id}" ${res.id == singleShipment.from_state_id ? 'selected' : ''}>${res.name}</option>`)
				})
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}

	function getFromStateCity(fromStateId) {
		$.ajax({
			url: "{{ route('getSeletedStateCity') }}",
			method: 'POST',
			data: {
				id: fromStateId,
			},
			success: function (response) {
				let responseData = response;
				responseData.forEach(res => {
					$('.selectedFromCity').append(`<option value="${res.id}" ${res.id == singleShipment.from_city_id ? 'selected' : ''}>${res.name}</option>`)
				})
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}

	function getFromCityArea(fromCityId) {
		$.ajax({
			url: "{{ route('getSeletedCityArea') }}",
			method: 'POST',
			data: {
				id: fromCityId,
			},
			success: function (response) {
				let responseData = response;
				responseData.forEach(res => {
					$('.selectedFromArea').append(`<option value="${res.id}" ${res.id == singleShipment.from_area_id ? 'selected' : ''}>${res.name}</option>`)
				})
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}


	function getToCountryState(toCountryId) {
		$.ajax({
			url: "{{ route('getSeletedCountryState') }}",
			method: 'POST',
			data: {
				id: toCountryId,
			},
			success: function (response) {
				let responseData = response;
				responseData.forEach(res => {
					$('.selectedToState').append(`<option value="${res.id}" ${res.id == singleShipment.to_state_id ? 'selected' : ''}>${res.name}</option>`)
				})
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}

	function getToStateCity(toStateId) {
		$.ajax({
			url: "{{ route('getSeletedStateCity') }}",
			method: 'POST',
			data: {
				id: toStateId,
			},
			success: function (response) {
				let responseData = response;
				responseData.forEach(res => {
					$('.selectedToCity').append(`<option value="${res.id}" ${res.id == singleShipment.to_city_id ? 'selected' : ''}>${res.name}</option>`)
				})
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}

	function getToCityArea(toCityId) {
		$.ajax({
			url: "{{ route('getSeletedCityArea') }}",
			method: 'POST',
			data: {
				id: toCityId,
			},
			success: function (response) {
				let responseData = response;
				responseData.forEach(res => {
					$('.selectedToArea').append(`<option value="${res.id}" ${res.id == singleShipment.to_area_id ? 'selected' : ''}>${res.name}</option>`)
				})
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}

	//	Manage packing service js for edit shipment
	let packingData = @json($singleShipment->packing_services);
	if (packingData) {
		packingData.forEach((item, index) => {
			selectedPackageVariant(item.package_id, item.variant_id, index)
		})
	}

	function selectedPackageVariant(packageId, variantId, index) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: "{{ route('getSelectedPackageVariant') }}",
			method: 'POST',
			data: {
				id: packageId,
			},
			success: function (response) {
				let responseData = response;

				responseData.forEach(res => {
					$(`.selectedVariant_${index}`).append(`<option value="${res.id}" ${res.id == variantId ? 'selected' : ''}>${res.variant}</option>`)
				})
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}


	//	Mangage parcel service js for edit shipment
	let parcelData = @json($singleShipment->parcel_information);
	if (parcelData) {
		parcelData.forEach((item, index) => {
			selectedParcelTypeUnit(item.parcel_type_id, item.parcel_unit_id, index)
		})
	}

	function selectedParcelTypeUnit(parcelTypeId, parcelUnitId, index) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: "{{ route('getSelectedParcelTypeUnit') }}",
			method: 'POST',
			data: {
				id: parcelTypeId,
			},
			success: function (response) {
				let responseData = response;

				responseData.forEach(res => {
					$(`.selectedParcelUnit_${index}`).append(`<option value="${res.id}" ${res.id == parcelUnitId ? 'selected' : ''}>${res.unit}</option>`)
				})
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}


	var shipment_images = {!! json_encode($shipmentAttatchments->toArray()) !!};
	let preloaded = [];
	shipment_images.forEach(function (value, index) {
		preloaded.push({
			id: value.id,
			shipment_id: value.shipment_id,
			image_name: value.image,
			src: value.src
		});
	});

	let shipmentImageOptions = {
		preloaded: preloaded,
		imagesInputName: 'shipment_image',
		preloadedInputName: 'old_shipment_image',
		label: 'Drag & Drop files here or click to browse images',
		extensions: ['.jpg', '.jpeg', '.png'],
		mimes: ['image/jpeg', 'image/png'],
		maxSize: 5242880
	};
	$('.shipment_image').imageUploader(shipmentImageOptions);


	$(".flatpickr2").flatpickr({
		wrap: true,
		minDate: "today",
		altInput: true,
		dateFormat: "Y-m-d H:i",
	});

</script>
