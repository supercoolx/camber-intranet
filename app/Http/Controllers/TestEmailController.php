<?php

namespace App\Http\Controllers;

use App\Mail\TestEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestEmailController extends Controller
{
    public function send()
    {
        $comment = 'Hi, This test feedback.';
        $toEmail = "phpflow@gmail.com";
        Mail::to('artdecomplus@gmail.com')->send(new TestEmail($comment));

   return 'Email has been sent to '. $toEmail;
}
}
