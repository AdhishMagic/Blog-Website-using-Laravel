<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    // Added SoftDeletes so Filament's trashed() visibility checks work.
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_author',
        'mobile',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for arrays / JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be type cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_author' => 'boolean',
    ];

    /**
     * A user (author) can write many posts.
     */
    public function posts(): HasMany
    {
        // Posts table uses user_id as the author foreign key
        return $this->hasMany(Post::class, 'user_id');
    }

    /**
     * A user can also make many comments.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    /**
     * Filament: restrict panel access to admins only.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Allow all authenticated roles into the panel; policies & navigation will restrict capabilities.
        return true;
    }

    public function isAdmin(): bool
    {
        return ($this->role ?? 'user') === 'admin';
    }

    public function isAuthor(): bool
    {
        // Backwards compatibility with legacy is_author boolean
        if ($this->is_author) {
            return true;
        }

        return ($this->role ?? 'user') === 'author';
    }
}
