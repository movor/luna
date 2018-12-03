<?php namespace App\Http\Controllers\Admin;

use App\Models\BackpackUser as User;
use Illuminate\Http\Request;

class UserCrudController extends BaseCrudController
{
    public function setup()
    {
        $this->crud->setModel(User::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/user');
        $this->crud->denyAccess(['delete', 'update']);

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
        // Remove password if null to allow validation via "sometimes" rule
        if (is_null($request->password) && is_null($request->password_confirmation)) {
            $request->request->remove('password');
        }

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            // TODO.SOLVE
            // Email "unique" rule is omitted to allow user to update pass only.
            // If user sets another email that is already in the database (some other user has it)
            // table unique key will prevent update, so only the output is nasty, and database
            // stays cool
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