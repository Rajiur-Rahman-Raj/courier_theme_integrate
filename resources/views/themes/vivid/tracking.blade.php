@extends($theme.'layouts.app')
@section('title', trans('Tracking'))

@section('banner_main_heading')
	@lang('Tracking Order')
@endsection

@section('banner_heading')
	@lang('Tracking')
@endsection

@section('content')
	<!-- tracking_id_area_start -->
	<section class="{{ $shipment == null && $initial != true ? 'tracking_id_area_padding' : '' }}  tracking_id_area">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 mx-auto">
					<div class="tracking_inner text-center">
						<div class="icon_area">
							<i class="fad fa-search-location"></i>
						</div>

						@if(isset($templates['tracking'][0]) && $tracking = $templates['tracking'][0])
							<h3 class="mb-15">@lang($tracking['description']->title)</h3>

							<form action="" method="get" enctype="multipart/form-data">
								@csrf
								<label for="exampleInputEmail1" class="form-label">
									<h5>@lang($tracking['description']->sub_title)</h5></label>
								<div class="mb-3 position-relative d-flex align-items-center">
									<input type="text" name="shipment_id" class="form-control" id="exampleInputEmail1"
										   aria-describedby="emailHelp" placeholder="@lang('Type Shipment Id')"
										   value="{{ old('shipment_id', request()->shipment_id) }}">
									<button type="submit" class="cmn_btn">@lang('Search')</button>
								</div>
							</form>

							@if($shipment == null & $initial == true)
								<figure class="tracking-img">
									<img src="{{ asset($themeTrue.'images/tracking-img.png') }}" alt="">
								</figure>
								<p>Find your parcel using tracking id.</p>
							@endif

						@else
							<h3 class="mb-15">@lang('Tracking Shipment')</h3>
							<form action="" method="get" enctype="multipart/form-data">
								@csrf
								<label for="exampleInputEmail1" class="form-label">
									<h5>@lang('Enter your tracking code')</h5></label>
								<div class="mb-3 position-relative d-flex align-items-center">
									<input type="text" name="shipment_id" class="form-control" id="exampleInputEmail1"
										   aria-describedby="emailHelp" placeholder="@lang('Type Shipment Id')"
										   value="{{ old('shipment_id', request()->shipment_id) }}">
									<button type="submit" class="cmn_btn">@lang('Search')</button>
								</div>
							</form>
						@endif
					</div>
				</div>
			</div>
		</div>
	</section>


	@if($shipment && $initial == false)
		@php
			$shipment_status = $shipment->status;
            $shipment_by = $shipment->shipment_by;
		@endphp

			<!--shipment tracking_area_start -->
		@if($shipment_status == 0 || $shipment_status == 6 || $shipment_status == 1 || $shipment_status == 2 || $shipment_status == 3 || $shipment_status == 4 || $shipment_status == 5)
			<section class="tracking_area">
				<div class="container-fluid">
					<div class="row justify-content-center">
						@if($shipment_by == 1)
							<div class="col-md-2 col-sm-7 col-10 box">
								<div class="cmn_box3 text-center mx-auto">
									<div
										class="icon_area mx-auto {{ ($shipment_status == 0 || $shipment_status == 6 || $shipment_status == 5 || $shipment_status == 1 || $shipment_status == 2 || $shipment_status == 3 || $shipment_status == 4 ? 'active-tracking' : 'inactive-tracking') }} ">
										<i class="fas fa-registered"></i>
									</div>

									<div class="text_area">
										<h5>@lang('Requested')</h5>
									</div>
								</div>
							</div>
						@endif
						@if($shipment_by == 1 && $shipment_status == 6)
							<div class="col-md-2 col-sm-7 col-10 box">
								<div class="cmn_box3 text-center mx-auto">
									<div
										class="icon_area mx-auto {{ ($shipment_status == 0 || $shipment_status == 6 ? 'active-tracking' : 'inactive-tracking') }} ">
										<i class="fas fa-times-circle"></i>
									</div>

									<div class="text_area">
										<h5>@lang('Cancel Shipment')</h5>
									</div>
								</div>
							</div>
						@endif
						<div class="col-md-2 col-sm-7 col-10 box">
							<div class="cmn_box3 text-center">
								<div
									class="icon_area mx-auto {{ ($shipment_status == 1 || ($shipment_status == 6 && $shipment_by == null) || $shipment_status == 2 || $shipment_status == 3 || $shipment_status == 4 ? 'active-tracking' : 'inactive-tracking') }}">
									<i class="fas fa-spinner"></i>
								</div>
								<div class="text_area">
									<h5>@lang('In Queue')</h5>
								</div>
							</div>
						</div>

						@if($shipment_by == null && $shipment_status == 6)
							<div class="col-md-2 col-sm-7 col-10 box">
								<div class="cmn_box3 text-center mx-auto">
									<div
										class="icon_area mx-auto {{ ($shipment_status == 1 || $shipment_status == 6 ? 'active-tracking' : 'inactive-tracking') }} ">
										<i class="far fa-shopping-cart"></i>
									</div>

									<div class="text_area">
										<h5>@lang('Cancel Shipment')</h5>
									</div>
								</div>
							</div>
						@endif

						<div class="col-md-2 col-sm-7 col-10 box">
							<div class="cmn_box3 text-center">
								<div
									class="icon_area mx-auto {{ ($shipment_status == 2 || $shipment_status == 3 || $shipment_status == 4 ? 'active-tracking' : 'inactive-tracking') }}">
									<i class="fal fa-truck"></i>
								</div>
								<div class="text_area">
									<h5>@lang('Order Shipped')</h5>
								</div>
							</div>
						</div>
						<div class="col-md-2 col-sm-7 col-10 box">
							<div class="cmn_box3 text-center">
								<div
									class="icon_area mx-auto {{ ($shipment_status == 3 || $shipment_status == 4 ? 'active-tracking' : 'inactive-tracking') }}">
									<i class="fal fa-hand-receiving"></i>
								</div>
								<div class="text_area">
									<h5>@lang('Order Received')</h5>
								</div>
							</div>
						</div>
						<div class="col-md-2 col-sm-7 col-10 box">
							<div class="cmn_box3 text-center">
								<div
									class="icon_area mx-auto {{ ($shipment_status == 4 ? 'active-tracking' : 'inactive-tracking') }}">
									<i class="far fa-check"></i>
								</div>
								<div class="text_area">
									<h5>@lang('Order Delivered')</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section class="package section-2">
				<div class="container">
					<div class="package-box">
						<div class="package-head">
							<div class="row">
								<div class="col-sm-6">
									<div class="package-title">
										<h1>@lang('Shipment information')</h1>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="track-number">
										<h3>@lang('Tracking Number')</h3>
										<span>{{ $shipment->shipment_id }}</span>
									</div>
								</div>
							</div>
						</div>
						<div class="delivery">
							<div class="row">
								<div class="col-sm-4 ">
									<div class="delivery-date">
										<h4>@lang('Shipment Date')</h4>
										<h4>{{ customDate($shipment->shipment_date) }}</h4>
									</div>
								</div>
								<div class="col-sm-4 ">
									<div class="delivery-time">
										<h4>@lang('Elapsed After Shipment')</h4>
										<h4 class="text-dark elapsed-time">{{ $shipment->shipmentElapsedTime()['difference']->d . ' Day' . ($shipment->shipmentElapsedTime()['difference']->d > 1 ? 's' : '') . ' ' . $shipment->shipmentElapsedTime()['difference']->h . ' Hour' . ($shipment->shipmentElapsedTime()['difference']->h > 1 ? 's' : '') . ' ' . $shipment->shipmentElapsedTime()['difference']->i . ' Minute' . ($shipment->shipmentElapsedTime()['difference']->i > 1 ? 's' : '') }}
										</h4>
									</div>
								</div>
								<div class="col-sm-4 ">
									<div class="delivery-type">
										<h4>@lang('Shipment Type')</h4>
										<h4>{{ formatedShipmentType($shipment->shipment_type) }}</h4>
									</div>
								</div>
							</div>
						</div>

						@if($shipment_status == 0 || $shipment_status == 5)
							<div class="processing">
								<div class="processing-title">
									<h2>@lang('Shipment Status')</h2>
								</div>

								<div class="process d-flex justify-content-center text-center">
									<div class="spinner-grow text-warning" role="status">
										<span class="visually-hidden"></span>
									</div>
									@lang('Requesting')...
								</div>
							</div>
						@elseif($shipment_status == 6)
							<div class="processing shipment_info_area">
								<div class="process d-flex justify-content-center text-center">
									<div class="estmated_received text-center">
										<div class="icon_area">
											<i class="fal fa-times-circle text-danger"></i>
										</div>
										<h5>@lang('Shipment Cancel Date')</h5>
										<h5>{{ customDate($shipment->shipment_cancel_time) }}</h5>

									</div>
								</div>
							</div>
						@elseif($shipment_status == 1)
							<div class="processing">
								<div class="processing-title">
									<h2>@lang('Shipment Status')</h2>
								</div>
								<div class="process d-flex justify-content-center text-center">
									<div class="spinner-border text-warning" role="status">
										<span class="visually-hidden"></span>

									</div>
									@lang('In Queue')...
								</div>
							</div>
						@elseif($shipment_status == 2)
							<div class="payment-details">
								<div class="row">
									<div class="col-md-12">
										<div class="down-icon">
											<i class="fas fa-solid fa-arrow-down "></i>
										</div>
										<div class="location dot-1">
											<h4>@lang(optional($shipment->senderBranch)->branch_name)</h4>
											<h5>@lang(optional($shipment->senderBranch)->address)</h5>
										</div>
										<div class="location dot-2">
											<h4><span>@lang(optional($shipment->receiverBranch)->branch_name)</span>
											</h4>
											<h5><span>@lang(optional($shipment->receiverBranch)->address)</h5>
										</div>
									</div>
								</div>
							</div>

						@elseif($shipment->status == 3 || $shipment->status == 7)
							<div class="processing shipment_info_area">
								<div class="process d-flex justify-content-center text-center">
									<div class="estmated_received text-center">
										<div class="icon_area">
											<i class="fal fa-check-circle"></i>
										</div>
										<h5>@lang('Shipment Received Date')</h5>
										<h5>{{ customDate($shipment->receive_time) }}</h5>
										<div class="btn_area mt-25">
											<button disabled type="button"
													class="cmn_btn">@lang('Ready For Delivery')</button>
										</div>
									</div>
								</div>
							</div>
						@elseif($shipment->status == 4)
							<div class="processing shipment_info_area">
								<div class="process d-flex justify-content-center text-center">
									<div class="estmated_delivery text-center">
										<div class="icon_area">
											<i class="fal fa-check-circle"></i>
										</div>
										<h5>@lang('Shipment Delivery Date')</h5>
										<h5>{{ customDate($shipment->delivered_time) }}</h5>
										<div class="btn_area mt-25">
											<button disabled type="button"
													class="cmn_btn">@lang('Delivery Successfully Completed')</button>
										</div>
									</div>
								</div>
							</div>
						@endif
					</div>
				</div>
			</section>
		@else
			<section class="tracking_area">
				<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-md-2 col-sm-7 col-10 box">
							<div class="cmn_box3 text-center">
								<div
									class="icon_area mx-auto {{ ($shipment_status == 11 ? 'active-tracking' : 'inactive-tracking') }}">
									<i class="far fa-check" aria-hidden="true"></i>
								</div>
								<div class="text_area">
									<h5>@lang('Return Order Delivered')</h5>
								</div>
							</div>
						</div>

						<div class="col-md-2 col-sm-7 col-10 box">
							<div class="cmn_box3 text-center">
								<div
									class="icon_area mx-auto {{ ($shipment_status == 10 || $shipment_status == 11 ? 'active-tracking' : 'inactive-tracking') }}">
									<i class="fal fa-hand-receiving" aria-hidden="true"></i>
								</div>
								<div class="text_area">
									<h5>@lang('Return Order Received')</h5>
								</div>
							</div>
						</div>

						<div class="col-md-2 col-sm-7 col-10 box">
							<div class="cmn_box3 text-center">
								<div
									class="icon_area mx-auto {{ ($shipment_status == 9 || $shipment_status == 10 || $shipment_status == 11 ? 'active-tracking' : 'inactive-tracking') }}">
									<i class="fal fa-truck rotate-custom" aria-hidden="true"></i>
								</div>
								<div class="text_area">
									<h5>@lang('Return Order Shipped')</h5>
								</div>
							</div>
						</div>

						<div class="col-md-2 col-sm-7 col-10 box">
							<div class="cmn_box3 text-center">
								<div
									class="icon_area mx-auto {{ ($shipment_status == 8 || $shipment_status == 9 || $shipment_status == 10 || $shipment_status == 11 ? 'active-tracking' : 'inactive-tracking') }}">
									<i class="fas fa-spinner" aria-hidden="true"></i>
								</div>
								<div class="text_area">
									<h5>@lang('Return In Queue')</h5>
								</div>
							</div>
						</div>

					</div>
				</div>
			</section>
			<section class="package section-2">
				<div class="container">
					<div class="package-box">
						<div class="package-head">
							<div class="row">
								<div class="col-sm-6">
									<div class="package-title">
										<h1>@lang('Shipment information')</h1>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="track-number">
										<h3>@lang('Tracking Number')</h3>
										<span>{{ $shipment->shipment_id }}</span>
									</div>
								</div>
							</div>
						</div>
						<div class="delivery">
							<div class="row">
								<div class="col-sm-4 ">
									<div class="delivery-date">
										<h4>@lang('Shipment Date')</h4>
										<h4>{{ customDate($shipment->shipment_date) }}</h4>
									</div>
								</div>
								<div class="col-sm-4 ">
									<div class="delivery-time">
										<h4>@lang('Elapsed After Shipment')</h4>
										<h4 class="text-dark elapsed-time">{{ $shipment->shipmentElapsedTime()['difference']->d . ' Day' . ($shipment->shipmentElapsedTime()['difference']->d > 1 ? 's' : '') . ' ' . $shipment->shipmentElapsedTime()['difference']->h . ' Hour' . ($shipment->shipmentElapsedTime()['difference']->h > 1 ? 's' : '') . ' ' . $shipment->shipmentElapsedTime()['difference']->i . ' Minute' . ($shipment->shipmentElapsedTime()['difference']->i > 1 ? 's' : '') }}
										</h4>
									</div>
								</div>
								<div class="col-sm-4 ">
									<div class="delivery-type">
										<h4>@lang('Shipment Type')</h4>
										<h4>{{ formatedShipmentType($shipment->shipment_type) }}</h4>
									</div>
								</div>
							</div>
						</div>

						@if($shipment_status == 8)
							<div class="processing">
								<div class="processing-title">
									<h2>@lang('Shipment Status')</h2>
								</div>
								<div class="process d-flex justify-content-center text-center">
									<div class="spinner-border text-warning" role="status">
										<span class="visually-hidden"></span>

									</div>
									@lang('Return Processing')...
								</div>
							</div>
						@elseif($shipment_status == 9)
							<div class="payment-details-2">
								<div class="row">
									<div class="col-md-12">
										<div class="location dot-3">
											<h4>@lang(optional($shipment->senderBranch)->branch_name)</h4>
											<h5>@lang(optional($shipment->senderBranch)->address)</h5>
										</div>
										<div class="location dot-4">
											<h4><span>@lang(optional($shipment->receiverBranch)->branch_name)</span>
											</h4>
											<h5><span>@lang(optional($shipment->receiverBranch)->address)</h5>
										</div>
										<div class="down-icon-2">
											<i class="fas fa-solid fa-arrow-up"></i>
										</div>
									</div>
								</div>
							</div>

						@elseif($shipment->status == 10)
							<div class="processing shipment_info_area">
								<div class="process d-flex justify-content-center text-center">
									<div class="estmated_received text-center">
										<div class="icon_area">
											<i class="fal fa-check-circle"></i>
										</div>
										<h5>@lang('Shipment Received Date')</h5>
										<h5>{{ customDate($shipment->return_receive_time) }}</h5>
										<div class="btn_area mt-25">
											<button disabled type="button"
													class="cmn_btn">@lang('Ready For Delivery')</button>
										</div>
									</div>
								</div>
							</div>

						@elseif($shipment->status == 11)
							<div class="processing shipment_info_area">
								<div class="process d-flex justify-content-center text-center">
									<div class="estmated_delivery text-center">
										<div class="icon_area">
											<i class="fal fa-check-circle"></i>
										</div>
										<h5>@lang('Shipment Delivery Date')</h5>
										<h5>{{ customDate($shipment->return_delivered_time) }}</h5>
										<div class="btn_area mt-25">
											<button disabled type="button"
													class="cmn_btn">@lang('Delivery Successfully Completed')</button>
										</div>
									</div>
								</div>
							</div>
						@endif
					</div>
				</div>
			</section>
		@endif
		<!-- shipment tracking_area_end -->

	@elseif($shipment == null && $initial == false)
		<div class="container shipment_info_area mb-5">
			<div class="row">
				<div class="col-lg-8 mx-auto ">
					<div class="track-not-found text-center">
						<div>
							<img class="img-fluid" src="{{ asset($themeTrue.'images/track-not-found.png') }}" alt="">
						</div>
						<h3 class="section_subtitle">@lang('No Result Found')</h3>
						<h5>@lang('We can’t find any results based on your search.')</h5>
					</div>
				</div>
			</div>
		</div>
	@endif

@endsection
