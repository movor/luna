<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogTag;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;

class BlogTagCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(BlogTag::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/blog-tag');

        // Columns
        $this->crud
            ->addColumn([
                'label' => 'Name',
                'name' => 'name',
            ]);

        // Fields
        $this->crud
            ->addField([
                'label' => 'Name',
                'name' => 'name'
            ]);
    }

    public function store(Request $request)
    {

        $request->merge(['name' => strtolower($request->name)]);

        $this->validate($request, [
            'name' => 'required|min:2|max:32|unique:blog_tags,name'
        ]);

        return parent::storeCrud($request);
    }

    public function update(Request $request)
    {
        return parent::updateCrud();
    }
}
