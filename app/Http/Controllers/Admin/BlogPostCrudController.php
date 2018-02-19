<?php namespace App\Http\Controllers\Admin;

use App\Models\BlogPost;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Requests\CrudRequest;

class BlogPostCrudController extends CrudController
{

    public function setup()
    {
        $this->crud->setModel(BlogPost::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/blog-post');
        $this->crud->setEntityNameStrings('blogPost', 'blogPosts');

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
                'name' => 'user_id',
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
                'label' => 'User',
                'name' => 'user_id'
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