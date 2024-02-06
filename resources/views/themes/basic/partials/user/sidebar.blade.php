<!-- sidebar -->
<div id="sidebar" class="">
	<div class="sidebar-top">
		<a class="navbar-brand" href="{{url('/')}}"> <img
				src="{{ getFile(config('basic.default_file_driver'),config('basic.logo_image')) }}"
				alt="@lang('logo image')"/></a>
		<button class="sidebar-toggler d-md-none" onclick="toggleSideMenu()">
			<i class="fal fa-times"></i>
		</button>
	</div>

	<ul class="main">
		<li>
			<a class="{{menuActive('user.dashboard')}}" href="{{route('user.dashboard')}}"><i
					class="fal fa-th-large"></i>@lang('Dashboard')</a>
		</li>

		<li>
			<a class="dropdown-toggle" data-bs-toggle="collapse" href="#dropdownCollapsible" role="button"
			   aria-expanded="false" aria-controls="collapseExample">
				<i class="fal fa-truck text-info"></i>@lang('Shipments')
			</a>
			<div
				class="collapse {{menuActive(['user.shipmentList', 'user.viewShipment', 'user.createShipment']) == 'active' ? 'show' : ''}}"
				id="dropdownCollapsible">
				<ul class="">
					<li>
						<a class="{{ activeMenu(['user.shipmentList', 'user.viewShipment'], null, 'all') }}"
						   href="{{ route('user.shipmentList', ['shipment_status' => 'all', 'shipment_type' => 'operator-country']) }}">@lang('All Shipments')</a>
					</li>

					<li>
						<a class="{{ activeMenu(['user.shipmentList', 'user.viewShipment'], null, 'in_queue') }}"
						   href="{{ route('user.shipmentList', ['shipment_status' => 'in_queue', 'shipment_type' => 'operator-country']) }}">
							@lang('In Queue')
						</a>
					</li>

					<li>
						<a class="{{ activeMenu(['user.shipmentList', 'user.viewShipment'], null, 'dispatch') }}"
						   href="{{ route('user.shipmentList', ['shipment_status' => 'dispatch', 'shipment_type' => 'operator-country']) }}">
							@lang('Dispatch')
						</a>
					</li>

					<li>
						<a class="{{ activeMenu(['user.shipmentList', 'user.viewShipment'], null, 'received') }}"
						   href="{{ route('user.shipmentList', ['shipment_status' => 'received', 'shipment_type' => 'operator-country']) }}">
							@lang('Received')
						</a>
					</li>

					<li>
						<a class="{{ activeMenu(['user.shipmentList', 'user.viewShipment'], null, 'delivered') }}"
						   href="{{ route('user.shipmentList', ['shipment_status' => 'delivered', 'shipment_type' => 'operator-country']) }}">
							@lang('Delivered')
						</a>
					</li>

					<li>
						<a class="{{ activeMenu(['user.shipmentList', 'user.shipmentRequest'], null, 'requested') }}"
						   href="{{ route('user.shipmentList', ['shipment_status' => 'requested', 'shipment_type' => 'operator-country']) }}">
							@lang('Shipment Request')
						</a>
					</li>

				</ul>
			</div>
		</li>

		<li>
			<a class="{{menuActive(['user.receiverList', 'user.receiver.create'])}}"
			   href="{{ route('user.receiverList') }}"><i class="fal fa-users text-success"></i>@lang('Receiver List')
			</a>
		</li>

		<li>
			<a class="{{menuActive(['fund.initialize', 'deposit.confirm', 'payment.process'])}}"
			   href="{{ route('fund.initialize') }}"><i class="fal fa-funnel-dollar text-primary"
														aria-hidden="true"></i>@lang('Add Fund')</a>
		</li>

		<li>
			<a class="{{ menuActive(['fund.index', 'fund.search']) }}" href="{{ route('fund.index') }}"><i
					class="fal fa-file-invoice-dollar text-warning" aria-hidden="true"></i>@lang('Fund History')
			</a>
		</li>

		<li>
			<a class="{{menuActive(['payout.request'])}}" href="{{route('payout.request')}}"><i
					class="fal fa-credit-card text-danger" aria-hidden="true"></i>@lang('Payout')</a>
		</li>

		<li>
			<a class="{{menuActive(['payout.index', 'payout.search'])}}" href="{{ route('payout.index') }}"><i
					class="fal fa-usd-square text-purple" aria-hidden="true"></i>@lang('Payout History')</a>
		</li>

		<li>
			<a class="{{menuActive(['user.money-transfer'])}}" href="{{route('user.money-transfer')}}"><i
					class="fal fa-exchange-alt text-orange" aria-hidden="true"></i>@lang('Money Transfer')</a>
		</li>

		<li>
			<a class="{{menuActive(['user.transaction','user.transaction.search'])}}"
			   href="{{ route('user.transaction') }}"><i
					class="fal fa-money-check-alt text-indigo" aria-hidden="true"></i>@lang('Transaction')</a>
		</li>

		<li>
			<a class="{{menuActive(['user.ticket.list', 'user.ticket.view', 'user.ticket.create'])}}"
			   href="{{route('user.ticket.list')}}"><i
					class="fal fa-ticket text-success" aria-hidden="true"></i>@lang('Support Ticket')</a>
		</li>

	</ul>
</div>
