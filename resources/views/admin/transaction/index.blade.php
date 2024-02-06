@extends('admin.layouts.master')
@section('page_title',__('Transactions'))

@push('extra_styles')
	<link href="{{ asset('assets/dashboard/css/flatpickr.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1>@lang('Shipment Transactions')</h1>
			<div class="section-header-breadcrumb">
				<div class="breadcrumb-item active">
					<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
				</div>
				<div class="breadcrumb-item">@lang('Transactions')</div>
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
								<form action="" method="get">
									@include('admin.transaction.searchForm')
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="card mb-4 card-primary shadow">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
								<h6 class="m-0 font-weight-bold text-primary">@lang('Transactions List')</h6>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-striped table-hover align-items-center table-borderless">
										<thead class="thead-light">
										<tr>
											<th col="">@lang('SL')</th>
											<th col="">@lang('Transaction Id')</th>
											<th col="">@lang('Name')</th>
											<th col="">@lang('Amount')</th>
											<th col="">@lang('Remarks')</th>
											<th col="">@lang('Transaction At')</th>
										</tr>
										</thead>
										<tbody>
										@forelse($transactions as $key => $value)
											<tr>
												<td data-label="@lang('SL')">
													{{loopIndex($transactions) + $key}}
												</td>
												<td data-label="@lang('Transaction Id')">
													{{ $value->trx_id }}
												</td>

												<td data-label="@lang('Name')">@lang(optional($value->user)->name ?? "N/A")</td>
												<td data-label="@lang('Amount')"><span class="{{ $value->trx_type == '+' ? 'text-success' : 'text-danger' }} font-weight-bold">{{ $value->trx_type }}</span> <span class="{{ $value->trx_type == '+' ? 'text-success' : 'text-danger' }}">{{ (config('basic.currency_symbol').getAmount($value->amount)) }}</span></td>

												<td data-label="@lang('Remarks')">@lang($value->remarks)</td>

												<td data-label="@lang('Transaction At')"> {{ customDateTime($value->created_at)}} </td>
											</tr>
										@empty
											<tr>
												<td colspan="100%" class="text-center">
													<img class="not-found-img" src="{{ asset('assets/dashboard/images/empty-state.png') }}" alt="">
													<p class="text-center no-data-found-text">@lang('No Transaction Found')</p>
												</td>
											</tr>
										@endforelse
										</tbody>
									</table>
								</div>
								<div class="card-footer d-flex justify-content-center">
									{{ $transactions->links() }}
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

			$('.from_date').on('change', function () {
				$('.to_date').removeAttr('disabled');
			});
		})
	</script>
@endsection
