@extends('admin.layouts.master')

@section('page_title')
	@lang('Edit Driver')
@endsection

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang("Edit Driver")</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active"><a href="{{ route('admin.home') }}">@lang("Dashboard")</a></div>
					<div class="breadcrumb-item"><a href="{{route('branchDriverList')}}">@lang("Edit Driver")</a></div>
					<div class="breadcrumb-item">@lang("Edit Driver")</div>
				</div>
			</div>
		</section>

		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-12 col-lg-12">
					<div class="card mb-4 card-primary shadow-sm">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h5>@lang("Edit Driver")</h5>

							<a href="{{route('branchDriverList')}}" class="btn btn-sm  btn-primary mr-2">
								<span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
							</a>
						</div>

						<div class="card-body">
							<form method="post" action="{{ route('branchDriverUpdate', $singleBranchDriverInfo->id) }}"
								  class="mt-4" enctype="multipart/form-data">
								@csrf
								@if($authenticateUser->role_id == null)
									<div class="row mb-3">
										<div class="col-sm-12 col-md-12 mb-3">
											<label for="branch_id"> @lang('Select Branch')</label>
											<select name="branch_id"
													class="form-control @error('branch_id') is-invalid @enderror select2">
												@foreach($allBranches as $branch)
													<option value="{{ $branch->id }}" {{ $branch->id == $singleBranchDriverInfo->branch_id ? 'selected' : '' }}>@lang($branch->branch_name)</option>
												@endforeach
											</select>

											<div class="invalid-feedback">
												@error('branch_id') @lang($message) @enderror
											</div>
											<div class="valid-feedback"></div>
										</div>
									</div>
								@else
									<input type="hidden" name="branch_id" class="form-control"
										   value="{{ $allBranches[0]->id }}">
								@endif

								<div class="row mb-3">
									<div class="col-sm-12 col-md-12 mb-3">
										<label for="role_id"> @lang('Select Role') </label>
										<select name="role_id" class="form-control @error('role_id') is-invalid @enderror select2 selectRole">
											<option value="" disabled selected>@lang('Select Role')</option>
											@foreach($allRoles as $role)
												<option value="{{ $role->id }}" {{ $singleBranchDriverInfo->role_id == $role->id ? 'selected' : '' }}>@lang($role->name)</option>
											@endforeach
										</select>

										<div class="invalid-feedback">
											@error('role_id') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>
								</div>

								<div class="row mb-3">
									<div class="col-sm-12 col-md-12 mb-3">
										<label for="branch_driver_id"> @lang('Select Driver') </label>
										<select name="branch_driver_id" class="form-control @error('branch_driver_id') is-invalid @enderror select2 branchDriver"  id="branchDriver">
											@foreach($allDrivers as $driver)
												<option value="{{ $driver->id }}" {{ $singleBranchDriverInfo->admin_id == $driver->id ? 'selected' : '' }}>@lang($driver->name)</option>
											@endforeach
										</select>

										<div class="invalid-feedback">
											@error('branch_driver_id') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12 col-md-12 mb-3">
										<label for="email"> @lang('Email') </label>
										<input type="text" name="email"
											   class="form-control @error('email') is-invalid @enderror driverEmail"
											   value="{{ old('email', $singleBranchDriverInfo->email) }}">
										<div class="invalid-feedback">
											@error('email') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12 col-md-12 mb-3">
										<label for="phone"> @lang('phone') </label>
										<input type="text" name="phone"
											   class="form-control @error('phone') is-invalid @enderror driverPhone"
											   value="{{ old('phone', $singleBranchDriverInfo->phone) }}">
										<div class="invalid-feedback">
											@error('phone') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12 col-md-12 my-3">
										<div class="form-group ">
											<label for="address" class="font-weight-normal"> @lang('Address') </label>

											<textarea class="form-control @error('address') is-invalid @enderror"
													  name="address" rows="5"
													  value="{{ old('address', $singleBranchDriverInfo->address) }}">{{old('address', $singleBranchDriverInfo->address)}}</textarea>

											<div class="invalid-feedback">
												@error('address') @lang($message) @enderror
											</div>
											<div class="valid-feedback"></div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12 col-md-12 mb-3">
										<label for="national_id"> @lang('National Id') <span class="font-weight-bold"><sub>(@lang('optional'))</sub></span></span></label>
										<input type="text" name="national_id"
											   class="form-control @error('national_id') is-invalid @enderror"
											   value="{{ old('national_id', $singleBranchDriverInfo->national_id) }}">
										<div class="invalid-feedback">
											@error('national_id') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12 col-md-3">
										<div class="form-group mb-4">
											<label class="col-form-label font-weight-normal">@lang("Driver Photo") <span class="font-weight-bold"><sub>(@lang('optional'))</sub></span></label>
											<div id="image-preview" class="image-preview"
												 style="background-image: url({{ getFile($singleBranchDriverInfo->driver, $singleBranchDriverInfo->image)}}">
												<label for="image-upload"
													   id="image-label">@lang('Choose File')</label>
												<input type="file" name="image" class=""
													   id="image-upload"/>
											</div>
											@error('image')
											<span class="text-danger">{{ $message }}</span>
											@enderror
										</div>
									</div>

									<div class="col-md-5 form-group">
										<label class="font-weight-normal">@lang('Status')</label>
										<div class="selectgroup w-100">
											<label class="selectgroup-item">
												<input type="radio" name="status" value="0"
													   class="selectgroup-input" {{ old('status', $singleBranchDriverInfo->status) == 0 ? 'checked' : ''}}>
												<span class="selectgroup-button">@lang('OFF')</span>
											</label>
											<label class="selectgroup-item">
												<input type="radio" name="status" value="1"
													   class="selectgroup-input" {{ old('status', $singleBranchDriverInfo->status) == 1 ? 'checked' : ''}}>
												<span class="selectgroup-button">@lang('ON')</span>
											</label>
										</div>
									</div>
								</div>

								<button type="submit"
										class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">@lang('Save')</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endsection


		@push('extra_scripts')
			<script src="{{ asset('assets/dashboard/js/jquery.uploadPreview.min.js') }}"></script>
		@endpush

		@section('scripts')
			<script type="text/javascript">
				'use strict';
				$(document).ready(function () {
					$.uploadPreview({
						input_field: "#image-upload",
						preview_box: "#image-preview",
						label_field: "#image-label",
						label_default: "Choose File",
						label_selected: "Change File",
						no_label: false
					});

					$('.selectRole').on('change', function (){
						let selectedValue = $(this).val();
						getSeletedRoleUser(selectedValue);
					})

					function getSeletedRoleUser(value) {

						$.ajaxSetup({
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							}
						});

						$.ajax({
							url: '{{ route('getRoleUser') }}',
							method: 'POST',
							data: {
								id: value,
							},
							success: function (response) {
								$('#branchDriver').empty();
								let responseData = response;
								responseData.forEach(res => {
									$('#branchDriver').append(`<option value="${res.id}">${res.name}</option>`)
								})
								$('#branchDriver').append(`<option value="" selected disabled>@lang('Select Driver')</option>`)
							},
							error: function (xhr, status, error) {
								console.log(error)
							}
						});
					}


					$('.branchDriver').on('change', function (){
						let selectedValue = $(this).val();
						getSeletedRoleUserInfo(selectedValue);
					})

					function getSeletedRoleUserInfo(value) {

						$.ajaxSetup({
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							}
						});

						$.ajax({
							url: '{{ route('getRoleUserInfo') }}',
							method: 'POST',
							data: {
								id: value,
							},
							success: function (response) {
								$('.driverEmail').val(response.email);
								$('.driverPhone').val(response.phone);
							},
							error: function (xhr, status, error) {
								console.log(error)
							}
						});
					}
				});
			</script>
@endsection
