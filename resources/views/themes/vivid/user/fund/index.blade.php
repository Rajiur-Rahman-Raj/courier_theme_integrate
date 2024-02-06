@extends($theme.'layouts.user')
@section('page_title',__('Fund History List'))

@push('extra_styles')
	<link href="{{ asset('assets/dashboard/css/flatpickr.min.css') }}" rel="stylesheet">
@endpush

@section('content')
	<!-- Main Content -->
	<div class="container-fluid">
		<div class="main row">
			<div class="col-12">
				<div class="dashboard-heading">
					<div>
						<h2 class="mb-0">@lang('Fund List')</h2>
						<nav aria-label="breadcrumb" class="ms-2">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('Home')</a>
								</li>
								<li class="breadcrumb-item"><a href="javascript:void(0)">@lang('Fund History')</a></li>
							</ol>
						</nav>
					</div>
				</div>
			</div>

			<div class="row mb-3">
				<div class="container-fluid" id="container-wrapper">
					<div class="row">
						<div class="col-lg-12">
							<div class="search-bar profile-setting">
								<form action="{{ route('fund.search') }}" method="get">
									@include($theme.'user.fund.searchForm')
								</form>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="table-parent table-responsive">
								<table
									class="table table-striped table-hover align-items-center table-borderless">
									<thead class="thead-light">
									<tr>
										<th>@lang('SL')</th>
										<th>@lang('Method')</th>
										<th>@lang('Transaction ID')</th>
										<th>@lang('Requested Amount')</th>
										<th>@lang('Status')</th>
										<th>@lang('Created time')</th>
									</tr>
									</thead>
									<tbody>
									@forelse($funds as $key => $value)
										<tr>
											<td data-label="@lang('SL')">{{loopIndex($funds) + $key }}</td>
											<td data-label="@lang('Method')">{{ __(optional(optional($value->depositable)->gateway)->name) ?? __('N/A') }}</td>
											<td data-label="@lang('Transaction ID')">{{ __($value->utr) }}</td>
											<td data-label="@lang('Requested Amount')">{{ (getAmount($value->amount)).' '.config('basic.base_currency') }}</td>
											<td data-label="@lang('Status')">
												@if($value->status)
													<span class="badge text-bg-success">@lang('Success')</span>
												@else
													<span class="badge text-bg-warning">@lang('Pending')</span>
												@endif
											</td>
											<td data-label="@lang('Created time')"> {{ customDate($value->created_at)}} </td>
										</tr>
									@empty
										<tr>
											<td colspan="100%" class="text-center p-2 flex-column">
												<img class="not-found-img"
													 src="{{ asset($themeTrue.'images/business.png') }}"
													 alt="">
												<p class="text-center no-data-found-text">@lang('No Deposit History Found')</p>
											</td>
										</tr>
									@endforelse
									</tbody>
								</table>
							</div>
							<div class="pagination_area mt-4">
								<nav aria-label="Page navigation example">
									<ul class="pagination justify-content-center">
										{{ $funds->appends($_GET)->links() }}
									</ul>
								</nav>
							</div>
						</div>
					</div>
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
