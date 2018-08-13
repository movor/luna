<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProjectWizard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // TODO: add "--force" option that should override existing ".env" file
    protected $signature = 'project:wizard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Answer small questionnaire to generate ".env" file';

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
        $this->question('Answer me few question regarding application nomenclature and environment');
        $appName = $this->ask('Name of your application', 'Movor Uni');
        $appEnvironment = $this->choice('Choose your environment', ['local', 'production'], 0);
        $appDomain = $this->ask('Enter domain name or ip address', 'http://movor-uni.loc');
        $appContactEmail = $this->ask('Enter Contact email', 'contact@movor.io');
        $appContactPhone = $this->ask('Enter Contact Phone', '+381696666661');

        $this->question('Answer me few question regarding database configuration');
        $dbConnection = $this->choice('Database server connection', ['mysql', 'sqlite'], 0);
        if ($dbConnection === 'mysql') {
            $dbHost = $this->ask('Database host name or ip address?', 'localhost');
            $dbPort = $this->ask('Database port number', 3306);
            $dbDatabase = $this->ask('Database name', 'movor_uni');
            $dbUsername = $this->ask('Database user', 'root');
            $dbPassword = $this->ask("Database user's password", 'root');
        } else {
            $dbDatabase = app()->basePath() . $this->ask('Database file name, I will assume that database file should be located at the root of the project', 'database.sqlite');
        }

        $this->question('Answer me few question regarding mail configuration');
        $googleAnalytics = $this->confirm('Do you use Google Analytics', false);
        if ($googleAnalytics) {
            $googleAnalyticsKey = $this->ask('Enter your google_analytics_key: ', '');
        }
        $googleRecaptcha = $this->confirm('Do you use Google ReCaptcha', false);
        if ($googleRecaptcha) {
            $googleRecaptchaPublic = $this->ask('Enter your google recaptcha PUBLIC key: ', '');
            $googleRecaptchaSecret = $this->ask('Enter your google recaptcha PRIVATE key: ', '');
        }
        $disqus = $this->confirm('Do you use DISQUS services', false);
        if ($disqus) {
            $disqusDomain = $this->ask('Enter your DISQUS domain: ', '');
        }

        $inMemoryEnvironmentVariables = collect([
            'APP_NAME' => $appName,
            'APP_ENV' => $appEnvironment,
            'APP_KEY' => '', // To be generated
            'APP_DEBUG' => $appEnvironment === 'local' ? 'true' : 'false',
            'APP_LOG_LEVEL' => 'debug',
            'APP_URL' => $appDomain,
            'APP_CONTACT_EMAIL' => $appContactEmail,
            'APP_CONTACT_PHONE' => $appContactPhone,
            'DB_CONNECTION' => $dbConnection,
            'DB_DATABASE' => $dbDatabase,
            'DB_HOST' => empty($dbHost) ? '' : $dbHost,
            'DB_PORT' => empty($dbPort) ? '' : $dbPort,
            'DB_USERNAME' => empty($dbUsername) ? '' : $dbUsername,
            'DB_PASSWORD' => empty($dbPassword) ? '' : $dbPassword,

            'BROADCAST_DRIVER' => 'log',
            'CACHE_DRIVER' => 'file',
            'SESSION_DRIVER' => 'file',
            'SESSION_LIFETIME' => 120,
            'QUEUE_DRIVER' => 'sync',

            'MAIL_DRIVER' => 'mailgun',
            'MAILGUN_DOMAIN' => '',
            'MAILGUN_SECRET' => '',
            'MAIL_FROM_ADDRESS' => 'movor_uni@mail.com',
            'MAIL_FROM_NAME' => 'Movor Uni',
            'MAIL_FORCE_RECIPIENT' => 'test@movor.io',
            'MAIL_FORCE_RECIPIENT_NAME' => "Movor Dev",

            'GOOGLE_ANALYTICS_KEY' => empty($googleAnalyticsKey) ? '' : $googleAnalyticsKey,
            'GOOGLE_RECAPTCHA_KEY' => empty($googleRecaptchaPublic) ? '' : $googleRecaptchaPublic,
            'GOOGLE_RECAPTCHA_SECRET' => empty($googleRecaptchaSecret) ? '' : $googleRecaptchaSecret,

            'MIX_DISQS_WEBSITE' => empty($disqusDomain) ? '' : $disqusDomain,
            'JS_ENV' => $appEnvironment,
            'JS_APP_URL' => $appDomain,
            'JS_PAYPAL_CLIENT_ID' => '',
        ]);


        $textEnvConfiguration = $inMemoryEnvironmentVariables->map(function ($item, $key) {
            // if key contains space or similar character return it wrapped with "quotes"
            return preg_match('/\s/', $item) ? $key . '=' . '"' . $item . '"' : $key . '=' . $item;
        })->reduce(function ($carry, $item) {
            return $carry . $item . PHP_EOL;
        }, '');

        if ($this->confirm('Do you want to review environment variables?', true)) {
            $this->info($textEnvConfiguration);
        }

        $readyForWrite = $this->confirm('Do you want to save ".env" file', true);
        if ($readyForWrite) {
            file_put_contents(base_path('.env'), $textEnvConfiguration);

            $this->call('key:generate');
            $this->info(PHP_EOL . 'Activate application inside of /etc/apache2/sites-available, for more documentation look at docs "https://docs.movor.io/apache2-basics/"');
            $this->info('Configuration is generated!!!' . PHP_EOL);


            // TODO: Application is always in production, it should be as defined above inside of $appEnvironment


        } else {
            if ($this->confirm("Do you want to run this wizard again?", false)) {
                $this->call('project:wizard');
            }
        }
    }
}
