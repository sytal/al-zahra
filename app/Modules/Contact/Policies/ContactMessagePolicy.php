<?php

namespace App\Modules\Contact\Policies;

use App\Models\User;
use App\Modules\Contact\Models\ContactMessage;

class ContactMessagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('contact.manage');
    }

    public function view(User $user, ContactMessage $message): bool
    {
        return $user->can('contact.manage');
    }

    public function update(User $user, ContactMessage $message): bool
    {
        return $user->can('contact.manage');
    }
}
