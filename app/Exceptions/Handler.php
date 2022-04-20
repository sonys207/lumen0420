<?php

namespace App\Exceptions;

use Throwable ;
use Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Throwable $e)
    {   
	  
      if ($e instanceof HcException) {
            $ls_result = [
                'ev_error' => $e->getInnerCode(),
                'ev_message' => $e->getMessage(),
                'ev_context' => $e->getContext(),
            ];
            Log::DEBUG($e->getFile().':'.$e->getLine().':HcException:'.
                json_encode($ls_result, JSON_PARTIAL_OUTPUT_ON_ERROR));
            return;
        }
     
	    parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {   
       
        if ($e instanceof HcException) {
			//dd($e->inner_code);
            $errcode = $e->getInnerCode();
            $message = $e->getMessage();
            $debug_msg = null;
			//dd(env('APP_DEBUG', false));
            if (env('APP_DEBUG', false) || ($errcode<40000 && $errcode>=30000) || ($message=='SYSTEM_ERROR_W_MSG')) {
                //always expose detail for INVALID_PARAMETER
				
                $debug_msg = $e->getContext();
            }
            if ($errcode>=50000) {
                $st_code = 200;
            }
            else {
                $st_code = ($errcode < 20000 && $errcode >= 10000) ? 401:200;
            }
			//dd($errcode, $st_code, $debug_msg);
            return $this->failedResponse($message, $st_code, $debug_msg);
		
        }
        return parent::render($request,$e);
        return $this->failedResponse('UNCATCHED_ERROR', 500);
    }

    /**
     * Create a Symfony response for the given exception.
     *
     * @param  \Exception  $e
     * @return mixed
     */
    protected function convertExceptionToResponse(Exception $e)
    {
        if (env('APP_DEBUG')) {
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);

            return response()->make(
                $whoops->handleException($e),
                method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                method_exists($e, 'getHeaders') ? $e->getHeaders() : []
            );
        }

        return parent::convertExceptionToResponse($e);
    }
	
	
	public function failedResponse($ev_message, $httpCode, $ev_context=null, $headers=[], $json_options=0)
	{
		//dd($ev_message, $httpCode, $ev_context);
		//Standard failed response
		$stdResponse = [
			'ev_error' => 1,
			'ev_message' => $ev_message,
			'http_code'=>$httpCode
		];
		
		if (!is_null($ev_context)) {
			$stdResponse['ev_context'] = $ev_context;
		}
		return response()->json($stdResponse);
	}

}







