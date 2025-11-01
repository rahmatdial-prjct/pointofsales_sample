<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function view(User $user, Transaction $transaction)
    {
        return $user->isDirector() || 
               ($user->branch_id === $transaction->branch_id && 
                ($user->isManager() || $user->isEmployee()));
    }

    public function create(User $user)
    {
        return $user->isManager() || $user->isEmployee();
    }

    public function cancel(User $user, Transaction $transaction)
    {
        return $user->branch_id === $transaction->branch_id && 
               ($user->isManager() || $user->isEmployee());
    }

    public function printInvoice(User $user, Transaction $transaction)
    {
        return $this->view($user, $transaction);
    }
} 