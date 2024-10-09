<?php

namespace App\Http\Controllers\Api\WebsiteMonitoring;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\Monitor;
use App\Models\MonitorTab;
use App\Models\Tab;
use App\Models\UserTab;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WebsiteMonitorController extends Controller
{
    /**~
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('wm.logout');
        }
        $user = Auth::user();
        $tabs = $user->tabs;
        $featuredMonitorsByTab = collect();
        $statusMessages = [];

        foreach ($tabs as $tab) {
            $monitorsFeatured = Monitor::withCount('uptimeRecords')
                ->join('monitor_tab', 'monitors.id', 'monitor_tab.monitor_id')
                ->select('monitors.*', 'monitor_tab.tab_id')
                ->where('tab_id', $tab->id)
                ->where('monitor_tab.featured', '1')
                ->where('status', 'active')
                ->orderByDesc('monitor_tab.set_featured_at')
                ->take(12)
                ->get();

            foreach ($monitorsFeatured as $monitorFeatured) {
                $recordLimit = 15;
                $monitorFeatured->load(['uptimeRecords' => function ($query) use ($recordLimit) {
                    $query->orderBy('checked_at', 'desc')->take($recordLimit);
                }]);

                foreach ($monitorFeatured->uptimeRecords as $uptimeRecord) {
                    $moreInfoJson = unserialize($uptimeRecord->more_info);
                    $moreInfo = json_decode($moreInfoJson);

                    if (strpos($moreInfo->status_message, 'failed to verify certificate') !== false) {
                        $statusMessages[$monitorFeatured->url][] = "Failed to verify certificate";
                    } else if (strpos($moreInfo->status_message, 'context deadline exceeded') !== false) {
                        $statusMessages[$monitorFeatured->url][] = "Connection timeout";
                    } else if (strpos($moreInfo->status_message, 'no such host') !== false || strpos($moreInfo->status_message, 'server misbehaving') !== false) {
                        $statusMessages[$monitorFeatured->url][] = "Forbidden";
                    } else {
                        $statusMessages[$monitorFeatured->url][] = $moreInfo->status_message;
                    }
                }
            }

            $featuredMonitorsByTab = $featuredMonitorsByTab->merge($monitorsFeatured);
        }

        return response()->json([
            'tabs' => $tabs,
            'monitors' => $featuredMonitorsByTab,
            'statusMessages' => $statusMessages,
        ]);
    }

    public function getTableMonitor(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('wm.logout');
        }

        $user = Auth::user();
        $tabs = $user->tabs()->pluck('tabs.id');

        $monitors = Monitor::join('monitor_tab', 'monitors.id', 'monitor_tab.monitor_id')
            ->select('id', 'url', 'type', 'monitor_tab.tab_id', 'monitor_tab.featured')
            ->whereIn('tab_id', $tabs)
            ->where('status', 'active')
            ->orderBy('monitor_tab.featured', 'desc')
            ->orderBy('monitor_tab.set_featured_at', 'desc')
            ->get();

        return response()->json([
            'monitors' => $monitors,
            'tabs' => $tabs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('wm.logout');
        }

        $validator = Validator::make($request->all(), [
            'url' => 'required|regex:/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]$/',
            'dest-ip' => 'ip|nullable',
        ]);

        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }

        try {
            $url = $request->input('url');
            $type = $request->input('type') === '0' ? 'https' : 'http';
            $dest_ip = $request->input('dest-ip') ?: null;
            $tab_id = $request->input('tab_id');

            $attributes = ['url' => $url];
            $values = compact('type', 'dest_ip');
            $monitor = Monitor::updateOrCreate($attributes, $values);
            $monitorId = $monitor->id;

            $existingRecordMonitorTab = MonitorTab::where('monitor_id', $monitorId)
                ->where('tab_id', $tab_id)
                ->first();

            $featured = $request->input('featured') === 'on';
            $set_featured_at = $request->input('featured') === 'on' ? now('Asia/Ho_Chi_Minh')->timestamp : null;
            if (!$existingRecordMonitorTab) {
                MonitorTab::create(['monitor_id' => $monitorId, 'tab_id' => $tab_id, 'featured' => $featured, 'set_featured_at' => $set_featured_at]);
                return 'Monitor created! You can keep creating new monitors.';
            } else {
                MonitorTab::where('monitor_id', $monitorId)
                    ->where('tab_id', $tab_id)
                    ->update(['featured' => $featured, 'set_featured_at' => $set_featured_at]);
                return 'Monitor already exists in this tab and has been updated! You can keep creating new monitors.';
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function storeMultipleMonitor(Request $request) // API
    {
        $monitors = $request->input('monitors', []);

        foreach ($monitors as $monitorData) {
            $url = $monitorData['url'];
            $type = $monitorData['type'];
            $dest_ip = $monitorData['dest_ip'];

            $attributes = ['url' => $url];
            $values = compact('type', 'dest_ip');

            $newMonitor = Monitor::updateOrCreate($attributes, $values);

            $existingRecordMonitorTab = MonitorTab::where('monitor_id', $newMonitor->id)
                ->where('tab_id', 1)
                ->first();

            $featured = $monitorData['featured'];
            $set_featured_at = $monitorData['featured'] === 1 ? now('Asia/Ho_Chi_Minh')->timestamp : null;

            if (!$existingRecordMonitorTab) {
                MonitorTab::create(['monitor_id' => $newMonitor->id, 'tab_id' => 1, 'featured' => $featured, 'set_featured_at' => $set_featured_at]);
            } else {
                MonitorTab::where('monitor_id', $newMonitor->id)
                    ->where('tab_id', 1)
                    ->update(['featured' => $featured, 'set_featured_at' => $set_featured_at]);
            }
        }
        return 'Monitors created successfully and added in tab 1';
    }

    public function addNewTab(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('wm.logout');
        }

        $user = Auth::user();

        $nameTab = $request->input('name-tab');
        if (!$nameTab) {
            return false;
        }

        $newTab = Tab::create(['name' => $nameTab]);
        $user->tabs()->attach($newTab);

        return true;
    }

    public function removeTab($tabId)
    {
        if (!Auth::check()) {
            return redirect()->route('wm.logout');
        }

        MonitorTab::where('tab_id', $tabId)->delete();
        UserTab::where('tab_id', $tabId)->delete();
        Tab::destroy($tabId);

        return 'Monitors deleted successfully';
    }

    public function renderTabs(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('wm.logout');
        }

        $user = Auth::user();
        return $user->tabs;
    }

    public function renderIncidents(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('wm.logout');
        }

        $tabId = $request->input('tabId');
        $fiveDaysAgo = (new DateTime())->sub(new DateInterval('P5D'));

        $incidents = Incident::getIncidents($tabId, $fiveDaysAgo);

        return $incidents;
    }
}
