<?php

namespace App\Http\Controllers\Admin;

use App\Models\Newsletter;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;

class NewsletterCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel(Newsletter::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/newsletter');
        $this->crud->removeButton('create', 'top');
        $this->crud->removeAllButtonsFromStack('line');
        $this->crud->addButtonFromView('top', 'export', 'export_newsletter');

        // Columns
        $this->crud
            ->addColumn([
                'label' => 'Email',
                'name' => 'email',
            ]);
    }

    public function export(Request $request)
    {
        // Get an array of emails
        $customerMails = Newsletter::pluck('email')->toArray();

        if (empty($customerMails)) {
            \Alert::success('Newsletter list is empty')->flash();

            return redirect()->back();
        }

        // Merge them with comma between
        $csv = implode(',', $customerMails);

        return \Response::make($csv)->withHeaders([
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=all_emails.csv'
        ]);
    }
}