<?php
namespace App\Traits;


trait HasRoles
{
    /**
     * Check if a user has a role
     */
    public function hasRole(string $roleName): bool
    {
        // TODO: check if a user has the given role by name

        return true;
    }
}