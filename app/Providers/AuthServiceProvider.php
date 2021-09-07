<?php

namespace App\Providers;

use App\Policies\CommentPolicy;
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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::resource('comments_custom', CommentPolicy::class, [
            'delete' => 'delete',
            'reply' => 'reply',
            'edit' => 'edit',
            'vote' => 'vote',
            'store' => 'store'
        ]);
    }
}
