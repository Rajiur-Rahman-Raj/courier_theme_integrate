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
																	value="canceled" {{ request()->shipment_status == 'canceled' ? 'selected' : '' }}>@lang('Canceled')</option>
																<option
																	value="in_queue" {{ request()->shipment_status == 'in_queue' ? 'selected' : '' }}>@lang('In Queue')</option>
																<option
																	value="dispatch" {{ request()->shipment_status == 'dispatch' ? 'selected' : '' }}>@lang('Dispatch')</option>
																<option
																	value="upcoming" {{ request()->shipment_status == 'upcoming' ? 'selected' : '' }}>@lang('Upcoming')</option>
																<option
																	value="received" {{ request()->shipment_status == 'received' ? 'selected' : '' }}>@lang('Received')</option>
																<option
																	value="delivered" {{ request()->shipment_status == 'delivered' ? 'selected' : '' }}>@lang('Delivered')</option>
																<option
																	value="return_in_queue" {{ request()->shipment_status == 'return_in_queue' ? 'selected' : '' }}>@lang('Return In Queue')</option>
																<option
																	value="return_dispatch" {{ request()->shipment_status == 'return_dispatch' ? 'selected' : '' }}>@lang('Return Dispatch')</option>
																<option
																	value="return_upcoming" {{ request()->shipment_status == 'return_upcoming' ? 'selected' : '' }}>@lang('Return Upcoming')</option>
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
											<a href="javascript:void(0)" data-route="{{route('export.shipmentReport')}}"
											   class="btn btn-sm btn-outline-primary downloadExcel"><i
													class="fas fa-download"></i> @lang('Download Excel File')</a>
										</div>

										<div class="card-body">
											<div class="section-invoice p-0">
												<div class="invoice-box p-0" id="shipmentInvoice">
													<div class="invoice-table">
														<table class="table  table-hover">
															<thead>
															<th scope="col">@lang('SL.')</th>
															<th scope="col">@lang('Shipment Id')</th>
															<th scope="col">@lang('Type')</th>
															<th scope="col">@lang('Sender')</th>
															<th scope="col">@lang('Receiver')</th>
															<th scope="col">@lang('From')</th>
															<th scope="col">@lang('To')</th>
															<th scope="col">@lang('Total Cost')</th>
															<th scope="col">@lang('Shipment Date')</th>
															<th scope="col">@lang('Payment Status')</th>
															<th scope="col">@lang('Shipment Status')</th>
															</thead>
															@if(isset($shipmentReports) && count($shipmentReports) > 0 && count($search) > 0)
																<tbody>
																@foreach($shipmentReports as $key => $shipment)
																	<tr>
																		<td data-label="SL."> {{ ++$key }} </td>
																		<td data-label="Shipment Id"> {{ $shipment->shipment_id }} </td>
																		<td data-label="Shipment Type"> {{ formatedShipmentType($shipment->shipment_type) }} </td>
																		<td data-label="Sender">
																			@lang('Branch'): <br> @lang(optional($shipment->senderBranch)->branch_name)
																			<br>
																			@lang('Customer'): <br> @lang(optional($shipment->sender)->name)
																		</td>
																		<td data-label="Receiver">
																			@lang('Branch'): <br> @lang(optional($shipment->receiverBranch)->branch_name)
																			<br>
																			@lang('Customer'): <br> @lang(optional($shipment->receiver)->name)
																			<br>
																		</td>
																		<td data-label="From">
																			@if($shipment->from_country_id != null)
																				@lang('Country')
																				: <br> @lang(optional($shipment->fromCountry)->name)
																			@endif
																			<br>
																			@if($shipment->from_state_id != null)
																				@lang('State')
																				:  <br> @lang(optional($shipment->fromState)->name)
																			@endif
																			<br>
																			@if($shipment->from_city_id != null)
																				@lang('City')
																				: <br> @lang(optional($shipment->fromCity)->name)
																			@endif
																			<br>
																			@if($shipment->from_area_id != null)
																				@lang('Area')
																				: <br> @lang(optional($shipment->fromArea)->name)
																			@endif
																			<br>
																		</td>
																		<td data-label="To">
																			@if($shipment->to_country_id != null)
																				@lang('Country')
																				: <br> @lang(optional($shipment->toCountry)->name)
																			@endif
																			<br>
																			@if($shipment->to_state_id != null)
																				@lang('State')
																				: <br> @lang(optional($shipment->toState)->name)
																			@endif
																			<br>
																			@if($shipment->to_city_id != null)
																				@lang('City')
																				: <br> @lang(optional($shipment->toCity)->name)
																			@endif
																			<br>
																			@if($shipment->to_area_id != null)
																				@lang('Area')
																				: @lang(optional($shipment->toArea)->name)
																			@endif
																			<br>
																		</td>
																		<td data-label="Total Cost"> {{ $basic->currency_symbol }}{{ $shipment->total_pay }} </td>
																		<td data-label="Shipment Date"> {{ customDate($shipment->shipment_date) }} </td>
																		<td>
																			@if($shipment->payment_status == 1)
																				<span class="badge badge-light">
            																			<i class="fa fa-circle text-success"></i>
																						@lang('Paid')
																					</span>
																			@else
																				<span class="badge badge-light">
            																			<i class="fa fa-circle text-danger"></i>
																						@lang('Unpaid')
																					</span>
																			@endif
																		</td>
																		<td data-label="Status">
																			@if(($shipment->status == 0) || ($shipment->status == 5 && $shipment->assign_to_collect != null))
																				<span class="badge badge-light">
            																			<i class="fa fa-circle text-dark"></i>
																						@lang('Requested')
																					</span>
																			@elseif($shipment->status == 6)
																				<span class="badge badge-light">
            																			<i class="fa fa-circle text-danger"></i>
																						@lang('Canceled')
																					</span>
																			@elseif($shipment->status == 1)
																				<span class="badge badge-light">
            																			<i class="fa fa-circle text-info"></i>
																						@lang('In Queue')
																					</span>
																			@elseif(($shipment->status == 2) && (@request()->shipment_status == 'dispatch' || @request()->shipment_status == 'all'))
																				<span class="badge badge-light">
            																			<i class="fa fa-circle text-warning"></i>
																						@lang('Dispatch')
																					</span>
																			@elseif(($shipment->status == 2) && (@request()->shipment_status == 'upcoming' || @request()->shipment_status == 'all'))
																				<span class="badge badge-light">
            																			<i class="fa fa-circle text-indigo"></i>
																						@lang('Upcoming')
																					</span>
																			@elseif(($shipment->status == 3) || ($shipment->status == 7 && $shipment->assign_to_delivery != null))
																				<span class="badge badge-light">
            																			<i class="fa fa-circle text-success"></i>
																						@lang('Received')
																					</span>
																			@elseif($shipment->status == 4)
																				<span class="badge badge-light">
            																			<i class="fa fa-circle text-danger"></i>
																						@lang('Delivered')
																					</span>
																			@elseif($shipment->status == 8)
																				<span class="badge badge-light">
            																			<i class="fa fa-circle text-info"></i>
																						@lang('Return In Queue')
																					</span>
																			@elseif(($shipment->status == 9) && (@request()->shipment_status == 'return_dispatch' || @request()->shipment_status == 'all'))
																				<span class="badge badge-light">
            																			<i class="fa fa-circle text-warning"></i>
																						@lang('Return Dispatch')
																					</span>
																			@elseif(($shipment->status == 9) && (@request()->shipment_status == 'return_upcoming' || @request()->shipment_status == 'all'))
																				<span class="badge badge-light">
            																			<i class="fa fa-circle text-indigo"></i>
																						@lang('Return Upcoming')
																					</span>
																			@elseif(($shipment->status == 10) && (@request()->shipment_status == 'return_received' || @request()->shipment_status == 'all'))
																				<span class="badge badge-light">
            																			<i class="fa fa-circle text-success"></i>
																						@lang('Return Received')
																					</span>
																			@elseif(($shipment->status == 11) && (@request()->shipment_status == 'return_delivered' || @request()->shipment_status == 'all'))
																				<span class="badge badge-light">
            																			<i class="fa fa-circle text-danger"></i>
																						@lang('Return Delivered')
																					</span>
																			@endif
																		</td>
																	</tr>
																@endforeach
																</tbody>
															@else
																<tr>
																	<td colspan="100%" class="text-center">
																		<img class="not-found-img"
																			 src="{{ asset('assets/dashboard/images/empty-state.png') }}"
																			 alt="">
																		<p class="text-center d-block no-data-found-text">@lang('No Shipment Report Found')</p>
																	</td>
																</tr>
															@endif
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
		var serachRoute = "{{route('shipmentReport')}}"
		$(document).on("click", ".downloadExcel", function () {
			$('.searchForm').attr('action', $(this).data('route'));
			$('.searchForm').submit();
			$('.searchForm').attr('action', serachRoute);

		});


		$(".flatpickr").flatpickr({
			wrap: true,
			altInput: true,
			dateFormat: "Y-m-d H:i",
			maxDate: new Date(new Date().getTime() + 24 * 60 * 60 * 1000) // today + 1 day
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
