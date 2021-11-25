<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\mail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Http\Requests\UpdateProfileRequest;

header('content-type: application/json');

class UserController extends Controller
{

   //For Updation User Data
   public function edit(UpdateProfileRequest $req)
   { ///
      $updation = [];
      if (!empty($req->file('image'))) {          //If Image Update request Recieve
         $result = $req->file('image')->store('apidoc');
         $updation['image'] = $result;
      }
      foreach ($req->all() as $key => $value) {          //Take Changes in Array
         if (in_array($key, ['name', 'email', 'gender'])) {
            $updation[$key] = $value;
         }
      }
      if (!empty($req->password))   //if Password change rewuest recieve
         $updation['password'] = Hash::make($req->password);

      if (!empty($updation)) {   //if any update request recieve
         $updation['updated_at'] =  now()->addMinutes(30);   //increase token time
         $jwt = $req->bearerToken();  //catch jwt token from bearer
         $user = DB::table('users')
            ->where('remember_token', $jwt)->update($updation);  //Update in database 
         if ($user > 0) {
            return response()->success(200);
         }
      } else
         return response()->error(303);
   }


   public function logOut(Request $req)
   {
      $jwt = $req->bearerToken();
      $sql = DB::table('users')->where('remember_token', $jwt)->update(['remember_token' => '']);
      if ($sql)
         return response()->success(200);
      else
         return response()->error(205);
   }
}
