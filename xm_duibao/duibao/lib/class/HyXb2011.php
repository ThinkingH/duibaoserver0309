<?php
/*
 * 优惠券列表的输出--
 */
class HyXb201 extends HyXb{
	
	private $count;
	private $page;
	private $quantype;
	private $quanshow;
	
	
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
		$this->quanshow = isset($input_data['quanshow'])? $input_data['quanshow']:'';    //优惠券fen
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
		
		
		$returnarr = array();
		
		//获取总条数
		$quansumsql  = "select count(*) as num from youhuiquan where flag=9 and youxiao";
		$tasksumlist = parent::__get('HyDb')->get_all($tasksumsql);
		
		if($tasksumlist[0]['num']>0){
			$returnarr['maxcon'] = $tasksumlist[0]['num'];//总条数
		}else{
			$returnarr['maxcon'] = 0;//总条数
		}
		
		//总页数
		$returnarr['sumpage'] = ceil($returnarr['maxcon']/$pagesize);
		
		
		
		
		
		
		
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
	
	
		//判断任务传递的类型
		$shuzu = array('1','2');
	
		if(!in_array($this->quantype,$shuzu)){
	
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