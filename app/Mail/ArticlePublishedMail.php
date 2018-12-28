<?php

namespace App\Mail;

use App\Models\Article;

class ArticlePublishedMail extends GenericMail
{
    /**
     * Create a new message instance.
     *
     * @param Article $article
     *
     * @return void
     */
    public function __construct(Article $article)
    {
        // Our email needs to have at least title and body
        $title = 'Article published: "' . $article->title . '"';
        $body = 'Hi, your article has been published';
        parent::__construct($title, $body);

        // Set subject and user address
        $this->subject('Article Published');
        $this->to($article->user->email, $article->user->name);

        // And than mail custom things
        $this->setPanel('Article summary: "' . $article->summary . '"');
        $this->setButton('Checkout it out', $article->getUrl());
    }
}
