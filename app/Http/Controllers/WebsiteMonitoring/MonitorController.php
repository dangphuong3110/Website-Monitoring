<?php

namespace App\Http\Controllers\WebsiteMonitoring;

use App\Http\Controllers\Controller;
use App\Models\MonitorTab;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class MonitorController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('wm.logout');
        }

        $user = Auth::user();

        return view('website-monitor.dashboard', compact('user'));
    }

    public function updateFeaturedMonitor(Request $request, $monitorId)
    {
        if (!Auth::check()) {
            return redirect()->route('wm.logout');
        }

        $isChecked = (boolean)$request->input('isChecked');
        $tabId = $request->input('tabId');

        $featured = $isChecked ? 1 : 0;

        $createdUnix = Carbon::now('Asia/Ho_Chi_Minh')->timestamp;
        $set_featured_at = $featured ? $createdUnix : null;

        MonitorTab::where('monitor_id', $monitorId)
            ->where('tab_id', $tabId)
            ->update(['featured' => $featured, 'set_featured_at' => $set_featured_at]);

        return 'Updated successfully.';
    }
}
