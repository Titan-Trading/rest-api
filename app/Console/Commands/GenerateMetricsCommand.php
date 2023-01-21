<?php

namespace App\Console\Commands;

use App\Models\Metric;
use App\Models\User;
use App\Services\Metrics;
use Illuminate\Console\Command;

class GenerateMetricsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'metrics:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate metrics';

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

        $metricTypes = [
            'total-sales',
            'overall-pnl',
            'bootcamp-completion',
            'pnl-by-strategy',
            'exchange-account-balances',
            'profit-by-exchange'
        ];

        foreach(User::all() as $user) {
            foreach($metricTypes as $name) {
                
                $metric = Metric::whereName($name)
                    ->where('metricable_type', 'user')
                    ->where('metricable_id', $user->id)
                    ->first();

                if(!$metric) {
                    $metric = new Metric();
                    $metric->name = $name;
                    $metric->metricable_type = 'user';
                    $metric->metricable_id = $user->id;
                }

                $metricData = Metrics::generate($name);
    
                if($metricData) {
                    $metric->value = json_encode($metricData);
                    $metric->save();
                }
            }
        }

        return Command::SUCCESS;
    }
}
