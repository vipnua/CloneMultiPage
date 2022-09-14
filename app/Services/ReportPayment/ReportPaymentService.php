<?php
/**
 * Author: Pham Dinh Tung
 * Date: 7/25/2022
 * Time: 8:54 AM
 */

namespace App\Services\ReportPayment;

use App\Model\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportPaymentService
{
    public function formatDate($date){
        return Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
    }

    public function getDataDashboard($time)
    {
        $report = PaymentMethod::with(['payment_transaction' => function($q) use ($time) {
            $q->whereBetween('created_at', $time);
        }])->get();
        return $report;
    }
}
