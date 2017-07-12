<?php
/*
 * 淘宝联盟--首页类型分类接口
 */

class HyXb6605 extends HyXb{
	
	private $quantype;
	
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
	
		$this->quantype = isset($input_data['quantype'])? $input_data['quantype']:'';  //首页商品类型
		$this->count = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
	
	}
	
	
	public function controller_shouyetypelist(){
		
		
		$sql_where = '';
		if($this->quantype=='666'){
			$sql_where .= "  flag=1  ";
		}else{
			$sql_where .= " flag=1 and maintype='".$this->quantype."'";
		}
		
		
		if($this->page=='' || $this->page=='0' || $this->page=='undefined' ){
				$this->page=1;
			}
			
			if($this->count=='' || $this->count=='undefined' || $this->count=='0' ){
				$this->count=10;
			}
			
			$firstpage = ($this->page-1)*$this->count;
			$pagesize  = $this->count;
			
			$returnarr = array();
			
			//分类数据的查询
			$typesql  = "select count(*) as num from z_quantaobaoke where $sql_where ";
			$typelist = parent::__get('HyDb')->get_all($typesql);
			
			if($typelist[0]['num']>0){
				$returnarr['maxcon'] = $typelist[0]['num'];
			}else{
				$returnarr['maxcon'] = 0;
			}
			
			//总页数
			$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
			
			//商品数据列表的获取
			$shangpinsql  = "select id,hyflag,new_datetime,type,maintype,childtype,title,picurl,spicurl,quanurl,quanprice, 
							yuanprice,nowprice from z_quantaobaoke 
							where $sql_where
							order by new_datetime desc,id desc limit $firstpage,$pagesize ";
			//echo $shangpinsql;
			$shangpinlist = parent::__get('HyDb')->get_all($shangpinsql);
			
			
			//收藏数据的查询
			$collectsql  = "select quanid,userid from xb_collection where flag='2' and userid = '".parent::__get('xb_userid')."' ";
			$collectlist = parent::__get('HyDb')->get_all($collectsql);
				
			$checktaskarr = array();
			foreach ($collectlist as $vals){
				$checktaskarr[$vals['quanid']] = $vals['quanid'];
			}
			
			$con = 0;
			$conn = 0;
			
			foreach ($shangpinlist as $keys=>$vals){
				++$con;
				
				$temptaskid = $shangpinlist[$keys]['id'];
					
				if(isset($checktaskarr[$temptaskid])){
					$shangpinlist[$keys]['collect'] = '11';//已收藏
				}else{
					$shangpinlist[$keys]['collect'] = '22';//未收藏
				}
				
				$shangpinlist[$keys]['gflag'] = '1';
				
				/* $dataarr = parent::func_advertisement($this->page);  */
				
				if($con=='10'){
					
					$page = $this->page-1;
					$dataarr = parent::func_advertisement($page);
						
					if($dataarr['id']>0){//有广告存在可以进行插入
					
						$shangpinlist[$keys]['gflag'] = $dataarr['gflag'];//广告类型1-优惠券 2-网页下载 3-下载广告
						if($dataarr['gtype']=='1'){
							$dataarr['gtype']='任务';
						}else if($dataarr['gtype']=='2'){
							$dataarr['gtype']='广告';
						}
						$shangpinlist[$keys]['gtype'] = $dataarr['gtype'];//1-任务 2-广告
						$shangpinlist[$keys]['url'] = $dataarr['picurl'];//图片链接
						$shangpinlist[$keys]['adurl'] = $dataarr['adurl'];//广告跳转链接
						$shangpinlist[$keys]['taskid'] = $dataarr['taskid'];//任务下载编号
						$shangpinlist[$keys]['adtitle'] = $dataarr['adtitle'];//广告标题
						$shangpinlist[$keys]['adcontent'] = $dataarr['adcontent'];//广告描述
						$shangpinlist[$keys]['childtype'] = '广告';//广告导航栏
					
					}
					
				}
				
				if($con=='20'){
					
					$page = $this->page-1;
					$dataarr = parent::func_advertisement($page,3);
					
					if($dataarr['id']>0){//有广告存在可以进行插入
							
						$shangpinlist[$keys]['gflag'] = $dataarr['gflag'];//广告类型1-优惠券 2-网页下载 3-下载广告
						if($dataarr['gtype']=='1'){
							$dataarr['gtype']='任务';
						}else if($dataarr['gtype']=='2'){
							$dataarr['gtype']='广告';
						}
						$shangpinlist[$keys]['gtype'] = $dataarr['gtype'];//1-任务 2-广告
						$shangpinlist[$keys]['url'] = $dataarr['picurl'];//图片链接
						$shangpinlist[$keys]['adurl'] = $dataarr['adurl'];//广告跳转链接
						$shangpinlist[$keys]['taskid'] = $dataarr['taskid'];//任务下载编号
						$shangpinlist[$keys]['adtitle'] = $dataarr['adtitle'];//广告标题
						$shangpinlist[$keys]['adcontent'] = $dataarr['adcontent'];//广告描述
						$shangpinlist[$keys]['childtype'] = '广告';//广告导航栏
								
					}
					
					
				}
				/* if($shangpinlist[$keys]['id']%10==0){//每10条显示一条广告
				
				    $page = $this->page-1;
					$dataarr = parent::func_advertisement($page);
					
					if($dataarr['id']>0){//有广告存在可以进行插入
						
						$shangpinlist[$keys]['gflag'] = $dataarr['gflag'];//广告类型1-优惠券 2-网页下载 3-下载广告
						$shangpinlist[$keys]['gtype'] = $dataarr['gtype'];//1-任务 2-广告
						$shangpinlist[$keys]['url'] = $dataarr['picurl'];//图片链接
						$shangpinlist[$keys]['adurl'] = $dataarr['adurl'];//广告跳转链接
						$shangpinlist[$keys]['taskid'] = $dataarr['taskid'];//任务下载编号
						$shangpinlist[$keys]['adtitle'] = $dataarr['adtitle'];//广告标题
						$shangpinlist[$keys]['adcontent'] = $dataarr['adcontent'];//广告描述
						
					}
				} */
					
				
				
				
			}
			
			
			
			if(count($shangpinlist)>0){
				
				$echoarr = array();
				$echoarr['returncode'] = 'success';
				$echoarr['returnmsg']  = '主分类列表获取成功!';
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
				$echoarr['returnmsg']  = '主分类列表为空!';
				$echoarr['dataarr'] = array();
				$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
				parent::hy_log_str_add($logstr);
				echo json_encode($echoarr);
				return true;
			}
		
		
	}
	
	
	
	
	
	
	//操作入口--首页类型的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
		/* //商品类型的判断
		if($this->quantype==''){
	
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '首页类型不能为空！';
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
	
	
		//首页分类
		$this->controller_shouyetypelist();
	
		return true;
	}
	
	
	
}