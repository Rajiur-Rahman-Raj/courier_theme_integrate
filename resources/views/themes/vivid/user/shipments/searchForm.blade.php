
<div class="row g-3 align-items-end">
	<div class="input-box col-lg-2">
		<input placeholder="@lang('shipment id')" name="shipment_id"
			   value="{{ old('name',request()->shipment_id) }}" type="text"
			   class="form-control form-control-sm">
	</div>

	<div class="input-box col-lg-2">
		<select class="form-select"
				name="shipment_type"
				id="shipment_type">
			<option value="">@lang('All Shipments')</option>
			<option
				value="drop_off" {{ isset($search['shipment_type']) && $search['shipment_type'] == 'drop_off' ? 'selected' : '' }}>@lang('Drop Off')</option>
			<option
				value="pickup" {{ isset($search['shipment_type']) && $search['shipment_type'] == 'pickup' ? 'selected' : '' }}>@lang('Pickup')</option>
			@if($type ?? '' == 'operator-country')
				<option value="condition" {{ isset($search['shipment_type']) && $search['shipment_type'] == 'condition' ? 'selected' : '' }}>@lang('Condition')</option>
			@endif
		</select>
	</div>

	<div class="input-box col-lg-2">
		<input placeholder="@lang('sender branch')" name="sender_branch"
			   value="{{ old('sender_branch',request()->sender_branch) }}"
			   type="text" class="form-control form-control-sm">
	</div>

	<div class="input-box col-lg-2">
		<input placeholder="@lang('receiver branch')" name="receiver_branch"
			   value="{{ old('receiver_branch',request()->receiver_branch) }}"
			   type="text" class="form-control form-control-sm">
	</div>

	<div class="flatpickr input-box col-lg-2">
		<div class="input-group">
			<input type="date" placeholder="@lang('shipment date')" class="form-control shipment_date"
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

	<div class="flatpickr input-box col-lg-2">
		<div class="input-group">
			<input type="date" placeholder="@lang('delivery date')" class="form-control delivery_date"
				   name="delivery_date"
				   value="{{ old('delivery_date',request()->delivery_date) }}" data-input>
			<div class="input-group-append" readonly="">
				<div class="form-control">
					<a class="input-button cursor-pointer" title="clear" data-clear>
						<i class="fas fa-times"></i>
					</a>
				</div>
			</div>
			<div class="invalid-feedback d-block">
				@error('delivery_date') @lang($message) @enderror
			</div>
		</div>
	</div>

	<div class="input-box col-lg-12 mt-4">
		<button class="cmn_btn w-100" type="submit"><i
				class="fal fa-search"></i> @lang('Search') </button>
	</div>
</div>

