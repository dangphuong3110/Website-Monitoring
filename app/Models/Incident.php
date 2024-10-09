<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Incident extends Model
{
    use HasFactory;

    public function monitor() {
        return $this->belongsTo(Monitor::class, 'monitor_id');
    }

    public static function getIncidents($tabId, $timeFrame) {
//        return DB::table('incidents')
//            ->select('incidents.id', 'incidents.status', 'incidents.name', 'incidents.started_at', 'incidents.resolved_at', 'incidents.count', 'incidents.monitor_id', 'latest_checked_at.max_checked_at AS checked_at', 'uptime_records.response_time', 'monitors.url', 'monitors.type', 'monitor_tab.tab_id')
//            ->join('monitors', 'incidents.monitor_id', '=', 'monitors.id')
//            ->join('monitor_tab', 'monitors.id', '=', 'monitor_tab.monitor_id')
//            ->leftJoin(DB::raw('(SELECT monitor_id, MAX(checked_at) AS max_checked_at FROM uptime_records GROUP BY monitor_id) AS latest_checked_at'), function ($join) {
//                $join->on('latest_checked_at.monitor_id', '=', 'monitors.id');
//            })
//            ->leftJoin('uptime_records', function ($join) {
//                $join->on('incidents.monitor_id', '=', 'uptime_records.monitor_id')
//                    ->on('latest_checked_at.max_checked_at', '=', 'uptime_records.checked_at');
//            })
//            ->where('monitor_tab.tab_id', $tabId)
//            ->whereNull('incidents.resolved_at')
//            ->where('incidents.started_at', '>=', $timeFrame)
//            ->where('monitors.status', '=', 'active')
//            ->orderByDesc('checked_at')
//            ->get();
        return DB::table('incidents')
            ->select('incidents.id', 'incidents.status', 'incidents.name', 'incidents.started_at', 'incidents.resolved_at', 'incidents.count', 'incidents.latest_status_code','incidents.monitor_id', 'incidents.latest_checked_at', 'incidents.response_time', 'monitors.url', 'monitors.type', 'monitor_tab.tab_id')
            ->join('monitors', 'incidents.monitor_id', '=', 'monitors.id')
            ->join('monitor_tab', 'monitors.id', '=', 'monitor_tab.monitor_id')
            ->where('monitor_tab.tab_id', $tabId)
            ->whereNull('incidents.resolved_at')
            ->where('incidents.started_at', '>=', $timeFrame)
            ->where('monitors.status', '=', 'active')
            ->orderByDesc('latest_checked_at')
            ->get();
    }

    protected $fillable = ['created_at', 'updated_at'];
}
