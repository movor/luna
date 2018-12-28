<?php

namespace App\Listeners;

use App\Events\ArticlePublishedEvent;
use App\Mail\ArticlePublishedMail;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;

class MailDispatcher
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

    /**
     * @param ArticlePublishedEvent $articlePublishedEvent
     */
    public function emailUserAboutPublishedArticle(ArticlePublishedEvent $articlePublishedEvent)
    {
        $article = $articlePublishedEvent->article;

        Mail::send(new ArticlePublishedMail($article));
    }
}
