<?php
/*
 * 用户优惠券的收藏和取消收藏----在淘宝联盟抓取的新数据---状态字段flag=3
 */
class HyXb6609 extends HyXb{
	
	
	private $quantype; //优惠券的类型
	private $quanid; //优惠券id
	private $collect; //收藏的类型 1-收藏 2-取消收藏
	
	
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
	
	
		$this->quantype = isset($input_data['quantype'])? $input_data['quantype']:'';    //优惠券类型
		$this->quanid  = isset($input_data['quanid'])? $input_data['quanid']:'';    //优惠券id
		$this->collect = isset($input_data['collect'])? $input_data['collect']:'';    //收藏的类型
	
	
	}
	
	
	//用户收藏
	public function controller_getcollectionlist(){
		
		//收藏分为1--收藏和2--取消
		if($this->collect=='1'){//用户进行收藏
			
			$collectsql  = "select id from xb_collection where flag=3 and quanid='".$this->quanid."' and userid='".parent::__get('xb_userid')."' ";
			$collectlist = parent::__get('HyDb')->get_all($collectsql);
			
			if(count($collectlist)<=0){//该商品未被收藏过，点击收藏
				$date = date('Y-m-d H:i:s');
				$collectinsertsql = "insert into xb_collection (flag,userid,quanid,quantype,createtime) 
						values('3','".parent::__get('xb_userid')."','".$this->quanid."','".$this->quantype."','".$date."')";
				$collectinsertlist = parent::__get('HyDb')->execute($collectinsertsql);
				
				if($collectinsertlist){
					$echoarr = array();
					$echoarr['returncode'] = 'success';
					$echoarr['returnmsg']  = '收藏成功';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return true;
				}else{
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '收藏失败';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
					
				}
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '该商品已收藏，不可重复收藏';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
			
			
			
		}else if($this->collect=='2'){//取消收藏
			
			$collectsql  = "select id from xb_collection where flag=3 and quanid='".$this->quanid."' and userid='".parent::__get('xb_userid')."'";
			$collectlist = parent::__get('HyDb')->get_all($collectsql);
			
			if(count($collectlist)>0){//该商品存在，删除该商品
				
				$delcollectsql  = "delete from xb_collection where flag=3 and quanid='".$this->quanid."' and userid='".parent::__get('xb_userid')."' ";
				$delcollectlist = parent::__get('HyDb')->execute($delcollectsql);
				
				if($delcollectlist){
					$echoarr = array();
					$echoarr['returncode'] = 'success';
					$echoarr['returnmsg']  = '取消收藏成功';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return true;
				}else{
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '取消收藏失败';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
				}
				
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '商品不在收藏列表，不可以取消';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}else if($this->collect=='3'){//全部清空
			
			$historysql  = "delete from xb_collection where flag='3' and userid='".parent::__get('xb_userid')."' ";
			$historylist = parent::__get('HyDb')->execute($historysql);
			
			if($historylist){
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '取消收藏商品成功！';
				$echoarr['dataarr']    = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
					
				echo json_encode($echoarr);
				return true;
			}else{
				
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '取消收藏商品失败！';
				$echoarr['dataarr']    = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
					
				echo json_encode($echoarr);
				return false;
			}
		}
		
		
	}
	
	
	
	
	
	
	
	
	//操作入口--优惠券的收藏
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//优惠券id
		if($this->quanid==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '优惠券id不能为空！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		/* //优惠券类型
		if($this->quantype==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '优惠券类型不能为空！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		} */
		
		//优惠券的收藏类型
		$shuzu = array('1','2','3');
		
		if(!in_array($this->collect,$shuzu)){
		
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '收藏类型参数传递错误！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
	
		//用户收藏入口
		$this->controller_getcollectionlist();
	
		return true;
	}
	
	
	
	
	
	
}