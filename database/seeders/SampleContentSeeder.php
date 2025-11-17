<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleContentSeeder extends Seeder
{
    public function run(): void
    {
        // Create authors
        $authors = collect([
            ['name' => 'Alice Author', 'email' => 'alice@author.test'],
            ['name' => 'Bob Author', 'email' => 'bob@author.test'],
        ])->map(function ($data) {
            return User::query()->firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'is_author' => true,
                    'role' => 'author',
                    'mobile' => '1234567890',
                ]
            );
        });

        // Create regular users
        $commenters = collect([
            ['name' => 'Charlie Commenter', 'email' => 'charlie@site.test'],
            ['name' => 'Dana Reader', 'email' => 'dana@site.test'],
        ])->map(function ($data) {
            return User::query()->firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'is_author' => false,
                    'role' => 'user',
                    'mobile' => '1987654321',
                ]
            );
        });

        // Create posts for each author
        $statuses = ['Draft', 'Published', 'Archived'];
        /** @var \Illuminate\Support\Collection<int, \App\Models\Post> $posts */
        $posts = collect();
        foreach ($authors as $author) {
            for ($i = 1; $i <= 2; $i++) {
                $posts->push(Post::query()->create([
                    'user_id' => $author->id,
                    'title' => $author->name.' Post '.$i,
                    'content' => 'This is sample content for post '.$i.' by '.$author->name.'.',
                    'status' => $statuses[$i % count($statuses)],
                    'created_by' => $author->name,
                ]));
            }
        }

        // Add comments to each post by commenters; author is the post's author
        foreach ($posts as $post) {
            foreach ($commenters as $user) {
                Comment::query()->create([
                    'post_id' => $post->id,
                    'user_id' => $user->id,
                    'author_id' => $post->user_id, // the post's author
                    'content' => 'Nice post, '.$post->author->name.'! - from '.$user->name,
                    'created_by' => $user->name,
                ]);
            }
        }
    }
}
