<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Mail;
use Illuminate\Support\Facades\Mail;
use App\Mail\LunasMail;


class MailController extends Controller
{
    //
    public function index(){
        $mailData = [
            'title' => 'Mail from Admin',
            'body' => 'this is for testing email using smtp',
        ];

        Mail::to('muhammadazqi098@gmail.com')->send(new LunasMail($mailData));

        dd('Email send successfully.');
    }
   
}


