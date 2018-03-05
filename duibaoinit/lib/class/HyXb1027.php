<?php
/*
 * 首页列表数据的获取
 */
class HyXb1027 extends HyXb{
	
	private $type; //操作类型 1-//兑宝热门免费  2-兑宝特供优惠  3-特价好货 
	private $imgwidth;
	private $imgheight;
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		
		$this->type = isset($input_data['type'])?$input_data['type']:'';
		$this->imgwidth = isset($input_data['imgwidth'])?$input_data['imgwidth']:'';
		$this->imgheight = isset($input_data['imgheight'])?$input_data['imgheight']:'';
	}
	
	
	public function controller_exec1(){
		if($this->imgwidth==''){
			$this->imgwidth='200';
		}
		if($this->imgheight==''){
			$this->imgheight='160';
		}
		$youhuiquanconfsql  = "select id,shopid,shopname,img,imgurl,action, type,value,isused
								from xb_lunbotu where flag='1' and biaoshi='5' order by picname desc limit 3";
		//$youhuiquanconflist = parent::__get('HyDb')->get_all($youhuiquanconfsql);
		$youhuiquanconflist = parent::func_runtime_sql_data($youhuiquanconfsql);
		
		foreach ($youhuiquanconflist as $keys => $vals ){
			$youhuiquanconflist[$keys]['img'] = HyItems::hy_qiniuimgurl('duibao-basic',$youhuiquanconflist[$keys]['img'],$this->imgwidth,$this->imgheight);
		}
		
		$echojsonstr = HyItems::echo2clientjson('100','数据获取成功',$youhuiquanconflist);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
		
	}
	
	public function controller_exec2(){
		if($this->imgwidth==''){
			$this->imgwidth='320';
		}
		if($this->imgheight==''){
			$this->imgheight='179';
		}
		$youhuiquanconfsql  = "select id,img from xb_lunbotu where flag='1' and biaoshi='6' order by picname desc limit 4";
		//$youhuiquanconflist = parent::__get('HyDb')->get_all($youhuiquanconfsql);
		$youhuiquanconflist = parent::func_runtime_sql_data($youhuiquanconfsql);
			
		foreach ($youhuiquanconflist as $keys => $vals){
			$youhuiquanconflist[$keys]['img'] = HyItems::hy_qiniuimgurl('duibao-basic',$youhuiquanconflist[$keys]['img'],$this->imgwidth,$this->imgheight);
		}
		
		$echojsonstr = HyItems::echo2clientjson('100','数据获取成功',$youhuiquanconflist);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	}
	
	public function controller_exec3(){
		if($this->imgwidth==''){
			$this->imgwidth='250';
		}
		if($this->imgheight==''){
			$this->imgheight='300';
		}
		$youhuiquanconfsql  = "select kindtype,kindname,smallpic from xb_kind where flag='1' and biaoshi='2' order by createtime desc limit 3";
		//$youhuiquanconflist = parent::__get('HyDb')->get_all($youhuiquanconfsql);
		$youhuiquanconflist = parent::func_runtime_sql_data($youhuiquanconfsql);
		
		foreach ($youhuiquanconflist as $keys => $vals){
			$youhuiquanconflist[$keys]['smallpic'] = HyItems::hy_qiniuimgurl('duibao-basic',$youhuiquanconflist[$keys]['smallpic'],$this->imgwidth,$this->imgheight);
		}
		
		$echojsonstr = HyItems::echo2clientjson('100','数据获取成功',$youhuiquanconflist);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	}
	
	public function controller_exec4(){
		
		if($this->imgwidth==''){
			$this->imgwidth='640';
		}
		if($this->imgheight==''){
			$this->imgheight='200';
		}
		$youhuiquanconfsql  = "select * from xb_lunbotu where flag='1' and biaoshi='7' limit 1";
		//$youhuiquanconflist = parent::__get('HyDb')->get_row($youhuiquanconfsql);
		$youhuiquanconflist = parent::func_runtime_sql_data($youhuiquanconfsql);
		
		
		$youhuiquanconflist[0]['img'] = HyItems::hy_qiniuimgurl('duibao-basic',$youhuiquanconflist[0]['img'],$this->imgwidth,$this->imgheight);
		
		$echojsonstr = HyItems::echo2clientjson('100','数据获取成功',$youhuiquanconflist);
		if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	}
	
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		if($this->type=='1'){//兑宝热门免费
			$ret = $this->controller_exec1();
		}else if($this->type=='2'){
			$ret = $this->controller_exec2();
		}else if($this->type=='3'){
			$ret = $this->controller_exec3();
		}else if($this->type=='4'){
			$ret = $this->controller_exec4();
		}else{
			$echojsonstr = HyItems::echo2clientjson('301','类型错误');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		
		return $ret;
	}
	
}