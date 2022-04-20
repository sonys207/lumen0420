<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\HcException;
use App\Exceptions\CustomException;
use App\Exceptions\TonyException;
use Illuminate\Support\Facades\Validator;
class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

  


    public function __construct()
    {   
       // $this->consts['REQUEST_PARAS']='';
       // $this->consts['REQUEST_PARAS']['cOrder0317']='';
       $this->consts['REQUEST_PARAS']['cOrder0317'] = [
            'user_id' =>[ 'required'=>true,'0322'=>false,'default_value'=>567,'checker'=>['is_int', [0,'inf']],],
            'user_id1' =>[ 'default_value'=>567],
            'order_number' =>[ 'required'=>true],
            'time' =>['required'=>true],
           
        ];

        $this->consts['REQUEST_PARAS']['cOrder0324'] = [
            'user_id0324' =>[ 'required'=>true,'0322'=>false,'default_value'=>567,'checker'=>['is_array', [0,'inf']],],
            'user_id123' =>[ 'default_value'=>567],
            'order_number0324' =>[ 'required'=>true],
            'time0324' =>['required'=>true],
           
        ];
        $this->consts['IGNORED_REQ_PARAS'] = [
            'guofu1',
            'guofu2'
        ];
      
        // $this->middleware('locale');
    }
  

    public function cOrder0317(Request $Request)
    {  

      $la_paras = $this->parse_parameters($Request,__FUNCTION__); 
      //1.validation
       $validator = Validator::make($Request->all(), [
            'user_id' => 'required|exists:users,id,admin_id,1',
            'order_number' => 'required|unique:orders,order_number|max:12',
           // 'time'=>'nullable|integer'
        ]);
        //如果validation有错误:$validator->fails()值为1。 没错误则调用$validator->fails()时，没有值返回
      //2.判断是否有错误
        if ($validator->fails()) {
            //3.存入log
            Log::DEBUG("Validation Error".$validator->errors()); 
            //4.返回给用户
            return "request000001:".$validator->errors();
        }

      
      

   /*    $this->validate($Request, [
         //判断传入的user_id是否在数据库users表的id字段中存在且该行admin_id字段=1，满足条件pass
        'user_id' => 'required|exists:users,id,admin_id,1',
        //判断传入的order_number是否在数据库orders表的order_number字段中存在，如存在则报错（订单号应该是唯一的）
        'order_number' => 'required|unique:orders,order_number|max:12',
        //https://laravel.com/docs/8.x/validation#a-note-on-optional-fields
        //{"order_number":"00000003","user_id":"3","time":null}
        //"null"表示字符串的值为null，null表示字段为null！！！ nullable是判断字段是否为null
        //"3"表示字符3,3表示数字3！
        'time'=>'nullable|integer'
        ]);  */   

    }


    //类的方法
    public function example($arg) {
        dd($this->message);
     
    }

    //类的属性
    public $message = 'world';


    public function test(Request $Request)
    {   

        $message1=123;
        $example = function ($arg) use($message1) {
            //调用类中定义的属性$this->message
            dd($arg . ' ' . $this->message. ' ' .$message1);
        };

         function example1($arg,$message1) {
            dd($message1);
         
        } 
        //1.use（） 继承了$message1的数据，可以在方法中直接使用
        $example("hello");
        //2.调用当前方法中的方法
        example1("hello",$message1);
        //3.调用类中定义的方法
        $this->example("hello");
    
    
      //  $const_name="TOKEN_KICKED";
      // dd(constant('self::'.$const_name));
      
        //$orders = app()->make('cs_order_service')->get_order_report(15);
        throw new TonyException('Your error message310',10698); 
       // throw new CustomException('Your error message',10086);     
       throw new HcException('SYSTEM_ERROR_W_MSG','store_id not maintained.');
     //方法3
      /*    Log::build([
            'driver' => 'errorlog',
            'path' => storage_path('logs/custom123.log'),
          ])->info('Something happened!'); */
    
     //方法1
      // Log::info('This is some useful information.');
      //Log::warning('Something could be going wrong.');
      //Log::emergency('Something is really going wrong.');

    //方法2
    Log::channel('info1')->info('Something happened12345!');
    Log::channel('info1')->emergency('Something happened12346!');
    Log::channel('err1')->info('Something happened12345!');
    Log::channel('err1')->emergency('Something happened12346!');


        $orders1 = app()->make('tony0127')->get_order_report(15);
        dd($orders1);
        

        $la_paras = $this->tonytest(6, __FUNCTION__);   
        $la_paras1 = $this->tonytest1(16, __FUNCTION__);      
         dd($la_paras,$la_paras1);
        //---Get access token Start---
        $postData = array (
            'client_id' => '238843d9-cecd-4a6e-82c7-84f93a7d96fe',
            'client_secret' => '3MC7Q~CLkWv4SyqYrXn6H6s4rkZ6JJwfqJZBn',
            'grant_type' => 'client_credentials',
            //方案1
           // 'Scope' => 'https://vault.azure.net/.default'
            //方案2
            'resource' => 'https://vault.azure.net'
        );
        $ch = curl_init();
        //e13d0b7a-a128-47ce-81a8-9e7d3daf0e94为app registration中的 (tenant) ID
        //方案1
        //curl_setopt($ch, CURLOPT_URL,"https://login.microsoftonline.com/e13d0b7a-a128-47ce-81a8-9e7d3daf0e94/oauth2/v2.0/token");
        //方案2
        curl_setopt($ch, CURLOPT_URL,"https://login.microsoftonline.com/e13d0b7a-a128-47ce-81a8-9e7d3daf0e94/oauth2/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); 
        $json_response_data = curl_exec($ch);
        curl_close($ch);
        print_r(json_decode($json_response_data, true));
      //  print_r(json_decode($json_response_data, true)['access_token']);
        //dd(json_decode($json_response_data, true)['access_token']);
        $access_token=json_decode($json_response_data, true)['access_token'];
        print_r($access_token);
        //---Get access token End---



        //---Get data Encyption Start
        $cURL = curl_init();
        $str = 'I like to live in Markham!!! Six Chicken';
        //encode待加密数据
        $strenconde = base64_encode($str);
        //header格式参考 https://stackoverflow.com/questions/8115683/php-curl-custom-headers
       $header=array(
           'Content-Type:application/json',
            'Authorization:bearer '.$access_token   
        );
      /*  // header1这种键值对格式，curl不接受！！！
        $header1=array(
            'Content-Type'=>'application/json',
             'Authorization'=>'bearer '.$access_token   
         );*/
        $postdata2 = [
            'alg'=>'RSA-OAEP-256',
            'value'=>$strenconde
        ];
        //转换为json格式
        $postdatajson = json_encode($postdata2);
        curl_setopt($cURL, CURLOPT_URL, "https://keyvalut0222.vault.azure.net/keys/RSAKEY202222/8ab185785de54ca1bba657482d908ad8/encrypt?api-version=7.2");
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_HTTPHEADER, $header); 
        curl_setopt($cURL, CURLOPT_POSTFIELDS, $postdatajson);
        curl_setopt($cURL, CURLOPT_POST, true);
        $json_response_data1 = curl_exec($cURL);
        curl_close($cURL);
        print_r("             result is ".json_decode($json_response_data1, true)['value']);
        // 实际业务场景：$valueEncyption保存到数据库
        $valueEncyption=json_decode($json_response_data1, true)['value'];
        //---Get data Encyption End
      


       //---Get data Decyption Start
        $cURL = curl_init();
        
        //解密需要重新申请access token
        $header=array(
            'Content-Type:application/json',
             'Authorization:bearer '.$access_token   
         );
          /*header1这种键值对格式，curl不接受！！！
        $header1=array(
            'Content-Type'=>'application/json',
             'Authorization'=>'bearer '.$access_token   
         );*/  
        //实际业务场景： 从数据库中取出$valueEncyption
         $postdata3 = [
            'alg'=>'RSA-OAEP-256',
            'value'=>$valueEncyption
        ];
        //转换为json格式
        $postdatajson = json_encode($postdata3);
        curl_setopt($cURL, CURLOPT_URL, "https://keyvalut0222.vault.azure.net/keys/RSAKEY202222/8ab185785de54ca1bba657482d908ad8/decrypt?api-version=7.2");
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_HTTPHEADER, $header); 
        curl_setopt($cURL, CURLOPT_POSTFIELDS, $postdatajson);
        curl_setopt($cURL, CURLOPT_POST, true);
        $json_response_data2 = curl_exec($cURL);
        curl_close($cURL);
        $valueDecyption=json_decode($json_response_data2, true)['value'];
        //decode解密后的数据
        dd(base64_decode($valueDecyption));
        //---Get data Decyption End
        




       // $user = $Request->user();
        $user = Auth::user();
      //  $mgt_uid = app()->make('CSAuth')->getCS($uid)->mgt_uid;
      //dd($this);
         $la_paras = $this->parse_parameters($Request, __FUNCTION__);
      
      
      
         dd($la_paras);
       $user1 = app()->make('tony0127')->auth0127();
       dd($user1);
       dd($user);
        return response()->json(['name' => $Request->input('tony'), 'state' => 'CA']);
    }

    public function save()
    {

        

        $result=DB::table('users')->insert(
            ["id" => "2",
                'name' => 'sam',
                'email' => 'sam@mail.com',
                'password' => Hash::make("sam1"),
            ]
        );
        echo $result;
    }
    

    public function userinfo(Request $Request)
    {
        
        
        $encrypted =$this->str_encryptaesgcm("mysecretText", "myPassword", "hex");
        $decrypted =$this->str_decryptaesgcm($encrypted, "myPassword", "hex");
        dd($encrypted,$decrypted);
        $ch = curl_init('https://www.howsmyssl.com/a/check'); 
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt ($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_MAX_TLSv1_1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch); 
        curl_close($ch); 
        $json = json_decode($data); 
        echo "<h1>Your TLS version is: " . $json->tls_version . "</h1>\n";

	 // dd(DB::connection()->getPdo());	
     error_log('API Error:Some message here.');
     
     //方法1
    // $file_path1 = base_path().'\tmp\trace.log';
    //方法2
    $file_path1 = base_path('tmp/trace.log');
     error_log("order_info:10000017.\r\n",3,$file_path1);
      return User::all();
    }


    function str_encryptaesgcm($plaintext, $password, $encoding = null) {
        if ($plaintext != null && $password != null) {
            $keysalt = openssl_random_pseudo_bytes(16);
            //openssl_random_pseudo_bytes — 生成一个伪随机字节串
            
            $key = hash_pbkdf2("sha512", $password, $keysalt, 20000, 32, true);
            //生成所提供密码的 PBKDF2 密钥派生
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-256-gcm"));
            $tag = "";
            $encryptedstring = openssl_encrypt($plaintext, "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $tag, "", 16);
            $result=$encoding == "hex" ? bin2hex($keysalt.$iv.$encryptedstring.$tag) : ($encoding == "base64" ? base64_encode($keysalt.$iv.$encryptedstring.$tag) : $keysalt.$iv.$encryptedstring.$tag);
            echo 123456;
            dd($keysalt,$key,$iv,$encryptedstring,$result);
            return $result;
        }
    }
    
    function str_decryptaesgcm($encryptedstring, $password, $encoding = null) {
        if ($encryptedstring != null && $password != null) {
            $encryptedstring = $encoding == "hex" ? hex2bin($encryptedstring) : ($encoding == "base64" ? base64_decode($encryptedstring) : $encryptedstring);
            $keysalt = substr($encryptedstring, 0, 16);
            $key = hash_pbkdf2("sha512", $password, $keysalt, 20000, 32, true);
            $ivlength = openssl_cipher_iv_length("aes-256-gcm");
            $iv = substr($encryptedstring, 16, $ivlength);
            $tag = substr($encryptedstring, -16);
            return openssl_decrypt(substr($encryptedstring, 16 + $ivlength, -16), "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $tag);
        }
    }

    public function create_order(Request $Request)

    {

        $la_paras = $Request->json()->all();
        dd($la_paras);
        $this->validate($Request, [
            '*.user_id' => 'required',
//distinct,数组中的每一个order_number都应该是唯一的（对数组中的order_number进行检查）。。。保证传入数据的准确性
            '*.order_number' => 'required|unique:orders,order_number|max:12|distinct'
        ]);
                  
     
            return 123; 
     // return $Request->input();
     //print_r ($Request->input());
     $order=new Order();
     $order->order_number=$Request->input('order_number');
     $order->user_id=$Request->input('user_id');
     return  $Request->user_id;
     $status = $order->save();
     echo $status;
    }
    //



    public function qiantao0318(Request $Request)
    {  
      //   return $Request->order_info['user_id'];
     
       $this->validate($Request, [
        'order_info.user_id' => 'required|exists:users,id,admin_id,1',
        'order_info.order_number' => 'required|unique:orders,order_number|max:12'
        ]);
     
       return $errors;
    }


    public function DQtao0318(Request $Request)
    {  
 
        $this->validate($Request, [
            '*.orderinfo.user_id' => 'required',
            '*.orderinfo.order_number' => 'required|unique:orders,order_number|max:12|distinct'
        ]);
    }  
    
    public function DQtao0318_1(Request $Request)
    {  
 
        $this->validate($Request, [
            '*.orderinfo.*.user_id' => 'required',
            '*.orderinfo.*.order_number' => 'required|unique:orders,order_number|max:12|distinct'
        ]);
    }    
}
