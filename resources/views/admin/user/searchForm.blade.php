<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<input placeholder="@lang('Name')" name="name" value="{{ isset($search['name']) ? $search['name'] : '' }}" type="text" class="form-control form-control-sm">
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<input placeholder="@lang('Phone')" name="phone" value="{{ isset($search['phone']) ? $search['phone'] : '' }}" type="text" class="form-control form-control-sm">
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<input placeholder="@lang('E-mail')" name="email" value="{{ isset($search['email']) ? $search['email'] : '' }}" type="text" class="form-control form-control-sm">
		</div>
	</div>

	<div class="col-sm-12 col-md-2 input-box">
		<div class="input-group flatpickr">
			<input type="date" placeholder="@lang('Join Date')"
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


	<div class="col-md-2">
		<div class="form-group search-currency-dropdown">
			<select name="status" class="form-control form-control-sm">
				<option value="">@lang('All Status')</option>
				<option value="active" {{ isset($search['status']) && $search['status'] == 'active' ? 'selected' : '' }}>@lang('Active')</option>
				<option value="inactive" {{ isset($search['status']) && $search['status'] == 'inactive' ? 'selected' : '' }}>@lang('Inactive')</option>
			</select>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<button type="submit" class="btn btn-primary btn-sm btn-block"><i
				class="fas fa-search"></i> @lang('Search')</button>
		</div>
	</div>
</div>
