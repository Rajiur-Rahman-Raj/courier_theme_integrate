<?php

$arr = [
	"Admin_Dashboard" => [
		"Dashboard" => [
			'permission' => [
				'view' => ['admin.home'],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
		],
		"User_Statistics" => [
			'permission' => [
				'view' => ['admin.user-statistics'],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
		],
		"Branch_Statistics" => [
			'permission' => [
				'view' => ['admin.branch-statistics'],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
		],
		"Shipment_Statistics" => [
			'permission' => [
				'view' => ['admin.shipment-statistics'],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
		],
		"Shipment_Chart_Statistics" => [
			'permission' => [
				'view' => ['admin.shipment-chart-statistics', 'get.daily.shipment.analytics', 'get.monthly.shipment.analytics'],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
		],
		"Shipment_Transaction" => [
			'permission' => [
				'view' => ['admin.shipment-transaction'],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
		],
		"Shipment_Transaction_Chart" => [
			'permission' => [
				'view' => ['admin.shipment-transaction-chart', 'get.daily.shipment.transactions.analytics'],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
		],
		"Payment_Chart" => [
			'permission' => [
				'view' => ['admin.payment-chart'],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
		],
		"Tickets" => [
			'permission' => [
				'view' => ['admin.tickets'],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
		],
		"Browser_Statistics" => [
			'permission' => [
				'view' => ['admin.browser-statistics'],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
		],
	],
	"Manage_Shipments" => [
		"Shipment_List" => [
			'permission' => [
				'view' => ['shipmentList', 'viewShipment'],
				'add' => ['createShipment', 'shipmentStore'],
				'edit' => ['editShipment', 'shipmentUpdate', 'acceptShipmentRequest', 'cancelShipmentRequest', 'assignToCollectShipmentRequest', 'assignToDeliveredShipmentRequest', 'payConditionShipmentToSender'],
				'delete' => ['deleteShipment', 'trashShipmentList', 'restoreShipment', 'forceDeleteShipment'],
				'dispatch' => ['updateShipmentStatus'],
			],
		],
	],

	"Shipment_Types" => [
		"Shipment_Type_List" => [
			'permission' => [
				'view' => ['shipmentTypeList'],
				'edit' => ['shipmentTypeUpdate'],
				'add' => [],
				'delete' => [],
			],
		],
	],

	"Shipping_Rates" => [
		"Default_Rate" => [
			'permission' => [
				'view' => ['defaultRate'],
				'add' => [],
				'edit' => ['defaultShippingRateOperatorCountryUpdate', 'defaultShippingRateInternationallyUpdate'],
				'delete' => [],
				'show' => [],
			],
		],
		"Operator_Country_Rate" => [
			'permission' => [
				'view' => ['operatorCountryRate'],
				'add' => ['createShippingRateOperatorCountry', 'shippingRateOperatorCountry.store'],
				'show' => ['operatorCountryShowRate'],
				'edit' => ['stateRateUpdate', 'cityRateUpdate', 'areaRateUpdate'],
				'delete' => ['deleteStateRate', 'deleteCityRate', 'deleteAreaRate'],
			],
		],
		"Internationally_Rate" => [
			'permission' => [
				'view' => ['internationallyRate'],
				'add' => ['createShippingRateInternationally', 'shippingRateInternationally.store'],
				'show' => ['internationallyShowRate'],
				'edit' => ['countryRateUpdateInternationally', 'stateRateUpdateInternationally', 'cityRateUpdateInternationally'],
				'delete' => ['deleteICountryRate', 'deleteIStateRate', 'deleteICityRate'],
			],
		],
	],

	"Packaging_Service" => [
		"Service_List" => [
			'permission' => [
				'view' => ['packingServiceList'],
				'add' => ['packageStore', 'variantStore', 'packingServiceStore'],
				'edit' => ['packageUpdate', 'variantUpdate', 'packingServiceUpdate'],
				'delete' => [],
			],
		],
	],

	"Parcel_Service" => [
		"Service_List" => [
			'permission' => [
				'view' => ['parcelServiceList'],
				'add' => ['parcelTypeStore', 'parcelUnitStore', 'parcelServiceStore'],
				'edit' => ['parcelTypeUpdate', 'parcelUnitUpdate', 'parcelServiceUpdate'],
				'delete' => [],
			],
		],
	],

	"Manage_Branch" => [
		"Branch_List" => [
			'permission' => [
				'view' => ['branchList'],
				'add' => ['createBranch', 'branchStore'],
				'edit' => ['branchEdit'],
				'delete' => [],
				'show_profile' => ['showBranchProfile'],
				'show_staff_list' => [],
				'login_as' => [],
			],
		],
		"Branch_Manager" => [
			'permission' => [
				'view' => ['branchManagerList', ],
				'add' => ['createBranchManager', 'branchManagerStore'],
				'edit' => ['branchManagerEdit', 'branchManagerUpdate'],
				'delete' => [],
				'show_profile' => [],
				'show_staff_list' => ['branchStaffList'],
				'login_as' => ['admin.role.managerLogin'],
			],
		],
		"Employee_List" => [
			'permission' => [
				'view' => ['branchEmployeeList'],
				'add' => ['createEmployee', 'branchEmployeeStore'],
				'edit' => ['branchEmployeeEdit', 'branchEmployeeUpdate'],
				'delete' => [],
				'show_profile' => [],
				'show_staff_list' => [],
				'login_as' => ['admin.role.employeeLogin'],
			],
		],
		"Driver_List" => [
			'permission' => [
				'view' => ['branchDriverList'],
				'add' => ['createDriver', 'branchDriverStore'],
				'edit' => ['branchDriverEdit', 'branchDriverUpdate'],
				'delete' => [],
				'show_profile' => [],
				'show_staff_list' => [],
				'login_as' => ['admin.role.driverLogin'],
			],
		],
	],

	'Manage_Department' => [
		"Department_List" => [
			'permission' => [
				'view' => ['departmentList'],
				'add' => ['createDepartment', 'departmentStore'],
				'edit' => ['departmentEdit'],
				'delete' => [],
			],
		],
	],

	'Manage_Customers' => [
		"Customer_List" => [
			'permission' => [
				'view' => ['clientList'],
				'add' => ['createClient', 'clientStore'],
				'edit' => ['clientEdit', 'clientUpdate', 'client.balance.update'],
				'delete' => [],
				'show_profile' => ['client.edit'],
				'login_as' => ['user.clientLogin'],
			],
		],
	],

	'Manage_Locations' => [
		"Country_List" => [
			'permission' => [
				'view' => ['countryList'],
				'add' => ['countryStore'],
				'edit' => ['countryUpdate'],
				'delete' => [],
			],
		],
		"State_List" => [
			'permission' => [
				'view' => ['stateList'],
				'add' => ['stateStore'],
				'edit' => ['stateUpdate'],
				'delete' => [],
			],
		],
		"City_List" => [
			'permission' => [
				'view' => ['cityList'],
				'add' => ['cityStore'],
				'edit' => ['cityUpdate'],
				'delete' => [],
			],
		],
		"Area_List" => [
			'permission' => [
				'view' => ['areaList'],
				'add' => ['areaStore'],
				'edit' => ['areaUpdate'],
				'delete' => [],
			],
		],
	],

	'Manage_Reports' => [
		"Shipment_Report" => [
			'permission' => [
				'view' => ['shipmentReport', 'export.shipmentReport', 'shipmentTransactionReport', 'export.shipmentTransactionReport'],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
		],
		"Shipment_Transaction" => [
			'permission' => [
				'view' => ['shipmentTransactionReport','export.shipmentTransactionReport'],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
		],
	],

	'User_Panel' => [
		"Manage_Users" => [
			'permission' => [
				'view' => ['user-list','inactive.user.list'],
				'add' => [],
				'edit' => ['user.edit', 'user.balance.update'],
				'delete' => [],
				'send_mail' => ['send.mail.user'],
				'login_as' => ['user.asLogin'],
			],
		],
	],

	'Support_Tickets' => [
		"Tickets" => [
			'permission' => [
				'view' => ['admin.ticket','admin.ticket.view'],
				'add' => [],
				'edit' => ['admin.ticket.reply', 'admin.ticket.download'],
				'delete' => ['admin.ticket.delete'],
			],
		],
	],

	'Transactions' => [
		"Add_Fund_List" => [
			'permission' => [
				'view' => ['admin.fund.add.index', 'admin.fund.add.search', 'admin.user.fund.add.show', 'admin.user.fund.add.search'],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
		],
		"Payout_List" => [
			'permission' => [
				'view' => ['admin.payout.index', 'admin.payout.search', 'payout.details'],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
		],
		"Transaction_List" => [
			'permission' => [
				'view' => ['admin.transaction.index'],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
		],
	],

	'Control_Panel' => [
		"Control_Panel" => [
			'permission' => [
				'view' => ['settings', 'notify.template.index', 'email.template.index', 'sms.template.index', 'sms.template.edit', 'language.index', 'storage.index', 'plugin.config'],
				'add' => ['language.create', 'language.store'],
				'edit' => ['basic.control', 'logo.update', 'seo.update', 'pusher.config', 'notify.template.edit', 'email.config', 'email.template.default', 'email.template.edit', 'sms.config', 'sms.template.update', 'language.edit', 'language.update', 'language.keyword.edit', 'language.keyword.update', 'language.import.json', 'language.store.key', 'language.update.key', 'storage.edit', 'storage.setDefault', 'tawk.control', 'fb.messenger.control', 'google.recaptcha.control', 'google.analytics.control'],
				'delete' => ['language.delete', 'language.delete.key'],
			],
		],
	],

	'Payment_Settings' => [
		"Payment_Methods" => [
			'permission' => [
				'view' => ['payment.methods'],
				'add' => [],
				'edit' => ['edit.payment.methods', 'update.payment.methods', 'sort.payment.methods'],
				'delete' => [],
			],
		],
		"Manual_Gateway" => [
			'permission' => [
				'view' => ['admin.deposit.manual.index'],
				'add' => ['admin.deposit.manual.create', 'admin.deposit.manual.store'],
				'edit' => ['admin.deposit.manual.edit', 'admin.deposit.manual.update'],
				'delete' => [],
			],
		],
		"Payment_Request" => [
			'permission' => [
				'view' => ['admin.payment.pending', 'admin.payment.search'],
				'add' => [],
				'edit' => ['admin.payment.action'],
				'delete' => [],
			],
		],
		"Payment_Log" => [
			'permission' => [
				'view' => ['admin.payment.log'],
				'add' => [],
				'edit' => [],
				'delete' => [],
			],
		],
	],

	'Payout_Settings' => [
		"Payout_Methods" => [
			'permission' => [
				'view' => ['payout.method.list'],
				'add' => ['payout.method.add'],
				'edit' => ['payout.method.edit'],
				'delete' => [],
			],
		],
	],

	'Role_And_Permissions' => [
		"Available_Roles" => [
			'permission' => [
				'view' => ['admin.role'],
				'add' => ['createRole', 'roleStore'],
				'edit' => ['editRole', 'roleUpdate'],
				'delete' => ['deleteRole'],
				'login_as' => [],
			],
		],
		"Manage_Staff" => [
			'permission' => [
				'view' => ['admin.role.staff'],
				'add' => ['admin.role.usersCreate'],
				'edit' => ['admin.role.usersEdit', 'admin.role.statusChange'],
				'delete' => [],
				'login_as' => ['admin.role.usersLogin'],
			],
		],
	],

	'Theme_Settings' => [
		"Ui_Settings" => [
			'permission' => [
				'view' => ['template.show'],
				'add' => [],
				'edit' => ['template.update'],
				'delete' => [],
			],
		],
		"Content_Settings" => [
			'permission' => [
				'view' => ['content.index'],
				'add' => ['content.create', 'content.store'],
				'edit' => ['content.show', 'content.update'],
				'delete' => ['content.delete'],
			],
		],
	],

	'Blog_Settings' => [
		"Category_List" => [
			'permission' => [
				'view' => ['blogCategory'],
				'add' => ['blogCategoryCreate', 'blogCategoryStore'],
				'edit' => ['blogCategoryEdit', 'blogCategoryUpdate'],
				'delete' => ['blogCategoryDelete'],
			],
		],
		"Blog_List" => [
			'permission' => [
				'view' => ['blogList'],
				'add' => ['blogCreate', 'blogStore'],
				'edit' => ['blogEdit', 'blogUpdate'],
				'delete' => ['blogDelete'],
			],
		],
	],
];

return $arr;
