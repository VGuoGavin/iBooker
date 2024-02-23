<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'title' => 'required|max:50',
            'content' => 'required',
            'name' => 'nullable|max:50',
            'email' => 'required|email'
        ]);
        $title = $request->input('title');
        $content = $request->input('content');
        $name = $request->input('name');
        $email = $request->input('email');

        Mail::send('emails.send', ['title' => $title, 'content' => $content], function ($message) use ($name, $email)
        {

            $message->from($email, $name ? $name : $email);

            $message->to('laurentiusdominick13125@gmail.com');

        });

        return response()->json(['message' => 'Message sent successfully']);
    }
}
