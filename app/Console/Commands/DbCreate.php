<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use PDO;

class DbCreate extends Command
{
    use ConfirmableTrait;

    /**
     * Current database name (from env)
     *
     * @var string
     */
    protected $db;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create {--force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create db defined in .env file if not exists';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->db = env('DB_DATABASE');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return 1;
        }

        $created = false;

        try {
            $pdo = $this->getPDOConnection(env('DB_HOST'), env('DB_PORT'), env('DB_USERNAME'), env('DB_PASSWORD'));

            // Database will be created if not exists and $created will be true.
            // If db exists $created will be false.
            $this->comment(PHP_EOL . "Creating databse {$this->db}");
            $created = $pdo->exec("CREATE DATABASE $this->db");
        } catch (\Exception $e) {
            $this->error(PHP_EOL . sprintf('Failed to create %s database, %s', $this->db, $e->getMessage()) . PHP_EOL);
        }

        if ($created) {
            $this->info(PHP_EOL . sprintf('Database "%s" created successfully', $this->db) . PHP_EOL);
            return 0;
        } else {
            $this->comment(PHP_EOL . sprintf('Database "%s" already exists', $this->db) . PHP_EOL);
            return 1;
        }
    }

    /**
     * Establish PDO connection
     *
     * @param  string  $host
     * @param  integer $port
     * @param  string  $username
     * @param  string  $password
     *
     * @return PDO
     */
    protected function getPDOConnection($host, $port, $username, $password)
    {
        return new PDO(sprintf('mysql:host=%s;port=%d;', $host, $port), $username, $password);
    }
}
