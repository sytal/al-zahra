<?php

namespace App\Modules\Research\Policies;

use App\Models\User;
use App\Modules\Research\Models\ResearchPaper;

class ResearchPaperPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('research.create');
    }

    public function view(User $user, ResearchPaper $paper): bool
    {
        return $user->can('research.create');
    }

    public function create(User $user): bool
    {
        return $user->can('research.create');
    }

    public function update(User $user, ResearchPaper $paper): bool
    {
        return $user->can('research.edit');
    }

    public function delete(User $user, ResearchPaper $paper): bool
    {
        return $user->can('research.delete');
    }

    public function publish(User $user, ResearchPaper $paper): bool
    {
        return $user->can('research.publish');
    }
}
