<?php

namespace App\Http\Controllers;

use App\Models\BlogTag;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Mail;

class StaticPageController extends Controller
{
    public function index()
    {
        // SEO
        SEOMeta::setTitle('Home')
            ->setDescription('Welcome to ' . env('APP_NAME'))
            ->setKeywords(BlogTag::pluck('name')->toArray())
            ->setCanonical(url('/'));

        return view('index');
    }

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

        // SEO
        SEOMeta::setTitle('Contact')
            ->setDescription('Feel free to contact us any time using web form or email!')
            ->setKeywords(BlogTag::pluck('name')->toArray())
            ->setCanonical(url('contact'));

        return view('static_pages.contact');
    }

    public function about()
    {
        // SEO
        SEOMeta::setTitle('About')
            ->setDescription('Check out our awesome team!')
            ->setKeywords(BlogTag::pluck('name')->toArray())
            ->setCanonical(url('about'));

        return view('static_pages.about');
    }
}
