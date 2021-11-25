<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use App\Service\TokenService;
use App\Service\MailService;


class LoginController extends Controller
{
    //Log In Function Call
    public function logIn(LoginRequest $req)
    {
        $Auth_key = TokenService::encode();     //Jwt Token Get From Token Service
        $user = DB::table('users')
            ->where('email', $req->email)
            ->update(['remember_token' => $Auth_key, 'updated_at' => now()->addMinutes(30)]);
        $mail = MailService::login_mail($req->email);
        return response(["Message" => ["message" => "Successfully Login", "Status" => 200,], 'Auth_key' => $Auth_key,], 200);
    }
}