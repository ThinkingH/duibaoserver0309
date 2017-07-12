<?php
/*
 * 商品的搜索列表
 */
class HyXb604 extends HyXb{
	
	
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
	
		$this->proname = isset($input_data['proname'])? $input_data['proname']:'';  //商品模糊搜索
	
		$this->count = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
	
	}
	
	
	//商品的搜索
	public function controller_searchproductlist(){
		
		//获取网站数据读取的类型
		$z = parent::__get('wangzhantype');
		
		$where = '';
		
		if($this->proname==''){
			
			$where = " flag=1 and zflag=1 and z='".$z."' ";
		}else{
			$where = " flag=1 and zflag=1 and z='".$z."' and title like '%".$this->proname."%' ";
		}
		
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
		$typesql  = "select count(*) as num from z_quanmainlist where $where ";
		$typelist = parent::__get('HyDb')->get_all($typesql);
		
		if($typelist[0]['num']>0){
			$returnarr['maxcon'] = $typelist[0]['num'];
		}else{
			$returnarr['maxcon'] = 0;
		}
		
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		
		//商品数据列表的获取
		$shangpinsql  = "select id,new_datetime,title,picurl,tiaozhuanurl,yuanprice,nowprice,yilingcon,maintype,childtype from z_quanmainlist 
						where $where   
						order by new_datetime desc limit $firstpage,$pagesize ";
		$shangpinlist = parent::__get('HyDb')->get_all($shangpinsql); 
		
		
		//收藏数据的查询
		$collectsql  = "select quanid,userid from xb_collection where flag='2' and userid = '".parent::__get('xb_userid')."' ";
		$collectlist = parent::__get('HyDb')->get_all($collectsql);
		
		$checktaskarr = array();
		foreach ($collectlist as $vals){
			$checktaskarr[$vals['quanid']] = $vals['quanid'];
		}
		
		foreach ($shangpinlist as $keys=>$vals){
		
			$temptaskid = $shangpinlist[$keys]['id'];
				
			if(isset($checktaskarr[$temptaskid])){
				$shangpinlist[$keys]['collect'] = '11';//已收藏
			}else{
				$shangpinlist[$keys]['collect'] = '22';//未收藏
			}
		}
		
		
		
		
		
		
		
		if($this->proname!=''){
			//搜索列表的记录
			$date = date('Y-m-d H:i:s');
			$historysql = "insert into search_history(userid,name,create_date) values ('".parent::__get('xb_userid')."','".$this->proname."','".$date."')";
			parent::__get('HyDb')->execute($historysql);
		}
		
		
		
		if(count($shangpinlist)>0){
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '搜索列表获取成功';
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
			$echoarr['returnmsg']  = '搜索为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
		
		
		
	}
	
	
	
	//操作入口--商品搜索列表的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		/* //商品名称
		if($this->proname==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '商品查询模糊字段不能为空';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
				
		} */
	
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
		$this->controller_searchproductlist();
	
		return true;
	}
	
	
}