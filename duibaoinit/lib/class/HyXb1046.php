<?php
/*
 * 附近数据评论列表数据的获取
 */
class HyXb1046 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $orderid;  //订单id
	
	
	//数据的初始化
	public function __construct($input_data){
		//数据初始化
		parent::__construct($input_data);
		
		$this->pagesize = isset($input_data['pagesize'])? $input_data['pagesize']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
		$this->imgwidth = isset($input_data['imgwidth'])? $input_data['imgwidth']:'';  //图片的宽
		$this->imgheight  = isset($input_data['imgheight'])?$input_data['imgheight']:'';     //图片高
		
		$this->type    = isset($input_data['type'])? $input_data['type']:'';    //操作类型
		$this->quanid  = isset($input_data['quanid'])? $input_data['quanid']:'';    //优惠券id
		
		
		if($this->imgwidth==''){
			$this->imgwidth='100';
		}
		if($this->imgheight==''){
			$this->imgheight='100';
		}
	}
	
	
	public function liuyanlist_1(){
	
			
		//回复列表数据
		$replay_sql  = "select count(*) as num1 from `xb_subcomment` where quanid='".$this->quanid."' ";
		$replay_list = parent::__get('HyDb')->get_one($replay_sql);
	
		//评论数据表
		$typesql  = "select count(*) as num from xb_comment where quanid='".$this->quanid."' ";
		$typelist = parent::__get('HyDb')->get_one($typesql);
	
		//总的回复数
		$sumreplay = $replay_list+$typelist;
		
		$pagearr = HyItems::hy_pagepage($this->page,$this->pagesize,$sumreplay);
		$pagemsg = $pagearr['pagemsg'];
		$pagelimit = $pagearr['pagelimit'];
	
	
		$usertouxiangarr = array();//头像地址
		$usernamearr = array();//昵称
	
	
		$useridsql = "select * from xb_comment where quanid='".$this->quanid."' order by subcreatetime desc,id desc ".$pagelimit;
		$useridlist = parent::__get('HyDb')->get_all($useridsql);
	
		foreach($useridlist as $keyud => $valud) {
			$useridlist[$keyud]['cid'] = $useridlist[$keyud]['id'];
			$useridlist[$keyud]['dtype'] = 'm';
				
		}
	
		$allarray = array();
	
		$inarr = array();
		$kkk = -1;
		foreach ($useridlist as $keyu => $valu){
			++$kkk;
				
			//获取留言的用户id
			if(is_numeric($valu['userid'])) {
				array_push($inarr,$valu['userid']);
			}
				
			$cid = $valu['id'];//主留言编号
			$sql_getchild = "select * from xb_subcomment where cid='".$cid."' order by id desc limit 5";
			$list_getchild = parent::__get('HyDb')->get_all($sql_getchild);
				
			foreach($list_getchild as $keygc => $valgc) {
				$list_getchild[$keygc]['dtype'] = 'c';
			}
				
			$allarray[$kkk]= $valu;
			$allarray1[$kkk]['childlist'] = $list_getchild;
				
				
			foreach($list_getchild as $valg) {
				array_push($inarr,$valg['fromuserid']);
				array_push($inarr,$valg['touserid']);
			}
		}
	
		$inarr = array_unique($inarr);
		if(count($inarr)<=0){
			$where_user = 'id=0';
		}else{
			$instr = ' ('.implode(',',$inarr).') ';
			$where_user = ' id in '. $instr;
		}
	
	
		//获取用户列表
		$usersql  = "select id,nickname,touxiang from xb_user where $where_user ";
		$userlist = parent::__get('HyDb')->get_all($usersql);
	
		foreach ($userlist as $keys=>$vals){
				
			$usertouxiangarr[$vals['id']]  = $vals['touxiang'];
			$usernamearr[$vals['id']]      = $vals['nickname'];
				
		}
	
	
		foreach($allarray as $keyaa => $valaa) {
				
			$allarray[$keyaa]['fromuser'] = array(
					'user_id'  => $allarray[$keyaa]['userid'],
					'nickname' => isset($usernamearr[$allarray[$keyaa]['userid']])?(string)$usernamearr[$allarray[$keyaa]['userid']]:'',
					'img_url'  => isset($usertouxiangarr[$allarray[$keyaa]['userid']])?(string)$usertouxiangarr[$allarray[$keyaa]['userid']]:'',
			);
		//留言的时间
		if((time()-strtotime($allarray[$keyaa]['createtime']))<1*60*60){
				
			$allarray[$keyaa]['createtime']='刚刚';
				
		}else if((time()-strtotime($allarray[$keyaa]['createtime']))<1*24*60*60){
				
			$allarray[$keyaa]['createtime']=intval(((time()-strtotime($allarray[$keyaa]['createtime']))/3600)).'小时前';//算出小时
				
		}else {
				
			$allarray[$keyaa]['createtime'] = intval(((time()-strtotime($allarray[$keyaa]['createtime']))/86400)).'天前';//算出多少天前
		}
				
				
		//子留言信息
		foreach($allarray1[$keyaa]['childlist'] as $keyccc => $valccc) {

			$allarray1[$keyaa]['childlist'][$keyccc]['fromuser'] = array(
					'nickname' => isset($usernamearr[$allarray1[$keyaa]['childlist'][$keyccc]['fromuserid']])?$usernamearr[$allarray1[$keyaa]['childlist'][$keyccc]['fromuserid']]:'',
					'user_id'  => isset($allarray1[$keyaa]['childlist'][$keyccc]['fromuserid'])?$allarray1[$keyaa]['childlist'][$keyccc]['fromuserid']:'',
					'img_url'  => isset($usertouxiangarr[$allarray1[$keyaa]['childlist'][$keyccc]['fromuserid']])?$usertouxiangarr[$allarray1[$keyaa]['childlist'][$keyccc]['fromuserid']]:'',
			);

			$allarray1[$keyaa]['childlist'][$keyccc]['touser'] = array(
					'nickname' => isset($usernamearr[$allarray1[$keyaa]['childlist'][$keyccc]['touserid']])?$usernamearr[$allarray1[$keyaa]['childlist'][$keyccc]['touserid']]:'',
					'user_id'  => isset($allarray1[$keyaa]['childlist'][$keyccc]['touserid'])?$allarray1[$keyaa]['childlist'][$keyccc]['touserid']:'',
					'img_url'  => isset($usertouxiangarr[$allarray1[$keyaa]['childlist'][$keyccc]['touserid']])?$usertouxiangarr[$allarray1[$keyaa]['childlist'][$keyccc]['touserid']]:'',
			);


			//留言的时间
			if((time()-strtotime($allarray1[$keyaa]['childlist'][$keyccc]['createtime']))<1*60*60){

				$allarray1[$keyaa]['childlist'][$keyccc]['createtime']='刚刚';

			}else if((time()-strtotime($allarray1[$keyaa]['childlist'][$keyccc]['createtime']))<1*24*60*60){

				$allarray1[$keyaa]['childlist'][$keyccc]['createtime']=intval(((time()-strtotime($allarray1[$keyaa]['childlist'][$keyccc]['createtime']))/3600)).'小时前';//算出小时

			}else {

				$allarray1[$keyaa]['childlist'][$keyccc]['createtime'] = intval(((time()-strtotime($allarray1[$keyaa]['childlist'][$keyccc]['createtime']))/86400)).'天前';//算出多少天前
			}


			if($allarray1[$keyaa]['childlist'][$keyccc]['fromuserid']==$this->userid){
				$allarray1[$keyaa]['childlist'][$keyccc]['is_deled'] = '1';//可删除
			}else{
				$allarray1[$keyaa]['childlist'][$keyccc]['is_deled'] = '2';//bu可删除
			}


			$allarray1[$keyaa]['childlist'][$keyccc]['comments'] = array();


		}
				
		$allarray[$keyaa]['comments'] = $allarray1[$keyaa]['childlist'];
			
		if($allarray[$keyaa]['userid']==$this->userid) {
			$allarray[$keyaa]['is_deled'] = '1';

		}else {
			$allarray[$keyaa]['is_deled'] = '2';

		}
	}
	
	$retarr = array(
			'pagemsg' => $pagemsg,
			'list' => $allarray,
	);
		
		
	$echojsonstr = HyItems::echo2clientjson('100','留言列表获取成功',$retarr);
	if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
	echo $echojsonstr;
	return true;
	
	}
	
	
	public function liuyanlist_2(){
		
		//获取回复的全部数据
		$subcomment_sql  = "select * from xb_subcomment where cid='".$this->cid."' order by id desc  ";
		$subcomment_list = parent::__get('HyDb')->get_all($subcomment_sql);
	
		$inarr = array();
	
	
		foreach ($subcomment_list as $val){
				
			//获取留言的用户id
			if(is_numeric($val['fromuserid'])) {
				array_push($inarr,$val['fromuserid']);
			}
			//获取留言的用户id
			if(is_numeric($val['touserid'])) {
				array_push($inarr,$val['touserid']);
			}
				
		}
	
		$inarr = array_unique($inarr);
		if(count($inarr)<=0){
	
			$where_user = 'id=0';
		}else{
			$instr = ' ('.implode(',',$inarr).') ';
			$where_user = ' id in '. $instr;
		}
	
		$usertouxiangarr = array();//头像地址
		$usernamearr = array();//昵称
	
	
		//获取用户列表
		$usersql  = "select id,nickname,touxiang from xb_user where $where_user ";//$usertask_list[$key]['id']       = $taskidarr[$usertask_list[$key]['taskid']];
		$userlist = parent::__get('HyDb')->get_all($usersql);
			
		foreach ($userlist as $keys=>$vals){
	
			$usertouxiangarr[$vals['id']]  = $vals['touxiang'];
			$usernamearr[$vals['id']]      = $vals['nickname'];
	
		}
	
	
		foreach ($subcomment_list as $keys=>$vals){
				
			$subcomment_list[$keys]['dtype'] = 'c';
				
			$subcomment_list[$keys]['fromuser'] = array(
					'user_id'  => $subcomment_list[$keys]['fromuserid'],
					'nickname' => isset($usernamearr[$subcomment_list[$keys]['fromuserid']])?$usernamearr[$subcomment_list[$keys]['fromuserid']]:'',
					'img_url'  => isset($usertouxiangarr[$subcomment_list[$keys]['fromuserid']])?$usertouxiangarr[$subcomment_list[$keys]['fromuserid']]:'',
			);
	
			$subcomment_list[$keys]['touser'] = array(
					'user_id'  => $subcomment_list[$keys]['touserid'],
					'nickname' => isset($usernamearr[$subcomment_list[$keys]['touserid']])?$usernamearr[$subcomment_list[$keys]['touserid']]:'',
					'img_url'  => isset($usertouxiangarr[$subcomment_list[$keys]['touserid']])?$usertouxiangarr[$subcomment_list[$keys]['touserid']]:'',
			);
				
			//留言的时间
			if((time()-strtotime($subcomment_list[$keys]['createtime']))<1*60*60){
					
				$subcomment_list[$keys]['createtime']='刚刚';
					
			}else if((time()-strtotime($subcomment_list[$keys]['createtime']))<1*24*60*60){
					
				$subcomment_list[$keys]['createtime']=intval(((time()-strtotime($subcomment_list[$keys]['createtime']))/3600)).'小时前';//算出小时
					
			}else {
					
				$subcomment_list[$keys]['createtime'] = intval(((time()-strtotime($subcomment_list[$keys]['createtime']))/86400)).'天前';//算出多少天前
			}
				
			$subcomment_list[$keys]['comments'] = array();
				
				
	
			$subcomment_list[$keys]['is_deled'] = '2';
				
				
		}
		
		$retarr = array(
				'list' => $subcomment_list,
		);
		
		
		$echojsonstr = HyItems::echo2clientjson('100','回复列表获取成功',$retarr);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	
	
	}
	
	
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		if($this->type==1) {
			$this->liuyanlist_1();
		}else if($this->type==2) {
			$this->liuyanlist_2();
		}else {
			$echojsonstr = HyItems::echo2clientjson('301','type参数状态不正确');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		return true;
	}
	
}