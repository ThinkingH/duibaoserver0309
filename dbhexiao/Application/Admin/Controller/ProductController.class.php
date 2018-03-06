<?php
namespace Admin\Controller;
use Think\Controller;

class ProductController extends Controller{
	
	//查看数量（总数=剩余数量+订单数）
	public function productnum(){
		
		//用户是否登录判断
		$sitelist = session('adminUser');
		$siteid = $sitelist['id'];
		//$siteid = '1000';
		if(!$siteid){
			return show('0','请进行登录');
		}
		$page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
		$pageSize = 5;//每页条数
		$firstpage = ($page-1)*$pageSize;
		
		//数据的总页数
		$listcount = M('shop_product')->where('xushi_type=2 and status=1 and siteid='.$siteid)->count();
		
		//总页数
		$countpage = ceil($listcount/$pageSize);
		
		//分类查询商品的数量
		$list = M('shop_product')
				->field('id,siteid,name,kucun')
				->where('xushi_type=2 and status=1 and siteid='.$siteid)
				->group('name')
				->limit($firstpage,$pageSize)
				->select();
		$sql = M('shop_product')->getlastsql();
		
		foreach ($list as $key=>$val){
			$id = $list[$key]['id'];//商品id
			$qudaoid = $list[$key]['siteid']; //渠道id
			
			//商品订单数量
			$salenum = M('shop_userbuy')->where("productid='".$id."' and siteid='".$qudaoid."' ")->count();
			
			//商品总数量
			$totalcount = $salenum+$list[$key]['kucun'];
			
			//该商品未核销的数量
			$nohexiao = M('shop_userbuy')->where("productid='".$id."' and siteid='".$qudaoid."' and status='3' ")->count();
			
			$list[$key]['totalnum'] = (string)$totalcount;
			$list[$key]['hexiaonum'] = $nohexiao;
		}
		
		$returnarr = array(
				'totalpage' => (string)$countpage,      //总页数
				'countlist' => $listcount,//总条数
				'nowpage'   => $page,//当前页
				'list' => $list
				);
		
		
		return show('1','数据获取成功',$returnarr);
	}
	
	
}