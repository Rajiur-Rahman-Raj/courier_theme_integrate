@extends('admin.layouts.master')
@section('page_title', __('Branch Staff List'))
@push('extra_styles')
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Branch Staff List')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Branch Staff List')</div>
				</div>
			</div>

			<div class="section-body">
				<div class="row mt-sm-4">
					<div class="col-12 col-md-12 col-lg-12">
						<div class="container-fluid" id="container-wrapper">
							<div class="row">
								<div class="col-lg-12">
									<div class="card mb-4 card-primary shadow-sm">
										<div
											class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
											<h6 class="m-0 font-weight-bold text-primary">@lang('Search')</h6>
										</div>
										<div class="card-body">
											<form action="" method="get">
												<div class="row">
													<div class="col-md-2">
														<div class="form-group">
															<input placeholder="@lang('Branch')" name="branch"
																   value="{{ old('branch',request()->branch) }}"
																   type="text"
																   class="form-control form-control-sm">
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<input placeholder="@lang('Department')" name="department"
																   value="{{ old('department',request()->department) }}"
																   type="text"
																   class="form-control form-control-sm">
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<input placeholder="@lang('Phone')" name="phone"
																   value="{{ old('phone',request()->phone) }}"
																   type="text" class="form-control form-control-sm">
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
															<input placeholder="@lang('E-mail')" name="email"
																   value="{{ old('email',request()->email) }}"
																   type="text" class="form-control form-control-sm">
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group search-currency-dropdown">
															<select name="status" class="form-control form-control-sm">
																<option value="all">@lang('All Status')</option>
																<option
																	value="active" {{  request()->status == 'active' ? 'selected' : '' }}>@lang('Active')</option>
																<option
																	value="deactive" {{  request()->status == 'deactive' ? 'selected' : '' }}>@lang('Deactive')</option>
															</select>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<button type="submit"
																	class="btn btn-primary btn-sm btn-block"><i
																	class="fas fa-search"></i> @lang('Search')</button>
														</div>
													</div>
												</div>

											</form>
										</div>
									</div>
								</div>
							</div>
							<div class="row justify-content-md-center">
								<div class="col-lg-12">
									<div class="card mb-4 card-primary shadow">
										<div
											class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
											<h6 class="m-0 font-weight-bold text-primary">@lang('Branch Staff List')</h6>
										</div>
										<div class="card-body">
											<div class="table-responsive">
												<table
													class="table table-striped table-hover align-items-center table-flush"
													id="data-table">
													<thead class="thead-light">
													<tr>
														<th scope="col">@lang('Employee')</th>
														<th scope="col">@lang('Department')</th>
														<th scope="col">@lang('Phone')</th>
														<th scope="col">@lang('Address')</th>
														<th scope="col">@lang('Status')</th>
													</tr>
													</thead>

													<tbody>
													@forelse($branchStaffList as $key => $employee)
														<tr>
															<td data-label="@lang('Employee')">
																<a href="javascript:void(0)"
																   class="text-decoration-none">
																	<div
																		class="d-lg-flex d-block align-items-center branch-list-img">
																		<div class="mr-3"><img
																				src="{{getFile($employee->driver,$employee->image) }}"
																				alt="user" class="rounded-circle"
																				width="40" height="40"
																				data-toggle="tooltip"
																				title=""
																				data-original-title="{{ __(optional($employee->admin)->name) }}">
																		</div>
																		<div
																			class="d-inline-flex d-lg-block align-items-center ms-2">
																			<p class="text-dark mb-0 font-16 font-weight-medium">
																				{{ __(optional($employee->admin)->name) }}</p>
																			<span
																				class="text-dark font-weight-bold font-14 ml-1">{{$employee->email}}</span>
																		</div>
																	</div>
																</a>
															</td>

															<td data-label="@lang('Department')">
																@lang(optional($employee->department)->name)
															</td>

															<td data-label="@lang('Phone')">
																{{ $employee->phone }}
															</td>

															<td data-label="@lang('Address')">
																@lang($employee->address)
															</td>

															<td data-label="@lang('Status')"
																class="font-weight-bold text-dark">
																@if($employee->status == 1)
																	<span class="badge badge-light">
            															<i class="fa fa-circle text-success success font-12"></i> @lang('Active')
																	</span>
																@else
																	<span class="badge badge-light">
            															<i class="fa fa-circle text-danger success font-12"></i> @lang('Deactive')
																	</span>
																@endif
															</td>

														</tr>
													@empty
														<tr>
															<th colspan="100%"
																class="text-center">@lang('No data found')</th>
														</tr>
													@endforelse
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

@endsection

@section('scripts')
	@if ($errors->any())
		@php
			$collection = collect($errors->all());
			$errors = $collection->unique();
		@endphp
		<script>
			"use strict";
			@foreach ($errors as $error)
			Notiflix.Notify.failure("{{ trans($error) }}");
			@endforeach
		</script>
	@endif
@endsection
