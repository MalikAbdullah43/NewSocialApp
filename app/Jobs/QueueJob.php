<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\LoginMail;
use App\Mail\PasswordMail;
use App\Mail\TestMail;
use Illuminate\Support\Facades\mail;


class QueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $details;
    public function __construct($details)
    {
         $this->details=$details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    //Login Mail
    public function handle()
    {   
        Mail::to('malikabdullah4300@gmail.com')->send(new LoginMail($this->details));   //Here in to We Put Mail $req->email Where We send mail
    }
      
}
