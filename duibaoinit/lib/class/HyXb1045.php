<?php
/*
 * 附近数据评论
 */
class HyXb1045 extends HyXb{
	
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
		$this->userid  = isset($input_data['userid']) ? $input_data['userid']:'';   //用户id
		
		$this->touserid = isset($input_data['touserid']) ? $input_data['touserid']:''; //
		$this->yijian = isset($input_data['yijian'])? $input_data['yijian']:'';    //留言内容
		
		$this->dtype      = isset($input_data['dtype']) ? $input_data['dtype']:'c'; //m或c m--评论 c-回复
		$this->cid        = isset($input_data['cid']) ? $input_data['cid']:''; //评论的id
		$this->nowid      = isset($input_data['nowid']) ? $input_data['nowid']:''; //回复的id或评论的id
		
		if($this->imgwidth==''){
			$this->imgwidth='100';
		}
		if($this->imgheight==''){
			$this->imgheight='100';
		}
	}
	
	
	public function liuyan_m(){
	
	
		//敏感词的判断ddd($str)
		$r = parent::sensitive($this->yijian);
	
		if($r){//存在敏感词
				
			$echojsonstr = HyItems::echo2clientjson('100','留言中存在敏感词');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
				
		}else{
				
			$sql_gettouserid = "select userid from z_fabulist where id='".$this->quanid."'";
			$thetouserid = parent::__get('HyDb')->get_one($sql_gettouserid);
			if($thetouserid=='') {
				$thetouserid = 0;
			}
				
			//用户留言信息的入库操作
			$insertsql = "insert into xb_comment(userid,quanid,touserid,content,createtime)
				values ('".$this->userid."','".$this->quanid."','".$thetouserid."','".$this->yijian."','".date('Y-m-d H:i:s')."')";
			$insertlist = parent::__get('HyDb')->execute($insertsql);
				
				
			if($insertlist){
				$echojsonstr = HyItems::echo2clientjson('100','留言成功');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return true;
			}else{
				
				$echojsonstr = HyItems::echo2clientjson('427','留言失败');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return false;
			}
		}
	
	
	}
	
	
	
	
	public function liuyan_c(){
			
		//敏感词的判断
		$r = parent::sensitive($this->yijian);
	
		if($r){
			$echojsonstr = HyItems::echo2clientjson('428','留言中存在敏感词');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
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
				$echojsonstr = HyItems::echo2clientjson('100','留言成功');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return true;
			}else{
				
				$echojsonstr = HyItems::echo2clientjson('429','留言失败');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
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
	
				$echojsonstr = HyItems::echo2clientjson('100','评论删除成功');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return true;
					
			}else{
				$echojsonstr = HyItems::echo2clientjson('430','评论删除失败');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return false;
			}
				
		}else{
			$echojsonstr = HyItems::echo2clientjson('431','删除数据不存在');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
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
				$echojsonstr = HyItems::echo2clientjson('100','回复删除成功');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return true;
			}else{
				$echojsonstr = HyItems::echo2clientjson('432','回复删除失败');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return false;
			}
				
		}else{
			$echojsonstr = HyItems::echo2clientjson('431','删除数据不存在');
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
		
		//优惠券id
		if($this->quanid==''){
			$echojsonstr = HyItems::echo2clientjson('433','优惠券id不能为空');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		if('1'==$this->type) {
			if('m'==$this->dtype) {//评论
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
			$echojsonstr = HyItems::echo2clientjson('301','类型参数传递错误');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		return true;
	}
	
}