<?php


namespace App\Services\ReportDaily;


use App\Model\ReportDaily;
use Carbon\Carbon;

class ReportDailyService
{

    public function insertMultipleRecords($data)
    {
        return ReportDaily::insert($data);
    }

    /**
     * @param $date
     * @return mixed
     */
    public function deleteByDate($date)
    {
        $date = $date->toDateString();
        return ReportDaily::where('date', $date)->delete();
    }


    /**
     * @param $user
     * @param $time
     * @return array
     */
    public function createDataBeforInsert($user, $time)
    {
        $time = $time->toDateString();
        if ($user->date_late_action) {
            $date_late_action = new Carbon($user->date_late_action);
            $date_late_action = $date_late_action->toDateString();
        } else {
            $date_late_action = null;
        }
        $total_new_browser = $user->browser()->withTrashed()->whereDate('created_at', $time)->count();
        $total_active_browser = $user->browser()->withTrashed()->whereDate('updated_at', $time)->count();
        $data = [
            'unique_key' => $time . '_' . $user->id,
            'user_id' => $user->id,
            'date' => $time,
            'new_user' => $time == $user->created_at->toDateString() ? 1 : 0,
            'action_user' => $time == $date_late_action ? 1 : 0,
            'total_new_browser' => $total_new_browser,
            'total_active_browser' => $total_active_browser,
            'created_at' => Carbon::now(),
        ];
        return $data;
    }

    public function getDataDashboard($report)
    {
        $query ='sum(new_user) as newUser,sum(total_new_browser) as newBrowser,sum(action_user) as actionUser,sum(total_active_browser) as actionBrowser';
        $data =$report->selectRaw($query)->get(['newUser','newBrowser','actionUser','actionBrowser'])->first()->toArray();

        $key =['New User','New Browser','User Action','Browser Action'];
        $result = array_combine($key,$data);

        foreach ($result as $key => $value) {
            if (!$value) {
                $result[$key] = 0;
            }
        }
        return $result;

//        return [
//            'newUser'=>count($result->where('new_user',1)),
//            'newBrowser'=>array_sum($result->where('total_new_browser','>',0)->pluck('total_new_browser')->toArray()),
//            'actionUser'=>array_sum($result->where('action_user','>',0)->pluck('action_user')->toArray()),
//            'actionBrowser'=>array_sum($result->where('total_active_browser','>',0)->pluck('total_active_browser')->toArray()),
//        ];
    }

    public function formatDate($date){
        return Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
    }
}
