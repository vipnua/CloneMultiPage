<?php
/**
 * Author: Pham Dinh Tung
 * Date: 7/15/2022
 * Time: 4:18 PM
 */

namespace App\Services\PaymentTransaction;

use App\Model\PaymentTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PaymentTransactionService
{
    public function createNewPaymentTransaction($data)
    {
        return PaymentTransaction::create($data);
    }

    public function updatePaymentTransaction($payment,$data)
    {
        return $payment->update($data);
    }

    public function formatDataCreateFromManager($request)
    {
        $data = [
            'user_id' => $request['user'],
            'plan_id' => $request['plan'],
            'payment_method_id' => $request['payment_method'],
            'amount' => $request['amount'],
            'currency' => $request['currency'],
            'transaction_id' => $request['transaction'],
            'currency' => $request['currency'],
            'status' => 1,
            'payment_date' => Carbon::now(),
            'employee_id' => Auth::user()->id,
            'active_plan_date' => null,
            'charge_id' => null,
            'note' => $request['note'],
        ];
        return $data;
    }

}
