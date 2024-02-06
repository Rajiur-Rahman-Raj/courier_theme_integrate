<div class="row g-3 align-items-end">
	<div class="input-box col-lg-3">
		<input placeholder="@lang('Transaction ID')" name="utr" value="{{ old('utr', request()->utr) }}" type="text"
			   class="form-control form-control-sm">
	</div>
	<div class="input-box col-lg-2">
		<input placeholder="@lang('Min Amount')" name="min" value="{{ old('min', request()->min) }}" type="text"
			   class="form-control form-control-sm">
	</div>
	<div class="input-box col-lg-2">
		<input placeholder="@lang('Maximum Amount')" name="max" value="{{ old('max', request()->max) }}" type="text"
			   class="form-control form-control-sm">
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
	<div class="input-box col-lg-2">
		<button type="submit" class="cmn_btn w-100"><i class="fal fa-search"></i> @lang('Filter')</button>
	</div>
</div>
