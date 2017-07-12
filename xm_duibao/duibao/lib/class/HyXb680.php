<?php
/*
 * 订单记录
 */
class HyXb680 extends HyXb{


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
		
		$where = '';
		
		if($this->kindtype=='1'){
			$where = " userid='".parent::__get('xb_userid')."' and shop_userbuy.status='3'  and shop_userbuy.typeid='22' ";
		}else if($this->kindtype=='2'){
			$where = " userid='".parent::__get('xb_userid')."' and shop_userbuy.status='4' ";
		}else if($this->kindtype=='3'){
			$where = " userid='".parent::__get('xb_userid')."' and shop_userbuy.pingjia='9' and shop_userbuy.status='4' ";
		}


		if($this->page=='' || $this->page=='0'){

			$this->page=1;
		}

		if($this->count=='' || $this->count== 'undefined'){
				
			$this->count=10;
		}

		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;

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
		
		$shangjiaarr = array();
		$shangjiaqqarr = array();
		
		//商家信息
		$shangjiasql = "select * from shop_site where flag=1 and checkstatus=3 ";
		$shangjialist = parent::__get('HyDb')->get_all($shangjiasql);
		
		foreach ($shangjialist as $keys=>$vals){
			
			$shangjiaarr[$shangjialist[$keys]['id']] = $shangjialist[$keys]['storename'];//商户名
			$shangjiaqqarr[$shangjialist[$keys]['id']] = $shangjialist[$keys]['qq'];//商户名
			
			
		}
		
		
		$duihuan_sql  = "select shop_userbuy.*,shop_product.mainpic,shop_product.pickup from shop_userbuy,shop_product where $where and shop_userbuy.productid = shop_product.id order by id desc limit $firstpage,$pagesize";
		echo $duihuan_sql;
		$duihuan_list = parent::__get('HyDb')->get_all($duihuan_sql);
		
		foreach ($duihuan_list as $keys=>$vals){
			
			$typeid = substr($duihuan_list[$keys]['typeid'],0,1);//判断是虚拟的还是实物
			
			if($typeid=='1'){
				$duihuan_list[$keys]['qflag']='1';//自营商户
			}else if($typeid=='2'){
				$duihuan_list[$keys]['qflag']='2';//实体
			}else if($duihuan_list[$keys]['siteid']=='1000' && $typeid=='1'){
				$duihuan_list[$keys]['qflag']='3';//虚拟
			}
			
			if($duihuan_list[$keys]['siteid']=='1000'){
				$duihuan_list[$keys]['flag']='自营商品';//自营商户
			}else{
				$duihuan_list[$keys]['flag']='入驻商户商品';//商家入驻商户
			}
			
			
			if($duihuan_list[$keys]['typeid']=='11'|| $duihuan_list[$keys]['typeid']=='13'){
				$duihuan_list[$keys]['tflag']='1';//单秘钥
			}else if($duihuan_list[$keys]['typeid']=='22'){
				$duihuan_list[$keys]['tflag']='2';//实物类型
			}else if($duihuan_list[$keys]['typeid']=='14'){
				$duihuan_list[$keys]['tflag']='3';//多秘钥
			}else{
				$duihuan_list[$keys]['tflag']='4';//其他
			}
			
			$duihuan_list[$keys]['storename'] = $shangjiaarr[$duihuan_list[$keys]['siteid']];//商户名称
			$duihuan_list[$keys]['qq']        = $shangjiaqqarr[$duihuan_list[$keys]['siteid']];//商户qq
			
			$duihuan_list[$keys]['moneyscore']  = '￥'.$duihuan_list[$keys]['price'].'+'.$duihuan_list[$keys]['score'].'馅饼';
			
			if($duihuan_list[$keys]['pickup']=='1'){
				$duihuan_list[$keys]['pickup']='自提';
			}else if($duihuan_list[$keys]['pickup']=='2'){
				$duihuan_list[$keys]['pickup']='网上兑换';
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