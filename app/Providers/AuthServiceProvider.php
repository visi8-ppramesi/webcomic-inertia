<?php

namespace App\Providers;

use App\Helpers\Acl;
use App\Models\TokenTransaction;
use App\Models\User;
use App\Policies\CommentPolicy;
use App\Policies\TokenTransactionPolicy;
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
        // TokenTransaction::class => TokenTransactionPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-chapter-transactions', function (User $user){
            return $user->hasPermissionTo(Acl::PERMISSION_TRANSACTION_MANAGE);
        });

        Gate::define('view-transactions', function (User $user, User $requestUser){
            $u = auth()->user();
            if(empty($u)){
                return false;
            }
            return $u->hasPermissionTo(Acl::PERMISSION_TRANSACTION_MANAGE) || $u->id == $requestUser->id;
        });

        Gate::resource('comments_custom', CommentPolicy::class, [
            'delete' => 'delete',
            'reply' => 'reply',
            'edit' => 'edit',
            'vote' => 'vote',
            'store' => 'store'
        ]);
    }
}
