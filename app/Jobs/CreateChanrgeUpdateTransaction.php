<?php

namespace App\Jobs;

use App\Model\PaymentTransaction;
use App\Services\PaymentTransaction\PaymentTransactionService;
use App\Services\Plan\ChargeService;
use App\Services\User\UserService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateChanrgeUpdateTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $paymentTransaction;
    public $chargeService;
    public $userService;
    public $paymentTransactionService;

    public function __construct(PaymentTransaction $paymentTransaction)
    {
        $this->paymentTransaction = $paymentTransaction;
        $this->chargeService = new ChargeService();
        $this->userService = new UserService();
        $this->paymentTransactionService = new PaymentTransactionService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //add charge
        $user = $this->userService->getUserByID($this->paymentTransaction->user_id);
        $charge = $this->chargeService->addChargeForUser($user, $this->paymentTransaction);

        if ($charge) {
            # code...
            //update payment_transaction
            $data = [
                'active_plan_date' => Carbon::now(),
                'charge_id' => $charge->id,
            ];
            $this->paymentTransactionService->updatePaymentTransaction($this->paymentTransaction, $data);
        }

        // hỏi lại anh Hiếu xem nếu có lỗi thì thông báo trên trang nào.
    }
}
