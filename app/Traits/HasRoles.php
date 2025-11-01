<?php

namespace App\Traits;

trait HasRoles
{
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function hasAllRoles(array $roles): bool
    {
        return count(array_intersect([$this->role], $roles)) === count($roles);
    }
} 