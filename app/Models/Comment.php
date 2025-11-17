<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'post_id',
        'user_id',
        'author_id',
        'content',
        'created_by',
        'updated_by',
    ];

    // Each comment belongs to one post
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    // Each comment belongs to a user (who commented)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Each comment also belongs to the author of the post
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope: comments made by the post's author (user_id == author_id).
     */
    public function scopeByAuthor($query)
    {
        return $query->whereColumn('user_id', 'author_id');
    }

    /**
     * Scope: comments made by other users (user_id != author_id).
     */
    public function scopeByOthers($query)
    {
        return $query->whereColumn('user_id', '<>', 'author_id');
    }

    /**
     * Helper to check if a single comment was written by the post's author.
     */
    public function isByAuthor(): bool
    {
        return (int) $this->user_id === (int) $this->author_id;
    }
}
