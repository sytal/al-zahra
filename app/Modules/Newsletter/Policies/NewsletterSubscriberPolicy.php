<?php

namespace App\Modules\Newsletter\Policies;

use App\Models\User;
use App\Modules\Newsletter\Models\NewsletterSubscriber;

class NewsletterSubscriberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('settings.manage');
    }

    public function view(User $user, NewsletterSubscriber $subscriber): bool
    {
        return $user->can('settings.manage');
    }
}
