<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Mail;

class PagesController extends Controller
{
    public function contact(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'email',
                'message' => 'required',
            ]);

            Mail::raw($request->message, function (Message $mail) use ($request) {
                $mail->to($request->email);
            });

            return redirect()
                ->back()
                ->withMessage('Your message has been successfully sent. You can expect our response soon.');
        }

        return view('static_pages.contact', [
            'description' => 'Feel free to contact us any time using web form or email!'
        ]);
    }

    public function about()
    {
        return view('static_pages.about', [
            'description' => 'We are ' . env('APP_NAME') . ' . Dedicated to our dreams!'
        ]);
    }
}
