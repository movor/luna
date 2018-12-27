<?php

use App\Models\Article;
use App\Models\CustomCasts\ArticleFeaturedImageCast;
use App\Models\Newsletter;
use App\Models\Tag;
use App\Models\User;
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
        factory(User::class)->create(['name' => 'Movor Dev', 'email' => 'movor@movor.io']);
        factory(User::class, 3)->create();

        //
        // Tag
        //

        factory(Tag::class, 10)->create();

        //
        // Article
        //

        // Delete all previous article featured images before seeding
        $deletePattern = ArticleFeaturedImageCast::storageDir();
        File::delete(File::glob(storage_path_app($deletePattern) . '/*'));

        // Disable article model events and create some articles
        factory(Article::class, 20)->withoutEvents()->create();

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

        factory(Newsletter::class, 50)->create();
    }
}
