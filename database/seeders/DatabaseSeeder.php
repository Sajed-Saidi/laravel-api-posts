<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create(); // Create 10 users
        // \App\Models\Category::factory(5)->create(); // Create 5 categories
        // \App\Models\Post::factory(30)->create(); // Create 20 posts
        \App\Models\Comment::factory(50)->create(); // Create 50 comments
        // \App\Models\PostImage::factory(30)->create(); // Create 30 post images
        // \App\Models\PostCategory::factory(40)->create(); // Create 40 post-category relationships
        \App\Models\Like::factory(100)->create(); // Create 100 likes
    }
}
