@extends('admin.layouts.master')
@section('page_title', __('Available Roles'))
@push('extra_styles')
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Available Roles')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Available Roles')</div>
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
													<div class="col-md-4">
														<div class="form-group">
															<input placeholder="@lang('Role Name')" name="name"
																   value="{{ old('name',request()->name) }}" type="text"
																   class="form-control form-control-sm">
														</div>
													</div>

													<div class="col-md-4">
														<div class="form-group search-currency-dropdown">
															<select name="status"
																	class="form-control form-control-sm select2">
																<option value="all">@lang('All Status')</option>
																<option
																	value="active" {{  request()->status == 'active' ? 'selected' : '' }}>@lang('Active')</option>
																<option
																	value="deactive" {{  request()->status == 'deactive' ? 'selected' : '' }}>@lang('Deactive')</option>
															</select>
														</div>
													</div>

													<div class="col-md-4">
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
											<h6 class="m-0 font-weight-bold text-primary">@lang('Role List')</h6>
											@if(adminAccessRoute(config('permissionList.Role_And_Permissions.Available_Roles.permission.add')))
												<a href="{{route('createRole')}}"
												   class="btn btn-sm btn-outline-primary add"><i
														class="fas fa-plus-circle"></i> @lang('Create New Role')</a>
											@endif
										</div>
										<div class="card-body">
											<div class="table-responsive">
												<table
													class="table table-striped table-hover align-items-center table-flush"
													id="data-table">
													<thead class="thead-light">
													<tr>
														<th>@lang('SL.')</th>
														<th>@lang('Name')</th>
														<th>@lang('Status')</th>
														@if(adminAccessRoute(array_merge(config('permissionList.Role_And_Permissions.Available_Roles.permission.edit'), config('permissionList.Role_And_Permissions.Available_Roles.permission.delete'))))
															<th>@lang('Action')</th>
														@endif
													</tr>
													</thead>

													<tbody>
													@forelse($roles as $key => $value)
														<tr>
															<td data-label="@lang('SL.')">{{++$key }}</td>
															<td data-label="@lang('Name')">{{$value->name }}</td>

															<td data-label="@lang('Status')"
																class="font-weight-bold text-dark">
																@if($value->status == 1)
																	<span class="badge badge-light">
																		<i class="fa fa-circle text-success font-12"></i>@lang('Active')
																	</span>
																@else
																	<span class="badge badge-light">
																		<i class="fa fa-circle text-danger font-12"></i>@lang('Deactive')
																	</span>
																@endif
															</td>
															@if(adminAccessRoute(array_merge(config('permissionList.Role_And_Permissions.Available_Roles.permission.edit'), config('permissionList.Role_And_Permissions.Available_Roles.permission.delete'))))
																<td data-label="@lang('Action')">
																	@if(adminAccessRoute(config('permissionList.Role_And_Permissions.Available_Roles.permission.edit')))
																		<a href="{{ route('editRole', $value->id) }}"
																		   class="btn btn-outline-primary rounded-circle btn-sm"
																		   data-toggle="tooltip"
																		   data-original-title="@lang('Edit')"><i class="fa fa-edit"
																									aria-hidden="true"></i>
																		</a>
																	@endif
																	@if(adminAccessRoute(config('permissionList.Role_And_Permissions.Available_Roles.permission.delete')))
																		<button
																			class="btn btn-sm btn-outline-danger rounded-circle notiflix-confirm"
																			data-target="#delete-modal"
																			data-toggle="modal"
																			data-route="{{route('deleteRole',$value->id)}}">
																			<i class="fas fa-trash" data-toggle="tooltip"
																			   data-original-title="@lang('Delete')"></i></button>
																	@endif
																</td>
															@endif
														</tr>
													@empty
														<tr>
															<td colspan="100%" class="text-center">
																<img class="not-found-img" src="{{ asset('assets/dashboard/images/empty-state.png') }}" alt="">
																<p class="text-center no-data-found-text">@lang('No Roles Found')</p>
															</td>
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

	{{--	Delete Modal--}}
	<div id="delete-modal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Delete Confirmation')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" class="deleteRoute">
					@csrf
					@method('delete')
					<div class="modal-body">
						<p>@lang('Are you sure want to delete this roles')</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
						<button type="submit" class="btn btn-primary">@lang('Confirm')</button>
					</div>
				</form>
			</div>
		</div>
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

@push('extra_scripts')
	<script>
		'use strict'
		$(document).on('click', '.notiflix-confirm', function () {
			var route = $(this).data('route');
			$('.deleteRoute').attr('action', route)
		});
	</script>
@endpush

