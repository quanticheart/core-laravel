<?php

namespace Quanticheart\Laravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

/**
 * Class SendPushToUsers
 * @package App\Console\Commands
 *
 */
class MigrateQuanticHeartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:quanticheart {test?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all tables and re-run all migrations QuanticHeart Databases';

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
     * @return mixed
     */
    public function handle()
    {
//        $path = realpath(__DIR__ . "/../");
//        $test = $this->option('--test');
        if ($this->argument('test') === 'test') {
            $path = "/packages/quanticheart/laravel/src/Migrations";
        } else {
            $path = "/vendor/quanticheart/local/src/Migrations";
        }

        $this->info('Running QaunticHeart migrations');
        $this->info($path);
//        $this->info('php artisan migrate --path="' . $path);
        try {
            Artisan::call('migrate', array('--path' => $path));
            $this->info(Artisan::output());
        } catch (\Throwable $th) {
            $this->comment("Failed to migrate tables. " . $th->getMessage());
        }
        $this->comment('All done');

        return true;
    }
}
