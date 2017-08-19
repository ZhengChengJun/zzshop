<?php 
	namespace App\Http\Controllers;
	use App\Entity\Member;
	class MemberController extends Controller{
		public function info(){
			$test =new Member;
			echo $test;die
			$data=array('name'=>'acj','age'=>26);
			return view('member-info',$data);
		}
	}
 ?>