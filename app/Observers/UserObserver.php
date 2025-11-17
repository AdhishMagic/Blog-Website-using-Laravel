<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    public function creating(User $user): void
    {
        $email = Auth::user()?->email ?? 'system';
        $user->created_by = $email;
        $user->updated_by = $email;
    }

    public function updating(User $user): void
    {
        $user->updated_by = Auth::user()?->email ?? 'system';
    }
}
