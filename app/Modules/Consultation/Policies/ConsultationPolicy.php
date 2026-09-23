<?php

namespace App\Modules\Consultation\Policies;

use App\Models\User;
use App\Modules\Consultation\Models\Consultation;

class ConsultationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('consultations.respond');
    }

    public function view(User $user, Consultation $consultation): bool
    {
        return $user->can('consultations.respond') || $user->id === $consultation->user_id;
    }

    public function update(User $user, Consultation $consultation): bool
    {
        return $user->can('consultations.respond');
    }
}
