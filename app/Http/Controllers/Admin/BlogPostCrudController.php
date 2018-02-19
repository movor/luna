<?php namespace App\Http\Controllers\Admin;

use App\Models\BlogPost;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Request;

class BlogPostCrudController extends CrudController
{

    public function setup()
    {
        $this->crud->setModel(BlogPost::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/blog-post');

        // Columns
        $this->crud
            ->addColumn([
                'label' => 'Title',
                'name' => 'title',
            ])
            ->addColumn([
                'label' => 'Summary',
                'name' => 'summary',
            ])
            ->addColumn([
                'label' => 'User',
                'type' => 'related_link',
                'relationRoute' => 'user',
                'relation' => 'user',
                'attribute' => 'name'
            ]);

        // Fields
        $this->crud
            ->addField([
                'label' => 'Title',
                'name' => 'title'
            ])
            ->addField([
                'label' => 'Summary',
                'name' => 'summary'
            ])
            ->addField([
                'label' => 'Body',
                'name' => 'body',
                'type' => 'simplemde'
            ])
            ->addField([
                'label' => 'User',
                'name' => 'user_id'
            ])
            ->addField([
                'name' => 'user_id',
                'label' => 'User',
                'type' => 'select',
                'entity' => 'user',
                'attribute' => 'name'
            ]);
    }

    public function store(Request $request)
    {
        return parent::storeCrud();
    }

    public function update(Request $request)
    {
        return parent::updateCrud();
    }
}