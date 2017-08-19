<?php
namespace App\Http\Controllers\Service;
use App\Http\Controllers\Controller;
//引入验证类
use App\Tool\ValidateCode\ValidateCode;
//引入容联云短信类
use App\Tool\SMS\sendTemplateSMS;
//引入数据模型temp_phone类进行增删改查
use App\Entity\TempPhone;
//引入状态输出类
use App\Models\M3Result;
//没啥屁用
use Illuminate\Http\Request;
/**
 * coder:zhengchengjun
 * time:2017-8-14
 * function:验证功能类
 */
class ValidateCodeController extends Controller
{
	//短信有效期单位是分钟
	private $deadline=5;
	//短信验证码
	private $code;
	//短信模板,上线再说
	private $templent=1;
	//验证码方法
    public function create(Request $request){
    	$validate=new ValidateCode();
        $request->session()->put('validateCode',$validate->getCode());
    	return $validate->doimg();
    }
    //手机短信方法
    public function sendSMS(Request $request){
        //客户端请求Ip
        $RequestIp=$request->getClientIp();
        $result=new M3Result;
        if($RequestIp!='127.0.0.1'){
            $result->status='110';
            $result->message='请求地址非法';
            return json_encode($result);
        }
    	$phone=$request->get('phone','');
        if(!$phone||$phone==' '){
            $result->status=1;  
            $result->message='手机号不能为空';
            return json_encode($result);
        }
        if(strlen($phone)!=11||$phone[0]!='1'){
    		$result->status=1;	
    		$result->message='手机格式不正确';
    		return json_encode($result);
    	}
    	$sendTemplateSMS=new sendTemplateSMS; 
    	$this->code=mt_rand(100000,999999);   	
    	//发送短信，得到发送信息
    	$info=$sendTemplateSMS->sendTemplateSMS($phone,array($this->code,$this->deadline),$this->templent);
    	//进表tempPhone操作
        //把用户以前的验证码清除
        if($tempPhone=TempPhone::where('phone',$phone)){
            TempPhone::where('phone',$phone)->delete();
        }
    	$tempPhone=new TempPhone();   
    	$tempPhone->phone=$phone;
    	$tempPhone->code=$this->code;
    	$tempPhone->deadline=time()+60*$this->deadline;
    	$tempPhone->save();
    	$result->status=0;
    	$result->message='发送成功';
    	return json_encode($result);
    }
}
