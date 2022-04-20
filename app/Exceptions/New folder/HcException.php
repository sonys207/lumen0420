<?php
namespace App\Exceptions;
use  Exception;
class HcException extends Exception
{
    protected $inner_code = -1;
    protected $context;
    const AUTHORTOKEN_NOT_FOUND = 10000;
    const INVALID_TOKEN = 10001;
    const PERMISSION_DENIED = 10002;
    const TOKEN_EXPIRE = 10003;
    const TOKEN_KICKED = 10004;
    const LOGIN_FAIL = 10005;
    const TOO_MANY_ATTEMPTS = 10006;
    const NUMBER_OF_PARAMETER_INCORRECT = 30000;
    const PARAMETER_NAME_INCORRECT      = 30001;
    const PARAMETER_TYPE_INCORRECT      = 30002;
    const EXCEPTION_VALIDATION_ERROR    = 30003;
    const SYSTEM_ERROR          = 40008;
    const SYSTEM_ERROR_W_MSG    = 40009;
    const DUP_CHECKOUT          = 40010;
    const UNCATCHED_ERROR       = 60000;

    public function __construct(string $const_name, $context = null)
    {   
	  
        if (defined('self::'.$const_name)) {
            $this->inner_code = constant('self::'.$const_name);
        } else {
        }
        if (is_null($context)) {
            $this->context = $const_name;
        } else {
            $this->context = $context;
        }
    }
	
	 public function report($e)
    {   
       // dd($this,$this->getCode(),$this->getMessage(),$this->code,$this->message);    
        return;       
    }
     public function render($request)
    {   
       // dd($this,$this->getCode(),$this->getMessage(),$this->code,$this->message);    
        return response()->json(["error" => true, "message" => $this->getMessage()]);       
    }
    public function getInnerCode()
    {
        return $this->inner_code;
    }
    public function getContext()
    {
        return $this->context;
    }
	
}
