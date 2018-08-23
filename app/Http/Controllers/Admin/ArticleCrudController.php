<?php namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArticleCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(Article::class);
        $this->crud->orderBy('created_at', 'desc');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/article');

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
            'name' => 'tag',
            'type' => 'select2_multiple',
        ], Tag::pluck('name', 'id')->toArray(), function ($values) {
            $values = json_decode($values);
            if ($values) {
                foreach ($values as $key => $value) {
                    $this->crud->query = $this->crud->query->whereHas('tags', function ($query) use ($value) {
                        $query->where('tag_id', $value);
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
                'model' => Tag::class,
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
                'method_name' => 'getUrl',
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
                'method_name' => 'getUrl',
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

        //
        // Preselect primary tag
        //

        $selected = app('request')->old('primary_tag');
        $options = Tag::ordered()->pluck('name', 'id')->toArray() + [null => '-'];

        if ($selected === null
            && is_numeric($segment = \Request::segment(3))
            && ($article = Article::find($segment))
            && Tag::exists()
            && ($primaryTag = $article->getPrimaryTag())) {
            $selected = $primaryTag->id;
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
                'label' => 'Featured Article',
                'type' => 'checkbox',
                'tab' => 'Other'
            ])
            ->addField([
                'name' => 'commentable',
                'label' => 'Enable Comments',
                'type' => 'checkbox',
                'tab' => 'Other'
            ])
            ->addField([
                'name' => 'published_at',
                'label' => 'Published At',
                'type' => 'date',
                'tab' => 'Other'
            ]);

        if (Tag::exists()) {
            $this->crud
                ->addField([
                    'name' => 'tags',
                    'label' => 'Secondary Tags',
                    'type' => 'select_secondary_tags',
                    'entity' => 'tags',
                    'attribute' => 'name',
                    'model' => Tag::class,
                    'pivot' => true,
                    'tab' => 'Other'
                ])
                ->afterField('commentable');
        }
    }

    public function store(Request $request)
    {
        // Artificially add slug to the request object
        $request->request->set('slug', str_slug($request->title));

        // Set title case
        $request->request->set('title', title_case($request->title));

        $this->validate($request, [
            'title' => 'required|min:5|max:128',
            'summary' => 'required|min:30|max:255',
            'slug' => 'required|unique:articles,slug',
            'body' => 'required'
        ]);

        $this->handleTags($request);
        $this->handleEmptyImages($request);
        $this->handleCustomCastableFeaturedImage($request);

        return parent::storeCrud($request);
    }

    public function update(Request $request)
    {
        // Artificially add slug to the request object
        $request->request->set('slug', str_slug($request->title));

        // Set title case
        $request->request->set('title', title_case($request->title));

        $this->validate($request, [
            'title' => 'required|min:5|max:128',
            'summary' => 'required|min:30|max:255',
            'slug' => [
                'required',
                Rule::unique('articles', 'slug')->ignore(\Request::segment(3))
            ],
            'body' => 'required'
        ]);

        $this->handleTags($request);
        $this->handleEmptyImages($request);
        $this->handleCustomCastableFeaturedImage($request);

        return parent::updateCrud();
    }

    /**
     * Handle tags saving.
     * Many to many relationship with additional pivot data.
     *
     * @param Request $request
     */
    protected function handleTags(Request $request)
    {
        $newSecondaryTags = $request->tags ?: [];
        $newPrimaryTagId = $request->primary_tag;

        // We need to put all tags info single array
        if ($newPrimaryTagId && !in_array($newPrimaryTagId, $newSecondaryTags)) {
            $newSecondaryTags[] = $newPrimaryTagId;
        }

        // Transform many to many to accept additional field - "primary"
        $tags = [];
        foreach ($newSecondaryTags as $tag) {
            $tags[$tag] = ['primary' => $tag == $newPrimaryTagId];
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

