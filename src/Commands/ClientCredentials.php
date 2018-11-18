<?php

namespace Bor3y\ApiTemplate\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ClientCredentials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:api:client:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates/Replaces client credentials in env file';

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
        $clientId = str_random(50);
        $clientSecret = str_random(50);

        $path = $this->laravel->environmentFilePath();

        if (! Str::contains(file_get_contents($path), 'CLIENT_ID')) {
            file_put_contents($path, PHP_EOL."CLIENT_ID={$clientId}", FILE_APPEND);
        }else{
            $escaped = preg_quote($this->laravel['config']['auth.client_id'], '/');
            if(empty($escaped)){
                file_put_contents($path, preg_replace(
                    "/^CLIENT_ID=/m",
                    "CLIENT_ID={$clientId}",
                    file_get_contents($path)
                ));
            }else {
                file_put_contents($path, preg_replace(
                    "/^CLIENT_ID={$escaped}/m",
                    "CLIENT_ID={$escaped}",
                    file_get_contents($path)
                ));
            }
        }

        if (! Str::contains(file_get_contents($path), 'CLIENT_SECRET')) {
            file_put_contents($path, PHP_EOL."CLIENT_SECRET={$clientSecret}", FILE_APPEND);
        }else{
            $escaped = preg_quote($this->laravel['config']['auth.client_secret'], '/');
            if(empty($escaped)){
                file_put_contents($path, preg_replace(
                    "/^CLIENT_SECRET=/m",
                    "CLIENT_SECRET={$clientSecret}",
                    file_get_contents($path)
                ));
            }else {
                file_put_contents($path, preg_replace(
                    "/^CLIENT_SECRET={$escaped}/m",
                    "CLIENT_SECRET={$escaped}",
                    file_get_contents($path)
                ));
            }
        }

        \Artisan::call('config:clear');
        \Artisan::call('config:cache');
    }
}
