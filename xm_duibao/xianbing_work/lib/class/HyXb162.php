<?php
/*
 * 任务详细数据获取--162
 */
class HyXb162 extends HyXb{
	 
	private $taskid; 
	private $taskmianze;//任务页的免责声明"
	private $picurlpath;
	
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
	
	//获取任务id编号
	$this->taskid = isset($input_data['taskid']) ? $input_data['taskid']:'';  //
	$this->taskmianze = '任务页的免责声明';
	$this->picurlpath = XMAINURL;
	}
		
	
	//任务详细列表的获取
	protected function controller_getdetailtasklist(){
		
		//获取用户的类型
		$usertype = parent::__get('xb_usertype');
		
		if($usertype=='1'){
				
			$tablename = 'xb_user_task';
				
		}else if($usertype=='2'){
				
			$tablename = 'xb_temp_user_task';
				
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该用户传递的用户类型参数错误';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		$taskidlistsql = "select * from xb_task where id='".$this->taskid."' ";
		$taskidlist    = parent::__get('HyDb')->get_row($taskidlistsql);
		
		$seltaskdata_sql  = "select id,taskid,status from $tablename where userid = '".parent::__get('xb_userid')."' and taskid = '".$this->taskid."' limit 1";
		$seltaskdata_list = parent::__get('HyDb')->get_row($seltaskdata_sql);
		
		
		if($seltaskdata_list['status']=='1'){
			$seltaskdata_list['status'] = '11';
		}else if($seltaskdata_list['status']=='2'){
			$seltaskdata_list['status'] = '22';
		}else if($seltaskdata_list['status']=='4'){
			$seltaskdata_list['status'] = '44';
		}else{
			$seltaskdata_list['status'] = '10';
		}
		
		
		
		if(count($taskidlist)>0){
			
			
			$liuchengarr = array();
			if($taskidlist['liucheng_1_img']!='') {
				$temparr = array(
						'img'     => $this->picurlpath.$taskidlist['liucheng_1_img'],
						'title'   => $taskidlist['liucheng_1_title'],
						'miaoshu' => $taskidlist['liucheng_1_miaoshu'],
				);
				array_push($liuchengarr,$temparr);
			}
			if($taskidlist['liucheng_2_img']!='') {
				$temparr = array(
						'img'     => $this->picurlpath.$taskidlist['liucheng_2_img'],
						'title'   => $taskidlist['liucheng_2_title'],
						'miaoshu' => $taskidlist['liucheng_2_miaoshu'],
				);
				array_push($liuchengarr,$temparr);
			}
			if($taskidlist['liucheng_3_img']!='') {
				$temparr = array(
						'img'     => $this->picurlpath.$taskidlist['liucheng_3_img'],
						'title'   => $taskidlist['liucheng_3_title'],
						'miaoshu' => $taskidlist['liucheng_3_miaoshu'],
				);
				array_push($liuchengarr,$temparr);
			}
			if($taskidlist['liucheng_4_img']!='') {
				$temparr = array(
						'img'     => $this->picurlpath.$taskidlist['liucheng_4_img'],
						'title'   => $taskidlist['liucheng_4_title'],
						'miaoshu' => $taskidlist['liucheng_4_miaoshu'],
				);
				array_push($liuchengarr,$temparr);
			}
			if($taskidlist['liucheng_5_img']!='') {
				$temparr = array(
						'img'     => $this->picurlpath.$taskidlist['liucheng_5_img'],
						'title'   => $taskidlist['liucheng_5_title'],
						'miaoshu' => $taskidlist['liucheng_5_miaoshu'],
				);
				array_push($liuchengarr,$temparr);
			}
			
			
			//获取任务的详细信息
			$dataarr = array(
					'id'        => $taskidlist['id'],
					'flag'      => $taskidlist['flag'],
					'type'      => $taskidlist['type'],
					'over_time' => $taskidlist['over_inttime'],
					'score'     => $taskidlist['score'],
					'taskmianze'  => $this->taskmianze,
					'mainimage'   => $this->picurlpath.$taskidlist['mainimage'],
					'downtimes'    => $taskidlist['downtimes'],
					'scoretimes'   => $taskidlist['scoretimes'],
					'scoretimes'   => $taskidlist['scoretimes'],
					'name'         => $taskidlist['name'],
					'shuoming'       => $taskidlist['shuoming'],
					'huodonggaishu'  => $taskidlist['huodonggaishu'],
					'huodongguize'   => $taskidlist['huodongguize'],
					'downurl'        => $taskidlist['downurl'],
					'iosdownurl'        => $taskidlist['iosdownurl'],
					'showurl'        => $taskidlist['showurl'],
					'status'         => $seltaskdata_list['status'],
					'liuchengarr'  => $liuchengarr,
					
					
			);
			
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '任务详细列表获取成功';
			$echoarr['dataarr'] = $dataarr;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n".var_export($dataarr,1); //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '任务详细列表获取失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
	}
	
	
	
	//操作入口--任务详细列表的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='162'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		//任务id编号的获取
		if($this->taskid==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '任务id编号不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg'].'-----'.$echoarr['dataarr']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		//任务详细列表的获取
		$this->controller_getdetailtasklist();
	
		return true;
	}
	
}