@extends('admin.layouts.master')
@section('page_title', __('Payout Transactions'))

@push('extra_styles')
	<link href="{{ asset('assets/dashboard/css/flatpickr.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1>@lang('Payout Transactions')</h1>
			<div class="section-header-breadcrumb">
				<div class="breadcrumb-item active">
					<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
				</div>
				<div class="breadcrumb-item">@lang('Payout Transactions')</div>
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
								@if(isset($userId))
									<form action="{{ route('admin.user.payout.search',$userId) }}" method="get">
										@include('admin.payout.searchForm')
									</form>
								@else
									<form action="{{ route('admin.payout.search') }}" method="get">
										@include('admin.payout.searchForm')
									</form>
								@endif
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="card mb-4 card-primary shadow">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
								<h6 class="m-0 font-weight-bold text-primary">@lang('Transaction List')</h6>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-striped table-hover align-items-center table-borderless">
										<thead class="thead-light">
										<tr>
											<th>@lang('SL')</th>
											<th>@lang('Sender')</th>

											<th>@lang('Amount')</th>
											<th>@lang('Transaction ID')</th>
											<th>@lang('Status')</th>
											<th>@lang('Transaction At')</th>
											<th>@lang('Action')</th>
										</tr>
										</thead>
										<tbody>
										@forelse($payouts as $key => $value)
											<tr>
												<td data-label="@lang('SL')">{{loopIndex($payouts) + $key}}</td>
												<td data-label="@lang('Sender')">

													<a href="{{ route('user.edit', $value->user_id)}}"
													   class="text-decoration-none">
														<div class="d-lg-flex d-block align-items-center ">
															<div class="mr-3"><img
																	src="{{ getFile(optional($value->user->profile)->driver,optional($value->user->profile)->profile_picture) }}"
																	alt="user"
																	class="rounded-circle" width="45" height="45"
																	data-toggle="tooltip"
																	data-original-title="{{optional($value->user)->name?? __('N/A')}}">
															</div>
															<div
																class="d-inline-flex d-lg-block align-items-center">
																<p class="text-dark mb-0 font-16 font-weight-medium">{{Str::limit(optional($value->user)->name?? __('N/A'),20)}}</p>
																<span
																	class="text-muted font-14 ml-1">{{ '@'.optional($value->user)->username?? __('N/A')}}</span>
															</div>
														</div>
													</a>

												</td>

												<td data-label="@lang('Amount')">{{ getAmount($value->amount).' '.__(config('basic.base_currency')) }}</td>

												<td data-label="@lang('Transaction ID')">{{ __( $value->utr) }}</td>
												<td data-label="@lang('Status')">
													@if($value->status == 0)
														<span class="badge badge-light">
															<i class="fa fa-circle text-warning font-12"></i> @lang('Pending')
														</span>
													@elseif($value->status == 1)
														<span class="badge badge-light">
															<i class="fa fa-circle text-info font-12"></i> @lang('Generated')
														</span>
													@elseif($value->status == 2)
														<span class="badge badge-light">
															<i class="fa fa-circle text-success font-12"></i> @lang('Payment Done')
														</span>
													@elseif($value->status == 5)
														<span class="badge badge-light">
															<i class="fa fa-circle text-danger font-12"></i> @lang('Canceled')
														</span>
													@endif
												</td>
												<td data-label="@lang('Transaction At')"> {{ dateTime($value->created_at)}} </td>
												<td data-label="@lang('Action')">
													<a href="{{ route('payout.details',[$value->utr]) }}" class="btn btn-sm btn-outline-info confirmButton"> <i class="fas fa-eye"></i> @lang('View')</a>
												</td>
											</tr>
										@empty
											<tr>
												<td colspan="100%" class="text-center">
													<img class="not-found-img" src="{{ asset('assets/dashboard/images/empty-state.png') }}" alt="">
													<p class="text-center no-data-found-text">@lang('No Payout Found')</p>
												</td>
											</tr>
										@endforelse
										</tbody>
									</table>
								</div>
								<div class="pagination_area">
									<nav aria-label="Page navigation example">
										<ul class="pagination justify-content-center">
											{{ $payouts->appends($_GET)->links() }}
										</ul>
									</nav>
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
