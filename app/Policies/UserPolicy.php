<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given employee can be managed by the user.
     */
    public function manage(User $user, User $employee): bool
    {
        return $user->branch_id === $employee->branch_id
            && $employee->role === 'employee'
            && $user->isManager();
    }
}
