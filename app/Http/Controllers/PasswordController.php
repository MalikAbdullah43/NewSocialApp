<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Mail\PasswordMail;
use Illuminate\Support\Facades\mail;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Models\User;
use App\Service\MailService;
use App\Service\TokenService;

class PasswordController extends Controller
{
    public function forgetPassword(ForgetPasswordRequest $req)
    {
        //Token
        $user = DB::table('users')
            ->where('email', $req->email)
            ->whereNotNull('email_verified_at')   //Checking if user Email Verify or Not
            ->where('status', 1)
            ->first();
        if (!empty($user->id)) {
            $otp = rand(111111, 999999);
            $Auth_key = TokenService::encode();     //JWT Updation And Printing Message of Log in
            $user = DB::table('users')
                ->where('email', $req->email)
                ->update(['remember_token' => $Auth_key, "otp" => $otp, 'updated_at' => now()->addMinutes(30)]);
            //Token Validity Increase if All Activity Perfoam And Message Show
            MailService::forgetmail($req->email, $otp);
            return response(
                [
                    "Message" => "Otp Send On Email", "Status" => "200", "Auth_key2" => $Auth_key
                ],
                200
            );
        } else return response()->error(404);
    }

    //Reset Password
    public function passwordReset(ResetPasswordRequest $req)
    {
        $otp = $req->otp;
        $jwt = $req->bearerToken();

        $token = DB::table('users')
            ->where('remember_token', $jwt)
            ->where('otp', $otp)
            ->where('updated_at', '>=', now())
            ->first();

        if (!empty($token->id)) {
            $update = DB::table('users')->where('id', $token->id)->update(['password' => Hash::make($req->new_password), 'remember_token' => '']);
            if ($update > 0) {
                return response()->success(200);
            } else {
                return response()->error(404);
            }
        } else {
            return response()->error(404);
        }
    }
}
