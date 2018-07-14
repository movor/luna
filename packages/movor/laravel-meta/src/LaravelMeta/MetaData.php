<?php

namespace Movor\LaravelMeta;

class MetaData
{
    /**
     * Set meta
     *
     * @param array  $data
     * @param string $type
     * @param string $metableType
     * @param int    $metableId
     *
     * @throws \Exception
     */
    public function set($data, $type, $metableType = '', $metableId = 0)
    {
        MetaModel::updateOrCreate([
            'type' => $type,
            'metable_type' => $metableType,
            'metable_id' => $metableId,
        ], ['data' => $data]);
    }

    /**
     * Get meta
     *
     * @param string $type
     * @param string $metableType
     * @param int    $metableId
     *
     * @throws \Exception
     *
     * @return array
     */
    public function get($type, $metableType = '', $metableId = 0)
    {
        $meta = MetaModel::where([
            'type' => $type,
            'metable_type' => $metableType,
            'metable_id' => $metableId,
        ])->first();

        if (!$meta) {
            $this->throwFormattedException($type, $metableType, $metableId);
        }

        return $meta->data;
    }

    /**
     * Delete entire meta data and set it to empty array
     *
     * @param        $type
     * @param string $metableType
     * @param int    $metableId
     *
     * @throws \Exception
     */
    public function delete($type, $metableType = '', $metableId = 0)
    {
        $meta = MetaModel::where([
            'type' => $type,
            'metable_type' => $metableType,
            'metable_id' => $metableId,
        ])->first();

        if (!$meta) {
            $this->throwFormattedException($type, $metableType, $metableId, 'Cannot delete meta. ');
        }

        $meta->data = [];
        $meta->save();
    }

    /**
     * Remove meta record from database
     *
     * @param        $type
     * @param string $metableType
     * @param int    $metableId
     *
     * @throws \Exception
     */
    public function remove($type, $metableType = '', $metableId = 0)
    {
        $meta = MetaModel::where([
            'type' => $type,
            'metable_type' => $metableType,
            'metable_id' => $metableId,
        ])->first();

        if (!$meta) {
            $this->throwFormattedException($type, $metableType, $metableId, 'Cannot remove meta. ');
        }

        $meta->delete();
    }

    /**
     * Add key-value in meta data.
     * If "unique meta key" already exists, data will be merged.
     *
     * "unique meta key" consists of: "type", "metable_type", "metable_id".
     *
     * @param array  $data
     * @param string $type
     * @param string $metableType
     * @param int    $metableId
     *
     * @throws \Exception
     */
    public function add($data = [], $type, $metableType = '', $metableId = 0)
    {
        $meta = MetaModel::where(['type' => $type])->first();

        if ($meta) {
            $metaData = $meta->data;

            foreach ($data as $key => $value) {
                $metaData[$key] = $value;
            }

            $meta->data = $metaData;
            $meta->save();
        } else {
            $this->set($data, $type, $metableType, $metableId);
        }
    }

    /**
     * Get value for given key in meta
     *
     * @param string $key
     * @param string $type
     * @param string $metableType
     * @param int    $metableId
     *
     * @throws  \Exception
     *
     * @return bool
     */
    public function getKey($key, $type, $metableType = '', $metableId = 0)
    {
        $meta = MetaModel::where([
            'type' => $type,
            'metable_type' => $metableType,
            'metable_id' => $metableId,
        ])->first();

        if (!$meta) {
            $this->throwFormattedException($type, $metableType, $metableId, 'Cannot get key. ');
        }

        $data = object_get($meta, 'data');

        return array_get($data, $key);
    }

    /**
     * Check if key exists
     *
     * @param string  $key
     * @param string  $type
     * @param string  $metableType
     * @param integer $metableId
     *
     * @throws  \Exception
     *
     * @return bool
     */
    public function hasKey($key, $type, $metableType = '', $metableId = 0)
    {
        $meta = MetaModel::where([
            'type' => $type,
            'metable_type' => $metableType,
            'metable_id' => $metableId,
        ])->first();

        if (!$meta) {
            $this->throwFormattedException($type, $metableType, $metableId, 'Cannot check key. ');
        }

        $data = object_get($meta, 'data', []);

        return isset($data[$key]);
    }

    /**
     * Remove data associated with the given key.
     * Given key can be string or array of keys.
     *
     * @param string|array $key
     * @param string       $type
     * @param string       $metableType
     * @param integer      $metableId
     *
     * @throws \Exception
     */
    public function removeKey($key, $type, $metableType = '', $metableId = 0)
    {
        $meta = MetaModel::where([
            'type' => $type,
            'metable_type' => $metableType,
            'metable_id' => $metableId,
        ])->first();

        if (!$meta) {
            $this->throwFormattedException($type, $metableType, $metableId, 'Cannot remove key. ');
        }

        $metaData = $meta->data;

        // Remove data by given key/keys from meta data variable
        if (is_array($key)) {
            foreach ($key as $k) {
                if (isset($metaData[$k])) {
                    unset($metaData[$k]);
                }
            }
        } elseif (isset($metaData[$key])) {
            unset($metaData[$key]);
        }

        // Put back data and save
        $meta->data = $metaData;
        $meta->save();
    }

    /**
     * @param string $type
     * @param string $metableType
     * @param int    $metableId
     * @param string $prependMsg
     *
     * @throws \Exception
     */
    protected function throwFormattedException($type, $metableType, $metableId, $prependMsg = '')
    {
        $uniqueMetaKey = $type . '-' . $metableType . '-' . $metableId;

        $message = $prependMsg . 'No meta found: ' . $uniqueMetaKey;
        throw new \Exception($message);
    }
}