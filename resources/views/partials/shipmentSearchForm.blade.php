<form action="" method="get">
	<div class="row">
		<div class="col-md-2">
			<div class="form-group">
				<label for="shipment_id" class="custom-text">@lang('Shipment id')</label>
				<input placeholder="@lang('shipment id')" name="shipment_id"
					   value="{{ old('name',request()->shipment_id) }}" type="text"
					   class="form-control form-control-sm">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group search-currency-dropdown">
				<label for="shipment_type" class="custom-text">@lang('Shipment Type')</label>
				<select name="shipment_type" class="form-control form-control-sm">
					<option value="">@lang('All Shipments')</option>
					<option
						value="drop_off" {{ isset($search['shipment_type']) && $search['shipment_type'] == 'drop_off' ? 'selected' : '' }}>@lang('Drop Off')</option>
					<option
						value="pickup" {{ isset($search['shipment_type']) && $search['shipment_type'] == 'pickup' ? 'selected' : '' }}>@lang('Pickup')</option>
					@if($type ?? '' == 'operator-country')
						<option
							value="condition" {{ isset($search['shipment_type']) && $search['shipment_type'] == 'condition' ? 'selected' : '' }}>@lang('Condition')</option>
					@endif

				</select>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label for="sender_branch" class="custom-text">@lang('Sender Branch')</label>
				<input placeholder="@lang('sender branch')" name="sender_branch"
					   value="{{ old('sender_branch',request()->sender_branch) }}"
					   type="text" class="form-control form-control-sm">
			</div>
		</div>

		<div class="col-md-2">
			<div class="form-group">
				<label for="receiver_branch" class="custom-text">@lang('Receiver Branch')</label>
				<input placeholder="@lang('receiver branch')" name="receiver_branch"
					   value="{{ old('receiver_branch',request()->receiver_branch) }}"
					   type="text" class="form-control form-control-sm">
			</div>
		</div>

		<div class="col-md-2">
			<label for="shipment_date" class="custom-text"> @lang('Shipment Date') </label>
			<div class="flatpickr">
				<div class="input-group">
					<input type="date" placeholder="@lang('Select date')" class="form-control shipment_date"
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

		<div class="col-md-2">
			<label for="shipment_date" class="custom-text"> @lang('Delivery Date') </label>
			<div class="flatpickr">
				<div class="input-group">
					<input type="date" placeholder="@lang('Select date')" class="form-control delivery_date"
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
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<button type="submit"
						class="btn btn-primary btn-sm btn-block"><i
						class="fas fa-search"></i> @lang('Search')</button>
			</div>
		</div>
	</div>

</form>

