<?php
/*
 * 留言列表和回复列表的获取
 */
class HyXb811 extends HyXb{
	
	
	private $quanid; //优惠券id
	private $type; //1-留言2-回复留言 3-删除
	private $userid;   //楼层id
	
	private $count;
	private $page;
	private $cid;
	
	
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
	
		
		$this->quanid  = isset($input_data['quanid'])? $input_data['quanid']:'';    //优惠券id
		$this->type = isset($input_data['type'])? $input_data['type']:'';    //留言内容
// 		$this->userid = isset($input_data['userid']) ? $input_data['userid']:'';   //板块id
		
		$this->count = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
		
		$this->cid  = isset($input_data['cid'])?$input_data['cid']:'';     
		
	}
	
	
	public function liuyanlist(){
		
		
		if($this->type=='1'){//留言列表的获取
	
			//分页
			if($this->page=='' || $this->page=='0' || $this->page=='undefined' ){
				$this->page=1;
			}
				
			if($this->count=='' || $this->count=='undefined' || $this->count=='0' ){
				$this->count=10;
			}
			
			
			$firstpage = ($this->page-1)*$this->count;
			$pagesize  = $this->count;
				
			$returnarr = array();
				
			//回复列表数据
			$replay_sql  = "select count(*) as num1 from `xb_subcomment` where quanid='".$this->quanid."' ";
			$replay_list = parent::__get('HyDb')->get_row($replay_sql); 
			
			//分类数据的查询
			$typesql  = "select count(*) as num from xb_comment where quanid='".$this->quanid."' ";
			$typelist = parent::__get('HyDb')->get_all($typesql);
			
			if($typelist[0]['num']>0){
				$returnarr['maxcon'] = $typelist[0]['num'];
			}else{
				$returnarr['maxcon'] = 0;
			}
			
			//总页数
			$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
			
			//总的回复数
			$sumreplay = $replay_list['num1']+$typelist[0]['num'];
			
			
			$usertouxiangarr = array();//头像地址
			$usernamearr = array();//昵称
			
			
			$useridsql = "select * from xb_comment where quanid='".$this->quanid."' limit $firstpage,$pagesize ";
			$useridlist = parent::__get('HyDb')->get_all($useridsql); 
			
			
			$allarray = array();
			
			
			$inarr = array();
			$kkk = 0;
			foreach ($useridlist as $val){
				
				++$kkk;
				
				//获取留言的用户id
				if(is_numeric($val['userid'])) {
					array_push($inarr,$val['userid']);
				}
				
				
				$cid = $val['id'];//主留言编号
				$sql_getchild = "select * from xb_subcomment where cid='".$cid."' order by id desc limit 5";
				$list_getchild = parent::__get('HyDb')->get_all($sql_getchild); 
				
				
				$allarray[$kkk]['mainlist'] = $val;
				$allarray[$kkk]['childlist'] = $list_getchild;
				
				
				foreach($list_getchild as $valg) {
					array_push($inarr,$valg['senderid']);
					array_push($inarr,$valg['receiverid']);
				}
				
				
			}
			
			$inarr = array_unique($inarr);
			if(count($inarr)<=0){
			
				$where = 'id=0';
			}else{
				$instr = ' ('.implode(',',$inarr).') ';
				$where = ' id in '. $instr;
			
			}
			
			
			
			
			//获取用户列表
			$usersql  = "select id,nickname,touxiang from xb_user where $where ";//$usertask_list[$key]['id']       = $taskidarr[$usertask_list[$key]['taskid']];
			$userlist = parent::__get('HyDb')->get_all($usersql); 
			
			foreach ($userlist as $keys=>$vals){
				
				$usertouxiangarr[$vals['id']]  = $vals['touxiang'];
				$usernamearr[$vals['id']]      = $vals['nickname'];
				
			}
			
			
			
			
			foreach($allarray as $keyaa => $valaa) {
				
				
				//主留言头像
				$allarray[$keyaa]['mainlist']['touxiang'] = $usertouxiangarr[$allarray[$keyaa]['mainlist']['userid']];
				$allarray[$keyaa]['mainlist']['nickname'] = $usernamearr[$allarray[$keyaa]['mainlist']['userid']];
				
				//留言的时间
				if((time()-strtotime($allarray[$keyaa]['mainlist']['createtime']))<1*60*60){
						
					$allarray[$keyaa]['mainlist']['createtime']='刚刚';
						
				}else if((time()-strtotime($allarray[$keyaa]['mainlist']['createtime']))<1*24*60*60){
						
					$allarray[$keyaa]['mainlist']['createtime']=intval(((time()-strtotime($allarray[$keyaa]['mainlist']['createtime']))/3600)).'小时前';//算出小时
						
				}else {
						
					$allarray[$keyaa]['mainlist']['createtime'] = intval(((time()-strtotime($allarray[$keyaa]['mainlist']['createtime']))/86400)).'天前';//算出多少天前
				}
				
				
				//子留言信息
				foreach($allarray[$keyaa]['childlist'] as $keyccc => $valccc) {
					
					$allarray[$keyaa]['childlist'][$keyccc]['stouxiang'] = $usertouxiangarr[$allarray[$keyaa]['childlist'][$keyccc]['senderid']];
					$allarray[$keyaa]['childlist'][$keyccc]['snickname'] = $usernamearr[$allarray[$keyaa]['childlist'][$keyccc]['senderid']];
					
					$allarray[$keyaa]['childlist'][$keyccc]['rtouxiang'] = $usertouxiangarr[$allarray[$keyaa]['childlist'][$keyccc]['receiverid']];
					$allarray[$keyaa]['childlist'][$keyccc]['rnickname'] = $usernamearr[$allarray[$keyaa]['childlist'][$keyccc]['receiverid']];
					
					//留言的时间
					if((time()-strtotime($allarray[$keyaa]['childlist'][$keyccc]['createtime']))<1*60*60){
					
						$allarray[$keyaa]['childlist'][$keyccc]['createtime']='刚刚';
					
					}else if((time()-strtotime($allarray[$keyaa]['childlist'][$keyccc]['createtime']))<1*24*60*60){
					
						$allarray[$keyaa]['childlist'][$keyccc]['createtime']=intval(((time()-strtotime($allarray[$keyaa]['childlist'][$keyccc]['createtime']))/3600)).'小时前';//算出小时
					
					}else {
					
						$allarray[$keyaa]['childlist'][$keyccc]['createtime'] = intval(((time()-strtotime($allarray[$keyaa]['childlist'][$keyccc]['createtime']))/86400)).'天前';//算出多少天前
					}
					
				}
				
				
			}
			
			
			if(count($allarray)>0){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '留言列表获取成功';
				$echoarr['maxcon']  = $returnarr['maxcon'];//总条数
				$echoarr['sumpage'] = $returnarr['sumpage'];
				$echoarr['nowpage'] = $this->page;
				$echoarr['maxnum'] = $sumreplay;
				$echoarr['dataarr'] = $allarray;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '留言列表获取为空';
				$echoarr['dataarr'] = array();
				$echoarr['maxnum'] = 0;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}else if($this->type=='2'){
			
			
			//获取回复的全部数据
			$subcomment_sql  = "select * from xb_subcomment where cid='".$this->cid."' order by id desc  ";
			$subcomment_list = parent::__get('HyDb')->get_all($subcomment_sql); 
			
			$inarr = array();
			
			foreach ($subcomment_list as $val){
				
				//获取留言的用户id
				if(is_numeric($val['senderid'])) {
					array_push($inarr,$val['senderid']);
				}
				//获取留言的用户id
				if(is_numeric($val['receiverid'])) {
					array_push($inarr,$val['receiverid']);
				}
				
			}
			
			$inarr = array_unique($inarr);
			if(count($inarr)<=0){
			
				$where = 'id=0';
			}else{
				$instr = ' ('.implode(',',$inarr).') ';
				$where = ' id in '. $instr;
			}
			
			$usertouxiangarr = array();//头像地址
			$usernamearr = array();//昵称
			
			
			//获取用户列表
			$usersql  = "select id,nickname,touxiang from xb_user where $where ";//$usertask_list[$key]['id']       = $taskidarr[$usertask_list[$key]['taskid']];
			$userlist = parent::__get('HyDb')->get_all($usersql);
				
			foreach ($userlist as $keys=>$vals){
			
				$usertouxiangarr[$vals['id']]  = $vals['touxiang'];
				$usernamearr[$vals['id']]      = $vals['nickname'];
			
			}
			
			
			foreach ($subcomment_list as $keys=>$vals){
				
				
				$subcomment_list[$keys]['stouxiang'] = isset($usertouxiangarr[$subcomment_list[$keys]['senderid']])?$usertouxiangarr[$subcomment_list[$keys]['senderid']]:'';
				$subcomment_list[$keys]['snickname'] = isset($usernamearr[$subcomment_list[$keys]['senderid']])?$usernamearr[$subcomment_list[$keys]['senderid']]:'';
				
				$subcomment_list[$keys]['rtouxiang'] = isset($usertouxiangarr[$subcomment_list[$keys]['receiverid']])?$usertouxiangarr[$subcomment_list[$keys]['receiverid']]:'';
				$subcomment_list[$keys]['rnickname'] = isset($usernamearr[$subcomment_list[$keys]['receiverid']])?$usernamearr[$subcomment_list[$keys]['receiverid']]:'';
				
				
				//留言的时间
				if((time()-strtotime($subcomment_list[$keys]['createtime']))<1*60*60){
						
					$subcomment_list[$keys]['createtime']='刚刚';
						
				}else if((time()-strtotime($subcomment_list[$keys]['createtime']))<1*24*60*60){
						
					$subcomment_list[$keys]['createtime']=intval(((time()-strtotime($subcomment_list[$keys]['createtime']))/3600)).'小时前';//算出小时
						
				}else {
						
					$subcomment_list[$keys]['createtime'] = intval(((time()-strtotime($subcomment_list[$keys]['createtime']))/86400)).'天前';//算出多少天前
				}
				
				
				
			}
			
			
			if(count($subcomment_list)>0){
				
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '回复列表获取成功';
				$echoarr['dataarr'] = $subcomment_list;
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
				
			}else{
				
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '回复列表为空';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
			
		}
		
		
	}
	
	
	//操作入口
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
	/* 	//优惠券id
		if($this->quanid==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '优惠券id不能为空！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		} */
		
		
		
// 		//留言系统
// 		$shuzu = array('1','2','3');
		
// 		if(!in_array($this->type,$shuzu)){
		
// 			$echoarr = array();
// 			$echoarr['returncode'] = 'error';
// 			$echoarr['returnmsg']  = '类型参数传递错误！';
// 			$echoarr['dataarr']    = array();
// 			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
// 			parent::hy_log_str_add($logstr);
// 			echo json_encode($echoarr);
// 			return false;
// 		}
	
	
		$this->liuyanlist();
		
	
		return true;
	}
	
	
	
	
	
	
}