<?php
/*
 * 订单记录
 */
class HyXb528 extends HyXb{


	private $count;
	private $page;


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

		$this->count = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
		
		$this->kindtype  = isset($input_data['kindtype'])?$input_data['kindtype']:'';     //订单展示的类型

	}


	
	protected function controller_duihuanscore(){
		
		
		if($this->kindtype=='5'){//订单数量的添加
			
			$ordernumsql  = "select count(*)as num1 from shop_userbuy where userid='".parent::__get('xb_userid')."' and status='3' ";
			$ordernumlist = parent::__get('HyDb')->get_row($ordernumsql);
			
			$ordernumsql1  = "select count(*)as num2 from shop_userbuy where userid='".parent::__get('xb_userid')."' and status='4'";
			$ordernumlist1 = parent::__get('HyDb')->get_row($ordernumsql1);
			
			$ordernumsql2  = "select count(*)as num3 from shop_userbuy where userid='".parent::__get('xb_userid')."' and pingjia='9' and status='4' ";
			$ordernumlist2 = parent::__get('HyDb')->get_row($ordernumsql2);
			
			
			$temparr = array(
					'num1'     =>(int)$ordernumlist['num1'],
					'num2'     => (int)$ordernumlist1['num2'],
					'num3'     => (int)$ordernumlist2['num3'],
						
			);
			
			
			
			if(empty($temparr)){
				
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '订单数量获取为空';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
				
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '订单数量获取成功';
				$echoarr['dataarr'] = $temparr;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}
			
			
			
			
			
		}else{
		
			$where = '';
			
			if($this->kindtype=='1'){//待领取
				$where = " userid='".parent::__get('xb_userid')."' and shop_userbuy.status='3'  ";
			}else if($this->kindtype=='2'){//已领取
				$where = " userid='".parent::__get('xb_userid')."' and shop_userbuy.status='4' ";
			}else if($this->kindtype=='3'){//待评价
				$where = " userid='".parent::__get('xb_userid')."' and shop_userbuy.pingjia='9' and shop_userbuy.status='4' ";
			}else if($this->kindtype=='4'){//全部
				$where = " userid='".parent::__get('xb_userid')."' and shop_userbuy.status in (3,4) ";
			}
	
	
			if($this->page=='' || $this->page=='0'){
	
				$this->page=1;
			}
	
			if($this->count=='' || $this->count== 'undefined'){
					
				$this->count=10;
			}
	
			$firstpage = ($this->page-1)*$this->count;
			$pagesize  = $this->count;
	
			$returnarr = array();
			$flagarr = array();
			$reorderarr = array();
	
			//查询总条数
			$duihuansumsql  = "select count(*) as num from shop_userbuy,shop_product where $where and shop_userbuy.productid = shop_product.id ";
			$duihuansumlist = parent::__get('HyDb')->get_all($duihuansumsql);
	
			if(count($duihuansumlist)>0){
				$returnarr['maxcon'] = $duihuansumlist[0]['num'];//总条数
			}else{
				$returnarr['maxcon'] = 0;
			}
	
			//总页数
			$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
			
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
			
			$duihuan_sql  = "select shop_userbuy.*,shop_product.mainpic,shop_product.pickup,shop_product.youxiaoqi,shop_product.stop_datetime from shop_userbuy,shop_product where $where and shop_userbuy.productid = shop_product.id order by id desc limit $firstpage,$pagesize";
			$duihuan_list = parent::__get('HyDb')->get_all($duihuan_sql);
			
			foreach ($duihuan_list as $keys=>$vals){
				
				if($duihuan_list[$keys]['keystr']==''){
					$duihuan_list[$keys]['keystr']='派送中';
				}
				
				if($duihuan_list[$keys]['passwd']==''){
					$duihuan_list[$keys]['passwd']='派送中';
				}
				
				$typeid = substr($duihuan_list[$keys]['typeid'],0,1);//判断是虚拟的还是实物
				
				if($typeid=='1'){
					$duihuan_list[$keys]['qflag']='3';//自营商户
				}else if($typeid=='2'){
					$duihuan_list[$keys]['qflag']='2';//实体
				}else if($duihuan_list[$keys]['siteid']=='1000' && $typeid=='1'){
					$duihuan_list[$keys]['qflag']='1';//虚拟
				}
				
				if($duihuan_list[$keys]['siteid']=='1000'){
					$duihuan_list[$keys]['flag']='自营商品';//自营商户
				}else{
					$duihuan_list[$keys]['flag']='入驻商户商品';//商家入驻商户
				}
				
				/* //单卡密 多卡密的判断
				if($this->typeid=='1112' ||$this->typeid=='1312' || $this->typeid=='1322' ){//11流量 1311 自采多卡密 1312 自采单卡密    1321商户发放多卡密      1322 商户发放单卡密
					$this->tflag='1';//单卡密
				}else if($this->typeid=='1311' || $this->typeid=='1321'){
					$this->tflag='3';//多卡密
				}else if($this->typeid=='22'){
					$this->tflag='2';
				}else{
					$this->tflag='1';
				} */
				
				//判断什么类型
				$typelist = substr($duihuan_list[$keys]['typeid'],0,4);
				
				if($typelist=='1112'|| $typelist=='1312' || $typelist=='1322' ){//单秘钥
					$duihuan_list[$keys]['tflag']='1';//单秘钥
				}else if($typelist=='22'){
					$duihuan_list[$keys]['tflag']='2';//实物类型
				}else if($typelist=='1311' || $typelist=='1321' ){
					
					$duihuan_list[$keys]['tflag']='3';//多秘钥
				}else{
					$duihuan_list[$keys]['tflag']='4';//其他
				}
				
				$duihuan_list[$keys]['storename'] = $shangjiaarr[$duihuan_list[$keys]['siteid']];//商户名称
				$duihuan_list[$keys]['qq']        = $shangjiaqqarr[$duihuan_list[$keys]['siteid']];//商户qq
				$duihuan_list[$keys]['phone']        = $shangphonearr[$duihuan_list[$keys]['siteid']];//商户手机号
				
				$duihuan_list[$keys]['moneyscore']  = '￥'.$duihuan_list[$keys]['price'].'+'.$duihuan_list[$keys]['score'].'馅饼';
				
				if($duihuan_list[$keys]['pickup']=='1'){
					$duihuan_list[$keys]['pickup']='到店自提';
				}else if($duihuan_list[$keys]['pickup']=='2'){
					$duihuan_list[$keys]['pickup']='网上兑换';
				}
				
				//流量单独的状态判断
				if($typelist=='1112' && $duihuan_list[$keys]['status']=='3'){
					$duihuan_list[$keys]['statuss'] = $dhorderlistarr[$duihuan_list[$keys]['orderno']];//1已使用，5兑换中，9未使用 (只有流量类型才会显示未使用、充值中和已使用的状态)
				}else{
					$duihuan_list[$keys]['statuss']='';
				}
				
				if($duihuan_list[$keys]['statuss']=='1'){
					$duihuan_list[$keys]['statuss']='充值成功';
					$duihuan_list[$keys]['status'] = '4';
				}else if($duihuan_list[$keys]['statuss']=='5'){
					$duihuan_list[$keys]['statuss']='充值中';
				}else if($duihuan_list[$keys]['statuss']=='9'){
					$duihuan_list[$keys]['statuss']='未领取流量';
				}else{
					$duihuan_list[$keys]['statuss']='error';
				}
				
				if($duihuan_list[$keys]['status']=='3'){
					$duihuan_list[$keys]['status']='待领取';
				}else if($duihuan_list[$keys]['status']=='4'){
					$duihuan_list[$keys]['status']='已领取';
				}else{
					$duihuan_list[$keys]['status']='error';
				}
				
				if($duihuan_list[$keys]['mtype']=='1' && $typelist=='1112'){
					
					$duihuan_list[$keys]['mtype']='1';
				}
				
				
				//兑换码的有效期dayok
				$duihuan_list[$keys]['dayok'] = $duihuan_list[$keys]['youxiaoqi'];
				
				if($duihuan_list[$keys]['dayok']>0 && $duihuan_list[$keys]['dayok']!=''){
					
					$duihuan_list[$keys]['nflag'] = '1';
					
					//兑换码的过期时间
					$duihuan_list[$keys]['endday'] = strtotime(substr($duihuan_list[$keys]['order_createtime'],0,10))+ $duihuan_list[$keys]['dayok']*24*60*60;
					$duihuan_list[$keys]['endday'] = substr(date('Y-m-d H:i:s',$duihuan_list[$keys]['endday']),0,10);
						
					//是否有效的标识
					if(strtotime($duihuan_list[$keys]['endday'])<strtotime(date('Y-m-d'))){
					
						$duihuan_list[$keys]['okflag'] = '9';//失效
						$duihuan_list[$keys]['status']='已失效';
					}else{
						$duihuan_list[$keys]['okflag'] = '1';//有效
					}
					
				}else{
					$duihuan_list[$keys]['nflag'] = '9';
				}
				
			}
			
			if(count($duihuan_list)>0){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '用户兑换记录列表获取成功';
				$echoarr['maxcon']  = $returnarr['maxcon'];
				$echoarr['sumpage'] = $returnarr['sumpage'];
				$echoarr['nowpage'] = $this->page;
				$echoarr['dataarr'] = $duihuan_list;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '用户兑换记录列表为空';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
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