<?php
/*
 * 任务列表获取--161----更加status的输出
 */
class HyXb161 extends HyXb{
	
	
	private $count;
	private $page;
	private $tasktype;
	
	
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
		
		
		$this->tasktype = isset($input_data['tasktype'])? $input_data['tasktype']:'';    //任务分组的标记
		
		$this->count = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
	
	}
	
	
	
	
	//任务列表获取操作
	protected function controller_gettasklist(){
		
		
		//获取用户的类型
		$usertype = parent::__get('xb_usertype');
		
		
		if($this->page=='' || $this->page=='0'){
		
			$this->page=1;
		}
		
		if($this->count=='' || $this->count=='undefined'){
			
			$this->count=10;
		}
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;
		
		$returnarr = array();
		
		//获取总条数
		$tasksumsql  = "select count(*) as num from xb_task where flag=1";
		$tasksumlist = parent::__get('HyDb')->get_all($tasksumsql);
		
		if($tasksumlist[0]['num']>0){
			$returnarr['maxcon'] = $tasksumlist[0]['num'];//总条数
		}else{
			$returnarr['maxcon'] = 0;//总条数
		}
		
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
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
		
		
		//用户分组
		if($this->tasktype=='1'){//最新任务
			
			$nowtime = time();
			
			$tasklist_sql = "select id,flag,type,over_inttime,score,downtimes,scoretimes,name,shuoming,mainimage 
							from xb_task where flag=1 and over_inttime>= '".$nowtime."' order by id desc limit $firstpage,$pagesize";
			$tasklist    = parent::__get('HyDb')->get_all($tasklist_sql);
			
			$seltaskdata_sql  = "select id,taskid,status from $tablename where userid = '".parent::__get('xb_userid')."' ";
			$seltaskdata_list = parent::__get('HyDb')->get_all($seltaskdata_sql);
			
			$checktaskarr = array();
			foreach($seltaskdata_list as $valk) {
				$checktaskarr[$valk['taskid']] = $valk['status'];
			}
			
			foreach($tasklist as $keys => $vals) {
				
				$temptaskid = $tasklist[$keys]['id'];
				
				//图片的展示
				$tasklist[$keys]['mainimage']= XMAINURL.$tasklist[$keys]['mainimage'];
				
				if(!isset($checktaskarr[$temptaskid])){
			
					$tasklist[$keys]['status'] = '10';  //代表任务还没开始
			
				}else {
					//$tasklist[$keys]['status'] =  $checktaskarr[$temptaskid];
					if($checktaskarr[$temptaskid]=='1'){
						$tasklist[$keys]['status'] = '11';  //任务正在下载中，返回前端的状态是11
					}else if($checktaskarr[$temptaskid]=='2'){
						$tasklist[$keys]['status'] = '22';    //游戏正在审核中，任务结束
					}else if($checktaskarr[$temptaskid]=='4'){
						$tasklist[$keys]['status'] = '44';    //奖金已到账
					}else{
						$tasklist[$keys]['status'] = '10';
					}
				}
					
			}
			
		}else if($this->tasktype=='2'){//最高奖赏--按积分的最大值排序
			
			$nowtime = time();
			
			$tasklist_sql = "select id,flag,type,over_inttime,score,downtimes,scoretimes,name,shuoming,mainimage 
							from xb_task where flag=1 and over_inttime>= '".$nowtime."' order by score desc limit $firstpage,$pagesize";
			$tasklist    = parent::__get('HyDb')->get_all($tasklist_sql);
			
			$seltaskdata_sql  = "select id,taskid,status from $tablename where userid = '".parent::__get('xb_userid')."' ";
			$seltaskdata_list = parent::__get('HyDb')->get_all($seltaskdata_sql);
				
			$checktaskarr = array();
			foreach($seltaskdata_list as $valk) {
				$checktaskarr[$valk['taskid']] = $valk['status'];
			}
				
			foreach($tasklist as $keys => $vals) {
				
				//图片的展示
				$tasklist[$keys]['mainimage']= XMAINURL.$tasklist[$keys]['mainimage'];
				
				$temptaskid = $tasklist[$keys]['id'];
				if(!isset($checktaskarr[$temptaskid])){
						
					$tasklist[$keys]['status'] = '10';  //代表任务还没开始
						
				}else {
					//$tasklist[$keys]['status'] =  $checktaskarr[$temptaskid];
					if($checktaskarr[$temptaskid]=='1'){
						$tasklist[$keys]['status'] = '11';  //任务正在下载中，返回前端的状态是11
					}else if($checktaskarr[$temptaskid]=='2'){
						$tasklist[$keys]['status'] = '22';    //游戏正在审核中，任务结束
					}else if($checktaskarr[$temptaskid]=='4'){
						$tasklist[$keys]['status'] = '44';    //奖金已到账
					}else{
						$tasklist[$keys]['status'] = '10';
					}
				}
					
			}
			
		}else if($this->tasktype=='3'){//未完成的任务---只显示该用户未做的
			
			
			$seltaskdata_sql  = "select id,taskid,status from $tablename where userid = '".parent::__get('xb_userid')."' ";
			$seltaskdata_list = parent::__get('HyDb')->get_all($seltaskdata_sql);
			
			
			$inarr = array();
			foreach ($seltaskdata_list as $val){
			
				if(is_numeric($val['taskid'])) {
					array_push($inarr,$val['taskid']);
				}
			}
			
			if(empty($inarr) ){
				
				$where = 'flag=1';
			}else{
				$instr = ' ('.implode(',',$inarr).') ';
				$where = 'flag=1 and id not in'. $instr;
				
			}
			
			$nowtime = time();
			$tasklist_sql = "select id,flag,type,over_inttime,score,downtimes,scoretimes,name,shuoming,mainimage from 
							xb_task where $where  and over_inttime >= '".$nowtime."'order by id desc limit $firstpage,$pagesize";
			//$tasklist_sql = "select id,flag,type,over_inttime,score,downtimes,scoretimes,name,shuoming,mainimage from xb_task where flag=1 and id not in $instr order by id desc limit $firstpage,$pagesize";
			$tasklist    = parent::__get('HyDb')->get_all($tasklist_sql);
			
			foreach ($tasklist as $keys => $vals){
				
				//图片的展示
				$tasklist[$keys]['mainimage']= XMAINURL.$tasklist[$keys]['mainimage'];
				
				$tasklist[$keys]['status'] = '10';
			}
			
		}
		
		
		if(count($tasklist)>0){
				
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '任务列表获取成功';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $tasklist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
				
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '任务列表为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	}
	
	
	
	//操作入口--任务列表的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='161'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		
		//判断任务传递的类型
		$shuzu = array('1','2','3');
		
		if(!in_array($this->tasktype,$shuzu)){
		
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '用户传递的任务分组参数错误！';
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
		
	
		//任务列表的获取入口
		$this->controller_gettasklist();
	
		return true;
	}
	
	
	
}
