<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(?User $user): bool
    {
        // Anyone (including guests) can view list of posts
        return true;
    }

    public function view(?User $user, Post $post): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        // Authors can create posts
        return $user->isAuthor();
    }

    public function update(User $user, Post $post): bool
    {
        // Authors can update only their own posts
        return $user->isAuthor() && (int) $post->user_id === (int) $user->id;
    }

    public function delete(User $user, Post $post): bool
    {
        // Authors can delete only their own posts
        return $user->isAuthor() && (int) $post->user_id === (int) $user->id;
    }

    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}
