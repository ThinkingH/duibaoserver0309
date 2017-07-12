<?php
/*
 * 用户的点赞或取消
 */
class HyXb809 extends HyXb{
	
	
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
	
	
		$this->quantype = isset($input_data['quantype'])? $input_data['quantype']:'';    //优惠券类型--kfc,bsk
		$this->quanid  = isset($input_data['quanid'])? $input_data['quanid']:'';    //优惠券id
		$this->collect = isset($input_data['collect'])? $input_data['collect']:'';    //收藏的类型
	
	
	}
	
	
	//用户点赞
	public function controller_getcollectionlist(){
		
		
		if($this->collect=='1'){//用户进行点赞
			
			$collectsql  = "select id from xb_collection where flag=4 and quanid='".$this->quanid."' and userid='".parent::__get('xb_userid')."' ";
			$collectlist = parent::__get('HyDb')->get_all($collectsql);
			
			if(count($collectlist)<=0){//该商品未被点赞，请进行点赞
				$date = date('Y-m-d H:i:s');
				$collectinsertsql = "insert into xb_collection (flag,userid,quanid,quantype,createtime) 
						values('4','".parent::__get('xb_userid')."','".$this->quanid."','".$this->quantype."','".$date."')";
				$collectinsertlist = parent::__get('HyDb')->execute($collectinsertsql);
				
				//表中点赞次数的增加
				$updatesql = "update z_tuanmainlist set dianzan=dianzan+1 where id='".$this->quanid."' ";
				parent::__get('HyDb')->execute($updatesql);
				
				$selectsql = "select dianzan, userid from z_tuanmainlist where id='".$this->quanid."' ";
				$selectlist = parent::__get('HyDb')->get_all($selectsql);
				
				//用户积分的增加
				$scoresql = "update xb_user set keyong_jifen=keyong_jifen+5 where id='".$selectlist[0]['userid']."' ";
				parent::__get('HyDb')->execute($scoresql);
				
				//积分详情的记录
				$getdescribe = '你上传的优惠券收到一个赞奖励您5馅饼';
				$date=time();
				$scoresql = "insert into xb_user_score (userid,goodstype,maintype,type,score,gettime,getdescribe)
						values ('".$selectlist[0]['userid']."','1','1','1','5','".$date."','".$getdescribe."')";
				parent::__get('HyDb')->execute($scoresql);
				
				
				if($collectinsertlist){
					$echoarr = array();
					$echoarr['returncode'] = 'success';
					$echoarr['returnmsg']  = '点赞成功';
					$echoarr['dataarr'] = $selectlist;
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return true;
				}else{
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '点赞失败';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
					
				}
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '该商品已点赞，不可重复点赞';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return false;
			}
			
			
			
			
		}else if($this->collect=='2'){//取消点赞
			
			$collectsql  = "select id from xb_collection where flag=4 and quanid='".$this->quanid."' and userid='".parent::__get('xb_userid')."'";
			$collectlist = parent::__get('HyDb')->get_all($collectsql);
			
			if(count($collectlist)>0){
				
				$selectsql = "select dianzan from z_tuanmainlist where id='".$this->quanid."' ";
				$selectlist = parent::__get('HyDb')->get_all($selectsql);
				
				if($selectlist[0]['dianzan']!='0'){
					
					$updatesql = "update z_tuanmainlist set dianzan=dianzan-1 where id='".$this->quanid."' ";
					parent::__get('HyDb')->execute($updatesql);
				}
				
				$selectsql = "select dianzan from z_tuanmainlist where id='".$this->quanid."' ";
				$selectlist = parent::__get('HyDb')->get_all($selectsql);
				
				$delcollectsql  = "delete from xb_collection where flag=4 and quanid='".$this->quanid."' and userid='".parent::__get('xb_userid')."' ";
				$delcollectlist = parent::__get('HyDb')->execute($delcollectsql);
				
				
				if($delcollectlist){
					$echoarr = array();
					$echoarr['returncode'] = 'success';
					$echoarr['returnmsg']  = '取消点赞成功';
					$echoarr['dataarr'] = $selectlist;
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return true;
				}else{
					$echoarr = array();
					$echoarr['returncode'] = 'error';
					$echoarr['returnmsg']  = '取消点赞失败';
					$echoarr['dataarr'] = array();
					$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
					parent::hy_log_str_add($logstr);
					echo json_encode($echoarr);
					return false;
				}
				
			}else{
				$echoarr = array();
				$echoarr['returncode'] = 'error';
				$echoarr['returnmsg']  = '商品未点赞，不可以取消';
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
		
		
		
		//优惠券的收藏类型
		$shuzu = array('1','2','3');
		
		if(!in_array($this->collect,$shuzu)){
		
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '类型参数传递错误！';
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