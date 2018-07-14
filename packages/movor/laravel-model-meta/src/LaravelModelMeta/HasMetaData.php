<?php

namespace Movor\LaravelModelMeta;

use Movor\LaravelMeta\MetaData;
use Movor\LaravelMeta\MetaModel;

trait HasMetaData
{
    public function meta()
    {
        return $this->morphOne(MetaModel::class, 'metable');
    }

    public function setMeta($data)
    {
        list($type, $metableType, $metableId) = $this->getMetaIdentifier();

        (new MetaData)->set($data, $type, $metableType, $metableId);
    }

    public function getMeta()
    {
        list($type, $metableType, $metableId) = $this->getMetaIdentifier();

        return (new MetaData)->get($type, $metableType, $metableId);
    }

    public function deleteMeta()
    {
        list($type, $metableType, $metableId) = $this->getMetaIdentifier();

        return (new MetaData)->delete($type, $metableType, $metableId);
    }

    public function removeMeta()
    {
        list($type, $metableType, $metableId) = $this->getMetaIdentifier();

        return (new MetaData)->remove($type, $metableType, $metableId);
    }

    public function addMeta($data)
    {
        list($type, $metableType, $metableId) = $this->getMetaIdentifier();

        return (new MetaData)->add($data, $type, $metableType, $metableId);
    }

    public function getMetaKey($key)
    {
        list($type, $metableType, $metableId) = $this->getMetaIdentifier();

        return (new MetaData)->getKey($key, $type, $metableType, $metableId);
    }

    public function hasMetaKey($key)
    {
        list($type, $metableType, $metableId) = $this->getMetaIdentifier();

        return (new MetaData)->hasKey($key, $type, $metableType, $metableId);
    }

    public function removeMetaKey($key)
    {
        list($type, $metableType, $metableId) = $this->getMetaIdentifier();

        return (new MetaData)->removeKey($key, $type, $metableType, $metableId);
    }

    public function scopeWhereMeta($query, $key, $value)
    {
        return $query->whereHas('meta', function ($q) use ($key, $value) {
            list($type, $metableType, $metableId) = $this->getMetaIdentifier();

            $q->where([
                'type' => $type,
                'metable_type' => $metableType,
            ])->where('data', 'like', '%' . sprintf('"%s":"%s"', $key, $value) . '%');
        });
    }

    protected function getMetaIdentifier()
    {
        return [
            config('laravel-model-meta.model_meta_type'),
            get_class($this),
            $this->getKey()
        ];
    }
}