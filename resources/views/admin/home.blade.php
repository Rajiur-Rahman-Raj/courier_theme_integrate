@extends('admin.layouts.master')
@section('page_title',__('Dashboard'))

@push('extra_styles')
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/daterangepicker.css') }}">
@endpush

@section('content')
	<style>
		#canvas {
			border: solid 1px blue;
			width: 100%;
		}
	</style>
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Admin Dashboard')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Admin Dashboard')</div>
				</div>
			</div>

			<div class="row mb-3" id="firebase-app" v-if="admin_foreground == '1' || admin_background == '1'">
				<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-4 mt-0"
					 v-if="notificationPermission == 'default' && !is_notification_skipped" v-cloak>
					<div class="d-flex justify-content-between align-items-start bd-callout bd-callout-warning  shadow">
						<div>
							<i class="fas fa-info-circle mr-2"></i> @lang('Do not miss any single important notification! Allow your
                        browser to get instant push notification')
							<button id="allow-notification" class="btn btn-sm btn-primary mx-2"><i
									class="fa fa-check-circle"></i> @lang('Allow me')</button>
						</div>
						<a href="javascript:void(0)" @click.prevent="skipNotification"><i class="fas fa-times"></i></a>
					</div>
				</div>

				<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-4 mt-0"
					 v-if="notificationPermission == 'denied' && !is_notification_skipped" v-cloak>
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

			<div class="row mb-3">
				@if(adminAccessRoute(array_merge(config('permissionList.Admin_Dashboard.User_Statistics.permission.view'))))
					<div class="col-xl-8">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-12">
								<div class="card home-statistic-1 border-1 shadow-sm block-statistics">

									<div class="card-header d-flex justify-content-between">
										<div class="cart-icon icon1">
											<i class="fas fa-user-tie"></i>
										</div>
										<div class="cart-content">
											<p class="text-sm mb-0 text-capitalize">@lang('Total Users')</p>
											<h4 class="mb-0 totalUser"></h4>
										</div>

									</div>
									<div class="card-footer">
										<p class="mb-0">
											<span class="text-sm font-weight-bolder text-success">
												<i class="fas fa-chart-line"></i>
											</span>
											@lang('This platform')
										</p>
									</div>

								</div>
							</div>

							<div class="col-lg-6 col-md-6 col-sm-6 col-12">
								<div class="card home-statistic-1 border-2 shadow-sm block-statistics">
									<div class="card-header d-flex justify-content-between">
										<div class="cart-icon icon2">
											<i class="fas fa-user-tie"></i>
										</div>
										<div class="cart-content">
											<p class="text-sm mb-0 text-capitalize">@lang(date('M')) @lang('Users')</p>
											<h4 class="mb-0 thisMonthUsers"></h4>
										</div>
									</div>

									<hr class="dark horizontal my-0">
									<div class="card-footer">
										<p class="mb-0"><span class="text-sm font-weight-bolder userCurrentMonthClass"> <i
													class="userCurrentMonthArrowIcon"></i> <span
													class="userMonthIncreaseDecreaseSign"></span><span
													class="userCurrentMonthPercentage"></span></span>@lang('than') {{ date("M", strtotime("last month")) }} @lang('month')
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-12">
								<div class="card home-statistic-1 border-3 shadow-sm block-statistics">

									<div class="card-header d-flex justify-content-between">
										<div class="cart-icon icon3">
											<i class="fas fa-user-tie"></i>
										</div>
										<div class="cart-content">
											<p class="text-sm mb-0 text-capitalize">@lang('Current Year Users')</p>
											<h4 class="mb-0 thisYearUsers"></h4>
										</div>
									</div>
									<hr class="dark horizontal my-0">
									<div class="card-footer">
										<p class="mb-0"><span class="text-sm font-weight-bolder userCurrentYearClass"><i
													class="userCurrentYearArrowIcon"></i><span
													class="userYearIncreaseDecreaseSign"></span><span
													class="userCurrentYearPercentage"></span></span>@lang('than previous year')
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-12">
								<div class="card home-statistic-1 border-4 shadow-sm block-statistics">

									<div class="card-header d-flex justify-content-between">
										<div class="cart-icon icon4">
											<i class="fas fa-user-tie"></i>
										</div>
										<div class="cart-content">
											<p class="text-sm mb-0 text-capitalize">@lang(date('M')) @lang('Deposit')</p>
											<h4 class="mb-0 thisMonthDeposit"></h4>
										</div>
									</div>
									<hr class="dark horizontal my-0">
									<div class="card-footer">
										<p class="mb-0"><span
												class="text-sm font-weight-bolder depositCurrentMonthClass"> <i
													class="depositCurrentMonthArrowIcon"></i> <span
													class="depositMonthIncreaseDecreaseSign"></span><span
													class="depositCurrentMonthPercentage"></span></span>@lang('than') {{ date("M", strtotime("last month")) }} @lang('month')
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				@endif
				@if(adminAccessRoute(array_merge(config('permissionList.Admin_Dashboard.Branch_Statistics.permission.view'))))
					<div class="col-xl-4">
						<div class="card bracnh-statistics">
							<div class="card-header">
								<h4>@lang('Branch Statistics')</h4>
							</div>
							<div class="card-body block-statistics">
								<ul class="p-0">
									<li class="d-flex align-items-center w-100">
										<div class="branch-icon icon1">
											<i class="fas fa-code-branch"></i>
										</div>
										<div class="branch-content w-100 d-flex justify-content-between">
											<div class="branch-text">
												<h5>@lang('Total Branches')</h5>
											</div>
											<div class="branch-number">
												<h6><span class="totalBranches"></span></h6>
											</div>
										</div>
									</li>
									<li class="d-flex  align-items-center w-100">
										<div class="branch-icon icon2">
											<i class="fas fa-users"></i>
										</div>
										<div class="branch-content w-100 d-flex justify-content-between">
											<div class="branch-text">
												<h5>@lang('Total Branch Managers')</h5>
											</div>
											<div class="branch-number">
												<h6><span class="totalBranchManagers"></span></h6>
											</div>
										</div>
									</li>
									<li class="d-flex align-items-center w-100">
										<div class="branch-icon icon3">
											<i class="fas fa-motorcycle"></i>
										</div>
										<div class="branch-content w-100 d-flex justify-content-between">
											<div class="branch-text">
												<h5>@lang('Total Branch Drivers')</h5>
											</div>
											<div class="branch-number">
												<h6><span class="totalBranchDrivers"></span></h6>
											</div>
										</div>
									</li>
									<li class="d-flex align-items-center w-100">
										<div class="branch-icon icon4">
											<i class="fas fa-users-cog"></i>
										</div>
										<div class="branch-content w-100 d-flex justify-content-between">
											<div class="branch-text">
												<h5>@lang('Total Branch Employees')</h5>
											</div>
											<div class="branch-number">
												<h6><span class="totalBranchEmployees"></span></h6>
											</div>
										</div>
									</li>
								</ul>
							</div>

						</div>
					</div>
				@endif
			</div>

			<!---------- Shipments Summary Current Month-------------->
			@if(adminAccessRoute(array_merge(config('permissionList.Admin_Dashboard.Shipment_Statistics.permission.view'))))
				<div class="row mb-3">

					<div class="col-xl-8 col-lg-12 mb-4">
						<div class="card shipment-chart1 shadow-sm dailyShipmentsLeft">
							<div class="card-body shipmentSummaryChart">
								<div class="d-flex justify-content-between">
									<h5 class="card-title">@lang('Shipments summary')</h5>
									<div class="daterange-container">
										<div class="daterange-picker">
											<input type="text" id="dailyShipments" value=""/>
											<i class="fa fa-caret-down"></i>
										</div>
									</div>
								</div>

								<div class="block-statistics  block-card-height ">
									<canvas id="daily-shipments-line-chart" class=""></canvas>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-12  shipmentsCounting">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-12 ">
								<div class="card shipment-statistic border1 shadow-sm block-statistics mb-4">
									<div class="card-header">
										<div class="cart-icon icon1">
											<i class="fas fa-shipping-fast"></i>
										</div>
									</div>
									<div class="card-body">
										<div class="cart-content">
											<p class="text-sm mb-0 text-capitalize">@lang('Total Shipments')</p>
											<h4 class="mb-2"><span class="totalShipments"></span></h4>
										</div>
									</div>
									<hr class="dark horizontal my-0">
									<div class="card-footer">
										<p class="mb-0">
											<span class="text-sm font-weight-bolder text-success">
												<i class="fas fa-chart-line"></i>
											</span>
											@lang('This platform')
										</p>
									</div>

								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-12 ">
								<div class="card shipment-statistic border2 shadow-sm block-statistics mb-4">
									<div class="card-header">
										<div class="cart-icon icon2">
											<i class="fas fa-truck"></i>
										</div>
									</div>
									<div class="card-body">
										<div class="cart-content">
											<p class="text-sm mb-0 text-capitalize">@lang(date('D')) @lang("Shipments")</p>
											<h4 class="mb-2"><span class="totalTodayShipments"></span></h4>
										</div>
									</div>
									<hr class="dark horizontal my-0">
									<div class="card-footer">
										<p class="mb-0">
											<span class="text-sm font-weight-bolder shipmentCurrentTodayClass">
												<i class="shipmentCurrentTodayArrowIcon"></i>
												<span class="shipmentCurrentTodayPercentage"></span>
											</span>
											@lang('than') {{ date('D', strtotime('yesterday')) }} @lang('day')
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-12">
								<div class="card shipment-statistic border3 shadow-sm block-statistics mb-4">
									<div class="card-header">
										<div class="cart-icon icon3">
											<i class="fas fa-plane"></i>
										</div>
									</div>
									<div class="card-body">
										<div class="cart-content">
											<p class="text-sm mb-0 text-capitalize">@lang(date('M')) @lang('Shipments')
											</p>
											<h4 class="mb-2"><span class="thisMonthShipments"></span></h4>
										</div>
									</div>
									<hr class="dark horizontal my-0">
									<div class="card-footer">
										<p class="mb-0">
											<span class="text-sm font-weight-bolder shipmentCurrentMonthClass">
												<i class="shipmentCurrentMonthArrowIcon"></i>
												<span class="shipmentCurrentMonthPercentage"></span>
											</span>
											@lang('than') {{ date("M", strtotime("last month")) }} @lang('month')
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-12">
								<div class="card shipment-statistic border4 shadow-sm block-statistics mb-4">
									<div class="card-header">
										<div class="cart-icon icon4">
											<i class="fas fa-truck"></i>
										</div>
									</div>

									<div class="card-body">
										<div class="cart-content">
											<p class="text-sm mb-0 text-capitalize">@lang(optional(basicControl()->operatorCountry)->name) @lang('Shipments')</p>
											<h4 class="mb-2"><span class="thisMonthOperatorCountryShipments"></span>
											</h4>
										</div>
									</div>

									<hr class="dark horizontal my-0">
									<div class="card-footer">
										<p class="mb-0">
											<span
												class="text-sm font-weight-bolder shipmentCurrentMonthOperatorCountryClass">
												<i class="shipmentCurrentMonthOperatorCountryArrowIcon"></i>
												<span class="shipmentCurrentMonthOperatorCountryPercentage"></span>
											</span>
											@lang('than') {{ date("M", strtotime("last month")) }} @lang('month')
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xxl col-xl-8 col-lg-12">
						<div class="card  shipment-chart2 mb-4 shadow-sm yearlyShipmentsChart">
							<div class="card-body yearlyShipmentSummary">
								<div class="d-flex justify-content-between">
									<h5 class="card-title">@lang('Yearly Shipments Summary')</h5>
								</div>

								<div class="block-statistics block-card-height2">
									<canvas id="shipment-year-chart"></canvas>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xxl col-xl-4 col-lg-12">
						<div class="row yearlyShipmentsCounting">
							<div class="col-lg-6 col-md-6 col-sm-6 col-12">
								<div class="card shipment-statistic border1 shadow-sm block-statistics">
									<div class="card-header">
										<div class="cart-icon icon1">
											<i class="fas fa-shipping-fast"></i>
										</div>
									</div>
									<div class="card-body">
										<div class="cart-content">
											<p class="text-sm mb-0 text-capitalize">@lang('Internationally Shipments')</p>
											<h4 class="mb-3"><span class="thisMonthInternationallyShipments"></span>
											</h4>
										</div>
									</div>
									<hr class="dark horizontal my-0">
									<div class="card-footer ">
										<p class="mb-0">
											<span
												class="text-sm font-weight-bolder shipmentCurrentMonthInternationallyClass">
												<i class="shipmentCurrentMonthInternationallyArrowIcon"></i>
												<span class="shipmentCurrentMonthInternationallyPercentage"></span>
											</span>
											@lang('than') {{ date("M", strtotime("last month")) }} @lang('month')
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-12">
								<div class="card shipment-statistic border2 shadow-sm block-statistics">
									<div class="card-header">
										<div class="cart-icon icon2">
											<i class="far fa-money-bill-alt"></i>
										</div>
									</div>
									<div class="card-body">
										<div class="cart-content">
											<p class="text-sm mb-0 text-capitalize">@lang('Drop Off Shipments')
											</p>
											<h4 class="mb-3"><span class="thisMonthDropOffShipments"></span></h4>
										</div>
									</div>
									<hr class="dark horizontal my-0">
									<div class="card-footer  ">
										<p class="mb-0">
											<span class="text-sm font-weight-bolder shipmentCurrentMonthDropOffClass">
												<i class="shipmentCurrentMonthDropOffArrowIcon"></i>
												<span class="shipmentCurrentMonthDropOffPercentage"></span>
											</span>
											@lang('than') {{ date("M", strtotime("last month")) }} @lang('month')
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-12">
								<div class="card shipment-statistic border3 shadow-sm block-statistics">
									<div class="card-header">
										<div class="cart-icon icon3">
											<i class="fas fa-spinner" aria-hidden="true"></i>
										</div>
									</div>
									<div class="card-body">
										<div class="cart-content">
											<p class="text-sm mb-0 text-capitalize">@lang('Pickup Shipments')
											</p>
											<h4 class="mb-3"><span class="thisMonthPickupShipments"></span></h4>
										</div>
									</div>
									<hr class="dark horizontal my-0">
									<div class="card-footer  ">
										<p class="mb-0">
											<span class="text-sm font-weight-bolder shipmentCurrentMonthPickupClass">
												<i class="shipmentCurrentMonthPickupArrowIcon"></i>
												<span class="shipmentCurrentMonthPickupPercentage"></span>
											</span>
											@lang('than') {{ date("M", strtotime("last month")) }} @lang('month')
										</p>
									</div>
								</div>
							</div>

							<div class="col-lg-6 col-md-6 col-sm-6 col-12">
								<div class="card shipment-statistic border4 shadow-sm block-statistics">
									<div class="card-header">
										<div class="cart-icon icon4">
											<i class="fas fa-people-carry"></i>
										</div>
									</div>
									<div class="card-body">
										<div class="cart-content">
											<p class="text-sm mb-0 text-capitalize">@lang('Condition Shipments')
											</p>
											<h4 class="mb-3"><span class="thisMonthConditionShipments"></span></h4>
										</div>
									</div>
									<hr class="dark horizontal my-0">
									<div class="card-footer  ">
										<p class="mb-0">
											<span class="text-sm font-weight-bolder shipmentCurrentMonthConditionClass">
												<i class="shipmentCurrentMonthConditionArrowIcon"></i>
												<span class="shipmentCurrentMonthConditionPercentage"></span>
											</span>
											@lang('than') {{ date("M", strtotime("last month")) }} @lang('month')
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif


			@if(adminAccessRoute(array_merge(config('permissionList.Admin_Dashboard.Shipment_Transaction.permission.view'))))
				<div class="row  ">
					<div class="col-xxl col-xl-8">
						<div class="card year-shipment  shadow-sm YearlyShipmentsTransactions">
							<div class="card-body">
								<h5 class="card-title">@lang('Yearly Shipments Transactions')</h5>
								<div class="block-statistics block-card-height3">
									<canvas id="shipments-transaction-current-year"></canvas>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xxl col-xl-4">
						<div class="card transaction-statistics">
							<div class="card-header">
								<h4>@lang('Transaction Statistics')</h4>
							</div>
							<div class="card-body">
								<ul class="p-0">
									<li class="d-flex align-items-center mb-4 w-100">
										<div class="transaction-icon icon1">
											<i class="fas fa-funnel-dollar"></i>
										</div>
										<div class="transaction-content w-100 d-flex justify-content-between">
											<div class="transaction-text">
												<h5>@lang('Total Transactions')</h5>
											</div>
											<div class="transaction-number">
												<h6><span class="totalShipmentTransactions"></span></h6>
											</div>
										</div>
									</li>

									<li class="d-flex  align-items-center w-100">
										<div class="transaction-icon icon2">
											<i class="fas fa-hand-holding-usd"></i>
										</div>
										<div class="transaction-content w-100 d-flex justify-content-between">
											<div class="transaction-text">
												<h5>@lang("Today's Transactions")</h5>
											</div>
											<div class="transaction-number">
												<h6><span class="totalTodayShipmentTransactions"></span></h6>
											</div>
										</div>
									</li>
									<p class="mb-0">
											<span
												class="text-sm font-weight-bolder shipmentTransactionCurrentTodayClass">
												<i class="shipmentTransactionCurrentTodayArrowIcon"></i>
												<span class="shipmentTransactionCurrentTodayPercentage"></span>
											</span>
										@lang('than') {{ date("M", strtotime("last month")) }} @lang('month')
									</p>

									<li class="d-flex  align-items-center w-100">
										<div class="transaction-icon icon1">
											<i class="fas fa-dollar-sign"></i>
										</div>
										<div class="transaction-content w-100 d-flex justify-content-between">
											<div class="transaction-text">
												<h5>@lang('This Month Transactions')</h5>
											</div>
											<div class="transaction-number">
												<h6><span class="thisMonthShipmentTransactions"></span></h6>
											</div>
										</div>
									</li>
									<p class="mb-0">
											<span
												class="text-sm font-weight-bolder shipmentTransactionCurrentMonthClass">
												<i class="shipmentTransactionCurrentMonthArrowIcon"></i>
												<span class="shipmentTransactionCurrentMonthPercentage"></span>
											</span>
										@lang('than') {{ date("M", strtotime("last month")) }} @lang('month')
									</p>

									<li class="d-flex  align-items-center w-100">
										<div class="transaction-icon icon4">
											<i class="fas fa-code-branch"></i>
										</div>
										<div class="transaction-content w-100 d-flex justify-content-between">
											<div class="transaction-text">
												<h5>@lang(optional(basicControl()->operatorCountry)->name) @lang('Transactions')</h5>
											</div>
											<div class="transaction-number">
												<h6><span class="thisMonthOperatorCountryTransactions"></span></h6>
											</div>
										</div>
									</li>

									<p class="mb-0">
											<span
												class="text-sm font-weight-bolder shipmentTransactionCurrentMonthOperatorCountryClass">
												<i class="shipmentTransactionCurrentMonthOperatorCountryArrowIcon"></i>
												<span
													class="shipmentTransactionCurrentMonthOperatorCountryPercentage"></span>
											</span>
										@lang('than') {{ date("M", strtotime("last month")) }} @lang('month')
									</p>

									<li class="d-flex align-items-center w-100">
										<div class="transaction-icon icon3">
											<i class="fas fa-plane"></i>
										</div>
										<div class="transaction-content w-100 d-flex justify-content-between">
											<div class="transaction-text">
												<h5>@lang('Internationally Transactions')</h5>
											</div>
											<div class="transaction-number">
												<h6><span class="thisMonthInternationallyTransactions"></span></h6>
											</div>
										</div>
									</li>
									<p class="mb-0">
											<span
												class="text-sm font-weight-bolder shipmentTransactionCurrentMonthInternationallyClass">
												<i class="shipmentTransactionCurrentMonthInternationallyArrowIcon"></i>
												<span
													class="shipmentTransactionCurrentMonthInternationallyPercentage"></span>
											</span>
										@lang('than') {{ date("M", strtotime("last month")) }} @lang('month')
									</p>
								</ul>
							</div>
						</div>
					</div>
				</div>
			@endif

			<!---------- Shipments Transactions Current Month-------------->
			@if(adminAccessRoute(array_merge(config('permissionList.Admin_Dashboard.Shipment_Transaction_Chart.permission.view'))))
				<div class="row mb-3">
					<div class="col-md-12">
						<div class="card mb-4 year-shipment shadow-sm">
							<div class="card-body border-r">
								<div class="d-flex justify-content-between">
									<h5 class="card-title">@lang('Monthly Shipments Transactions')</h5>
									<div class="daterange-container">
										<div class="daterange-picker">
											<input type="text" id="dailyShipmentTransactions" value=""/>
											<i class="fa fa-caret-down"></i>
										</div>
									</div>
								</div>
								<div class="block-statistics ">
									<canvas id="daily-shipment-transactions-line-chart"></canvas>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif

			<!---------- Withdraw & Deposit -------------->
			@if(adminAccessRoute(array_merge(config('permissionList.Admin_Dashboard.Payment_Chart.permission.view'))))
				<div class="row mb-3">
					<div class="col-md-8">
						<div class="card mb-4 year-shipment shadow-sm">
							<div class="card-body border-r">
								<h5 class="card-title">@lang('Deposit & Withdraw Transactions')</h5>
								<div class="block-statistics">
									<canvas id="deposit_and_withdraw_transaction_chart"></canvas>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card mb-4 border-r shadow-sm">
							<div class="card-body ">
								<h5 class="card-title">@lang('Deposit Summary')</h5>
								<div class="block-statistics ">
									<canvas id="deposit-summery-chart"></canvas>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif

			@if(adminAccessRoute(array_merge(config('permissionList.Admin_Dashboard.Browser_Statistics.permission.view'))))
				<div class="row mb-3">
					<div class="col-md-12">
						<h6 class="mb-3 text-darku">@lang('User Registration Statistics')</h6>
					</div>
					<div class="col-lg-4 col-md-6 mb-3 mb-lg-5">
						<!-- Card -->
						<div class="card h-100 border-r">
							<div class="card-header d-flex justify-content-between">
								<h4 class="card-header-title">@lang("Browser History")</h4>
								<div class="daterange-container">
									<div class="daterange-picker browser-history-date-range">
										<input type="text" id="dailyBrowserHistory" value=""/>
										<i class="fa fa-caret-down"></i>
									</div>
								</div>
							</div>
							<!-- Body -->
							<div class="card-body  text-center">

								<div class="block-statistics">
									<canvas id="browserHistory"></canvas>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-6 mb-3 mb-lg-5">
						<!-- Card -->
						<div class="card h-100 border-r">
							<div class="card-header d-flex justify-content-between">
								<h4 class="card-header-title">@lang("Operating System History")</h4>
								<div class="daterange-container">
									<div class="daterange-picker browser-history-date-range">
										<input type="text" id="dailyOperatingSystemHistory" value=""/>
										<i class="fa fa-caret-down"></i>
									</div>
								</div>
							</div>
							<!-- Body -->
							<div class="card-body  text-center">
								<div class="block-statistics">
									<canvas id="operatingSystemHistory"></canvas>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-6 mb-3 mb-lg-5">
						<!-- Card -->
						<div class="card h-100 border-r">
							<div class="card-header d-flex justify-content-between">
								<h4 class="card-header-title">@lang("Device History")</h4>
								<div class="daterange-container">
									<div class="daterange-picker browser-history-date-range">
										<input type="text" id="dailyDeviceHistory" value=""/>
										<i class="fa fa-caret-down"></i>
									</div>
								</div>
							</div>
							<!-- Body -->
							<div class="card-body  text-center">
								<div class="block-statistics">
									<canvas id="deviceHistory"></canvas>
								</div>
							</div>
						</div>
					</div>

				</div>
			@endif
			<!---------- User Statistics -------------->
		</section>
	</div>



	@if($basicControl->is_active_cron_notification)
		<div class="modal fade" id="cron-info" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">
							<i class="fas fa-info-circle"></i>
							@lang('Cron Job Set Up Instruction')
						</h5>
						<button type="button" class="close cron-notification-close" data-dismiss="modal"
								aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<p class="bg-orange text-white p-2">
									<i>@lang('**To sending emails and updating currency rate automatically you need to setup cron job in your server. Make sure your job is running properly. We insist to set the cron job time as minimum as possible.**')</i>
								</p>
							</div>
							<div class="col-md-12 form-group">
								<label><strong>@lang('Command for Email')</strong></label>
								<div class="input-group ">
									<input type="text" class="form-control copyText"
										   value="curl -s {{ route('queue.work') }}" disabled>
									<div class="input-group-append">
										<button class="input-group-text bg-primary btn btn-primary text-white copy-btn">
											<i class="fas fa-copy"></i></button>
									</div>
								</div>
							</div>
							<div class="col-md-12 form-group">
								<label><strong>@lang('Command for Currency Rate Update')</strong></label>
								<div class="input-group ">
									<input type="text" class="form-control copyText"
										   value="curl -s {{ route('schedule:run') }}"
										   disabled>
									<div class="input-group-append">
										<button class="input-group-text bg-primary btn btn-primary text-white copy-btn">
											<i class="fas fa-copy"></i></button>
									</div>
								</div>
							</div>
							<div class="col-md-12 text-center">
								<p class="bg-dark text-white p-2">
									@lang('*To turn off this pop up go to ')
									<a href="{{route('basic.control')}}"
									   class="text-orange">@lang('Basic control')</a>
									@lang(' and disable `Cron Set Up Pop Up`.*')
								</p>
							</div>

							<div class="col-md-12">
								<p class="text-muted"><span class="text-secondary font-weight-bold">@lang('N.B'):</span>
									@lang('If you are unable to set up cron job, Here is a video tutorial for you')
									<a href="https://www.youtube.com/watch?v=wuvTRT2ety0" target="_blank"><i
											class="fab fa-youtube"></i> @lang('Click Here') </a>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif

@endsection

@push('extra_scripts')
	<script src="{{ asset('assets/dashboard/js/Chart.min.js') }}"></script>
	<script src="{{ asset('assets/dashboard/js/moment.min.js') }}"></script>
	<script src="{{ asset('assets/dashboard/js/daterangepicker.min.js') }}"></script>
@endpush

@section('scripts')
	<script defer>
		'use strict';


		function setLeftChartHeight(rightColumnHeight, leftColumnHeight) {
			var windowWidth = $(window).width();
			if (windowWidth > 575) {
				var rightColumnHeight = $(rightColumnHeight).height() + 15;
			} else {
				// Reset the height to 'auto' for smaller screens
				$(leftColumnHeight).height('auto');
			}
		}

		$(document).ready(function () {
			setLeftChartHeight('.transaction-statistics', '.YearlyShipmentsTransactions');
			setLeftChartHeight('.shipmentsCounting', '.dailyShipmentsLeft');
			setLeftChartHeight('.yearlyShipmentsCounting', '.yearlyShipmentsChart');

			$(window).resize(function () {
				setLeftChartHeight('.transaction-statistics', '.YearlyShipmentsTransactions');
				setLeftChartHeight('.shipmentsCounting', '.dailyShipmentsLeft');
				setLeftChartHeight('.yearlyShipmentsCounting', '.yearlyShipmentsChart');
			});

		});


		function onDocumentLoad() {
			getAdminDashboardData();
			getUserRecordsData();
			getBranchStatRecords();
			getShipmentStatRecords();
			getShipmentTransactionRecords();
			getYearShipmentChartRecords();
			getYearShipmentTransactionChartRecords();
			getDepositPayoutChartRecords();
		}

		function getUserRecordsData() {
			Notiflix.Block.standard('.block-statistics');
			setTimeout(function () {
				$.ajax({
					url: "{{ route('getUserRecordsData') }}",
					method: 'GET',
					success: function (response) {
						Notiflix.Block.remove('.block-statistics');
						let basic = response.basic;
						let userRecords = response.data.userRecords;
						let depositStatRecords = response.data.depositStatRecords;
						userStatistics(userRecords);
						depositStatistics(depositStatRecords, basic);
					},
					error: function (xhr, status, error) {
						console.log(error)
					}
				});
			}, 1000)
		}

		function getBranchStatRecords() {
			Notiflix.Block.standard('.block-statistics');
			setTimeout(function () {
				$.ajax({
					url: "{{ route('getBranchStatRecords') }}",
					method: 'GET',
					success: function (response) {
						Notiflix.Block.remove('.block-statistics');
						let basic = response.basic;
						let branchRecords = response.data.branchRecords;
						branchStatistics(branchRecords);
					},
					error: function (xhr, status, error) {
						console.log(error)
					}
				});
			}, 1000)
		}

		function getShipmentStatRecords() {
			Notiflix.Block.standard('.block-statistics');
			setTimeout(function () {
				$.ajax({
					url: "{{ route('getShipmentStatRecords') }}",
					method: 'GET',
					success: function (response) {
						Notiflix.Block.remove('.block-statistics');
						let basic = response.basic;
						let shipmentRecords = response.data.shipmentRecords;
						shipmentStatistics(shipmentRecords);
					},
					error: function (xhr, status, error) {
						console.log(error)
					}
				});
			}, 1000)
		}

		function getShipmentTransactionRecords() {
			Notiflix.Block.standard('.block-statistics');
			setTimeout(function () {
				$.ajax({
					url: "{{ route('getShipmentTransactionRecords') }}",
					method: 'GET',
					success: function (response) {
						Notiflix.Block.remove('.block-statistics');
						let basic = response.basic;
						let transactionRecords = response.data.transactionRecords;
						shipmentTransactionStatistics(transactionRecords, basic);
					},
					error: function (xhr, status, error) {
						console.log(error)
					}
				});
			}, 1000)
		}

		function getYearShipmentChartRecords() {
			Notiflix.Block.standard('.block-statistics');
			setTimeout(function () {
				$.ajax({
					url: "{{ route('getYearShipmentChartRecords') }}",
					method: 'GET',
					success: function (response) {
						Notiflix.Block.remove('.block-statistics');
						let basic = response.basic;
						let yearShipmentChartRecords = response.data.yearShipmentChartRecords;
						currentYearShipmentSummeryChart(yearShipmentChartRecords);
					},
					error: function (xhr, status, error) {
						console.log(error)
					}
				});
			}, 1000)
		}

		function getYearShipmentTransactionChartRecords() {
			Notiflix.Block.standard('.block-statistics');
			setTimeout(function () {
				$.ajax({
					url: "{{ route('getYearShipmentTransactionChartRecords') }}",
					method: 'GET',
					success: function (response) {
						Notiflix.Block.remove('.block-statistics');
						let basic = response.basic;
						let yearShipmentTransactionChartRecords = response.data.yearShipmentTransactionChartRecords;
						currentYearShipmentTransactionChart(yearShipmentTransactionChartRecords);
					},
					error: function (xhr, status, error) {
						console.log(error)
					}
				});
			}, 1000)
		}

		function getDepositPayoutChartRecords() {
			Notiflix.Block.standard('.block-statistics');
			setTimeout(function () {
				$.ajax({
					url: "{{ route('getDepositPayoutChartRecords') }}",
					method: 'GET',
					success: function (response) {
						Notiflix.Block.remove('.block-statistics');
						let basic = response.basic;
						let yearDepositPayoutChartRecords = response.data.yearDepositPayoutChartRecords;
						let yearDepositSummeryChartRecords = response.data.yearDepositSummeryChartRecords;
						currentYearDepositPayoutChart(yearDepositPayoutChartRecords);
						currentYearDepositSummeryChart(yearDepositSummeryChartRecords);
					},
					error: function (xhr, status, error) {
						console.log(error)
					}
				});
			}, 1000)
		}


		function getAdminDashboardData() {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			Notiflix.Block.standard('.block-statistics');
			setTimeout(function () {
				$.ajax({
					url: "{{ route('getAdminDashboardData') }}",
					method: 'GET',
					success: function (response) {
						Notiflix.Block.remove('.block-statistics');
						let basic = response.basic;
						let ticketRecords = response.data.ticketRecords;
						ticketStatistics(ticketRecords);

					},
					error: function (xhr, status, error) {
						console.log(error)
					}
				});
			}, 1000)

		}

		document.addEventListener('DOMContentLoaded', onDocumentLoad);


		function percentTodayGrowthShow(calculationType = null, statistics = null, records) {
			if (calculationType != null && statistics != null) {
				$(`.${statistics}Current${calculationType}Class`).addClass(records[`current${calculationType}Class`]);
				$(`.${statistics}Current${calculationType}ArrowIcon`).addClass(records[`current${calculationType}ArrowIcon`]);
				$(`.${statistics}Current${calculationType}Percentage`).text(Math.abs(records[`current${calculationType}Percentage`]) + `%`);

				if (records[`current${calculationType}ArrowIcon`] != null) {
					if (records[`current${calculationType}Class`] == 'text-success') {
						$(`.${statistics}${calculationType}IncreaseDecreaseSign`).text('+');
					} else if (records[`current${calculationType}Class`] == 'text-danger') {
						$(`.${statistics}${calculationType}IncreaseDecreaseSign`).text('-');
					}
				}
			}
		}

		function percentMonthGrowthShow(calculationType = null, statistics = null, records) {
			if (calculationType != null && statistics != null) {
				$(`.${statistics}Current${calculationType}Class`).addClass(records[`current${calculationType}Class`]);
				$(`.${statistics}Current${calculationType}ArrowIcon`).addClass(records[`current${calculationType}ArrowIcon`]);
				$(`.${statistics}Current${calculationType}Percentage`).text(Math.abs(records[`current${calculationType}Percentage`]) + `%`);

				if (records[`current${calculationType}ArrowIcon`] != null) {
					if (records[`current${calculationType}Class`] == 'text-success') {
						$(`.${statistics}${calculationType}IncreaseDecreaseSign`).text('+');
					} else if (records[`current${calculationType}Class`] == 'text-danger') {
						$(`.${statistics}${calculationType}IncreaseDecreaseSign`).text('-');
					}
				}
			}
		}

		function percentYearGrowthShow(calculationType = null, statistics = null, records) {
			if (calculationType != null && statistics != null) {
				$(`.${statistics}Current${calculationType}Class`).addClass(records[`current${calculationType}Class`]);
				$(`.${statistics}Current${calculationType}ArrowIcon`).addClass(records[`current${calculationType}ArrowIcon`]);
				$(`.${statistics}Current${calculationType}Percentage`).text(Math.abs(records[`current${calculationType}Percentage`]) + `%`);

				if (records[`current${calculationType}ArrowIcon`] != null) {
					if (records[`current${calculationType}Class`] == 'text-success') {
						$(`.${statistics}${calculationType}IncreaseDecreaseSign`).text('+');
					} else if (records.currentYearClass == 'text-danger') {
						$(`.${statistics}${calculationType}IncreaseDecreaseSign`).text('-');
					}
				}
			}
		}


		function userStatistics(userRecords) {
			let statistics = 'user';
			$('.totalUser').text(userRecords.totalUser);
			$('.thisMonthUsers').text(userRecords.thisMonthUsers);
			$('.thisYearUsers').text(userRecords.thisYearUsers);

			percentMonthGrowthShow('Month', statistics, userRecords);
			percentYearGrowthShow('Year', statistics, userRecords);
		}

		function depositStatistics(depositStatRecords, basic) {
			let statistics = 'deposit';
			let thisMonthDeposit = depositStatRecords.thisMonthDeposit;
			if (thisMonthDeposit) {
				$('.thisMonthDeposit').text(`${basic.currency_symbol}` + parseFloat(thisMonthDeposit).toFixed(2));
			} else {
				$('.thisMonthDeposit').text(`${basic.currency_symbol}0`);
			}
			percentMonthGrowthShow('Month', statistics, depositStatRecords);
		}

		function ticketStatistics(ticketRecords) {
			$('.pendingTickets').text(ticketRecords.pendingTickets);
			$('.answeredTickets').text(ticketRecords.answeredTickets);
			$('.repliedTickets').text(ticketRecords.repliedTickets);
			$('.closedTickets').text(ticketRecords.closedTickets);
		}

		function branchStatistics(branchRecords) {
			$('.totalBranches').text(branchRecords.totalBranches);
			$('.totalBranchManagers').text(branchRecords.totalBranchManagers);
			$('.totalBranchDrivers').text(branchRecords.totalBranchDrivers);
			$('.totalBranchEmployees').text(branchRecords.totalBranchEmployees);
		}

		function shipmentStatistics(shipmentRecords) {
			let statistics = 'shipment'
			$('.totalShipments').text(shipmentRecords.totalShipments);

			$('.totalTodayShipments').text(shipmentRecords.totalTodayShipments);
			percentTodayGrowthShow('Today', statistics, shipmentRecords);


			$('.thisMonthShipments').text(shipmentRecords.thisMonthShipments);
			percentMonthGrowthShow('Month', statistics, shipmentRecords);

			$('.thisMonthOperatorCountryShipments').text(shipmentRecords.thisMonthOperatorCountryShipments);
			percentMonthGrowthShow('MonthOperatorCountry', statistics, shipmentRecords);

			$('.thisMonthInternationallyShipments').text(shipmentRecords.thisMonthInternationallyShipments);
			percentMonthGrowthShow('MonthInternationally', statistics, shipmentRecords);

			$('.thisMonthDropOffShipments').text(shipmentRecords.thisMonthDropOffShipments);
			percentMonthGrowthShow('MonthDropOff', statistics, shipmentRecords);

			$('.thisMonthPickupShipments').text(shipmentRecords.thisMonthPickupShipments);
			percentMonthGrowthShow('MonthPickup', statistics, shipmentRecords);

			$('.thisMonthConditionShipments').text(shipmentRecords.thisMonthConditionShipments);
			percentMonthGrowthShow('MonthCondition', statistics, shipmentRecords);

		}

		function shipmentTransactionStatistics(transactionRecords, basic) {
			let statistics = 'shipmentTransaction'
			if (transactionRecords.totalShipmentTransactions){
				$('.totalShipmentTransactions').text(`${basic.currency_symbol}` + parseFloat(transactionRecords.totalShipmentTransactions).toFixed(2));
			}else{
				$('.totalShipmentTransactions').text(`${basic.currency_symbol}0`);
			}

			if (transactionRecords.totalTodayShipmentTransactions){
				$('.totalTodayShipmentTransactions').text(`${basic.currency_symbol}` + parseFloat(transactionRecords.totalTodayShipmentTransactions).toFixed(2));
			}else{
				$('.totalTodayShipmentTransactions').text(`${basic.currency_symbol}0`);
			}
			percentTodayGrowthShow('Today', statistics, transactionRecords);

			if (transactionRecords.thisMonthShipmentTransactions){
				$('.thisMonthShipmentTransactions').text(`${basic.currency_symbol}` + parseFloat(transactionRecords.thisMonthShipmentTransactions).toFixed(2));
			}else{
				$('.thisMonthShipmentTransactions').text(`${basic.currency_symbol}0`);
			}
			percentMonthGrowthShow('Month', statistics, transactionRecords);

			if (transactionRecords.thisMonthOperatorCountryTransactions){
				$('.thisMonthOperatorCountryTransactions').text(`${basic.currency_symbol}` + parseFloat(transactionRecords.thisMonthOperatorCountryTransactions).toFixed(2));
			}else{
				$('.thisMonthOperatorCountryTransactions').text(`${basic.currency_symbol}0`);
			}
			percentMonthGrowthShow('MonthOperatorCountry', statistics, transactionRecords);

			if (transactionRecords.thisMonthInternationallyTransactions){
				$('.thisMonthInternationallyTransactions').text(`${basic.currency_symbol}` + parseFloat(transactionRecords.thisMonthInternationallyTransactions).toFixed(2));
			}else{
				$('.thisMonthInternationallyTransactions').text(`${basic.currency_symbol}0`);
			}
			percentMonthGrowthShow('MonthInternationally', statistics, transactionRecords);
		}

		function currentYearShipmentSummeryChart(yearShipmentChartRecords) {
			new Chart(document.getElementById("shipment-year-chart"), {
				type: 'bar',
				data: {
					labels: yearShipmentChartRecords.shipmentYearLabels,
					datasets: [
						{
							data: yearShipmentChartRecords.yearTotalShipments,
							label: "Total Shipments",
							borderColor: "#6B7EF8",
							backgroundColor: "#6B7EF8",
						},
						{
							data: yearShipmentChartRecords.yearOperatorCountryShipments,
							label: "Operate Country",
							borderColor: "#30ba90",
							backgroundColor: "#30ba90",
						},
						{
							data: yearShipmentChartRecords.yearInternationallyShipments,
							label: "Internationally",
							borderColor: "#FF6C22",
							backgroundColor: "#FF6C22",
						},
						{
							data: yearShipmentChartRecords.yearDropOffShipments,
							label: "Drop Off",
							borderColor: "#0174BE",
							backgroundColor: "#0174BE",
						},
						{
							data: yearShipmentChartRecords.yearPickupShipments,
							label: "Pickup",
							borderColor: "#CE5A67",
							backgroundColor: "#CE5A67",
						},
						{
							data: yearShipmentChartRecords.yearConditionShipments,
							label: "Condition",
							borderColor: "#F4CE14",
							backgroundColor: "#F4CE14",
						},
						{
							data: yearShipmentChartRecords.yearRequestShipments,
							label: "Requested",
							borderColor: "#0F0F0F",
							backgroundColor: "#0F0F0F",
						},

						{
							data: yearShipmentChartRecords.yearDeliveredShipments,
							label: "Delivered",
							borderColor: "#0174BE",
							backgroundColor: "#0174BE",
						},
						{
							data: yearShipmentChartRecords.yearReturnShipments,
							label: "Return Shipments",
							borderColor: "#C70039",
							backgroundColor: "#C70039",
						},
					]
				},
				options: {
					maintainAspectRatio: false,
					responsive: true,
					aspectRatio: 1,
					scales: {
						y: {
							beginAtZero: true
						}
					}
				}
			});
		}

		function currentYearShipmentTransactionChart(yearShipmentTransactionChartRecords) {
			new Chart(document.getElementById("shipments-transaction-current-year"), {
				type: 'bar',
				data: {
					labels: yearShipmentTransactionChartRecords.yearLabels,
					datasets: [
						{
							data: yearShipmentTransactionChartRecords.yearTotalShipmentTransactions,
							label: "Total Transactions",
							borderColor: "#5272F2",
							backgroundColor: "#5272F2",
						},
						{
							data: yearShipmentTransactionChartRecords.yearTotalOperatorCountryTransactions,
							label: "Operator Country",
							borderColor: "#5DD9AB",
							backgroundColor: "#5DD9AB",
						},
						{
							data: yearShipmentTransactionChartRecords.yearTotalInternationallyTransactions,
							label: "Internationally",
							borderColor: "#FD7389",
							backgroundColor: "#FD7389",
						},
						{
							data: yearShipmentTransactionChartRecords.yearTotalDropOffTransactions,
							label: "Drop Off",
							borderColor: "#FDC72E",
							backgroundColor: "#FDC72E",
						},
						{
							data: yearShipmentTransactionChartRecords.yearTotalPickupTransactions,
							label: "Pickup",
							borderColor: "#25CCF7",
							backgroundColor: "#25CCF7",
						},
						{
							data: yearShipmentTransactionChartRecords.yearTotalConditionTransactions,
							label: "Condition",
							borderColor: "#FF4322",
							backgroundColor: "#FF4322",
						},
					]
				},
				options: {
					maintainAspectRatio: false,
					responsive: true,
					aspectRatio: 1,
					scales: {
						y: {
							beginAtZero: true
						}
					}
				}
			});
		}

		function currentYearDepositPayoutChart(yearDepositPayoutChartRecords) {
			new Chart(document.getElementById("deposit_and_withdraw_transaction_chart"), {
				type: 'bar',
				data: {
					labels: yearDepositPayoutChartRecords.yearLabels,
					datasets: [
						{
							data: yearDepositPayoutChartRecords.yearDeposit,
							label: "Deposit",
							borderColor: "#F5AB35",
							backgroundColor: "#F5AB35",
						},
						{
							data: yearDepositPayoutChartRecords.yearPayout,
							label: "Withdraw",
							borderColor: "#596fdb",
							backgroundColor: "#596fdb",
						},
					]
				},
				options: {
					maintainAspectRatio: false,
					responsive: true,
					aspectRatio: 1,
					scales: {
						y: {
							beginAtZero: true
						}
					}
				}
			});
		}

		function currentYearDepositSummeryChart(yearDepositSummeryChartRecords) {
			new Chart(document.getElementById("deposit-summery-chart"), {
				type: 'pie',
				data: {
					labels: yearDepositSummeryChartRecords.paymentMethodeLabel,
					datasets: [{
						backgroundColor: ["#FFA10A", "#596FDB", "#3498db", "#9b59b6", "#34495e", "#16a085", "#27ae60", "#2980b9", "#8e44ad", "#2c3e50",
							"#f1c40f", "#e67e22", "#e74c3c", "#ecf0f1", "#95a5a6", "#f39c12", "#d35400", "#c0392b", "#bdc3c7", "#7f8c8d",
							"#55efc4", "#81ecec", "#74b9ff", "#a29bfe", "#dfe6e9",
						],
						data: yearDepositSummeryChartRecords.paymentMethodeData,
					}]
				},
				options: {
					maintainAspectRatio: false,
					responsive: true,
					aspectRatio: 1,
					scales: {
						y: {
							beginAtZero: true
						}
					},
					tooltips: {
						callbacks: {
							label: function (tooltipItems, data) {
								return data.labels[tooltipItems.index] + ': ' + data.datasets[0].data[tooltipItems.index] + yearDepositSummeryChartRecords.basicControl.base_currency_code;
							}
						}
					}
				}
			});
		}


		$(document).ready(function () {
			// daily shipment analytics
			$('#dailyShipments').daterangepicker({
				startDate: moment().startOf('month'),
				endDate: moment().endOf('month'),
				locale: {
					format: 'DD/MM/YYYY'
				},
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				},
				opens: 'right', // Set the position to top right
			}, function (start, end, label) {
				getDailyShipmentAnalytics(start.format('DD/MM/YYYY'), end.format('DD/MM/YYYY'));
			});


			function getDailyShipmentAnalytics(start, end) {
				Notiflix.Block.standard('#daily-shipments-line-chart');
				$.ajax({
					method: "GET",
					url: "{{ route('get.daily.shipment.analytics') }}",
					dataType: "json",
					data: {
						'start': start,
						'end': end,
					}
				}).done(function (response) {
					Notiflix.Block.remove('#daily-shipments-line-chart');
					new Chart(document.getElementById("daily-shipments-line-chart"), {
						type: 'line',
						data: {
							labels: response.labels,
							datasets: [

								{
									data: response.dataPendingShipment,
									label: "Requested",
									borderColor: "#0F0F0F",
									fill: false
								},
								{
									data: response.dataInQueueShipment,
									label: "In Queue",
									borderColor: "#39A7FF",
									fill: false
								},
								{
									data: response.dataDispatchShipment,
									label: "Dispatch",
									borderColor: "#FF6C22",
									fill: false
								},
								{
									data: response.dataReceivedShipment,
									label: "Received",
									borderColor: "#005B41",
									fill: false
								},
								{
									data: response.dataDeliveredShipment,
									label: "Delivered",
									borderColor: "#C70039",
									fill: false
								},
								{
									data: response.dataReturnInQueueShipment,
									label: "Return In Queue",
									borderColor: "#3085C3",
									fill: false
								},
								{
									data: response.dataReturnDispatchShipment,
									label: "Return Dispatch",
									borderColor: "#862B0D",
									fill: false
								},
								{
									data: response.dataReturnReceivedShipment,
									label: "Return Received",
									borderColor: "#00DFA2",
									fill: false
								},
								{
									data: response.dataReturnDeliveredShipment,
									label: "Return Delivered",
									borderColor: "#E11299",
									fill: false
								},
							]
						},
						options: {
							maintainAspectRatio: false,
							responsive: true,
							aspectRatio: 1,
							scales: {
								y: {
									beginAtZero: true
								}
							}
						}
					});
				});
			}

			getDailyShipmentAnalytics(moment().startOf('month').format('DD/MM/YYYY'), moment().endOf('month').format('DD/MM/YYYY'));


			// daily shipment transactions analytics
			$('#dailyShipmentTransactions').daterangepicker({
				startDate: moment().startOf('month'),
				endDate: moment().endOf('month'),
				locale: {
					format: 'DD/MM/YYYY'
				},
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				},
				opens: 'right', // Set the position to top right
			}, function (start, end, label) {
				getDailyShipmentTransactionsAnalytics(start.format('DD/MM/YYYY'), end.format('DD/MM/YYYY'));
			});

			function getDailyShipmentTransactionsAnalytics(start, end) {
				$.ajax({
					method: "GET",
					url: "{{ route('get.daily.shipment.transactions.analytics') }}",
					dataType: "json",
					data: {
						'start': start,
						'end': end,
					}
				}).done(function (response) {
					new Chart(document.getElementById("daily-shipment-transactions-line-chart"), {
						type: 'line',
						data: {
							labels: response.labels,
							datasets: [
								{
									data: response.dataTotalShipmentTransactions,
									label: "Total Transactions",
									borderColor: "#4E4FEB",
									fill: false
								},
								{
									data: response.dataTotalOperatorCountryTransactions,
									label: "Operator Country",
									borderColor: "#FFB23E",
									fill: false
								},
								{
									data: response.dataTotalInternationallyTransactions,
									label: "Internationally",
									borderColor: "#F94C10",
									fill: false
								},
								{
									data: response.dataDropOffTransactions,
									label: "Drop Off",
									borderColor: "#009FBD",
									fill: false
								},

								{
									data: response.dataPickupTransactions,
									label: "Pickup",
									borderColor: "#E11299",
									fill: false
								},

								{
									data: response.dataConditionTransactions,
									label: "Condition",
									borderColor: "#E21818",
									fill: false
								},
							]
						},
						options: {
							maintainAspectRatio: false,
							responsive: true,
							aspectRatio: 1,
							scales: {
								y: {
									beginAtZero: true
								}
							}
						}
					});
				});
			}


			getDailyShipmentTransactionsAnalytics(moment().startOf('month').format('DD/MM/YYYY'), moment().endOf('month').format('DD/MM/YYYY'));


			// Daily browser history analytics
			$('#dailyBrowserHistory').daterangepicker({
				startDate: moment().startOf('month'),
				endDate: moment().endOf('month'),
				locale: {
					format: 'DD/MM/YYYY'
				},
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				},
				opens: 'right', // Set the position to top right
			}, function (start, end, label) {
				getDailyBrowserHistoryAnalytics(start.format('DD/MM/YYYY'), end.format('DD/MM/YYYY'));
			});

			function getDailyBrowserHistoryAnalytics(start, end) {
				$.ajax({
					method: "GET",
					url: "{{ route('get.daily.browser.history.analytics') }}",
					dataType: "json",
					data: {
						'start': start,
						'end': end,
					}
				}).done(function (response) {
					const browserCounts = response.userCreationBrowserData;
					const keys = Object.keys(browserCounts);
					const values = Object.values(browserCounts);
					new Chart(document.getElementById("browserHistory"), {
						type: 'doughnut',
						data: {
							labels: keys,
							datasets: [{
								backgroundColor: ['#FFC107', '#015EC2', '#006CFF', "#1EBBEE", "#B15EFF", "#FFA33C", "#CE5A67", "#610C9F", "#FF4B91", "#2c3e50",
									"#f1c40f", "#e67e22", "#e74c3c", "#ecf0f1", "#95a5a6", "#f39c12", "#d35400", "#c0392b", "#bdc3c7", "#7f8c8d",
									"#55efc4", "#81ecec", "#74b9ff", "#a29bfe", "#dfe6e9",
								],
								data: values
							}]
						},
						options: {
							maintainAspectRatio: false,
							responsive: true,
							aspectRatio: 1,
							scales: {
								y: {
									beginAtZero: true
								}
							},
							tooltips: {
								callbacks: {
									label: function (tooltipItems, data) {
										return data.labels[tooltipItems.index] + ': ' + data.datasets[0].data[tooltipItems.index] + " {{ __($basicControl->base_currency_code) }}";
									}
								}
							}
						}
					});

				});
			}

			getDailyBrowserHistoryAnalytics(moment().startOf('month').format('DD/MM/YYYY'), moment().endOf('month').format('DD/MM/YYYY'));


			// Daily Operating System History Analytics
			$('#dailyOperatingSystemHistory').daterangepicker({
				startDate: moment().startOf('month'),
				endDate: moment().endOf('month'),
				locale: {
					format: 'DD/MM/YYYY'
				},
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				},
				opens: 'right', // Set the position to top right
			}, function (start, end, label) {
				getDailyOperatingSystemHistoryAnalytics(start.format('DD/MM/YYYY'), end.format('DD/MM/YYYY'));
			});

			function getDailyOperatingSystemHistoryAnalytics(start, end) {
				$.ajax({
					method: "GET",
					url: "{{ route('get.daily.operating.system.history.analytics') }}",
					dataType: "json",
					data: {
						'start': start,
						'end': end,
					}
				}).done(function (response) {
					const browserCounts = response.userCreationOSData;
					const keys = Object.keys(browserCounts);
					const values = Object.values(browserCounts);

					new Chart(document.getElementById("operatingSystemHistory"), {
						type: 'doughnut',
						data: {
							labels: keys,
							datasets: [{
								backgroundColor: ['#0055B0', '#a4c639', '#77216f', "#B15EFF", "#B15EFF", "#FFA33C", "#CE5A67", "#610C9F", "#FF4B91", "#2c3e50",
									"#f1c40f", "#e67e22", "#e74c3c", "#ecf0f1", "#95a5a6", "#f39c12", "#d35400", "#c0392b", "#bdc3c7", "#7f8c8d",
									"#55efc4", "#81ecec", "#74b9ff", "#a29bfe", "#dfe6e9",
								],
								data: values
							}]
						},
						options: {
							maintainAspectRatio: false,
							responsive: true,
							aspectRatio: 1,
							scales: {
								y: {
									beginAtZero: true
								}
							},
							tooltips: {
								callbacks: {
									label: function (tooltipItems, data) {
										return data.labels[tooltipItems.index] + ': ' + data.datasets[0].data[tooltipItems.index] + " {{ __($basicControl->base_currency_code) }}";
									}
								}
							}
						}
					});
				});
			}


			getDailyOperatingSystemHistoryAnalytics(moment().startOf('month').format('DD/MM/YYYY'), moment().endOf('month').format('DD/MM/YYYY'));


			// Daily Device History Analytics
			$('#dailyDeviceHistory').daterangepicker({
				startDate: moment().startOf('month'),
				endDate: moment().endOf('month'),
				locale: {
					format: 'DD/MM/YYYY'
				},
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				},
				opens: 'right', // Set the position to top right
			}, function (start, end, label) {
				getDailyDeviceHistoryAnalytics(start.format('DD/MM/YYYY'), end.format('DD/MM/YYYY'));
			});

			function getDailyDeviceHistoryAnalytics(start, end) {
				$.ajax({
					method: "GET",
					url: "{{ route('get.daily.device.history.analytics') }}",
					dataType: "json",
					data: {
						'start': start,
						'end': end,
					}
				}).done(function (response) {
					const browserCounts = response.userCreationDeviceData;
					const keys = Object.keys(browserCounts);
					const values = Object.values(browserCounts);

					new Chart(document.getElementById("deviceHistory"), {
						type: 'doughnut',
						data: {
							labels: keys,
							datasets: [{
								backgroundColor: ['#7175F5', '#14C1E7', '#77D748', "#B15EFF", "#B15EFF", "#FFA33C", "#CE5A67", "#610C9F", "#FF4B91", "#2c3e50",
									"#f1c40f", "#e67e22", "#e74c3c", "#ecf0f1", "#95a5a6", "#f39c12", "#d35400", "#c0392b", "#bdc3c7", "#7f8c8d",
									"#55efc4", "#81ecec", "#74b9ff", "#a29bfe", "#dfe6e9",
								],
								data: values
							}]
						},
						options: {
							maintainAspectRatio: false,
							responsive: true,
							aspectRatio: 1,
							scales: {
								y: {
									beginAtZero: true
								}
							},
							tooltips: {
								callbacks: {
									label: function (tooltipItems, data) {
										return data.labels[tooltipItems.index] + ': ' + data.datasets[0].data[tooltipItems.index] + " {{ __($basicControl->base_currency_code) }}";
									}
								}
							}
						}
					});
				});
			}

			getDailyDeviceHistoryAnalytics(moment().startOf('month').format('DD/MM/YYYY'), moment().endOf('month').format('DD/MM/YYYY'));


		});

		$(document).ready(function () {
			let isActiveCronNotification = '{{ $basicControl->is_active_cron_notification }}';
			if (isActiveCronNotification == 1)
				$('#cron-info').modal('show');
			$(document).on('click', '.copy-btn', function () {
				var _this = $(this)[0];
				var copyText = $(this).parents('.input-group-append').siblings('input');
				$(copyText).prop('disabled', false);
				copyText.select();
				document.execCommand("copy");
				$(copyText).prop('disabled', true);
				$(this).text('Coppied');
				setTimeout(function () {
					$(_this).text('');
					$(_this).html('<i class="fas fa-copy"></i>');
				}, 500)
			});
		})
	</script>
@endsection

@if($firebaseNotify)
	@push('extra_scripts')
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
											url: "{{ route('admin.save.token') }}",
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
					admin_foreground: '',
					admin_background: '',
					notificationPermission: Notification.permission,
					is_notification_skipped: sessionStorage.getItem('is_notification_skipped') == '1'
				},
				mounted() {
					this.admin_foreground = "{{$firebaseNotify->admin_foreground}}";
					this.admin_background = "{{$firebaseNotify->admin_background}}";
				},
				methods: {
					skipNotification() {
						sessionStorage.setItem('is_notification_skipped', '1');
						this.is_notification_skipped = true;
					}
				}
			});
		</script>

	@endpush
@endif
