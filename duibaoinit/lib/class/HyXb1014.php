<?php
/*
 * 用户收货地址的修改
 */

class HyXb1014 extends HyXb{
	
	private $phone;
	private $shouhuoren; //收货人
	private $province;
	private $city;
	private $address;
	private $zipcode;   //邮编
	private $is_default; //默认地址
	private $address_id;
	
	//数据的初始化
	public function __construct($input_data){
	
		//数据初始化
		parent::__construct($input_data);
		//获取地址修改的参数
		$this->phone     = isset($input_data['phone'])? $input_data['phone']:'';
		$this->shouhuoren = isset($input_data['shouhuoren'])?$input_data['shouhuoren']:'';
		$this->province   = isset($input_data['province'])?$input_data['province']:'';
		$this->city       = isset($input_data['province'])?$input_data['province']:'';
		$this->address    = isset($input_data['address'])?$input_data['address']:'';
		$this->zipcode    = isset($input_data['zipcode'])?$input_data['zipcode']:'';//邮编
		$this->is_default = isset($input_data['is_default'])? $input_data['is_default']:'';  
		$this->address_id = isset($input_data['address_id'])?$input_data['address_id']:'';
	
	}
	
	
	
	protected function controller_exec1(){
		
		if($this->phone!=''|| $this->shouhuoren!=''|| $this->province!='' || $this->city!='' || $this->address!=''||$this->zipcode!=''||$this->is_default!='') {
		
			$sql_update = "update xb_user_address set ";
		
			if($this->phone!='') {
				$sql_update .= " mobile='".$this->phone."', ";
			}
			if($this->shouhuoren!='') {
				$sql_update .= " shouhuoren='".$this->shouhuoren."', ";
			}
			if($this->province!='') {
				$sql_update .= " province='".$this->province."', ";
			}
			if($this->city!='') {
				$sql_update .= " city='".$this->city."', ";
			}
				
			if($this->address!='') {
				$sql_update .= " address='".$this->address."', ";
			}
				
			if($this->zipcode!='') {
				$sql_update .= " zipcode='".$this->zipcode."', ";
			}
				
			if($this->is_default!='') {
				$sql_update .= " is_default='".$this->is_default."', ";
			}
				
			$sql_update = rtrim($sql_update,', ');
		
			$sql_update .= "where id='".$this->address_id."'";
		
			$r = parent::__get('HyDb')->execute($sql_update);
			parent::hy_log_str_add(HyItems::hy_trn2space($sql_update)."\n");
			
			if($r===true){
				$echojsonstr = HyItems::echo2clientjson('100','收货地址修改成功');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return true;
			}else{
				$echojsonstr = HyItems::echo2clientjson('319','收货地址修改失败');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return false;
			}
		
		}else{
			$echojsonstr = HyItems::echo2clientjson('318','传递参数为空，修改未发生变化');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
	}
	
	
	
	//操作入口
	public function controller_init(){
		
		//初始化参数判断
		$r = parent::func_usercheck();
		if($r===false){
			return false;
		}
		
		//判断是否为正常用户
		if($this->address_id==''){
			$echojsonstr = HyItems::echo2clientjson('317','修改地址编号为空');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
		
		//判断是否为正常用户
		if(parent::__get('usertype')!='1'){
			$echojsonstr = HyItems::echo2clientjson('314','用户类型错误');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}
	
		$r = $this->controller_exec1();
		
		return $r;
	}
	
}