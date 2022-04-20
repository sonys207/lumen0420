<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller_Tony extends BaseController
{
    //
    public function tonytest1( $request1, $api_name){
         // dd($api_name);/* 方法名test*/
         return 2+$request1;
    }
}
