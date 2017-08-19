<?php 
	namespace App\Http\Controllers\Service;
	use App\Http\Controllers\Controller;
	//引入状态输出类
	use App\Models\M3Result;
	use Illuminate\Http\Request;
	use App\Entity\category;
	/**
	* coder:zhengchengjun
	* time:2017-8-18
	* function:产品逻辑类
	*/
	class BookController extends Controller{
		/**
		 * 得到父分类下的子父类 二级分类
		 * @param  [type] $parent_id [父类id]
		 * @return [type]            [子类数据库中的相关数据]
		 */
		public function getCategoryByParentId($parent_id){
			$categorys=Category::where('parent_id',$parent_id)->get();
			$M3Result=new M3Result;
			if($categorys){
				$M3Result->status=0;
				$M3Result->message='数据查询成功';
				$M3Result->categorys=$categorys;
				return $M3Result->toJson();
			}else{
				$M3Result->status=1;
				$M3Result->message='数据查询失败';
				return $M3Result->toJson();
			}
		}
	}
 ?>