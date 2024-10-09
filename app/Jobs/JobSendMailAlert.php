<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JobSendMailAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $incident;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        $incidents = Incident::where('incidents.created_at', '!=', NULL)
//            ->where('incidents.count', '>=', 8)
//            ->where('incidents.started_at', '<', now()->subMinutes(30))
//            ->join('monitors', 'incidents.monitor_id', '=', 'monitors.id')
//            ->join('monitor_tab', 'monitors.id', '=', 'monitor_tab.monitor_id')
//            ->join('tabs', 'monitor_tab.tab_id', '=', 'tabs.id')
//            ->join('user_tab', 'tabs.id', '=', 'user_tab.tab_id')
//            ->join('users', 'user_tab.user_id', '=', 'users.id')
//            ->select('incidents.id', 'incidents.status', 'incidents.name AS url', 'incidents.started_at', 'monitors.url AS name_domain', 'tabs.id AS tab_id', 'tabs.name AS tab_name', 'users.id AS user_id', 'users.name AS username', 'users.email')
//            ->get();
//
//        if ($incidents->isNotEmpty()) {
//            $incidents_tab1 = [];
//            $incidentsByEmail = [];
//            foreach ($incidents as $incident) {
//                $updateCreatedAtIncident = Incident::find($incident->id);
//                $updateCreatedAtIncident->created_at = NULL;
//                $updateCreatedAtIncident->save();
//
//                if ($incident->tab_id == 1) {
//                    if (!in_array($incident->name_domain, array_column($incidents_tab1, 'name_domain'))) {
//                        $incidents_tab1[] = $incident;
//                    }
//                    continue;
//                }
//
//                $email = $incident->email;
//                if (!isset($incidentsByEmail[$email])) {
//                    $incidentsByEmail[$email] = [];
//                }
//
//                if (!in_array($incident->name_domain, array_column($incidentsByEmail[$email], 'name_domain'))) {
//                    $incidentsByEmail[$email][] = $incident;
//                }
//            }
//
//            if ($incidents_tab1) {
//                $mail = [];
////                $mail['to'] = 'support@zozo.vn';
//                $mail['to'] = 'phuongdangnguyen31102002@gmail.com';
//                $mail['subject'] = count($incidents_tab1) == 1 ? 'Monitor is DOWN' : 'Monitors are DOWN';
//                $mail['content'] = view('mailer.send-mail')->with(['incidents' => $incidents_tab1])->render();
//
//                WorkerLibrary::mail('send', $mail);
//            }
//
//            foreach ($incidentsByEmail as $email => $incidentsForEmail) {
//                if ($incidentsForEmail) {
//                    $mail = [];
//                    $mail['to'] = $email;
//                    $mail['subject'] = count($incidentsForEmail) == 1 ? 'Monitor is DOWN' : 'Monitors are DOWN';
//                    $mail['content'] = view('mailer.send-mail')->with(['incidents' => $incidentsForEmail])->render();
//
//                    WorkerLibrary::mail('send', $mail);
//                }
//            }
//        }
    }
}
