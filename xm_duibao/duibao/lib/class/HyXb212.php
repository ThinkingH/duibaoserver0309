<?php
/*
 * 抽奖领取列表
 */
class HyXb212 extends HyXb{
	
	
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
		$this->userid = isset($input_data['userid']) ? $input_data['userid']:'';  //
		
		//接收临时用户的key
		$this->userkey = isset($input_data['userkey']) ? $input_data['userkey']:'';  //
		
		$this->type = isset($input_data['type']) ? $input_data['type']:'';  //Type=’’为空 全部  type=1 实物 type=2 流量 type=3 优惠券
		
		$this->typeid = isset($input_data['typeid']) ? $input_data['typeid']:'';  //
		
		$this->xiafaurl = XAIFALIULIANGURL;   //调用流量下发兑换码的接口
		
		$this->count = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
	}
	
	
	//获取全部数据
	public function controller_alllist(){
		
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;		
		
		$returnarr = array();
		
		//获取总条数
		$tasksumsql  = "select count(*) as num from db_prize_list where userid='".$this->userid."'  ";
		$tasksumlist = parent::__get('HyDb')->get_all($tasksumsql);
		
		if($tasksumlist[0]['num']>0){
			$returnarr['maxcon'] = $tasksumlist[0]['num'];//总条数
		}else{
			$returnarr['maxcon'] = 0;//总条数
		}
		
		//总页数$firstpage,$pagesize
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		$prize_sql  = "select * from db_prize_list where userid='".$this->userid."' order by create_datetime desc  limit $firstpage,$pagesize ";
		$prize_list = parent::__get('HyDb')->get_all($prize_sql);
		
		//print_r($prize_list);
		
		if(count($prize_list)>0){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '获取成功';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $prize_list;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '获取为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	}
	
	//获取流量
	public function controller_liulianglist(){
		
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;		
		
		$returnarr = array();
		
		//获取总条数
		$tasksumsql  = "select count(*) as num from db_prize_list where type='2' and userid='".$this->userid."'";
		$tasksumlist = parent::__get('HyDb')->get_all($tasksumsql);
		
		if($tasksumlist[0]['num']>0){
			$returnarr['maxcon'] = $tasksumlist[0]['num'];//总条数
		}else{
			$returnarr['maxcon'] = 0;//总条数
		}
		
		//总页数$firstpage,$pagesize
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		$prize_sql  = "select * from db_prize_list where userid='".$this->userid."' and type='2' order by create_datetime desc limit $firstpage,$pagesize ";
		$prize_list = parent::__get('HyDb')->get_all($prize_sql);
		
		//print_r($prize_list);
		
		if(count($prize_list)>0){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '流量数据获取成功';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $prize_list;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '流量数据获取为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	}
	
	
	//获取优惠券
	public function controller_quanlist(){
		
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;		
		
		$returnarr = array();
		
		//获取总条数
		$tasksumsql  = "select count(*) as num from db_prize_list where type='3'  and  userid='".$this->userid."'";
		$tasksumlist = parent::__get('HyDb')->get_all($tasksumsql);
		
		if($tasksumlist[0]['num']>0){
			$returnarr['maxcon'] = $tasksumlist[0]['num'];//总条数
		}else{
			$returnarr['maxcon'] = 0;//总条数
		}
		
		//总页数$firstpage,$pagesize
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		$prize_sql  = "select * from db_prize_list where userid='".$this->userid."' and type='3' order by create_datetime desc limit $firstpage,$pagesize ";
		$prize_list = parent::__get('HyDb')->get_all($prize_sql);
		
		//print_r($prize_list);
		
		if(count($prize_list)>0){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '优惠券数据获取成功';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $prize_list;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '优惠券数据获取为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
	}
	
	
	//实物列表
	public function controller_shiwulist(){
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;
		
		$returnarr = array();
		
		//获取总条数
		$tasksumsql  = "select count(*) as num from db_prize_list where type='1' and userid='".$this->userid."' ";
		$tasksumlist = parent::__get('HyDb')->get_all($tasksumsql);
		
		if($tasksumlist[0]['num']>0){
			$returnarr['maxcon'] = $tasksumlist[0]['num'];//总条数
		}else{
			$returnarr['maxcon'] = 0;//总条数
		}
		
		//总页数$firstpage,$pagesize
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		$prize_sql  = "select * from db_prize_list where userid='".$this->userid."' and type='1' order by create_datetime desc limit $firstpage,$pagesize ";
		$prize_list = parent::__get('HyDb')->get_all($prize_sql);
		
		//print_r($prize_list);
		
		if(count($prize_list)>0){
				
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '实物数据获取成功';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $prize_list;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
				
		}else{
				
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '实物数据获取为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	}
	
	
	//流量领取操作
	public function controller_getliuliang(){
		
		
		//获取该数据的详情
		$get_sql = "select * from db_prize_list where id='".$this->typeid."' and type='2'  ";
		$get_list = parent::__get('HyDb')->get_row($get_sql);
		
		if($get_list['id']>0 && $get_list['quanid']>='100' ){
			
			$phone = $get_list['phone'];
			
			//判断手机号对应的运营商 1-移动 2-联通 3-电信
			$gateway = parent::hy_yunyingshangcheck($phone,$type='num');
			
			if(!is_numeric($gateway)){
				
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '手机号错误';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
			$goodsname='';
			$keystr='';
			if($gateway=='1'){
				$goodsname = '移动流量100M';
				$productid = '11';
			}else if($gateway=='2'){
				$goodsname = '联通流量100M';
				$productid ='11';
			}else if($gateway=='3'){
				$goodsname = '电信流量100M';
				$productid ='11';
			}
			
			//商品订单
			$orderno = 'D'.date('YmdHis').mt_rand(1000,9999);//商品订单号
			
			//流量兑换
			$url =$this->xiafaurl.'?gateway='.$gateway.'&mbps=100&ttype=1&orderno='.$orderno.'&userid='.$this->userid.
				'&youxiaoday=30&name='.urlencode($goodsname).'&describe='.urlencode($goodsname);
			
			$duihuancode = HyItems::vget( $url, 10000 );
			
			if($duihuancode['httpcode']=='200'){
				$keystr = $duihuancode['content'];//生成兑换码
				
				//$keystr='th3mcmh1fj74t85nud';
				
				if(strlen($keystr)!='18'){//秘钥生成错误
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '兑换码生成失败,系统维修中';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
						
				}else{//兑换码生成成功，数据的插入
					//订单的插入
					
					$insert_sql = "insert into shop_userbuy (userid,siteid,typeid,mtype,name,productnum,orderno,keystr,productid,status,order_createtime)
							 values ('".$this->userid."','1000','11','1','".$goodsname."','1','".$orderno."','".$keystr."','".$productid."','3','".date('Y-m-d H:i:s')."')";
					$r = parent::__get('HyDb')->execute($insert_sql);
					
					$update_sql = "update db_prize_list set quanid=quanid-100 where id='".$this->typeid."'";
					parent::__get('HyDb')->execute($update_sql);
					
					//该用户的其他流量累加清空
					$clear_sql = "update db_prize_list set quanid=0 where userid='".$this->userid."' and id <> '".$this->typeid."' ";
					parent::__get('HyDb')->execute($clear_sql);
					
					//获取订单id
					$selectid_sql  = "select id from shop_userbuy where orderno='".$orderno."'  ";
					$selectid_list = parent::__get('HyDb')->get_row($selectid_sql);
				}
			}
			
			$temparr = array(
					'goodsname' =>$goodsname,
					'keystr'     => $keystr,
					'phone'     => $phone,
					'createtime' => date('Y-m-d H:i:s'),
					'id'         => $selectid_list['id'],
			);
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '订单提交成功';
			$echoarr['dataarr'] = $temparr;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
			
			
		}else{
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '数据不存在';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
	}
	
	
	//操作入口
	public function controller_init(){
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
		if($r===false){
			return false;
		}
		
		if($this->page=='' || $this->page=='0'){
		
			$this->page=1;
		}
		
		if($this->count=='' || $this->count=='undefined'){
		
			$this->count=10;
		}
		
		if($this->type==''){//获取全部数据
			
			$this->controller_alllist();
			
		}else if($this->type=='1'){
			
			$this->controller_shiwulist();
			
		}else if($this->type=='2'){//流量
			
			$this->controller_liulianglist();
			
		}else if($this->type=='3'){//优惠券
			
			$this->controller_quanlist();
			
		} else if($this->type=='4'){//领取操作
			
			$this->controller_getliuliang();
			
		}
	
	
		
	
		return true;
	
	}
	
	
}