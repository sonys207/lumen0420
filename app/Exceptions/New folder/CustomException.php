<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
     public function render($request)
    {   
        dd($this,$this->getCode(),$this->getMessage(),$this->code,$this->message);    
        return response()->json(["error" => true, "message" => $this->getMessage()]);       
    }
}