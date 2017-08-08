<?php
/*
 * 附近上传优惠券详情页展示
 */
class HyXb812 extends HyXb{
	
	
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
	
		$this->quanid = isset($input_data['quanid'])? $input_data['quanid']:'';  //优惠券id
	
	}
	
	
	//详情信息的获取
	public function controller_getproductdetail(){
		
		
		$sqldata = "select id,faflag,new_datetime,over_datetime,type,maintype,childtype,title,content,picurl,tiaozhuanurl,yuanprice,nowprice,round(nowprice/yuanprice,2) as bydiscount,yilingcon,address,
					 phone,lat,lng,fenleiming,shopname,pingfen,reamrk,dianzan 
					 from z_tuanmainlist where id='".$this->quanid."' limit 1 ";
		
		
		$listdata = parent::__get('HyDb')->get_all($sqldata);
		
//	print_r($listdata);
		
		
		//点赞数据的查询
		$collectsql  = "select quanid,userid from xb_collection where flag='4' and quanid = '".$this->quanid."' and userid = '".parent::__get('xb_userid')."' ";
		//echo $collectsql;
		$collectlist = parent::__get('HyDb')->get_all($collectsql);
			
		$checktaskarr = array();
		
		foreach ($collectlist as $vals){
			$checktaskarr[$vals['quanid']] = $vals['quanid'];
		}
		
		
		foreach ($listdata as $keys=>$vals){
			
				
			if($listdata[$keys]['yuanprice']=='0'){
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
		
		if(count($listdata)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '详情页获取成功!';
			$echoarr['dataarr'] = $listdata;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '详情页为空!';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}
		
		
	}
	
	
	//操作入口--详情信息的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
		
		//优惠券id的判断
		if($this->quanid==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '优惠券id不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
				
		}
	
		
	
	
		//优惠券详情信息的入口
		$this->controller_getproductdetail();
	
		return true;
	}
	
	
}