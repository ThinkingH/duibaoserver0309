<?php
/*
 * 商城首页商品列表的获取
 */

class HyXb501 extends HyXb{
	
	
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
	
		$this->count = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
		
		$this->type  = isset($input_data['type'])?$input_data['type']:'';     //商品类型 10--全部 11--流量 13--电子卡 14--优惠卡 22--实物
		
	
	}
	
	
	//商品列表的获取
	public function controller_getproductlist(){
		
		//获取用户的类型
		$usertype = parent::__get('xb_usertype');
		
		if($this->type=='1'){
			$wherestr = " where flag=1 and status=1 and onsales=1 and feetype=1  ";  // 1--全部
		}else if($this->type=='2'){
			$wherestr = " where flag=1 and status=1 and onsales=1 and hottypeid=101 ";  //2-获取今日推荐的数据
		}
		
		
		if($this->page=='' || $this->page=='0'){
			$this->page=1;
		}
		
		if($this->count=='' || $this->count=='undefined'){
			$this->count=10;
		}
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->count;
		
		$returnarr = array();
		
		//获取商品的总条数
		$productnumsql  = "select count(*) as num from shop_product $wherestr ";
		$productnumlist = parent::__get('HyDb')->get_all($productnumsql); 
		
		if($productnumlist[0]['num']>0){
			$returnarr['maxcon'] = $productnumlist[0]['num'];//总条数
		}else{
			$returnarr['maxcon']= 0;
		}
		
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		//商品数据列表的获取
		$shangpinsql  = "select id,siteid,typeid,name,price,
						score,mainpic,xiangqingurl,buycount,pingjiacount,feetype 
						from shop_product 
						 $wherestr order by orderbyid asc,id desc limit $firstpage,$pagesize ";
		$shangpinlist = parent::__get('HyDb')->get_all($shangpinsql); 
		
		
		foreach ($shangpinlist as $keys=>$vals){
			
			if($this->width==''){//753 * 292
				$this->width='800';
			}
			
			if($this->height==''){
				$this->height='800';
			}
			
			$replace = array("\t", "\r", "\n",);
			
			//图片展示
			$arr = unserialize(BUCKETSTR);//获取七牛访问链接
			if(substr($shangpinlist[$keys]['mainpic'],0,7)=='http://' ||substr($shangpinlist[$keys]['mainpic'],0,8)=='https://' ){
				//[$keys]['img'] = 'https://ojlty2hua.qnssl.com/image-1500545214106-NTk1Y2FlOWNlMzE2MC5wbmc=.png?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
				$shangpinlist[$keys]['mainpic'] = str_replace($replace, '', $shangpinlist[$keys]['mainpic']);
			}else{
				$shangpinlist[$keys]['mainpic'] = $arr['duibao-shop'].$shangpinlist[$keys]['mainpic'].'?imageView2/1/w/'.$this->width.'/h/'.$this->height.'/q/75|imageslim';
				$shangpinlist[$keys]['mainpic'] = str_replace($replace, '', $shangpinlist[$keys]['mainpic']);
			}
			
			
			if($shangpinlist[$keys]['feetype']=='1'){//积分number_format($paymoney / 100, 2)
				$shangpinlist[$keys]['scoremoney'] = '¥'.$shangpinlist[$keys]['price'].'+'.$shangpinlist[$keys]['score'].'馅饼';
			}else if($shangpinlist[$keys]['feetype']=='2'){
				$shangpinlist[$keys]['scoremoney'] = '¥'.number_format($shangpinlist[$keys]['price'] / 100, 2).'+'.$shangpinlist[$keys]['score'].'馅饼';
			}else if($shangpinlist[$keys]['feetype']=='4'){
				$shangpinlist[$keys]['scoremoney'] = '免费';
			}else if($shangpinlist[$keys]['feetype']=='5'){
				$shangpinlist[$keys]['scoremoney'] = '免费';
			}
			
			
			
			
			$shangpinlist[$keys]['downloadnum'] = '568'+$shangpinlist[$keys]['buycount'];
		}
		
		
		
		if(count($shangpinlist)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '推荐商品列表获取成功';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $shangpinlist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '推荐商品列表为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
	}
	
	
	//操作入口--商品列表的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='501'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
	
		//类型不能为空
		if($this->type==''){
			
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '商品类型展示字段不能为空';
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
	
	
		//商品列表的获取入口
		$this->controller_getproductlist();
	
		return true;
	}
	
}