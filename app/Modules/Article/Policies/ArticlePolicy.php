<?php

namespace App\Modules\Article\Policies;

use App\Models\User;
use App\Modules\Article\Models\Article;

class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('articles.create');
    }

    public function view(User $user, Article $article): bool
    {
        return $user->can('articles.create');
    }

    public function create(User $user): bool
    {
        return $user->can('articles.create');
    }

    public function update(User $user, Article $article): bool
    {
        return $user->can('articles.edit');
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->can('articles.delete');
    }

    public function publish(User $user, Article $article): bool
    {
        return $user->can('articles.publish');
    }
}
