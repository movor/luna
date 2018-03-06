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
        $this->crud->orderBy('created_at', 'desc');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/blog-post');

        // Filter: user
        $this->crud->addFilter([
            'label' => 'Author',
            'name' => 'user',
            'type' => 'dropdown',
        ], User::pluck('name', 'id')->toArray(), function ($value) {
            $this->crud->query->where('user_id', $value);
        });

        // Filter: published
        $this->crud->addFilter([
            'label' => 'Published',
            'name' => 'is_published',
            'type' => 'dropdown',
        ], [0 => 'unpublished', 1 => 'published'], function ($value) {
            if ($value) {
                $this->crud->query->published();
            } else {
                $this->crud->query->published(false);
            }
        });

        // Filter: tag
        $this->crud->addFilter([
            'label' => 'Tag',
            'name' => 'blog_tag',
            'type' => 'select2_multiple',
        ], BlogTag::pluck('name', 'id')->toArray(), function ($values) {
            $values = json_decode($values);
            if ($values) {
                foreach ($values as $key => $value) {
                    $this->crud->query = $this->crud->query->whereHas('tags', function ($query) use ($value) {
                        $query->where('blog_tag_id', $value);
                    });
                }
            }
        });
        // Filter: featured
        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'featured',
            'label' => 'Featured',
        ], false, function () {
            $this->crud->addClause('featured');
        });

        $this->addColumns();
        $this->addBasicsTab()->addOtherTab();
    }

    protected function addColumns()
    {
        // Columns
        $this->crud
            ->addColumn([
                'label' => 'Title',
                'name' => 'title',
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
                'label' => 'Link',
                'type' => 'front_link',
                'method_name' => 'getPageUrl',
                'name' => 'url'
            ])
            ->addColumn([
                'label' => 'Published At',
                'name' => 'published_at'
            ]);
    }

    protected function addBasicsTab()
    {
        // Fields
        $this->crud
            ->addField([
                'label' => 'Link',
                'type' => 'front_link',
                'method_name' => 'getPageUrl',
                'tab' => 'Basics',
                'name' => 'url'
            ])
            ->addField([
                'label' => 'Title',
                'name' => 'title',
                'tab' => 'Basics'
            ])
            ->addField([
                'name' => 'user_id',
                'label' => 'User',
                'type' => 'select',
                'entity' => 'user',
                'attribute' => 'name',
                'tab' => 'Basics'
            ])
            ->addField([
                'label' => 'Summary',
                'name' => 'summary',
                'type' => 'textarea',
                'tab' => 'Basics'
            ])
            ->addField([
                'label' => 'Body',
                'name' => 'body',
                'type' => 'simplemde',
                'tab' => 'Basics'
            ]);

        // TODO.SOLVE
        if (\Request::segment(3)) {
            $segment = \Request::segment(3);
            $options = BlogTag::ordered()->pluck('name', 'id')->toArray();

            if ($options) {
                // Edit
                if (is_numeric($segment)) {
                    $postId = $segment;
                    $post = BlogPost::find($postId);
                    $selected = $post->getPrimaryTag()->id;
                } // Create
                else {
                    $selected = BlogTag::ordered()->first()->id;
                }

                $this->crud->addField([
                    'name' => 'primary_tag',
                    'label' => 'Primary Tag',
                    'type' => 'select_from_array_with_default',
                    'options' => $options,
                    'selected' => $selected,
                    'allow_null' => false,
                    'tab' => 'Basics'
                ])->afterField('title');
            }
        }

        return $this;
    }

    protected function addOtherTab()
    {
        // Fields
        $this->crud
            ->addField([
                'label' => 'Slug',
                'name' => 'name',
                'attributes' => ['disabled' => 'disabled'],
                'tab' => 'Other'
            ])
            ->addField([
                'name' => 'featured_image_raw',
                'label' => 'Featured Image',
                'type' => 'image',
                'crop' => 'true',
                'aspect_ratio' => 1.7777777778,
                'tab' => 'Other'
            ])
            ->addField([
                'name' => 'featured',
                'label' => 'Featured Post',
                'type' => 'checkbox',
                'tab' => 'Other'
            ])
            ->addField([
                'name' => 'tags',
                'label' => 'Tags',
                'type' => 'select2_multiple',
                'entity' => 'tags',
                'attribute' => 'name',
                'model' => BlogTag::class,
                'pivot' => true,
                //'wrapperAttributes' => ['class' => 'form-group col-md-8'],
                'tab' => 'Other'
            ])
            ->addField([
                'name' => 'published_at',
                'label' => 'Published At',
                'type' => 'date',
                'tab' => 'Other'
            ]);
    }

    public function store(Request $request)
    {
        // Artificially add slug to the request object
        $request->request->set('slug', str_slug($request->title));

        $this->validate($request, [
            'title' => 'required|min:5|max:128',
            'summary' => 'required|min:30|max:255',
            'slug' => 'required|unique:blog_posts,slug',
            'body' => 'required'
        ]);

        $this->handlePrimaryTag($request);
        $this->handleEmptyImages($request);
        $this->handleCustomCastableFeaturedImage($request);

        return parent::storeCrud($request);
    }

    public function update(Request $request)
    {
        // Artificially add slug to the request object
        $request->request->set('slug', str_slug($request->title));

        $this->validate($request, [
            'title' => 'required|min:5|max:128',
            'summary' => 'required|min:30|max:255',
            'slug' => 'required',
            'body' => 'required'
        ]);

        $this->handlePrimaryTag($request);
        $this->handleEmptyImages($request);
        $this->handleCustomCastableFeaturedImage($request);

        return parent::updateCrud();
    }

    /**
     * Handle primary tag savingg.
     * Many to many relationship with additional pivot data.
     *
     * @param Request $request
     */
    protected function handlePrimaryTag(Request $request)
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

    /**
     * If image is not set for upload, request attribute
     * will not contain base 64 (data:image) prefix
     * so remove value from request to avoid errors
     *
     * @param Request $request
     */
    protected function handleEmptyImages(Request $request)
    {
        $imageAttributes = [
            'featured_image',
        ];

        foreach ($imageAttributes as $attribute) {
            if (strpos($request->get($attribute), 'data:image') === false) {
                $request->request->remove($attribute);
            }
        }
    }

    protected function handleCustomCastableFeaturedImage(Request $request)
    {
        $imageBase64 = object_get($request, 'featured_image_raw');

        if ($imageBase64 && starts_with($imageBase64, 'data:image')) {
            $request->request->remove('featured_image_raw');
            $request->request->set('featured_image', $imageBase64);
        }
    }
}

