@extends($theme.'layouts.user')
@section('page_title',__('Shipment List'))

@push('extra_styles')
	<link href="{{ asset('assets/dashboard/css/flatpickr.min.css') }}" rel="stylesheet">
@endpush

@section('content')
	<div class="container-fluid">
		<div class="main row">
			<div class="col-12">
				<div class="dashboard-heading">
					<div class="">
						<h2 class="mb-0">@lang('Shipment List')</h2>
						<nav aria-label="breadcrumb" class="ms-2">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a
										href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
								<li class="breadcrumb-item"><a href="javascript:void(0)">@lang('Shipment List')</a></li>
							</ol>
						</nav>
					</div>
				</div>

				<div class="search-bar profile-setting">
					<form action="" method="get">
						@include($theme.'user.shipments.searchForm')
					</form>
				</div>


				<div class="card" id="switcherContent">
					<div class="card-body">
						<div class="d-flex justify-content-between flex-wrap mb-5">
							<div class="switcher d-flex flex-wrap">
								<a href="{{ route('user.shipmentList', ['shipment_status' => $status, 'shipment_type' => 'operator-country']) }}">
									<button
										class="@if(lastUriSegment() == 'operator-country') active @endif">@lang(optional(basicControl()->operatorCountry)->name)</button>
								</a>
								<a href="{{ route('user.shipmentList', ['shipment_status' => $status, 'shipment_type' => 'internationally']) }}">
									<button
										class="@if(lastUriSegment() == 'internationally') active @endif">@lang('Internationally')</button>
								</a>
							</div>

							<div class="mt-3">
								<a href="{{route('user.createShipment', ['shipment_type' => 'internationally', 'shipment_status' => $status])}}"
								   class="view_cmn_btn2">
									<i class="fal fa-plus-circle"></i> @lang('Create Shipment')
								</a>
							</div>
						</div>

						<div class="table-parent table-responsive">
							<table class="table table-striped">
								<thead>
								<tr>
									<th scope="col"
										class="custom-text">@lang('SL').
									</th>
									<th scope="col"
										class="custom-text">@lang('Shipment Id')</th>
									<th scope="col"
										class="custom-text">@lang('Shipment Type')</th>
									<th scope="col"
										class="custom-text">@lang('Sender Branch')</th>
									<th scope="col"
										class="custom-text">@lang('Receiver Branch')</th>
									<th scope="col"
										class="custom-text">@lang('From Country')</th>
									<th scope="col"
										class="custom-text">@lang('To Country')</th>
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
										<td data-label="From State"> @lang(optional($shipment->fromCountry)->name) </td>
										<td data-label="To State"> @lang(optional($shipment->toCountry)->name) </td>
										<td data-label="Total Cost"> {{ $basic->currency_symbol }}{{ $shipment->total_pay }} </td>

										<td data-label="Shipment Date"> {{ customDate($shipment->shipment_date) }} </td>

										<td data-label="Status">
											@if($shipment->status == 0)
												<span
													class="badge text-bg-dark">@lang('Requested')</span>
											@elseif($shipment->status == 6)
												<span
													class="badge text-bg-danger">@lang('Canceled')</span>
											@elseif($shipment->status == 5 && $shipment->assign_to_collect != null)
												<span
													class="badge text-bg-primary">@lang('Assigned Driver For Pickup')</span>
											@elseif($shipment->status == 1)
												<span
													class="badge text-bg-info">@lang('In Queue')</span>
											@elseif($shipment->status == 2)
												<span
													class="badge text-bg-warning">@lang('Dispatch')</span>
											@elseif($shipment->status == 3)
												<span
													class="badge text-bg-success">@lang('Received')</span>
											@elseif($shipment->status == 7 && $shipment->assign_to_delivery != null)
												<span
													class="badge text-bg-primary">@lang('Delivery In Queue')</span>
											@elseif($shipment->status == 4)
												<span
													class="badge text-bg-danger">@lang('Delivered')</span>
											@elseif($shipment->status == 8)
												<span
													class="badge text-bg-info">@lang('Return In Queue')</span>
											@elseif($shipment->status == 9)
												<span
													class="badge text-bg-warning">@lang('Return In Dispatch')</span>
											@elseif($shipment->status == 10)
												<span
													class="badge text-bg-success">@lang('Return In Received')</span>
											@elseif($shipment->status == 11)
												<span
													class="badge text-bg-danger">@lang('Return Delivered')</span>
											@endif
										</td>

										<td data-label="@lang('Action')">
											<div class="dropdown">
												<button class="action-btn-secondary" type="button"
														data-bs-toggle="dropdown" aria-expanded="false">
													<i class="fas fa-ellipsis-v"></i>
												</button>
												<ul class="dropdown-menu">
													<li>
														<a class="dropdown-item"
														   href="{{ route('user.viewShipment', ['id' => $shipment->id, 'segment' => $status, 'shipment_type' => 'internationally']) }}">@lang('Details')</a>
													</li>

													@if($shipment->status == 0)
														<li>
															<a class="dropdown-item cancelShipmentRequest"
															   data-bs-toggle="modal"
															   data-route="{{route('user.cancelShipmentRequest', $shipment->id)}}"
															   href="javascript:void(0)"
															   data-property="{{ $shipment }}"
															   data-bs-target="#cancelShipmentRequest">@lang('Cancel Request')</a>
														</li>
													@elseif($shipment->status == 6 && $shipment->shipment_cancel_time != null && $shipment->refund_time == null)
														<li>
															<a class="dropdown-item deleteShipmentRequest"
															   data-bs-toggle="modal"
															   data-route="{{route('user.deleteShipmentRequest', $shipment->id)}}"
															   href="javascript:void(0)"
															   data-bs-target="#deleteShipmentRequest">@lang('Delete')</a>
														</li>
													@endif
												</ul>
											</div>
										</td>

									</tr>
								@empty
									<tr>
										<td colspan="100%" class="text-center p-2 flex-column flex-column">
											<img class="not-found-img"
												 src="{{ asset($themeTrue.'images/business.png') }}"
												 alt="">
											<p class="text-center no-data-found-text">@lang('No Shipments Found')</p>
										</td>
									</tr>
								@endforelse
								</tbody>
							</table>
						</div>
						<nav aria-label="Page navigation example">
							<ul class="pagination justify-content-center">
								{{ $allShipments->appends($_GET)->links() }}
							</ul>
						</nav>
					</div>
				</div>

			</div>
		</div>
	</div>
	<!-- Modal section start -->
	<div class="modal fade" id="cancelShipmentRequest" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
		 aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title" id="staticBackdropLabel">@lang('Confirmation')</h1>
					<button type="button" class="cmn-btn-close modal-close" data-bs-dismiss="modal" aria-label="Close">
						<i class="fa fa-times"></i>
					</button>
				</div>
				<form action="" method="post" id="cancelShipmentRequestForm">
					@csrf
					@method('put')
					<div class="modal-body">
						<div class="mb-5">
							<p>@lang('Are you sure to cancel this shipment request?')</p>
						</div>
						<div class="shipment-refund-alert">

						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="cmn_btn modal-close" data-bs-dismiss="modal">@lang('No')</button>
						<button type="submit" class="cmn_btn2" data-bs-dismiss="modal">@lang('Yes')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Modal section end -->
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
			});

			$(".flatpickr").flatpickr({
				wrap: true,
				altInput: true,
				dateFormat: "Y-m-d H:i",
			});

			$(document).on('click', '.cancelShipmentRequest', function () {
				let dataRoute = $(this).data('route');
				$('#cancelShipmentRequestForm').attr('action', dataRoute);
				let basicControl = @json(basicControl());
				let refundTimeArray = basicControl.refund_time.split("_");
				let refundTime = refundTimeArray[0];
				let refundTimeType = refundTimeArray[1];
				let dataProperty = $(this).data('property');
				let paymentType = dataProperty.payment_type;
				let paymentStatus = dataProperty.payment_status;

				if (paymentType == 'wallet' && paymentStatus == 1) {
					$('.shipment-refund-alert').html(`
						<div class="nb-callout bd-callout-warning m-0 ">
							<i class="fas fa-info-circle mr-2 text-warning"></i>
							N.B: You will get a refund ${refundTime} ${refundTimeType} after canceling your shipment request. Refund charges will be deducted.
						</div>`);
				}
			});

			$(document).on('click', '.modal-close', function () {
				$('.shipment-refund-alert').html('');
			});
		})

	</script>
@endsection

