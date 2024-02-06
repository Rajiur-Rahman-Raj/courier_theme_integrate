<div class="row">
	<div class="col-md-3">
        <div class="form-group">
            <input placeholder="@lang('Transaction ID')" name="trx_id"
				   value="{{ @request()->trx_id}}" type="text"
                   class="form-control form-control-sm">
        </div>
    </div>

	<div class="col-md-3">
		<div class="form-group">
			<input placeholder="@lang('Remark')" name="remark"
				   value="{{@request()->remark}}" type="text"
				   class="form-control form-control-sm">
		</div>
	</div>

	<div class="col-sm-12 col-md-3 input-box">
		<div class="input-group flatpickr">
			<input type="date" placeholder="@lang('Select From Date')"
				   class="form-control from_date" name="from_date"
				   value="{{ old('from_date',request()->from_date) }}" data-input/>
			<div class="input-group-append" readonly="">
				<div class="form-control">
					<a class="input-button cursor-pointer" title="clear" data-clear>
						<i class="fas fa-times"></i>
					</a>
				</div>
			</div>
		</div>
		<div class="invalid-feedback d-block">
			@error('from_date') @lang($message) @enderror
		</div>
	</div>

	<div class="flatpickr input-box col-lg-3">
		<div class="input-group">
			<input type="date" placeholder="@lang('Select To Date')"
				   class="form-control to_date" name="to_date"
				   value="{{ old('to_date',request()->to_date) }}" data-input disabled="true"/>
			<div class="input-group-append" readonly="">
				<div class="form-control">
					<a class="input-button cursor-pointer" title="clear" data-clear>
						<i class="fas fa-times"></i>
					</a>
				</div>
			</div>
		</div>
		<div class="invalid-feedback d-block">
			@error('to_date') @lang($message) @enderror
		</div>
	</div>

    <div class="col-md-12">
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-sm btn-block w-100">@lang('Search')</button>
        </div>
    </div>
</div>
