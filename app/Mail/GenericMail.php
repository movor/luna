<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericMail extends Mailable
{
    use Queueable, SerializesModels;

    //
    // If we want to have variables in emails, we need to
    // defined them as public object properties
    //

    public $title;
    public $body;
    public $table;
    public $panel;
    public $button;

    /**
     * Create a new message instance.
     * Body and title are mandatory
     *
     * @param string $body
     *
     * @return void
     */
    public function __construct($title = null, $body = null)
    {
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * @param mixed $table
     */
    public function setTable($table)
    {
        //
        // Table example
        // -------------
        //
        // $table = PHP_EOL;
        // $table .= '| Laravel       | Table         | Example  |' . PHP_EOL;
        // $table .= '| ------------- |:-------------:| --------:|' . PHP_EOL;
        // $table .= '| Col 2 is      | Centered      | $10      |' . PHP_EOL;
        // $table .= '| Col 3 is      | Right-Aligned | $20      |' . PHP_EOL;

        $this->table = $table;
    }

    /**
     * @param mixed $panel
     */
    public function setPanel($panel)
    {
        $this->panel = $panel;
    }

    /**
     * @param mixed $button
     */
    public function setButton($text, $url)
    {
        $this->button['text'] = $text;
        $this->button['url'] = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.generic');
    }
}
