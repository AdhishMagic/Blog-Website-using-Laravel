<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $statuses = ['Draft', 'Published', 'Archived'];

        return [
            'user_id' => User::factory()->author(),
            'title' => fake()->sentence(6),
            'content' => fake()->paragraphs(3, true),
            'status' => fake()->randomElement($statuses),
            'created_by' => fake()->name(),
        ];
    }
}
