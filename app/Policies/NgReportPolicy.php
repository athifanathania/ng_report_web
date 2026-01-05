<?php

namespace App\Policies;

use App\Models\NgReport;
use App\Models\User;

class NgReportPolicy
{
    // Siapa saja boleh melihat daftar laporan
    public function viewAny(User $user): bool
    {
        return true;
    }

    // Siapa saja boleh melihat detail laporan
    public function view(User $user, NgReport $ngReport): bool
    {
        return true;
    }

    // Siapa saja boleh membuat laporan baru
    public function create(User $user): bool
    {
        return true;
    }

    // Siapa saja boleh edit (misal operator salah ketik)
    public function update(User $user, NgReport $ngReport): bool
    {
        return true;
    }

    // HANYA ADMIN YANG BOLEH HAPUS
    public function delete(User $user, NgReport $ngReport): bool
    {
        return $user->isAdmin();
    }

    // HANYA ADMIN YANG BOLEH HAPUS BANYAK SEKALIGUS
    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}