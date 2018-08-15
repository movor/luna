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
        // Tag
        //

        factory(\App\Models\Tag::class, 10)->create();

        //
        // Article
        //

        // Delete all previous article featured images before seeding
        $deletePattern = \App\Models\CustomCasts\ArticleFeaturedImageCast::storageDir();
        File::delete(File::glob(storage_path_app($deletePattern) . '/*'));

        factory(\App\Models\Article::class, 20)->create();

        //
        // Pivot: Articles and tags (each article should have exactly one primary tag)
        //

        seedPivotData('article_tag', 'articles', 'tags', function ($articleId) {
            static $tmpArticleId;

            if ($tmpArticleId != $articleId) {
                $tmpArticleId = $articleId;

                return ['primary' => true];
            }

            return ['primary' => false];
        });

        //
        // Newsletter
        //

        factory(App\Models\Newsletter::class, 50)->create();
    }
}
