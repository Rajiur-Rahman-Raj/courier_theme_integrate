@extends('admin.layouts.master')
@section('page_title', __($page_title). ' Shipments')
@push('extra_styles')
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dataTables.bootstrap4.min.css') }}">
	<link href="{{ asset('assets/dashboard/css/flatpickr.min.css') }}" rel="stylesheet">
@endpush

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Shipment List')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Shipment List')</div>
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
											@include('partials.shipmentSearchForm')
										</div>
									</div>
								</div>
							</div>

							<div class="row justify-content-md-center">
								<div class="col-lg-12">
									<div class="card mb-4 card-primary shadow">
										<div
											class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
											<h6 class="m-0 font-weight-bold text-primary">@lang('Shipment List')</h6>
											@if(adminAccessRoute(config('permissionList.Manage_Shipments.Shipment_List.permission.add')))
												@if($authenticateUser->branch != null || $authenticateUser->role_id == null)
													<a href="{{route('createShipment', ['shipment_type' => 'operator-country', 'shipment_status' => $status])}}"
													   class="btn btn-sm btn-outline-primary add"><i
															class="fas fa-plus-circle"></i> @lang('Create Shipment')</a>
												@endif
											@endif
										</div>

										<div class="card-body">
											<div class="switcher">
												<a href="{{ route('shipmentList', ['shipment_status' => $status, 'shipment_type' => 'operator-country']) }}">
													<button
														class="custom-text @if(lastUriSegment() == 'operator-country') active @endif">@lang(optional(basicControl()->operatorCountry)->name)</button>
												</a>
												<a href="{{ route('shipmentList', ['shipment_status' => $status, 'shipment_type' => 'internationally']) }}">
													<button
														class="custom-text @if(lastUriSegment() == 'internationally') active @endif">@lang('Internationally')</button>
												</a>
											</div>

											{{-- Table --}}
											<div class="row justify-content-md-center mt-4">
												<div class="col-lg-12">
													<div class="table-responsive">
														<table
															class="table table-striped table-hover align-items-center table-flush"
															id="data-table">
															<thead class="thead-light">
															<tr>
																<th scope="col"
																	class="custom-text">@lang('SL.')</th>
																<th scope="col"
																	class="custom-text">@lang('Shipment Id')</th>
																<th scope="col"
																	class="custom-text">@lang('Shipment Type')</th>
																<th scope="col"
																	class="custom-text">@lang('Sender Branch')</th>
																<th scope="col"
																	class="custom-text">@lang('Receiver Branch')</th>
																<th scope="col"
																	class="custom-text">@lang('From State')</th>
																<th scope="col"
																	class="custom-text">@lang('To State')</th>
																<th scope="col"
																	class="custom-text">@lang('Total Cost')</th>
																<th scope="col"
																	class="custom-text">@lang('Shipment Date')</th>
																<th scope="col"
																	class="custom-text">@lang('Status')</th>
																<th scope="col"
																	class="custom-text">@lang('Action')</th>
															</tr>
															</thead>

															<tbody>
															@forelse($allShipments as $key => $shipment)
																<tr>
																	<td data-label="SL."> {{ ++$key }} </td>
																	<td data-label="Shipment Id"> {{ $shipment->shipment_id }} </td>
																	<td data-label="Shipment Type"> {{ formatedShipmentType($shipment->shipment_type) }} </td>
																	<td data-label="Sender Branch"> @lang(optional($shipment->senderBranch)->branch_name) </td>
																	<td data-label="Receiver Branch"> @lang(optional($shipment->receiverBranch)->branch_name) </td>
																	<td data-label="From State"> @lang(optional($shipment->fromState)->name) </td>
																	<td data-label="To State"> @lang(optional($shipment->toState)->name) </td>
																	<td data-label="Total Cost"> {{ $basic->currency_symbol }}{{ $shipment->total_pay }} </td>

																	<td data-label="Shipment Date"> {{ customDate($shipment->shipment_date) }} </td>

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
																		@elseif(($shipment->status == 2) && ($status == 'dispatch'))
																			<span class="badge badge-light">
            																			<i class="fa fa-circle text-warning"></i>
																						@lang('Dispatch')
																					</span>
																		@elseif($shipment->status == 2 && $status == 'upcoming')
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
																		@elseif(($shipment->status == 9) && ($status == 'return_in_dispatch'))
																			<span class="badge badge-light">
            																			<i class="fa fa-circle text-warning"></i>
																						@lang('Return In Dispatch')
																					</span>
																		@elseif($shipment->status == 9 && $status == 'return_in_upcoming')
																			<span class="badge badge-light">
            																			<i class="fa fa-circle text-indigo"></i>
																						@lang('Return In Upcoming')
																					</span>
																		@elseif($shipment->status == 10 && $status == 'return_in_received')
																			<span class="badge badge-light">
            																			<i class="fa fa-circle text-success"></i>
																						@lang('Return Received')
																					</span>
																		@elseif($shipment->status == 11 && $status == 'return_in_delivered')
																			<span class="badge badge-light">
            																			<i class="fa fa-circle text-danger"></i>
																						@lang('Return Delivered')
																					</span>
																		@endif
																	</td>

																	<td data-label="@lang('Action')">
																		<div class="btn-group">
																			<div class="dropdown">
																				<button type="button"
																						class="btn btn-outline-primary rounded-circle btn-sm "
																						data-toggle="dropdown"
																						aria-haspopup="true"
																						aria-expanded="false">
																					<i class="fas fa-cog"
																					   data-toggle="tooltip"
																					   data-placement="top"
																					   title="@lang('More Options')"></i>
																				</button>
																				<div
																					class="dropdown-menu dropdown-menu-right">
																					<a href="{{ route('shipmentInvoice', ['id' => $shipment->id, 'segment' => $status, 'shipment_type' => 'operator-country']) }}"
																					   class="dropdown-item btn-outline-primary text-success btn-sm"><i
																							class="fas fa-file-invoice  mr-2"></i> @lang('Invoice')
																					</a>
																					@if(($shipment->status == 1) && ($status == 'in_queue' || $status == 'all') && (optional(optional($shipment->senderBranch)->branchManager)->admin_id == $authenticateUser->id || $authenticateUser->role_id == null))
																						@if(adminAccessRoute(config('permissionList.Manage_Shipments.Shipment_List.permission.dispatch')))
																							<a data-target="#updateShipmentStatus"
																							   data-toggle="modal"
																							   data-status="{{ $status }}"
																							   data-route="{{route('updateShipmentStatus', ['id' => $shipment->id, 'type' => 'dispatch'])}}"
																							   href="javascript:void(0)"
																							   class="dropdown-item btn-outline-primary text-warning btn-sm editShipmentStatus"><i
																									class="fas fa-sign-out-alt mr-2"></i> @lang('Dispatch')
																							</a>
																						@endif
																					@elseif($shipment->status == 2 && $status == 'upcoming')
																						<a data-target="#updateShipmentStatus"
																						   data-toggle="modal"
																						   data-status="{{ $status }}"
																						   data-route="{{route('updateShipmentStatus', ['id' => $shipment->id, 'type' => 'received'])}}"
																						   href="javascript:void(0)"
																						   class="dropdown-item btn-outline-primary text-success btn-sm editShipmentStatus">
																							<i class="far fa-handshake mr-2"></i> @lang('Received')
																						</a>
																					@elseif($shipment->status == 9 && $status == 'return_in_upcoming')
																						<a data-target="#updateShipmentStatus"
																						   data-toggle="modal"
																						   data-status="{{ $status }}"
																						   data-route="{{route('updateShipmentStatus', ['id' => $shipment->id, 'type' => 'return_in_received'])}}"
																						   href="javascript:void(0)"
																						   class="dropdown-item btn-outline-primary text-success btn-sm editShipmentStatus">
																							<i class="far fa-handshake mr-2"></i> @lang('Return Received')
																						</a>
																					@elseif(($shipment->status == 3 || $shipment->status == 7) && ($status == 'received' || $status == 'assign_to_delivery') && (optional(optional($shipment->receiverBranch)->branchManager)->admin_id == $authenticateUser->id || optional($shipment->assignToDelivery)->id == $authenticateUser->id || $authenticateUser->role_id == null))
																						<a data-target="#updateShipmentStatus"
																						   data-toggle="modal"
																						   data-status="{{ $status }}"
																						   data-route="{{route('updateShipmentStatus', ['id' => $shipment->id, 'type' => 'delivered'])}}"
																						   href="javascript:void(0)"
																						   class="dropdown-item btn-outline-primary text-danger btn-sm editShipmentStatus">
																							<i class="fas fa-thumbs-up mr-2"></i> @lang('Delivered')
																						</a>

																						<a data-target="#updateShipmentStatus"
																						   data-toggle="modal"
																						   data-status="return_in_queue"
																						   data-route="{{route('updateShipmentStatus', ['id' => $shipment->id, 'type' => 'return_in_queue'])}}"
																						   href="javascript:void(0)"
																						   class="dropdown-item btn-outline-primary text-warning btn-sm editShipmentStatus">
																							<i class="fas fa-exchange-alt mr-2"></i> @lang('Return Back')
																						</a>
																						@if($shipment->shipment_type == 'pickup' && (optional(optional($shipment->receiverBranch)->branchManager)->admin_id == $authenticateUser->id || $authenticateUser->role_id == null))
																							<a data-target="#assignToDeliveredShipmentRequest"
																							   data-toggle="modal"
																							   data-route="{{route('assignToDeliveredShipmentRequest', $shipment->id)}}"
																							   data-property="{{ $shipment }}"
																							   href="javascript:void(0)"
																							   class="dropdown-item btn-outline-primary text-cyan btn-sm assignToDeliveredShipmentRequest"><i
																									class="fas fa-check"></i> @lang('Assign To Delivery')
																							</a>
																						@endif

																					@elseif(($shipment->status == 10 && $status == 'return_in_received') && (optional(optional($shipment->senderBranch)->branchManager)->admin_id == $authenticateUser->id || $authenticateUser->role_id == null))
																						<a data-target="#updateShipmentStatus"
																						   data-toggle="modal"
																						   data-status="{{ $status }}"
																						   data-route="{{route('updateShipmentStatus', ['id' => $shipment->id, 'type' => 'return_in_delivered'])}}"
																						   href="javascript:void(0)"
																						   class="dropdown-item btn-outline-primary text-danger btn-sm editShipmentStatus">
																							<i class="fas fa-thumbs-up mr-2"></i> @lang('Return Delivered')
																						</a>
																					@endif

																					@if(adminAccessRoute(config('permissionList.Manage_Shipments.Shipment_List.permission.edit')))
																						@if(($shipment->status == 0 || $shipment->status == 1 || $shipment->status == 5 || $shipment->status == 7) && (optional(optional($shipment->senderBranch)->branchManager)->admin_id == $authenticateUser->id || optional(optional($shipment->receiverBranch)->branchManager)->admin_id == $authenticateUser->id || optional($shipment->assignToCollect)->id == $authenticateUser->id || optional($shipment->assignToDelivery)->id == $authenticateUser->id || $authenticateUser->role_id == null) || ($shipment->status == 3 && $shipment->payment_status == 2))
																							<a class="dropdown-item btn-outline-primary text-primary btn-sm"
																							   href="{{ route('editShipment', ['id' => $shipment->id, 'shipment_identifier' => $shipment->shipment_identifier, 'segment' => $status, 'shipment_type' => 'operator-country']) }}"><i
																									class="fa fa-edit mr-2"
																									aria-hidden="true"></i> @lang('Edit')
																							</a>
																						@endif
																					@endif

																					<a class="dropdown-item btn-outline-primary btn-sm text-info"
																					   href="{{ route('viewShipment', ['id' => $shipment->id, 'segment' => $status, 'shipment_type' => 'operator-country']) }}"><i
																							class="fa fa-eye mr-2"
																							aria-hidden="true"></i> @lang('Details')
																					</a>

																					@if($shipment->status == 8 && $status == 'return_in_queue')
																						<a data-target="#updateShipmentStatus"
																						   data-toggle="modal"
																						   data-status="return_in_dispatch"
																						   data-route="{{route('updateShipmentStatus', ['id' => $shipment->id, 'type' => 'return_in_dispatch'])}}"
																						   href="javascript:void(0)"
																						   class="dropdown-item btn-outline-primary text-warning btn-sm editShipmentStatus"><i
																								class="fas fa-file-invoice mr-2"></i> @lang('Dispatch Return')
																						</a>
																					@endif

																					@if($shipment->status == 0 || $shipment->status == 5)
																						<a data-target="#acceptShipmentRequest"
																						   data-toggle="modal"
																						   data-route="{{route('acceptShipmentRequest', $shipment->id)}}"
																						   href="javascript:void(0)"
																						   class="dropdown-item btn-outline-primary text-purple btn-sm acceptShipmentRequest"><i
																								class="fas fa-check"></i> @lang('Accept Shipment')
																						</a>
																					@endif
																					@if($shipment->status == 0 || $shipment->status == 5 || $shipment->status == 1)
																						<a data-target="#cancelShipmentRequest"
																						   data-toggle="modal"
																						   data-route="{{route('cancelShipmentRequest', $shipment->id)}}"
																						   data-property="{{ $shipment }}"
																						   href="javascript:void(0)"
																						   class="dropdown-item btn-outline-primary text-danger btn-sm cancelShipmentRequest"><i
																								class="fas fa-ban"></i> @lang('Cancel Shipment')
																						</a>
																					@endif
																					@if(($shipment->shipment_type == 'pickup') && ($shipment->status == 0 || $shipment->status == 1) && (optional(optional($shipment->senderBranch)->branchManager)->admin_id == $authenticateUser->id || $authenticateUser->role_id == null))
																						<a data-target="#assignToCollectShipmentRequest"
																						   data-toggle="modal"
																						   data-route="{{route('assignToCollectShipmentRequest', $shipment->id)}}"
																						   data-property="{{ $shipment }}"
																						   href="javascript:void(0)"
																						   class="dropdown-item btn-outline-primary text-info btn-sm assignToCollectShipmentRequest"><i
																								class="fas fa-check"></i> @lang('Assign To Collect')
																						</a>
																					@endif

																					@if(adminAccessRoute(config('permissionList.Manage_Shipments.Shipment_List.permission.delete')))
																						@if(($shipment->status == 6 && $shipment->shipment_cancel_time != null && $shipment->refund_time == null) || (($shipment->shipment_type != 'condition') && ($shipment->status == 4 || $shipment->status == 11) ))
																							<a data-target="#deleteShipment"
																							   data-toggle="modal"
																							   data-route="{{route('deleteShipment', $shipment->id)}}"
																							   href="javascript:void(0)"
																							   class="dropdown-item btn-outline-primary text-danger btn-sm deleteShipment"><i
																									class="fas fa-trash mr-2"></i> @lang('Delete')
																							</a>
																						@elseif(($shipment->shipment_type == 'condition' && $shipment->condition_amount_payment_confirm_to_sender == 0 && $shipment->status == 4) && (optional(optional($shipment->senderBranch)->branchManager)->admin_id == $authenticateUser->id || $authenticateUser->role_id == null))
																							<a data-target="#payConditionShipmentToSender"
																							   data-toggle="modal"
																							   data-property="{{ $shipment }}"
																							   data-basic="{{ $basic }}"
																							   data-route="{{route('payConditionShipmentToSender', $shipment->id)}}"
																							   href="javascript:void(0)"
																							   class="dropdown-item btn-outline-primary text-primary btn-sm payConditionShipmentToSender"><i
																									class="fab fa-cc-amazon-pay mr-2"></i> @lang('Payment Now')
																							</a>
																						@elseif(($shipment->shipment_type == 'condition' && $shipment->status == 4 && $shipment->condition_amount_payment_confirm_to_sender == 1) || ($shipment->shipment_type == 'condition' && $shipment->status == 11))
																							<a data-target="#deleteShipment"
																							   data-toggle="modal"
																							   data-route="{{route('deleteShipment', $shipment->id)}}"
																							   href="javascript:void(0)"
																							   class="dropdown-item btn-outline-primary text-danger btn-sm deleteShipment"><i
																									class="fas fa-trash mr-2"></i> @lang('Delete')
																							</a>
																						@endif
																					@endif
																				</div>
																			</div>
																		</div>
																		{{--																				</div>--}}
																	</td>
																</tr>
															@empty
																<tr>
																	<td colspan="100%" class="text-center">
																		<img class="not-found-img"
																			 src="{{ asset('assets/dashboard/images/empty-state.png') }}"
																			 alt="">
																		<p class="text-center no-data-found-text">@lang('No Shipments Found')</p>
																	</td>
																</tr>
															@endforelse
															</tbody>
														</table>
													</div>
													{{ $allShipments->links() }}
												</div>
											</div>

											{{-- Table --}}
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

	{{-- Pay condition shipment to sender modal --}}
	<div id="payConditionShipmentToSender" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Confirmation')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="payConditionShipmentToSenderForm">
					@csrf
					@method('put')
					<div class="modal-body">
						<p class="dueConditionPaymentAlert"></p>
						<h6 class="conditionPayableAmount"></h6>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('No')</button>
						<button type="submit" class="btn btn-primary">@lang('Yes')</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	@include('admin.partials.manageShipmentModal')

@endsection

@push('extra_scripts')
	<script src="{{ asset('assets/dashboard/js/flatpickr.js') }}"></script>
@endpush

@section('scripts')
	@include('admin.partials.manageShipmentJs')
	<script>
		'use strict'
		$(".flatpickr").flatpickr({
			wrap: true,
			altInput: true,
			dateFormat: "Y-m-d H:i",
			maxDate: new Date(new Date().getTime() + 24 * 60 * 60 * 1000) // today + 1 day
		});

		$(document).on('click', '.payConditionShipmentToSender', function () {
			let dataRoute = $(this).data('route');
			let dataProperty = $(this).data('property');
			let dataBasic = $(this).data('basic');
			$('#payConditionShipmentToSenderForm').attr('action', dataRoute);
			$('.dueConditionPaymentAlert').text(`Do you want to confirm the due payment of condition shipment to ${dataProperty.sender.name}`);
			$('.conditionPayableAmount').text(`Payable Amount: ${dataBasic.currency_symbol}${dataProperty.receive_amount}`);


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
