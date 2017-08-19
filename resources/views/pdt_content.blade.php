@extends('master')
@section('title','商品详情')
@section('content')
<link rel="stylesheet" href="/css/swiper.min.css">    
 <!-- Swiper -->
    <div class="swiper-container">
        <div class="swiper-wrapper">
            @if (!$pdtImages)
              <div class="swiper-slide"><img src="/images/noimage.png" alt=""></div>
            @else
              @foreach($pdtImages as $pdtImage)
                <div class="swiper-slide"><img src="{{$pdtImage->image_path}}" alt=""></div>
              @endforeach
            @endif    
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Arrows -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
 <!-- Swiper -->



<div class="weui_cells_title">
  <span class="shop_title">{{$product->name}}</span>
  <span class="bk_price" style="float:right;color:red;font-size:20px;">￥{{$product->price}}</span>
</div>
<div class="weui_cells">
  <div class="weui_cell">
    <p class="bk_summary shop_detail" style="font-style:italic;color:#324057">{{$product->summary}}</p>
  </div>
</div>


<div class="weui_cells_title" style="color:black">详细介绍</div>
<div class="weui_cells">
  <div class="weui_cell shop_detail">
    @if (!$pdt)
    <p>暂无详情</p>
    @else
    <!-- xss不转义 -->
    <p>{!!$pdt->content!!}<br></p>
    @endif
  </div>
</div>
<div class="buy">
  <span class="gocart">加入购物车</span>
  <span class="gobanlance">结算(<span class="cartNum"></span>)</span>
</div>
@endsection
@section('childrendetail-js')
<script src="/js/swiper.min.js"></script>
    <!-- Initialize Swiper -->
    <script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
    });
    </script>
<script>
  $(function(){

  })
</script>
@endsection