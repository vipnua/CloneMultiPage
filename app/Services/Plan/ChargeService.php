<?php

namespace App\Services\Plan;

use App\Model\Charge;
use App\Model\Plan;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ChargeService
{

    public function addChargeForUser($user, $paymentTransaction)
    {
        $plan_id = $user->plan_id;
        $plan = Plan::find($paymentTransaction->plan_id);
        if ($plan->price == 0 && $plan->price_vn == 0) {
            $expires_on = Carbon::now()->addDays($plan->interval);
        } else {

            if ($paymentTransaction->currency == 'VND') {
                # code...
                $price = (float)$plan->price_vn;
            } else {
                $price = (float)$plan->price;
            }
            
            if ($price == 0) {
                $paymentTransaction->update(['system_note' => 'Price plan is not valid']);
                return false;
            }
            $paymentDividedInterval = (float)$paymentTransaction->amount / $price;

            if ($paymentDividedInterval < 1) {
                $paymentTransaction->update(['system_note' => 'Amount is not valid']);
                return false;
            }
            $expires_on = Carbon::now()->addDays($plan->interval * $paymentDividedInterval);
        }


        if ($plan_id) {
            $oldCharge = Charge::where('user_id', $user->id)->latest('id')->first();
            if ($plan_id == $paymentTransaction->plan_id) {
                $date_expired = $oldCharge ? Carbon::create($oldCharge->expires_on) : null;
                if (Carbon::now()->gt($date_expired)) {
                    $expires_on = $plan->interval ? Carbon::now()->addDays($plan->interval) : null;
                } else {
                    $expires_on = $plan->interval ? Carbon::now()->addDays((int)$plan->interval + Carbon::now()->diffInDays($date_expired)) : null;
                }
            }
        }
        $user->update(['plan_id' => $paymentTransaction->plan_id]);
        $charge = Charge::create([
            'uuid' => uuid(),
            'type' => $plan->type,
            'name' => $plan->name,
            'price' => $plan->price,
            'interval' => $plan->interval,
            'profile' => $plan->profile,
            'activated_on' => Carbon::now(),
            'expires_on' => $expires_on,
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'profile_share' => $plan->profile_share,
        ]);
        return $charge;
    }

    public function addChargeForUserByHong($user, $plan_id_add)
    {
        $plan_id = $user->plan_id;
        $plan = Plan::find($plan_id_add);

        $expires_on = Carbon::now()->addDays($plan->interval);
        if ($plan_id) {
            $oldCharge = Charge::where('user_id', $user->id)->latest('id')->first();
            if ($plan_id == $plan_id_add) {
                $date_expired = Carbon::create($oldCharge->expires_on);
                if (Carbon::now()->gt($date_expired)) {
                    $expires_on = $plan->interval ? Carbon::now()->addDays($plan->interval) : null;
                } else {
                    $expires_on = $plan->interval ? Carbon::now()->addDays((int)$plan->interval + Carbon::now()->diffInDays($date_expired)) : null;
                }
            }
        }
        $user->update(['plan_id' => $plan_id_add]);
        $charge = Charge::create([
            'uuid' => uuid(),
            'type' => $plan->type,
            'name' => $plan->name,
            'price' => $plan->price,
            'interval' => $plan->interval,
            'profile' => $plan->profile,
            'activated_on' => Carbon::now(),
            'expires_on' => $expires_on,
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'profile_share' => $plan->profile_share,
        ]);
        return $charge;
    }

    public static function getListCharge($userId)
    {
        $charge = Charge::where('user_id', $userId)->latest('id');
        return $charge;
    }
}
