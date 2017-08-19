<?php 
	namespace App\Http\Controllers\Service;
	use App\Http\Controllers\Controller;
	//引入状态输出类
	use App\Models\M3Result;
	use App\Models\M3Email;
	use Illuminate\Http\Request;
	use App\Entity\Member;
	use App\Entity\TempPhone;
	use App\Entity\TempEmail;
	use App\Tool\uuid;
	use Mail;
	/**
	* coder:zhengchengjun
	* time:2017-8-16
	* function:用户控制器类
	*/
	class MemberController extends Controller{
		//注册
		public function register(Request $request){
		    $email = $request->input('email', ' ');
		    $phone = $request->input('phone', ' ');
		    $password = $request->input('pd', ' ');
		    $confirm = $request->input('cd', ' ');
		    $phone_code = $request->input('pCode', ' ');
		    $validate_code = $request->input('validateCode', ' ');
		    $m3_result = new M3Result; 
		    //后台检验开始
		    //不能为空格或空
		    if(($email == ' ' && $phone == ' ')||!($phone || $email)) {
		      $m3_result->status = 1;
		      $m3_result->message = '手机号or邮箱为空';
		      return $m3_result->toJson();
		    }
		     //不能小于6位
		    if($password == '' || strlen($password) < 6) {
		      $m3_result->status = 2;
		      $m3_result->message = '密码不少于6位';
		      return $m3_result->toJson();
		    }
		    if($confirm == ' ' || strlen($confirm) < 6) {
		      $m3_result->status = 3;
		      $m3_result->message = '确认密码不小于6位';
		      return $m3_result->toJson();
		    }
		    //两次密码是否相等
		    if($password != $confirm) {
		      $m3_result->status = 4;
		      $m3_result->message = '两次密码不相同';
		      return $m3_result->toJson();
		    }
			//后台检验结束
			
		    // 手机号注册
		    if($phone!=' ') {
		    	//再检验一下长度
		      if($phone_code == '' || strlen($phone_code) != 6) {
		        $m3_result->status = 5;
		        $m3_result->message = $phone;
		        return $m3_result->toJson();
		      }
	          //判段譔手机是否己注册过
	          if(Member::where('phone',$phone)->pluck('id')){
    		    $m3_result->status = 111;
	            $m3_result->message = '手机己注册';
	            return $m3_result->toJson();
	          }
		      //返回的数据是一个对象，输出这个对象自动转为json
		      //where有三个参数，一个是要查询的字段，第二个是条件，不写的话这个
		      //参数的值为第三个参数的值，条件是等于
		      //frist获得一行数据
		      $tempPhone = TempPhone::where('phone', $phone)->first();
		      //判段验证码是否相等
		      if($tempPhone->code == $phone_code) {
		      	//验证码是否在有效期内
		        if(time() > $tempPhone->deadline) {
		          $m3_result->status = 7;
		          $m3_result->message = '手机验证码不正确';
		          return $m3_result->toJson();
		        }
		        //插入数据可以表的insert方法Tempphone::insert
		        //也可以实例一个对象用属性的方式插入
		        $member = new Member;
		        $member->phone = $phone;
		        $member->password = md5('bk'.$password);
		        $member->active = 1;
		        $member->save();
		        $m3_result->status = 0;
		        $m3_result->message = '注册成功';
		        return $m3_result->toJson();
		        //验证码不正确
		      } else {
		        $m3_result->status = 7;
		        $m3_result->message = '手机验证码不正确';
		        return $m3_result->toJson();
		      }

		    // 邮箱注册
		    } else {
		      if($validate_code == ' ' || strlen($validate_code) != 4) {
		        $m3_result->status = 6;
		        $m3_result->message = '验证码为4位';
		        return $m3_result->toJson();
		      }
		      //验证码存在session里
		     $validate_code_session = $request->session()->get('validateCode', ' ');
		      if($validate_code_session != strtolower($validate_code)) {
		        $m3_result->status = 8;
		        $m3_result->message = '验证码不正确';
		        return $m3_result->toJson();
		      }
		      //判段此邮箱是否己注册过
	          if(Member::where('email',$email)->pluck('id')){
    		    $m3_result->status = 112;
	            $m3_result->message = '邮箱己注册';
	            return $m3_result->toJson();
	          }
		      //添加用户
		      $member = new Member;
		      $member->email = $email;
		      $member->password = md5('bk'.$password);
		      $member->save();
		      //邮箱验证
		      $uuid = UUID::create();
		      $m3_email = new M3Email;
		      $m3_email->to = $email;
		      $m3_email->subject = '请点击验证链接';
		      $m3_email->content = '请于24小时点击该链接完成验证.http://www.learnlaravel.com/service/validateEmail'.'?member_id='.$member->id. '&code=' . $uuid;
		      //保存到email验证表
		      //把这个邮箱之前的验证效果清空，所以把这个邮箱的验证表数据清空
		      if
		 ($tempEmailInfo=TempEmail::where('email',$email)->pluck('id')){  
		      	TempEmail::where('email',$email)->delete();
		      }
		      //插入邮箱验证数据
		      //larveal全都是面向对象
		      //生成一个数据实例，插入表中
		      $tempEmail = new TempEmail;
		      $tempEmail->member_id = $member->id;
		      $tempEmail->email=$email;
		      $tempEmail->code = $uuid;
		      $tempEmail->deadline = time() + 24*60*60;
		      $tempEmail->save();
  			  //发送邮件
		      Mail::send('email_register', ['m3_email' => $m3_email], function ($m) use ($m3_email) {
		          $m->to($m3_email->to, '尊敬的用户')
		            ->subject($m3_email->subject);
		      });
		      //输出状态信息
		      $m3_result->status = 0;
		      $m3_result->message = '请查看邮件';
		      return $m3_result->toJson();
		    }
		}
		//验证邮箱链接
		public function validateEmail(Request $request){
			$member_id=$request->input('member_id',' ');
			$code=$request->input('code',' ');
			if(Member::where('id',$member_id)->pluck('active')){
				return '此账户己激活';
			}
			$tempEmailInfo=TempEmail::where('member_id',$member_id)->first();
			if(!$tempEmailInfo){
				return "请点击最新的验证链接";
			}
			//判段code是否合法
			if($code==$tempEmailInfo->code){
				//判段是否过期
				if(time()>$tempEmailInfo->deadline){
					return '链接过期，过期时间为一天';
				}
				//find接收一个主键返回一条数据对象进行修改
				$member=Member::find($member_id);
				//修改数据对象
				$member->active=1;
				//保存数据对象相当于修改数据
				$member->save();
				return redirect('/login');
			}
		}





		//登录
		public function login(Request $request){
			$username=$request->input('username',' ');
			$password=$request->input('password',' ');
			$verifyCode=strtolower($request->input('verifyCode',' '));
			$result=new M3Result();
			//验证码判段
			$validateCode=$request->session()->get('validateCode',' ');
			if($validateCode!=$verifyCode){
				$result->status=9;
				$result->message='验证码不正确';
				return json_encode($result);
			}
			//用户信息验证
			//账号存在@符号就代表是邮箱登录
			if(strpos($username,'@')){
				$memberInfo=Member::where('email',$username)->first();
			}else{
				//手机登录
				$memberInfo=Member::where('phone',$username)->first();
			}
			if($memberInfo==null){
				$result->status=10;
				$result->message="用户不存在";
				return json_encode($result);
			}
			if(md5('bk'.$password)!=$memberInfo->password){
				$result->status=11;
				$result->message="密码错误";
				return json_encode($result);
			}
			$result->status=0;
			$result->message='登录成功';
			$request->session()->put('member'.$memberInfo);
			return json_encode($result);
		}
	}
 ?>