<?php
namespace Admin\Controller;
use Think\Controller;

class HexiaoController extends Controller {
	
	
	//查询核销数据
	public function index(){
		
		//获取该商户的id
		$sitelist = session('adminUser');
		$siteid = $sitelist['id'];
		//$siteid = '1000';
		if(!$siteid){
			return show('0','请进行登录');
		}
		
		$keystr   = $_POST['keystr'];//兑换码
		//$keystr ='FFTpJz';
		if(!$keystr || !isset($keystr)){
			return show('0','兑换码不能为空');
		}
		
		//查询数据列表
		 $list = M('shop_userbuy')
				->field('id,name,keystr,status,siteid,productnum,orderno,order_createtime,productid')
				->where("keystr='".$keystr."' and siteid='".$siteid."'  and mtype=2 ")
				->select(); 
		
		
		if(empty($list)){
			$list = array();
		}
         return show('1','获取成功',$list); 
		
		
	}
	
	
	//核销操作
	//确认核销
	public function hexiao(){
	
		//用户是否登录判断
		$sitelist = session('adminUser');
		$siteid = $sitelist['id'];
		
		if(!$siteid){
			return show('0','请进行登录');
		}
		
		//获取参数
		$id     = $_POST['id'];//查询数据id
		$keystr = $_POST['keystr'];//兑换码
// 				$id = '855';
// 				$keystr = 'FFTpJz';
		if(!$id || !isset($id)){
			return show('0','id不存在,非法操作');
		}
		if(!$keystr || !isset($keystr)){
			return show('0','兑换码不能为空');
		}
	
		//输入兑换码进行判断
		$list = M('shop_userbuy')->where('id="'.$id.'"')->find();
	
		if($list['status']!='3'){
			return show('0','该商品已核销');
		}
	
		if($list['keystr']!=$keystr){
			return show('0','核销码错误，兑换失败');
		}
	
		//状态的更新，兑换时间的更新
		$data = array();
		$data['status'] = '4';
		$data['fh_fahuotime']= date('Y-m-d H:i:s');
	
		$updatelist = M('shop_userbuy')->where('id="'.$id.'"')->save($data);
	
		if($updatelist){
			return show('1','核销成功');
		}else{
			return show('0','核销失败');
		}
	}
	
	
	
	
	
	
	

	
}