<?php 
/*
 * 订单记录的查询展示
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
	
	//订单展示操作
	public function controller_duihuanscore(){
		
		
		if($this->kindtype=='5'){//订单数量的展示
			
			//echo $this->kindtype;
			//待领取
			$ordernumsql  = "select count(*)as num1 from shop_userbuy where userid='".parent::__get('xb_userid')."' and status='3' ";
			//echo $ordernumsql;exit;
			$ordernumlist = parent::__get('HyDb')->get_row($ordernumsql);
			
			//已发货
			$ordernumsql1  = "select count(*)as num2 from shop_userbuy where userid='".parent::__get('xb_userid')."' and status='4'";
			$ordernumlist1 = parent::__get('HyDb')->get_row($ordernumsql1);

			//待评价的数量
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
				//$where = " userid='".parent::__get('xb_userid')."' and shop_userbuy.pingjia='9' and shop_userbuy.confirm='2' and shop_userbuy.status='4' or shop_userbuy.status='5' ";
				$where = " userid='".parent::__get('xb_userid')."' and  shop_userbuy.pingjia='9' and shop_userbuy.status in (4,5) and shop_userbuy.confirm='2' ";
			}else if($this->kindtype=='4'){//全部
				$where = " userid='".parent::__get('xb_userid')."' and shop_userbuy.status in (3,4,5,6,20) ";
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
		
			$duihuan_sql  =  "select shop_userbuy.*,
									shop_product.mainpic,
									shop_product.pickup,
									shop_product.miyao_type,
									shop_product.feetype,
									shop_product.xushi_type,
									shop_product.youxiaoqi,
									shop_product.stop_datetime 

									from shop_userbuy,shop_product 
							
								where   shop_userbuy.productid = shop_product.id  and $where 
                            order by id desc limit $firstpage,$pagesize ";
			
			
			$duihuan_list = parent::__get('HyDb')->get_all($duihuan_sql);
			
			
			foreach ($duihuan_list as $keys=>$vals){
				
				//$duihuan_list[$keys]['endday'] = strtotime($duihuan_list[$keys]['fh_fahuotime'])+ 14*24*60*60;
				
				if($duihuan_list[$keys]['address_id']==null){
					$duihuan_list[$keys]['address_id'] ='';
				}
				if($duihuan_list[$keys]['childtypeid']==null){
					$duihuan_list[$keys]['childtypeid'] ='';
				}
				if($duihuan_list[$keys]['zhifu_order']==null){
					$duihuan_list[$keys]['zhifu_order'] ='';
				}
				if($duihuan_list[$keys]['miyao_type']==null){
					$duihuan_list[$keys]['miyao_type'] ='';
				}
				
				
				//收货地址的展示
				$address_sql = "select * from xb_user_address where id='".$duihuan_list[$keys]['address_id']."'";
				$address_list = parent::__get('HyDb')->get_row($address_sql); 
				
				//mobile,shouhuoren,address
				if($address_list['mobile']!=''){
					
					$duihuan_list[$keys]['fh_phone'] = $address_list['mobile'];
				}else{
					$duihuan_list[$keys]['fh_phone']='';
				}
				
				if($address_list['shouhuoren']!=''){
					
					$duihuan_list[$keys]['fh_shouhuoren'] = $address_list['shouhuoren'];
				}else{
					$duihuan_list[$keys]['fh_shouhuoren']='';
				}
				
				if($address_list['address']!=''){//fh_address
					$duihuan_list[$keys]['fh_address']=$address_list['address'];
				}else{
					$duihuan_list[$keys]['fh_address']='';
				}
				
				
				$duihuan_list[$keys]['typeid']= substr($duihuan_list[$keys]['typeid'],0,2);
				
				if($duihuan_list[$keys]['typeid']=='11'){
					$duihuan_list[$keys]['lflag']='1';//单独的商品
				}else{
					$duihuan_list[$keys]['lflag']='2';//其他商品
				}
				
				
				if($duihuan_list[$keys]['keystr']==''){
					$duihuan_list[$keys]['keystr']='派送中';
				}
				
				if($duihuan_list[$keys]['passwd']==''){
					$duihuan_list[$keys]['passwd']='派送中';
				}
				
				//自营还是商户类型判断
				if($duihuan_list[$keys]['siteid']=='1000'){
					$duihuan_list[$keys]['flag']='自营商品';//自营商户
				}else{
					$duihuan_list[$keys]['flag']='入驻商户商品';//商家入驻商户
				}
				
				//取货方式
				if($duihuan_list[$keys]['pickup']=='1'){
					$duihuan_list[$keys]['pickup']='到店自提';
				}else if($duihuan_list[$keys]['pickup']=='2'){
					$duihuan_list[$keys]['pickup']='网上兑换';
				}else if($duihuan_list[$keys]['pickup']=='3'){
					$duihuan_list[$keys]['pickup']='物流';
				}
				
				
				$duihuan_list[$keys]['storename'] = isset($shangjiaarr[$duihuan_list[$keys]['siteid']])?$shangjiaarr[$duihuan_list[$keys]['siteid']]:'';//商户名称
				$duihuan_list[$keys]['qq']        = isset($shangjiaqqarr[$duihuan_list[$keys]['siteid']])?$shangjiaqqarr[$duihuan_list[$keys]['siteid']]:'';//商户qq
				$duihuan_list[$keys]['phone']        = isset($shangphonearr[$duihuan_list[$keys]['siteid']])?$shangphonearr[$duihuan_list[$keys]['siteid']]:'';//商户手机号
				
				//金额的展示方式
				$duihuan_list[$keys]['price'] = number_format($duihuan_list[$keys]['price']/100,2);
				
				if($duihuan_list[$keys]['feetype']=='1'){
					$duihuan_list[$keys]['moneyscore']  = '￥'.$duihuan_list[$keys]['price'].'+'.$duihuan_list[$keys]['score'].'馅饼';
				}else if($duihuan_list[$keys]['feetype']=='2'){
					$duihuan_list[$keys]['moneyscore']  = '￥'.$duihuan_list[$keys]['price'];
				}else if($duihuan_list[$keys]['feetype']=='4' || $duihuan_list[$keys]['feetype']=='5'){
					$duihuan_list[$keys]['moneyscore']  = '免费';
				}
				
				
				//兑换码的有效期dayok
				$duihuan_list[$keys]['dayok'] = $duihuan_list[$keys]['youxiaoqi'];//miyao_type
				
				if($duihuan_list[$keys]['status']=='4'){
					
					if($duihuan_list[$keys]['feetype']=='2' || $duihuan_list[$keys]['xushi_type']=='2'){//金额支付 或实物
						
						$duihuan_list[$keys]['endday'] = strtotime($duihuan_list[$keys]['fh_fahuotime'])+ 14*24*60*60;
						$duihuan_list[$keys]['endday'] = date('Y-m-d H:i:s',$duihuan_list[$keys]['endday']);
						
						//是否有效的标识
						if(strtotime($duihuan_list[$keys]['endday'])<time()){
							
							 //更新确认收货状态
							$updatestatus = "update shop_userbuy set status='5',fh_shouhuotime='".date('Y-m-d H:i:s')."' where id='".$duihuan_list[$keys]['id']."' ";
							parent::__get('HyDb')->execute($updatestatus); 
						
							$duihuan_list[$keys]['okflag'] = '29';//失效
							$duihuan_list[$keys]['statusmsg']='已确认';
						}else{
							$duihuan_list[$keys]['okflag'] = '21';//有效
							$duihuan_list[$keys]['comfirmsg']='待确认';
						}
						
						
					}else{
						
						if($duihuan_list[$keys]['dayok']>0 && $duihuan_list[$keys]['dayok']!=''){
							
							//兑换码的过期时间
							$duihuan_list[$keys]['endday'] = strtotime(substr($duihuan_list[$keys]['order_createtime'],0,10))+ $duihuan_list[$keys]['dayok']*24*60*60;
							$duihuan_list[$keys]['endday'] = substr(date('Y-m-d H:i:s',$duihuan_list[$keys]['endday']),0,10);
							
							//是否有效的标识
							if(strtotime($duihuan_list[$keys]['endday'])<strtotime(date('Y-m-d'))){
									
								$duihuan_list[$keys]['okflag'] = '19';//失效
								$duihuan_list[$keys]['statusmsg']='已失效';
							}else{
								$duihuan_list[$keys]['okflag'] = '11';//有效
							}
							
						}
						
					}
					
				}
				
				
				
				//流量单独的状态判断typeid
				if($duihuan_list[$keys]['typeid']=='11' && $duihuan_list[$keys]['status']=='3'){
					$duihuan_list[$keys]['statuss'] = isset($dhorderlistarr[$duihuan_list[$keys]['orderno']])?$dhorderlistarr[$duihuan_list[$keys]['orderno']]:'';//1已使用，5兑换中，9未使用 (只有流量类型才会显示未使用、充值中和已使用的状态)
				}else{
					$duihuan_list[$keys]['statuss']='';
				}
				
				if($duihuan_list[$keys]['statuss']=='1'){
					$duihuan_list[$keys]['statuss']='充值成功';
				}else if($duihuan_list[$keys]['statuss']=='5'){
					$duihuan_list[$keys]['statuss']='充值中';
				}else if($duihuan_list[$keys]['statuss']=='9'){
					$duihuan_list[$keys]['statuss']='未领取流量';
				}else{
					$duihuan_list[$keys]['statuss']='error';
				}
				
				
				//状态 卡密的状态为领取  实物的状态为发货
				//金额支付
				if($duihuan_list[$keys]['status']=='3'){
					if($duihuan_list[$keys]['feetype']=='2'  && $duihuan_list[$keys]['xushi_type']=='2'){
						$duihuan_list[$keys]['statusmsg']='待发货';
					}else{
						$duihuan_list[$keys]['statusmsg']='待领取';
					}
					
				}else if($duihuan_list[$keys]['status']=='4'){
					if($duihuan_list[$keys]['feetype']=='2'  && $duihuan_list[$keys]['xushi_type']=='2'){
						$duihuan_list[$keys]['statusmsg']='已发货';
						$duihuan_list[$keys]['comfirmsg']='待确认';
					}else{
						$duihuan_list[$keys]['statusmsg']='已领取';
					}
				}else if($duihuan_list[$keys]['status']=='5'){//已确认
					$duihuan_list[$keys]['statusmsg']='已确认';
				}else if($duihuan_list[$keys]['status']=='6'){//已删除
					$duihuan_list[$keys]['statusmsg']='已删除';
				}else if($duihuan_list[$keys]['status']=='20'){
					$duihuan_list[$keys]['statusmsg']='支付确认中';
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
			$echoarr['returnmsg']  = '用户类型错误！';
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

?>