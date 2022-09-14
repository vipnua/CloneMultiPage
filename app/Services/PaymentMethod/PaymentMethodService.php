<?php
/**
 * Author: Pham Dinh Tung
 * Date: 7/13/2022
 * Time: 10:30 AM
 */

namespace App\Services\PaymentMethod;

use App\Model\PaymentMethod;

class PaymentMethodService
{
    public function createNewPaymentMethod($data)
    {
        return PaymentMethod::create($data);
    }

    public function getPaymentMethodPaypal()
    {
        return PaymentMethod::where('type',PaymentMethod::METHOD['paypal'])->first();
    }

    public function destroyPaymentMethodByID($id)
    {
        return PaymentMethod::where('id',$id)->delete();
    }

    public function getPaymentMethodByID($id)
    {
        return PaymentMethod::where('id',$id)->first();
    }
    public function updatePaymentMethod($paymentMethod,$data)
    {
        return $paymentMethod->update($data);
    }

    public function updateOrCreatePaymentMethodPaypal($data)
    {
        return true;
//        return PaymentMethod::updateOrCreate(['type'=>PaymentMethod::TYPE['paypal']],$data);
    }

    public function formatData($data)
    {
        switch ($data['type']) {
            case 'paypal':
                $data = [
                    type => $data['type'],
                    bank_name => 'paypal',
                    bank_number => null,
                    account_number => null,
                    client_id => $data['client_id'],
                ];
                break;

            default:
                $data = [
                    type => $data['type'],
                    bank_name => $data['bank_name'],
                    bank_number => $data['bank_number'],
                    account_number => $data['account_number'],
                    client_id => null,
                ];
                break;
        }
        return $data;
    }
}
