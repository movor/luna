<?php namespace App\Http\Controllers\Admin;

use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;

class BlogPostCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(BlogPost::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/blog-post');

        // Filter: user
        $this->crud->addFilter([
            'label' => 'Author',
            'name' => 'user',
            'type' => 'dropdown',
        ], User::all()->pluck('name', 'id')->toArray(), function ($value) {
            $this->crud->query->where('user_id', $value);
        });

        // Filter: published
        $this->crud->addFilter([
            'label' => 'Published',
            'name' => 'is_published',
            'type' => 'dropdown',
        ], [0 => 'unpublished', 1 => 'published'], function ($value) {
            if ($value) {
                $this->crud->query->whereNotNull('published_at');
            } else {
                $this->crud->query->whereNull('published_at');
            }
        });

        // Filter: tag
        $this->crud->addFilter([
            'label' => 'Tag',
            'name' => 'blog_tag',
            'type' => 'select2_multiple',
        ], BlogTag::all()->pluck('name', 'id')->toArray(), function ($values) {
            $values = json_decode($values);
            if ($values) {
                foreach ($values as $key => $value) {
                    $this->crud->query = $this->crud->query->whereHas('tags', function ($query) use ($value) {
                        $query->where('blog_tag_id', $value);
                    });
                }
            }
        });

        // Columns
        $this->crud
            ->addColumn([
                'label' => 'Title',
                'name' => 'title',
            ])
            ->addColumn([
                'name' => 'primaryTag',
                'label' => 'Primary Tag',
                'type' => 'model_function_attribute',
                'function_name' => 'getPrimaryTag',
                'attribute' => 'name'
            ])
            ->addColumn([
                'label' => 'Tags',
                'type' => 'select_multiple',
                'name' => 'tags',
                'entity' => 'tags',
                'attribute' => 'name',
                'model' => BlogTag::class,
            ])
            ->addColumn([
                'label' => 'User',
                'type' => 'related_link',
                'relationRoute' => 'user',
                'relation' => 'user',
                'attribute' => 'name'
            ])
            ->addColumn([
                'label' => 'Published At',
                'name' => 'published_at'
            ]);

        // Fields
        $this->crud
            ->addField([
                'label' => 'Title',
                'name' => 'title'
            ])
            ->addField([
                'name' => 'user_id',
                'label' => 'User',
                'type' => 'select',
                'entity' => 'user',
                'attribute' => 'name'
            ])
            ->addField([
                'name' => 'tags',
                'label' => 'Tags',
                'type' => 'select2_multiple',
                'entity' => 'tags',
                'attribute' => 'name',
                'model' => BlogTag::class,
                'pivot' => true,
                'wrapperAttributes' => ['class' => 'form-group col-md-8']
            ])
            ->addField([
                'name' => 'published_at',
                'label' => 'Published At',
                'type' => 'date'
            ])
            ->addField([
                'label' => 'Summary',
                'name' => 'summary',
                'type' => 'textarea'
            ])
            ->addField([
                'label' => 'Body',
                'name' => 'body',
                'type' => 'simplemde'
            ]);

        // TODO.SOLVE
        if (is_numeric(\Request::segment(3))) {
            $postId = \Request::segment(3);
            $post = BlogPost::find($postId);

            $this->crud->addField([
                'name' => 'primary_tag', // the name of the db column
                'label' => 'Primary Tag', // the input label
                'type' => 'select_from_array_with_default',
                'options' => BlogTag::all()->pluck('name', 'id')->toArray(),
                'allow_null' => false,
                'selected' => $post->getPrimaryTag()->id,
                'wrapperAttributes' => ['class' => 'form-group col-md-4'],
            ])->beforeField('tags');
        }
    }

    public function store(Request $request)
    {
        // Artificially add slug to the request object
        $request->merge(['slug' => str_slug($request->title)]);

        $this->handlePrimaryTag($request);

        $this->validate($request, [
            'title' => 'required|min:5|max:128',
            'summary' => 'required|min:15|max:256',
            'slug' => 'unique:blog_posts,slug',
        ]);

        return parent::storeCrud($request);
    }

    public function update(Request $request)
    {
        $this->handlePrimaryTag($request);

        return parent::updateCrud();
    }

    /**
     * Handle primary tag savingg.
     * Many to many relationship with additional pivot data.
     *
     * @param Request $request
     */
    private function handlePrimaryTag(Request $request)
    {
        $requestTags = $request->tags ?: [];
        $primaryTag = $request->primary_tag;

        if (!in_array($primaryTag, $requestTags)) {
            $requestTags[] = $primaryTag;
        }

        // Transform many to many to accept additional field - "primary"
        $tags = [];
        foreach ($requestTags as $tag) {
            $tags[$tag] = ['primary' => $tag == $primaryTag];
        }

        $request->request->set('tags', $tags);
    }
}

