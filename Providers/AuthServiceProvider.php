<?php

namespace App\Providers;

use App\Modules\Resume\Models\Resume;
use App\Modules\Resume\Policies\ResumePolicy;
use App\Modules\Setting\Models\Setting;
use App\Modules\Setting\Policies\SettingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Resume::class => ResumePolicy::class,
        Setting::class => SettingPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
