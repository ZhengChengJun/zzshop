@extends('master')

@section('title', '注册')

@section('content')
<div class="weui_cells_title">注册方式</div>
<div class="weui_cells weui_cells_radio">
  <label class="weui_cell weui_check_label" for="x11" id="phoneRegister">
      <div class="weui_cell_bd weui_cell_primary">
          <p>手机号注册</p>
      </div>
      <div class="weui_cell_ft">
          <input type="radio" class="weui_check" name="register_type" id="x11" checked="checked">
          <span class="weui_icon_checked"></span>
      </div>
  </label>
  <label class="weui_cell weui_check_label" for="x12" id="emailRegister">
      <div class="weui_cell_bd weui_cell_primary">
          <p>邮箱注册</p>
      </div>
      <div class="weui_cell_ft">
          <input type="radio" class="weui_check" name="register_type" id="x12">
          <span class="weui_icon_checked"></span>
      </div>
  </label>
</div>
<div class="weui_cells weui_cells_form animated" id="phoneForm">
  <div class="weui_cell">
      <div class="weui_cell_hd"><label class="weui_label">手机号</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" type="number" placeholder="仅支持大陆" name="phone"/>
      </div>
  </div>
  <div class="weui_cell">
      <div class="weui_cell_hd"><label class="weui_label">密码</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" type="password" placeholder="不少于6位" name='passwd_phone'/>
      </div>
  </div>
  <div class="weui_cell">
      <div class="weui_cell_hd"><label class="weui_label">确认密码</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" type="password" placeholder="不少于6位" name='passwd_phone_cfm'/>
      </div>
  </div>
  <div class="weui_cell">
      <div class="weui_cell_hd"><label class="weui_label">手机验证码</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" type="number" placeholder="" name='phone_code'/>
      </div>
      <p class="bk_important bk_phone_code_send">发送验证码</p>
      <div class="weui_cell_ft">
      </div>
  </div>
</div>
<div class="weui_cells weui_cells_form animated" style="display: none;" id="emailForm">
  <div class="weui_cell">
      <div class="weui_cell_hd"><label class="weui_label">邮箱</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" type="text" placeholder="" name='email'/>
      </div>
  </div>
  <div class="weui_cell">
      <div class="weui_cell_hd"><label class="weui_label">密码</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" type="password" placeholder="不少于6位" name='passwd_email'>
      </div>
  </div>
  <div class="weui_cell">
      <div class="weui_cell_hd"><label class="weui_label">确认密码</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" type="password" placeholder="不少于6位" name='passwd_email_cfm'/>
      </div>
  </div>
  <div class="weui_cell weui_vcode">
      <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" type="text" placeholder="请输入验证码" name='validate_code'/>
      </div>
      <div class="weui_cell_ft">
          <img src="/service/validateCode/create" class="bk_validate_code"/>
      </div>
  </div>
</div>
<div class="weui_cells_tips"></div>
<div class="weui_btn_area">
  <a class="weui_btn weui_btn_primary" href="javascript:" id="register">注册</a>
</div>
<a href="/login" class="bk_bottom_tips bk_important1">已有帐号? 去登录</a>
@endsection

