<?php
/*
 * 订单记录
 */
class HyXb1043 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $type;  //商品id
	
	
	//数据的初始化
	public function __construct($input_data){
		//数据初始化
		parent::__construct($input_data);
		
		$this->pagesize = isset($input_data['pagesize'])? $input_data['pagesize']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
		$this->imgwidth = isset($input_data['imgwidth'])? $input_data['imgwidth']:'';  //图片的宽
		$this->imgheight  = isset($input_data['imgheight'])?$input_data['imgheight']:'';     //图片高
		$this->type = isset($input_data['type'])?$input_data['type']:'';//数据类型
		
		if($this->imgwidth==''){
			$this->imgwidth='100';
		}
		if($this->imgheight==''){
			$this->imgheight='100';
		}
	}
	
	
	//订单数量的展示
	public function controller_exec5(){
		
		//待领取
		$ordernumsql  = "select count(*)as num1 from shop_userbuy where userid='".parent::__get('userid')."' and status='3' ";
		$ordernumlist = parent::__get('HyDb')->get_row($ordernumsql);
			
		//已发货
		$ordernumsql1  = "select count(*)as num2 from shop_userbuy where userid='".parent::__get('userid')."' and status='4'";
		$ordernumlist1 = parent::__get('HyDb')->get_row($ordernumsql1);
		
		//待评价的数量
		$ordernumsql2  = "select count(*)as num3 from shop_userbuy where userid='".parent::__get('userid')."' and status='6' ";
		$ordernumlist2 = parent::__get('HyDb')->get_row($ordernumsql2);
		
		
		$temparr = array(
				'num1'     =>(int)$ordernumlist['num1'],
				'num2'     => (int)$ordernumlist1['num2'],
				'num3'     => (int)$ordernumlist2['num3'],
					
		);
		
		if(empty($temparr)){
			$echojsonstr = HyItems::echo2clientjson('426','订单数量为空');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}else{
			$echojsonstr = HyItems::echo2clientjson('100','获取成功',$temparr);
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return true;
		}
	}
	
	//订单状态
	public function controller_exec1(){
		
		$where='';
		if($this->type=='1'){//全部
			$where = " userid='".parent::__get('userid')."'  ";
		}else if($this->type=='2'){//待领取
			$where = " userid='".parent::__get('userid')."' and shop_userbuy.status='3'  ";
		}else if($this->type=='3'){//已领取
			$where = " userid='".parent::__get('userid')."' and shop_userbuy.status='5' ";//确认收货
		}else if($this->type=='4'){//待评价
			$where = " userid='".parent::__get('userid')."' and shop_userbuy.status ='4' ";
		}else{
			$echojsonstr = HyItems::echo2clientjson('410','类型错误');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		//查询总条数
		$duihuansumsql  = "select count(*) as num from shop_userbuy,shop_product where $where and shop_userbuy.productid = shop_product.id ";
		$duihuansumlist = parent::__get('HyDb')->get_one($duihuansumsql);
		
		$pagearr = HyItems::hy_pagepage($this->page,$this->pagesize,$duihuansumlist);
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
		
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
		
		$duihuan_sql  =  "select shop_userbuy.id,shop_userbuy.siteid,shop_userbuy.mtype,shop_userbuy.name,
						shop_userbuy.price,shop_userbuy.score,shop_userbuy.status,shop_userbuy.order_createtime,
						shop_userbuy.youxiaoqi,shop_userbuy.fh_fahuotime,shop_userbuy.typeid,
						shop_product.mainpic,shop_product.pickup,shop_product.miyao_type,
						shop_product.feetype,shop_product.xushi_type,shop_product.youxiaoqi,shop_product.stop_datetime 
						from shop_userbuy ,shop_product
						where shop_userbuy.productid = shop_product.id  and $where
						order by id desc ".$pagelimit;
		$duihuan_list = parent::__get('HyDb')->get_all($duihuan_sql);
		
		foreach ($duihuan_list as $keys=>$vals){
			//取货方式
			if($duihuan_list[$keys]['pickup']=='1'){
				$duihuan_list[$keys]['pickup']='到店自提';
			}else if($duihuan_list[$keys]['pickup']=='2'){
				$duihuan_list[$keys]['pickup']='网上兑换';
			}else if($duihuan_list[$keys]['pickup']=='3'){
				$duihuan_list[$keys]['pickup']='物流';
			}
			
			//价格展示
			$duihuan_list[$keys]['moneyscore'] = parent::func_diffzhifutype($duihuan_list[$keys]['feetype'],$duihuan_list[$keys]['price'],$duihuan_list[$keys]['score']);
			//图片
			$duihuan_list[$keys]['mainpic'] = HyItems::hy_qiniuimgurl('duibao-shop',$duihuan_list[$keys]['mainpic'],$this->imgwidth,$this->imgheight);
			
			//自营还是商户类型判断
			if($duihuan_list[$keys]['siteid']=='1000'){
				$duihuan_list[$keys]['flag']='自营商品';//自营商户
			}else{
				$duihuan_list[$keys]['flag']='入驻商户商品';//商家入驻商户
			}
			
			$duihuan_list[$keys]['typeid']= substr($duihuan_list[$keys]['typeid'],0,2);
			
			if($duihuan_list[$keys]['typeid']=='11'){
				$duihuan_list[$keys]['lflag']='1';//单独的商品
			}else{
				$duihuan_list[$keys]['lflag']='2';//其他商品
			}
			
			
			$duihuan_list[$keys]['storename'] = isset($shangjiaarr[$duihuan_list[$keys]['siteid']])?$shangjiaarr[$duihuan_list[$keys]['siteid']]:'';//商户名称
			$duihuan_list[$keys]['touxiang']  = isset($shangjiaqqarr[$duihuan_list[$keys]['siteid']])?$shangjiaqqarr[$duihuan_list[$keys]['siteid']]:'';//商户qq
			$duihuan_list[$keys]['phone']     = isset($shangphonearr[$duihuan_list[$keys]['siteid']])?$shangphonearr[$duihuan_list[$keys]['siteid']]:'';//商户手机号
			
			//状态 卡密的状态为领取  实物的状态为发货
			if($duihuan_list[$keys]['status']=='3'){
				if($duihuan_list[$keys]['feetype']=='2'  && $duihuan_list[$keys]['xushi_type']=='2'){
					$duihuan_list[$keys]['statusmsg']='待发货';
				}else{
					$duihuan_list[$keys]['statusmsg']='待领取';
				}
			}else if($duihuan_list[$keys]['status']=='4'){
				if($duihuan_list[$keys]['feetype']=='2'  && $duihuan_list[$keys]['xushi_type']=='2'){
					$duihuan_list[$keys]['statusmsg']='已发货';
				}else{
					$duihuan_list[$keys]['statusmsg']='已领取';
				}
			}else if($duihuan_list[$keys]['status']=='5'){//已确认
				$duihuan_list[$keys]['statusmsg']='待确认';
			}else if($duihuan_list[$keys]['status']=='7'){//已评价
				$duihuan_list[$keys]['statusmsg']='已评价';
			}else if($duihuan_list[$keys]['status']=='20'){
				$duihuan_list[$keys]['statusmsg']='支付确认中';
			}else if($duihuan_list[$keys]['status']=='8'){
				$duihuan_list[$keys]['statusmsg']='已删除';
			}
			
			
			
			//实物，超过14天自动确认为收货
			if($duihuan_list[$keys]['status']=='5'){
				if($duihuan_list[$keys]['endday']<time()){
					$duihuan_list[$keys]['endday'] = strtotime($duihuan_list[$keys]['fh_fahuotime'])+(int)$duihuan_list[$keys]['youxiaoqi']*24*60*60;
					//更新确认收货状态
					$updatestatus = "update shop_userbuy set status='4',fh_shouhuotime='".date('Y-m-d H:i:s')."' where id='".$duihuan_list[$keys]['id']."' ";
					parent::__get('HyDb')->execute($updatestatus);
					
					$duihuan_list[$keys]['statusmsg']='已确认';
				}
			}else if($duihuan_list[$keys]['mtype']='1'){
				$duihuan_list[$keys]['endday'] = strtotime($duihuan_list[$keys]['order_createtime'])+(int)$duihuan_list[$keys]['youxiaoqi']*24*60*60;
				//是否有效的标识
				if($duihuan_list[$keys]['endday']<time()){
					$duihuan_list[$keys]['statusmsg']='已失效';
				}
			}
			
			
		}
		
		$retarr = array(
				'pagemsg' => $pagemsg,
				'list' => $duihuan_list,
		);
			
			
		$echojsonstr = HyItems::echo2clientjson('100','获取成功',$retarr);
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
		
		if($this->type=='5'){//待处理
			$ret = $this->controller_exec5();
		}else{
			$ret = $this->controller_exec1();
			/* $echojsonstr = HyItems::echo2clientjson('100','类型错误');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false; */
		}
		
		return $ret;
	}
	
}