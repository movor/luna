<?php

namespace App\Http\Controllers;

use App\Models\BlogTag;
use App\Models\Newsletter;
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
            ->setKeywords(BlogTag::pluck('name')->toArray());

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

            $message = $request->message;
            $message .= 2 * PHP_EOL;
            $message .= 'Sender email: ' . $request->email;

            try {
                Mail::raw($message, function (Message $mail) {
                    $mail->subject(env('APP_NAME') . ' Contact Form')
                        ->from(env('MAIL_FROM_ADDRESS'))
                        ->to(env('APP_CONTACT_EMAIL'));
                });
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withErrors($e->getMessage());
            }

            return redirect()->back()
                ->withMessages('Your message has been successfully sent. You can expect our response soon.');
        }

        // SEO
        SEOMeta::setTitle('Contact')
            ->setDescription('You can use webform on this page to contact us')
            ->setKeywords(BlogTag::pluck('name')->toArray());

        return view('static_pages.contact');
    }

    public function about()
    {
        // SEO
        SEOMeta::setTitle('About')
            ->setDescription('Check out our awesome team!')
            ->setKeywords(BlogTag::pluck('name')->toArray());

        return view('static_pages.about');
    }

    public function newsletter(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required|email',
            ]);

            try {
                Newsletter::create(['email' => $request->email]);
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withErrors($e->getMessage());
            }

            return redirect()
                ->back()
                ->withMessages('You have successfully subscribed');
        }

        // SEO
        SEOMeta::setTitle('Newsletter')
            ->setDescription('Sign up for newsletter')
            ->setKeywords(BlogTag::pluck('name')->toArray());

        return view('static_pages.newsletter');
    }
}
