<?php

namespace Movor\LaravelDbRedirects;

use Movor\LaravelDbRedirects\Models\RedirectRule;

class RedirectInfo
{
    /**
     * Origin uri
     *
     * @var string
     */
    public $origin;

    /**
     * Destination url
     *
     * @var string
     */
    public $destination;

    /**
     * Redirect status code
     *
     * @var int
     */
    public $statusCode;

    /**
     * Related database model which represent redirection rule
     *
     * @var RedirectRule
     */
    protected $redirectRule;

    public function __construct($origin, $destination, RedirectRule $redirectRule)
    {
        $this->origin = $origin;
        $this->destination = $destination;
        $this->statusCode = $redirectRule->status_code;
        $this->redirectRule = $redirectRule;
    }

    /**
     * Get related redirect rule model
     *
     * @return RedirectRule
     */
    public function getRedirectRuleModel()
    {
        return $this->redirectRule;
    }
}