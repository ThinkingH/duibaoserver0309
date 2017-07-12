<?php
/*
 * 免费领流量--流量列表的获取
 */
class HyXb702 extends HyXb{
	
	private $type;
	private $mobile;
	
	
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
	
		//接收用户的userid
		$this->userid = isset($input_data['userid']) ? $input_data['userid']:'';  //用户id 
		$this->type  = isset($input_data['type'])?$input_data['type']:'';     //流量的类型 1-移动 2-联通 3-电信
		
		$this->count = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
	
		
		
	
	
	}
	
	
	public function controller_getliulianglist(){
		
		/* //获取用户的手机号
		$phonesql  = "select phone from xb_user where id='".$this->userid."'";
		$phonelist = parent::__get('HyDb')->get_row($phonesql);
		
		$this->mobile = $phonelist['phone'];
		
		
		if($this->mobile==''){//默认显示移动流量
		  $this->type = '1';
		}else{
			//判断手机号属于哪个运营商
		  $this->type = parent::yunyingshangcheck($this->mobile,'num');
		} */
		
		//查询对应的流量数据
		$shangpinsql  = "select id,siteid,typeid,name,price,score,mainpic,
						xiangqingurl,buycount,pingjiacount,gateway,mbps  
						from shop_product 
						where flag=1 and status=1 and gateway='".$this->type."' 
						order by mbps asc, hottypeid asc,orderbyid asc,id desc ";
		$shangpinlist = parent::__get('HyDb')->get_all($shangpinsql); 
		
		foreach ($shangpinlist as $keys=>$vals){
			$shangpinlist[$keys]['mbps'] = $shangpinlist[$keys]['mbps'].'MB('.$shangpinlist[$keys]['score'].'馅饼)';
			$shangpinlist[$keys]['score'] = $shangpinlist[$keys]['score'].'馅饼';
		}
		
		if(count($shangpinlist)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '流量列表获取成功！';
			$echoarr['dataarr'] = $shangpinlist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '流量列表获取为空！';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		
	}
	
	
	
	//操作入口--流量列表的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
		
		
		//类型不能为空
		if($this->type==''){
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '流量类型不能为空！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
	
	
		//流量列表的获取入口
		$this->controller_getliulianglist();
	
		return true;
	}
	
	
	
	
	
	
	
}
