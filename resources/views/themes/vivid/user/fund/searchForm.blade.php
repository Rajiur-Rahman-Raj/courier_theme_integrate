<div class="row">
	<div class="col-md-2">
		<div class="form-group input-box">
			<input placeholder="@lang('Transaction ID')" name="utr" value="{{ old('utr', request()->utr) }}" type="text"
				   class="form-control form-control-sm">
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group input-box">
			<input placeholder="@lang('Min Amount')" name="min" value="{{ old('min', request()->min) }}" type="text"
				   class="form-control form-control-sm">
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group input-box">
			<input placeholder="@lang('Maximum Amount')" name="max" value="{{ old('max', request()->max) }}" type="text"
				   class="form-control form-control-sm">
		</div>
	</div>

	<div class="flatpickr input-box col-lg-3">
		<div class="input-box input-group">
			<input type="date" placeholder="@lang('Select Date')"
				   class="form-control from_date" name="created_at"
				   value="{{ old('created_at',request()->created_at) }}" data-input/>
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
		<div class="form-group input-box">
			<button type="submit" class="btn cmn_btn btn-sm btn-block w-100"> @lang('Search') </button>
		</div>
	</div>
</div>
