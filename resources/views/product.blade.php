@extends('master')
@section('title','书籍列表')
@section('content')
@if (!$products)
  <div class="weui_cells_title">此分类下暂无书藉</div>
@else
<div class="weui_cells_title">{{$productNav}}</div>
<div class="weui_cells weui_cells_access">
  @foreach($products as $product)
    <a href="/product/{{$product->id}}" class="weui_cell">
      <div class="weui_cell hd"><img class="animated fadeInLeft" height="100" width="100" src="{{$product->preview}}" alt=""></div>
      <div class="weui_cell_bd weui_cell_primary">
        <div>
          <span class="bk_title" style="font-weight: bold;">{{$product->name}}</span>
          <span class="bk_price" style="float:right;color:red;">￥{{$product->price}}</span>
        </div>
        <p class="bk_summary">{{$product->summary}}</p>
      </div>
    </a>
  @endforeach
</div>
@endif
@endsection
@section('childrendetail-js')
@endsection