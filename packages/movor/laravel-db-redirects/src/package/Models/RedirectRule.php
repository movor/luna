<?php

namespace Movor\LaravelDbRedirects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Movor\LaravelDbRedirects\DbRedirectsHandler;
use Movor\LaravelDbRedirects\RedirectInfo;

class RedirectRule extends Model
{
    protected $casts = [
        'last_redirect_at' => 'datetime'
    ];

    protected $guarded = [];

    /**
     * Match given uri with with database origin and return destination url
     *
     * @param $uri
     *
     * @return RedirectInfo|null
     */
    public static function match($uri)
    {
        //
        // Try to match uri with rule without url params
        //

        $redirectRule = self::where('origin', $uri)->first();

        if ($redirectRule) {
            return new RedirectInfo($uri, $redirectRule->destinatin, $redirectRule);
        }

        //
        // Try to match uri with rule with url params
        //

        // Narrow potential matches by matching number of slashes
        $slashesCount = substr_count($uri, '/');
        $rawWhere = \DB::raw("LENGTH(origin) - LENGTH(REPLACE(origin, '/', ''))");
        $potentialRules = RedirectRule::where($rawWhere, $slashesCount)->get();

        // Try to match requested uri with one of potential rules
        foreach ($potentialRules as $potentialRule) {
            $rule = $potentialRule->origin;

            // Extract params on match.
            $params = self::getParamsIfMatched($uri, $rule);

            // Replace params in route if $params is array
            if (is_array($params)) {
                $destination = $potentialRule->destination;

                foreach ($params as $paramName => $paramValue) {
                    $destination = str_replace('{' . $paramName . '}', $paramValue, $destination);
                }

                return new RedirectInfo($uri, $destination, $potentialRule);
            }
        }

        return null;
    }

    /**
     * Try to match uri with parametrized db entry ("origin")
     *
     * @param string $uri  Uri to match with db rules (usually requested uri)
     * @param string $rule Db redirect origin rule
     *
     * @return array|bool Array of params and values if matched - false if not
     */
    protected static function getParamsIfMatched($uri, $rule)
    {
        // Rule must have params
        if (strpos($rule, '{') === false) {
            return false;
        }

        // Convert url segments to arrays for easier comparison
        $requestedUriSegments = explode('/', ltrim(rtrim($uri, '/'), '/'));
        $ruleSegments = explode('/', ltrim(rtrim($rule, '/'), '/'));

        // Rule and uri segment count must be equal
        if (count($requestedUriSegments) != count($ruleSegments)) {
            return false;
        }

        $params = [];
        for ($i = 0; $i < count($requestedUriSegments); $i++) {
            // Either segments must match or rule segment is param
            if ($requestedUriSegments[$i] == $ruleSegments[$i]) {
                continue;
            } elseif (self::isParam($ruleSegments[$i])) {
                $paramName = ltrim(rtrim($ruleSegments[$i], '}'), '{');
                $params[$paramName] = $requestedUriSegments[$i];
                continue;
            } else {
                return false;
            }
        }

        return $params;
    }

    /**
     * Check if segment is param
     *
     * @param $segment
     *
     * @return bool
     */
    protected static function isParam($segment)
    {
        return starts_with($segment, '{') && ends_with($segment, '}');
    }

    /**
     * Setter for the origin attribute
     *
     * Valid origins:
     * /foo/bar
     * /foo/{param}
     *
     * @param $value
     *
     * @throws \Exception
     */
    public function setOriginAttribute($value)
    {
        $value = trim($value);

        // Origin must always be local path
        if (strpos($value, '://') !== false) {
            throw new \Exception('Invalid uri: "' . $value . '". Origin must be local path (uri)');
        }

        $value = '/' . trim($value, '/');

        // Validate url with PHP validator
        if (!filter_var('http://foo.bar' . $value, FILTER_VALIDATE_URL)) {
            throw new \Exception('Invalid uri: "' . $value . '"');
        }

        $this->attributes['origin'] = $value;
    }

    /**
     * Setter for the destination attribute
     *
     * Valid destinations:
     * /foo/bar
     * /foo/{param}
     * http://web.site/foo/{param}/bar
     *
     * @param $value
     *
     * @throws \Exception
     */
    public function setDestinationAttribute($value)
    {
        // Remove leading and trailing whitespaces and trailing slash
        $value = rtrim(trim($value), '/');

        // Format url if we detect foreign address (e.g. foo.bar/test)
        // without protocol (http or https)
        if (preg_match('/^[A-Za-z0-9_-]+$/', strtok($value, '.'))) {
            $value = 'http://' . $value;
        }

        // Destination can be local or foreign address
        // so validate both cases

        $filterVarPrefix = '';
        if (strpos($value, '://') === false) {
            $filterVarPrefix = 'http://foo.bar';
            // Make sure there is only one slash if uri is local
            $value = '/' . ltrim($value, '/');
        }

        // TODO.improve - In case subdomain has underscore, validation will fail
        if (!filter_var($filterVarPrefix . $value, FILTER_VALIDATE_URL)) {
            throw new \Exception('Invalid uri: ' . $value);
        }

        $this->attributes['destination'] = $value;
    }

    /**
     * Setter for the "status" attribute
     *
     * @param $value
     *
     * @throws \Exception
     */
    public function setStatusCodeAttribute($value = 301)
    {
        // Make sure status code is valid HTTP redirect code
        if (!in_array($value, range(300, 308))) {
            throw new \Exception('Invalid redirect status code: ' . $value);
        }

        $this->attributes['status_code'] = $value;
    }

    /**
     * Update hits amount and last hit date
     * for the db redirect rule
     */
    public function hit()
    {
        $this->update([
            'last_hit_at' => new Carbon,
            'hits' => $this->hits + 1
        ]);
    }
}