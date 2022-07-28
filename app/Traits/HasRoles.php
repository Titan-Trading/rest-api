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
        if($this->role->name === $roleName) {
            return true;
        }

        return false;
    }

    /**
     * Check if a user can perform an action
     */
    public function can($abilities, $arguments = [])
    {

    }
}