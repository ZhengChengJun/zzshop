@extends('master')
@section('title','登录')
@section('content')
<div class="weui_cells_title">zz商城</div>
<div class="weui_cells weui_cells_form">
  <div class="weui_cell">
      <div class="weui_cell_hd"><label class="weui_label">帐号</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" type="tel" name="un" placeholder="邮箱或手机号"/>
      </div>
  </div>
  <div class="weui_cell">
      <div class="weui_cell_hd"><label class="weui_label">密码</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" type="password" placeholder="不少于6位" name="pd"/>
      </div>
  </div>
  <div class="weui_cell weui_vcode">
      <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" name="verify" placeholder="请输入验证码"/>
      </div>
      <div class="weui_cell_ft">
          <img src="/service/validateCode/create" class="bk_validate_code"/>
      </div>
  </div>
</div>
<div class="weui_cells_tips"></div>
<div class="weui_btn_area">
  <button class="weui_btn weui_btn_primary" id="login">登录</button>
</div>
<div class="weui_cell_ft">
      <p class="prompt">验证码不区分大小写</p>
  </div>
<a href="/register" class="bk_bottom_tips bk_important1">没有帐号? 去注册</a>
@endsection
@section('childrendetail-js')
<script>
$(function(){
  //常规处理
	$('.bk_validate_code').click(function() {
		$(this).attr('src','/service/validateCode/create?resetValideImage'+Math.random())
	});


  //点击登录
  $('#login').click(function(){
    var data={
              username:$('input[name=un]').val(),
              password:$('input[name=pd]').val(),
              verifyCode:$('input[name=verify]').val(),
              _token:"{{csrf_token()}}"
             };
    if(!data.username||data.username==' '){
      bottomMessage('请输入账号');
      return;
    }
    if(data.password.length<6){
      bottomMessage('密码格式不正确');
      return;
    }
    if(data.verifyCode.length!=4){
      bottomMessage('验证码格式不正确');
      return;
    }
    //发送登录信息至后台
    $.ajax({
      url: '/service/login',
      type: 'POST',
      //就是说要填小写是吧
      dataType: 'json',
      data: data,
      success:function(data){
       // var data=JSON.parse(data);指定为type为json就不需要转对象了，jquery帮我转了
        if(data.status!=0){
          bottomMessage(data.message);
        }
        if(data.status==0){
          location.href="/category";
        }
      }
    })
  });
});
</script>
@endsection