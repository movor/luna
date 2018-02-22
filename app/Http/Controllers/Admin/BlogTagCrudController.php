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
        // Tags will always be written with small letters.
        $request->merge(['slug' => str_slug($request->name)]);
        $request->merge(['name' => strtolower($request->name)]);

        $this->validate($request, [
            'name' => 'required|min:2|max:32|unique:blog_tags,name',
            'slug' => 'unique:blog_tags,slug'
        ]);

        return parent::storeCrud($request);
    }

    public function update(Request $request)
    {
        return parent::updateCrud();
    }
}
