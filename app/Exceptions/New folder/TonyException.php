<?php

namespace App\Exceptions;

use Exception;

class TonyException extends Exception
{
	protected $inner_code = -1;
    protected $context;
	
	 public function report()
    {   
	  Log::DEBUG($e->getFile().':'.$e->getLine().':HcException:'.
                json_encode($ls_result, JSON_PARTIAL_OUTPUT_ON_ERROR));
            return;
        
        //       
    }	
     public function render($request)
    {   
	
       // dd($this,$this->getCode(),$this->getMessage(),$this->code,$this->message);    
        return response()->json(["error" => true, "message" => $this->getMessage()]);       
    }
}
















