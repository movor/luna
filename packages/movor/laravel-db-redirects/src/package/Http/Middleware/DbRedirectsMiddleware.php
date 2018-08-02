<?php

namespace Movor\LaravelDbRedirects\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Movor\LaravelDbRedirects\Models\RedirectRule;

class DbRedirectsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $redirectInfo = RedirectRule::match($request->getRequestUri());

        // Continue with the request stack execution if no redirect defined for current request
        if ($redirectInfo === null) {
            return $next($request);
        }

        // Update hits
        $redirectInfo->getRedirectRuleModel()->hit();

        // Perform redirect
        return redirect($redirectInfo->origin, $redirectInfo->statusCode);
    }
}