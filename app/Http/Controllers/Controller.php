<?php

namespace App\Http\Controllers;

//use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Controllers\Controller_Tony;
use Illuminate\Http\Request;
use App\Exceptions\HcException;
use Illuminate\Support\Arr;
use Log;
class Controller extends Controller_Tony
{

    public function tonytest( $request1, $api_name){
         // dd($api_name);/* 方法名test*/
         return 4+$request1;
    }

    public function parse_parameters(Request $request, $api_name, $callerInfoObj=null, $bSilent=false) {
     // dd($this->consts['REQUEST_PARAS'][$api_name]);调用api对应的validation_rule

        $api_paras_def =  empty($api_name) ? $this->consts['REQUEST_PARAS'] : 
            $this->consts['REQUEST_PARAS'][$api_name]; 
           
        if (empty($api_paras_def))
            throw new HcException('SYSTEM_ERROR', 'EMPTY_API_DEFINITION for '.$api_name);
         
        //获取API传入的参数$request,将其变为array！！！ 
        $la_paras = $request->json()->all();
       //将API请求存入日志
        try {
            //https://stackoverflow.com/questions/68463964/call-to-undefined-function-app-http-controllers-array-except-in-laravel
            //不将敏感信息存入log！！！
            $payload = json_encode(Arr::except($la_paras,['password','pwd','sec_code','time']));
           // dd(json_decode($payload)->order_number);
           //日志格式化！！！
            $caller_str = $this->format_caller_str($request, $callerInfoObj);
           
            if (!$bSilent) Log::DEBUG($caller_str . " called ".$api_name." payload_noPWD:". $payload);
            
        }
        //将存日志过程中出现的问题throw exception，而后存入日志
        catch (\Exception $e) {
            Log::DEBUG("Exception in logging parse_parameters:". $e->getMessage());
        }
        //进行validation
        $ret = $this->do_check_para($api_paras_def, $la_paras);
        dd('$ret is'.$ret);
        return $ret;
    }

    private function format_caller_str($request, $caller) {
        //https://www.qinziheng.com/php/4202.htm
        $ret = $request->method().'|'.($_SERVER["HTTP_X_REAL_IP"]??$request->ip()).'|'.$request->path();
       // $request->ip()  结果为 ::1(即127.0.0.1)  localhost 127.0.0.1架设的server，无法获取用户的ip。。。
        if (!empty($caller)) {
            if (!empty($caller->account_id)) {
                $ret .= '|A_ID:'.$caller->account_id.'|';
            }
            if (!empty($caller->uid)) {
                $ret .= '|U_ID:'.$caller->uid.'|';
            }
            if (!empty($caller->username)) {
                $ret .= '|USER:'.$caller->username.'|';
            }
        }
        return $ret;
    }


