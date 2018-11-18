<?php

namespace Bor3y\ApiTemplate\Commands;

use Illuminate\Console\Command;

class PublishCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:api:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'just text command';

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
        $this->publishApiHelpers();
        $this->publishAPiRequests();
        $this->publishAPiMiddleware();
        $this->publishApiControllers();

        $this->generateKeys();
    }


    private function publishApiControllers(){
        $api_dist = base_path('app') . DIRECTORY_SEPARATOR."Http" . DIRECTORY_SEPARATOR. "Controllers" . DIRECTORY_SEPARATOR . "API" . DIRECTORY_SEPARATOR . "Auth";
        $api_source = dirname (__DIR__, 1) . DIRECTORY_SEPARATOR . "Controllers" . DIRECTORY_SEPARATOR . "API" . DIRECTORY_SEPARATOR . "Auth";
        if (!file_exists($api_dist)) {
            mkdir($api_dist, 0777, true);
        }
        $this->copyIfNotExist($api_source . DIRECTORY_SEPARATOR . "_AuthController.php", $api_dist . DIRECTORY_SEPARATOR . "AuthController.php");
        $this->copyIfNotExist($api_source . DIRECTORY_SEPARATOR . "_PasswordController.php", $api_dist . DIRECTORY_SEPARATOR . "PasswordController.php");
    }

    private function publishAPiRequests(){
        $api_dist = base_path('app'). DIRECTORY_SEPARATOR ."Http" . DIRECTORY_SEPARATOR . "Requests" . DIRECTORY_SEPARATOR . "API";
        $api_source = dirname (__DIR__, 1) . DIRECTORY_SEPARATOR . "Requests" . DIRECTORY_SEPARATOR . "API";
        if (!file_exists($api_dist . DIRECTORY_SEPARATOR . "Auth")) {
            mkdir($api_dist . DIRECTORY_SEPARATOR . "Auth", 0777, true);
        }
        $this->copyIfNotExist($api_source . DIRECTORY_SEPARATOR . "Auth" . DIRECTORY_SEPARATOR . "_LoginRequest.php", $api_dist . DIRECTORY_SEPARATOR . "Auth" . DIRECTORY_SEPARATOR . "LoginRequest.php");
        $this->copyIfNotExist($api_source . DIRECTORY_SEPARATOR . "Auth" . DIRECTORY_SEPARATOR . "_RegisterRequest.php", $api_dist . DIRECTORY_SEPARATOR . "Auth" . DIRECTORY_SEPARATOR . "RegisterRequest.php");
        $this->copyIfNotExist($api_source . DIRECTORY_SEPARATOR . "Auth" . DIRECTORY_SEPARATOR . "_TokenRefreshRequest.php", $api_dist . DIRECTORY_SEPARATOR . "Auth" . DIRECTORY_SEPARATOR . "TokenRefreshRequest.php");
        if (!file_exists($api_dist . DIRECTORY_SEPARATOR . "User")) {
            mkdir($api_dist . DIRECTORY_SEPARATOR . "User", 0777, true);
        }
        $this->copyIfNotExist($api_source . DIRECTORY_SEPARATOR . "User" . DIRECTORY_SEPARATOR . "_ChangePasswordRequest.php", $api_dist . DIRECTORY_SEPARATOR . "User" . DIRECTORY_SEPARATOR . "ChangePasswordRequest.php");
        $this->copyIfNotExist($api_source . DIRECTORY_SEPARATOR . "User" . DIRECTORY_SEPARATOR . "_ResetPasswordRequest.php", $api_dist . DIRECTORY_SEPARATOR . "User" . DIRECTORY_SEPARATOR . "ResetPasswordRequest.php");
    }

    private function publishAPiHelpers(){
        $api_dist = base_path('app') . DIRECTORY_SEPARATOR . "Helpers" . DIRECTORY_SEPARATOR . "API";
        $api_source = dirname (__DIR__, 1) . DIRECTORY_SEPARATOR . "Helpers" . DIRECTORY_SEPARATOR . "API";
        if (!file_exists($api_dist)) {
            mkdir($api_dist, 0777, true);
        }
        $this->copyIfNotExist($api_source . DIRECTORY_SEPARATOR . "_API.php", $api_dist . DIRECTORY_SEPARATOR . "API.php");
    }

    private function publishAPiMiddleware(){
        $api_dist = base_path('app') . DIRECTORY_SEPARATOR . "Http" . DIRECTORY_SEPARATOR . "Middleware";
        $api_source = dirname (__DIR__, 1) . DIRECTORY_SEPARATOR . "Middleware";
        if (!file_exists($api_dist)) {
            mkdir($api_dist, 0777, true);
        }
        $this->copyIfNotExist($api_source . DIRECTORY_SEPARATOR . "_AuthorizePublicApiRequests.php", $api_dist . DIRECTORY_SEPARATOR . "AuthorizePublicApiRequests.php");
    }

    private function copyIfNotExist($src, $dist){
        if(!file_exists($dist)){
            copy($src, $dist);
        }
    }

    private function generateKeys(){
        \Artisan::call('jwt:secret');
        \Artisan::call('auth:api:client:generate');
    }
}
