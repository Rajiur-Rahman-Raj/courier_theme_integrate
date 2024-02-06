<script>
	'use strict'
	var variantEditAccessRoute =  @json(adminAccessRouteForPackageVariant());

	$(document).on('change', '.selectedPackage', function () {
		let selectedValue = $(this).val();
		const selectedPack = false;
		getSelectedPackageVariant(selectedValue, selectedPack);

	})

	function selectedPackageVariantHandel(id = null) {
		let selectedValue;
		if (id) {
			selectedValue = $(`.selectedPackage_${id}`).val();
		}
		selectedValue = $(`.selectedPackage_${id}`).val();
		const selectedPack = false;
		getSelectedPackageVariant(selectedValue, selectedPack, null, id);
	}

	function getSelectedPackageVariant(value, type, variantId = null, packageId = null) {
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
				let selectedVariantClass = '.selectedVariant';

				if (type == true) {
					let result = [];

					if (0 < responseData.length) {
						responseData.forEach((res, key) => {
							var tr = `<tr>
									<td data-label="@lang('Variant')">
										${res.variant}
									</td>

									<td data-label="@lang('Image')">
										<a href="javascript:void(0)"
										   class="text-decoration-none">
											<div
												class="d-lg-flex d-block align-items-center branch-list-img">
												<div class="mr-3"><img
														src="${res.images}"
														alt="user"
														width="100" height="80">
												</div>
											</div>
										</a>
									</td>

									<td data-label="@lang('Status')" class="font-weight-bold text-dark">
										${res.statusMessage}
									</td>

									${variantEditAccessRoute == 1 ? `<td data-label="@lang('Action')">
										<button data-target="#updateVariantModal"
												data-toggle="modal"
												data-id="${res.id}"
												data-packageid="${res.package_id}"
												data-name="${res.variant}"
												data-image="${res.images}"
												data-status="${res.status}"
												data-route="${res.editVariantRoute}"
												class="btn btn-sm btn-outline-primary rounded-circle editVariant">
											<i class="fas fa-edit"></i> </button>
									</td>` : ''}

								</tr>`

							result[key] = tr;
						});
					} else {
						result[0] = `<tr class="text-center"> <td colspan="100%" class=""text-center>@lang('No Data Found')</td> </tr>`;
					}
					$('.packageVariantTr').html(result);
				} else {
					if (packageId){
						selectedVariantClass = `.selectedVariant_${packageId}`
					}

					$(selectedVariantClass).empty();
					responseData.forEach(res => {
						$(selectedVariantClass).append(`<option value="${res.id}">${res.variant}</option>`)
					})
					$(selectedVariantClass).prepend(`<option value="" selected disabled>@lang('Select Variant')</option>`)
					$('#variId').val(variantId);
				}
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});
	}


	$(document).on('change', '.selectedVariant', function () {
		let selectedPackage = $('.selectedPackage').val();
		let selectedVariant = $(this).val();
		getSelectedVariantService(selectedPackage, selectedVariant);
	})


	function selectedVariantServiceHandel(id = null) {
		let selectedPackageValue = $(`.selectedPackage`).val();
		let selectedVariantValue = $(`.selectedVariant`).val();
		if (id) {
			selectedPackageValue = $(`.selectedPackage_${id}`).val();
			selectedVariantValue = $(`.selectedVariant_${id}`).val();
		}

		getSelectedVariantService(selectedPackageValue, selectedVariantValue, id);
	}



	function getSelectedVariantService(selectedPackage, selectedVariant, id = null) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			url: "{{ route('getSelectedVariantService') }}",
			method: 'POST',
			data: {
				packageId: selectedPackage,
				variantId: selectedVariant,
			},
			success: function (response) {
				let responseData = response;
				let variantPriceClass = ".variantPrice";
				if (id) {
					variantPriceClass = `.variantPrice_${id}`;
				}
				$(variantPriceClass).val(responseData[0].cost)
				window.calculatePackingTotalPrice();
			},
			error: function (xhr, status, error) {
				console.log(error)
			}
		});

	}

</script>
