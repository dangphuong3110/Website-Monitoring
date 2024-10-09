<?php

namespace App\Console\Commands;

use App\Models\UptimeRecord;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class DeleteLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:delete_logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xoa uptime records da luu vao logs';

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
        $startOfYesterday = Carbon::yesterday()->startOfDay()->timestamp;
        $endOfYesterday = Carbon::yesterday()->endOfDay()->timestamp;

        UptimeRecord::whereBetween('checked_at', [$startOfYesterday, $endOfYesterday])->delete();
        return 0;
    }
}
