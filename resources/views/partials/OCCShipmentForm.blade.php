<form method="post" action="{{ route('shipmentStore', 'operator-country') }}"
	  class="mt-4" enctype="multipart/form-data">
	@csrf
	<div class="row mb-3">
		<div class="col-sm-12 col-md-12 mb-3">
			<h6 for="branch_id"
				class="text-dark font-weight-bold"> @lang('Shipment Type') </h6>
			<div class="custom-control custom-radio">
				<input type="radio" id="shipmentTypeDropOff"
					   name="shipment_type"
					   value="drop_off"
					   class="custom-control-input" checked @if(old('shipment_type') === 'drop_off') checked @endif>
				<label class="custom-control-label"
					   for="shipmentTypeDropOff">@lang($shipmentTypeList[0]['shipment_type'])
					(@lang($shipmentTypeList[0]['title']))</label>
			</div>

			<div class="custom-control custom-radio">
				<input type="radio" id="shipmentTypePickup"
					   name="shipment_type"
					   value="pickup"
					   data-resource="{{ $defaultShippingRateOC }}"
					   class="custom-control-input" @if(old('shipment_type') === 'pickup') checked @endif>
				<label class="custom-control-label"
					   for="shipmentTypePickup">@lang($shipmentTypeList[1]['shipment_type'])
					(@lang($shipmentTypeList[1]['title']))</label>
			</div>

			<div class="custom-control custom-radio">
				<input type="radio" id="shipmentTypeCondition"
					   name="shipment_type"
					   value="condition"
					   class="custom-control-input" @if(old('shipment_type') === 'condition') checked @endif>
				<label class="custom-control-label"
					   for="shipmentTypeCondition">@lang($shipmentTypeList[2]['shipment_type'])
					(@lang($shipmentTypeList[2]['title']))</label>
			</div>
			<div class="invalid-feedback d-block">
				@error('shipment_type') @lang($message) @enderror
			</div>
		</div>
	</div>


	<div class="row get_receive_amount d-none">
		<div class="col-sm-12 col-md-3 mb-3">
			<label for="receive_amount"> @lang('Get Amount From Receiver') </label>
			<div class="input-group">
				<input type="text" class="form-control"
					   name="receive_amount"
					   value="{{ old('receive_amount',request()->receive_amount) }}"
					   placeholder="0.00"
					   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
					   min="0"
					   autocomplete="off"/>
				<div class="input-group-append">
					<div class="form-control">
						{{ $basic->currency_symbol }}
					</div>
				</div>
				<div class="invalid-feedback d-block">
					@error('receive_amount') @lang($message) @enderror
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12 col-md-3 mb-3">
			<label for="shipment_date"> @lang('Shipment Date') </label>
			<div class="flatpickr">
				<div class="input-group">
					<input type="date" placeholder="@lang('Select date-time')" class="form-control shipment_date"
						   name="shipment_date"
						   value="{{ old('shipment_date',request()->shipment_date) }}" data-input>
					<div class="input-group-append" readonly="">
						<div class="form-control">
							<a class="input-button cursor-pointer" title="clear" data-clear>
								<i class="fas fa-times"></i>
							</a>
						</div>
					</div>
					<div class="invalid-feedback d-block">
						@error('shipment_date') @lang($message) @enderror
					</div>
				</div>
			</div>
		</div>


		<div class="col-sm-12 col-md-3 mb-3">
			<label for="delivery_date"> @lang('Estimate Delivery Date') </label>
			<div class="flatpickr">
				<div class="input-group">
					<input type="date" placeholder="@lang('Select Date')" class="form-control delivery_date"
						   name="delivery_date"
						   value="{{ old('delivery_date',request()->delivery_date) }}" data-input>
					<div class="input-group-append" readonly="">
						<div class="form-control">
							<a class="input-button cursor-pointer" title="clear" data-clear>
								<i class="fas fa-times"></i>
							</a>
						</div>
					</div>
				</div>
				<div class="invalid-feedback d-block">
					@error('delivery_date') @lang($message) @enderror
				</div>
			</div>
		</div>

		<div class="col-sm-12 col-md-3 mb-3">
			<label for="sender_branch"> @lang('Sender Branch') </label>
			<select name="sender_branch"
					class="form-control @error('sender_branch') is-invalid @enderror select2 select-branch sender_branch">
				<option value="" disabled selected>@lang('Select Branch')</option>
				@foreach($allBranches as $branch)
					<option
						value="{{ $branch->id }}" {{ old('sender_branch') == $branch->id ? 'selected' : ''}}>@lang($branch->branch_name)</option>
				@endforeach
			</select>
			<div class="invalid-feedback">
				@error('sender_branch') @lang($message) @enderror
			</div>
		</div>

		<div class="col-sm-12 col-md-3 mb-3">
			<label for="receiver_branch"> @lang('Receiver Branch')</label>
			<select name="receiver_branch"
					class="form-control @error('receiver_branch') is-invalid @enderror select2 select-branch receiver_branch">
				<option value="" disabled selected>@lang('Select Branch')</option>
				@foreach($allBranches as $branch)
					<option
						value="{{ $branch->id }}" {{ old('receiver_branch') == $branch->id ? 'selected' : ''}}>@lang($branch->branch_name)</option>
				@endforeach
			</select>

			<div class="invalid-feedback">
				@error('receiver_branch') @lang($message) @enderror
			</div>
			<div class="valid-feedback"></div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12 col-md-3 mb-3">
			<label for="sender_id"> @lang('Sender')</label>
			<select name="sender_id"
					class="form-control @error('sender_id') is-invalid @enderror select2 select-client getSender"
					data-oldsenderid="{{ old('sender_id') }}">
			</select>

			<div class="invalid-feedback">
				@error('sender_id') @lang($message) @enderror
			</div>
		</div>

		<div class="col-sm-12 col-md-3 mb-3">
			<label for="receiver_id"> @lang('Receiver')</label>
			<select name="receiver_id"
					class="form-control @error('receiver_id') is-invalid @enderror select2 select-client getReceiver"
					data-oldreceiverid="{{ old('receiver_id') }}">
			</select>

			<div class="invalid-feedback">
				@error('receiver_id') @lang($message) @enderror
			</div>
		</div>

		<div class="col-sm-12 col-md-3 mb-3">
			<label for="from_state_id">@lang('From State') </label>
			<select name="from_state_id"
					class="form-control @error('from_state_id') is-invalid @enderror select2 select2State selectedFromState">
				<option value="" selected disabled>@lang('Select State')</option>
				@foreach(optional($basicControl->operatorCountry)->state() as $state)
					<option
						value="{{ $state->id }}" {{ old('from_state_id') == $state->id ? 'selected' : ''}}>@lang($state->name)</option>
				@endforeach
			</select>
			<div class="invalid-feedback">
				@error('from_state_id') @lang($message) @enderror
			</div>
		</div>

		<div class="col-sm-12 col-md-3 mb-3">
			<label for="from_city_id">@lang('Select City') <span
					class="font-weight-bold"><sub>(@lang('optional'))</sub></span></label>
			<select name="from_city_id"
					class="form-control @error('from_city_id') is-invalid @enderror select2 select2City selectedFromCity"
					data-oldfromcityid="{{ old('from_city_id') }}">
			</select>
			<div class="invalid-feedback">
				@error('from_city_id') @lang($message) @enderror
			</div>
		</div>

		<div class="col-sm-12 col-md-3 mb-3">
			<label for="from_area_id">@lang('Select Area') <span
					class="font-weight-bold"><sub>(@lang('optional'))</sub></span></label>
			<select name="from_area_id"
					class="form-control @error('from_area_id') is-invalid @enderror select2 select2Area selectedFromArea"
					data-oldfromareaid="{{ old('from_area_id') }}">
			</select>
			<div class="invalid-feedback">
				@error('from_area_id') @lang($message) @enderror
			</div>
		</div>

		<div class="col-sm-12 col-md-3 mb-3">
			<label for="to_state_id">@lang('To State') </label>
			<select name="to_state_id"
					class="form-control @error('to_state_id') is-invalid @enderror select2 select2State selectedToState">
				<option value="" selected disabled>@lang('Select State')</option>
				@foreach(optional($basicControl->operatorCountry)->state() as $state)
					<option
						value="{{ $state->id }}" {{ old('to_state_id') == $state->id ? 'selected' : ''}}>@lang($state->name)</option>
				@endforeach
			</select>
			<div class="invalid-feedback">
				@error('to_state_id') @lang($message) @enderror
			</div>
		</div>

		<div class="col-sm-12 col-md-3 mb-3">
			<label for="to_city_id">@lang('Select City') <span
					class="font-weight-bold"><sub>(@lang('optional'))</sub></span></label>
			<select name="to_city_id"
					class="form-control @error('to_city_id') is-invalid @enderror select2 select2City selectedToCity"
					data-oldtocityid="{{ old('to_city_id') }}">
			</select>
			<div class="invalid-feedback">
				@error('to_city_id') @lang($message) @enderror
			</div>
		</div>


		<div class="col-sm-12 col-md-3 mb-3">
			<label for="to_area_id">@lang('Select Area') <span
					class="font-weight-bold"><sub>(@lang('optional'))</sub></span>
			</label>
			<select name="to_area_id"
					class="form-control @error('to_area_id') is-invalid @enderror select2 select2Area selectedToArea"
					data-oldtoareaid="{{ old('to_area_id') }}">
			</select>
			<div class="invalid-feedback">
				@error('to_area_id') @lang($message) @enderror
			</div>
		</div>

		<div class="col-sm-12 col-md-4 mb-3">
			<label for="payment_by"> @lang('Payment By')</label>
			<select name="payment_by"
					class="form-control @error('payment_by') is-invalid @enderror payment_by">
				<option value="1" {{ old('payment_by') == '1' ? 'selected' : '' }}>@lang('Sender')</option>
				<option value="2" {{ old('payment_by') == '2' ? 'selected' : '' }}>@lang('Receiver')</option>
			</select>
			<div class="invalid-feedback">
				@error('payment_by') @lang($message) @enderror
			</div>
		</div>

		<div class="col-sm-12 col-md-4 mb-3">
			<label for="payment_type"> @lang('Payment Type')</label>
			<select name="payment_type"
					class="form-control @error('payment_type') is-invalid @enderror select2">
				<option value="" disabled
						selected>@lang('Select Payment Type')</option>
				<option
					value="wallet" {{ old('payment_type') == 'wallet' ? 'selected' : '' }}>@lang('From Wallet')</option>
				<option value="cash" {{ old('payment_type') == 'cash' ? 'selected' : '' }}>@lang('Cash')</option>
			</select>

			<div class="invalid-feedback">
				@error('payment_type') @lang($message) @enderror
			</div>
		</div>

		<div class="col-sm-12 col-md-4 mb-3">
			<label for="payment_status"> @lang('Payment Status')</label>
			<select name="payment_status"
					class="form-control @error('payment_status') is-invalid @enderror select2" required>
				<option value="" disabled
						selected>@lang('Select Payment Status')</option>
				<option value="1" {{ old('payment_status') == '1' ? 'selected' : '' }}>@lang('Paid')</option>
				<option value="2" {{ old('payment_status') == '2' ? 'selected' : '' }}>@lang('Unpaid')</option>
			</select>

			<div class="invalid-feedback">
				@error('payment_status') @lang($message) @enderror
			</div>
		</div>
	</div>


	<div class="row mb-3">
		<div class="col-sm-12 col-md- mt-3">
			<h6 class="text-dark font-weight-bold"> @lang('Packing Service') </h6>
			<div class="custom-control custom-radio">
				<input type="radio" id="packingServiceOn" name="packing_service"
					   class="custom-control-input" checked @if(old('packing_service') === 'yes') checked
					   @endif value="yes">
				<label class="custom-control-label"
					   for="packingServiceOn">@lang('Yes')</label>
			</div>
			<div class="custom-control custom-radio">
				<input type="radio" id="packingServiceOff" value="no"
					   name="packing_service"
					   class="custom-control-input" @if(old('packing_service') === 'no') checked @endif>
				<label class="custom-control-label"
					   for="packingServiceOff">@lang('No')</label>
			</div>
		</div>

		<div class="col-md-12 addPackingFieldButton d-none">
			<div class="form-group">
				<a href="javascript:void(0)"
				   class="btn btn-success float-right"
				   id="packingGenerate"><i
						class="fa fa-plus-circle"></i> {{ trans('Add More') }}
				</a>
			</div>
		</div>
	</div>


	<div class="packingField d-none">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="input-group">
						<select name="package_id[]"
								class="form-control @error('package_id.0') is-invalid @enderror selectedPackage">
							<option value="" disabled
									selected>@lang('Select package')</option>
							@foreach($packageList as $package)
								<option
									value="{{ $package->id }}" {{ old('package_id.0') == $package->id ? 'selected' : '' }}>@lang($package->package_name)</option>
							@endforeach
						</select>

						<div class="invalid-feedback">
							@error('package_id.0') @lang($message) @enderror
						</div>

						<select name="variant_id[]"
								class="form-control @error('variant_id.0') is-invalid @enderror selectedVariant"
								data-oldvariant='{{ old("variant_id.0") }}'>
							<option value="">@lang('Select Variant')</option>
						</select>

						<input type="text" name="variant_price[]"
							   class="form-control @error('variant_price.0') is-invalid @enderror variantPrice newVariantPrice"
							   placeholder="@lang('price')" value="{{ old('variant_price.0') }}" readonly>
						<div class="input-group-append">
							<div class="form-control">
								{{ config('basic.currency_symbol') }}
							</div>
						</div>

						<input type="text" name="variant_quantity[]"
							   class="form-control @error('variant_quantity.0') is-invalid @enderror newVariantQuantity"
							   value="{{ old('variant_quantity.0') }}"
							   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
							   min="1"
							   id="variantQuantity"
							   placeholder="@lang('quantity')">

						<input type="text" name="package_cost[]"
							   class="form-control @error('package_cost.0') is-invalid @enderror managerEmail totalPackingCost packingCostValue"
							   value="{{ old('package_cost.0') }}" readonly
							   placeholder="@lang('total cost')">
						<div class="input-group-append" readonly="">
							<div class="form-control">
								{{ config('basic.currency_symbol') }}
							</div>
						</div>
						<span class="input-group-btn">
								<button
									class="btn btn-danger delete_packing_desc custom_delete_desc_padding"
									type="button">
									<i class="fa fa-times"></i>
								</button>
                            </span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="addedPackingField">
		@php
			$oldPackingCounts = old('variant_price') ? count(old('variant_price')) : 0;
		@endphp

		@if($oldPackingCounts > 1)
			@for($i = 1; $i < $oldPackingCounts; $i++)

				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<div class="input-group">
								<select name="package_id[]"
										class="form-control @error("package_id.$i") is-invalid @enderror selectedPackage_{{$i}}"
										onchange="selectedPackageVariantHandel({{$i}})" required>
									<option value="" disabled selected>@lang('Select package')</option>
									@foreach($packageList as $package)
										<option
											value="{{ $package->id }}" {{ old("package_id.$i") == $package->id ? 'selected' : '' }}>@lang($package->package_name)</option>
									@endforeach
								</select>

								<select name="variant_id[]" class="form-control selectedVariant_{{$i}} newVariant"
										data-oldvariant='{{ old("variant_id.$i") }}'
										onchange="selectedVariantServiceHandel({{$i}})" required>
									<option value="">@lang('Select Variant')</option>
								</select>


								<input type="text" name="variant_price[]" value="{{ old("variant_price.$i") }}"
									   class="form-control @error("variant_price.$i") is-invalid @enderror newVariantPrice variantPrice_{{$i}}"
									   placeholder="@lang('price')"
									   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')" readonly>
								<div class="input-group-append" readonly="">
									<div class="form-control">
										{{ config('basic.currency_symbol') }}
									</div>
								</div>

								<input type="text" name="variant_quantity[]"
									   class="form-control @error("variant_quantity.$i") is-invalid @enderror newVariantQuantity"
									   value="{{ old("variant_quantity.$i") }}" id="variantQuantity_{{$i}}"
									   onkeyup="variantQuantityHandel({{$i}})" placeholder="@lang('quantity')" required>
								<input type="text" name="package_cost[]"
									   class="form-control @error('package_cost') is-invalid @enderror totalPackingCost_{{$i}} packingCostValue"
									   value="{{ old("package_cost.$i") }} " readonly
									   placeholder="@lang('total cost')">
								<div class="input-group-append">
									<div class="form-control">
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
				</div>
			@endfor
		@endif
	</div>

	<div class="row mt-4">
		<div class="col-md-12 d-flex justify-content-between">
			<div>
				<h6 for="branch_id"
					class="text-dark font-weight-bold"> @lang('Shipment Parcel Information') </h6>
			</div>

			<div class="addParcelFieldButton">
				<div class="form-group">
					<a href="javascript:void(0)"
					   class="btn btn-success float-right"
					   id="parcelGenerate"><i
							class="fa fa-plus-circle"></i> {{ trans('Add More') }}
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="add_cod_parcel_info d-none">
		<div class="row">
			<div class="col-sm-12 col-md-12 mb-3">
				<label
					for="parcel_details"> @lang('Parcel Details') </label>
				<textarea type="text" name="parcel_details"
						  class="form-control @error('parcel_details') is-invalid @enderror"
						  id="cod_parcel_details"
						  value="{{ old('parcel_details') }}"
						  placeholder="@lang('parcel details')" rows="20"
						  cols="20">{{ old('parcel_details') }}</textarea>
				<div class="invalid-feedback d-block">
					@error('parcel_details') @lang($message) @enderror
				</div>
			</div>
		</div>
	</div>

	<div class="parcelField">
		<div class="row">
			<div class="col-sm-12 col-md-3 mb-3">
				<label
					for="parcel_name"> @lang('Parcel Name') </label>
				<input type="text" name="parcel_name[]"
					   class="form-control @error('parcel_name.0') is-invalid @enderror"
					   value="{{ old('parcel_name.0') }}">
				<div class="invalid-feedback">
					@error('parcel_name.0') @lang($message) @enderror
				</div>
			</div>

			<div class="col-sm-12 col-md-3 mb-3">
				<label for="parcel_quantity"> @lang('Parcel Quantity')</label>
				<input type="text" name="parcel_quantity[]"
					   class="form-control @error('parcel_quantity.0') is-invalid @enderror"
					   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
					   value="{{ old('parcel_quantity.0') }}">
				<div class="invalid-feedback">
					@error('parcel_quantity.0') @lang($message) @enderror
				</div>
			</div>

			<div class="col-sm-12 col-md-3 mb-3">
				<label for="parcel_type_id"> @lang('Parcel Type') </label>
				<select name="parcel_type_id[]"
						class="form-control @error('parcel_type_id.0') is-invalid @enderror select2 selectedParcelType select2ParcelType OCParcelTypeWiseShippingRate">
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

			<div class="col-sm-12 col-md-3 mb-3">
				<label for="parcel_unit_id"> @lang('Select Unit') </label>
				<select name="parcel_unit_id[]"
						class="form-control @error('parcel_unit_id.0') is-invalid @enderror selectedParcelUnit"
						data-oldparcelunitid='{{ old("parcel_unit_id.0") }}'>
					<option value="" disabled
							selected>@lang('Select Parcel Unit')</option>
				</select>

				<div class="invalid-feedback">
					@error('parcel_unit_id.0') @lang($message) @enderror
				</div>
			</div>

			<div class="col-sm-12 col-md-4 mb-3">

				<label for="cost_per_unit"> @lang('Cost per unit')</label>
				<div class="input-group">
					<input type="text" name="cost_per_unit[]"
						   class="form-control @error('cost_per_unit.0') is-invalid @enderror unitPrice newCostPerUnit"
						   value="{{ old('cost_per_unit.0') }}" readonly>
					<div class="input-group-append" readonly="">
						<div class="form-control">
							{{ $basic->currency_symbol }}
						</div>
					</div>

					<div class="invalid-feedback">
						@error('cost_per_unit.0') @lang($message) @enderror
					</div>

				</div>
			</div>

			<div class="col-sm-12 col-md-4 mb-3 new_total_weight_parent">
				<label for="total_unit"> @lang('Total Unit')</label>
				<div class="input-group">
					<input type="text" name="total_unit[]"
						   class="form-control @error('total_unit.0') is-invalid @enderror newTotalWeight"
						   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
						   value="{{ old('total_unit.0') }}">
					<div class="input-group-append" readonly="">
						<div class="form-control">
							@lang('kg')
						</div>
					</div>
					<div class="invalid-feedback d-block">
						@error('total_unit.0') @lang($message) @enderror
					</div>
				</div>
			</div>

			<div class="col-sm-12 col-md-4 mb-3">
				<label for="parcel_total_cost"> @lang('Total Cost')</label>
				<div class="input-group">
					<input type="text" name="parcel_total_cost[]"
						   class="form-control @error('parcel_total_cost.0') is-invalid @enderror totalParcelCost"
						   value="{{ old('parcel_total_cost.0') }}" readonly>
					<div class="input-group-append" readonly="">
						<div class="form-control">
							{{ $basic->currency_symbol }}
						</div>
					</div>
				</div>

				<div class="invalid-feedback">
					@error('parcel_total_cost.0') @lang($message) @enderror
				</div>
			</div>

			<div class="col-sm-12 col-md-12 d-flex justify-content-between">
				<label for="email"> @lang('Dimensions') [Length x Width x Height]
					(cm)
					<span
						class="text-dark font-weight-bold">(@lang('optional'))</span></label>
			</div>

			<div class="col-sm-12 col-md-4 mb-3">
				<input type="text" name="parcel_length[]"
					   class="form-control @error('parcel_length.0') is-invalid @enderror"
					   value="{{ old('parcel_length.0') }}">
				<div class="invalid-feedback">
					@error('parcel_length.0') @lang($message) @enderror
				</div>
			</div>

			<div class="col-sm-12 col-md-4 mb-3">
				<input type="text" name="parcel_width[]"
					   class="form-control @error('parcel_width.0') is-invalid @enderror"
					   value="{{ old('parcel_width.0') }}">
				<div class="invalid-feedback">
					@error('parcel_width.0') @lang($message) @enderror
				</div>
			</div>

			<div class="col-sm-12 col-md-4 mb-3">
				<input type="text" name="parcel_height[]"
					   class="form-control @error('parcel_height.0') is-invalid @enderror"
					   value="{{ old('parcel_height.0') }}">
				<div class="invalid-feedback">
					@error('parcel_height.0') @lang($message) @enderror
				</div>
			</div>
		</div>
	</div>

	<div class="addedParcelField">

		@php
			$oldParcelCounts = old('parcel_name') ? count(old('parcel_name')) : 0;
		@endphp

		@if($oldParcelCounts > 1)
			@for($i = 1; $i < $oldParcelCounts; $i++)
				<div class="row addMoreParcelBox" id="removeParcelField{{$i}}">
					<div class="col-md-12 d-flex justify-content-end">
						<button
							class="btn btn-danger  delete_parcel_desc custom_delete_desc_padding mt-4"
							type="button" onclick="deleteParcelField({{$i}})">
							<i class="fa fa-times"></i>
						</button>
					</div>
					<div class="col-sm-12 col-md-3 mb-3">
						<label for="parcel_name"> @lang('Parcel Name') </label>
						<input type="text" name="parcel_name[]"
							   class="form-control @error("parcel_name.$i") is-invalid @enderror"
							   value="{{ old("parcel_name.$i") }}" required>
						<div class="invalid-feedback">
							@error("parcel_name.$i") @lang($message) @enderror
						</div>
					</div>

					<div class="col-sm-12 col-md-3 mb-3">
						<label for="parcel_quantity"> @lang('Parcel Quantity')</label>
						<input type="number" name="parcel_quantity[]"
							   class="form-control @error("parcel_quantity.$i") is-invalid @enderror"
							   value='{{ old("parcel_quantity.$i") }}' required>
						<div class="invalid-feedback">
							@error("parcel_quantity.$i") @lang($message) @enderror
						</div>
					</div>

					<div class="col-sm-12 col-md-3 mb-3">
						<label for="parcel_type_id"> @lang('Parcel Type') </label>
						<select name="parcel_type_id[]"
								class="form-control @error("parcel_type_id.$i") is-invalid @enderror OCParcelTypeWiseShippingRate select2 selectedParcelType_{{$i}} select2ParcelType"
								onchange="selectedParcelTypeHandel({{$i}})" required>
							<option value="" disabled selected>@lang('Select Parcel Type')</option>
							@foreach($parcelTypes as $parcel_type)
								<option
									value="{{ $parcel_type->id }}" {{ old("parcel_type_id.$i") == $parcel_type->id ? 'selected' : '' }}>@lang($parcel_type->parcel_type)</option>
							@endforeach
						</select>

						<div class="invalid-feedback">
							@error("parcel_type_id.$i") @lang($message) @enderror
						</div>
					</div>

					<div class="col-sm-12 col-md-3 mb-3">
						<label for="parcel_unit_id"> @lang('Select Unit') </label>
						<select name="parcel_unit_id[]"
								class="form-control @error("parcel_unit_id.$i") is-invalid @enderror selectedParcelUnit_{{$i}}"
								data-oldparcelunitid='{{ old("parcel_unit_id.$i") }}'
								onchange="selectedParcelServiceHandel({{$i}})" required>
							<option value="" disabled
									selected>@lang('Select Parcel Unit')</option>
						</select>

						<div class="invalid-feedback">
							@error("parcel_unit_id.$i") @lang($message) @enderror
						</div>
					</div>


					<div class="col-sm-12 col-md-4 mb-3">
						<label for="cost_per_unit"> @lang('Cost per unit')</label>
						<div class="input-group">
							<input type="text" name="cost_per_unit[]"
								   class="form-control @error("cost_per_unit.$i") is-invalid @enderror newCostPerUnit unitPrice_{{$i}}"
								   value="{{ old("cost_per_unit.$i") }}" readonly>
							<div class="input-group-append" readonly="">
								<div class="form-control">
									{{ $basic->currency_symbol }}
								</div>
							</div>

							<div class="invalid-feedback">
								@error("cost_per_unit.$i") @lang($message) @enderror
							</div>

						</div>
					</div>

					<div class="col-sm-12 col-md-4 mb-3 new_total_weight_parent">
						<label for="total_unit"> @lang('Total Unit')</label>
						<div class="input-group">
							<input type="text" name="total_unit[]"
								   class="form-control @error("total_unit.$i") is-invalid @enderror newTotalWeight"
								   value="{{ old("total_unit.$i") }}" required>
							<div class="input-group-append" readonly="">
								<div class="form-control">
									@lang('kg')
								</div>
							</div>
						</div>
						<div class="invalid-feedback"> @error("total_unit.$i") @lang($message) @enderror </div>
					</div>

					<div class="col-sm-12 col-md-4 mb-3">
						<label for="parcel_total_cost"> @lang('Total Cost')</label>
						<div class="input-group">
							<input type="text" name="parcel_total_cost[]"
								   class="form-control @error("parcel_total_cost.$i") is-invalid @enderror totalParcelCost"
								   value="{{ old("parcel_total_cost.$i") }}" readonly>
							<div class="input-group-append" readonly="">
								<div class="form-control">
									{{ $basic->currency_symbol }}
								</div>
							</div>
						</div>

						<div class="invalid-feedback">
							@error("parcel_total_cost.$i") @lang($message) @enderror
						</div>
					</div>

					<div class="col-sm-12 col-md-12">
						<label> @lang('Dimensions') [Length x Width x Height] (cm)
							<span class="text-dark font-weight-bold">(optional)</span></label>
					</div>

					<div class="col-sm-12 col-md-4 mb-3">
						<input type="text" name="parcel_length[]"
							   class="form-control @error("parcel_length.$i") is-invalid @enderror"
							   value="{{ old("parcel_length.$i") }}">
						<div class="invalid-feedback">
							@error("parcel_length.$i") @lang($message) @enderror
						</div>
					</div>

					<div class="col-sm-12 col-md-4 mb-3">
						<input type="text" name="parcel_width[]"
							   class="form-control @error("parcel_width.$i") is-invalid @enderror"
							   value="{{ old("parcel_width.$i") }}">
						<div class="invalid-feedback">
							@error("parcel_width.$i") @lang($message) @enderror
						</div>
					</div>

					<div class="col-sm-12 col-md-4 mb-3">
						<input type="text" name="parcel_height[]"
							   class="form-control @error("parcel_height.$i") is-invalid @enderror"
							   value="{{ old("parcel_height.$i") }}">
						<div class="invalid-feedback">
							@error("parcel_height.$i") @lang($message) @enderror
						</div>
					</div>
				</div>
			@endfor
		@endif
	</div>

	<div class="row">
		<div class="col-sm-12 col-md-12">
			<div class="form-group mb-4">
				<label class="col-form-label">@lang("Attatchments")</label>
				<div class="shipment_image"></div>
				@error('shipment_image.*')
				<span class="text-danger">@lang($message)</span>
				@enderror
			</div>
		</div>


		<div class="col-md-5 form-group d-none">
			<label>@lang('Status')</label>
			<div class="selectgroup w-100">
				<label class="selectgroup-item">
					<input type="radio" name="status" value="0"
						   class="selectgroup-input" {{ old('status') == 0 ? 'checked' : ''}}>
					<span class="selectgroup-button">@lang('OFF')</span>
				</label>
				<label class="selectgroup-item">
					<input type="radio" name="status" value="1"
						   class="selectgroup-input" checked {{ old('status') == 1 ? 'checked' : ''}}>
					<span class="selectgroup-button">@lang('ON')</span>
				</label>
			</div>
		</div>
	</div>


	<div class="border-line-area">
		<h6 class="border-line-title">@lang('Summary')</h6>
	</div>

	<div class="d-flex justify-content-end shipmentsDiscount">
		<div class="col-md-3">
			<div class="input-group">
				<span class="input-group-text">@lang('Discount')</span>
				<input type="text" name="discount" value="{{ old('discount') ?? '0' }}"
					   class="form-control bg-white text-dark OCDiscount"
					   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
					   id="discount" min="0">
				<span class="input-group-text">%</span>
			</div>
		</div>
	</div>

	<div class=" d-flex justify-content-end mt-2">
		<div class="col-md-3 d-flex justify-content-between">
			<span class="fw-bold">@lang('Discount Amount')</span>
			<div class="input-group w-50">
				<input type="number" name="discount_amount" value="{{ old('discount_amount') ?? '0' }}"
					   class="form-control bg-white text-dark OCDiscountAmount"
					   data-discountamount="{{ old('discount_amount') }}"
					   readonly>
				<div class="input-group-append" readonly="">
					<div class="form-control">
						{{ $basic->currency_symbol }}
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class=" d-flex justify-content-end mt-2">
		<div class="col-md-3 d-flex justify-content-between">
			<span class="fw-bold">@lang('Subtotal')</span>
			<div class="input-group w-50">
				<input type="number" name="sub_total" value="{{ old('sub_total') ?? '0' }}"
					   class="form-control bg-white text-dark OCSubTotal" data-subtotal="{{ old('sub_total') }}"
					   readonly>
				<div class="input-group-append" readonly="">
					<div class="form-control">
						{{ $basic->currency_symbol }}
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="pickup d-none">
		<div class=" d-flex justify-content-end mt-2">
			<div class="col-md-3 d-flex justify-content-between">
				<span class="fw-bold">@lang('Pickup Cost')</span>
				<div class="input-group w-50">
					<input type="text" name="pickup_cost" value="{{ old('pickup_cost') ?? '0' }}"
						   data-pickupcost="{{ old('pickup_cost') }}"
						   class="form-control bg-white text-dark OCPickupCost"
						   readonly>
					<div class="input-group-append" readonly="">
						<div class="form-control">
							{{ $basic->currency_symbol }}
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class=" d-flex justify-content-end mt-2">
			<div class="col-md-3 d-flex justify-content-between">
				<span class="fw-bold">@lang('Supply Cost')</span>
				<div class="input-group w-50">
					<input type="text" name="supply_cost" value="{{ old('supply_cost') ?? '0' }}"
						   data-supplycost="{{ old('supply_cost') }}"
						   class="form-control bg-white text-dark OCSupplyCost"
						   readonly>
					<div class="input-group-append" readonly="">
						<div class="form-control">
							{{ $basic->currency_symbol }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class=" d-flex justify-content-end mt-2">
		<div class="col-md-3 d-flex justify-content-between">
			<span class="fw-bold">@lang('Shipping Cost')</span>
			<div class="input-group w-50">
				<input type="text" name="shipping_cost" value="{{ old('shipping_cost') ?? '0' }}"
					   data-shippingcost="{{ old('shipping_cost') }}"
					   class="form-control bg-white text-dark OCShippingCost"
					   readonly>
				<input type="hidden" name="return_shipment_cost" value="{{ old('return_shipment_cost') ?? '0' }}"
					   data-shippingcost="{{ old('return_shipment_cost') }}"
					   class="form-control bg-white text-dark OCReturnShipmentCost"
					   readonly>

				<input type="hidden" name="cod_return_shipment_cost"
					   value="{{ old('cod_return_shipment_cost') ?? '0' }}"
					   data-shippingcost="{{ old('cod_return_shipment_cost') }}"
					   class="form-control bg-white text-dark OC_CodReturnShipmentCost"
					   readonly>

				<div class="input-group-append">
					<div class="form-control">
						{{ $basic->currency_symbol }}
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class=" d-flex justify-content-end mt-2">
		<div class="col-md-3 d-flex justify-content-between">
			<span class="fw-bold">@lang('Tax')</span>
			<div class="input-group w-50">
				<input type="text" name="tax" value="{{ old('tax') ?? '0' }}" data-tax="{{ old('tax') }}"
					   class="form-control bg-white text-dark OCTax" readonly>
				<div class="input-group-append">
					<div class="form-control">
						{{ $basic->currency_symbol }}
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class=" d-flex justify-content-end mt-2">
		<div class="col-md-3 d-flex justify-content-between">
			<span class="fw-bold">@lang('Insurance')</span>
			<div class="input-group w-50">
				<input type="text" name="insurance" value="{{ old('insurance') ?? '0' }}"
					   data-insurance="{{ old('insurance') }}"
					   class="form-control bg-white text-dark OCInsurance" readonly>
				<div class="input-group-append">
					<div class="form-control">
						{{ $basic->currency_symbol }}
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class=" d-flex justify-content-end mt-2">
		<div class="col-md-3 d-flex justify-content-between">
			<span class="fw-bold">@lang('Total Pay')</span>
			<div class="input-group w-50">
				<input type="number" name="total_pay" value="{{ old('total_pay') ?? '0' }}"
					   data-totalpay="{{ old('total_pay') }}"
					   class="form-control bg-white text-dark OCtotalPay" readonly>
				<div class="input-group-append">
					<div class="form-control">
						{{ $basic->currency_symbol }}
					</div>
				</div>
			</div>
		</div>
	</div>

	<input type="hidden" name="first_fiv" class="firstFiv" value="{{ old('first_fiv') ?? '0' }}">
	<input type="hidden" name="last_fiv" class="lastFiv" value="{{ old('last_fiv') ?? '0' }}">


	<button type="submit"
			class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">@lang('Save')</button>
</form>
