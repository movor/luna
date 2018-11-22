<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;
use Illuminate\Http\Request;

abstract class BaseCrudController extends CrudController
{
    protected $dateTimeFields = [];

    public function __construct()
    {
        parent::__construct();

        $this->crud->allowAccess('show');

        // Try to set model (entity) name based on crud controller name.
        // "App\Http\Controllers\Admin\{modelNameFromHere}CrudController"
        $crudFullClassName = explode('\\', static::class);
        $crudClassName = end($crudFullClassName);

        if (strpos($crudClassName, 'CrudController') > 0) {
            $model = explode('CrudController', $crudClassName)[0];
            $this->crud->setEntityNameStrings($model, str_plural($model));
        }
    }

    public function storeCrud(Request $request = null)
    {
        if (!$request) {
            $request = request();
        }

        if ($this->dateTimeFields) {
            $this->handleDateTimeFields($request);
        }

        return parent::storeCrud($request);
    }

    public function updateCrud(Request $request = null)
    {
        if (!$request) {
            $request = request();
        }

        if ($this->dateTimeFields) {
            $this->handleDateTimeFields($request);
        }

        return parent::updateCrud($request);
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

    public function isEditRequest()
    {
        return \Request::segment(4) == 'edit';
    }

    public function isCreateRequest()
    {
        return !$this->isEdit();
    }
}

