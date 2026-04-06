<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function __invoke(ContactRequest $request)
    {
        \Log::info('CONTACT FORM SUBMITTED', $request->all());
//        Mail::to('comtemshop@gmail.com')->send(new ContactMail($request->name, $request->email, $request->body));
        Mail::to('comtemshop@gmail.com')
            ->send(
                (new ContactMail($request->name, $request->email, $request->body))
                    ->from('comtemshop@gmail.com', 'Comtem')
            );
        return redirect()->back();
    }
}
