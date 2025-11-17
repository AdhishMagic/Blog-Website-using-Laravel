<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'status',
        'created_by',
        'updated_by',
    ];

    // Each post belongs to one author (user)
    public function author()
    {
        // The posts table uses `user_id` as the author foreign key
        return $this->belongsTo(User::class, 'user_id');
    }

    // Each post can have many comments
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    /**
     * Comments on this post that were written by the author of the post.
     */
    public function authorComments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id')
            ->whereColumn('comments.user_id', 'comments.author_id');
    }

    /**
     * Comments on this post that were written by other users.
     */
    public function otherComments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id')
            ->whereColumn('comments.user_id', '<>', 'comments.author_id');
    }
}
