<?php
/*
 * 详情页获取接口---淘宝联盟
 */

class HyXb6608 extends HyXb{
	
	
	private $quanid;
	
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
	
		$this->quanid = isset($input_data['quanid'])? $input_data['quanid']:'';  //首页商品类型
	
	}
	
	
	public function controller_shopid(){
		
		
		//商品数据列表的获取
		$shangpinsql  = "select id,hyflag,new_datetime,type,maintype,childtype,title,
						picurl,spicurl,yuanurl,taourl,quanprice,nowprice,yuanprice from z_quantaobaoke 
						where flag=1 and id='".$this->quanid."' ";
		$shangpinlist = parent::__get('HyDb')->get_all($shangpinsql);
			
			
		if(count($shangpinlist)>0){
		
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '详细内容获取成功!';
			$echoarr['dataarr'] = $shangpinlist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '详细内容获取为空!';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}
		
		
		
	}
	
	
	
	
	//操作入口--首页类型的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
		//商品id的判断
		if($this->quanid==''){
	
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '商品id不能为空！';
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
			$echoarr['returnmsg']  = '每页展示的条数超过20条';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
	
		}
	
	
		//首页分类
		$this->controller_shopid();
	
		return true;
	}
	
	
}