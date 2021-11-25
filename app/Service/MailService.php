<?php

namespace App\Service;

use Illuminate\Http\Request;
use App\Jobs\QueueJob;
use App\Jobs\SignupJob;
use App\Jobs\ForgetPasswordJob;


class MailService
{

    /// If User Login Then This Function Call Response Send On User Email
    public static function login_mail($email)
    {
        $details = [
            'title' => 'Log in Confirmation Mail',
            'Message' => 'Your Are Log in At ' . now()
        ];
    
        dispatch(new QueueJob($details));
    
    }
    //Service For sending Mail for User
    public static function mail($email)
    {
        $details = [
            'title' => 'Hello Malik',
            'link' => 'http://127.0.0.1:8000/api/verification' . '/' . $email,
            'link1' => 'http://127.0.0.1:8000/api/regenrate' . '/' . $email
        ];
        dispatch(new SignupJob($details));
    }
    //Mail Service For Forget Password
    public static function forgetmail($email, $otp)
    {
        $details = [
            'title' => 'Hello Dear User',
            'Message' => 'This is  Your Otp:' . $otp,
        ];
      
        $mailQueue  = dispatch(new ForgetPasswordJob($details));
    
    }
}
