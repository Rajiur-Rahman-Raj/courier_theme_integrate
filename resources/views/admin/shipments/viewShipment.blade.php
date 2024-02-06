@extends('admin.layouts.master')
@section('page_title', __('View Shipment'))

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Shipment Details')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">
						<a href="{{ route('admin.home') }}">@lang('Shipment List')</a>
					</div>
					<div class="breadcrumb-item">@lang('Details')</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-body shadow">
							<div class="d-flex justify-content-between align-items-center">
								<h4 class="card-title">@lang("Shipment Details")</h4>

								<div>
									<a href="{{route('shipmentList', ['shipment_status' => $status, 'shipment_type' => $shipment_type])}}"
									   class="btn btn-sm  btn-primary mr-2">
										<span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
									</a>
									<button class="btn btn-success btn-sm" id="shipmentDetailsPrint"><i
											class="far fa-check-circle"></i> @lang('Print')
									</button>
								</div>
							</div>
							<hr>
							<div class="p-4 card-body shadow" id="shipmentDetails">
								<div class="row">
									<div class="col-md-4 border-right">
										<ul class="list-style-none shipment-view-ul">
											<li class="my-2 border-bottom-2 pb-3">
												<span class="font-weight-medium text-dark custom-text"> <i
														class="fas fa-fingerprint mr-2 text-orange "></i> @lang("Shipment Id"): <small
														class="float-right custom-text"> #{{ $singleShipment->shipment_id }} </small></span>

											</li>

											<li class="my-3">
                                            <span class="font-weight-bold text-dark"><i
													class="fas fa-shipping-fast mr-2 text-primary"></i> @lang("Shipment Type") : <span
													class="font-weight-medium">@lang($singleShipment->shipmentTypeFormat())</span></span>
											</li>

											<li class="my-3">
                                            <span class="font-weight-bold text-dark"><i
													class="far fa-calendar-alt mr-2 text-success"></i> @lang("Shipment Date") : <span
													class="font-weight-medium">{{ customDate($singleShipment->shipment_date) }}</span></span>
											</li>

											<li class="my-3">
                                            <span class="font-weight-bold text-dark"><i
													class="far fa-calendar-alt mr-2 text-purple"></i> @lang("Estimate Delivery Date") : <span
													class="font-weight-medium">{{ customDate($singleShipment->delivery_date) }}</span></span>
											</li>

											<li class="my-3 d-flex align-items-center">
												<span class="font-weight-bold text-dark"><i
														class="fas fa-user-plus mr-2 text-primary"></i> @lang('Sender :')</span>

												<a class="ml-3 text-decoration-none"
												   href="{{ route('user.edit',optional($singleShipment->sender)->id) }}">
													<span
														class="font-weight-bold">@lang(optional($singleShipment->sender)->name)</span>
												</a>
											</li>

											<li class="my-3 d-flex align-items-center">
												<span class="font-weight-bold text-dark"> <i
														class="fas fa-user-minus mr-2 text-orange "></i> @lang('Receiver :')</span>

												<a class="ml-3 text-decoration-none"
												   href="{{ route('user.edit',optional($singleShipment->receiver)->id) }}">
													<span
														class="font-weight-bold">@lang(optional($singleShipment->receiver)->name)</span>
												</a>
											</li>

											@if($singleShipment->shipment_type == 'condition')
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"><i
													class="fas fa-check-circle mr-2 text-primary"></i> @lang("Receive Amount") : <span
													class="font-weight-medium">{{ $basic->currency_symbol }}{{ $singleShipment->receive_amount }}</span></span>
												</li>
											@endif

											<li class="my-3 d-flex align-items-center">
												<span class="font-weight-bold text-dark"> <i
														class="fas fa-tree mr-2 text-purple"></i> @lang('Sender Branch') : </span>

												<a class="ml-3 text-decoration-none"
												   href="{{ route('branchEdit', optional($singleShipment->senderBranch)->id) }}">
													<span
														class="font-weight-bold">@lang(optional($singleShipment->senderBranch)->branch_name)</span>
												</a>
											</li>

											<li class="my-3 d-flex align-items-center">
												<span class="font-weight-bold text-dark"> <i
														class="fas fa-tree mr-2 text-info"></i> @lang('Receiver Branch') : </span>

												<a class="ml-3 text-decoration-none"
												   href="{{ route('branchEdit', optional($singleShipment->receiverBranch)->id) }}">
													<span
														class="font-weight-bold">@lang(optional($singleShipment->receiverBranch)->branch_name)</span>
												</a>

											</li>


											@if($singleShipment->from_country_id != null)
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"> <i
													class="fas fa-map-marker-alt mr-2 text-primary"></i> @lang("From Country") : <span
													class="font-weight-medium">@lang(optional($singleShipment->fromCountry)->name)</span></span>
												</li>
											@endif

											@if($singleShipment->from_state_id != null)
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"><i
													class="fas fa-map-marker-alt mr-2 text-primary"></i> @lang("From State") : <span
													class="font-weight-medium">@lang(optional($singleShipment->fromState)->name)</span></span>
												</li>
											@endif

											@if($singleShipment->from_city_id != null)
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"><i
													class="fas fa-map-marker-alt mr-2 text-danger"></i> @lang("From City") : <span
													class="font-weight-medium">@lang(optional($singleShipment->fromCity)->name)</span></span>
												</li>
											@endif

											@if($singleShipment->from_area_id != null)
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"><i
													class="fas fa-map-marker-alt mr-2 text-success"></i> @lang("From Area") : <span
													class="font-weight-medium">@lang(optional($singleShipment->fromArea)->name)</span></span>
												</li>
											@endif

											@if($singleShipment->to_country_id != null)
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"><i
													class="fas fa-map-marker-alt mr-2 text-cyan"></i> @lang("To Country") : <span
													class="font-weight-medium">@lang(optional($singleShipment->toCountry)->name)</span></span>
												</li>
											@endif

											@if($singleShipment->to_state_id != null)
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"><i
													class="fas fa-map-marker-alt mr-2 text-success "></i> @lang("To State") : <span
													class="font-weight-medium">@lang(optional($singleShipment->toState)->name)</span></span>
												</li>
											@endif

											@if($singleShipment->to_city_id != null)
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"><i
													class="fas fa-map-marker mr-2 text-purple"></i> @lang("To City") : <span
													class="font-weight-medium">@lang(optional($singleShipment->toCity)->name)</span></span>
												</li>
											@endif

											@if($singleShipment->to_area_id != null)
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"> <i
													class="fas fa-location-arrow mr-2 text-primary"></i> @lang("To Area") : <span
													class="font-weight-medium">@lang(optional($singleShipment->toArea)->name)</span></span>
												</li>
											@endif

											<li class="my-3">
                                            <span class="font-weight-bold text-dark">  <i
													class="fas fa-search-dollar  mr-2 text-orange"></i> @lang("Payment Type") : <span
													class="font-weight-medium">@lang($singleShipment->payment_type)</span></span>
											</li>

											<li class="my-3">
                                            <span class="font-weight-bold text-dark">  <i
													class="fas fa-file-invoice-dollar mr-2 text-purple"></i> @lang("Payment By") : <span
													class="font-weight-medium">{{ $singleShipment->payment_by == 1 ? 'Sender' : 'Receiver' }}</span></span>
											</li>

											<li class="my-3">
                                            <span class="font-weight-bold text-dark"><i
													class="fas fa-money-check-alt  mr-2 text-primary"></i> @lang('Payment Status') :
                                                @if($singleShipment->payment_status == 1)
													<p class="badge badge-success rounded">@lang('Paid')</p>
												@else
													<p class="badge badge-warning rounded">@lang('Unpaid')</p>
												@endif
                                            </span>
											</li>


											<li class="my-3">
                                            <span class="font-weight-bold text-dark"><i
													class="fas fa-shipping-fast mr-2 text-warning"></i> @lang('Shipment Status') :
												@if(($singleShipment->status == 0) || ($singleShipment->status == 5 && $singleShipment->assign_to_collect != null))
													<p class="badge badge-dark rounded">@lang('Requested')</p>
												@elseif($singleShipment->status == 6)
													<p class="badge badge-danger rounded">@lang('Canceled')</p>
												@elseif($singleShipment->status == 1)
													<p class="badge badge-info rounded">@lang('In Queue')</p>
												@elseif($singleShipment->status == 2 && $status == 'dispatch')
													<p
														class="badge badge-warning rounded">@lang('Dispatch')</p>
												@elseif($singleShipment->status == 2 && $status == 'upcoming')
													<p
														class="badge badge-primary rounded">@lang('Upcoming')</p>
												@elseif(($singleShipment->status == 3) || ($singleShipment->status == 7 && $singleShipment->assign_to_delivery != null))
													<p
														class="badge badge-success rounded">@lang('Received')</p>
												@elseif($singleShipment->status == 4)
													<p
														class="badge badge-danger rounded">@lang('Delivered')</p>
												@elseif($singleShipment->status == 8)
													<p
														class="badge badge-info rounded">@lang('Return In Queue')</p>
												@elseif($singleShipment->status == 9 && $status == 'return_in_dispatch')
													<p
														class="badge badge-warning rounded">@lang('Return In Dispatch')</p>
												@elseif($singleShipment->status == 9 && $status == 'return_in_upcoming')
													<p
														class="badge badge-primary rounded">@lang('Return In Dispatch')</p>
												@elseif($singleShipment->status == 10 && $status == 'return_in_received')
													<p
														class="badge badge-success rounded">@lang('Return Received')</p>
												@elseif($singleShipment->status == 11 && $status == 'return_in_delivered')
													<p
														class="badge badge-danger rounded">@lang('Return Delivered')</p>
												@endif
                                            </span>
											</li>

											@if((optional(optional($singleShipment->senderBranch)->branchManager)->admin_id == $authenticateUser->id || $authenticateUser->role_id == null) && $singleShipment->assign_to_collect != null)
												<li class="my-3">
														<span class="font-weight-bold text-dark"> <i
																class="fas fa-tasks mr-2 text-primary"
																aria-hidden="true"></i> @lang('Assign To Driver') :
															<span
																class="fw-normal">{{ optional($singleShipment->assignToCollect)->name }}</span>
															<span></span>
														</span>
												</li>
											@endif

											@if($singleShipment->status == 6 && $singleShipment->shipment_cancel_time != null && $singleShipment->refund_time != null && $singleShipment->is_refund_complete == 0)
												<li class="my-3">
														<span class="font-weight-bold text-dark"><i
																class="fas fa-money-check-alt  mr-2 text-primary"></i> @lang('Shipment Refund Time') :
															<span
																class="fw-normal">{{ customDateTime($singleShipment->refund_time) }}</span>
															<span></span>
														</span>
												</li>
											@elseif($singleShipment->status == 6 && $singleShipment->shipment_cancel_time != null && $singleShipment->refund_time == null && $singleShipment->is_refund_complete == 1)
												<li class="my-3">
															<span class="font-weight-bold text-dark"><i
																	class="fas fa-dollar-sign mr-2 text-primary"></i> @lang('Shipment Refund Time') :
															<p class="badge badge-primary rounded">@lang('Refund Given')</p>
															<span></span>
														</span>
												</li>
											@endif

											@if($singleShipment->dispatch_time != null)
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"> <i
													class="far fa-clock mr-2 text-info"></i> @lang("Dispatched Time") : <span
													class="font-weight-medium">{{ customDateTime($singleShipment->dispatch_time) }}</span></span>
												</li>
											@endif

											@if($singleShipment->receive_time != null)
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"> <i
													class="far fa-clock mr-2 text-info"></i> @lang("Received Time") : <span
													class="font-weight-medium">{{ customDateTime($singleShipment->receive_time) }}</span></span>
												</li>
											@endif

											@if($singleShipment->delivered_time == null && $singleShipment->return_dispatch_time != null)
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"> <i
													class="far fa-clock mr-2 text-info"></i> @lang("Return Dispatched Time") : <span
													class="font-weight-medium">{{ customDateTime($singleShipment->return_dispatch_time) }}</span></span>
												</li>
											@endif

											@if($singleShipment->delivered_time == null && $singleShipment->return_receive_time != null)
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"> <i
													class="far fa-clock mr-2 text-info"></i> @lang("Return Received Time") : <span
													class="font-weight-medium">{{ customDateTime($singleShipment->return_receive_time) }}</span></span>
												</li>
											@endif

											@if(($singleShipment->return_receive_time != null && $singleShipment->payment_status == 2) && ($singleShipment->return_shipment_cost != 0 || $singleShipment->return_shipment_cost != null))
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"> <i
													class="far fa-clock mr-2 text-info"></i> @lang("Return Shipment Cost") : <span
													class="font-weight-medium">{{ $basic->currency_symbol }}{{ $singleShipment->return_shipment_cost }}</span> (@lang('Due'))</span>
												</li>
											@endif

											@if($singleShipment->return_delivered_time != null && $singleShipment->status == 11)
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"> <i
													class="far fa-clock mr-2 text-info"></i> @lang("Return Delivered Time") : <span
													class="font-weight-medium">{{ customDateTime($singleShipment->return_delivered_time) }}</span></span>
												</li>
											@endif

											@if($singleShipment->status == 7 && optional(optional($singleShipment->receiverBranch)->branchManager)->admin_id == $authenticateUser->id && $singleShipment->assign_to_delivery != null)
												<li class="my-3">
														<span class="font-weight-bold text-dark"> <i
																class="fas fa-tasks mr-2 text-primary"
																aria-hidden="true"></i> @lang('Assign To Driver') :
															<span
																class="fw-normal">{{ optional($singleShipment->assignToDelivery)->name }}</span>
															<span></span>
														</span>
												</li>
											@endif

											@if($singleShipment->delivered_time != null)
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"> <i
													class="far fa-clock mr-2 text-info"></i> @lang("Delivered Time") : <span
													class="font-weight-medium">{{ customDateTime($singleShipment->delivered_time) }}</span></span>
												</li>
											@endif

											@if(($singleShipment->shipment_type == 'condition' && $singleShipment->status == 4) && (optional(optional($singleShipment->senderBranch)->branchManager)->admin_id == $authenticateUser->id || $authenticateUser->role_id == null))
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"> <i
													class="far fa-clock mr-2 text-info"></i> @lang("Payment to Sender") : <p
													class="badge badge-{{ $singleShipment->condition_amount_payment_confirm_to_sender == 0 ? 'warning' : 'success' }} rounded"> {{ $singleShipment->condition_amount_payment_confirm_to_sender == 0 ? 'Due' : 'Complete' }}</p>
											</span>
												</li>
											@endif

											@if(($singleShipment->shipment_type == 'condition' && $singleShipment->status == 4) && (optional(optional($singleShipment->senderBranch)->branchManager)->admin_id == $authenticateUser->id || $authenticateUser->role_id == null) && ($singleShipment->condition_payment_time != null))
												<li class="my-3">
                                            <span class="font-weight-bold text-dark"> <i
													class="far fa-clock mr-2 text-info"></i> @lang("Payment Given Time") : <span
													class="font-weight-medium">{{ customDateTime($singleShipment->condition_payment_time) }}</span></span>
												</li>
											@endif
										</ul>
									</div>


									<div class="col-md-8">
										<ul class="list-style-none shipment-view-ul">
											<li class="my-2 border-bottom-2 pb-3">
												<span class="font-weight-bold text-dark"> <i
														class="fas fa-cart-plus mr-2 text-primary"></i> @lang('Parcel Information')</span>
											</li>

											@if($singleShipment->packing_services != null)
												<li class="my-3">
                                            <span class="custom-text"><i
													class="fas fa-check-circle mr-2 text-success"></i>  @lang('Packing Service')

                                            </span>
												</li>

												<table class="table table-bordered">
													<thead>
													<tr>
														<th scope="col">@lang('Package')</th>
														<th scope="col">@lang('Variant')</th>
														<th scope="col">@lang('Price')</th>
														<th scope="col">@lang('Quantity')</th>
														<th scope="col">@lang('Cost')</th>
													</tr>
													</thead>
													<tbody>
													@php
														$totalPackingCost = 0;
													@endphp
													@foreach($singleShipment->packing_services as $packing_service)

														<tr>
															<td>{{ $singleShipment->packageName($packing_service['package_id'])  }}</td>
															<td>{{ $singleShipment->variantName($packing_service['variant_id']) }}</td>
															<td>{{ $basic->currency_symbol }}{{ $packing_service['variant_price'] }}</td>
															<td>{{ $packing_service['variant_quantity'] }}</td>
															<td>{{ $basic->currency_symbol }}{{ $packing_service['package_cost'] }}</td>
															@php
																$totalPackingCost += $packing_service['package_cost'];
															@endphp
														</tr>
													@endforeach

													<tr>
														<th colspan="4" class="text-right">@lang('Total Price')</th>
														<td>{{ $basic->currency_symbol }}{{ number_format($totalPackingCost, 2) }}</td>
													</tr>
													</tbody>
												</table>
											@endif

											@if($singleShipment->parcel_information != null)
												<li class="my-3">
													<span class="custom-text"><i
															class="fas fa-check-circle mr-2 text-success"></i>  @lang('Parcel Details') </span>
												</li>

												<table class="table table-bordered">
													<thead>
													<tr>
														<th scope="col">@lang('Parcel Name')</th>
														<th scope="col">@lang('Quantity')</th>
														<th scope="col">@lang('Parcel Type')</th>
														<th scope="col">@lang('Total Unit')</th>
														<th scope="col">@lang('Cost')</th>
													</tr>
													</thead>
													<tbody>
													@php
														$totalParcelCost = 0;
													@endphp
													@foreach($singleShipment->parcel_information as $parcel_information)
														<tr>
															<td>{{ $parcel_information['parcel_name'] }}</td>
															<td>{{ $parcel_information['parcel_quantity'] }}</td>
															<td>{{ $singleShipment->parcelType($parcel_information['parcel_type_id'])  }}</td>
															<td>{{ $parcel_information['total_unit'] }} <span
																	class="">{{ $singleShipment->parcelUnit($parcel_information['parcel_unit_id']) }}</span>
															</td>
															<td>{{ $basic->currency_symbol }}{{ $parcel_information['parcel_total_cost'] }}</td>
															@php
																$totalParcelCost += $parcel_information['parcel_total_cost'];
															@endphp
														</tr>
													@endforeach

													<tr>
														<th colspan="4" class="text-right">@lang('Total Price')</th>
														<td>{{ $basic->currency_symbol }}{{ number_format($totalParcelCost, 2) }}</td>
													</tr>
													</tbody>
												</table>
											@else
												<li class="my-3">
													<span class="custom-text"><i
															class="fas fa-check-circle mr-2 text-success"></i>  @lang('Parcel Details')</span>
												</li>
												<table class="table table-bordered mb-5">
													<tbody>
													<tr>
														<td>@lang($singleShipment->parcel_details)</td>
													</tr>
													</tbody>
												</table>
											@endif


											@if(sizeof($singleShipment->shipmentAttachments) != 0)
												<li class="my-3">
													<span class="custom-text"><i
															class="fas fa-check-circle mr-2 text-success"></i>  @lang('shipment Attachments')</span>
												</li>

												<div class="row">

													<div class="col-sm-12">
														<div class="card">
															<div class="card-body">
																<div class="row shipmentAttachments">
																	@foreach($singleShipment->shipmentAttachments as $attachment)
																		<div class="col-md-4">
																			<div class="imgWrap mb-3">
																				<img class="card-img-top"
																					 src="{{ getFile($attachment->driver, $attachment->image) }}"
																					 alt="Card image cap">
																			</div>
																		</div>
																	@endforeach
																</div>
															</div>
														</div>
													</div>
												</div>
											@endif


											<li class="my-2 border-bottom-2 pb-3">
												<span class="font-weight-bold text-dark"> <i
														class="fas fa-credit-card mr-2 text-primary"></i> @lang('Payment Calculation')</span>
											</li>
											<li class="my-3">
                                            <span class="custom-text"> <i
													class="fas fa-dollar-sign mr-2 text-primary"></i> @lang('Sub Total') :
												<span
													class="font-weight-medium">{{ $basic->currency_symbol }}@lang($singleShipment->sub_total)</span>

                                            </span>
											</li>

											@if($singleShipment->discount != null)
												<li class="my-3 ">
                                            <span class="custom-text"><i
													class="fas fa-dollar-sign mr-2 text-warning"></i>  @lang('Discount') :
												<span
													class="font-weight-medium">{{ $basic->currency_symbol }}{{$singleShipment->discount_amount}}</span>

                                            </span>
												</li>
											@endif


											@if($singleShipment->shipment_type == 'pickup')
												<li class="my-3">
                                            <span class="custom-text"><i
													class="fas fa-dollar-sign mr-2 text-success"></i>  @lang('Pickup Cost') :
												<span
													class="font-weight-medium">{{ $basic->currency_symbol }}@lang($singleShipment->pickup_cost)</span>

                                            </span>
												</li>

												<li class="my-3">
                                            <span class="custom-text"><i
													class="fas fa-dollar-sign mr-2 text-danger"></i>  @lang('Supply Cost') :
												<span
													class="font-weight-medium">{{ $basic->currency_symbol }}@lang($singleShipment->supply_cost)</span>

                                            </span>
												</li>
											@endif

											<li class="my-3">
                                            <span class="custom-text"><i
													class="fas fa-dollar-sign mr-2 text-purple"></i>  @lang('Shipping Cost') :
												<span
													class="font-weight-medium">{{ $basic->currency_symbol }}@lang($singleShipment->shipping_cost)</span>

                                            </span>
											</li>

											<li class="my-3">
                                            <span class="custom-text"><i
													class="fas fa-dollar-sign mr-2 text-orange"></i>  @lang('Tax') :
												<span
													class="font-weight-medium">{{ $basic->currency_symbol }}@lang($singleShipment->tax)</span>

                                            </span>
											</li>

											<li class="my-3">
                                            <span class="custom-text"><i class="fas fa-dollar-sign mr-2 text-info"></i>  @lang('Insurance') :
												<span
													class="font-weight-medium">{{ $basic->currency_symbol }}@lang($singleShipment->insurance)</span>

                                            </span>
											</li>

											<li class="my-3">
                                            <span class="custom-text"><i
													class="fas fa-dollar-sign mr-2 text-primary"></i>  @lang('Payable Amount') :
												<span
													class="custom-text text-warning">{{ $basic->currency_symbol }}@lang($singleShipment->total_pay)</span>
                                            </span>
											</li>
										</ul>
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
	<script>
		'use strict'

		$(document).on('click', '#shipmentDetailsPrint', function () {
			let allContents = document.getElementById('body').innerHTML;
			let printContents = document.getElementById('shipmentDetails').innerHTML;
			document.getElementById('body').innerHTML = printContents;
			window.print();
			document.getElementById('body').innerHTML = allContents;
		})

	</script>
@endsection

