<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;

use Illuminate\Http\RedirectResponse;

use Mail;

class ContactController extends Controller
{
    public function create(){
    	return view('contact');
}

    public function store(ContactFormRequest $request) {

    	$contactEmail = $this->createContactEmail($request);

        // Firing an email is one of the slowest things you can do
        // So queuing is standard, and stops the user from having to wait
        Mail::queue('emails.contact', $contactEmail, function ($message)
        {
            $message->from('contact@corynorris.me')
                    ->to('corynorris@gmail.com')
                    ->subject('Personal Portfolio Contact');
        });

    	 return redirect('contact')->with('message', 'Thanks for contacting me!');
    }

    public function createContactEmail(ContactFormRequest $request) {
    	return ['name' => $request->get('name'),
            'email' => $request->get('email'),
            'messageData' => $request->get('message')
        ];
    }
}