<?php
/*
 * 订单记录---单个订单的详情记录
 */
class HyXb532 extends HyXb{


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

		$this->quanid  = isset($input_data['quanid'])?$input_data['quanid']:'';     //订单id

	}


	
	protected function controller_duihuanscore(){
		
		
		$returnarr = array();
		$flagarr = array();
		$reorderarr = array();
		
		$shangjiaarr = array();//商户名
		$shangjiaqqarr = array();//商户QQ
		$shangphonearr = array();//商户联系方式
		
		
		//商家信息
		$shangjiasql = "select * from shop_site where flag=1 and checkstatus='2' and storestatus='2' ";
		$shangjialist = parent::__get('HyDb')->get_all($shangjiasql);
		
		foreach ($shangjialist as $keys=>$vals){
			
			$shangjiaarr[$shangjialist[$keys]['id']] = $shangjialist[$keys]['storename'];//商户名
			$shangjiaqqarr[$shangjialist[$keys]['id']] = $shangjialist[$keys]['qq'];//商户名
			$shangphonearr[$shangjialist[$keys]['id']] = $shangjialist[$keys]['phone'];//商户联系方式
			
		}
		
		//获取流量兑换的状态
		$dh_orderlist_sql  = "select userid,flag,orderno from dh_orderlist where userid='".parent::__get('xb_userid')."'";
		$dh_orderlist_list = parent::__get('HyDb')->get_all($dh_orderlist_sql);
		
		$dhorderlistarr = array();
		
		foreach ($dh_orderlist_list as $vals){
			$dhorderlistarr[$vals['orderno']] = $vals['flag'];
				
		}
		//flag  0状态未知，1已使用，5兑换中，9未使用 (只有流量类型才会显示未使用、充值中和已使用的状态)
		
		$duihuan_sql  = "select shop_userbuy.*,shop_product.mainpic,shop_product.pickup,shop_product.youxiaoqi,shop_product.stop_datetime from shop_userbuy,shop_product 
						 where  shop_userbuy.productid = shop_product.id and shop_userbuy.id='".$this->quanid."'  ";
		
		$duihuan_list = parent::__get('HyDb')->get_row($duihuan_sql);
		
		
		//foreach ($duihuan_list as $keys=>$vals){
			
			$typeid = substr($duihuan_list['typeid'],0,1);//判断是虚拟的还是实物
			
			if($typeid=='1'){
				$duihuan_list['qflag']='3';//自营商户
			}else if($typeid=='2'){
				$duihuan_list['qflag']='2';//实体
			}else if($duihuan_list['siteid']=='1000' && $typeid=='1'){
				$duihuan_list['qflag']='1';//虚拟
			}
			
			if($duihuan_list['siteid']=='1000'){
				$duihuan_list['flag']='自营商品';//自营商户
			}else{
				$duihuan_list['flag']='入驻商户商品';//商家入驻商户
			}
			
			/*  	//判断什么类型
			$typelist = substr($duihuan_list[$keys]['typeid'],0,4);
			
			if($typelist=='1112'|| $typelist=='1312' || $typelist=='1322' ){//单秘钥
				$duihuan_list[$keys]['tflag']='1';//单秘钥
			}else if($typelist=='22'){
				$duihuan_list[$keys]['tflag']='2';//实物类型
			}else if($typelist=='1311' || $typelist=='1321' ){
				
				$duihuan_list[$keys]['tflag']='3';//多秘钥
			}else{
				$duihuan_list[$keys]['tflag']='4';//其他
			}*/
			
			$typelist = substr($duihuan_list['typeid'],0,4);
			
			if($typelist=='1112'|| $typelist=='1312' || $typelist=='1322' ){//单秘钥
				$duihuan_list['tflag']='1';//单秘钥
			}else if($typelist=='22'){
				$duihuan_list['tflag']='2';//实物类型
			}else if($typelist=='1311' || $typelist=='1321' ){
			
				$duihuan_list['tflag']='3';//多秘钥
			}else{
				$duihuan_list['tflag']='4';//其他
			}
			
			/* if($duihuan_list['typeid']=='11'|| $duihuan_list['typeid']=='14'){
				$duihuan_list['tflag']='1';//单秘钥
			}else if($duihuan_list['typeid']=='22'){
				$duihuan_list['tflag']='2';//实物类型
			}else if($duihuan_list['typeid']=='13'){
				$duihuan_list['tflag']='3';//多秘钥
			}else{
				$duihuan_list['tflag']='4';//其他
			} */
			
			
			if($duihuan_list['tflag']=='1'){//单秘钥
				
				if($duihuan_list['keystr']==''){
					$duihuan_list['keystr']='派送中';
				}
				
				if($duihuan_list['passwd']==''){
					$duihuan_list['passwd']='派送中';
				}
			}else if($duihuan_list['tflag']=='3'){//多秘钥
				
				if($duihuan_list['keystr']==''){
					$duihuan_list['keystr']='派送中';
				}
				
				if($duihuan_list['passwd']==''){
					$duihuan_list['passwd']='派送中';
				}
			}
			
			
			
			
			$duihuan_list['storename'] = $shangjiaarr[$duihuan_list['siteid']];//商户名称
			$duihuan_list['qq']        = $shangjiaqqarr[$duihuan_list['siteid']];//商户qq
			$duihuan_list['phone']        = $shangphonearr[$duihuan_list['siteid']];//商户手机号
			
			$duihuan_list['moneyscore']  = '￥'.$duihuan_list['price'].'+'.$duihuan_list['score'].'馅饼';
			
			if($duihuan_list['pickup']=='1'){
				$duihuan_list['pickup']='到店自提';
			}else if($duihuan_list['pickup']=='2'){
				$duihuan_list['pickup']='网上兑换';
			}
			
			//流量单独的状态判断
			if($typelist=='1112' && $duihuan_list['status']=='3'){
				$duihuan_list['statuss'] = $dhorderlistarr[$duihuan_list['orderno']];//1已使用，5兑换中，9未使用 (只有流量类型才会显示未使用、充值中和已使用的状态)
			}else{
				$duihuan_list['statuss']='';
			}
			
			
			if($duihuan_list['statuss']=='1'){
				$duihuan_list['statuss']='充值成功';
				$duihuan_list['status'] = '4';
			}else if($duihuan_list['statuss']=='5'){
				$duihuan_list['statuss']='充值中';
			}else if($duihuan_list['statuss']=='9'){
				$duihuan_list['statuss']='未领取流量';
			}
			
			if($duihuan_list['status']=='3'){
				$duihuan_list['status']='待领取';
			}else if($duihuan_list['status']=='4'){
				$duihuan_list['status']='已领取';
			}
			
			/* if($duihuan_list['mtype']=='1' && $duihuan_list['typeid']=='11'){
				
				$duihuan_list['mtype']='11';
			} */
			
			if(substr($duihuan_list['typeid'],0,2)=='11'){
				
				$duihuan_list['typeid'] = substr($duihuan_list['typeid'],0,2);//表示流量
				
			}
			
			
			//兑换码的有效期dayok
			$duihuan_list['dayok'] = $duihuan_list['youxiaoqi'];
			
			if($duihuan_list['dayok']>0 && $duihuan_list['dayok']!=''){
				
				$duihuan_list['nflag'] = '1';
				
				//兑换码的过期时间
				$duihuan_list['endday'] = strtotime(substr($duihuan_list['order_createtime'],0,10))+ $duihuan_list['dayok']*24*60*60;
				$duihuan_list['endday'] = substr(date('Y-m-d H:i:s',$duihuan_list['endday']),0,10);
					
				//是否有效的标识
				if(strtotime($duihuan_list['endday'])<strtotime(date('Y-m-d'))){
				
					$duihuan_list['okflag'] = '9';//失效
					$duihuan_list['status']='已失效';
				}else{
					$duihuan_list['okflag'] = '1';//有效
				}
				
			}else{
				$duihuan_list['nflag'] = '9';
			}

			
		if(count($duihuan_list)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '订单详情获取成功';
			$echoarr['dataarr'] = $duihuan_list;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '订单详情获取失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}


	}



	public function controller_init(){


		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}

		//判断是否为正常用户
		if(parent::__get('xb_usertype')!='1'){

			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '用户兑换记录为空！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}

		//判断每页的条数，数值介于1到20之间
		if($this->count<0 || $this->count>20){

			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '每页展示的条数不能超过20条';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;

		}


		$r = $this->controller_duihuanscore();
		if($r===false) {
			return false;
		}

		return true;


	}

}