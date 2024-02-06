@extends($theme.'layouts.user')
@section('page_title',__('Dashboard'))

@section('content')
	<!-- main -->
	<div class="container-fluid">
		<div class="row" id="firebase-app" v-if="user_foreground == '1' || user_background == '1'">
			<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-4 mt-0" v-if="notificationPermission == 'default' && !is_notification_skipped" v-cloak>
				<div class="d-flex justify-content-between align-items-start bd-callout bd-callout-warning  shadow">
					<div>
						<i class="fas fa-info-circle mr-2"></i> @lang('Do not miss any single important notification! Allow your
                        browser to get instant push notification')
						<button id="allow-notification" class="btn btn-sm cmn_btn2 mx-2"><i
								class="fa fa-check-circle"></i> @lang('Allow me')</button>
					</div>
					<a href="javascript:void(0)" @click.prevent="skipNotification"><i class="fas fa-times"></i></a>
				</div>
			</div>

			<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-4 mt-0" v-if="notificationPermission == 'denied' && !is_notification_skipped" v-cloak>
				<div class="d-flex justify-content-between align-items-start bd-callout bd-callout-warning  shadow">
					<div>
						<i class="fas fa-info-circle mr-2"></i> @lang('Please allow your browser to get instant push notification.
                        Allow it from
                        notification setting.')
					</div>
					<a href="javascript:void(0)" @click.prevent="skipNotification"><i class="fas fa-times"></i></a>
				</div>
			</div>
		</div>

		<div class="main row">
			<div class="col-12">
				<div class="d-flex justify-content-between align-items-center mb-4">
					<h3 class="mb-0">@lang('Dashboard')</h3>
				</div>

				<div class="col-12">
					<div class="row g-3">
						<div class="col-xl-4 col-lg-6">
							<div class="card-box balance-box p-0 h-100">
								<div class="user-account-number p-4 h-100">
									<i class="account-wallet far fa-wallet"></i>
									<div class="mb-4 d-flex justify-content-between">
										<div>
											<h5 class="text-black mb-2">
												@lang('Current Balance')
											</h5>
											<h3>
                                        <span
											class="text-black"><small><sup>{{trans(config('basic.currency_symbol'))}}</sup></small>{{getAmount($walletBalance, config('basic.fraction_number'))}}</span>
											</h3>
										</div>

										<a href="{{ route('fund.initialize') }}" class="cash-in text-black"><i
												class="fal fa-plus me-1 text-black"></i> @lang('Cash In')</a>
									</div>
									<div class="d-flex justify-content-between">
										<div>
											<h5 class="text-black mb-2">
												@lang('Total Deposit')
											</h5>
											<h3><span
													class="text-black otal_available__balance"><small><sup>{{trans(config('basic.currency_symbol'))}}</sup></small>{{getAmount($totalDeposit, config('basic.fraction_number'))}}</span>
											</h3>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-4 col-lg-6 dashboard-box-wrapper">
							<div class="row g-3">
								<div class="col-lg-12 col-12">
									<div class="dashboard-box d-flex justify-content-between">
										<div class="text_area">
											<h5>@lang('Total Shipments')</h5>
											<h3>{{ $shipmentRecord['totalShipments'] }}</h3>
										</div>
										<div class="icon_area1">
											<i class="fal fa-shipping-fast" aria-hidden="true"></i>
										</div>
									</div>
								</div>

								<div class="col-lg-12 col-12">
									<div class="dashboard-box d-flex justify-content-between">
										<div class="text_area">
											<h5>@lang('Total Shipment Transactions')</h5>
											<h3>{{trans(config('basic.currency_symbol'))}}{{getAmount($transactionRecord['totalShipmentTransactions'], config('basic.fraction_number'))}}</h3>
										</div>
										<div class="icon_area2">
											<i class="fal fa-dollar-sign"></i>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-4 dashboard-box-wrapper">
							<div class="row g-3">
								<div class="col-xl-12 col-12">
									<div class="dashboard-box d-flex justify-content-between">
										<div class="text_area">
											<h5>@lang('Operator Country Shipments')</h5>
											<h3>{{ $shipmentRecord['totalOperatorCountryShipments'] }}</h3>
										</div>
										<div class="icon_area3">
											<i class="fal fa-truck"></i>
										</div>
									</div>
								</div>
								<div class="col-xl-12 col-12 box">
									<div class="dashboard-box d-flex justify-content-between">
										<div class="text_area">
											<h5>@lang('Internationally Shipments')</h5>
											<h3>{{ $shipmentRecord['totalInternationallyShipments'] }}</h3>
										</div>
										<div class="icon_area4">
											<i class="fal fa-plane"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="dashboard-box-wrapper mt-20 mt-5">
					<div class="row g-4 mb-4">
						<div class="col-xxl-3 col-md-6 box">
							<div class="dashboard-box d-flex justify-content-between">
								<div class="text_area">
									<h5>@lang('Pending Shipments')</h5>
									<h3>{{ $shipmentRecord['totalPendingShipments'] }}</h3>
								</div>
								<div class="icon_area">
									<i class="fal fa-spinner" aria-hidden="true"></i>
								</div>
							</div>
						</div>

						<div class="col-xxl-3 col-md-6 box">
							<div class="dashboard-box d-flex justify-content-between">

								<div class="text_area">
									<h5>@lang('In Queue Shipments')</h5>
									<h3>{{ $shipmentRecord['totalInQueueShipments'] }}</h3>
								</div>
								<div class="icon_area">
									<i class="fal fa-paper-plane"></i>
								</div>
							</div>
						</div>

						<div class="col-xxl-3 col-md-6 box">
							<div class="dashboard-box d-flex justify-content-between">

								<div class="text_area">
									<h5>@lang('Delivered Shipments')</h5>
									<h3>{{ $shipmentRecord['totalDeliveredShipments'] }}</h3>
								</div>
								<div class="icon_area">
									<i class="fal fa-check-double"></i>
								</div>
							</div>
						</div>

						<div class="col-xxl-3 col-md-6 box">
							<div class="dashboard-box d-flex justify-content-between">

								<div class="text_area">
									<h5>@lang('Return Shipments')</h5>
									<h3>{{ $shipmentRecord['totalReturnShipments'] }}</h3>
								</div>
								<div class="icon_area">
									<i class="fal fa-times"></i>
								</div>
							</div>
						</div>
					</div>
				</div>


				<!-- table -->
				<div class="table-parent table-responsive mt-5">
					<div class="d-flex justify-content-between align-items-center mb-4">
						<h4 class="mb-0 font-weight-bold">@lang('Latest Shipments')</h4>
					</div>
					<table class="table table-striped">
						<thead>
						<tr>
							<th scope="col"
								class="custom-text">@lang('SL.')</th>
							<th scope="col"
								class="custom-text">@lang('Shipment From')</th>

							<th scope="col"
								class="custom-text">@lang('Shipment Id')</th>

							<th scope="col"
								class="custom-text">@lang('Shipment Type')</th>
							<th scope="col"
								class="custom-text">@lang('Sender Branch')</th>
							<th scope="col"
								class="custom-text">@lang('Receiver Branch')</th>
							<th scope="col"
								class="custom-text">@lang('Total Cost')</th>
							<th scope="col"
								class="custom-text">@lang('Shipment Date')</th>
							<th scope="col"
								class="custom-text">@lang('Status')</th>
						</tr>
						</thead>
						<tbody>
						@if(count($allShipments) > 0)
							@foreach($allShipments as $key => $shipment)
								<tr>
									<td data-label="SL."> {{ ++$key }} </td>
									<td data-label="Shipment Type">@if($shipment->shipment_identifier == 1)
											@lang(optional(basicControl()->operatorCountry)->name)
										@else
											@lang('Internationally')
										@endif</td>
									<td data-label="Shipment Id"> {{ $shipment->shipment_id }} </td>
									<td data-label="Shipment Type"> {{ formatedShipmentType($shipment->shipment_type) }} </td>
									<td data-label="Sender Branch"> @lang(optional($shipment->senderBranch)->branch_name) </td>
									<td data-label="Receiver Branch"> @lang(optional($shipment->receiverBranch)->branch_name) </td>
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
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="100%" class="text-center p-2 flex-column">
									<img class="not-found-img"
										 src="{{ asset($themeTrue.'images/business.png') }}"
										 alt="">
									<p class="text-center no-data-found-text">@lang('No Shipments Found')</p>
								</td>
							</tr>
						@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection

@if($firebaseNotify)
	@section('scripts')

		<script type="module">
			import {initializeApp} from "https://www.gstatic.com/firebasejs/9.17.1/firebase-app.js";
			import {
				getMessaging,
				getToken,
				onMessage
			} from "https://www.gstatic.com/firebasejs/9.17.1/firebase-messaging.js";

			const firebaseConfig = {
				apiKey: "{{$firebaseNotify->api_key}}",
				authDomain: "{{$firebaseNotify->auth_domain}}",
				projectId: "{{$firebaseNotify->project_id}}",
				storageBucket: "{{$firebaseNotify->storage_bucket}}",
				messagingSenderId: "{{$firebaseNotify->messaging_sender_id}}",
				appId: "{{$firebaseNotify->app_id}}",
				measurementId: "{{$firebaseNotify->measurement_id}}"
			};

			const app = initializeApp(firebaseConfig);
			const messaging = getMessaging(app);
			if ('serviceWorker' in navigator) {
				navigator.serviceWorker.register('{{ getProjectDirectory() }}' + `/firebase-messaging-sw.js`, {scope: './'}).then(function (registration) {
						requestPermissionAndGenerateToken(registration);
					}
				).catch(function (error) {
				});
			} else {
			}

			onMessage(messaging, (payload) => {
				if (payload.data.foreground || parseInt(payload.data.foreground) == 1) {
					const title = payload.notification.title;
					const options = {
						body: payload.notification.body,
						icon: payload.notification.icon,
					};
					new Notification(title, options);
				}
			});

			function requestPermissionAndGenerateToken(registration) {
				document.addEventListener("click", function (event) {
					if (event.target.id == 'allow-notification') {
						Notification.requestPermission().then((permission) => {
							if (permission === 'granted') {
								getToken(messaging, {
									serviceWorkerRegistration: registration,
									vapidKey: "{{$firebaseNotify->vapid_key}}"
								})
									.then((token) => {
										$.ajax({
											url: "{{ route('user.save.token') }}",
											method: "post",
											data: {
												token: token,
											},
											success: function (res) {
											}
										});
										window.newApp.notificationPermission = 'granted';
									});
							} else {
								window.newApp.notificationPermission = 'denied';
							}
						});
					}
				});
			}
		</script>
		<script>
			window.newApp = new Vue({
				el: "#firebase-app",
				data: {
					user_foreground: '',
					user_background: '',
					notificationPermission: Notification.permission,
					is_notification_skipped: sessionStorage.getItem('is_notification_skipped') == '1'

				},
				mounted() {
					this.user_foreground = "{{$firebaseNotify->user_foreground}}";
					this.user_background = "{{$firebaseNotify->user_background}}";
				},
				methods: {
					skipNotification() {
						sessionStorage.setItem('is_notification_skipped', '1');
						this.is_notification_skipped = true;
					}
				}
			});
		</script>
	@endsection
@endif
