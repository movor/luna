<?php namespace App\Http\Controllers\Admin;

use App\Models\BlogTag;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Requests\CrudRequest;

class BlogTagCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(BlogTag::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/blog-tag');
        $this->crud->setEntityNameStrings('blogTag', 'blogTags');

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

    public function store(CrudRequest $request)
    {
        return parent::storeCrud();
    }

    public function update(CrudRequest $request)
    {
        return parent::updateCrud();
    }
}