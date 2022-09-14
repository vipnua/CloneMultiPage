<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Browser;
use Carbon\Carbon;
use App\Services\Browser\BrowserService;
use App\Traits\FileService;

class checkRecycle extends Command
{
    use FileService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity:recycle';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Expired Records';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public $browserService;
    public function __construct(BrowserService $browserService)
    {
        parent::__construct();
        $this->browserService = $browserService;

    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $outOfDate = Carbon::now()->subMonth(1);
        $browsers = Browser::onlyTrashed()->whereDate('deleted_at', '<', $outOfDate)->get();
        foreach ($browsers as $browser) {
            if ($browser->forceDelete()) {
                $part = $this->browserService->getFilePart($browser);
                if ($part) {
                    $this->destroyFile($part);
                }
            }
        }
        return 0;
    }
}
