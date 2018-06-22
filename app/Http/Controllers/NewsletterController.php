<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            Newsletter::create($request->all());
        } catch (\Exception $e) {
        }

        return redirect()
            ->back()
            ->withMessages('You have successfully subscribed');
    }
}