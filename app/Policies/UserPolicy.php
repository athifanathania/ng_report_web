<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Siapa yang boleh melihat daftar user di sidebar?
     * HANYA ADMIN.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Siapa yang boleh melihat detail user?
     */
    public function view(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Siapa yang boleh menambah user baru?
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Siapa yang boleh edit user?
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Siapa yang boleh hapus user?
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }
}