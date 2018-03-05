<?php
/*
 * 优惠券的取消和收藏
 */

class HyXb1018 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $typeid; //商品的id
	private $type; //收藏的类型 1-收藏 2-取消收藏
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		$this->typeid = isset($input_data['typeid'])?$input_data['typeid']:'1';
		$this->type = isset($input_data['type'])?$input_data['type']:'1';
	}
	
	//收藏
	protected function controller_exec1(){
		
		$collectsql  = "select id from xb_collection where flag=1 and quanid='".$this->typeid."' and userid='".parent::__get('userid')."' ";
		$collectlist = parent::__get('HyDb')->get_all($collectsql);
		
		if(count($collectlist)>0){
			$echojsonstr = HyItems::echo2clientjson('323','收藏失败，不可重复收藏');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}else{
			$collectinsertsql = "insert into xb_collection (flag,userid,quanid,createtime)
						values('1','".parent::__get('userid')."','".$this->typeid."','".date('Y-m-d H:i:s')."')";
			parent::hy_log_str_add(HyItems::hy_trn2space($collectinsertsql)."\n");
			$collectinsertlist = parent::__get('HyDb')->execute($collectinsertsql);
			if($collectinsertlist){
				$echojsonstr = HyItems::echo2clientjson('100','收藏成功');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return true;
			}else{
				$echojsonstr = HyItems::echo2clientjson('324','收藏失败');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return false;
			}
		}
		
	}
	
	//取消收藏
	protected function controller_exec2(){
		
		$collectsql  = "select id from xb_collection where flag=1 and quanid='".$this->typeid."' and userid='".parent::__get('userid')."'";
		$collectlist = parent::__get('HyDb')->get_all($collectsql);
		
		if(count($collectlist)>0){
			$delcollectsql  = "delete from xb_collection where flag=1 and quanid='".$this->typeid."' and userid='".parent::__get('userid')."' ";
			$delcollectlist = parent::__get('HyDb')->execute($delcollectsql);
			parent::hy_log_str_add(HyItems::hy_trn2space($delcollectsql)."\n");
			if($delcollectlist){
				$echojsonstr = HyItems::echo2clientjson('100','取消收藏成功');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return true;
			}else{
				$echojsonstr = HyItems::echo2clientjson('325','取消收藏失败');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return false;
			}
			
		}else{
			$echojsonstr = HyItems::echo2clientjson('326','系统错误');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		
		$echojsonstr = HyItems::echo2clientjson('100','热门饭票数据获取成功',$youhuiquanconflist);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
		
	}
	
	
	
	//用户收藏
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		if($this->type=='1'){//收藏
			$ret = $this->controller_exec1();
		}else if($this->type=='2'){//取消收藏
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