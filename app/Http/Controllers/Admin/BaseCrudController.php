<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;
use Illuminate\Http\Request;

abstract class BaseCrudController extends CrudController
{
    protected $imageFields = [];
    protected $dateTimeFields = [];

    public function storeCrud(Request $request = null)
    {
        if ($request === null) {
            $request = app('request');
        }

        if ($this->imageFields) {
            $this->handleEmptyImages($request);
            $this->handleCustomCastedImages($request);
        }

        if ($this->dateTimeFields) {
            $this->handleDateTimeFields($request);
        }

        return parent::storeCrud($request);
    }

    public function updateCrud(Request $request = null)
    {
        if ($request === null) {
            $request = app('request');
        }

        if ($this->imageFields) {
            $this->handleEmptyImages($request);
            $this->handleCustomCastedImages($request);
        }

        if ($this->dateTimeFields) {
            $this->handleDateTimeFields($request);
        }

        return parent::updateCrud($request);
    }

    protected function handleEmptyImages(Request $request)
    {
        foreach ($this->imageFields as $field) {
            if (strpos($request->get($field), 'data:image') === false) {
                $request->request->remove($field);
            }
        }
    }

    protected function handleCustomCastedImages(Request $request)
    {
        if ($request === null) {
            $request = app('request');
        }

        foreach ($this->imageFields as $field) {
            $rawAttribute = $field . '_raw';
            $imageBase64 = object_get($request, $rawAttribute);

            if ($imageBase64 !== null && starts_with($imageBase64, 'data:image')) {
                $request->request->set($field, $imageBase64);
            }

            $request->request->remove($rawAttribute);
        }
    }

    protected function handleDateTimeFields(Request $request)
    {
        foreach ($this->dateTimeFields as $field) {
            try {
                $request->request->set($field, Carbon::parse(object_get($request, $field)));
            } catch (\Exception $e) {
                continue;
            }
        }
    }
}
