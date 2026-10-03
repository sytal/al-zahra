<?php

namespace App\Modules\Support\Policies;

use App\Models\User;
use App\Modules\Support\Models\SupportTicket;

class SupportTicketPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('contact.manage');
    }

    public function view(User $user, SupportTicket $supportTicket): bool
    {
        return $user->can('contact.manage') || $user->id === $supportTicket->user_id;
    }

    public function update(User $user, SupportTicket $supportTicket): bool
    {
        return $user->can('contact.manage');
    }
}
