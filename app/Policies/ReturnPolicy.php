<?php

namespace App\Policies;

use App\Models\ReturnTransaction;
use App\Models\User;

class ReturnPolicy
{
    public function view(User $user, ReturnTransaction $return)
    {
        return $user->isDirector() || 
               ($user->branch_id === $return->branch_id && 
                ($user->isManager() || $user->isEmployee()));
    }

    public function create(User $user)
    {
        return $user->isManager() || $user->isEmployee();
    }

    public function approve(User $user, ReturnTransaction $return)
    {
        return $user->branch_id === $return->branch_id &&
               $user->isManager() &&
               $return->status === 'menunggu'; // Updated to Indonesian status
    }
} 