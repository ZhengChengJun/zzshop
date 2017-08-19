<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
  <title id="title">@yield('title')</title>
  <link rel="stylesheet" href="/css/weui.css">
  <link rel="stylesheet" href="/css/book.css">
  <link rel="stylesheet" href="/css/cikonss.css">
</head>
<body >
<!-- 头部导航栏 -->
<div class="title_bar">
  <span class="title_bar_bank" onclick="history.go(-1)"><</span>
  <span class="title_bar_content">xxx</span>
  <span class="title_bar_menu" id="goTop">...</span>
</div>
<div class="content" id="contentBox">
  @yield('content')
</div>
<!-- 遮罩层 -->
<div id="content_wrap" class="animated"></div>
<!-- 底部导航栏 -->
<ul class="BottomNav animated" id="bottomMenu">
     <li><a href="javascript:;" class="weui_btn weui_btn_default">按钮</a></li>
     <li><a href="javascript:;" class="weui_btn weui_btn_default">按钮</a></li>
     <li><a href="javascript:;" class="weui_btn weui_btn_default">按钮</a></li>
     <li><a href="javascript:;" class="weui_btn weui_btn_default">按钮</a></li>
 </ul> 
 <!-- 导航按纽 -->
<!-- 提示小点点 -->
<div class="xiaodiaidiai" id="xiaodiaidiaiId"></div>
 </body>
<script src="/js/zepto.js"></script>
<script>
//通用函数
  /**
   * 底部出现灰色提示
   * @param1 [提示文字]
   * @param2 [多少豪秒后消失]
   * dom:结构：<div class="xiaodiaidiai" id="xiaodiaidiaiId"></div>
   * 基础样式：book.css 91行
   */
  function bottomMessage(msg,speed){
      //傻逼Ie吃屎了，参数默认值都不支持
      speed=speed||2000;
      //小点点
      $('#xiaodiaidiaiId').removeClass('fadeOutLeft').addClass('animated fadeInLeft').show().html(msg);
      setTimeout(function(){
         $('#xiaodiaidiaiId').removeClass('fadeInLeft').addClass('fadeOutLeft');
      },speed);
      //小点点
  }
$(function(){
    //头部标题
    var title=$('#title').html();
    $('.title_bar_content').html(title);
    //头部下方导航实现
    var BottomFlag=true;
    $('#goTop').click(function(){
        $('#bottomMenu').removeClass('fadeOutDownBig').addClass('fadeInUpBig').show();
        BottomFlag=false;
        var bowerHeight=$(window).height();
        var navHeight=$('#bottomMenu').height();
        var warpHeight=bowerHeight-navHeight;
        $('#content_wrap').height(warpHeight).removeClass('fadeOutDownBig').addClass('fadeInUpBig').show();
        setTimeout(function(){
            $('#content_wrap').removeClass('fadeInUpBig')
        },1000);
    });
    $('#contentBox,#content_wrap').click(function(){
        if(!BottomFlag){
            $('#bottomMenu').removeClass('fadeInUpBig').addClass('fadeOutDownBig'); 
            $('#content_wrap').addClass('fadeOutDownBig');
            BottomFlag=true;
        }
    });
});
</script>
@yield('childrendetail-js')
</html>
