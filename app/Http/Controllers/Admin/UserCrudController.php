<?php namespace App\Http\Controllers\Admin;

use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;

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
            ])
            ->addField([
                'name' => 'password',
                'label' => 'Password',
                'type' => 'password'
            ])->addField([
                'name' => 'password_confirmation',
                'label' => 'Confirm Password',
                'type' => 'password'
            ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $this->handlePassword($request);

        return parent::storeCrud();
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            // TODO.SOLVE
            // Unique email is omitted to allow user to update pass only.
            // There is a problem in case user changes email to already existing one.
            'password' => 'sometimes|string|min:6|confirmed'
        ]);

        $this->handlePassword($request);

        return parent::updateCrud();
    }

    protected function handlePassword(Request $request)
    {
        $request->merge(['password' => bcrypt($request->password)]);
    }

    protected function validateFields(Request $request, $allowEmptyPassword = false)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => $allowEmptyPassword ? '' : 'required|string|min:6|confirmed',
        ]);
    }
}