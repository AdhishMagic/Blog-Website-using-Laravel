<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ResetAuditSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'admin@example.com';

        Model::withoutEvents(function () use ($email) {
            Post::query()->update([
                'created_by' => $email,
                'updated_by' => $email,
            ]);

            Comment::query()->update([
                'created_by' => $email,
                'updated_by' => $email,
            ]);

            User::query()->update([
                'created_by' => $email,
                'updated_by' => $email,
            ]);
        });
    }
}
