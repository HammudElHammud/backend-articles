<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Setup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the whole project';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            if (!env('APP_KEY')) {
                Artisan::call('key:generate');
                $this->info(Artisan::output());
            } else {
                $this->info('Key creation was skipped');
            }

            Artisan::call('db:wipe');
            $this->info(Artisan::output());

            Artisan::call('migrate:install');
            $this->info(Artisan::output());

            Artisan::call('migrate');
            $this->info(Artisan::output());

            $this->info('Seeder started');

            Artisan::call('db:seed');
            $this->info(Artisan::output());
            $this->info('Hit enter to set the password grant client');

            $clientName = 'Password Grant Client';
            $redirectUri = 'http://localhost';
            Artisan::call("passport:client --password --name=\"{$clientName}\" --redirect_uri=\"{$redirectUri}\"");
            Artisan::call("passport:client --personal");
            $this->info(Artisan::output());



            $this->info('Setup completed successfully.');

            return 0;
        } catch (\Exception $e) {
            $this->error('An error occurred during setup: ' . $e->getMessage());
            return 1;
        }
    }

}
