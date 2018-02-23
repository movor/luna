<?php

namespace App\Models\Traits;

trait PageUrl
{
    public function getPageUrl()
    {
        $this->checkPageBaseUrl();

        if (isset($this->slug)) {
            return url($this->baseUrl . '/' . $this->slug);
        }

        return $this->getPageCanonicalUrl();
    }

    public function getCanonicalUrl()
    {
        $this->checkPageBaseUrl();

        return url(kebab_case(class_basename($this)) . '/' . $this->id);
    }

    /**
     * @throws \Exception
     */
    protected function checkPageBaseUrl()
    {
        if (!isset($this->baseUrl)) {
            throw new \Exception('"baseUrl" property must be defined in class that uses PageUrl trait');
        }
    }
}
