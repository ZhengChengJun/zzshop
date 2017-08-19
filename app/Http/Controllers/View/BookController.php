<?php 
	namespace App\Http\Controllers\View;
	use App\Http\Controllers\Controller;
	use App\Entity\Category;
	use App\Entity\Product;
	use App\Entity\PdtContent;
	use App\Entity\PdtImages;
	use Log;
	/**
	* coder:zhengchengjun
	* time:2017-8-13
	* function:产品视图
	*/
	class BookController extends Controller{
		//分类
		public function toCategory(){
			//顶极分类
			$categorys=Category::whereNull('parent_id')->get();
			Log::info('进入书籍类别');
			return view('category')->with('categorys',$categorys);
		}
		//产品
		public function toProduct($cid){
			//指定分类下的所有产品
			$products=Product::where('category_id',$cid)->get();
			if($products->toJson()=='[]'){
				$products=0;
				$categroy=0;
			}else{
				$categroyInfo=Category::where('id',$cid)->select('name','parent_id')->first();
				$categroy=$categroyInfo->name;
				if($categroyInfo->parent_id){
					$categroy.='>'.Category::where('id',$categroyInfo->parent_id)->pluck('name');
				}
				$categroy=array_reverse(explode('>',$categroy));
				$categroy=implode(' > ',$categroy);
			}
			return view('product')->with('products',$products)->with('productNav',$categroy);
		}
		//产品详情
		public function toProductContent($pid){
			//find传Id找
			$product=Product::find($pid);
			//商品详情
			if (!$pdt=PdtContent::where('product_id',$pid)->first()) {
				$pdt=0;
			}
			//商品配图
			$pdtImages=PdtImages::where('product_id',$pid)->get();
			if ($pdtImages->toJson()=='[]') {
				$pdtImages=0;
			}
			return view('pdt_content')->with('product',$product)
								  	  ->with('pdt',$pdt)
								  	  ->with('pdtImages',$pdtImages);
		}
		// public function test(){
		// 	return view('test');
		// }
	}
 ?>