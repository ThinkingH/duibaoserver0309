<?php
/*
 * 用户兑换记录列表的获取--152
 */
class HyXb152 extends HyXb{
	
	
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
	
	}
	
	
	//用户积分兑换的主要操作
	protected function controller_duihuanscore(){
		
		
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
		$duihuansumsql  = "select count(*) as num from shop_userbuy where userid='".parent::__get('xb_userid')."' ";
		$duihuansumlist = parent::__get('HyDb')->get_all($duihuansumsql); 
		
		if(count($duihuansumlist)>0){
			$returnarr['maxcon'] = $duihuansumlist[0]['num'];//总条数
		}else{
			$returnarr['maxcon'] = 0;
		}
		
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		//兑换记录的查询
		/* $duihuan_sql  = "select id,mytype,flag,keystr,key_create_datetime,score,orderno,userid,goods_id,name,content,over_createdate from shop_userbuy 
						where userid='".parent::__get('xb_userid')."' 
						order by id desc limit $firstpage,$pagesize";
		$duihuan_list = parent::__get('HyDb')->get_all($duihuan_sql); */
		
		
		$duihuan_sql  = "select * from shop_userbuy where userid='".parent::__get('xb_userid')."' and typeid='11' order by id desc limit $firstpage,$pagesize";
		$duihuan_list = parent::__get('HyDb')->get_all($duihuan_sql);
		
		
		//关联查询flag
		$dh_orderlist_sql  = "select userid,flag,orderno from dh_orderlist where userid='".parent::__get('xb_userid')."'";
		$dh_orderlist_list = parent::__get('HyDb')->get_all($dh_orderlist_sql);
		
		$dhorderlistarr = array();
		
		foreach ($dh_orderlist_list as $vals){
			$dhorderlistarr[$vals['orderno']] = $vals['flag'];
			
		}
		
		$arr = array();
		foreach ($duihuan_list as $keys => $vals){
			
			if(substr($duihuan_list[$keys]['typeid'],0,2)=='11'){
				if(isset($dhorderlistarr[$duihuan_list[$keys]['orderno']])){
					$duihuan_list[$keys]['status'] = $dhorderlistarr[$duihuan_list[$keys]['orderno']];
				}
				
				$temper   = array(
								'id'   => $duihuan_list[$keys]['id'],
								'flag' => $duihuan_list[$keys]['status'],
								'key_create_datetime' => $duihuan_list[$keys]['order_createtime'],
								'score' => '-'.$duihuan_list[$keys]['score'],
								'price' => $duihuan_list[$keys]['price'],
								'orderno' => $duihuan_list[$keys]['orderno'],
								'userid' => $duihuan_list[$keys]['userid'],
								'name' => $duihuan_list[$keys]['name'],
								'keystr' => $duihuan_list[$keys]['keystr'],
				
				);
				array_push($arr,$temper);
				
			}else if(substr($duihuan_list[$keys]['typeid'],0,2)=='12'){//实物
				if($duihuan_list[$keys]['status']=='3'){
				
					$duihuan_list[$keys]['status']=='未使用';
				}
				
				$temper = array(
								'id'   => $duihuan_list[$keys]['id'],
								'flag' => $duihuan_list[$keys]['status'],
								'key_create_datetime' => $duihuan_list[$keys]['order_createtime'],
								'score' => '-'.$duihuan_list[$keys]['score'],
								'price' => $duihuan_list[$keys]['price'],
								'orderno' => $duihuan_list[$keys]['orderno'],
								'userid' => $duihuan_list[$keys]['userid'],
								'name' => $duihuan_list[$keys]['name'],
								'fh_phone' => $duihuan_list[$keys]['fh_phone'],
								'fh_address' => $duihuan_list[$keys]['fh_address'],
								'fh_shouhuotime' => $duihuan_list[$keys]['fh_shouhuotime'],
								'fh_shouhuoren' => $duihuan_list[$keys]['fh_shouhuoren'],
				
				);
				
				array_push($arr,$temper);
				
			}else if(substr($duihuan_list[$keys]['typeid'],0,2)=='13'){//卡密
				
				$duihuan_list[$keys]['keystr'] = '|'.$duihuan_list[$keys]['goods_id'].'|'.$duihuan_list[$keys]['keystr'];
				$temper   = array(
								'id'   => $duihuan_list[$keys]['id'],
								'flag' => $duihuan_list[$keys]['status'],
								'key_create_datetime' => $duihuan_list[$keys]['order_createtime'],
								'score' => '-'.$duihuan_list[$keys]['score'],
								'price' => $duihuan_list[$keys]['price'],
								'orderno' => $duihuan_list[$keys]['orderno'],
								'userid' => $duihuan_list[$keys]['userid'],
								'name' => $duihuan_list[$keys]['name'],
								'keystr' => $duihuan_list[$keys]['keystr'],
				
				);
				
				array_push($arr,$temper);
			}
			
		}
		
		
		
		
		if(count($duihuan_list)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '用户兑换记录列表获取成功';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $arr;
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
	
	
	
	//操作入口--用户兑换记录列表的获取
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
		
		
		//用户积分的兑换获取入口
		$r = $this->controller_duihuanscore();
		if($r===false) {
			return false;
		}
	
		return true;
		
		
	}
	
}