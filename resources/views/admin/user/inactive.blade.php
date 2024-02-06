@extends('admin.layouts.master')
@section('page_title', __('Inactive User List'))

@push('extra_styles')
	<link href="{{ asset('assets/dashboard/css/flatpickr.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1>@lang('Inactive User List')</h1>
			<div class="section-header-breadcrumb">
				<div class="breadcrumb-item active">
					<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
				</div>
				<div class="breadcrumb-item">@lang('Inactive User List')</div>
			</div>
		</div>

		<div class="row mb-3">
			<div class="container-fluid" id="container-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<div class="card mb-4 card-primary shadow-sm">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
								<h6 class="m-0 font-weight-bold text-primary">@lang('Search')</h6>
							</div>
							<div class="card-body">
								<form action="{{ route('inactive.user.search') }}" method="get">
									@include('admin.user.searchForm')
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="card mb- card-primary shadow">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
								<h6 class="m-0 font-weight-bold text-primary">@lang('User List')</h6>
								<a href="{{ route('send.mail.user') }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-envelope"></i> @lang('Send Mail to All')</a>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-striped table-hover align-items-center table-borderless">
										<thead class="thead-light">
										<tr>
											<th>@lang('SL')</th>
											<th>@lang('Name')</th>
											<th>@lang('Phone')</th>
											<th>@lang('Email')</th>
											<th>@lang('Join date')</th>
											<th>@lang('Last login')</th>
											<th>@lang('Action')</th>
										</tr>
										</thead>
										<tbody>
										@forelse($users as $key => $value)
											<tr>
												<td data-label="SL">
													{{loopIndex($users) + $key}}
												</td>

												<td data-label="@lang('Name')">
													<div class="d-lg-flex d-block align-items-center ">
														<div class="mr-3"><img src="{{ $value->profilePicture() }}" alt="user"
																			   class="rounded-circle" width="35" data-toggle="tooltip" title="" data-original-title="{{$value->name}}">
														</div>
														<div class="d-inline-flex d-lg-block align-items-center">
															<p class="text-dark mb-0 font-16 font-weight-medium">{{$value->name}}</p>
															<span class="text-muted font-14 ml-1">{{ '@'.$value->username}}</span>
														</div>
													</div>
												</td>

												<td data-label="@lang('Phone')">{{ __(optional($value->profile)->phone ?? __('N/A')) }}</td>
												<td data-label="@lang('Email')">{{ __($value->email) }}</td>
												<td data-label="@lang('Join date')">{{ __(date('d/m/Y - H:i',strtotime($value->created_at))) }}</td>
												<td data-label="@lang('Last login')">{{ (optional($value->profile)->last_login_at) ? __(date('d/m/Y - H:i',strtotime($value->profile->last_login_at))) : __('N/A') }}</td>
												<td data-label="@lang('Action')">
													<a href="{{ route('user.edit',$value) }}" class="btn btn-sm btn-outline-primary rounded-circle mb-1" data-toggle="tooltip"
													   data-original-title="@lang('Edit')"><i class="fas fa-user-edit"></i> </a>
													<a href="{{ route('send.mail.user',$value) }}" class="btn btn-sm btn-outline-warning rounded-circle mb-1" data-toggle="tooltip"
													   data-original-title="@lang('Send Mail')"><i class="fas fa-envelope"></i> @lang('Send mail')</a>
													<a href="{{ route('user.asLogin',$value) }}" class="btn btn-sm btn-outline-success rounded-circle mb-1" data-toggle="tooltip"
													   data-original-title="@lang('Login As User')"><i class="fas fa-sign-in-alt"></i> @lang('Login')</a>
												</td>
											</tr>
										@empty
											<tr>
												<td colspan="100%" class="text-center">
													<img class="not-found-img" src="{{ asset('assets/dashboard/images/empty-state.png') }}" alt="">
													<p class="text-center no-data-found-text">@lang('No In-active User Found')</p>
												</td>
											</tr>
										@endforelse
										</tbody>
									</table>
								</div>
								<div class="card-footer">{{ $users->links() }}</div>
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
