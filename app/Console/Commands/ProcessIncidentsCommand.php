<?php

namespace App\Console\Commands;

use App\Library\MyiNETLibrary;
use App\Library\WorkerLibrary;
use App\Models\Incident;
use Illuminate\Console\Command;

class ProcessIncidentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:incidents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process incidents and send email alerts';

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
        $incidents = Incident::where('incidents.created_at', '!=', NULL)
            ->where(function($query) {
                $query->where('incidents.count', '=', 5)
                    ->where('incidents.updated_at', '!=', NULL)
                    ->orWhere('incidents.count', '>=', 13);
            })
            ->join('monitors', 'incidents.monitor_id', 'monitors.id')
            ->join('monitor_tab', 'monitors.id', 'monitor_tab.monitor_id')
            ->join('tabs', 'monitor_tab.tab_id', 'tabs.id')
            ->join('user_tab', 'tabs.id', 'user_tab.tab_id')
            ->join('users', 'user_tab.user_id', 'users.id')
            ->select('incidents.id', 'incidents.status', 'incidents.name AS url', 'incidents.count', 'incidents.started_at', 'incidents.latest_status_code AS status_code', 'incidents.latest_status_message AS status_message', 'monitors.url AS name_domain', 'tabs.id AS tab_id', 'tabs.name AS tab_name', 'users.id AS user_id', 'users.name AS username', 'users.email')
            ->get();

        $incidents = $incidents->filter(function ($incident) {
            return $incident->status_code == 404 || $incident->status_code == 408 || $incident->status_code >= 500;
        });

        if ($incidents->isNotEmpty()) {
            $incidents_tab1 = [];
            $urls_tab1 = [];
            $incidentsByEmail = [];
            foreach ($incidents as $incident) {
                if ($incident->count == 5) {
                    $updateCreatedAtIncident = Incident::find($incident->id);
                    $updateCreatedAtIncident->updated_at = NULL;
                    $updateCreatedAtIncident->save();
                }

                if ($incident->count >= 13) {
                    $updateCreatedAtIncident = Incident::find($incident->id);
                    $updateCreatedAtIncident->created_at = NULL;
                    $updateCreatedAtIncident->save();
                }

                if ($incident->tab_id == 1) {
                    if (!in_array($incident->name_domain, array_column($incidents_tab1, 'name_domain'))) {
                        $incidents_tab1[] = $incident;
                        $urls_tab1[] = $incident->url . ' - ' . $incident->status_code . ' - ' . $incident->status_message . ' (Check count: ' . $incident->count . ')';
                    }
                    continue;
                }

                $email = $incident->email;
                if (!isset($incidentsByEmail[$email])) {
                    $incidentsByEmail[$email] = [];
                }

                if (!in_array($incident->name_domain, array_column($incidentsByEmail[$email], 'name_domain'))) {
                    $incidentsByEmail[$email][]

                        = $incident;
                }
            }

            if ($incidents_tab1) {
                //Thong bao tele
                $data = [];
                $stringUrls = '';
                foreach ($urls_tab1 as $url) {
                    $stringUrls .= $url . " \r\n";
                }

//                $stringUrls .= 'View incident details at: ' . config('app.url') . '/website-monitoring/dashboard';
                $data['content'] = count($incidents_tab1) == 1 ? "Monitor is DOWN \r\n" . $stringUrls : "Monitors are DOWN \r\n" . $stringUrls;
                //$data['accountEmail'] = 'tieudv@zozo.vn';// Thong bao toi 1 tai khoan my.inet bat ky, action: send
                $data['groupId'] = '-4018172018';// Thong bao toi 1 gr tele dinh san, action: sendtest
                $data['accountEmail'] = 'support@zozo.vn';
                (new MyiNETLibrary())::telebotSend('sendtest', $data);

                //Thong bao mail
                $mail = [];
                $mail['to'] = 'support@zozo.vn';
                $mail['subject'] = count($incidents_tab1) == 1 ? 'Monitor is DOWN' : 'Monitors are DOWN';
                $mail['content'] = view('mailer.send-mail')->with(['incidents' => $incidents_tab1])->render();

                WorkerLibrary::mail('send', $mail);
            }

            foreach ($incidentsByEmail as $email => $incidentsForEmail) {
                if ($incidentsForEmail) {
                    $mail = [];
                    $mail['to'] = $email;
                    $mail['subject'] = count($incidentsForEmail) == 1 ? 'Monitor is DOWN' : 'Monitors are DOWN';
                    $mail['content'] = view('mailer.send-mail')->with(['incidents' => $incidentsForEmail])->render();

                    WorkerLibrary::mail('send', $mail);
                }
            }
        }
        return 0;
    }
}
