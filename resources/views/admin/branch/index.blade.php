@extends('admin.layouts.master')
@section('page_title', __('All Branches'))
@push('extra_styles')
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Branches')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Branches')</div>
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
													<div class="col-md-3">
														<div class="form-group">
															<input placeholder="@lang('Branch name')" name="name"
																   value="{{ old('name',request()->name) }}" type="text"
																   class="form-control form-control-sm">
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<input placeholder="@lang('Phone')" name="phone"
																   value="{{ old('phone',request()->phone) }}"
																   type="text" class="form-control form-control-sm">
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<input placeholder="@lang('E-mail')" name="email"
																   value="{{ old('email',request()->email) }}"
																   type="text" class="form-control form-control-sm">
														</div>
													</div>

													<div class="col-md-3">
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


													<div class="col-md-12">
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
											<h6 class="m-0 font-weight-bold text-primary">@lang('Branch List')</h6>
											@if(adminAccessRoute(config('permissionList.Manage_Branch.Branch_List.permission.add')))
												<a href="{{route('createBranch')}}"
												   class="btn btn-sm btn-outline-primary add"><i
														class="fas fa-plus-circle"></i> @lang('Create Branch')</a>
											@endif
										</div>

										<div class="card-body">
											<div class="table-responsive">
												<table
													class="table table-striped table-hover align-items-center table-flush"
													id="data-table">
													<thead class="thead-light">
													<tr>
														<th scope="col">@lang('Branch Name')</th>
														<th scope="col">@lang('Phone')</th>
														<th scope="col">@lang('Address')</th>
														<th scope="col">@lang('Status')</th>
														@if(adminAccessRoute(array_merge(config('permissionList.Manage_Branch.Branch_List.permission.edit'), config('permissionList.Manage_Branch.Branch_List.permission.delete'), config('permissionList.Manage_Branch.Branch_List.permission.show_profile'))))
															<th scope="col">@lang('Action')</th>
														@endif
													</tr>
													</thead>

													<tbody>
													@forelse($allBranches as $key => $branch)
														<tr>
															<td data-label="@lang('Branch Name')">
																<a href="javascript:void(0)"
																   class="text-decoration-none">
																	<div
																		class="d-lg-flex d-block align-items-center branch-list-img">
																		<div class="mr-3"><img
																				src="{{getFile($branch->driver,$branch->image) }}"
																				alt="user" class="rounded-circle"
																				width="40" height="40"
																				data-toggle="tooltip"
																				title=""
																				data-original-title="{{ __($branch->branch_name) }}">
																		</div>
																		<div
																			class="d-inline-flex d-lg-block align-items-center ms-2">
																			<p class="text-dark mb-0 font-16 font-weight-medium">
																				{{ __($branch->branch_name) }}</p>
																			<span
																				class="text-dark font-weight-bold font-14 ml-1">{{$branch->email}}</span>
																		</div>
																	</div>
																</a>
															</td>

															<td data-label="@lang('Phone')">
																{{ $branch->phone }}
															</td>

															<td data-label="@lang('Address')">
																{{ $branch->address }}
															</td>

															<td data-label="@lang('Status')"
																class="font-weight-bold text-dark">
																@if($branch->status == 1)
																	<span class="badge badge-light">
            															<i class="fa fa-circle text-success"></i>
																		@lang('Active')
																	</span>
																@else
																	<span class="badge badge-light">
            															<i class="fa fa-circle text-danger"></i>
																		@lang('Deactive')
																	</span>
																@endif
															</td>
															@if(adminAccessRoute(array_merge(config('permissionList.Manage_Branch.Branch_List.permission.edit'), config('permissionList.Manage_Branch.Branch_List.permission.delete'), config('permissionList.Manage_Branch.Branch_List.permission.show_profile'))))
																<td data-label="@lang('Action')">
																	@if(adminAccessRoute(config('permissionList.Manage_Branch.Branch_List.permission.edit')))
																		<a href="{{ route('branchEdit', $branch->id) }}"
																		   class="btn btn-outline-primary rounded-circle btn-sm"
																		   data-toggle="tooltip" data-placement="top" title="@lang('Edit')"><i class="fa fa-edit"
																									aria-hidden="true"></i>
																		</a>
																	@endif

																	@if(adminAccessRoute(config('permissionList.Manage_Branch.Branch_List.permission.show_profile')))
																		<a href="{{ route('showBranchProfile', $branch->id) }}"
																		   class="btn btn-outline-primary rounded-circle btn-sm"
																		   data-toggle="tooltip" data-placement="top" title="@lang('Branch Profile')">
																			<i class="fas fa-user"></i>
																	@endif
																</td>
															@endif
														</tr>
													@empty
														<tr>
															<td colspan="100%" class="text-center">
																<img class="not-found-img"
																	 src="{{ asset('assets/dashboard/images/empty-state.png') }}"
																	 alt="">
																<p class="text-center no-data-found-text">@lang('No Branch Found')</p>
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
