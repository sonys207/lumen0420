<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\GenericUser;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
     
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        // $this->app['auth']->viaRequest('api', function ($request) {
        //     if ($request->input('api_token')) {
        //         return User::where('api_token', $request->input('api_token'))->first();
        //     }
        // });
        $this->app['auth']->viaRequest('api', function ($request) {
          
            //    //   $user = app()->make('tony0127')->auth0127();
                 return new GenericUser(['id' => 3, 'name' => 'Taylor1']);
                 
            });

        $this->app['auth']->viaRequest('mgt_api', function ($request) {
         // dd(123456);
           //  $user = app()->make('tony0307')->auth0127(520);
          //  dd($user);
              return new GenericUser(['id' => 9, 'name' => 'Taylor8']);
            
        });
        
        
     
    }
}
