<?php

namespace Movor\LaravelDbRedirect;

use Movor\LaravelDbRedirect\Models\Redirect;
use Movor\LaravelDbRedirect\Models\Redirect as DbRedirect;

class DbRedirectHandler
{
    /**
     * Get Laravel model for redirects table
     *
     * @return \Movor\LaravelDbRedirect\Models\Redirect
     */
    public function getModel()
    {
        return new DbRedirect;
    }

    /**
     * Add redirect.
     *
     * In case there is redirect with the same "from" address,
     * it will be overwritten.
     *
     * @param string $from
     * @param string $to
     * @param int    $status
     * @param array  $data
     *
     * @return  DbRedirect
     *
     * @throws \Exception
     */
    public function create($from, $to, $status = 301, $data = [])
    {
        try {
            return DbRedirect::create([
                'from' => $from,
                'to' => $to,
                'status' => $status,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Redirect entry could not be added to the database. Reason: ' . $e->getMessage());
        }
    }

    /**
     * Update redirect record in db while preserving number of hits.
     *
     * @param string $from   Used for finding redirect
     * @param string $to     Used for updating redirect "to"
     * @param int    $status Used for updating redirect "status"
     * @param array  $data   Used for updating redirect "data"
     *
     * @throws \Exception
     */
    public function update($from, $to = null, $status = null, $data = [])
    {
        $dbRedirect = $this->get($from);

        if (!$dbRedirect) {
            $message = 'Could not update redirect db record. No matching "from" redirect found by uri:';
            $message .= ' "' . $from . '"';
            throw new \Exception($message);
        }

        if ($to !== null) {
            $dbRedirect->to = $to;
        }

        if ($status !== null) {
            $dbRedirect->status = $status;
        }

        if ($data) {
            $dbRedirect->data = $data;
        }

        $dbRedirect->save();
    }

    /**
     * Update or create redirect record in db
     *
     * @param string $from
     * @param string $to
     * @param int    $status
     * @param array  $data
     *
     * @throws \Exception
     */
    public function updateOrCreate($from, $to, $status = 301, $data = [])
    {
        $from = $this->formatUri($from);

        try {
            $dbRedirect = $this->get($from);

            if ($dbRedirect) {
                // Update status and data only if they are not at default values

                $dbRedirect->to = $to;

                if ($status != 301) {
                    $dbRedirect->status = $status;
                }

                if ($data != []) {
                    $dbRedirect->data = $data;
                }

                $dbRedirect->save();
            } else {
                DbRedirect::create([
                    'from' => $from,
                    'to' => $to,
                    'status' => $status,
                    'data' => $data,
                ]);
            }
        } catch (\Exception $e) {
            throw new \Exception('Redirect entry could not be added to the database. Reason: ' . $e->getMessage());
        }
    }

    /**
     * Check if redirect exist
     *
     * @param string      $from
     * @param string|null $to If "to" is passed, it'll be used in search query
     *
     * @return bool
     */
    public function exists($from, $to = null)
    {
        $query = Redirect::where('from', $from);

        // Search by "to" if passed
        if ($to !== null) {
            $query->where('to', $to);
        }

        return $query->exists();
    }

    /**
     * Get redirect
     *
     * @param string      $from
     * @param string|null $to If "to" is passed, it'll be used in search query
     *
     * @return Redirect
     *
     * @throws \Exception
     */
    public function get($from, $to = null)
    {
        $from = $this->formatUri($from);
        $query = DbRedirect::where('from', $from);

        // Include "to" in the query if passed
        if ($to !== null) {
            $to = $this->formatUri($to);
            $query->where('to', $to);
        }

        $dbRedirect = $query->first();

        return $dbRedirect;
    }

    /**
     * Format uri - make sure it has only leading slash.
     * (this method should preserve uri format through the package)
     *
     * @param $uri
     *
     * @return string
     *
     * @throws \Exception
     */
    public function formatUri($uri)
    {
        // Make sure there is just leading slash in url
        $uri = '/' . ltrim(rtrim($uri, '/'), '/');

        if (!filter_var('http://foo.bar' . $uri, FILTER_VALIDATE_URL)) {
            throw new \Exception('Redirect could not be performed. Invalid URI: ' . $uri);
        }

        return $uri;
    }

    /**
     * Get redirect data based on request query param: "dbRedirectId".
     */
    public function getRedirectData()
    {
        $id = app('request')->query('dbRedirectId');
        $dbRedirect = DbRedirect::find($id);

        return $dbRedirect ? $dbRedirect->data : null;
    }
}