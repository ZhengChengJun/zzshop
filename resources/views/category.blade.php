@extends('master')
@section('title','书籍类别')
@section('content')
<div class="weui_cells_title">选择书籍列表</div>
<!-- 一级列表 -->
<div class="weui_cells weui_cells_split">
  <div class="weui_cell weui_cell_select">
    <div class="weui_cell_bd weui_cell_primary">
      <select name="category" class="weui_select">
          @foreach($categorys as $category)
            <option value="{{$category->id}}">{{$category->name}}</option>
          @endforeach
      </select>
    </div>
  </div>
</div>  
<!-- 二级列表 -->
<div class="weui_cells weui_cells_access" id="childCategory">
  
</div>
@endsection
@section('childrendetail-js')
<script>
$(function(){
  _getCategory();
  $('.weui_select').change(function(event) {
    _getCategory();
  });
  //主逻辑方法ajax得到子分类
  function _getCategory(){
    var parent_id=$('.weui_select').val();
    $.ajax({
      //给larveal的路由传值不是用?号传的是在指定路由里配置
      //这个在路由里配置就是这样写的 组/category/parent_id/{pid}
      url: '/service/category/parent_id/'+parent_id,
      //就是说要填小写是吧,不填小写返回的就是json字符串
      dataType: 'json',
      beforeSend:function(){
        $('.title_bar_content').html('加载中')
      },
      success:function(data){
        $('.title_bar_content').html($('#title').html())
       $('#childCategory').html(' ');
       if(data.status==0){
        var childCategoryHtml='';
        for(var i=0;i<data.categorys.length;i++){
          //把分类Id传来得到此分类下的产品就靠他
          var categoryContentLink='/product/category_id/'+data.categorys[i].id;
          //学到了把dom结构每行都用单引号括起来，用变量就用'+变量+'
          childCategoryHtml='<a href="'+categoryContentLink+'" class="weui_cell">'+
              '<div class="weui_cell_bd weui_cell_primary">' +
                '<p>'+data.categorys[i].name+'</p>'+
              '</div>'+
              '<div class="weui_cell_ft">说明文字</div>'+
            '</a>';
          $('#childCategory').append(childCategoryHtml);
        }
       }
      }
    })
  }
});
</script>
@endsection