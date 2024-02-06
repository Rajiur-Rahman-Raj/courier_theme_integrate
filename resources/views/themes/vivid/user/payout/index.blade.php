@extends($theme.'layouts.user')
@section('page_title',__('Payout List'))

@push('extra_styles')
	<link href="{{ asset('assets/dashboard/css/flatpickr.min.css') }}" rel="stylesheet">
@endpush

@section('content')
	<div class="container-fluid">
		<div class="main row">
			<div class="col-12">
				<div class="dashboard-heading">
					<div>
						<h2 class="mb-0">@lang('Payout List')</h2>
						<nav aria-label="breadcrumb" class="ms-2">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="javascript:void(0)">@lang('Home')</a></li>
								<li class="breadcrumb-item"><a href="javascript:void(0)">@lang('Payout History')</a></li>
							</ol>
						</nav>
					</div>
				</div>
				<div class="search-bar profile-setting">
					<form action="{{ route('payout.search') }}" method="get">
						@include($theme.'user.payout.searchForm')
					</form>
				</div>
				<div class="table-parent table-responsive">
					<table class="table table-striped">
						<thead>
						<tr>
							<th scope="col">@lang('SL')</th>
							<th scope="col">@lang('Transaction ID')</th>
							<th scope="col">@lang('Amount')</th>
							<th scope="col">@lang('Status')</th>
							<th scope="col">@lang('Created time')</th>
							<th scope="col">@lang('Action')</th>
						</tr>
						</thead>
						<tbody>
						@forelse($payouts as $key => $value)
							<tr>
								<td data-label="@lang('SL')">{{loopIndex($payouts) + $key}}</td>
								<td data-label="@lang('Transaction ID')">{{ __($value->utr) }}</td>
								<td data-label="@lang('Amount')">{{ (getAmount($value->amount)).' '.__(config('basic.base_currency')) }}</td>
								<td data-label="@lang('Status')">
									@if($value->status == 0)
										<span class="badge text-bg-warning">@lang('Pending')</span>
									@elseif($value->status == 1)
										<span class="badge text-bg-success">
                                                  @lang('Generated')
                                                </span>
									@elseif($value->status == 2)
										<span
											class="badge text-bg-info">@lang('Payment Done')</span>
									@elseif($value->status == 5)
										<span class="badge text-bg-danger">@lang('Canceled')</span>
									@endif
								</td>
								<td data-label="@lang('Created time')"> {{ dateTime($value->created_at)}} </td>
								<td data-label="@lang('Action')">
									@if($value->status == 0)
										<a href="{{ route('payout.confirm',$value->utr) }}"
										   target="_blank"
										   class="view_cmn_btn">@lang('Confirm')</a>
									@else
										---
									@endif
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="100%" class="text-center p-2 flex-column">
									<img class="not-found-img"
										 src="{{ asset($themeTrue.'images/business.png') }}"
										 alt="">
									<p class="text-center no-data-found-text">@lang('No Payouts Found')</p>
								</td>
							</tr>
						@endforelse
						</tbody>
					</table>
				</div>
				<div class="pagination_area mt-3">
					<nav aria-label="Page navigation example">
						<ul class="pagination justify-content-center">
							{{ $payouts->appends($_GET)->links() }}
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('extra_scripts')
	<script src="{{ asset('assets/dashboard/js/flatpickr.js') }}"></script>
@endpush

@section('scripts')
	<script>
		'use strict'
		$('.from_date').on('change', function () {
			$('.to_date').removeAttr('disabled');
		});

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

