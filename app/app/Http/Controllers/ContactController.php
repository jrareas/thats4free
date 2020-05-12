<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendFormMail;

class ContactController extends Controller
{
    public function index(Request $request) {
        return view('contact',['data'=>'']);
    }

    public function send(Request $request) {
        $this->validate($request, [
            'name'     =>  'required',
            'email'  =>  'required|email',
            'message' =>  'required'
        ]);

        $data = $request->all();

        Mail::to(getenv('CONTACT_EMAIL'))->send(new SendFormMail($data));
        return back()->with('success', 'Thanks for contacting us!');
    }
}
