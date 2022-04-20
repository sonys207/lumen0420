<?php

namespace App\Providers\TestService;

use Illuminate\Support\ServiceProvider;

class DriverServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('tony0307',function($app){
    		return new TestService520();
        });

        $this->app->singleton('tony0127',function($app){
          return new TestService1();
          });
    }

}