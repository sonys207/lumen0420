<?php
namespace App\Providers\TestService;

class TestService520
{
    public function auth0127($para1)
    {
     //  dd('doing good tomorrow');
      //  return response()->json(['name' => '0128', 'state' => 'CA']);
      //controller 可以调用provider中定义的方法。不知道provider是否可以调用control中定义的方法？
      return 'Mone'.$para1;
    }

}