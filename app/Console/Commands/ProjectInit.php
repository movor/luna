<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ProjectInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize project with database creation migrations and img generators';

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
        // Create database
        if ($this->call('db:create')) {
            $this->error('Cannot Init project!');
            return 1;
        }

        // Migrate
        $this->call('migrate');

        // Placeholders
        $this->call('image-placeholder:generate');

        // Activate Default Cron Job if one doesn't not exist
        $process = new Process("crontab -l | grep artisan | wc -l");

        $process->run();

        $errResponse = (int) trim($process->getOutput());

        if (!$errResponse) {
            $process = new Process('(crontab -l 2>/dev/null; echo "* * * * * php '
                . base_path() . '/artisan schedule:run >> /dev/null 2>&1") | crontab -');

            $process->run();

            if (!$process->isSuccessful()) {
                $exception = new ProcessFailedException($process);
                $this->error($exception->getMessage());
            }
        }
    }
}