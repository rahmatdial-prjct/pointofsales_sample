<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine if the given product can be managed by the user.
     */
    public function manage(User $user, Product $product): bool
    {
        return $user->branch_id === $product->branch_id && $user->isManager();
    }
}
