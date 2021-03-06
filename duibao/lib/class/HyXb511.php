<?php
/*
 * 商品详情信息的展示
 */
class HyXb511 extends HyXb{
	
	
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
	
		$this->productid = isset($input_data['productid'])? $input_data['productid']:'';  //商品的类型id
	
	}
	
	
	//详情信息的获取
	public function controller_getproductdetail(){
		
		
		
		//店铺信息
		$storenamearr = array();
		$storelogoarr = array();
		$storephonearr = array();
		$storeinfo = "select id,storename,touxiang,username,phone from shop_site where flag=1 and checkstatus=2  ";
		$storeinfolist = parent::__get('HyDb')->get_all($storeinfo);
		
		foreach ($storeinfolist as $keys=>$vals){
			
			$storenamearr[$storeinfolist[$keys]['id']] = $storeinfolist[$keys]['storename'];
			$storelogoarr[$storeinfolist[$keys]['id']] = $storeinfolist[$keys]['touxiang'];
			$storephonearr[$storeinfolist[$keys]['id']] = $storeinfolist[$keys]['phone'];
		}
		
		
		//商品信息
		$productdetailsql = "select * from shop_product 
				where flag=1 and status=1 and onsales=1 and id='".$this->productid."'";
		
		$productdetaillist = parent::__get('HyDb')->get_row($productdetailsql);
		
		
		//第三方店铺链接
		$shopdata_sql  = "select * from shop_store where siteid='".$productdetaillist['siteid']."' ";
		//echo $shopdata_sql;
		$shopdata_list = parent::__get('HyDb')->get_all($shopdata_sql); 
		
		foreach ($shopdata_list as $keys=>$vals){
			
			if($shopdata_list[$keys]['shopname']===null){
				$shopdata_list[$keys]['shopname']='';
			}
			if($shopdata_list[$keys]['shoplogo']===null){
				$shopdata_list[$keys]['shoplogo']='';
			}
			if($shopdata_list[$keys]['shoptype']===null){
				$shopdata_list[$keys]['shoptype']='';
			}
			if($shopdata_list[$keys]['shopurl']===null){
				$shopdata_list[$keys]['shopurl']='';
			}
			
		}
		
		//var_dump($shopdata_list['shopname']);
		
		//用户浏览记录的增加
		$updatesql = "update shop_site set renqi=renqi+1 where id='".$productdetaillist['siteid']."' ";
		$updatelist = parent::__get('HyDb')->execute($updatesql);
		
		
		//下载次数的增加
		$downloadsql  = "update shop_product set buycount=buycount+1 where id='".$this->productid."' ";
		$downloadlist = parent::__get('HyDb')->execute($downloadsql);
		
		
		if($productdetaillist['id']>0){
			
			if($this->width==''){//753 * 292
				$this->width='800';
			}
				
			if($this->height==''){
				$this->height='800';
			}
			
			
			$replace = array("\t", "\r", "\n",);
				
			//图片展示
			$arr = unserialize(BUCKETSTR);//获取七牛访问链接
			if($productdetaillist['showpic1']!=null){
				
				if(substr($productdetaillist['showpic1'],0,7)=='http://' ||substr($productdetaillist['showpic1'],0,8)=='https://' ){
					$productdetaillist['showpic1'] = str_replace($replace, '', $productdetaillist['showpic1']);
				}else{
					$productdetaillist['showpic1'] = $arr['duibao-shop'].$productdetaillist['showpic1'].'?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
					$productdetaillist['showpic1'] = str_replace($replace, '', $productdetaillist['showpic1']);
				}
				
			}
			
			
			if($productdetaillist['showpic2']!=null){
				if(substr($productdetaillist['showpic2'],0,7)=='http://' ||substr($productdetaillist['showpic2'],0,8)=='https://' ){
					$productdetaillist['showpic2'] = str_replace($replace, '', $productdetaillist['showpic2']);
				}else{
					$productdetaillist['showpic2'] = $arr['duibao-shop'].$productdetaillist['showpic2'].'?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
					$productdetaillist['showpic2'] = str_replace($replace, '', $productdetaillist['showpic2']);
				}
			}
			
			
			
			if($productdetaillist['showpic3']!=null){
				
				if(substr($productdetaillist['showpic3'],0,7)=='http://' ||substr($productdetaillist['showpic3'],0,8)=='https://' ){
					$productdetaillist['showpic3'] = str_replace($replace, '', $productdetaillist['showpic3']);
				}else{
					$productdetaillist['showpic3'] = $arr['duibao-shop'].$productdetaillist['showpic3'].'?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
					$productdetaillist['showpic3'] = str_replace($replace, '', $productdetaillist['showpic3']);
				}
					
			}
			if($productdetaillist['showpic4']!=null){
				
				if(substr($productdetaillist['showpic4'],0,7)=='http://' ||substr($productdetaillist['showpic4'],0,8)=='https://' ){
					$productdetaillist['showpic4'] = str_replace($replace, '', $productdetaillist['showpic4']);
				}else{
					$productdetaillist['showpic4'] = $arr['duibao-shop'].$productdetaillist['showpic4'].'?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
					$productdetaillist['showpic4'] = str_replace($replace, '', $productdetaillist['showpic4']);
				}
					
			}
			if($productdetaillist['showpic5']!=null){
				
				if(substr($productdetaillist['showpic5'],0,7)=='http://' ||substr($productdetaillist['showpic5'],0,8)=='https://' ){
					$productdetaillist['showpic5'] = str_replace($replace, '', $productdetaillist['showpic5']);
				}else{
					$productdetaillist['showpic5'] = $arr['duibao-shop'].$productdetaillist['showpic5'].'?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
					$productdetaillist['showpic5'] = str_replace($replace, '', $productdetaillist['showpic5']);
				}
					
			}
			
			
			
			
			if($productdetaillist['feetype']=='5'){
				//$productdetaillist['prizeurl'] = 'http://120.27.34.239:8018/choujiang/index.php'; 
				$productdetaillist['prizeurl'] = 'http://xbapp.xinyouxingkong.com/choujiang/index.php'; 
			}else{
				
				$productdetaillist['prizeurl']='';
			}
			
			
			if($productdetaillist['feetype']=='5'){
			}else{
				$productdetaillist['video_url']='';
			}
			
			
			$productdetaillist['shoparr'] = $shopdata_list;
			
			
			if($productdetaillist['kucun']<=0){
				$productdetaillist['productnum'] = '9';//库存不足
			}else{
				$productdetaillist['productnum'] = '1';//库存充足
			}
			//商品详情
			$productdetaillist['miaoshu'] = htmlspecialchars_decode($productdetaillist['miaoshu']);
			//商品的店铺名
			$productdetaillist['storename'] = isset($storenamearr[$productdetaillist['siteid']])?$storenamearr[$productdetaillist['siteid']]:'';
			//商品logo
			$productdetaillist['storelogo'] = isset($storelogoarr[$productdetaillist['siteid']])?$storelogoarr[$productdetaillist['siteid']]:'';
			
			$productdetaillist['phone'] = isset($storephonearr[$productdetaillist['siteid']])?$storephonearr[$productdetaillist['siteid']]:'';
			
			if($productdetaillist['feetype']=='4'){//免费商品
				$productdetaillist['scoremoney']='免费';
			}else if($productdetaillist['feetype']=='1'){
				$productdetaillist['scoremoney'] = '¥'.$productdetaillist['price'].'+'.$productdetaillist['score'].'馅饼';
			}else if($productdetaillist['feetype']=='2'){
				
				$productdetaillist['price'] = number_format($productdetaillist['price'] /100, 2);
				
				//$productdetaillist['scoremoney'] = '¥'.$productdetaillist['price'];
				
			}else if($productdetaillist['feetype']=='5'){
				$productdetaillist['scoremoney']='免费';
			}
			
			
			
			$productdetaillist['downloadnum'] = '568'+$productdetaillist['buycount'];
			
			if($productdetaillist['pickup']=='1'){
				$productdetaillist['pickup']='自提';
			}else if($productdetaillist['pickup']=='2'){
				$productdetaillist['pickup']='包邮';
			}else{
				$productdetaillist['pickup']='包邮';
			}
			
			
			if($productdetaillist['miyao_type']==''){
				$productdetaillist['miyao_type']='';
			}
			
			if($productdetaillist['fafang_type']==''){
				$productdetaillist['fafang_type']='';
			}
			
			
			
			
			
			
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '商品详情获取成功';
			$echoarr['dataarr'] = $productdetaillist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '该商品不存在';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		
	}
	
	
	//操作入口--商品详情信息的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='511'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//商品id的判断
		if($this->productid==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '商品id不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
				
		}
	
		//判断每页的条数，数值介于1到20之间
		if($this->count<0 || $this->count>20){
	
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '每页展示的条数超过20条';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
	
		}
	
	
		//商品详情信息的入口
		$this->controller_getproductdetail();
	
		return true;
	}
	
	
}