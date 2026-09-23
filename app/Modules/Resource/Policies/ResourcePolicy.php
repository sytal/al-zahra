<?php

namespace App\Modules\Resource\Policies;

use App\Models\User;
use App\Modules\Resource\Models\Resource;

class ResourcePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('resources.create');
    }

    public function view(User $user, Resource $resource): bool
    {
        return $user->can('resources.create');
    }

    public function create(User $user): bool
    {
        return $user->can('resources.create');
    }

    public function update(User $user, Resource $resource): bool
    {
        return $user->can('resources.edit');
    }

    public function delete(User $user, Resource $resource): bool
    {
        return $user->can('resources.delete');
    }

    public function publish(User $user, Resource $resource): bool
    {
        return $user->can('resources.publish');
    }
}
