<?php

namespace Movor\LaravelDbRedirect\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Movor\LaravelDbRedirect\Models\Redirect;

class DbRedirectMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Remove "dbRedirectId" url param from query string to be able to find
        // redirect in database, and rebuild uri without it
        $pathInfo = $request->getPathInfo();
        $httpQuery = http_build_query($request->except('dbRedirectId'));
        $httpQuery = $httpQuery ? '?' . $httpQuery : '';
        $uri = $pathInfo . $httpQuery;

        $dbRedirect = Redirect::where('from', $uri)->first();

        // Continue with the request if no redirect defined for requested uri
        if ($dbRedirect === null) {
            return $next($request);
        }

        // Update hit
        $dbRedirect->last_hit_at = new Carbon;
        $dbRedirect->hits += 1;
        $dbRedirect->save();

        // Append "dbRedirectId" query param
        // Useful if we want to show redirect data in upcoming redirect
        // @see \Movor\LaravelDbRedirect\RedirectHandler::getRedirectData() method
        $to = $dbRedirect->to;
        $to .= parse_url($to, PHP_URL_QUERY) ? '&' : '?';
        $to .= 'dbRedirectId=' . $dbRedirect->id;

        // Perform redirect
        return redirect($to, $dbRedirect->status);
    }
}