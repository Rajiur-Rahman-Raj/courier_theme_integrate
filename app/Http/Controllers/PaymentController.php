<?php

namespace App\Http\Controllers;

use App\Events\AdminNotification;
use App\Events\UserNotification;
use App\Mail\MasterTemplate;
use App\Models\Admin;
use App\Models\Deposit;
use App\Models\EmailTemplate;
use App\Models\Gateway;
use App\Models\SiteNotification;
use App\Models\User;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Facades\App\Services\BasicService;

class PaymentController extends Controller
{
	use Upload, Notify;

	public function depositConfirm($utr)
	{
		try {
			$deposit = Deposit::with('receiver', 'depositable')->where(['utr' => $utr, 'status' => 0])->first();

			if (!$deposit) throw new \Exception('Invalid Payment Request.');

			$gateway = Gateway::findOrFail($deposit->payment_method_id);

			if (!$gateway) throw new \Exception('Invalid Payment Gateway.');

			if (999 < $gateway->id) {
				return view(template() . 'user.payment.manual', compact('deposit'));
			}

			$getwayObj = 'App\\Services\\Gateway\\' . $gateway->code . '\\Payment';
			$data = $getwayObj::prepareData($deposit, $gateway);
			$data = json_decode($data);

		} catch (\Exception $exception) {
			return back()->with('alert', $exception->getMessage());
		}

		if (isset($data->error)) {
			return back()->with('alert', $data->message);
		}

		if (isset($data->redirect)) {
			return redirect($data->redirect_url);
		}

		$page_title = 'Payment Confirm';
		$basic = basicControl();
		return view(template() . $data->view, compact('data', 'page_title', 'deposit', 'basic'));
	}

	public function fromSubmit(Request $request, $utr)
	{
		$basic = (object)config('basic');

		$data = Deposit::where('utr', $utr)->orderBy('id', 'DESC')->with(['gateway', 'receiver'])->first();
		if (is_null($data)) {
			return redirect()->route('fund.initialize')->with('error', 'Invalid Fund Request');
		}
		if ($data->status != 0) {
			return redirect()->route('fund.initialize')->with('error', 'Invalid Fund Request');
		}
		$gateway = $data->gateway;
		$params = optional($data->gateway)->parameters;


		$rules = [];
		$inputField = [];

		$verifyImages = [];

		if ($params != null) {
			foreach ($params as $key => $cus) {
				$rules[$key] = [$cus->validation];
				if ($cus->type == 'file') {
					array_push($rules[$key], 'image');
					array_push($rules[$key], 'mimes:jpeg,jpg,png');
					array_push($rules[$key], 'max:2048');
					array_push($verifyImages, $key);
				}
				if ($cus->type == 'text') {
					array_push($rules[$key], 'max:191');
				}
				if ($cus->type == 'textarea') {
					array_push($rules[$key], 'max:300');
				}
				$inputField[] = $key;
			}
		}

		$this->validate($request, $rules);


		$path = config('location.deposit.path') . date('Y') . '/' . date('m') . '/' . date('d');
		$collection = collect($request);

		$reqField = [];
		if ($params != null) {
			foreach ($collection as $k => $v) {
				foreach ($params as $inKey => $inVal) {
					if ($k != $inKey) {
						continue;
					} else {
						if ($inVal->type == 'file') {
							if ($request->hasFile($inKey)) {
								try {
									$reqField[$inKey] = [
										'field_name' => $this->fileUpload($request[$inKey], $path),
										'type' => $inVal->type,
									];
								} catch (\Exception $exp) {
									session()->flash('error', 'Could not upload your ' . $inKey);
									return back()->withInput();
								}
							}
						} else {
							$reqField[$inKey] = $v;
							$reqField[$inKey] = [
								'field_name' => $v,
								'type' => $inVal->type,
							];
						}
					}
				}
			}
			$data->detail = $reqField;
		} else {
			$data->detail = null;
		}

		$data->created_at = Carbon::now();
		$data->status = 2; // pending
		$data->update();


		$msg = [
			'username' => $data->receiver->username,
			'amount' => getAmount($data->amount),
			'currency' => config('basic.base_currency'),
			'gateway' => $gateway->name
		];
		$action = [
			"link" => route('admin.user.fund.add.show', $data->user_id),
			"icon" => "fa fa-money-bill-alt text-white"
		];
		$this->adminPushNotification('PAYMENT_REQUEST', $msg, $action);

		session()->flash('success', 'You request has been taken.');
		return redirect()->route('fund.index');
	}

