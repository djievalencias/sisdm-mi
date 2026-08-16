<?php

namespace App\Policies;

use App\Models\CutiPerizinan;
use App\Models\User;

class CutiPerizinanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->bawahan()->exists();
    }

    public function view(User $user, CutiPerizinan $cuti): bool
    {
        return $user->hasRole('admin') || $cuti->user->id_atasan === $user->id;
    }

    /**
     * Self-approval is blocked even for admins: a decision must always
     * come from someone other than the requester.
     */
    public function approve(User $user, CutiPerizinan $cuti): bool
    {
        return $cuti->status_pengajuan === 'diajukan'
            && $cuti->id_user !== $user->id
            && ($user->hasRole('admin') || $cuti->user->id_atasan === $user->id);
    }

    public function reject(User $user, CutiPerizinan $cuti): bool
    {
        return $this->approve($user, $cuti);
    }

    public function undo(User $user, CutiPerizinan $cuti): bool
    {
        return in_array($cuti->status_pengajuan, ['disetujui', 'ditolak'], true)
            && $cuti->id_user !== $user->id
            && ($user->hasRole('admin') || $cuti->user->id_atasan === $user->id);
    }
}
