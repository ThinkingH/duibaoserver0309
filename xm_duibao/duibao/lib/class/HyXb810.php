<?php
/*
 * 用户评论
 */
class HyXb810 extends HyXb{
	
	
	private $yijian; //留言内容
	private $quanid; //优惠券id
	private $type; //1-留言2-回复留言 3-删除
	private $senderid; //回复者
	private $receiverid; //被回复者
	private $userid;   //楼层id
	private $cid;   //主表id
	
	
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
	
		
		$this->yijian = isset($input_data['yijian'])? $input_data['yijian']:'';    //留言内容
		$this->quanid  = isset($input_data['quanid'])? $input_data['quanid']:'';    //优惠券id
		$this->type = isset($input_data['type'])? $input_data['type']:'';    //留言内容
		
		$this->userid     = isset($input_data['userid']) ? $input_data['userid']:'';   //板块id
		$this->senderid   = isset($input_data['senderid']) ? $input_data['senderid']:'';   //回复者
		$this->receiverid = isset($input_data['receiverid']) ? $input_data['receiverid']:''; //被回复者
		$this->cid        = isset($input_data['cid']) ? $input_data['cid']:''; //被回复者
	
	}
	
	
	public function liuyan(){
		
		if($this->type=='1'){//用户进行留言
			
			//敏感词的判断ddd($str)
			$r = parent::sensitive($this->yijian);
			
			if($r){//存在敏感词
				
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '留言中存在敏感词';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
				
			}else{
				
				//用户留言信息的入库操作
				$insertsql = "insert into xb_comment(userid,quanid,content,createtime)
					values ('".$this->userid."','".$this->quanid."','".$this->yijian."','".date('Y-m-d H:i:s')."')";
				$insertlist = parent::__get('HyDb')->execute($insertsql);
				
				
				if($insertlist){
					$echoarr = array();
					$echoarr['returncode'] = 'success';
					$echoarr['returnmsg']  = '留言成功';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return true;
				}else{
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '留言失败';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
				}
			}
			
		
			
		}else if($this->type=='2'){//留言回复 1. 第一次留言的id 2.回复的id
			
			//敏感词的判断
			$r = parent::sensitive($this->yijian);
			
			if($r){
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '留言中存在敏感词';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}else{
				
				//楼主id  2.回复用户id
				//判断回复者和留言者是否是同一个人
				$panduan_sql  = "select userid from xb_comment where id='".$this->cid."' limit 1";
				$panduan_list = parent::__get('HyDb')->get_row($panduan_sql); 
				
				if($panduan_list['userid']==$this->userid){
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '不可以对自己发布的留言进行回复';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
				}else{
				
					//回复留言入库操作
					$insertsql = "insert into xb_subcomment (cid,userid,senderid,receiverid,
									quanid,content,createtime)
									values ('".$this->cid."','".$this->userid."','".$this->senderid."','".$this->receiverid."',
											'".$this->quanid."','".$this->yijian."','".date('Y-m-d H:i:s')."')";
					$insertlist = parent::__get('HyDb')->execute($insertsql);
					
					//当被回复时，主留言置顶
					$zhuliuyansql  = "update xb_comment set subcreatetime='".date('Y-m-d H:i:s')."' where id='".$this->cid."'";
					$zhuliuyanlist = parent::__get('HyDb')->execute($zhuliuyansql);
					
				
				
					if($insertlist){
						$echoarr = array();
						$echoarr['returncode'] = 'success';
						$echoarr['returnmsg']  = '留言成功';
						$echoarr['dataarr'] = array();
						$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
						parent::hy_log_str_add($logstr);
						echo json_encode($echoarr);
						return true;
					}else{
						$echoarr = array();
						$echoarr['returncode'] = 'error';
						$echoarr['returnmsg']  = '留言失败';
						$echoarr['dataarr'] = array();
						$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
						parent::hy_log_str_add($logstr);
						echo json_encode($echoarr);
						return false;
					}
				
				}
				
			}
			
		}else if($this->type=='3'){
			
			$panduan_sql  = "select userid from xb_comment where id='".$this->cid."' limit 1";
			$panduan_list = parent::__get('HyDb')->get_row($panduan_sql);
			
			//删除实现
			if($this->cid==$panduan_list['userid']){
				
				
				$delsql  = "delete from xb_comment where id='".$this->cid."'  ";
				$dellist = parent::__get('HyDb')->execute($delsql); 
				
				//回复列表的删除
				$delsubsql = "delete from xb_subcomment cid='".$this->cid."'";
				$delsublist = parent::__get('HyDb')->execute($delsubsql);
				
				if($dellist){//关联字表的查询
					
					$echoarr = array();
					$echoarr['returncode'] = 'success';
					$echoarr['returnmsg']  = '留言删除成功';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return true;
						
				}else{
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '留言删除失败';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
				}
				
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '系统错误';
				$echoarr['dataarr'] = array();
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
		
		
		
		//留言系统
		$shuzu = array('1','2','3');
		
		if(!in_array($this->type,$shuzu)){
		
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '类型参数传递错误！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
	
		$this->liuyan();
	
		return true;
	}
	
	
	
	
	
	
}