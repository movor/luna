<?php

namespace App\Events;

use App\Models\Article;
use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ArticlePublishedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Article
     */
    public $article;

    /**
     * Create a new event instance.
     *
     * @param Article $article
     *
     * @return void
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }
}