    public function do_check_para($api_paras_def, $la_paras) {
        
        $resolve_func_and_call = function ($func_spec, $value) {  
            $b_extra_para = is_array($func_spec) && count($func_spec)>=2;
            $func = is_array($func_spec)? $func_spec[0]: $func_spec;
            if ($b_extra_para && ($func == 'is_int' || $func == 'is_numeric')) {
                //https://www.php.net/manual/en/function.gettype.php
                //dd($func_spec,$func,$value,gettype($value),$func($value));
                $new_value = $func($value);
                //传入数据 {"order_number":"00000009","user_id":3,"time":"1e2","guofu1":"33","guofu2":"44"}
                //user_id是int，order_number是string！！！
                if (is_int($func_spec[1][0]))
                    $new_value = $new_value && $value>=$func_spec[1][0];
                if (is_int($func_spec[1][1]))
                    $new_value = $new_value && $value<=$func_spec[1][1];
                $value = $new_value;
              
            }
            elseif ($b_extra_para && ($func == 'is_string' || $func == 'is_array')) {
                $new_value = $func($value);
                if ($new_value) {
                    $len = ($func=='is_string')? strlen($value):count($value);
                   //举例 'checker'=>['is_array', [0,'inf']],$func_spec[1]为[0,'inf']
                    if (is_array($func_spec[1])) {
                        if (is_int($func_spec[1][0]))
                            $new_value = $new_value && $len>=$func_spec[1][0];
                        if (is_int($func_spec[1][1]))
                            $new_value = $new_value && $len<=$func_spec[1][1];
                    }
                    //举例 'checker'=>['is_array', 2]
                    else {
                        $new_value = $new_value && $len<=$func_spec[1];
                    }
                }
                $value = $new_value;
            }
            else
               //当 $b_extra_para 为true时，$func($value, $func_spec[1])比如is_int要传入2个参数。。。没有看到实例，或者留着接口调用未来自己写的function？？?
               //当'checker'=>['is_int',]不带限制参数时（比如要大于0，小于500 'checker'=>['is_int',[0,500]]）,即$b_extra_para为false
                $value = $b_extra_para ? $func($value, $func_spec[1]):$func($value);
                    
            return $value;
        };
        /*创建一个待处理数组，如果API请求传入的数据与该API定义的规则进行validation后，
          没有报错，则将该待处理数组返回程序进行之后的步骤！！！ */
        $ret = array();
        $para_count = 0;
        
        //一。遍历API定义的规则
        foreach ($api_paras_def as $key=>$item) {
          //  dd($api_paras_def,$key,$item);
            $rename = $item['rename'] ?? $key;
           // dd($api_paras_def,$rename,$la_paras);
           //1.如果API定义的规则字段包含在API请求字段中，执行如下程序，$para_count计数（计数点1）
            if (array_key_exists($key, $la_paras)) {
                $para_count += 1;
               // dd($para_count);
              // dd($item['required']);

              //1.1规则字段$item['checker']存在，即调用resolve_func_and_call去check API请求字段的数据类型是否一致
              //is_int,is_string,is_array
                if (isset($item['checker'])) {
                   $tony=json_encode($item['checker']);
                  // dd($tony,gettype($item['checker']));
                    if (!$resolve_func_and_call($item['checker'], $la_paras[$key]))
                        throw new HcException('PARAMETER_TYPE_INCORRECT',
                            "check failed:".$key.", checker:".json_encode($item['checker']));
                }

               /* foreach($la_paras[$key] as $dot=> $aItem) {
                    dd($la_paras[$key],$dot,$aItem);
                }*/

                //没设置child_obj,未来的接口
                
                if (isset($item['child_obj'])) {
                    $value = [];
                    foreach($la_paras[$key] as $aItem) {
                        $value[] = $this->do_check_para($item['child_obj'], $aItem);
                    }
                }
                else
                    $value = $la_paras[$key];
                //没设置converter,未来的接口  
                
                if (isset($item['converter'])) {
                    $value = $resolve_func_and_call($item['converter'], $value);
                }

                $ret[$rename] = $value;
             //  dd($key,$value,$item,!empty($item['required']));
            }
            //2.如果API定义的规则字段不包含在API请求字段中，并且该规则字段是required，报错
            elseif (!empty($item['required'])) {
            
                throw new HcException('PARAMETER_NAME_INCORRECT', " missing required:".$key);
            }
            //3.如果API定义的规则字段不包含在API请求字段中，并且该规则字段不是required时：
            //判断该规则字段是否有默认值，如果有默认值，则将默认值传入待处理数组
            elseif (array_key_exists('default_value', $item)) {
                $value = $item['default_value'];
               // dd($value);
                if (isset($item['converter'])) {
                    $value = $resolve_func_and_call($item['converter'], $value);
                }
                $ret[$rename] = $value;
            }
        }
        //二。遍历数组后（遍历API定义的规则字段），API请求的字段数量不一定等于API定义的规则字段数量！！！
        //    未定义规则的API请求字段需要在consts['IGNORED_REQ_PARAS']定义，告知程序：某些API请求字段不需要定义规则
        //    $para_count计数（计数点2）
        if (!empty($this->consts['IGNORED_REQ_PARAS'])) {
            foreach ($this->consts['IGNORED_REQ_PARAS'] as $ign_para) 
           //consts['IGNORED_REQ_PARAS']可以包含多个API的请求中未定义规则的字段
                   // 我们$para_count计数时，通过array_key_exists来统计只在本API的请求中未定义规则的字段
                $para_count += array_key_exists($ign_para, $la_paras) ? 1:0;
        }
        //三。如果API请求的字段数量大于$para_count计数，报错
        if (count($la_paras) > $para_count) {
            throw new HcException('NUMBER_OF_PARAMETER_INCORRECT');
        }
        dd($la_paras,$api_paras_def,count($la_paras),$para_count,$ret);
        return $ret;
    }

}



