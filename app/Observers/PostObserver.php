<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostObserver
{
    public function creating(Post $post): void
    {
        $email = Auth::user()?->email ?? 'system';
        $post->created_by = $email;
        $post->updated_by = $email;
    }

    public function updating(Post $post): void
    {
        $post->updated_by = Auth::user()?->email ?? 'system';
    }
}
