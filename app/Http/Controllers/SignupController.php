<?php

namespace App\Http\Controllers;

use App\Http\Resources\SignupResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\mail;
use App\Mail\TestMail;
use App\Mail\LoginMail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SignupRequest;
use App\Http\Resources\signUp;
use App\Service\MailService;


class SignupController extends Controller
{
   function signUp(SignupRequest $req)
   {  
      //Here We Create Instance of User Model For Passing Values in Model
      if (!empty($req->file('image'))) {
         $results = $req->file('image')->store('apidoc');
         $email = $req->email;
         $users = new User([
            'name'     => $req->name,
            'email'    => $email,
            'password' => Hash::make($req->password),
            'gender'   => $req->gender,
            'image'    =>  $results,
         ]);
         //Here We save Data in Database if Not Any Error Ocuure
         if ($users->save()) {
            //Send Mail New Registered 
            if (MailService::mail($email)) {
               return new SignupResource($users);
            } else {
            
               return response()->error(500);
            }
         }
      } else {
         $email = $req->email;
         $users = new User([
            'name'     => $req->name,
            'email'    => $email,
            'password' => Hash::make($req->password),
            'gender'   => $req->gender,
         ]);
         //Here We save Data in Database if Not Any Error Ocuure
         if ($users->save()) {
            //Send Mail New Registered 
            MailService::mail($email);
           return response()->success(200);
         }
      }
   }
}
