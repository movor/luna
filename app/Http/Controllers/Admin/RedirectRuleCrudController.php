<?php

namespace App\Http\Controllers\Admin;

use App\Models\RedirectRule;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;

class RedirectRuleCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(RedirectRule::class);
        $this->crud->orderBy('created_at', 'desc');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/redirect-rule');

        // Columns
        $this->crud
            ->addColumn([
                'label' => 'Origin',
                'name' => 'origin',
            ])
            ->addColumn([
                'label' => 'Destination',
                'name' => 'destination',
            ])
            ->addColumn([
                'label' => 'Status Code',
                'name' => 'status_code',
            ]);

        // Fields
        $this->crud
            ->addField([
                'label' => 'Origin',
                'name' => 'origin'
            ])
            ->addField([
                'label' => 'Destination',
                'name' => 'destination'
            ])
            ->addField([
                'type' => 'select_from_array',
                'label' => 'Status Code',
                'name' => 'status_code',
                'options' => array_combine(range(300, 308), range(300, 308)),
                'allows_null' => false,
                'default' => 301,
            ]);
    }

    public function store(Request $request)
    {
        return parent::storeCrud($request);
    }

    public function update(Request $request)
    {
        return parent::updateCrud();
    }
}
