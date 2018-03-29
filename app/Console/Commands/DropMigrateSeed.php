<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class DropMigrateSeed extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movor:drop-migrate-seed {--force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all tables, than perform migrate and seed';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Create db (from env) if not exists
        $this->call('movor:createdb');

        // Drop tables
        $this->call('movor:dropdb');

        // Migrate
        $this->call('migrate');

        // Seed
        $this->call('db:seed');

        // Success message
        $this->info(PHP_EOL . 'Database dropped, migrated and seeded successfully' . PHP_EOL);
    }
}
