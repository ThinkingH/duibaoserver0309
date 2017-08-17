<?php
/*
 * 支付主操作类库
 */
class HyRep {
	
	private $log_filepath;    //日志文件对应目录
	private $log_filename;    //日志文件对应民名称
	
	private $HyDb;         //数据库初始化变量
	
	private $payflag = 0; //订单状态标识，20创建成功，21创建失败，30支付成功，31支付失败
	private $cg_payid;
	private $cg_name;
	private $cg_account;
	private $cg_passwd;
	private $cg_back1;
	private $cg_back2;
	private $cg_back3;
	private $cg_back4;
	private $cg_back5;
	private $cg_createurl;
	private $cg_tuifeiurl;
	private $cg_selecturl;
	private $cg_backurl;
	
	private $d_siteid;
	private $d_typeid;
	private $d_stat;
	private $d_paymoney;
	private $d_sj_orderid;
	private $d_myorderid;
	private $d_tcid;
	private $d_openid;
	private $d_ystatus;    //上家错误码
	private $d_ymessage;   //上家错误码描述
	
	
	private $remark;  //当做透传参数使用
	
	
	
	public function __construct($input_data){
		
		$mname              = isset($input_data['mname'])?$input_data['mname']:'';
		$this->cg_payid     = isset($input_data['payid'])?$input_data['payid']:'';
		
		$this->d_siteid     = isset($input_data['siteid'])?$input_data['siteid']:'';
		$this->d_typeid     = isset($input_data['typeid'])?$input_data['typeid']:'';
		$this->d_stat       = isset($input_data['stat'])?$input_data['stat']:'';
		$this->d_paymoney   = isset($input_data['paymoney'])?$input_data['paymoney']:'';
		$this->d_sj_orderid = isset($input_data['sj_orderid'])?$input_data['sj_orderid']:'';
		$this->d_myorderid  = isset($input_data['myorderid'])?$input_data['myorderid']:'';
		$this->d_tcid       = isset($input_data['tcid'])?$input_data['tcid']:'';
		$this->d_openid     = isset($input_data['openid'])?$input_data['openid']:'';
		$this->d_ystatus    = isset($input_data['ystatus'])?$input_data['ystatus']:'';
		$this->d_ymessage   = isset($input_data['ymessage'])?$input_data['ymessage']:'';
		
		
		
		$this->log_filepath    = HY_PAYREPORTLOG.date('Y-m').'/';
		$this->log_filename    = date('Y-m-d').'_'.$mname;
		$this->log_str         = "\n".'BEGIN------------------------------------------'.date('Y-m-d H:i:s')."\n".json_encode($input_data)."\n";
		
		
		//初始化数据库
		$this->HyDb = new HyDb();
		
		
		unset($input_data);
		
		
	}
	
	
	public function __destruct() {
		//调用日志写入函数，将日志数据写入对应日志文件
		if($this->log_str!='') {
			$this->write_file_log();
		}
	}
	
	
	public function __get($property_name){
		return isset($this->$property_name) ? $this->$property_name : false;
	}
	
	
	public function __set($property_name, $value){
		$this->$property_name = $value;
	}
	
	
	
	private function fun_config_init() {
		if(!is_numeric($this->cg_payid)) {
			$this->log_str .= 'cg_payid格式不规范---'.$this->cg_payid."\n";
			echo '#cg_payid格式不规范';
			return false;
		}else {
			$sql_getcg = "select * from pay_config where id='".$this->cg_payid."' order by id desc limit 1";
			$list_getcg = $this->HyDb->get_row($sql_getcg);
			if(count($list_getcg)<=0) {
				$this->log_str .= 'cg_payid对应数据未找到---'.$this->cg_payid."\n";
				echo '#cg_payid对应数据未找到';
				return false;
			}else {
				
				$this->cg_name      = $list_getcg['name'];
				$this->cg_account   = $list_getcg['account'];
				$this->cg_passwd    = $list_getcg['passwd'];
				$this->cg_back1     = $list_getcg['back1'];
				$this->cg_back2     = $list_getcg['back2'];
				$this->cg_back3     = $list_getcg['back3'];
				$this->cg_back4     = $list_getcg['back4'];
				$this->cg_back5     = $list_getcg['back5'];
				$this->cg_createurl = $list_getcg['createurl'];
				$this->cg_tuifeiurl = $list_getcg['tuifeiurl'];
				$this->cg_selecturl = $list_getcg['selecturl'];
				$this->cg_backurl   = $list_getcg['backurl'];
				
				
				return true;
				
				
			}
		}
		
		
		
		
	}
	
	
	
	public function func_order_update() {
		if(''==$this->d_stat) {
			$this->log_str .= '状态值错误，不做更新操作---'.$this->cg_payid."\n";
			echo '#状态值错误，不做更新操作';
			return false;
		}
		
		$payflag = 0;
		if('DELIVRD'==$this->d_stat) {
			$payflag = 30;
		}else {
			$payflag = 31;
		}
		
		$sql_update  = "update pay_order set payflag='".$payflag."',
						ystatus='".$this->d_ystatus."',
						ymessage='".$this->d_ymessage."',
						openid='".$this->d_openid."',
						over_datetime='".date('Y-m-d H:i:s')."'
						where myorderid='".$this->d_myorderid."' and payflag='20'";
				
		$this->log_str .= HyItems::hy_trn2space($sql_update)."\n";
		
		$r = $this->HyDb->execute($sql_update);
		
		return $r;
		
	}
	
	
	
	
	private function func_data_check() {
		//校验订单号是否已经存在，已经存在的单号不可以再次使用
		$sql_pan = "select id,payflag,remark from pay_order where myorderid='".$this->d_myorderid."' order by id desc limit 1";
		$list_pan = $this->HyDb->get_row($sql_pan);
		
		if($list_pan<=0) {
			$this->log_str .= '该订单号不存在，无法执行更新操作---'.$this->d_myorderid."\n";
			//echo '#该订单号不存在，无法执行更新操作';
			return false;
		}else {
			$this->remark = $list_pan['remark'];  //当做透传参数使用
			$nowflag = $list_pan['payflag'];
			if(20!=$nowflag) {
				$this->log_str .= '该数据已经更新---'.$nowflag."\n";
				//echo '#该数据已经更新';
				return false;
			}else {
				return true;
			}
			
		}
		
		
		return true;
		
		
	}
	
	
	
	
	/**
	 * 日志变量数据追加，即将子类的日志变量数据追加到父类的日志变量数据中
	 */
	public function hy_log_str_add($addlog) {
		$this->log_str .= $addlog;
	}
	
	
	/**
	 * 日志写入封装函数
	 */
	public function write_file_log() {
	
		$path = $this->log_filepath;
		$name = $this->log_filename;
		$data = $this->log_str;
	
		//将数据写入日志文件
		HyItems::hy_writelog($path, $name, $data);
	
	}
	
	
	
	public function hy_init() {
		$r = $this->fun_config_init();
		if(false===$r) {
			return false;
		}
		
		$r = $this->func_data_check();
		if(false===$r) {
			return false;
		}
		
	}
	
	
	
}
