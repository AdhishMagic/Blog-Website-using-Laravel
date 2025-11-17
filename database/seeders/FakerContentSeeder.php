<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class FakerContentSeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 authors
        $authors = User::factory()->author()->count(10)->create();

        // Create a pool of non-author users to act as commenters
        $commenters = User::factory()->count(30)->create();

        // For each author, create 5 posts
        $authors->each(function (User $author) use ($commenters) {
            $posts = Post::factory()
                ->count(5)
                ->state([
                    'user_id' => $author->id,
                    'created_by' => $author->name,
                ])
                ->create();

            // For each post, create 10 comments: some by the author, some by other users
            $posts->each(function (Post $post) use ($author, $commenters) {
                // 3 comments by the post author
                Comment::factory()
                    ->count(3)
                    ->state([
                        'post_id' => $post->id,
                        'author_id' => $author->id,
                        'user_id' => $author->id, // author is commenter
                        'created_by' => $author->name,
                    ])
                    ->create();

                // 7 comments by other users
                Comment::factory()
                    ->count(7)
                    ->state(function () use ($post, $author, $commenters) {
                        $commenter = $commenters->random();

                        return [
                            'post_id' => $post->id,
                            'author_id' => $author->id,
                            'user_id' => $commenter->id,
                            'created_by' => $commenter->name,
                        ];
                    })
                    ->create();
            });
        });
    }
}
