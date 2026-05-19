<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        if (Post::count() > 0) {
            return;
        }

        $userIds = User::pluck('id');

        foreach ($userIds as $userId) {
            Post::factory(rand(8, 15))->create(['user_id' => $userId]);
        }
    }
}
