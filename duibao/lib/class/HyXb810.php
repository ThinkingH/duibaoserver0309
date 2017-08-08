<?php
/*
 * 用户评论
 */
class HyXb810 extends HyXb{
	
	
	private $yijian; //留言内容
	private $quanid; //优惠券id
	private $type; //1-留言2-回复留言 3-删除
	private $touserid; //被回复者id
	private $userid;   //发送者用户id
	private $cid;   //主表id
	private $dtype;   //类型---m/c
	private $nowid;   //
	
	
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
		
		
		
		$this->type    = isset($input_data['type'])? $input_data['type']:'';    //操作类型
		$this->quanid  = isset($input_data['quanid'])? $input_data['quanid']:'';    //优惠券id
		$this->userid  = isset($input_data['userid']) ? $input_data['userid']:'';   //用户id
		
		$this->touserid = isset($input_data['touserid']) ? $input_data['touserid']:''; //
		$this->yijian = isset($input_data['yijian'])? $input_data['yijian']:'';    //留言内容
		
		$this->dtype      = isset($input_data['dtype']) ? $input_data['dtype']:'c'; //m或c
		$this->cid        = isset($input_data['cid']) ? $input_data['cid']:''; //评论的id
		$this->nowid      = isset($input_data['nowid']) ? $input_data['nowid']:''; //回复的id或评论的id
	
	}
	
	
	
	
	public function liuyan_m(){
		
		
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
		
		
	}
	
	
	
	
	public function liuyan_c(){
			
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
			
			
			//回复留言入库操作
			$insertsql   = "insert into xb_subcomment (cid,userid,fromuserid,touserid,
							quanid,content,createtime)
							values ('".$this->cid."','".$this->userid."','".$this->userid."','".$this->touserid."',
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
	
	
	
	
	public function liuyan_delete_m(){
		
		$panduan_sql  = "select userid from xb_comment where id='".$this->cid."' limit 1";
		$panduan_userid = parent::__get('HyDb')->get_one($panduan_sql);
		
		//删除实现
		if($this->userid==$panduan_userid){
			
			//回复列表的删除
			$delsubsql = "delete from xb_subcomment where cid='".$this->cid."'";
			$delsublist = parent::__get('HyDb')->execute($delsubsql);
			parent::hy_log_str_add($delsubsql."\n");
			
			$delsql  = "delete from xb_comment where id='".$this->cid."'  ";
			$dellist = parent::__get('HyDb')->execute($delsql); 
			parent::hy_log_str_add($delsql."\n");
			
			
			if($dellist){//关联字表的查询
				
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '评论删除成功';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
					
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '评论删除失败';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '删除数据不存在';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		
	}
	
	
	
	public function liuyan_delete_c(){
		
		$panduan_sql  = "select userid from xb_subcomment where id='".$this->nowid."' limit 1";
		$panduan_userid = parent::__get('HyDb')->get_one($panduan_sql);
		
		//删除实现
		if($this->userid==$panduan_userid){
			
			//回复列表的删除
			$delsubsql = "delete from xb_subcomment where id='".$this->nowid."'";
			$delsublist = parent::__get('HyDb')->execute($delsubsql);
			parent::hy_log_str_add($delsubsql."\n");
			
			if($delsublist){//关联字表的查询
				
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '回复删除成功';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
					
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '回复删除失败';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '删除数据不存在';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
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
		
		
		
		
		if('1'==$this->type) {
			if('m'==$this->dtype) {
				$this->liuyan_m();
			}else {
				$this->liuyan_c();
			}
		}else if('2'==$this->type) {
			if('m'==$this->dtype) {
				$this->liuyan_delete_m();
			}else {
				$this->liuyan_delete_c();
			}
		}else {
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '类型参数传递错误！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		
		return true;
	
	}
	
	
	
	
	
}