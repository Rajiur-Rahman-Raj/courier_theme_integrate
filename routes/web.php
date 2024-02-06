<?php

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PackingController;
use App\Http\Controllers\Admin\ParcelController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\SiteNotificationController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserShipmentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManualRecaptchaController;
use App\Http\Controllers\SocialiteController;

Route::get('queue-work', function () {
	return Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);
})->name('queue.work');

Route::get('clear', function () {
	return Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('schedule-run', function () {
	return Illuminate\Support\Facades\Artisan::call('schedule:run');
})->name('schedule:run');

Route::get('removeStatus', function () {
	session()->forget('status');
})->name('removeStatus');

Route::get('/captcha', [ManualRecaptchaController::class, 'reCaptCha'])->name('captcha');

Route::match(['get', 'post'], 'success', [PaymentController::class, 'success'])->name('success');

Route::match(['get', 'post'], 'failed', [PaymentController::class, 'failed'])->name('failed');

Route::match(['get', 'post'], 'payment/{code}/{trx?}/{type?}', [PaymentController::class, 'gatewayIpn'])->name('ipn');

Auth::routes(['verify' => true]);


Route::group(['prefix' => 'user', 'middleware' => ['auth', 'verifyUser']], function () {

	Route::post('/save-token', [HomeController::class, 'saveToken'])->name('user.save.token');
	Route::get('/dashboard', [HomeController::class, 'index'])->name('user.dashboard');
	Route::get('get-transaction-chart', [HomeController::class, 'getTransactionChart'])->name('user.get.transaction.chart');

	/* Transaction List */
	Route::get('transaction-list', [TransactionController::class, 'index'])->name('user.transaction');
	Route::get('transaction-search', [TransactionController::class, 'search'])->name('user.transaction.search');

	Route::get('push-notification-show', [SiteNotificationController::class, 'show'])->name('push.notification.show');
	Route::get('push.notification.readAll', [SiteNotificationController::class, 'readAll'])->name('push.notification.readAll');
	Route::get('push-notification-readAt/{id}', [SiteNotificationController::class, 'readAt'])->name('push.notification.readAt');

	/* PROFILE SHOW UPDATE BY USER */
	Route::get('profile', [UserProfileController::class, 'index'])->name('user.profile');
	Route::post('/updateProfile', [UserProfileController::class, 'updateProfile'])->name('user.updateProfile');
	Route::put('/updateInformation', [UserProfileController::class, 'updateInformation'])->name('user.updateInformation');
	Route::match(['get', 'post'], 'change-password', [UserProfileController::class, 'changePassword'])->name('user.change.password');
	Route::post('/updatePassword', [UserProfileController::class, 'updatePassword'])->name('user.updatePassword');

	Route::post('/verificationSubmit', [UserProfileController::class, 'verificationSubmit'])->name('user.verificationSubmit');
	Route::post('/addressVerification', [UserProfileController::class, 'addressVerification'])->name('user.addressVerification');

	/* PAYMENT REQUEST BY USER */
	Route::get('payout-list', [PayoutController::class, 'index'])->name('payout.index');
	Route::get('payout-search', [PayoutController::class, 'search'])->name('payout.search');
	Route::match(['get', 'post'], 'request-payout', [PayoutController::class, 'payoutRequest'])->name('payout.request');
	Route::post('confirm-payout/flutterwave/{utr}', [PayoutController::class, 'flutterwavePayout'])->name('payout.flutterwave');
	Route::post('confirm-payout/paystack/{utr}', [PayoutController::class, 'paystackPayout'])->name('payout.paystack');
	Route::match(['get', 'post'], 'confirm-payout/{utr}', [PayoutController::class, 'confirmPayout'])->name('payout.confirm');
	Route::get('payout-check-limit', [PayoutController::class, 'checkLimit'])->name('payout.checkLimit');
	Route::post('payout-bank-form', [PayoutController::class, 'getBankForm'])->name('payout.getBankForm');
	Route::post('payout-bank-list', [PayoutController::class, 'getBankList'])->name('payout.getBankList');

	/* ADD MONEY BY USER */
	Route::match(['get', 'post'], 'add-fund', [FundController::class, 'initialize'])->name('fund.initialize');
	Route::get('fund-list', [FundController::class, 'index'])->name('fund.index');
	Route::get('fund-requested', [FundController::class, 'requested'])->name('fund.request');
	Route::get('fund-search', [FundController::class, 'search'])->name('fund.search');

	/* USER SUPPORT TICKET */
	Route::get('tickets', [SupportController::class, 'index'])->name('user.ticket.list');
	Route::get('ticket-create', [SupportController::class, 'create'])->name('user.ticket.create');
	Route::post('ticket-create', [SupportController::class, 'store'])->name('user.ticket.store');
	Route::get('ticket-view/{ticket}', [SupportController::class, 'view'])->name('user.ticket.view');
	Route::put('ticket-reply/{ticket}', [SupportController::class, 'reply'])->name('user.ticket.reply');
	Route::get('ticket-download/{ticket}', [SupportController::class, 'download'])->name('user.ticket.download');

	// money-transfer
	Route::get('/money-transfer', [HomeController::class, 'moneyTransfer'])->name('user.money-transfer');
	Route::post('/send-money', [HomeController::class, 'moneyTransferConfirm'])->name('user.send.money');

	// Manage Shipments By User
	Route::get('shipment-list/{shipment_status}/{shipment_type}', [UserShipmentController::class, 'shipmentList'])->name('user.shipmentList');
	Route::get('view-shipment/{id}', [UserShipmentController::class, 'viewShipment'])->name('user.viewShipment');
	Route::get('{shipment_type}/create-shipment', [UserShipmentController::class, 'createShipment'])->name('user.createShipment');
	Route::post('shipment-store/{type?}', [UserShipmentController::class, 'shipmentStore'])->name('user.shipmentStore');

	Route::delete('delete-shipment/{id}', [UserShipmentController::class, 'deleteShipmentRequest'])->name('user.deleteShipmentRequest');
	Route::put('cancel-shipment-request/{id}', [UserShipmentController::class, 'cancelShipmentRequest'])->name('user.cancelShipmentRequest');

	//Manage Receiver By User
	Route::get('receiverList', [HomeController::class, 'receiverList'])->name('user.receiverList');
	Route::get('receiver/create', [HomeController::class, 'receiverCreate'])->name('user.receiver.create');
	Route::post('receiver/store', [HomeController::class, 'receiverStore'])->name('user.receiver.store');
});

Route::group(['prefix' => 'user'], function () {
	Auth::routes();
	// Payment confirm page
	Route::get('deposit-check-amount', [DepositController::class, 'checkAmount'])->name('deposit.checkAmount');
	Route::get('payment-process/{utr}', [PaymentController::class, 'depositConfirm'])->name('payment.process');
	Route::match(['get', 'post'], 'confirm-deposit/{utr}', [DepositController::class, 'confirmDeposit'])->name('deposit.confirm');
	Route::post('addFundConfirm/{utr}', [PaymentController::class, 'fromSubmit'])->name('addFund.fromSubmit');

	Route::get('check', [VerificationController::class, 'check'])->name('user.check');
	Route::get('resend_code', [VerificationController::class, 'resendCode'])->name('user.resendCode');
	Route::post('mail-verify', [VerificationController::class, 'mailVerify'])->name('user.mailVerify');
	Route::post('sms-verify', [VerificationController::class, 'smsVerify'])->name('user.smsVerify');
	Route::post('twoFA-Verify', [VerificationController::class, 'twoFAverify'])->name('user.twoFA-Verify');

	Route::get('auth/{socialite}', [SocialiteController::class, 'socialiteLogin'])->name('socialiteLogin');
	Route::get('auth/callback/{socialite}', [SocialiteController::class, 'socialiteCallback'])->name('socialiteCallback');
});


// Frontend Route
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/service', [FrontendController::class, 'service'])->name('service');

Route::get('/tracking', [FrontendController::class, 'tracking'])->name('tracking');


Route::get('/shipping-calculator/operator-country', [FrontendController::class, 'shippingCalculatorOperatorCountry'])->name('shippingCalculator.operatorCountry');
Route::get('/shipping-calculator/internationally', [FrontendController::class, 'shippingCalculatorInternationally'])->name('shippingCalculator.internationally');
Route::get('/booking/sender-details', [FrontendController::class, 'senderDetails'])->name('senderDetails');
Route::get('/booking/shipping-details', [FrontendController::class, 'shippingDetails'])->name('shippingDetails');


Route::get('/faq', [FrontendController::class, 'faq'])->name('faq');

Route::get('coverage-area/{type?}', [FrontendController::class, 'coverageArea'])->name('coverageArea');
Route::get('package-cost', [FrontendController::class, 'packagingCost'])->name('packagingCost');

Route::get('/blog', [FrontendController::class, 'blog'])->name('blog');

Route::get('/blog-details/{slug}/{id}', [FrontendController::class, 'blogDetails'])->name('blogDetails');
Route::get('/category/blog/{slug}/{id}', [FrontendController::class, 'CategoryWiseBlog'])->name('CategoryWiseBlog');
Route::get('/blog/search', [FrontendController::class, 'blogSearch'])->name('blogSearch');
Route::get('/blog-details/{slug}/{id}', [FrontendController::class, 'blogDetails'])->name('blogDetails');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact/send', [FrontendController::class, 'contactSend'])->name('contact.send');
Route::post('subscribe', [FrontendController::class, 'subscribe'])->name('subscribe');
Route::get('{content_id}/{getLink}', [FrontendController::class, 'getLink'])->name('getLink');
Route::get('/{template}', [FrontendController::class, 'getTemplate'])->name('getTemplate');
Route::get('/language/switch/{code}', [FrontendController::class, 'setLanguage'])->name('language');


// All Ajax Route Here
Route::post('get-role-user', [BranchController::class, 'getRoleUser'])->name('getRoleUser');
Route::post('get-role-user-info', [BranchController::class, 'getRoleUserInfo'])->name('getRoleUserInfo');
Route::post('oc-get-selected-location-ship-rate', [ShipmentController::class, 'OCGetSelectedLocationShipRate'])->name('OCGetSelectedLocationShipRate');

Route::post('get-package-variant', [PackingController::class, 'getSelectedPackageVariant'])->name('getSelectedPackageVariant');
Route::post('get-variant-service', [PackingController::class, 'getSelectedVariantService'])->name('getSelectedVariantService');

Route::post('get-parcel-type-unit', [ParcelController::class, 'getSelectedParcelTypeUnit'])->name('getSelectedParcelTypeUnit');
Route::post('get-parcel-unit-service', [PackingController::class, 'getSelectedParcelUnitService'])->name('getSelectedParcelUnitService');

Route::post('get-country-state', [LocationController::class, 'getSeletedCountryState'])->name('getSeletedCountryState');
Route::post('get-state-city', [LocationController::class, 'getSeletedStateCity'])->name('getSeletedStateCity');
Route::post('get-city-area', [LocationController::class, 'getSeletedCityArea'])->name('getSeletedCityArea');

Route::post('selected-branch-sender', [ShipmentController::class, 'getSelectedBranchSender'])->name('getSelectedBranchSender');
Route::post('selected-branch-receiver', [ShipmentController::class, 'getSelectedBranchReceiver'])->name('getSelectedBranchReceiver');
