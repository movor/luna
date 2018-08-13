<?php

namespace App\Http\Controllers;

use App\Models\BlogTag;
use Artesaos\SEOTools\Facades\SEOMeta;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Mail;

class StaticPageController extends Controller
{
    public function __construct()
    {
        // Custom validator for Google captcha
        \Validator::extend('recaptcha', function ($attribute, $value) {
            $request = app('request');
            $client = new Client;

            $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
                    'response' => $value,
                    'remoteip' => $request->getClientIp()
                ]
            ]);

            $response = json_decode($response->getBody()->getContents());

            return $response->success === true;
        }, 'Google thinks that you are a bot. Please try again.');
    }

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
                'g-recaptcha-response' => 'required|recaptcha',
                'email' => 'email',
                'message' => 'required',
            ]);

            Mail::raw($request->message, function (Message $mail) use ($request) {
                $mail->subject(env('APP_NAME') . ' Contact Form')
                    ->from($request->email)
                    ->to(env('APP_CONTACT_EMAIL'));
            });

            return redirect()->back()
                ->withMessages('Your message has been successfully sent. You can expect our response soon.');
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
