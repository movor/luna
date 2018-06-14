<?php

namespace App\Http\Controllers;

use App\Models\BlogTag;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use GuzzleHttp\Client;
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
                'g-recaptcha-response' => 'required'
            ]);

            $client = new Client();

            $body = [
                'secret' => env('GOOGLE_RECAPTCHA_SECRET_KEY'),
                'response' => $request->get('g-recaptcha-response'),
                'remoteip' => $request->getClientIp()
            ];

            $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify',
                ['form_params' => $body]
            );

            $responseContent = json_decode($response->getBody()->getContents());

            // Successful message for blade rendering
            $flashMessage = [
                'variable' => 'message',
                'message' => 'Your message has been successfully sent. You can expect our response soon.'
            ];

            // If answer from google is right ...
            if ($responseContent->success === true) {
                Mail::raw($request->message, function (Message $mail) use ($request) {
                    $mail->subject(env('APP_NAME') . ' Contact Form')
                        ->from($request->email)
                        ->to(env('APP_CONTACT_EMAIL'));
                });
            } else {
                // Error message
                $flashMessage['variable'] = 'errorMessage';
                $flashMessage['message'] = 'Google thinks that you are a bot. Please try again.';
            }

            return redirect()->back()->with($flashMessage['variable'], $flashMessage['message']);
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
