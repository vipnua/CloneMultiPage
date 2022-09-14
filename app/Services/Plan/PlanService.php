<?php
/**
 * Author: Pham Dinh Tung
 * Date: 7/7/2022
 * Time: 1:36 PM
 */

namespace App\Services\Plan;

use App\Model\Plan;
use http\Env\Request;

class PlanService
{
    public function formatRequest($request,$uuid = null)
    {
        return [
            'uuid' => $uuid ?? uuid(),
            'type' => 'RECURRING',
            'name' => $request->name,
            'price' => $request->price,
            'price_vn' => $request->price_vn,
            'interval' => $request->interval,
            'profile' => $request->profile,
            'profile_share' => $request->profile_share,
            'status'=>$request->status,
            'default' => 0,
            'interval_type' => $request->interval_type,
            'describe' => str_replace('"',"'",$request->describe) ?? null,
        ];
    }

    public function addPlan($data)
    {
        return Plan::create($data);
    }

    public function updatePlan($plan,$data)
    {
        return $plan->update($data);
    }

    public static function findPlanById($id)
    {
        return Plan::where('id',$id)->first();
    }

    public static function destroyPlan($plan)
    {
        return $plan->delete();
    }

    public function updateAllPlan($dataUpdate)
    {
        return Plan::query()->update($dataUpdate);
    }

    public function getPlanDefault()
    {
        return Plan::where('default',1);
    }

}
