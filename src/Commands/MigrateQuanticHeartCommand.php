<?php

namespace Quanticheart\Laravel\Commands;

use Illuminate\Console\Command;

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
    protected $signature = 'migrate:quanticheart';

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
        $path = realpath(__DIR__ . "/../");

        $this->info('Running migration ' );
        $this->call('php artisan migrate --path='. $path . '/Migrations');
        $this->comment('All done' );

        return true;
    }
}
