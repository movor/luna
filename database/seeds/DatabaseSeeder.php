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
        //
        // User
        //

        // Generate one Movor Dev user and 3 random ones
        factory(\App\Models\User::class)->create(['name' => 'Movor Dev', 'email' => 'movor@movor.io']);
        factory(\App\Models\User::class, 3)->create();

        //
        // Blog Tag
        //

        factory(\App\Models\BlogTag::class, 10)->create();

        //
        // Blog Post
        //

        // Delete all previous blog images before seeding
        $deletePattern = \App\Models\CustomCasts\BlogPostFeaturedImageCast::storageDir();
        File::delete(File::glob(storage_path_app($deletePattern) . '/*'));
        factory(\App\Models\BlogPost::class, 20)->create();

        //
        // Pivot: Blog posts and blog tags (each post should have exactly one primary tag)
        //

        seedPivotData('blog_post_blog_tag', 'blog_posts', 'blog_tags', function ($postId) {
            static $tmpPostId;

            if ($tmpPostId != $postId) {
                $tmpPostId = $postId;

                return ['primary' => true];
            }

            return ['primary' => false];
        });
    }
}
