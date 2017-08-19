<?php 
	namespace App\Http\Controllers\View;
	use App\Http\Controllers\Controller;
	/**
	* coder:zhengchengjun
	* time:2017-8-13
	* function:用户视图返回类
	*/
	class MemberController extends Controller{
		public function toLogin($value=""){
			return view('login');
		}
		public function toRegister($value=""){
			return view('register');
		}
	}
 ?>