<?php

namespace App\Console\Commands;

use App\Models\Shipment;
use App\Models\Transaction;
use Facades\App\Services\NotifyMailService;
use Illuminate\Console\Command;

class RefundMoney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refund:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public static function handle()
    {
		$refundShipments = Shipment::with('sender', 'receiver')->whereNotNull('refund_time')->where('refund_time', '<=', now())->where('is_refund_complete', 0)->get();

		if (sizeof($refundShipments) > 0){
			foreach ($refundShipments as $refundShipment){
				try {
					$refundOwner = null;
					if ($refundShipment->payment_by == 1){
						$refundOwner = $refundShipment->sender;
					}elseif ($refundShipment->payment_by == 2){
						$refundOwner = $refundShipment->receiver;
					}
					$shipment = $refundShipment;
					$shipment->refund_time = null;
					$shipment->is_refund_complete = 1;
					$shipment->save();

					$user = $refundOwner;
					$refundAmount = ($shipment->total_pay - $shipment->return_shipment_cost);
					$user->balance = $user->balance + $refundAmount;
					$user->save();

					$transaction = new Transaction();
					$trans = strRandom();
					$transaction->user_id = $user->id;
					$transaction->amount = round($refundAmount, 2);
					$transaction->charge = 0;
					$transaction->final_balance = $user->balance;
					$transaction->trx_type = '+';
					$transaction->trx_id = $trans;
					$transaction->remarks = 'Your shipment has been refunded. ' . 'Shipment id = '. $shipment->shipment_id;
					$transaction->transactional_type = Shipment::class;
					$shipment->transactional()->save($transaction);

					NotifyMailService::cancelShipmentRequestRefundMoney($shipment, $user, $refundAmount);
				}catch (\Exception $e){
					continue;
				}

			}
		}
    }
}
