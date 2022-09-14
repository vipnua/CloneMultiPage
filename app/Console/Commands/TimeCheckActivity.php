<?php

namespace App\Console\Commands;

use App\Browser;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TimeCheckActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity:timeCheck';

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
    public function handle()
    {
        // lấy ra những thằng browser đang chạy
        // kiểm tra xem nó đã quá hạn chưa. nếu quá hạn thì chuyển trạng thái
        $browers = Browser::where('can_be_running', 0)->where('updated_at', '<', Carbon::now()->subMinutes(3))->update(['can_be_running' => 1]);
        return ;
    }
}
