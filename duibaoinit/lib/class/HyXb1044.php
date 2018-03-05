<?php
/*
 * 订单记录详情页
 */
class HyXb1044 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $orderid;  //订单id
	
	
	//数据的初始化
	public function __construct($input_data){
		//数据初始化
		parent::__construct($input_data);
		
		$this->pagesize = isset($input_data['pagesize'])? $input_data['pagesize']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
		$this->imgwidth = isset($input_data['imgwidth'])? $input_data['imgwidth']:'';  //图片的宽
		$this->imgheight  = isset($input_data['imgheight'])?$input_data['imgheight']:'';     //图片高
		
		$this->orderid = isset($input_data['orderid'])?$input_data['orderid']:'';//数据类型
		
		if($this->imgwidth==''){
			$this->imgwidth='100';
		}
		if($this->imgheight==''){
			$this->imgheight='100';
		}
	}
	
	
	//订单状态
	public function controller_exec1(){
		
		
		$shangjiaarr = array();//商户名
		$shangjiaqqarr = array();//商户QQ
		$shangphonearr = array();//商户联系方式
		
		//商家信息
		$shangjiasql = "select * from shop_site where flag=1 and checkstatus='2'  ";
		$shangjialist = parent::__get('HyDb')->get_all($shangjiasql);
		
		foreach ($shangjialist as $keys=>$vals){
			$shangjiaarr[$shangjialist[$keys]['id']] = $shangjialist[$keys]['storename'];//商户名
			$shangjiaqqarr[$shangjialist[$keys]['id']] = HyItems::hy_qiniuimgurl('duibao-business',$shangjialist[$keys]['touxiang'],$this->imgwidth,$this->imgheight);//头像
			$shangphonearr[$shangjialist[$keys]['id']] = $shangjialist[$keys]['phone'];//商户联系方式
				
		}
		
		$duihuan_sql  = "select shop_userbuy.*,shop_product.mainpic,shop_product.xushi_type,shop_product.miyao_type, 
						shop_product.feetype,shop_product.pickup,shop_product.youxiaoqi,shop_product.stop_datetime  
						from shop_userbuy,shop_product 
						where  shop_userbuy.productid = shop_product.id and shop_userbuy.id='".$this->orderid."'  ";
		$duihuan_list = parent::__get('HyDb')->get_row($duihuan_sql);
		
			//取货方式
			if($duihuan_list['pickup']=='1'){
				$duihuan_list['pickup']='到店自提';
			}else if($duihuan_list['pickup']=='2'){
				$duihuan_list['pickup']='网上兑换';
			}else if($duihuan_list['pickup']=='3'){
				$duihuan_list['pickup']='物流';
			}
			
			//价格展示
			$duihuan_list['moneyscore'] = parent::func_diffzhifutype($duihuan_list['feetype'],$duihuan_list['price'],$duihuan_list['score']);
			//图片
			$duihuan_list['mainpic'] = HyItems::hy_qiniuimgurl('duibao-shop',$duihuan_list['mainpic'],$this->imgwidth,$this->imgheight);
			
			$duihuan_list['price'] = parent::formatmoney($duihuan_list['price']/100);
			//自营还是商户类型判断
			if($duihuan_list['siteid']=='1000'){
				$duihuan_list['flag']='自营商品';//自营商户
			}else{
				$duihuan_list['flag']='入驻商户商品';//商家入驻商户
			}
			
			$duihuan_list['typeid']= substr($duihuan_list['typeid'],0,2);
			
			if($duihuan_list['typeid']=='11'){
				$duihuan_list['lflag']='1';//单独的商品
			}else{
				$duihuan_list['lflag']='2';//其他商品
			}
			
			$duihuan_list['storename'] = isset($shangjiaarr[$duihuan_list['siteid']])?$shangjiaarr[$duihuan_list['siteid']]:'';//商户名称
			$duihuan_list['touxiang']  = isset($shangjiaqqarr[$duihuan_list['siteid']])?$shangjiaqqarr[$duihuan_list['siteid']]:'';//商户qq
			$duihuan_list['phone']     = isset($shangphonearr[$duihuan_list['siteid']])?$shangphonearr[$duihuan_list['siteid']]:'';//商户手机号
			
			//状态 卡密的状态为领取  实物的状态为发货
			if($duihuan_list['status']=='3'){
				if($duihuan_list['feetype']=='2'  && $duihuan_list['xushi_type']=='2'){
					$duihuan_list['statusmsg']='待发货';
				}else{
					$duihuan_list['statusmsg']='待领取';
				}
			}else if($duihuan_list['status']=='4'){
				if($duihuan_list['feetype']=='2'  && $duihuan_list['xushi_type']=='2'){
					$duihuan_list['statusmsg']='已发货';
				}else{
					$duihuan_list['statusmsg']='已领取';
				}
			}else if($duihuan_list['status']=='5'){//已确认
				$duihuan_list['statusmsg']='待确认';
			}else if($duihuan_list['status']=='7'){//已评价
				$duihuan_list['statusmsg']='已评价';
			}else if($duihuan_list['status']=='20'){
				$duihuan_list['statusmsg']='支付确认中';
			}else if($duihuan_list['status']=='8'){
				$duihuan_list['statusmsg']='已删除';
			}
			
			
			
			//实物，超过14天自动确认为收货
			if($duihuan_list['status']=='5'){
				if($duihuan_list['endday']<time()){
					$duihuan_list['endday'] = strtotime($duihuan_list['fh_fahuotime'])+(int)$duihuan_list['youxiaoqi']*24*60*60;
					//更新确认收货状态
					$updatestatus = "update shop_userbuy set status='4',fh_shouhuotime='".date('Y-m-d H:i:s')."' where id='".$duihuan_list['id']."' ";
					parent::__get('HyDb')->execute($updatestatus);
					
					$duihuan_list['statusmsg']='已确认';
				}
			}else if($duihuan_list['mtype']='1'){
				$duihuan_list['endday'] = strtotime($duihuan_list['order_createtime'])+(int)$duihuan_list['youxiaoqi']*24*60*60;
				//是否有效的标识
				if($duihuan_list['endday']<time()){
					$duihuan_list['statusmsg']='已失效';
				}
			}
			
			
		$echojsonstr = HyItems::echo2clientjson('100','获取成功',$duihuan_list);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
		
	}
	
	
	
	
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		$ret = $this->controller_exec1();
		return $ret;
	}
	
}