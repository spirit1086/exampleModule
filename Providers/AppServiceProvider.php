<?php

namespace App\Providers;

use App\Modules\Jwt\Services\ServiceJwt;
use App\Modules\Resume\Repository\ResumeRepository;
use App\Modules\Resume\Service\ResumeService;
use App\Modules\Setting\Repository\SettingRepository;
use App\Modules\Setting\Services\SettingService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        App::singleton('dates',  \App\Helpers\Dates\Dates::class);
        App::singleton('minio',  \App\Helpers\Minio\Minio::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('App\Modules\Jwt\Interfaces\InterfaceJwt', function ($app) {
            return new ServiceJwt(Redis::connection(config('database.redis_connection')));
        });
        $this->app->bind('App\Modules\Resume\Repository\InterfaceResumeRepository', function ($app) {
            return new ResumeRepository();
        });
        $this->app->bind('App\Modules\Resume\Service\InterfaceResumeService', function ($app) {
           return new ResumeService(new ResumeRepository(),Redis::connection(config('database.redis_connection')));
        });
        $this->app->bind('App\Modules\Setting\Repository\InterfaceSettingRepository', function ($app) {
            return new SettingRepository();
        });
        $this->app->bind('App\Modules\Setting\Services\InterfaceSettingService', function ($app) {
            return new SettingService(new SettingRepository());
        });
    }
}
