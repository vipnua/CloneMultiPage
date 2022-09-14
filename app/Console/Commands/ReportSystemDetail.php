<?php

namespace App\Console\Commands;

use App\Services\ReportDaily\ReportDailyService;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReportSystemDetail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity:sync_data {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data to report detail table';

    public $reportDailyService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ReportDailyService $reportDailyService)
    {
        $this->reportDailyService = $reportDailyService;
        parent::__construct();
    }

    /**
     *
     */
    public function handle()
    {
        $date = $this->argument('date');
        $time = null;
        switch ($date) {
            case 'yesterday':
                $time = Carbon::yesterday();
                break;
            case 'today':
                $time = Carbon::now();
                break;

            default:
                return $this->info('Invalid date.');
                break;
        }
        $this->reportDailyService->deleteByDate($time);
        $users = User::all();
        $dataInsret = [];
        foreach ($users as $user) {
            $dataFormat = $this->reportDailyService->createDataBeforInsert($user, $time);
            array_push($dataInsret, $dataFormat);
        }

        $dataInsrets = array_chunk($dataInsret, 500, true);
        foreach ($dataInsrets as $data) {
            $this->reportDailyService->insertMultipleRecords($data);
        }

        return $this->info($this->description . ': ' . $time);
    }
}
