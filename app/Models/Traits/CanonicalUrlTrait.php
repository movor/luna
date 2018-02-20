<?php

namespace App\Models\Traits;

trait CanonicalUrlTrait
{
    protected $urlModelSegment = 0;

    public function getCanonicalUrl()
    {
        return '/' . kebab_case(class_basename($this)) . '/' . $this->id;
    }
}
