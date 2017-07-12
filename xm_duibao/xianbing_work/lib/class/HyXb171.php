<?php
/*
 * 推送信息获取
 */

class HyXb171 extends HyXb{
	
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
	
	
	//信息的推送
	protected function controller_tuisongmessage(){
		
		$usertype = parent::__get('xb_usertype');
		
		if($this->page=='' || $this->page=='0'){
		
			$this->page=1;
		}
		
		if($this->count=='' || $this->count=='undefined'){
				
			$this->count=10;
		}
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;
		
		
		if($usertype=='1'){
			
			$tablename = 'xb_user_tuisong';
			
		}else if($usertype=='2'){
			
			$tablename = 'xb_temp_user_tuisong';
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该用户的用户类型参数错误，信息推送失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		$returnarr = array();
		
		//分页的实现
		$tuisongsumsql  = "select count(*) as num from $tablename where userid='".parent::__get('xb_userid')."' ";
		$tuisongsumsqlist = parent::__get('HyDb')->get_all($tuisongsumsql);
		
		if($tuisongsumsqlist[0]['num']>0){
			$returnarr['maxcon'] = $tuisongsumsqlist[0]['num'];//总条数
		}else{
			$returnarr['maxcon'] = 0;//总条数
		}
		
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		
		
		$temptuisongsql  = "select id,type,status,taskid,message,create_inttime from $tablename where userid='".parent::__get('xb_userid')."' 
				order by create_inttime desc";
		$temltuisonglist = parent::__get('HyDb')->get_all($temptuisongsql);
		
		
		/* $inarr = '';
		foreach ($temltuisonglist as $val){
		
			if(is_numeric($val['id'])) {
				array_push($inarr,$val['id']);
			}
		
			//把时间戳更新为时间格式
			$val['create_inttime'] = date("Y-m-d H:i:s",$val['create_inttime']);
		}
		$instr = ' ('.implode(',',$inarr).') '; */
		foreach ($temltuisonglist as $keys => $vals){
			
			$temltuisonglist[$keys]['create_inttime'] = date("Y-m-d H:i:s",$temltuisonglist[$keys]['create_inttime']);
		}
		
		
		if(count($temltuisonglist)>0){
		
			/* $deltuisong_sql = "delete from xb_user_tuisong where id in ".$instr;
			 $deltuisong_list = parent::__get('HyDb')->execute($deltuisong_sql);
			 parent::hy_log_str_add(HyItems::hy_trn2space($deltuisong_sql)."\n"); */
		
		
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '信息推送成功';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $temltuisonglist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '推送信息为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
	}
	
	
	
	
	
	//操作入口--推送信息获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
		$shuzu = array('1','2');
		
		if(!in_array(parent::__get('xb_usertype'),$shuzu)){
				
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该用户传递的用户类型参数错误，用户积分记录列表获取失败';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//操作类型的判断
		if(parent::__get('xb_thetype')!='171'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		
		
	
		//推送信息入口
		$this->controller_tuisongmessage();
	
		return true;
	}
	
	
}