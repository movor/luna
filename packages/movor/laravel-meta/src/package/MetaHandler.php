<?php

namespace Movor\LaravelMeta;

use Movor\LaravelMeta\Models\Meta;

class MetaHandler
{
    /**
     * Set meta at given key
     *
     * @param string $key
     * @param mixed  $value
     * @param string $realm
     * @param string $metableType
     * @param string $metableId
     *
     * @throws \Exception
     */
    public function set($key, $value, $realm = null, $metableType = '', $metableId = '')
    {
        Meta::updateOrCreate([
            'realm' => !is_null($realm) ? $realm : config('laravel-meta.default_realm'),
            'metable_type' => $metableType,
            'metable_id' => $metableId,
            'key' => $key,
        ], ['key' => $key, 'value' => $value]);
    }

    /**
     * Get meta at given key
     *
     * @param string $key
     * @param mixed  $default
     * @param string $realm
     * @param string $metableType
     * @param string $metableId
     *
     * @throws \Exception
     *
     * @return array
     */
    public function get($key, $default = null, $realm = null, $metableType = '', $metableId = '')
    {
        $meta = Meta::filter($realm, $metableType, $metableId)
            ->where('key', $key)
            ->first();

        return optional($meta)->value ?: $default;
    }

    /**
     * Check if meta key record exists by given key
     *
     * @param string $key
     * @param string $realm
     * @param string $metableType
     * @param string $metableId
     *
     * @return bool
     */
    public function exits($key, $realm = null, $metableType = '', $metableId = '')
    {
        return Meta::filter($realm, $metableType, $metableId)
            ->where('key', $key)
            ->exists();
    }

    /**
     * Count all meta for specified realm, type and id
     *
     * @param string $realm
     * @param string $metableType
     * @param string $metableId
     *
     * @return bool
     */
    public function count($realm = null, $metableType = '', $metableId = '')
    {
        return Meta::filter($realm, $metableType, $metableId)->count();
    }


    /**
     * Get all meta
     *
     * @param string $realm
     * @param string $metableType
     * @param string $metableId
     *
     * @throws \Exception
     *
     * @return array
     */
    public function all($realm = null, $metableType = '', $metableId = '')
    {
        return Meta::filter($realm, $metableType, $metableId)
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Get all meta keys
     *
     * @param string $realm
     * @param string $metableType
     * @param string $metableId
     *
     * @throws \Exception
     *
     * @return array
     */
    public function keys($realm = null, $metableType = '', $metableId = '')
    {
        return Meta::filter($realm, $metableType, $metableId)
            ->pluck('key')
            ->toArray();
    }

    /**
     * Remove meta at given key
     *
     * @param string $key
     * @param string $realm
     * @param string $metableType
     * @param string $metableId
     *
     * @throws \Exception
     */
    public function remove($key, $realm = null, $metableType = '', $metableId = '')
    {
        Meta::filter($realm, $metableType, $metableId)
            ->where('key', $key)
            ->delete();
    }

    /**
     * Purge meta
     *
     * @param string $realm
     * @param string $metableType
     * @param string $metableId
     *
     * @return int Number of records deleted
     *
     * @throws \Exception
     */
    public function purge($realm = null, $metableType = '', $metableId = '')
    {
        return Meta::filter($realm, $metableType, $metableId)->delete();
    }

    /**
     * Check if key exists
     *
     * @param string $key
     * @param string $realm
     * @param string $metableType
     * @param string $metableId
     *
     * @return bool
     */
    public function has($key, $realm = null, $metableType = '', $metableId = '')
    {
        return Meta::filter($realm, $metableType, $metableId)
            ->where('key', $key)
            ->exists();
    }
}