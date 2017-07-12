<?php
/*
 * 优惠券列表的输出--
 */
class HyXb201 extends HyXb{
	
	private $count;
	private $page;
	private $quantype; //优惠券的类型
	private $quanshow;//优惠券的展示分类
	
	
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
	
	
		$this->quantype = isset($input_data['quantype'])? $input_data['quantype']:'';    //优惠券类型--kfc,bsk
		$this->quanshow = isset($input_data['quanshow'])? $input_data['quanshow']:'';    //优惠券分类展示
		$this->count = isset($input_data['count'])? $input_data['count']:'';  //每页显示的最大条数
		$this->page  = isset($input_data['page'])?$input_data['page']:'';     //页数
		
	
	}
	
	
	//优惠券的主要操作
	public function controller_getquanlist(){
		
		
		//获取用户的类型
		$usertype = parent::__get('xb_usertype');
		
		if($this->page=='' || $this->page=='0'){
		
			$this->page=1;
		}
		
		if($this->count=='' || $this->count=='undefined'){
				
			$this->count=10;
		}
		
		$firstpage = ($this->page-1)*$this->count;
		$pagesize  = $this->page*$this->count;
		
		//每个类型下的总条数
		if($this->quanshow=='1'){//显示全部的
			
			if($this->quantype==''){
				$where = " where flag=1 and youxiao='ok' ";
				
			}else if($this->quantype!=''){
				$where= " where flag=1 and youxiao='ok' and type='".$this->quantype."' ";
			}
			
		}else if($this->quanshow=='2'){//系统推荐
			
			if($this->quantype==''){
				
				$where = " where flag=1 and youxiao='ok' and tuijian=1";
				
			}else if($this->quantype!=''){
				
				$where = " where flag=1 and youxiao='ok' and type='".$this->quantype."' and tuijian=1";
			}
			
		}else if($this->quanshow=='3'){
			$where = '';
		}
		
		$returnarr = array();
		
		//获取总条数
		$quansumsql  = "select count(*) as num from youhuiquan $where ";
		$quansumlist = parent::__get('HyDb')->get_all($quansumsql);
		
		if($quansumlist[0]['num']>0){
			$returnarr['maxcon'] = $quansumlist[0]['num'];//总条数
		}else{
			$returnarr['maxcon'] = 0;//总条数
		}
		
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		
		//用户的分组查询
		if($this->quanshow=='1'){//显示全部数据
			//优惠券数据的查询
			$youhuiquansql  = "select * from youhuiquan $where order by id desc limit $firstpage,$pagesize";
			$youhuiquanlist = parent::__get('HyDb')->get_all($youhuiquansql);
			
			//收藏数据的查询
			$collectsql  = "select quanid,userid from xb_collection where userid = '".parent::__get('xb_userid')."' ";
			$collectlist = parent::__get('HyDb')->get_all($collectsql);
			
			$checktaskarr = array();
			foreach ($collectlist as $vals){
				$checktaskarr[$vals['quanid']] = $vals['quanid'];
			}
			
			foreach ($youhuiquanlist as $keys => $vals){
				
				$youhuiquanlist[$keys]['quanid'] = $youhuiquanlist[$keys]['id'];
				
				$temptaskid = $youhuiquanlist[$keys]['id'];
				
				if(isset($checktaskarr[$temptaskid])){
					$youhuiquanlist[$keys]['collect'] = '11';//已收藏
				}else{
					$youhuiquanlist[$keys]['collect'] = '22';//未收藏
				}
				
			}
			
			
		}else if($this->quanshow=='2'){//系统推荐
			
			$youhuiquansql  = "select * from youhuiquan $where order by id desc limit $firstpage,$pagesize";
			$youhuiquanlist = parent::__get('HyDb')->get_all($youhuiquansql);
			
			//收藏数据的查询
			$collectsql  = "select quanid,userid from xb_collection where userid = '".parent::__get('xb_userid')."' ";
			$collectlist = parent::__get('HyDb')->get_all($collectsql);
				
			$checktaskarr = array();
			foreach ($collectlist as $vals){
				
				$checktaskarr[$vals['quanid']] = $vals['quanid'];
			}
				
			foreach ($youhuiquanlist as $keys => $vals){
				$youhuiquanlist[$keys]['quanid'] = $youhuiquanlist[$keys]['id'];
				$temptaskid = $youhuiquanlist[$keys]['id'];
			
				if(isset($checktaskarr[$temptaskid])){
					$youhuiquanlist[$keys]['collect'] = '11';//已收藏
				}else{
					$youhuiquanlist[$keys]['collect'] = '22';//未收藏
				}
			
			}
			
			
		}else if($this->quanshow=='3'){//收藏
			
			
			//获取收藏的总条数
			$returnarr = array();
			
			//获取总条数
			$sqlcollect  = "select count(*) as num from xb_collection where flag=1 and userid='".parent::__get('xb_userid')."' ";
			$listcollect = parent::__get('HyDb')->get_all($sqlcollect);
			
			if($listcollect[0]['num']>0){
				$returnarr['maxcon'] = $listcollect[0]['num'];//总条数
			}else{
				$returnarr['maxcon'] = 0;//总条数
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
			$youhuiquandatasql  = "select id,flag,tuijian,type,youxiao,youxiaoqi,jiage,imgurl,title,content,theurl from youhuiquan";
			$youhuiquandatalist = parent::__get('HyDb')->get_all($youhuiquandatasql);
			
			foreach ($youhuiquandatalist as $vals){
				$quanidarr[$vals['id']] = $vals['id'];
				$quantuijianarr[$vals['id']] = $vals['tuijian'];
				$quanflagarr[$vals['id']]    = $vals['flag'];
				$quantypearr[$vals['id']]    = $vals['type'];
				$quanyouxiaoarr[$vals['id']] = $vals['youxiao'];
				$quanyxqarr[$vals['id']]     = $vals['youxiaoqi'];
				$quanjiagearr[$vals['id']]  = $vals['jiage'];
				$quanimgarr[$vals['id']]    = $vals['imgurl'];
				$quantitlearr[$vals['id']]  = $vals['title'];
				$quancontentarr[$vals['id']] = $vals['content'];
				$quantheurlarr[$vals['id']] = $vals['theurl'];
			}
			
			//查询收藏表
			$collectdatasql  = "select id,quanid from xb_collection where flag=1 and userid='".parent::__get('xb_userid')."' order by id desc limit $firstpage,$pagesize ";
			$youhuiquanlist = parent::__get('HyDb')->get_all($collectdatasql);
			
			foreach ($youhuiquanlist as $key => $val){
				
				$youhuiquanlist[$key]['id']        = $youhuiquanlist[$key]['id'];
				$youhuiquanlist[$key]['quanid']    = $quanidarr[$youhuiquanlist[$key]['quanid']];
				$youhuiquanlist[$key]['flag']      = $quanflagarr[$youhuiquanlist[$key]['quanid']];
				$youhuiquanlist[$key]['tuijian']   = $quantuijianarr[$youhuiquanlist[$key]['quanid']];
				$youhuiquanlist[$key]['type']      = $quantypearr[$youhuiquanlist[$key]['quanid']];
				$youhuiquanlist[$key]['youxiao']   = $quanyouxiaoarr[$youhuiquanlist[$key]['quanid']];
				$youhuiquanlist[$key]['youxiaoqi'] = $quanyxqarr[$youhuiquanlist[$key]['quanid']];
				$youhuiquanlist[$key]['jiage']   = $quanjiagearr[$youhuiquanlist[$key]['quanid']];
				$youhuiquanlist[$key]['imgurl']  = $quanimgarr[$youhuiquanlist[$key]['quanid']];
				$youhuiquanlist[$key]['title']   = $quantitlearr[$youhuiquanlist[$key]['quanid']];
				$youhuiquanlist[$key]['content'] = $quancontentarr[$youhuiquanlist[$key]['quanid']];
				$youhuiquanlist[$key]['theurl']  = $quantheurlarr[$youhuiquanlist[$key]['quanid']];
				$youhuiquanlist[$key]['collect']  = '11';//已收藏
			}
			
			
			
		}
		
		
		if(count($youhuiquanlist)>0){
			
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '列表获取成功';
			$echoarr['maxcon']  = $returnarr['maxcon'];
			$echoarr['sumpage'] = $returnarr['sumpage'];
			$echoarr['nowpage'] = $this->page;
			$echoarr['dataarr'] = $youhuiquanlist;
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return true;
		}else{
			$echoarr = array();
			$echoarr['returncode'] = 'success';
			$echoarr['returnmsg']  = '列表为空';
			$echoarr['dataarr'] = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		
	}
	
	
	//操作入口--优惠券列表的获取
	public function controller_init(){
	
	
		//基本参数的判断,md5key判断，时间戳的判断
		$r = parent::func_base_check();
	
		if($r===false){
	
			return false;
		}
	
	
		//操作类型的判断
		if(parent::__get('xb_thetype')!='201'){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '操作类型错误';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		}
		
		//优惠券类型
		/* if($this->quantype==''){
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '优惠券的类型不能为空！';
			$echoarr['dataarr']    = array();
			$logstr = $echoarr['returncode'].'-----'.$echoarr['returnmsg']."\n"; //日志写入
			parent::hy_log_str_add($logstr);
			echo json_encode($echoarr);
			return false;
		} */
	
	
		//判断任务传递的类型
		$shuzu = array('1','2','3');
	
		if(!in_array($this->quanshow,$shuzu)){
	
			$echoarr = array();
			$echoarr['returncode'] = 'error';
			$echoarr['returnmsg']  = '用户传递的优惠券分组参数错误！';
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
	
	
		//优惠券列表的获取入口
		$this->controller_getquanlist();
	
		return true;
	}
	
	
	
	
	
	
	
}