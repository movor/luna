<?php

namespace Movor\LaravelMeta;

class MetaData
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
        MetaModel::updateOrCreate([
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
        $meta = MetaModel::filter($realm, $metableType, $metableId)
            ->where('key', $key)
            ->first();

        return optional($meta)->value ?: $default;
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
    public function getAll($realm = null, $metableType = '', $metableId = '')
    {
        return MetaModel::filter($realm, $metableType, $metableId)
            ->pluck('value', 'key')
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
        $meta = MetaModel::filter($realm, $metableType, $metableId)
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
     * @throws \Exception
     */
    public function purge($realm = null, $metableType = '', $metableId = '')
    {
        MetaModel::filter($realm, $metableType, $metableId)->delete();
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
    public function hasKey($key, $realm = null, $metableType = '', $metableId = '')
    {
        return MetaModel::filter($realm, $metableType, $metableId)
            ->where('key', $key)
            ->exists();
    }
}