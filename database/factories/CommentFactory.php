<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            // Will be overridden in seeder when linked to a specific post/author
            'post_id' => Post::factory(),
            'author_id' => User::factory()->author(),
            'user_id' => null, // optional commenter association
            'content' => fake()->sentences(2, true),
            'created_by' => fake()->name(),
        ];
    }
}
