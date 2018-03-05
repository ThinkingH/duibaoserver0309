<?php
/*
 * 附近上传优惠券详情页展示
 */
class HyXb1026 extends HyXb{
	
	private $pagesize;
	private $page;
	private $imgwidth;
	private $imgheight;
	private $typeid; //优惠券id
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		
		$this->typeid = isset($input_data['typeid'])?$input_data['typeid']:'';
	}
	
	
	public function controller_exec1(){
		
		$sqldata = "select id,faflag,new_datetime,over_datetime,type,maintype,childtype,title,content,picurl,tiaozhuanurl,yuanprice,nowprice,round(nowprice/yuanprice,2) as bydiscount,yilingcon,address,
					 phone,lat,lng,fenleiming,shopname,pingfen,reamrk,dianzan
					 from z_fabulist where id='".$this->typeid."' limit 1 ";
		$listdata = parent::__get('HyDb')->get_all($sqldata);
		
		//点赞数据的查询
		$collectsql  = "select quanid,userid from xb_collection where flag='4' and quanid = '".$this->typeid."' and userid = '".parent::__get('userid')."' ";
		$collectlist = parent::__get('HyDb')->get_all($collectsql);
		
		$checktaskarr = array();
		foreach ($collectlist as $vals){
			$checktaskarr[$vals['quanid']] = $vals['quanid'];
		}
		
		foreach ($listdata as $keys=>$vals){
			
			if($listdata[$keys]['yuanprice']=='0' || $listdata[$keys]['yuanprice']==''){
				$listdata[$keys]['discount']='0';
			}else{
				$listdata[$keys]['discount'] = round($listdata[$keys]['nowprice']/$listdata[$keys]['yuanprice'],2)*10;
			}
			if(strlen($listdata[$keys]['discount'])=='1'){
				$listdata[$keys]['discount'] = $listdata[$keys]['discount'].'.0';
			}
			if($listdata[$keys]['discount']=='0'){
				if(strlen($listdata[$keys]['reamrk'])=='1'){
					$listdata[$keys]['discount'] = $listdata[$keys]['reamrk'].'.0';
				}
			}
			if($listdata[$keys]['over_datetime']==''){
			
			}else{
				$listdata[$keys]['over_datetime'] = substr($listdata[$keys]['over_datetime'],0,10);
			}
			
			//标题空格的去除
			$listdata[$keys]['title'] = str_replace(' ','',$listdata[$keys]['title']);
			
			
			if($listdata[$keys]['pingfen']>'0' && $listdata[$keys]['pingfen']<='1.5'){
			
				$listdata[$keys]['geshu']= '1';
			}else if($listdata[$keys]['pingfen']>'1.5' && $listdata[$keys]['pingfen']<='2.5'){
			
				$listdata[$keys]['geshu']= '2';
			}else if($listdata[$keys]['pingfen']>'2.5' && $listdata[$keys]['pingfen']<='3.5'){
				$listdata[$keys]['geshu']= '3';
			}else if($listdata[$keys]['pingfen']>'3.5' && $listdata[$keys]['pingfen']<='4.5'){
				$listdata[$keys]['geshu']= '4';
			}else if($listdata[$keys]['pingfen']>'4.5' && $listdata[$keys]['pingfen']<='5.0'){
				$listdata[$keys]['geshu']= '5';
			}
			
			
			$temptaskid = $listdata[$keys]['id'];
			
			if(isset($checktaskarr[$temptaskid])){
				$listdata[$keys]['dflag'] = '1';//已点赞
			}else{
				$listdata[$keys]['dflag'] = '2';//未点赞
			}
		}
		
		$echojsonstr = HyItems::echo2clientjson('100','数据获取成功',$listdata);
		if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
		
	}
	
	
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		$ret = $this->controller_exec1();
		
		return $ret;
	}
	
}