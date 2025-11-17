<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh', ['--seed' => true]);
    }

    public function test_authors_have_posts_and_posts_have_comments(): void
    {
        $authors = User::query()->where('is_author', true)->get();
        $this->assertGreaterThanOrEqual(2, $authors->count(), 'Expected at least 2 authors');

        foreach ($authors as $author) {
            $this->assertGreaterThanOrEqual(1, $author->posts()->count(), 'Author should have at least one post');
            foreach ($author->posts as $post) {
                $this->assertNotNull($post->author, 'Post should have an author relation');
                $this->assertGreaterThanOrEqual(1, $post->comments()->count(), 'Post should have at least one comment');
            }
        }

        $comment = Comment::query()->first();
        $this->assertNotNull($comment->post, 'Comment should belong to a post');
        $this->assertNotNull($comment->author, 'Comment should belong to an author (post author)');
        $this->assertNotNull($comment->user, 'Comment should belong to a user (who commented)');
    }
}
