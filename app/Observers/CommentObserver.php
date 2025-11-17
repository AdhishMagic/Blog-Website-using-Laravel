<?php

namespace App\Observers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentObserver
{
    public function creating(Comment $comment): void
    {
        $email = Auth::user()?->email ?? 'system';
        $comment->created_by = $email;
        $comment->updated_by = $email;
    }

    public function updating(Comment $comment): void
    {
        $comment->updated_by = Auth::user()?->email ?? 'system';
    }
}
