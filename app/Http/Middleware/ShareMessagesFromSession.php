<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\MessageBag;

class ShareMessagesFromSession
{
    /**
     * The view factory implementation.
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;

    /**
     * Create a new messages binder instance.
     *
     * @param  \Illuminate\Contracts\View\Factory $view
     *
     * @return void
     */
    public function __construct(ViewFactory $view)
    {
        $this->view = $view;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $messages = $request->session()->get('messages', []);

        if (!is_array($messages)) {
            $messages = [$messages];
        }

        $this->view->share(
            'messages', new MessageBag($messages)
        );

        return $next($request);
    }
}