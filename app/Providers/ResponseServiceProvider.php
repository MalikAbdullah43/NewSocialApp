<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success',function($status_code){
            return response()->json([
                'success' => true,
                'message' => "Successfully Completed",
            ],$status_code);

        });

        Response::macro('error',function($status_code){
            return response()->json([
                'success' => false,
                'message' => 'May Be Something Wrong',
            ],$status_code);

        });
    }
}
