<?php
/*
 * 我的发布列表的获取
 */
class HyXb807 extends HyXb{
	
	
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
	
	
	public function controller_myfabu(){
		
		
		if($this->page=='' || $this->page=='0' || $this->page='undefined'){
			$this->page=1;
		}
		
		if($this->count=='' || $this->count=='undefined' || $this->count=='0'){
			$this->count=10;
		}
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->count;
		
		$returnarr = array();
		
		//分类数据的查询
		$typesql  = "select count(*) as num from z_tuanmainlist where faflag=1 and shstatus='11' and userid='".parent::__get('xb_userid')."' ";
		$typelist = parent::__get('HyDb')->get_all($typesql);
		
		if($typelist[0]['num']>0){
			$returnarr['maxcon'] = $typelist[0]['num'];
		}else{
			$returnarr['maxcon'] = 0;
		}
		
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		$typenamearr = array();
		
		$shoptypesql  = "select * from z_tuanmainlist where faflag=1  and userid='".parent::__get('xb_userid')."'  order by id desc limit $firstpage,$pagesize ";
		$shoptypelist = parent::__get('HyDb')->get_all($shoptypesql);
		
		foreach ($shoptypelist as $keys=>$vals){
			
			if($shoptypelist[$keys]['shstatus']=='11'){
				$shoptypelist[$keys]['shstatus']='已成功';
			}else if($shoptypelist[$keys]['shstatus']=='99'){
				$shoptypelist[$keys]['shstatus']='审核中';
			}else if($shoptypelist[$keys]['shstatus']=='9'){
				$shoptypelist[$keys]['shstatus']='未通过';
			}
			
		}
		
		if(count($shoptypelist)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '我的发布数据获取成功';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $shoptypelist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '我的发布数据获取列表为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	}
	
	
	//我的发布列表操作入口
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
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
	
	
		$this->controller_myfabu();
	
		return true;
	}
	
}