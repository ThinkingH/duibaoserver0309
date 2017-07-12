<?php
/*
 * 收藏列表的获取
 */

class HyXb701 extends HyXb{
	
	
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
	
	
	}
	
	
	//收藏列表
	public function controller_getcollectlist(){
		
		//获取网站数据读取的类型
		$z = parent::__get('wangzhantype');
		
		if($this->page=='' || $this->page=='0'){
			$this->page=1;
		}
			
		if($this->count=='' || $this->count=='undefined'){
			$this->count=10;
		}
			
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;
			
		$returnarr = array();
			
		//分类数据的查询
		$typesql  = "select count(*) as num from xb_collection where flag=2  and userid = '".parent::__get('xb_userid')."' ";
		$typelist = parent::__get('HyDb')->get_all($typesql);
			
		if($typelist[0]['num']>0){
			$returnarr['maxcon'] = $typelist[0]['num'];
		}else{
			$returnarr['maxcon'] = 0;
		}
			
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		
		$quanidarr      = array();
		$quantuijianarr = array();
		$quanflagarr = array();
		$quantypearr = array();
		$quanyouxiaoarr = array();
		$quanyxqarr     = array();
		$quanjiagearr   = array();
		$quanimgarr     = array();
		$quantitlearr   = array();
		$quancontentarr = array();
		$quantheurlarr  = array();
			
		//查询优惠券表
		$youhuiquandatasql  = "select id,title,picurl,yuanprice,nowprice,yilingcon,type from z_quanmainlist where z='".$z."' and flag='1' and zflag='1' ";
		$youhuiquandatalist = parent::__get('HyDb')->get_all($youhuiquandatasql);
		
		
			
		foreach ($youhuiquandatalist as $vals){
			$quanidarr[$vals['id']]      = $vals['id'];
			$quantuijianarr[$vals['id']] = $vals['nowprice'];
			$quanflagarr[$vals['id']]    = $vals['yilingcon'];
			$quantypearr[$vals['id']]    = $vals['type'];
			$quanjiagearr[$vals['id']]  = $vals['yuanprice'];
			$quanimgarr[$vals['id']]    = $vals['picurl'];
			$quantitlearr[$vals['id']]  = $vals['title'];
		}
		
		
		$collectsql  = "select flag,quanid,userid,quantype,createtime from xb_collection where flag=2 and userid = '".parent::__get('xb_userid')."' ";
		$collectlist = parent::__get('HyDb')->get_all($collectsql);
		
		
		
		foreach ($collectlist as $keys => $vals){
			
			$collectlist[$keys]['id'] =  $collectlist[$keys]['quanid'];
			
			$collectlist[$keys]['nowprice'] =  $quantuijianarr[$collectlist[$keys]['quanid']];
			$collectlist[$keys]['yuanprice'] = $quanjiagearr[$collectlist[$keys]['quanid']];
			$collectlist[$keys]['yilingcon'] = $quanflagarr[$collectlist[$keys]['quanid']];
			$collectlist[$keys]['picurl']    = $quanimgarr[$collectlist[$keys]['quanid']];
			$collectlist[$keys]['title']     = $quantitlearr[$collectlist[$keys]['quanid']];
			$collectlist[$keys]['type']     = $quantypearr[$collectlist[$keys]['quanid']];
			$collectlist[$keys]['collect']  = '11';//已收藏
			
		}
		
		if(count($collectlist)>0){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '收藏列表获取成功!';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $collectlist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
			
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '收藏列表为空!';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}
		
		
		
		
		
		
		
		
		
	}
	
	
	
	
	
	
	
	//操作入口--收藏列表的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
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
	
	
		//优惠券列表的获取入口
		$this->controller_getcollectlist();
	
		return true;
	}
	
	
	
}