<?php

namespace App\Listeners;

use App\Events\ArticlePublishedEvent;
use App\Mail\ArticlePublishedEmail;
use App\Mail\InformStation;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;

class EmailDispatcher
{
    /**
     * Subscribe to multiple events
     *
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        // Email user when his article is published
        $events->listen(ArticlePublishedEvent::class, self::class . '@emailUserAboutPublishedArticle');
    }

    public function emailUserAboutPublishedArticle(ArticlePublishedEvent $articlePublishedEvent)
    {
        $article = $articlePublishedEvent->article;

        Mail::send(new ArticlePublishedEmail($article));
    }
}
