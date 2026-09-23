<?php

namespace App\Modules\Certificate\Policies;

use App\Models\User;
use App\Modules\Certificate\Models\Certificate;

class CertificatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('certificates.manage');
    }

    public function view(User $user, Certificate $certificate): bool
    {
        return $user->can('certificates.manage') || $user->id === $certificate->user_id;
    }

    public function delete(User $user, Certificate $certificate): bool
    {
        return $user->can('certificates.manage');
    }
}
