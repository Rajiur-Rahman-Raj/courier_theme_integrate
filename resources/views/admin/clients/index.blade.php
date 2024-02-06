@extends('admin.layouts.master')
@section('page_title',__('Customers'))

@push('extra_styles')
	<link href="{{ asset('assets/dashboard/css/flatpickr.min.css') }}" rel="stylesheet">
@endpush

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Customers')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Customers')</div>
				</div>
			</div>

			<div class="row mb-3">
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
													<input placeholder="@lang('Customer Name')" name="name"
														   value="{{ old('name',request()->name) }}"
														   type="text"
														   class="form-control form-control-sm">
												</div>
											</div>

											<div class="col-md-2">
												<div class="form-group">
													<input placeholder="@lang('Branch Name')" name="branch"
														   value="{{ old('branch',request()->branch) }}"
														   type="text" class="form-control form-control-sm">
												</div>
											</div>

											<div class="col-sm-12 col-md-2 input-box">
												<div class="input-group flatpickr">
													<input type="date" placeholder="@lang('Join Date')"
														   class="form-control transaction_date" name="created_at"
														   id="created_at"
														   value="{{ old('created_at', request()->created_at) }}"
														   data-input/>
													<div class="input-group-append" readonly="">
														<div class="form-control">
															<a class="input-button cursor-pointer" title="clear"
															   data-clear>
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
													<select name="client_type"
															class="form-control form-control-sm select2">
														<option value="all">@lang('All Types')</option>
														<option
															value="1" {{  request()->client_type == 1 ? 'selected' : '' }}>@lang('Sender/Customer')</option>
														<option
															value="2" {{  request()->client_type == 2 ? 'selected' : '' }}>@lang('Receiver')</option>
													</select>
												</div>
											</div>

											<div class="col-md-2">
												<div class="form-group search-currency-dropdown">
													<select name="status" class="form-control form-control-sm select2">
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
													<button type="submit" class="btn btn-primary btn-sm btn-block"><i
															class="fas fa-search"></i> @lang('Search')</button>
												</div>
											</div>
										</div>

									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="card mb-4 card-primary shadow">
								<div
									class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
									<h6 class="m-0 font-weight-bold text-primary">@lang('Customer List')</h6>
									@if(adminAccessRoute(config('permissionList.Manage_Customers.Customer_List.permission.add')))
										@if($authenticateUser->branch != null || $authenticateUser->role_id == null)
											<a href="{{ route('createClient') }}"
											   class="btn btn-sm btn-outline-primary"><i
													class="fas fa-plus-circle"></i> @lang('Create New Customer')</a>
										@endif
									@endif
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table
											class="table table-striped table-hover align-items-center table-borderless">
											<thead class="thead-light">
											<tr>
												<th>@lang('Name')</th>
												<th>@lang('Branch')</th>

												<th>@lang('Join date')</th>
												<th>@lang('User Type')</th>
												<th>@lang('Status')</th>
												@if(adminAccessRoute(array_merge(config('permissionList.Manage_Customers.Customer_List.permission.edit'), config('permissionList.Manage_Customers.Customer_List.permission.delete'), config('permissionList.Manage_Customers.Customer_List.permission.show_profile'), config('permissionList.Manage_Customers.Customer_List.permission.login_as'))))
													<th>@lang('Action')</th>
												@endif
											</tr>
											</thead>
											<tbody>

											@forelse($clients as $key => $client)
												<tr>
													<td data-label="@lang('Name')">
														<div
															class="d-lg-flex d-block align-items-center branch-list-img">
															<div class="mr-3"><img
																	src="{{ getFile(optional($client->profile)->driver, optional($client->profile)->profile_picture) }}"
																	alt="user" class="rounded-circle"
																	width="40" height="40"
																	data-toggle="tooltip"
																	title=""
																	data-original-title="{{$client->name}}">
															</div>

															<div
																class="d-inline-flex d-lg-block align-items-center ms-2">
																<p class="text-dark mb-0 font-16 font-weight-medium">
																	{{$client->name}}</p>
																<span
																	class="text-dark font-weight-bold font-14 ml-1">{{ '@'.$client->username}}</span>
															</div>
														</div>
													</td>
													@if(optional($client->profile)->branch)
														<td data-label="@lang('Branch')">
															<a href="{{ route('showBranchProfile', optional(optional($client->profile)->branch)->id) }}"
															   target="_blank">@lang(optional(optional($client->profile)->branch)->branch_name)</a>
														</td>
													@endif

													<td data-label="@lang('Join date')">{{ __(date('d M,Y - H:i a',strtotime($client->created_at))) }}</td>

													<td data-label="@lang('Status')">
														@if($client->user_type == 1)

															<span class="badge badge-light">
            															<i class="fa fa-circle text-primary"></i>
																		@lang('Sender')
																	</span>
														@else
															<span class="badge badge-light">
            															<i class="fa fa-circle text-warning"></i>
																		@lang('Receiver')
																	</span>
														@endif
													</td>

													<td data-label="@lang('Status')">
														@if($client->status)
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
													@if(adminAccessRoute(array_merge(config('permissionList.Manage_Customers.Customer_List.permission.edit'), config('permissionList.Manage_Customers.Customer_List.permission.delete'), config('permissionList.Manage_Customers.Customer_List.permission.show_profile'), config('permissionList.Manage_Customers.Customer_List.permission.login_as'))))
														<td data-label="@lang('Action')">
															<a href="{{ route('user-profile',$client) }}"
															   class="btn btn-outline-primary btn-sm rounded-circle"
															   data-toggle="tooltip" data-placement="top"
															   title="@lang('View Profile')"><i
																	class="fas fa-eye"></i>
															</a>

															<a href="{{ route('send.mail.user',$client) }}"
															   class="btn btn-outline-warning btn-sm rounded-circle"
															   data-toggle="tooltip" data-placement="top"
															   title="@lang('Send Mail')"><i
																	class="fas fa-envelope"></i>
															</a>

															<a href="{{ route('user.asLogin',$client) }}"
															   class="btn btn-outline-success btn-sm rounded-circle"
															   data-toggle="tooltip" data-placement="top"
															   title="@lang('Login As User')"><i
																	class="fas fa-sign-in-alt"></i>
															</a>
														</td>
													@endif
												</tr>
											@empty
												<tr>
													<td colspan="100%" class="text-center">
														<img class="not-found-img"
															 src="{{ asset('assets/dashboard/images/empty-state.png') }}"
															 alt="">
														<p class="text-center no-data-found-text">@lang('No Customer Found')</p>
													</td>
												</tr>
											@endforelse

											</tbody>
										</table>
									</div>
									<div class="card-footer">{{ $clients->links() }}</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</section>
	</div>
@endsection

@push('extra_scripts')
	<script src="{{ asset('assets/dashboard/js/flatpickr.js') }}"></script>
@endpush

@section('scripts')
	<script>
		'use strict'
		$(document).ready(function () {
			$(".flatpickr").flatpickr({
				wrap: true,
				altInput: true,
				dateFormat: "Y-m-d H:i",
				maxDate: new Date(new Date().getTime() + 24 * 60 * 60 * 1000) // today + 1 day
			});
		})
	</script>
@endsection
