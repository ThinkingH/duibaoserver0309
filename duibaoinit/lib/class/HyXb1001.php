<?php
/*
 * 1-轮播图 2-公司版本信息 3-新手礼包
 * 
 */
class HyXb1001 extends HyXb{
	
	private $type;
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $redis;
	
	//数据的初始化
	function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		
		//接口类型区分type=1 广告轮播图 type=2 开屏页 type=3新手礼包
		$this->type = isset($input_data['type'])?$input_data['type']:'';
		$this->imgwidth = isset($input_data['imgwidth'])?$input_data['imgwidth']:'';
		$this->imgheight = isset($input_data['imgheight'])?$input_data['imgheight']:'';
		
	}
	
	//app首页轮播图的获取
	protected function controller_exec1(){
		
		if($this->imgwidth==''){
			$this->imgwidth='750';
		}
			
		if($this->imgheight==''){
			$this->imgheight='290';
		}
		
		$lunbotu_sql = "select id,picname,biaoshi,flag,shopid,shopname,img,
					imgurl,action,type,value,isused,createdatetime
					from xb_lunbotu
					where flag='1' and biaoshi='9' order by id asc ";
		//$lunbotu_list = parent::__get('HyDb')->get_all($lunbotu_sql);
		$lunbotu_list = parent::func_runtime_sql_data($lunbotu_sql);
		//$lunbotu_list = array();
		
		foreach ($lunbotu_list as $key=>$val){
			$lunbotu_list[$key]['img'] = HyItems::hy_qiniuimgurl('duibao-basic',$lunbotu_list[$key]['img'],$this->imgwidth,$this->imgheight,$canshu=true);
		}
			
			
		$echojsonstr = HyItems::echo2clientjson('100','轮播图获取成功',$lunbotu_list);
		if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
		
		
		
	}
	
	//开屏页获取
	protected function controller_exec2(){
		
		if($this->imgwidth==''){
			$this->imgwidth='1080';
		}
			
		if($this->imgheight==''){
			$this->imgheight='1920';
		}
		
		$lunbotu_sql = "select img,isused,imgurl from xb_lunbotu where flag='1' and biaoshi='2' order by id asc";
		//$lunbotu_list = parent::__get('HyDb')->get_all($lunbotu_sql);
		$lunbotu_list = parent::func_runtime_sql_data($lunbotu_sql);
		
		$lunbotu_list[0]['img'] = HyItems::hy_qiniuimgurl('duibao-basic',$lunbotu_list[0]['img'],$this->imgwidth,$this->imgheight,$canshu=true);
		
		$echojsonstr = HyItems::echo2clientjson('100','开屏页获取成功',$lunbotu_list);
		if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	
	}
	
	//新手礼包
	protected function controller_exec3(){
		
		if($this->imgwidth==''){
			$this->imgwidth='750';
		}
			
		if($this->imgheight==''){
			$this->imgheight='600';
		}
		
		$lunbotu_sql = "select img,isused,imgurl from xb_lunbotu where flag='1' and biaoshi='3' order by id asc";
		$lunbotu_list = parent::__get('HyDb')->get_all($lunbotu_sql);
		//$lunbotu_list = parent::func_runtime_sql_data($lunbotu_sql);
		
		$lunbotu_list[0]['img'] = HyItems::hy_qiniuimgurl('duibao-basic',$lunbotu_list[0]['img'],$this->imgwidth,$this->imgheight,$canshu=true);
		
		$echojsonstr = HyItems::echo2clientjson('100','新手礼包获取成功',$lunbotu_list);
		if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	
	}
	
	
	//操作入口
	public function controller_init(){
		
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		if(!is_numeric($this->type)){
			$echojsonstr = HyItems::echo2clientjson('301','类型错误11111');
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		if($this->type=='1'||$this->type=='' ){//app首页轮播图获取
			$this->controller_exec1();
		}else if($this->type=='2'){//开屏页
			$this->controller_exec2();
		}else if($this->type=='3'){//新手礼包
			$this->controller_exec3();
		}else{
			$echojsonstr = HyItems::echo2clientjson('301','类型错误');
			if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		return true;
	
	}
}

