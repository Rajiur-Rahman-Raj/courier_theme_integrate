<!-- Sidebar -->
<div class="main-sidebar sidebar-style-2 shadow-sm">
	<aside id="sidebar-wrapper">
		<div class="sidebar-brand">
			<a href="{{url('/')}}" target="_blank">
				<img src="{{ getFile(config('basic.default_file_driver'),config('basic.logo_image')) }}"
					 class="dashboard-logo"
					 alt="@lang('Logo')">
			</a>
		</div>

		<div class="sidebar-brand sidebar-brand-sm">
			<a href="{{ route('admin.home') }}">
				<img src="{{ getFile(config('basic.default_file_driver'),config('basic.favicon_image')) }}"
					 class="dashboard-logo-sm" alt="@lang('Logo')">
			</a>
		</div>

		<ul class="sidebar-menu">
			@if(adminAccessRoute(array_merge(config('permissionList.Admin_Dashboard.Dashboard.permission.view'))))
				<li class="menu-header">@lang('Dashboard')</li>
				<li class="dropdown {{ activeMenu(['admin.home']) }}">
					<a href="{{ route('admin.home')}}" class="nav-link"> <i
							class="fas fa-tachometer-alt text-primary mt-1"></i> <span>@lang('Dashboard')</span></a>
				</li>
			@endif

			@if(adminAccessRoute(array_merge(config('permissionList.Manage_Shipments.Shipment_List.permission.view'))))
				<li class="menu-header">@lang('Manage Shipments')</li>
				<li class="dropdown {{ activeMenu(['shipmentList', 'createShipment', 'editShipment', 'viewShipment', 'trashShipmentList']) }}">
					<a href="javascript:void(0)" class="nav-link has-dropdown" data-toggle="dropdown">
						<i class="fas fa-truck text-purple mt-1"></i> <span>@lang('Shipments Order')</span>
					</a>
					<ul class="dropdown-menu">
						<li class="{{ activeMenu(['shipmentList'], null, 'requested') }}">
							<a class="nav-link "
							   href="{{ route('shipmentList', ['shipment_status' => 'requested', 'shipment_type' => 'operator-country']) }}">
								@lang('Requested Shipment')
							</a>
						</li>

						<li class="{{ activeMenu(['shipmentList'], null, 'in_queue') }}">
							<a class="nav-link "
							   href="{{ route('shipmentList', ['shipment_status' => 'in_queue', 'shipment_type' => 'operator-country']) }}">
								@lang('In Queue Shipment')
							</a>
						</li>

						<li class="{{ activeMenu(['shipmentList'], null, 'dispatch') }}">
							<a class="nav-link "
							   href="{{ route('shipmentList', ['shipment_status' => 'dispatch', 'shipment_type' => 'operator-country']) }}">
								@lang('Dispatch Shipment')
							</a>
						</li>

						<li class="{{ activeMenu(['shipmentList'], null, 'upcoming') }}">
							<a class="nav-link "
							   href="{{ route('shipmentList', ['shipment_status' => 'upcoming', 'shipment_type' => 'operator-country']) }}">
								@lang('Upcoming Shipment')
							</a>
						</li>

						<li class="{{ activeMenu(['shipmentList'], null, 'received') }}">
							<a class="nav-link "
							   href="{{ route('shipmentList', ['shipment_status' => 'received', 'shipment_type' => 'operator-country']) }}">
								@lang('Received Shipment')
							</a>
						</li>

						<li class="{{ activeMenu(['shipmentList'], null, 'delivered') }}">
							<a class="nav-link "
							   href="{{ route('shipmentList', ['shipment_status' => 'delivered', 'shipment_type' => 'operator-country']) }}">
								@lang('Delivered Shipment')
							</a>
						</li>


						<li class="{{ activeMenu(['shipmentList'], null, 'assign_to_collect') }}">
							<a class="nav-link "
							   href="{{ route('shipmentList', ['shipment_status' => 'assign_to_collect', 'shipment_type' => 'operator-country']) }}">
								@lang('Assigned To Collect')
							</a>
						</li>

						<li class="{{ activeMenu(['shipmentList'], null, 'assign_to_delivery') }}">
							<a class="nav-link "
							   href="{{ route('shipmentList', ['shipment_status' => 'assign_to_delivery', 'shipment_type' => 'operator-country']) }}">
								@lang('Assigned To Delivery')
							</a>
						</li>

						<li class="{{ activeMenu(['shipmentList'], null, 'return_in_queue') }}">
							<a class="nav-link "
							   href="{{ route('shipmentList', ['shipment_status' => 'return_in_queue', 'shipment_type' => 'operator-country']) }}">
								@lang('Return In Queue')
							</a>
						</li>

						<li class="{{ activeMenu(['shipmentList'], null, 'return_in_dispatch') }}">
							<a class="nav-link "
							   href="{{ route('shipmentList', ['shipment_status' => 'return_in_dispatch', 'shipment_type' => 'operator-country']) }}">
								@lang('Return In Dispatch')
							</a>
						</li>

						<li class="{{ activeMenu(['shipmentList'], null, 'return_in_upcoming') }}">
							<a class="nav-link "
							   href="{{ route('shipmentList', ['shipment_status' => 'return_in_upcoming', 'shipment_type' => 'operator-country']) }}">
								@lang('Return In Upcoming')
							</a>
						</li>

						<li class="{{ activeMenu(['shipmentList'], null, 'return_in_received') }}">
							<a class="nav-link "
							   href="{{ route('shipmentList', ['shipment_status' => 'return_in_received', 'shipment_type' => 'operator-country']) }}">
								@lang('Return In Received')
							</a>
						</li>

						<li class="{{ activeMenu(['shipmentList'], null, 'return_in_delivered') }}">
							<a class="nav-link "
							   href="{{ route('shipmentList', ['shipment_status' => 'return_in_delivered', 'shipment_type' => 'operator-country']) }}">
								@lang('Return In Delivered')
							</a>
						</li>

					</ul>
				</li>
			@endif


			<li class="menu-header">@lang('Shipment Settings')</li>

			@if(adminAccessRoute(config('permissionList.Shipment_Types.Shipment_Type_List.permission.view')))
				<li class="dropdown {{ activeMenu(['shipmentTypeList']) }}">
					<a href="{{ route('shipmentTypeList') }}" class="nav-link"> <i
							class="fas fa-text-width text-danger mt-1"></i> <span>@lang('Shipment Types')</span></a>
				</li>
			@endif
			@if(adminAccessRoute(array_merge(config('permissionList.Shipping_Rates.Default_Rate.permission.view'), config('permissionList.Shipping_Rates.Operator_Country_Rate.permission.view'), config('permissionList.Shipping_Rates.Internationally_Rate.permission.view'))))
				<li class="dropdown {{ activeMenu(['defaultRate', 'operatorCountryRate', 'internationallyRate', 'createShippingRateOperatorCountry', 'operatorCountryShowRate', 'internationallyShowRate', 'createShippingRateInternationally']) }}">
					<a href="javascript:void(0)" class="nav-link has-dropdown" data-toggle="dropdown">
						<i class="fas fa-percent text-info mt-1"></i> <span>@lang('Shipping Rates')</span>
					</a>
					<ul class="dropdown-menu">
						@if(adminAccessRoute(config('permissionList.Shipping_Rates.Default_Rate.permission.view')))
							<li class="{{ activeMenu(['defaultRate']) }}">
								<a class="nav-link " href="{{ route('defaultRate') }}">
									@lang('Default Rate')
								</a>
							</li>
						@endif

						@if(adminAccessRoute(config('permissionList.Shipping_Rates.Operator_Country_Rate.permission.view')))
							<li class="{{ activeMenu(['operatorCountryRate', 'createShippingRateOperatorCountry', 'operatorCountryShowRate']) }}">
								<a class="nav-link " href="{{ route('operatorCountryRate', 'state') }}">
									@lang('Operator Country Rate')
								</a>
							</li>
						@endif

						@if(adminAccessRoute(config('permissionList.Shipping_Rates.Internationally_Rate.permission.view')))
							<li class="{{ activeMenu(['internationallyRate', 'internationallyShowRate', 'createShippingRateInternationally']) }}">
								<a class="nav-link " href="{{ route('internationallyRate', 'country') }}">
									@lang('Internationally Rate')
								</a>
							</li>
						@endif
					</ul>
				</li>
			@endif

			@if(adminAccessRoute(config('permissionList.Packaging_Service.Service_List.permission.view')))
				<li class="dropdown {{ activeMenu(['packingServiceList']) }}">
					<a href="{{ route('packingServiceList') }}" class="nav-link"> <i
							class="fas fa-box-open text-warning mt-1"></i> <span>@lang('Packaging Service')</span></a>
				</li>
			@endif

			@if(adminAccessRoute(config('permissionList.Parcel_Service.Service_List.permission.view')))
				<li class="dropdown {{ activeMenu(['parcelServiceList']) }}">
					<a href="{{ route('parcelServiceList') }}" class="nav-link"> <i
							class="fas fa-cubes text-primary mt-1"></i> <span>@lang('Parcel Service')</span></a>
				</li>
			@endif

			<li class="menu-header">@lang('Logistics Hub')</li>

			@if(adminAccessRoute(config('permissionList.Manage_Branch.Branch_List.permission.view')))
				<li class="dropdown {{ activeMenu(['branchList', 'createBranch', 'branchEdit', 'showBranchProfile']) }}">
					<a href="{{ route('branchList') }}" class="nav-link"> <i
							class="fab fa-pagelines text-danger mt-1"></i> <span>@lang('Branches')</span></a>
				</li>
			@endif

			@if(adminAccessRoute(config('permissionList.Manage_Branch.Branch_Manager.permission.view')))
				<li class="dropdown {{ activeMenu(['branchManagerList', 'createBranchManager', 'branchManagerEdit', 'branchStaffList']) }}">
					<a href="{{ route('branchManagerList') }}" class="nav-link">  <i class="fa fa-users text-dark mt-1"></i> <span>@lang('Managers')</span></a>
				</li>
			@endif

			@if(adminAccessRoute(config('permissionList.Manage_Branch.Driver_List.permission.view')))
				<li class="dropdown {{ activeMenu(['branchDriverList', 'createDriver', 'branchDriverEdit']) }}">
					<a href="{{ route('branchDriverList') }}" class="nav-link"> <i
							class="fas fa-bicycle text-primary mt-1"></i> <span>@lang('Drivers')</span></a>
				</li>
			@endif

			@if(adminAccessRoute(config('permissionList.Manage_Branch.Employee_List.permission.view')))
				<li class="dropdown {{ activeMenu(['branchEmployeeList', 'createEmployee', 'branchEmployeeEdit']) }}">
					<a href="{{ route('branchEmployeeList') }}" class="nav-link"> <i
							class="fas fa-users text-dark mt-1"></i> <span>@lang('Employees')</span></a>
				</li>
			@endif
			@if(adminAccessRoute(config('permissionList.Manage_Department.Department_List.permission.view')))
				<li class="dropdown {{ activeMenu(['departmentList', 'createDepartment', 'editDepartment']) }}">
					<a href="{{ route('departmentList') }}">
						<i class="fas fa-graduation-cap text-purple mt-1"></i> <span>@lang('Departments')</span>
					</a>
				</li>
			@endif
			@if(adminAccessRoute(config('permissionList.Parcel_Service.Service_List.permission.view')))
				<li class="dropdown {{ activeMenu(['clientList', 'createClient', 'clientEdit']) }}">
					<a href="{{ route('clientList') }}" class="nav-link"> <i
							class="fas fa-users text-orange mt-1"></i> <span>@lang('Customers')</span></a>
				</li>
			@endif

			@if(adminAccessRoute(array_merge(config('permissionList.Manage_Locations.Country_List.permission.view'), config('permissionList.Manage_Locations.State_List.permission.view'), config('permissionList.Manage_Locations.City_List.permission.view'), config('permissionList.Manage_Locations.Area_List.permission.view'))))
				<li class="menu-header">@lang('Manage Locations')</li>
				<li class="dropdown {{ activeMenu(['areaList', 'countryList', 'stateList', 'cityList']) }}">
					<a href="javascript:void(0)" class="nav-link has-dropdown" data-toggle="dropdown">
						<i class="fas fa-map-marker-alt mt-1 text-warning"></i> <span>@lang('Manage Locations')</span>
					</a>
					<ul class="dropdown-menu">
						@if(adminAccessRoute(config('permissionList.Manage_Locations.Country_List.permission.view')))
							<li class="{{ activeMenu(['countryList']) }}">
								<a class="nav-link " href="{{ route('countryList') }}">
									@lang('Country List')
								</a>
							</li>
						@endif

						@if(adminAccessRoute(config('permissionList.Manage_Locations.State_List.permission.view')))
							<li class="{{ activeMenu(['stateList']) }}">
								<a class="nav-link " href="{{ route('stateList', ['state-list']) }}">
									@lang('State List')
								</a>
							</li>
						@endif

						@if(adminAccessRoute(config('permissionList.Manage_Locations.City_List.permission.view')))
							<li class="{{ activeMenu(['cityList']) }}">
								<a class="nav-link" href="{{ route('cityList', ['city-list']) }}">
									@lang('City List')
								</a>
							</li>
						@endif

						@if(adminAccessRoute(config('permissionList.Manage_Locations.Area_List.permission.view')))
							<li class="{{ activeMenu(['areaList']) }}">
								<a class="nav-link " href="{{ route('areaList', ['area-list']) }}">
									@lang('Area List')
								</a>
							</li>
						@endif
					</ul>
				</li>
			@endif


			@if(adminAccessRoute(array_merge(config('permissionList.Manage_Reports.Shipment_Report.permission.view'), config('permissionList.Manage_Reports.Shipment_Transaction.permission.view'))))
				<li class="menu-header">@lang('Shipment Reports')</li>
				<li class="dropdown {{ activeMenu(['shipmentReport', 'shipmentReportCount', 'shipmentTransactionReport']) }}">
					<a href="javascript:void(0)" class="nav-link has-dropdown" data-toggle="dropdown">
						<i class="fas fa-file-excel mt-1 text-success"></i> <span>@lang('Reports')</span>
					</a>
					<ul class="dropdown-menu">
						@if(adminAccessRoute(config('permissionList.Manage_Reports.Shipment_Report.permission.view')))
							<li class="{{ activeMenu(['shipmentReport']) }}">
								<a class="nav-link"
								   href="{{ route('shipmentReport') }}">
									@lang('Shipment Report')
								</a>
							</li>
						@endif

						@if(adminAccessRoute(config('permissionList.Manage_Reports.Shipment_Transaction.permission.view')))
							<li class="{{ activeMenu(['shipmentTransactionReport']) }}">
								<a class="nav-link "
								   href="{{ route('shipmentTransactionReport') }}">
									@lang('Shipment Transaction')
								</a>
							</li>
						@endif
					</ul>
				</li>
			@endif

			@if(adminAccessRoute(config('permissionList.User_Panel.Manage_Users.permission.view')))
				<li class="menu-header">@lang('User Panel')</li>
				<li class="dropdown {{ activeMenu(['user-list','user.search','inactive.user.search','send.mail.user','inactive.user.list']) }}">
					<a href="javascript:void(0)" class="nav-link has-dropdown" data-toggle="dropdown">
						<i class="fas fa-users text-primary mt-1"></i> <span>@lang('Manage Users')</span>
					</a>
					<ul class="dropdown-menu">
						<li class="{{ activeMenu(['user-list','user.search']) }}">
							<a class="nav-link " href="{{ route('user-list') }}">
								@lang('All User')
							</a>
						</li>
						<li class="{{ activeMenu(['inactive.user.list','inactive.user.search']) }}">
							<a class="nav-link" href="{{ route('inactive.user.list') }}">
								@lang('Inactive User')
							</a>
						</li>
						<li class="{{ activeMenu(['send.mail.user']) }}">
							<a class="nav-link" href="{{ route('send.mail.user') }}">
								@lang('Send Mail All User')
							</a>
						</li>
					</ul>
				</li>
			@endif

			@if(adminAccessRoute(array_merge(config('permissionList.Transactions.Add_Fund_List.permission.view'), config('permissionList.Transactions.Payout_List.permission.view'), config('permissionList.Transactions.Transaction_List.permission.view'))))
				<li class="menu-header">@lang('Transactions')</li>
				@if(adminAccessRoute(config('permissionList.Transactions.Add_Fund_List.permission.view')))
					<li class="dropdown {{ activeMenu(['admin.fund.add.index','admin.fund.add.search']) }}">
						<a href="{{ route('admin.fund.add.index') }}" class="nav-link"><i
								class="fas fa-money-check-alt text-success mt-1"></i><span>@lang('Fund Transactions')</span></a>
					</li>
				@endif

				@if(adminAccessRoute(config('permissionList.Transactions.Payout_List.permission.view')))
					<li class="dropdown {{ activeMenu(['admin.payout.index','admin.payout.search','payout.details']) }}">
						<a href="{{ route('admin.payout.index') }}" class="nav-link"><i
								class="far fa-money-bill-alt text-danger mt-1"></i><span>@lang('Payout Transactions')</span></a>
					</li>
				@endif

				@if(adminAccessRoute(config('permissionList.Transactions.Transaction_List.permission.view')))
					<li class="dropdown {{ activeMenu(['admin.transaction.index','admin.transaction.search']) }}">
						<a href="{{ route('admin.transaction.index') }}" class="nav-link"><i
								class="fas fa-chart-line text-purple mt-1"></i><span>@lang('Shipment Transactions')</span></a>
					</li>
				@endif
			@endif

			@if(adminAccessRoute(array_merge(config('permissionList.Payment_Settings.Payment_Methods.permission.view'), config('permissionList.Payment_Settings.Manual_Gateway.permission.view'), config('permissionList.Payment_Settings.Payment_Request.permission.view'), config('permissionList.Payment_Settings.Payment_Log.permission.view'))))
				<li class="menu-header">@lang('Payment Settings')</li>
				<li class="dropdown {{ activeMenu(['payment.methods','edit.payment.methods','admin.deposit.manual.index','admin.deposit.manual.create','admin.deposit.manual.edit', 'admin.payment.pending', 'admin.payment.log']) }}">
					<a href="javascript:void(0)" class="nav-link has-dropdown" data-toggle="dropdown">
						<i class="fas fa-money-check-alt text-success mt-1"></i>
						<span>@lang('Payment Settings')</span>
					</a>
					<ul class="dropdown-menu">
						@if(adminAccessRoute(config('permissionList.Payment_Settings.Payment_Methods.permission.view')))
							<li class="{{ activeMenu(['payment.methods','edit.payment.methods']) }}">
								<a class="nav-link" href="{{ route('payment.methods') }}">
									@lang('Payment Methods')
								</a>
							</li>
						@endif

						@if(adminAccessRoute(config('permissionList.Payment_Settings.Manual_Gateway.permission.view')))
							<li class="{{ activeMenu(['admin.deposit.manual.index','admin.deposit.manual.create','admin.deposit.manual.edit']) }}">
								<a class="nav-link" href="{{route('admin.deposit.manual.index')}}">
									@lang('Manual Gateway')
								</a>
							</li>
						@endif

						@if(adminAccessRoute(config('permissionList.Payment_Settings.Payment_Request.permission.view')))
							<li class="{{ activeMenu(['admin.payment.pending']) }}">
								<a class="nav-link" href="{{route('admin.payment.pending')}}">
									@lang('Payment Request')
								</a>
							</li>
						@endif

						@if(adminAccessRoute(config('permissionList.Payment_Settings.Payment_Log.permission.view')))
							<li class="{{ activeMenu(['admin.payment.log','admin.payment.search']) }}">
								<a class="nav-link" href="{{route('admin.payment.log')}}">
									@lang('Payment Log')
								</a>
							</li>
						@endif
					</ul>
				</li>
			@endif


			@if(adminAccessRoute(config('permissionList.Payout_Settings.Payout_Methods.permission.view')))
				<li class="dropdown {{ activeMenu(['payout.method.list','payout.method.add','payout.method.edit']) }}">
					<a href="javascript:void(0)" class="nav-link has-dropdown" data-toggle="dropdown">
						<i class="fas fa-users-cog text-danger mt-1"></i> <span>@lang('Payout Settings')</span>
					</a>
					<ul class="dropdown-menu">
						<li class="{{ activeMenu(['payout.method.list','payout.method.edit']) }}">
							<a class="nav-link" href="{{ route('payout.method.list') }}">
								@lang('Available Methods')
							</a>
						</li>
						<li class="{{ activeMenu(['payout.method.add']) }}">
							<a class="nav-link" href="{{ route('payout.method.add') }}">
								@lang('Add Method')
							</a>
						</li>
					</ul>
				</li>
			@endif

			@if(adminAccessRoute(array_merge(config('permissionList.Role_And_Permissions.Available_Roles.permission.view'), config('permissionList.Role_And_Permissions.Manage_Staff.permission.view'))))
				<li class="menu-header">@lang('Role & Permissions')</li>
				<li class="dropdown {{ activeMenu(['admin.role','admin.role.staff']) }}">
					<a href="javascript:void(0)" class="nav-link has-dropdown" data-toggle="dropdown">
						<i class="fas fa-user-friends text-purple mt-1"></i>
						<span>@lang('Roles and Permission')</span>
					</a>

					<ul class="dropdown-menu">
						@if(adminAccessRoute(config('permissionList.Role_And_Permissions.Available_Roles.permission.view')))
							<li class="{{ activeMenu(['admin.role']) }}">
								<a class="nav-link" href="{{ route('admin.role') }}">
									@lang('Available Roles')
								</a>
							</li>
						@endif
						@if(adminAccessRoute(config('permissionList.Role_And_Permissions.Manage_Staff.permission.view')))
							<li class="{{ activeMenu(['admin.role.staff']) }}">
								<a class="nav-link" href="{{ route('admin.role.staff') }}">
									@lang('Manage Staffs')
								</a>
							</li>
						@endif
					</ul>
				</li>
			@endif

			@if(adminAccessRoute(config('permissionList.Support_Tickets.Tickets.permission.view')))
				<li class="menu-header">@lang('Support Tickets')</li>
				<li class="dropdown {{ activeMenu(['admin.ticket','admin.ticket.view','admin.ticket.search']) }}">
					<a href="{{ route('admin.ticket') }}" class="nav-link"><i
							class="fas fa-headset text-info mt-1"></i><span>@lang('Tickets')</span></a>
				</li>
			@endif

			@if(adminAccessRoute(config('permissionList.Control_Panel.Control_Panel.permission.view')))
				<li class="menu-header">@lang('Control Panel')</li>
				<li class="dropdown {{ activeMenu(['settings','seo.update','plugin.config','tawk.control','google.analytics.control','google.recaptcha.control','fb.messenger.control','service.control','logo.update','breadcrumb.update','seo.update','currency.exchange.api.config','sms.config', 'sms.template.index','sms.template.edit','voucher.settings','basic.control','securityQuestion.index','securityQuestion.create','securityQuestion.edit','pusher.config','notify.template.index','notify.template.edit','language.index','language.create', 'language.edit','language.keyword.edit', 'email.config','email.template.index','email.template.default', 'email.template.edit', 'charge.index', 'charge.edit', 'currency.index', 'currency.create', 'currency.edit', 'charge.chargeEdit' ]) }}">
					<a href="{{ route('settings') }}" class="nav-link"><i
							class="fas fa-cog text-primary mt-1"></i><span>@lang('Control Panel')</span></a>
				</li>
			@endif


			@if(adminAccessRoute(array_merge(config('permissionList.Theme_Settings.Ui_Settings.permission.view'), config('permissionList.Theme_Settings.Content_Settings.permission.view'))))
				<li class="menu-header">@lang('Theme Settings')</li>
				@if(adminAccessRoute(config('permissionList.Theme_Settings.Ui_Settings.permission.view')))
					<li class="dropdown {{ activeMenu(['template.show']) }}">
						<a href="javascript:void(0)" class="nav-link has-dropdown" data-toggle="dropdown">
							<i class="fas fa-tasks text-info mt-1"></i> <span>@lang('Section Heading')</span>
						</a>
						<ul class="dropdown-menu">
							@foreach(array_diff(array_keys(config('templates')),['message','template_media']) as $name)
								<li class="{{ activeMenu(['template.show'],$name) }}">
									<a class="nav-link" href="{{ route('template.show',$name) }}">
										@lang(ucfirst(kebab2Title($name))) @lang('Section')
									</a>
								</li>
							@endforeach
						</ul>
					</li>
				@endif

				@if(adminAccessRoute(config('permissionList.Theme_Settings.Content_Settings.permission.view')))
					<li class="dropdown {{ activeMenu(['content.index','content.create','content.show']) }}">
						<a href="javascript:void(0)" class="nav-link has-dropdown" data-toggle="dropdown">
							<i class="fas fa-cogs text-primary mt-1"></i> <span>@lang('Content Settings')</span>
						</a>
						<ul class="dropdown-menu">
							@foreach(array_diff(array_keys(config('contents')),['message','content_media']) as $name)
								<li class="{{ activeMenu(['content.index','content.create','content.show'],$name) }}">
									<a class="nav-link" href="{{ route('content.index',$name) }}">
										@lang(ucfirst(kebab2Title($name)))
									</a>
								</li>
							@endforeach
						</ul>
					</li>
				@endif
			@endif

			@if(adminAccessRoute(array_merge(config('permissionList.Blog_Settings.Category_List.permission.view'), config('permissionList.Blog_Settings.Blog_List.permission.view'))))
				<li class="dropdown {{ activeMenu(['blogCategory','blogCategoryEdit','blogCategoryCreate', 'blogList', 'blogCreate', 'blogEdit']) }}">
					<a href="javascript:void(0)" class="nav-link has-dropdown" data-toggle="dropdown">
						<i class="fas fa-newspaper mt-1 text-warning"></i> <span>@lang('Blog Settings')</span>
					</a>

					<ul class="dropdown-menu">
						<li class="{{ activeMenu(['blogCategory','blogCategoryCreate', 'blogCategoryEdit']) }}">
							<a class="nav-link " href="{{route('blogCategory')}}">
								@lang('Category List')
							</a>
						</li>
						<li class="{{ activeMenu(['blogList','blogCreate', 'blogEdit']) }}">
							<a class="nav-link" href="{{ route('blogList') }}">
								@lang('Blog List')
							</a>
						</li>
					</ul>
				</li>
			@endif


			@foreach(config("generalsettings.settings") as $key => $detail)
				@if(isset($detail['route']))
					<li class="dropdown d-none {{ activeMenu(['admin.ticket','admin.ticket.view','admin.ticket.search']) }}">
						<a href="{{ getRoute($detail['route'], $detail['route_segment'] ?? null) }}" class="nav-link"><i
								class="fas fa-headset text-info mt-1"></i><span>{{ __(getTitle($key)) }} Settings</span></a>
					</li>
				@endif
			@endforeach
		</ul>


		<div class="mt-4 mb-4 p-3 hide-sidebar-mini">
		</div>

	</aside>
</div>

