<?php namespace App\Http\Controllers\Admin;

use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Request;

class UserCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(User::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/user');

        // Columns
        $this->crud
            ->addColumn([
                'label' => 'Name',
                'name' => 'name',
            ])
            ->addColumn([
                'label' => 'Email',
                'name' => 'email',
            ]);

        // Fields
        $this->crud
            ->addField([
                'label' => 'Name',
                'name' => 'name',
            ])
            ->addField([
                'label' => 'Email',
                'name' => 'email',
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