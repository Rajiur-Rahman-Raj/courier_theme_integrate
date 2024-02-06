<div class="row">
	<div class="col-md-3">
		<div class="form-group">
			<input placeholder="@lang('Receiver')" name="receiver" value="{{ $search['receiver'] ?? '' }}" type="text" class="form-control form-control-sm">
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<input placeholder="@lang('E-mail')" name="email" value="{{ $search['email'] ?? '' }}" type="text" class="form-control form-control-sm">
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<input placeholder="@lang('Transaction ID')" name="utr" value="{{ $search['utr'] ?? '' }}" type="text" class="form-control form-control-sm">
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<input placeholder="@lang('Min Amount')" name="min" value="{{ $search['min'] ?? '' }}" type="text" class="form-control form-control-sm">
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<input placeholder="@lang('Maximum Amount')" name="max" value="{{ $search['max'] ?? '' }}" type="text" class="form-control form-control-sm">
		</div>
	</div>

	<div class="col-sm-12 col-md-3 input-box">
		<div class="input-group flatpickr">
			<input type="date" placeholder="@lang('Transaction Date')"
				   class="form-control transaction_date" name="created_at" id="created_at"
				   value="{{ isset($search['created_at']) ? $search['created_at'] : '' }}" data-input/>
			<div class="input-group-append" readonly="">
				<div class="form-control">
					<a class="input-button cursor-pointer" title="clear" data-clear>
						<i class="fas fa-times"></i>
					</a>
				</div>
			</div>
		</div>
		<div class="invalid-feedback d-block">
			@error('created_at') @lang($message) @enderror
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group search-currency-dropdown">
			<select name="gateway_id" class="select2-single form-control form-control-sm select2">
				<option value="">@lang('All Gateway')</option>
				@foreach($gateways as $key => $gateway)
					<option value="{{ $gateway->id }}" {{ isset($search['gateway_id']) && $search['gateway_id'] == $gateway->id ? 'selected' : ''}}> {{ __($gateway->name) }} </option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group">
			<button type="submit" class="btn btn-primary btn-sm btn-block">@lang('Search')</button>
		</div>
	</div>
</div>
