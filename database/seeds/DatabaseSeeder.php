<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User
        factory(\App\Models\User::class, 3)->create();

        // Blog Post
        factory(\App\Models\BlogPost::class, 10)->create();

        // Blog Tag
        factory(\App\Models\BlogTag::class, 5)->create();

        // Pivot: Blog Posts And Blog Tags
        seedPivotData('blog_post_blog_tag', 'blog_posts', 'blog_tags');
    }
}
