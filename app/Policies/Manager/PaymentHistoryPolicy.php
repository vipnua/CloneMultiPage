<?php

namespace App\Policies\Manager;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentHistoryPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->is_admin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(Admin $user)
    {
        if ($user->can('paymenthistory_view')) {
            return true;
        }
    }
}
