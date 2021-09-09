<?php

namespace App\Policies;

use App\Helpers\Acl;
use App\Models\TokenTransaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TokenTransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TokenTransaction  $tokenTransaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TokenTransaction $tokenTransaction)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TokenTransaction  $tokenTransaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TokenTransaction $tokenTransaction)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TokenTransaction  $tokenTransaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TokenTransaction $tokenTransaction)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TokenTransaction  $tokenTransaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TokenTransaction $tokenTransaction)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TokenTransaction  $tokenTransaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TokenTransaction $tokenTransaction)
    {
        //
    }

    public function getUserTransactions(User $user){
        $u = auth()->user();
        if(empty($u)){
            return false;
        }
        return $u->hasPermissionTo(Acl::PERMISSION_TRANSACTION_MANAGE) || $u->id == $user->id;
    }
}