@section('childrendetail-js')
<script>
$(function(){
  //注册方式
  $('#phoneRegister').click(function(){
    $('#phoneForm').addClass('rotateInDownLeft').show();
    $('#emailForm').hide();
  });
  $('#emailRegister').click(function(){
    $('#emailForm').addClass('rotateInDownLeft').show();
    $('#phoneForm').hide();
  });
  $('.bk_validate_code').click(function() {
    $(this).attr('src','/service/validateCode/create?resetValideImage='+Math.random())
  });
  //注册类型
  var registerType=1;
  $('#x11').click(function(){
    registerType=1;
  });
  $('#x12').click(function(){
    registerType=0;
  });


  //是否允许发送手机验证码标识
  var enable=true;
  //发送验证码
  $('.bk_phone_code_send').click(function(event) {
    //还没到时间就不允许重新发送
    if(!enable){
      return;
    }
    var phone = $('input[name=phone]').val();
    // 手机号不为空
    if(!phone&&phone == '') {
      bottomMessage('请输入手机号');
      return;
    }
    // 手机号格式
    if(phone.length != 11 || phone[0] != '1') {
      bottomMessage('手机格式不正确');
      return;
    }
    $(this).css('color', '#ccc');
    $('.bk_phone_code_send').removeClass('animated rotateInDownLeft');
    //多少秒后允许重新发送
    var allowTime=10;
    enable=false;
    //定时器60s后自动把enable变成true
    window.interval=setInterval(function(){
      if(allowTime==0){
        $('.bk_phone_code_send').css('color', 'green');
        enable=true;
        clearInterval(interval);
        $('.bk_phone_code_send').addClass('animated rotateInDownLeft').html('重新发送');
        return;
      }
      $('.bk_phone_code_send').html(--allowTime+'s重新发送');
    },1000);
    //发送短信到后台
    $.ajax({
      //////////////////////////////
      url: '/service/validatePhoneCode/create',
      dataType: 'JSON',
      type:'GET',
      data:{phone:phone},
      cache:false,
      success:function(data){
        var data=JSON.parse(data);
        if(data==null){
          bottomMessage('服务端错误');
        }
        if (data.status==0) {
          bottomMessage('发送成功');
        }
        if(data.status!=0){
          clearInterval(window.interval);
           $('.bk_phone_code_send').css('color', 'green').html('重新发送');
          enable=true;
          bottomMessage(data.message);
        }
      },
      error:function(xhr,status,error){
        console.log(xhr);
        console.log(status);
        console.log(error);
      }
    }) ;
  });



  //提交注册
  $('#register').click(function(){
      //手机
      var phone='';
      //密码
      var pd='';
      //二次密码
      var confirm='';
      //手机验证码
      var pCode='';
      //邮箱
      var email='';
      //验证码
      var vCode='';
      //json数据
      var data={};
      //手机注册数据获取
      if(registerType){
        phone = $('input[name=phone]').val();
        pd = $('input[name=passwd_phone]').val();
        confirm = $('input[name=passwd_phone_cfm]').val();
        pCode = $('input[name=phone_code]').val();
        if(verifyPhone(phone, pd, confirm, pCode) == false) {
          return;
        }else{
          data={phone:phone,pd:pd,cd:confirm,pCode:pCode,_token:"{{csrf_token()}}"};
        }
      }else{
        //邮箱注册数据获取
        email = $('input[name=email]').val();
        pd = $('input[name=passwd_email]').val();
        confirm = $('input[name=passwd_email_cfm]').val();
        validateCode = $('input[name=validate_code]').val();
        if(verifyEmail(email, pd, confirm, validateCode) == false) {
          return;
        }else{
         data={email:email,pd:pd,cd:confirm,validateCode:validateCode,_token:"{{csrf_token()}}"};         
        }
      }
    $.ajax({
      //http://192.168.9.103/laravel51/public/service/register
      url: '/service/register',
      dataType: 'JSON',
      type:'POST',
      data:data,
      cache:false,
      success:function(data){
        var data=JSON.parse(data);
        if(data==null){
          bottomMessage('服务端错误');
        }
        if (data.status==0) {
          location.href="/login";
        }
        if(data.status!=0){
          bottomMessage(data.message);
        }
      },
      error:function(xhr,status,error){
        console.log(xhr);
        console.log(status);
        console.log(error);
      }      
    });      
  });
  //手机验证函数
  function verifyPhone(phone, password, confirm, phone_code) {
    // 手机号不为空
    if(!phone&&phone == '') {
      bottomMessage('请输入手机号');
      return false;
    }
    // 手机号格式
    if(phone.length != 11 || phone[0] != '1') {
      bottomMessage('手机格式不正确');
      return false;
    }
    if(password == '' || confirm == '') {
      bottomMessage('密码不能为空');
      return false;
    }
    if(password.length < 6 || confirm.length < 6) {
      bottomMessage('密码不能少于6位');
      return false;
    }
    if(password != confirm) {
      bottomMessage('两次密码不相同!');
      return false;
    }
    if(phone_code == '') {
      bottomMessage('手机验证码不能为空!');
      return false;
    }
    if(phone_code.length != 6) {
      bottomMessage('手机验证码为6位!');
      return false;
    }
    return true;
  }
  //邮箱验证函数
  function verifyEmail(email, password, confirm, validate_code) {
    // 邮箱不为空
    if(email == '') {
      bottomMessage('请输入邮箱');
      return false;
    }
    // 邮箱格式
    if(email.indexOf('@') == -1 || email.indexOf('.') == -1) {
      bottomMessage('邮箱格式不正确');
      return false;
    }
    if(password == '' || confirm == '') {
      bottomMessage('密码不能为空');
      return false;
    }
    if(password.length < 6 || confirm.length < 6) {
      bottomMessage('密码不能少于6位');
      return false;
    }
    if(password != confirm) {
      bottomMessage('两次密码不相同!');
      return false;
    }
    if(validate_code == '') {
      bottomMessage('验证码不能为空!');
      return false;
    }
    if(validate_code.length != 4) {
      bottomMessage('验证码为4位!');
      return false;
    }
    return true;
  }
});
</script>
@endsection
