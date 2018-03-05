<?php
/*
 * 用户的点赞或取消
 */
class HyXb1025 extends HyXb{
	
	private $pagesize;
	private $page;
	private $imgwidth;
	private $imgheight;
	private $type;//操作类型1-点赞 2-取消点赞
	private $typeid; //优惠券id
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		
		$this->type = isset($input_data['type'])? $input_data['type']:'';
		$this->typeid = isset($input_data['typeid'])?$input_data['typeid']:'';
	}
	
	
	public function controller_exec1(){
		
		$collectsql  = "select id from xb_collection where flag=4 and quanid='".$this->typeid."' and userid='".parent::__get('userid')."' ";
		$collectlist = parent::__get('HyDb')->get_all($collectsql);
		
		if(count($collectlist)<=0){
			$collectinsertsql = "insert into xb_collection (flag,userid,quanid,quantype,createtime)
						values('4','".parent::__get('userid')."','".$this->typeid."','','".date('Y-m-d H:i:s')."')";
			$collectinsertlist = parent::__get('HyDb')->execute($collectinsertsql);
			parent::hy_log_str_add(HyItems::hy_trn2space($collectinsertlist)."\n");
			
			//表中点赞次数的增加
			$updatesql = "update z_fabulist set dianzan=dianzan+1 where id='".$this->typeid."' ";
			parent::__get('HyDb')->execute($updatesql);
			parent::hy_log_str_add(HyItems::hy_trn2space($updatesql)."\n");
			
			$selectsql = "select dianzan, userid from z_fabulist where id='".$this->quanid."' ";
			$selectlist = parent::__get('HyDb')->get_row($selectsql);
			
			//积分记录的插入insert_userscore($tablename='xb_user_score',$userid='',$maintype='1',$type='1',$score='0',$getdescribe='',$remark=''){
			$getdescribe = '你上传的优惠券收到一个赞奖励您5馅饼';
			parent::insert_userscore('xb_user_score',$selectlist['userid'],'1','1','5',$getdescribe,$remark='');
			//用户积分的增加update_userscore($tablename='xb_user',$changscore='0',$type='1',$userid='' ){
			parent::update_userscore('xb_user','5','1',$selectlist['userid']);
			
			$echojsonstr = HyItems::echo2clientjson('100','点赞成功');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return true;
			
		}else{
			$echojsonstr = HyItems::echo2clientjson('339','该商品已点赞');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
	}
	
	
	public function controller_exec2(){
		$collectsql  = "select id from xb_collection where flag=4 and quanid='".$this->typeid."' and userid='".parent::__get('userid')."'";
		$collectlist = parent::__get('HyDb')->get_all($collectsql);
		if(count($collectlist)>0){
			$selectsql = "select dianzan from z_fabulist where id='".$this->typeid."' ";
			$selectlist = parent::__get('HyDb')->get_all($selectsql);
			if(count($selectlist)>0){
				if($selectlist[0]['dianzan']!='0'){
					$updatesql = "update z_fabulist set dianzan=dianzan-1 where id='".$this->typeid."' ";
					parent::__get('HyDb')->execute($updatesql);
				}
				
				$delcollectsql  = "delete from xb_collection where flag=4 and quanid='".$this->typeid."' and userid='".parent::__get('userid')."' ";
				$delcollectlist = parent::__get('HyDb')->execute($delcollectsql);
				parent::__get('HyDb')->execute($delcollectlist);
			}
			
			$echojsonstr = HyItems::echo2clientjson('100','取消点赞成功');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return true;
		}else{
			$echojsonstr = HyItems::echo2clientjson('410','系统错误');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
	}
	
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		if($this->type=='1'){
			$ret = $this->controller_exec1();
		}else if($this->type=='2'){
			$ret = $this->controller_exec2();
		}else{
			$echojsonstr = HyItems::echo2clientjson('301','类型错误');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		return $ret;
	}
	
}