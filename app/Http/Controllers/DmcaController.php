<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DmcaController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dmca');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function store(Request $request)
    {
        /*
         * Validate form inputs
         */
        $validator = Validator::make($request->all(), [
            'inputName' => 'required|max:255',
            'inputEmail' => 'required|email',
            'inputDmca' => 'required|url',
            'inputMessage' => 'required',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('front.dmca.get')
                ->withErrors($validator)
                ->withInput();
        }

        Mail::send(['text' => 'emails.dmca'],
            array(
                'name' => $request->get('inputName'),
                'email' => $request->get('inputEmail'),
                'dmca_url' => $request->get('inputDmca'),
                'user_message' => $request->get('inputMessage'),
            ), function($message) use ($request)
            {
                $message->from('no-reply@linkguard.net', 'LinkGuard');
                $message->to('hello@kmartin.io', 'Kévin Martin');
                $message->subject('DMCA Request');
                $message->replyTo($request->get('inputEmail'), $request->get('inputName'));
            });

        return back()->with('success', 'Thanks for reporting DMCA!');
    }
}
