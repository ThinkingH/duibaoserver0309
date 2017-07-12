<?php
/*
 * 商品兑换的主要操作
 * 
 */

class HyXb522 extends HyXb{
	
	
	//数据的初始化
	function __construct($input_data){
	
	
		//数据初始化
		parent::__construct($input_data);
	
		//日志数据开始写入
		$tmp_logstr   = "\n".'BEGINXB--------------------BEGIN--------------------BEGIN'."\n".
				date('Y-m-d H:i:s').'    request_uri:    '.$_SERVER["REQUEST_URI"]."\n".
				HyItems::hy_array2string($input_data)."\n";
		parent::hy_log_str_add($tmp_logstr);
		unset($tmp_logstr);
	
		$this->productid = isset($input_data['productid'])? $input_data['productid']:'';  //商品的类型id
		$this->keyong_jifen = isset($input_data['keyong_jifen'])? $input_data['keyong_jifen']:'';  
		$this->keyong_money = isset($input_data['keyong_money'])? $input_data['keyong_money']:'';
	}
	
	
	//用户的兑换操作
	public function controller_userduihuan(){
		
		
		//判断是否审核通过，是否启用
		$productidsql  = "select * from shop_product where flag=1 and status=1 and id='".$this->productid."'";
		$productidlist = parent::__get('HyDb')->get_row($productidsql);
		
		if($productidlist['id']>0){
			
			//该商品每次下载的次数
			$userdaymax   = $productidlist['userdaymax'];
			$usermonthmax = $productidlist['usermonthmax'];
			$userallmax   = $productidlist['userallmax'];
			$daymax       = $productidlist['daymax']; //该商品的当日兑换次数
			$price        = $productidlist['price']; //商品的金额价格
			$score        = $productidlist['score'];  //商品的积分价格
			$zhifuway     = $productidlist['feettype'];//用户的支付方式
			$typeid       = $productidlist['typeid'];//判断商品的类型11001--流量  21001--实物  12001--卡密   13001--卡券
			$goodsname    = $productidlist['name'];//商品名称
			$siteid       = $productidlist['siteid'];//渠道编号
			$productid    = $productidlist['productid'];//渠道编号
			
			//支付判断
			if($zhifuway=='1'){
				//积分支付
				$xiaohaomoney = 0;
				$xiaohaojifen = $score;
				
			}else if($zhifuway=='2'){
				//金额支付
				$xiaohaomoney = $price;
				$xiaohaojifen = 0;
				
			}else if($zhifuway=='3'){
				
				$xiaohaojifen = $score;
				$xiaohaomoney = $price;
				
			}
			
			//积分满足可以进行购买--1.用户表积分的减少 2.商品兑换次数的更加 3.商品存库量的减少4.用户购买表数据的插入 5.积分表的变动记录
			 //订单编号date('YmdHis').mt_rand(1000,9999);
			 //商品类型的判断
			 $protype = substr($typeid,0,1);
			 	
			 if($protype=='11'){//该商品为流量兑换，走流量接口
			 //流量商品名称的输入规则是 移动10M流量 --运营商+兆数+流量适用范围+唯一订单号+用户userid+有效期+商品名称+商品描述
			 $orderno = date('YmdHis').mt_rand(1000,9999);//商品订单号
			 //获取流量标识 移动100M流量
			 $gateway = $productidlist['gateway'];//运营商
			 $mbps    = $productidlist['mbps'];    //流量大小
			 $ttype   = $productidlist['ttype'];  //流量使用范围
			 
			 //判断该订单号是否存在购买表中
			 $buyproductsql  = "select id,keystr,orderno,order_createtime,name,price,score,typeid,productid from shop_userbuy where orderno='".$orderno."'";
			 $buyproductlist = parent::__get('HyDb')->get_all($buyproductsql);
			 
			 if($buyproductlist[0]['id']>0){//该订单号存在，直接读取秘钥输出
			 	$echoarr = array();
			 	$echoarr['returncode'] = 'success';
			 	$echoarr['returnmsg']  = '订单提交成功！';
			 	$echoarr['dataarr'] = $buyproductlist;
			 	$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			 	parent::hy_log_str_add($logstr);
			 	echo json_encode($echoarr);
			 	return false;
			 	
			 }else{
			 	//模拟用户操作发放兑换码--运营商1，2，3--
			 	$url ='http://xbapp.xinyouxingkong.com/dh_work/interface/dhinit.php?gateway='.$gateway.'&mbps='.$mbps.'&ttype='.$ttype.'&orderno='.$orderno.'&userid='.parent::__get('xb_userid').'&youxiaoday=30&name='.urlencode($goodsname).'&describe='.urlencode($goodsname);
			 	$duihuancode = HyItems::vget( $url, 10000 );
			 	
			 	if($duihuancode['httpcode']==200){
			 		//获取兑换码
			 		$code = $duihuancode['content'];
			 		
			 		if(strlen($code)!='18' || $code=''){
			 			$echoarr = array();
			 			$echoarr['returncode'] = 'error';
			 			$echoarr['returnmsg']  = '兑换码生成失败，订单提交失败！';
			 			$echoarr['dataarr'] = array();
			 			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			 			parent::hy_log_str_add($logstr);
			 			echo json_encode($echoarr);
			 			return false;
			 			
			 		}else{//兑换码生成成功，可以入库操作
			 			$time=date('Y-m-d H:i:s');
			 			$insertuserbuysql = "insert into shop_userbuy(userid,siteid,typeid,name,price,score,productid,status,orderno,keystr,order_createtime) values 
			 					('".parent::__get('xb_userid')."','".$siteid."','".$typeid."','".$goodsname."','".$xiaohaomoney."','".$xiaohaojifen."','".$productid."','3',
			 							'".$orderno."','".$code."','".$time."')";
			 			$insertuserbuylist = parent::__get('HyDb')->execute($insertuserbuysql);
			 			
			 			
			 		}
			 		
			 		
			 	}
			 	
			 }
			 
			 
			 }else if($protype=='12'){//该商品为卡密类的商品，走卡密接口
			 	
			 	$orderno = date('YmdHis').mt_rand(1000,9999);//商品订单号
			 	
			 	//判断该订单是否有兑换秘钥
				 $buyproductsql  = "select id,keystr,orderno,order_createtime,name,price,score,typeid,productid from shop_userbuy where orderno='".$orderno."'";
				 $buyproductlist = parent::__get('HyDb')->get_all($buyproductsql);
				 
				 if(count($buyproductlist)>0){
				 	$echoarr = array();
				 	$echoarr['returncode'] = 'success';
				 	$echoarr['returnmsg']  = '订单提交成功！';
				 	$echoarr['dataarr'] = $buyproductlist;
				 	$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				 	parent::hy_log_str_add($logstr);
				 	echo json_encode($echoarr);
				 	return false;
				 	
				 }else{
				 	//生成秘钥
				 	$duihuanmasql  = "select id,duihuanma from xb_duihuanma where flag=9 limit 1";
				 	$duihuanmalist = parent::__get('HyDb')->get_row($duihuanmasql);
				 	
				 	if($duihuanmalist['id']<=0){
				 		$echoarr = array();
				 		$echoarr['returncode'] = 'error';
				 		$echoarr['returnmsg']  = '该商品不可以兑换，系统维护中！';
				 		$echoarr['dataarr'] = array();
				 		$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				 		parent::hy_log_str_add($logstr);
				 		echo json_encode($echoarr);
				 		return false;
				 		
				 	}else{
				 		$code = $duihuanmalist['duihuanma'];
				 		//购买记录表的插入
				 		$time=date('Y-m-d H:i:s');
				 		$insertuserbuysql = "insert into shop_userbuy(userid,siteid,typeid,name,price,score,productid,status,orderno,keystr,order_createtime) values
			 					('".parent::__get('xb_userid')."','".$siteid."','".$typeid."','".$goodsname."','".$xiaohaomoney."','".$xiaohaojifen."','".$productid."','1',
			 							'".$orderno."','".$code."','".$time."')";
				 		$insertuserbuylist = parent::__get('HyDb')->execute($insertuserbuysql);
				 	}
				 	
				 }
			 	
			 	
			 }else if($protype=='13'){//该商品为饭票商品，走饭票的接口
			 	
			 	
			 }else if($protype=='14'){//该商品为实物，走实物接口
			 	
			 	$orderno = date('YmdHis').mt_rand(1000,9999);//商品订单号
			 		
			 	//判断该订单是否有兑换秘钥
			 	$buyproductsql  = "select id,keystr,orderno,order_createtime,name,price,score,typeid,productid from shop_userbuy where orderno='".$orderno."'";
			 	$buyproductlist = parent::__get('HyDb')->get_all($buyproductsql);
			 	
			 	if(count($buyproductlist)>0){
			 		$echoarr = array();
			 		$echoarr['returncode'] = 'success';
			 		$echoarr['returnmsg']  = '订单提交成功！';
			 		$echoarr['dataarr'] = $buyproductlist;
			 		$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			 		parent::hy_log_str_add($logstr);
			 		echo json_encode($echoarr);
			 		return false;
			 	}else{
			 		//读取用户的地址表
			 		$useraddresssql  = "select * from xb_user_address where userid='".parent::__get('xb_userid')."'";
			 		$useraddresslist = 
			 		
			 	}
			
			 }
			
			
			
			
			
			
			
		}else{
			
		}
		
		
		
		
		
		
	}
	
	
	
	
	//操作入口
	public function controller_init(){
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='522'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
	
	
	
		$this->controller_userduihuan();
	
		return true;
	}
	
	
}