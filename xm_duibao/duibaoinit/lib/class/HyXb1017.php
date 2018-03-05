<?php
/*
 * 首页主类型，子类型 和饭票类型
 */

class HyXb1017 extends HyXb{
	
	private $type; //查询类型 1-热门饭票数据获取成功 2-首页主分类获取成功 3- 子分类获取成功 
	private $typename;
	private $imgwidth;
	private $imgheight;
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		$this->type = isset($input_data['type'])?$input_data['type']:'1';
		$this->typename = isset($input_data['typename'])?$input_data['typename']:'1';
		$this->imgwidth = isset($input_data['imgwidth'])?$input_data['imgwidth']:'';
		$this->imgheight = isset($input_data['imgheight'])?$input_data['imgheight']:'';
		if($this->imgwidth==''){
			$this->imgwidth='200';
		}
		if($this->imgheight==''){
			$this->imgheight='200';
		}
	}
	
	//饭票分类数据
	protected function controller_exec1(){
		//分类数据的缓存
		$youhuiquanconfsql  = "select * from xb_kind where flag='1' and biaoshi='1'";
		//$youhuiquanconflist = parent::__get('HyDb')->get_all($youhuiquanconfsql);
		$youhuiquanconflist = parent::func_runtime_sql_data($youhuiquanconfsql);
		foreach ($youhuiquanconflist as $keyu=>$valu){
			$youhuiquanconflist[$keyu]['smallpic'] = HyItems::hy_qiniuimgurl('duibao-basic',$youhuiquanconflist[$keyu]['smallpic'],$this->imgwidth,$this->imgheight);
		}
		
		$echojsonstr = HyItems::echo2clientjson('100','热门饭票数据获取成功',$youhuiquanconflist);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
		
	}
	
	//首页主分类
	protected function controller_exec2(){
		
		$seldata = "select id,kindtype,kindname,smallpic from maintype where flag=1 order by id asc";
		//$selsql  = parent::__get('HyDb')->get_all($seldata);
		$selsql = parent::func_runtime_sql_data($seldata);
		foreach ($selsql as $keyu=>$valu){
			$selsql[$keyu]['smallpic'] = HyItems::hy_qiniuimgurl('duibao-basic',$selsql[$keyu]['smallpic'],$this->imgwidth,$this->imgheight);
		}
		$echojsonstr = HyItems::echo2clientjson('100','首页主分类获取成功',$selsql);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	}
	//子分类
	protected function controller_exec3(){
		$seldata = "select id,childtype,smallpic from shouye_config where flag=1 and type='".trim($this->typename)."' order by id";
		//$selsql  = parent::__get('HyDb')->get_all($seldata);
		$selsql = parent::func_runtime_sql_data($seldata);
		
		foreach ($selsql as $keyu=>$valu){
			$selsql[$keyu]['smallpic'] = HyItems::hy_qiniuimgurl('duibao-basic',$selsql[$keyu]['smallpic'],$this->imgwidth,$this->imgheight);
		}
		$echojsonstr = HyItems::echo2clientjson('100','子分类获取成功',$selsql);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	
	}
	
	
	//操作入口
	public function controller_init(){
		
		if($this->type=='1'){//饭票类型
			$ret = $this->controller_exec1();
		}else if($this->type=='2'){//首页类型
			$ret = $this->controller_exec2();
		}else if($this->type=='3'){//首页子类型
			$ret = $this->controller_exec3();
		}else{
			$echojsonstr = HyItems::echo2clientjson('301','类型错误');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		return $ret;
	}
	
}