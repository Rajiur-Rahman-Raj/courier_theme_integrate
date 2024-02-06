@extends('admin.layouts.master')
@section('page_title', __('Shipment Reports'))
@push('extra_styles')
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dataTables.bootstrap4.min.css') }}">
	<link href="{{ asset('assets/dashboard/css/flatpickr.min.css') }}" rel="stylesheet">
@endpush

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Shipment Report')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Shipment Reports')</div>
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
											<form action="" method="get" class="searchForm">
												<div class="row">
													<div class="{{ isset($branchId) ? 'col-md-2' : 'col-md-4' }}">
														<label for="shipment_date"
															   class="custom-text"> @lang('From Date') </label>
														<div class="flatpickr">
															<div class="input-group">
																<input type="date" placeholder="@lang('Select date')"
																	   class="form-control from_date"
																	   name="from_date"
																	   value="{{ old('from_date',request()->from_date) }}"
																	   data-input>
																<div class="input-group-append" readonly="">
																	<div class="form-control">
																		<a class="input-button cursor-pointer"
																		   title="clear" data-clear>
																			<i class="fas fa-times"></i>
																		</a>
																	</div>
																</div>
																<div class="invalid-feedback d-block">
																	@error('from_date') @lang($message) @enderror
																</div>
															</div>
														</div>
													</div>

													<div class="{{ isset($branchId) ? 'col-md-2' : 'col-md-4' }}">
														<label for="to_date"
															   class="custom-text"> @lang('To Date') </label>
														<div class="flatpickr">
															<div class="input-group">
																<input type="date" placeholder="@lang('Select date')"
																	   class="form-control delivery_date"
																	   name="to_date"
																	   value="{{ old('to_date',request()->to_date) }}"
																	   data-input>
																<div class="input-group-append" readonly="">
																	<div class="form-control">
																		<a class="input-button cursor-pointer"
																		   title="clear" data-clear>
																			<i class="fas fa-times"></i>
																		</a>
																	</div>
																</div>
																<div class="invalid-feedback d-block">
																	@error('to_date') @lang($message) @enderror
																</div>
															</div>
														</div>
													</div>

													@if(!isset($branchId))
														<div class="col-md-4">
															<div class="form-group search-currency-dropdown">
																<label for="branch_id"
																	   class="custom-text">@lang('Branch')</label>
																<select name="branch_id"
																		class="form-control form-control-sm select2">
																	<option
																		value="all" {{ request()->shipment_from == 'all' ? 'selected' : '' }}>@lang('All')</option>
																	@foreach($branches as $branch)
																		<option
																			value="{{ $branch->id }}" {{ request()->branch_id == $branch->id ? 'selected' : '' }}>@lang($branch->branch_name)</option>
																	@endforeach
																</select>
															</div>
														</div>
													@endif


													<div class="{{ isset($branchId) ? 'col-md-2' : 'col-md-4' }}">
														<div class="form-group search-currency-dropdown">
															<label for="shipment_from"
																   class="custom-text">@lang('Shipment From')</label>
															<select name="shipment_from"
																	class="form-control form-control-sm">
																<option
																	value="all" {{ request()->shipment_from == 'all' ? 'selected' : '' }}>@lang('All')</option>
																<option
																	value="operator_country" {{ request()->shipment_from == 'operator_country' ? 'selected' : '' }}>@lang(optional(basicControl()->operatorCountry)->name)</option>
																<option
																	value="internationally" {{ request()->shipment_from == 'internationally' ? 'selected' : '' }}>@lang('Internationally')</option>
															</select>
														</div>
													</div>

													<div class="{{ isset($branchId) ? 'col-md-2' : 'col-md-4' }}">
														<div class="form-group search-currency-dropdown">
															<label for="shipment_type"
																   class="custom-text">@lang('Shipment Type')</label>
															<select name="shipment_type"
																	class="form-control form-control-sm">
																<option
																	value="all" {{ request()->shipment_type == 'all' ? 'selected' : '' }}>@lang('All')</option>
																<option
																	value="drop_off" {{ request()->shipment_type == 'drop_off' ? 'selected' : '' }}>@lang('Drop Off')</option>
																<option
																	value="pickup" {{ request()->shipment_type == 'pickup' ? 'selected' : '' }}>@lang('Pickup')</option>
																<option
																	value="condition" {{ request()->shipment_type == 'condition' ? 'selected' : '' }}>@lang('Condition')</option>
															</select>
														</div>
													</div>

													<div class="{{ isset($branchId) ? 'col-md-2' : 'col-md-4' }}">
														<div class="form-group search-currency-dropdown">
															<label for="shipment_status"
																   class="custom-text">@lang('Shipment Status')</label>
															<select name="shipment_status"
																	class="form-control form-control-sm">
																<option value="all">@lang('All')</option>
																<option
																	value="requested" {{ request()->shipment_status == 'requested' ? 'selected' : '' }}>@lang('Requested')</option>
																<option
																	value="in_queue" {{ request()->shipment_status == 'in_queue' ? 'selected' : '' }}>@lang('In Queue')</option>
																<option
																	value="dispatch" {{ request()->shipment_status == 'dispatch' ? 'selected' : '' }}>@lang('Dispatch')</option>
																<option
																	value="received" {{ request()->shipment_status == 'received' ? 'selected' : '' }}>@lang('Received')</option>
																<option
																	value="delivered" {{ request()->shipment_status == 'delivered' ? 'selected' : '' }}>@lang('Delivered')</option>
																<option
																	value="return_in_queue" {{ request()->shipment_status == 'return_in_queue' ? 'selected' : '' }}>@lang('Return In Queue')</option>
																<option
																	value="return_dispatch" {{ request()->shipment_status == 'return_dispatch' ? 'selected' : '' }}>@lang('Return Dispatch')</option>
																<option
																	value="return_received" {{ request()->shipment_status == 'return_received' ? 'selected' : '' }}>@lang('Return Received')</option>
																<option
																	value="return_delivered" {{ request()->shipment_status == 'return_delivered' ? 'selected' : '' }}>@lang('Return Delivered')</option>
															</select>
														</div>
													</div>
													@if(isset($branchId))
														<input type="hidden" name="branch_id" value="{{ $branchId }}"
															   class="form-control">
													@endif
													<div class="{{ isset($branchId) ? 'col-md-2' : 'col-md-12' }}">
														<div class="form-group">
															<label for="shipment_status"
																   class="custom-text opacity-0">@lang('Search')</label>
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
											<h6 class="m-0 font-weight-bold text-primary">@lang('Shipment Report Result')</h6>
											<a href="javascript:void(0)" data-route="{{route('export.shipmentReportCount')}}"
											   class="btn btn-sm btn-outline-primary downloadExcel"><i
													class="fas fa-download"></i> @lang('Download Excel File')</a>

										</div>

										<div class="card-body">
											<div class="section-invoice p-0">
												<div class="invoice-box p-0" id="shipmentInvoice">
													{{--													<div class="invoice-table">--}}
													{{--														<table class="table  table-hover">--}}
													{{--															<thead>--}}
													{{--															<th scope="col">@lang('Parcel Name')</th>--}}
													{{--															<th scope="col">@lang('Quantity')</th>--}}
													{{--															<th scope="col">@lang('Parcel Type')</th>--}}
													{{--															<th scope="col">@lang('Total Unit')</th>--}}
													{{--															<th scope="col">@lang('Cost')</th>--}}
													{{--															</thead>--}}

													{{--															<tbody>--}}

													{{--															<tr>--}}
													{{--																<td>aasdfsad</td>--}}
													{{--																<td>15</td>--}}
													{{--																<td>#KHHGKJ</td>--}}
													{{--																<td>100--}}
													{{--																	<span--}}
													{{--																		class="">KG</span>--}}
													{{--																</td>--}}
													{{--																<td>{{ $basic->currency_symbol }}0</td>--}}
													{{--															</tr>--}}
													{{--															<tr>--}}
													{{--																<td colspan="4"--}}
													{{--																	class="t-price">@lang('Total Price')</td>--}}
													{{--																<td data-label="Total price">{{ $basic->currency_symbol }}--}}
													{{--																	0--}}
													{{--																</td>--}}
													{{--															</tr>--}}
													{{--															</tbody>--}}
													{{--														</table>--}}
													{{--													</div>--}}

													<div class="invoice-table mt-0">
														<table class="table table-hover">
															<tbody>
															@if(isset($shipmentReportRecords) && sizeof($search) > 0)
																@if($search['shipment_from'] == 'all')
																	<tr>
																		<td class="t-total"
																			colspan="5">@lang('Total Shipments')
																		</td>
																		<td class="text-right"
																			data-label="Total Shipments">
																			{{ $shipmentReportRecords['total_shipments'] }}
																		</td>
																	</tr>
																@endif

																@if($search['shipment_from'] == 'operator_country' || $search['shipment_from'] == 'all')
																	<tr>
																		<td class="t-total"
																			colspan="5">@lang(optional(basicControl()->operatorCountry)->name)
																		</td>
																		<td class="text-right"
																			data-label="Operator Country">
																			{{ $shipmentReportRecords['totalOperatorCountryShipments'] }}
																		</td>
																	</tr>
																@endif

																@if($search['shipment_from'] == 'internationally' || $search['shipment_from'] == 'all')
																	<tr>
																		<td class="t-total"
																			colspan="5">@lang('Internationally')
																		</td>
																		<td class="text-right"
																			data-label="Internationally">
																			{{ $shipmentReportRecords['totalInternationallyShipments'] }}
																		</td>
																	</tr>
																@endif

																@if($search['shipment_type'] == 'drop_off' || $search['shipment_type'] == 'all')
																	<tr>
																		<td class="t-total"
																			colspan="5">@lang('Drop Off')
																		</td>
																		<td class="text-right"
																			data-label="Drop Off">
																			{{ $shipmentReportRecords['totalDropOffShipments'] }}
																		</td>
																	</tr>
																@endif

																@if($search['shipment_type'] == 'pickup' || $search['shipment_type'] == 'all')
																	<tr>
																		<td class="t-total"
																			colspan="5">@lang('Pickup')
																		</td>
																		<td class="text-right"
																			data-label="Pickup">
																			{{ $shipmentReportRecords['totalPickupShipments'] }}
																		</td>
																	</tr>
																@endif

																@if($search['shipment_type'] == 'condition' || $search['shipment_type'] == 'all')
																	<tr>
																		<td class="t-total"
																			colspan="5">@lang('Condition')

																		</td>
																		<td class="text-right"
																			data-label="Condition">
																			{{ $shipmentReportRecords['totalConditionShipments'] }}
																		</td>
																	</tr>
																@endif

																@if($search['shipment_status'] == 'requested' || $search['shipment_status'] == 'all')
																	<tr>
																		<td class="t-total"
																			colspan="5">@lang('Requested')
																		</td>
																		<td class="text-right"
																			data-label="Requested">
																			{{ $shipmentReportRecords['totalPendingShipments'] }}
																		</td>
																	</tr>
																@endif

																@if($search['shipment_status'] == 'in_queue' || $search['shipment_status'] == 'all')
																	<tr>
																		<td class="t-total"
																			colspan="5">@lang('In Queue')
																		</td>
																		<td class="text-right"
																			data-label="In Queue">
																			{{ $shipmentReportRecords['totalInQueueShipments'] }}
																		</td>
																	</tr>
																@endif

																@if($search['shipment_status'] == 'dispatch' || $search['shipment_status'] == 'all')
																	<tr>
																		<td class="t-total"
																			colspan="5">@lang('Dispatch')
																		</td>
																		<td class="text-right"
																			data-label="Dispatch">
																			{{ $shipmentReportRecords['totalDispatchShipments'] }}
																		</td>
																	</tr>
																@endif

																@if($search['shipment_status'] == 'received' || $search['shipment_status'] == 'all')
																	<tr>
																		<td class="t-total"
																			colspan="5">@lang('Received')
																		</td>
																		<td class="text-right"
																			data-label="Received">
																			{{ $shipmentReportRecords['totalDeliveryInQueueShipments'] }}
																		</td>
																	</tr>
																@endif

																@if($search['shipment_status'] == 'delivered' || $search['shipment_status'] == 'all')
																	<tr>
																		<td class="t-total"
																			colspan="5">@lang('Delivered')
																		</td>
																		<td class="text-right"
																			data-label="Delivered">
																			{{ $shipmentReportRecords['totalDeliveredShipments'] }}
																		</td>
																	</tr>
																@endif

																@if($search['shipment_status'] == 'return_in_queue' || $search['shipment_status'] == 'all')
																	<tr>
																		<td class="t-total"
																			colspan="5">@lang('Return In Queue')
																		</td>
																		<td class="text-right"
																			data-label="Return In Queue">
																			{{ $shipmentReportRecords['totalReturnInQueueShipments'] }}
																		</td>
																	</tr>
																@endif

																@if($search['shipment_status'] == 'return_dispatch' || $search['shipment_status'] == 'all')
																	<tr>
																		<td class="t-total"
																			colspan="5">@lang('Return Dispatch')
																		</td>
																		<td class="text-right"
																			data-label="Return Dispatch">
																			{{ $shipmentReportRecords['totalReturnInDispatchShipments'] }}
																		</td>
																	</tr>
																@endif

																@if($search['shipment_status'] == 'return_received' || $search['shipment_status'] == 'all')
																	<tr>
																		<td class="t-total"
																			colspan="5">@lang('Return Received')
																		</td>
																		<td class="text-right"
																			data-label="Return Received">
																			{{ $shipmentReportRecords['totalReturnDeliveryInQueueShipments'] }}
																		</td>
																	</tr>
																@endif

																@if($search['shipment_status'] == 'return_delivered' || $search['shipment_status'] == 'all')
																	<tr>
																		<td class="t-total"
																			colspan="5">@lang('Return Delivered')
																		</td>
																		<td class="text-right"
																			data-label="Return Delivered">
																			{{ $shipmentReportRecords['totalReturnInDelivered'] }}
																		</td>
																	</tr>
																@endif
															@else
																<tr>
																	<td colspan="100%" class="text-center p-2">
																		<img class="not-found-img"
																			 src="{{ asset('assets/dashboard/images/empty-state.png') }}"
																			 alt="">
																		<p class="text-danger text-center d-block">@lang('No found data')</p>
																	</td>
																</tr>
															@endif
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
				</div>
			</div>
		</section>
	</div>

@endsection

@push('extra_scripts')
	<script src="{{ asset('assets/dashboard/js/flatpickr.js') }}"></script>
@endpush

@section('scripts')
	@include('admin.partials.manageShipmentJs')
	<script>
		'use strict'
		var serachRoute = "{{route('shipmentReportCount')}}"
		$(document).on("click", ".downloadExcel", function () {
			$('.searchForm').attr('action', $(this).data('route'));
			$('.searchForm').submit();
			$('.searchForm').attr('action', serachRoute);

		});

		$(".flatpickr").flatpickr({
			wrap: true,
			altInput: true,
			dateFormat: "Y-m-d H:i",
		});

	</script>

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
