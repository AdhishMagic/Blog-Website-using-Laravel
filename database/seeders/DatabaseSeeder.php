<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure an admin exists
        User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_author' => false,
            ]
        );

        // Seed a baseline test user only if it doesn't already exist to avoid unique constraint errors on reruns
        User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                // Use a deterministic password for local login; change in production.
                'password' => bcrypt('password'),
                'role' => 'user',
            ]
        );

        // Seed authors, posts, and comments with valid relationships
        $this->call([
            SampleContentSeeder::class,
        ]);
    }
}
