<?php

namespace App\Service;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Config;


class TokenService
{
    public static function encode()
    {
        $payload = self::payload();
        if ($payload) {
            $key = Config::get('constant.key');
            $Auth_key = JWT::encode($payload, $key, 'HS256');    //JWT Updation And Printing Message of Log in
            if ($Auth_key)
                return $Auth_key;
        } else
            return false;
    }

    public static function payload()
    {


        $payload = array(
            "iss" => "localhost",
            "iat" => time(),
            "nbf" => time() + 10,
            "aud" => "user",
        );
        if ($payload)
            return $payload;
        else
            return false;
    }


    public static function decode()
    {

        $payload = self::payload();
        $key = "Malik$43";
        JWT::$leeway = 1800; // $leeway in seconds
        $jwt = JWT::encode($payload, $key, 'HS256');
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

        return $decoded;
    }
}
?>