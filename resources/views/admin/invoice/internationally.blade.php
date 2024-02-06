@extends('admin.layouts.master')
@section('page_title', __('Shipment Invoice'))

@section('content')

	<div class="main-content">
		<section class="section">

			<div class="section-header">
				<h1> @lang('Shipment Invoice') </h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active"><a href="{{ route('admin.home') }}">@lang("Dashboard")</a></div>
					<div class="breadcrumb-item">@lang('Shipment Invoice')</div>
				</div>
			</div>
		</section>

		<div class="section-invoice section-1">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="invoice-box" id="shipmentInvoice">
							<div class="invoice-logo text-center">
								<h3>@lang(config('basic.site_title'))</h3>
								<div class="invoice-img">
									<img
										src="http://127.0.0.1/courier/courier-management-master/assets/upload/logo/logo.png"
										alt="">
								</div>
							</div>
							<div class="invoice-date-box d-flex flex-wrap justify-content-between mt-5">
								<div class="invoice-number">
									<h4>@lang('Shipment Type'): <span
											class="">@lang($singleShipment->shipmentTypeFormat())</span></h4>
									<h4>@lang('Shipment Date'):
										<span>{{ customDate($singleShipment->shipment_date) }}</span></h4>
									<h4>@lang('Estimate Delivery Date'):
										<span>{{ customDate($singleShipment->delivery_date) }}</span></h4>
								</div>
								<div class="invoice-id">
									<h4>@lang('Shipment id'): <span
											class="text-dark">{{ $singleShipment->shipment_id }}</span></h4>
									<h4>@lang('Payment Status'): <span
											class="{{ $singleShipment->payment_status == 1 ? 'paid' : 'unpaid' }}">
											@if($singleShipment->payment_status == 1)
												@lang('Paid')
											@else
												@lang('Unpaid')
											@endif
										</span></h4>
									<h4>@lang('Sander At Branch'):
										<span>@lang(optional($singleShipment->senderBranch)->branch_name)</span></h4>
									<h4>@lang('Received At Branch'):
										<span>@lang(optional($singleShipment->receiverBranch)->branch_name)</span></h4>
								</div>
							</div>
							<div class="invoice-location">
								<div class="invoice-location-box d-flex flex-wrap justify-content-between">
									<div class="invoice-from">
										<h5>@lang('From')</h5>
										<h5><strong>@lang(optional($singleShipment->sender)->name)</strong></h5>
										@if($singleShipment->from_country_id != null)
											<h5>@lang('Country'): @lang(optional($singleShipment->fromCountry)->name)
												<span></span></h5>
										@endif
										@if($singleShipment->from_state_id != null)
											<h5>@lang('State'):
												<span>@lang(optional($singleShipment->fromState)->name)</span></h5>
										@endif
										@if($singleShipment->from_city_id != null)
											<h5>@lang('City'):
												<span>@lang(optional($singleShipment->fromCity)->name)</span></h5>
										@endif
										@if($singleShipment->from_area_id != null)
											<h5>@lang('Area'):
												<span>@lang(optional($singleShipment->fromArea)->name)</span></h5>
										@endif
										@if(optional(optional($singleShipment->sender)->profile)->address)
											<h5>@lang('Address'):
												<span>@lang(optional(optional($singleShipment->sender)->profile)->address)</span>
											</h5>
										@endif
										@if(optional(optional($singleShipment->sender)->profile)->phone)
											<h5>@lang('Phone'):
												<span>@lang(optional(optional($singleShipment->sender)->profile)->phone)</span>
											</h5>
										@endif
									</div>
									<div class="invoice-to">
										<h5>@lang('To')</h5>
										<h5><strong>@lang(optional($singleShipment->receiver)->name)</strong></h5>
										@if($singleShipment->to_country_id != null)
											<h5>@lang('Country'):
												<span>@lang(optional($singleShipment->toCountry)->name)</span></h5>
										@endif
										@if($singleShipment->to_state_id != null)
											<h5>@lang('State'):
												<span>@lang(optional($singleShipment->toState)->name)</span></h5>
										@endif
										@if($singleShipment->to_city_id != null)
											<h5>@lang('City'):
												<span>@lang(optional($singleShipment->toCity)->name)</span></h5>
										@endif
										@if($singleShipment->to_area_id != null)
											<h5>@lang('Area'):
												<span>@lang(optional($singleShipment->toArea)->name)</span></h5>
										@endif
										@if(optional(optional($singleShipment->receiver)->profile)->address)
											<h5>@lang('Address'):
												<span>@lang(optional(optional($singleShipment->receiver)->profile)->address)</span>
											</h5>
										@endif
										@if(optional(optional($singleShipment->receiver)->profile)->phone)
											<h5>@lang('Phone'):
												<span>@lang(optional(optional($singleShipment->receiver)->profile)->phone)</span>
											</h5>
										@endif
									</div>
								</div>
							</div>
							@if($singleShipment->packing_services != null)
								<div class="invoice-table">
									<h3>@lang('Packing Service')</h3>
									<table class="table  table-hover">
										<thead>
										<th scope="col">@lang('Package')</th>
										<th scope="col">@lang('Variant')</th>
										<th scope="col">@lang('Price')</th>
										<th scope="col">@lang('Quantity')</th>
										<th scope="col">@lang('Cost')</th>
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

											<td colspan="4" class="t-price">@lang('Total Price')</td>
											<td>{{ $basic->currency_symbol }}{{ number_format($totalPackingCost, 2) }}</td>
										</tr>
										</tbody>
									</table>
								</div>
							@endif

							@if($singleShipment->parcel_information != null)
								<div class="invoice-table">
									<h3>@lang('Parcel Details')</h3>
									<table class="table  table-hover">
										<thead>
										<th scope="col">@lang('Parcel Name')</th>
										<th scope="col">@lang('Quantity')</th>
										<th scope="col">@lang('Parcel Type')</th>
										<th scope="col">@lang('Total Unit')</th>
										<th scope="col">@lang('Cost')</th>
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
											<td colspan="4" class="t-price">@lang('Total Price')</td>
											<td data-label="Total price">{{ $basic->currency_symbol }}{{ number_format($totalParcelCost, 2) }}</td>
										</tr>
										</tbody>
									</table>
								</div>
							@endif

							<div class="invoice-table">
								<h3>@lang('Payment Calculation')</h3>
								<table class="table  table-hover">
									{{--									<thead class="">--}}
									{{--									<th>#</th>--}}
									{{--									<th>couriur type</th>--}}
									{{--									<th>sending time</th>--}}
									{{--									<th>price</th>--}}
									{{--									<th>qty</th>--}}
									{{--									<th>subtotal</th>--}}
									{{--									</thead>--}}
									<tbody>
									<tr>
										<td class="t-total" colspan="5">@lang('Subtotal'):</td>
										<td class="text-right" data-label="subtotal">{{ $basic->currency_symbol }}{{ $singleShipment->sub_total }}</td>
									</tr>

									@if($singleShipment->discount != null)
										<tr>
											<td class="t-total" colspan="5">@lang('Discount'):</td>
											<td class="text-right" data-label="discount">{{ $basic->currency_symbol }} {{ $singleShipment->discount_amount }}</td>
										</tr>
									@endif

									@if($singleShipment->shipment_type == 'pickup')
										<tr>
											<td class="t-total" colspan="5">@lang('Pickup Cost'):</td>
											<td class="text-right" data-label="total">{{ $basic->currency_symbol }}{{ $singleShipment->pickup_cost }}</td>
										</tr>

										<tr>
											<td class="t-total" colspan="5">@lang('Supply Cost'):</td>
											<td class="text-right" data-label="total">{{ $basic->currency_symbol }}{{ $singleShipment->supply_cost }}</td>
										</tr>
									@endif

									<tr>
										<td class="t-total" colspan="5">@lang('Shipping Cost'):</td>
										<td class="text-right" data-label="total">{{ $basic->currency_symbol }}{{ $singleShipment->shipping_cost }}</td>
									</tr>

									<tr>
										<td class="t-total" colspan="5">@lang('Tax'):</td>
										<td class="text-right" data-label="total">{{ $basic->currency_symbol }}{{ $singleShipment->tax }}</td>
									</tr>

									<tr>
										<td class="t-total" colspan="5">@lang('Insurance'):</td>
										<td class="text-right" data-label="total">{{ $basic->currency_symbol }}{{ $singleShipment->insurance }}</td>
									</tr>

									<tr>
										<td class="t-total" colspan="5">@lang('total'):</td>
										<td class="text-right" data-label="total">{{ $basic->currency_symbol }}{{ $singleShipment->total_pay }}</td>
									</tr>
									</tbody>
								</table>
							</div>
						</div>

						<div class="invoice-btn clearfix">
							<a class="float-right" href="javascript:void(0)" id="shipmentInvoicePrint"><i class="fas fa-print"></i> @lang('Print') </a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script>
		'use strict'
		$(document).on('click', '#shipmentInvoicePrint', function () {
			let allContents = document.getElementById('body').innerHTML;
			let printContents = document.getElementById('shipmentInvoice').innerHTML;
			document.getElementById('body').innerHTML = printContents;
			window.print();
			document.getElementById('body').innerHTML = allContents;
		})

	</script>
@endsection
