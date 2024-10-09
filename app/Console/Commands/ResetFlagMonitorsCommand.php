<?php

namespace App\Console\Commands;

use App\Models\Monitor;
use Illuminate\Console\Command;

class ResetFlagMonitorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:reset_flags';

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
        Monitor::update(['flag' => 'free']);

        return 0;
    }
}
