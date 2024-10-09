<?php

namespace App\Console\Commands;

use App\Models\Monitor;
use App\Models\UptimeRecord;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class SaveLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:save_logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Luu uptime records => logs vao moi 1h sang';

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
        $uptimeRecords = UptimeRecord::select('checked_at', 'status_code', 'response_time', 'monitor_id')
                            ->whereBetween('checked_at', [$startOfYesterday, $endOfYesterday])
                            ->get();

        $dataToInsert = [];
        $checkStatus = [];

        foreach ($uptimeRecords as $record) {
            $monitorId = $record->monitor_id;
            $timestamp = $record->checked_at;
            $statusCode = $record->status_code;

            if (!isset($checkStatus[$monitorId][$statusCode])) {
                $checkStatus[$monitorId][$statusCode] = true;

                $dataToInsert[$monitorId][$timestamp] = $record->checked_at . '-' . $record->status_code . '-' . $record->response_time;
            }
        }

        foreach ($dataToInsert as $monitorId => $data) {
            $monitor = Monitor::find($monitorId);

            if ($monitorId) {
                $logs = json_decode($monitor->logs, true) ?? [];

                $logs[$startOfYesterday] = implode(',', $data);
                $monitor->update(['logs' => json_encode($logs)]);
            }
        }

        return 0;
    }
}
