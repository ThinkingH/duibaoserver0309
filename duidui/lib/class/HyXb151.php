<?php
/*
 * 用户积分记录列表的获取
 */
class HyXb151 extends HyXb{
	
	private $count;//每页的条数，数值介于1到20之间
	private $page;//数据请求对应的页数
	
	
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
	
	
	//获取用户积分的主要操作
	protected  function controller_getuserscore(){
		
		
		//获取用户登录的类型
		$usertype = parent::__get('xb_usertype');
		
		if($this->page==''||$this->page=='0'){
		
			$this->page=1;
		}
		
		if($this->count=='' || $this->count== 'undefined'){
			
			$this->count=10;
		}
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;
		
		$returnarr = array();
		
		//正常用户的登录查看积分--xb_user_score
		if($usertype=='1'){
			
			
			//查询该用户的总条数
			$usernumscore_sql  = "select count(*) as num from xb_user_score where userid='".parent::__get('xb_userid')."' ";
			$usernumscore_list = parent::__get('HyDb')->get_all($usernumscore_sql);
			
			if(count($usernumscore_list)>0){
				$returnarr['maxcon'] = $usernumscore_list[0]['num'];//总条数
			}else{
				$returnarr['maxcon'] = 0;
			}
			
			//总页数
			$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
			
			$userscore_sql  = "select id,type,score,gettime,getdescribe from xb_user_score 
					where userid='".parent::__get('xb_userid')."' 
					order by id desc limit $firstpage,$pagesize";
			$userscore_list = parent::__get('HyDb')->get_all($userscore_sql);
			
			foreach ($userscore_list as $keys=>$val){
				$userscore_list[$keys]['gettime'] = date('Y-m-d H:i:s',$userscore_list[$keys]['gettime']);
				
				if($userscore_list[$keys]['type']=='1'){
					$userscore_list[$keys]['score'] = '+'.$userscore_list[$keys]['score'];
				}else if($userscore_list[$keys]['type']=='9'){
					$userscore_list[$keys]['score'] = '-'.$userscore_list[$keys]['score'];
				}
				
				
			}
			
			if(count($userscore_list)>0){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '用户积分列表获取成功';
				$echoarr['maxcon']  = $returnarr['maxcon'];
				$echoarr['sumpage'] = $returnarr['sumpage'];
				$echoarr['nowpage'] = $this->page;
				$echoarr['dataarr'] = $userscore_list;
				
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '用户积分列表为空';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}else if($usertype=='2'){//匿名用户登录查看
			
			
			//查询该用户的总条数
			$usernumscore_sql  = "select count(*) as num from xb_temp_user_score where userid='".parent::__get('xb_userid')."' ";
			$usernumscore_list = parent::__get('HyDb')->get_all($usernumscore_sql);
				
			if(count($usernumscore_list)>0){
				$returnarr['maxcon'] = $usernumscore_list[0]['num'];//总条数
			}else{
				$returnarr['maxcon'] = 0;
			}
				
			//总页数
			$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
			
			
			$userscore_sql  = "select id,type,score,gettime,getdescribe from xb_temp_user_score 
					where userid='".parent::__get('xb_userid')."' 
					order by id desc limit $firstpage,$pagesize";
			$userscore_list = parent::__get('HyDb')->get_all($userscore_sql);
			
			foreach ($userscore_list as $keys=>$val){
				$userscore_list[$keys]['gettime'] = date('Y-m-d H:i:s',$userscore_list[$keys]['gettime']);
				
				if($userscore_list[$keys]['type']=='1'){
					$userscore_list[$keys]['score'] = '+'.$userscore_list[$keys]['score'];
				}else if($userscore_list[$keys]['type']=='9'){
					$userscore_list[$keys]['score'] = '-'.$userscore_list[$keys]['score'];
				}
			}
			
			if(count($userscore_list)>0){
				
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '用户积分列表获取成功';
				$echoarr['maxcon']  = $returnarr['maxcon'];
				$echoarr['sumpage'] = $returnarr['sumpage'];
				$echoarr['nowpage'] = $this->page;
				$echoarr['dataarr'] = $userscore_list;
				
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
				
			}else{
				
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '用户积分列表为空';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该用户传递的用户类型参数错误，用户积分列表获取失败';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
	}
	
	
	
	//操作入口--用户积分记录列表的获取
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
			$echoarr['returnmsg']  = '该用户传递的用户类型参数错误，用户积分列表获取失败';
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
		
		//用户积分记录列表的获取入口
		$this->controller_getuserscore();
	
		return true;
	}
	
	
}