	public function gatewayIpn(Request $request, $code, $trx = null, $type = null)
	{

		if (isset($request->m_orderid)) {
			$trx = $request->m_orderid;
		}
		if ($code == 'coinbasecommerce') {
			$gateway = Gateway::where('code', $code)->first();
			$postdata = file_get_contents("php://input");
			$res = json_decode($postdata);
			if (isset($res->event)) {
				$deposit = Deposit::with('receiver')->where('utr', $res->event->data->metadata->trx)->orderBy('id', 'DESC')->first();
				$sentSign = $request->header('X-Cc-Webhook-Signature');
				$sig = hash_hmac('sha256', $postdata, $gateway->parameters->secret);

				if ($sentSign == $sig) {
					if ($res->event->type == 'charge:confirmed' && $deposit->status == 0) {
						BasicService::prepareOrderUpgradation($deposit);
					}
				}
			}
			session()->flash('success', 'You request has been processing.');
			return redirect()->route('success');
		}

		try {
			$gateway = Gateway::where('code', $code)->first();

			if (!$gateway) throw new \Exception('Invalid Payment Gateway.');

			if (isset($trx)) {
				$deposit = Deposit::with('receiver')->where('utr', $trx)->first();
				if (!$deposit) throw new \Exception('Invalid Payment Request.');
			}
			$getwayObj = 'App\\Services\\Gateway\\' . $code . '\\Payment';
			$data = $getwayObj::ipn($request, $gateway, @$deposit, @$trx, @$type);

		} catch (\Exception $exception) {
			return back()->with('alert', $exception->getMessage());
		}
		if (isset($data['redirect'])) {

			if (basicControl()->email_notification) {
				$emailTemplate = EmailTemplate::find(2);
				$emailName = $emailTemplate->name;
				$notify_email = $emailTemplate->notify_email;
				$to = $deposit->email;
				$subject = 'Deposit Money';

				$message = str_replace('[[sender_name]]', optional($deposit->receiver)->name, $emailTemplate->template);
				$message = str_replace('[[receiver_name]]', optional($deposit->receiver)->name, $message);
				$message = str_replace('[[amount]]', $deposit->amount, $message);
				$message = str_replace('[[utr]]', $deposit->utr, $message);

				Mail::to($to)->queue(new MasterTemplate($subject, $message, $notify_email, $emailName));
			}

			$currencyCode = $deposit->payment_method_currency ?? 'N/A';

			$siteNotificationData = [
				"text" => "Payment received $deposit->amount $currencyCode",
				"link" => route('fund.index'),
				"icon" => "fas fa-donate text-white",
			];
			if (class_exists($deposit->depositable_type) && isset($deposit->depositable_id)) {
				$findDepoObj = $deposit->depositable_type::find($deposit->depositable_id);
				$user = User::find($findDepoObj->sender_id);
			}
			if (basicControl()->push_notification) {
				if (isset($user)) {
					$siteNotification = new SiteNotification();
					$siteNotification->description = $siteNotificationData;
					$user->siteNotificational()->save($siteNotification);
					event(new UserNotification($siteNotificationData, $deposit->user_id));
				}

				$admins = Admin::all();
				foreach ($admins as $admin) {
					$siteNotification = new SiteNotification();
					$siteNotification->description = $siteNotificationData;
					$admin->siteNotificational()->save($siteNotification);
					event(new AdminNotification($siteNotificationData, $admin->id));
				}
			}

			return redirect($data['redirect'])->with($data['status'], $data['msg']);
		}
	}

	public function apiResponseSend($order)
	{
		$url = $order->ipn_url;
		$postParam = [
			'status' => 'success',
			'data' => [
				'id' => $order->utr,
				'currency' => optional($order->currency)->code,
				'amount' => $order->amount,
				'order_id' => $order->order_id,
				'meta' => [
					'customer_name' => optional($order->meta)->customer_name ?? null,
					'customer_email' => optional($order->meta)->customer_email ?? null,
					'description' => optional($order->meta)->description ?? null,
				],
			],
		];
		$methodObj = 'App\\Services\\BasicCurl';
		$response = $methodObj::curlPostRequest($url, $postParam);
		return 0;
	}

	public function apiFailResponseSend($order, $msg)
	{
		$order->status = 2;
		$order->save();

		$url = $order->ipn_url;
		$postParam = [
			'status' => 'error',
			'data' => [
				'message' => $msg
			],
		];
		$methodObj = 'App\\Services\\BasicCurl';
		$response = $methodObj::curlPostRequest($url, $postParam);
		return 0;
	}

	public function success()
	{
		return redirect()->route('fund.index')->with('success', 'Fund added successfully!');
	}

	public function failed()
	{
		return view('failed');
	}
}
