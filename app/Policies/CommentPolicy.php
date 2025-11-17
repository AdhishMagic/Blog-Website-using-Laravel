<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentPolicy
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
        return true;
    }

    public function view(?User $user, Comment $comment): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        // Authors and users can create comments on any post
        return true;
    }

    public function update(User $user, Comment $comment): bool
    {
        // Not permitted by spec for authors/users
        return false;
    }

    public function delete(User $user, Comment $comment): bool
    {
        // Authors can delete comments on their own posts
        return $user->isAuthor() && (int) $comment->post()->value('user_id') === (int) $user->id;
    }

    public function restore(User $user, Comment $comment): bool
    {
        return false;
    }

    public function forceDelete(User $user, Comment $comment): bool
    {
        return false;
    }
}
