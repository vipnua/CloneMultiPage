<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model\Functions' => 'App\Policies\System\FunctionManager',
        'App\Model\Admin' => 'App\Policies\AdminPolicy',
        'App\Model\RoleSecond' => 'App\Policies\System\RolePolicy',
        'App\User' => 'App\Policies\UserPolicy',
        'App\Model\Browser\Resource' => 'App\Policies\Browser\ResourcePolicy',
        'App\Model\Plan' => 'App\Policies\Manager\PlanPolicy',
        'App\Model\PaymentMethod' => 'App\Policies\Manager\PaymentMethodPolicy',
        'App\Model\PaymentTransaction' => 'App\Policies\Manager\PaymentHistoryPolicy',
        'App\Model\Discount' => 'App\Policies\Manager\DiscountPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //
    }
}
