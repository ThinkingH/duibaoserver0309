<?php
/*
 * 用户收货地址的添加
 */

class HyXb1013 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;
	private $phone;
	private $shouhuoren; //收货人
	private $province;
	private $city;
	private $address;
	private $zipcode;   //邮编
	private $is_default; //默认地址
	
	
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
		if($this->is_default==''){
			$this->is_default='1';
		}
	
	}
	
	
	
	protected function controller_exec1(){
		
		//判断该用户的添加的收货地址的数量
		$addressnum_sql  = "select  id from xb_user_address where userid = '".parent::__get('userid')."'";
		$addressnum_list = parent::__get('HyDb')->get_all($addressnum_sql);
		if(count($addressnum_list)>0){//该用户地址已添加
			$echojsonstr = HyItems::echo2clientjson('100','地址已添加，不可以重复添加');
			if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
			echo $echojsonstr;
			return false;
		}else{//地址的添加
			$address_sql = "insert into xb_user_address (userid,mobile,shouhuoren,province,city,address,zipcode,is_default)
						values ('".parent::__get('userid')."','".$this->phone."','".$this->shouhuoren."','".$this->province."'
						,'".$this->city."','".$this->address."','".$this->zipcode."','".$this->is_default."')";
			parent::hy_log_str_add(HyItems::hy_trn2space($address_sql)."\n");
			$address_list = parent::__get('HyDb')->execute($address_sql);
			
			if($address_list){
				$echojsonstr = HyItems::echo2clientjson('100','收货地址添加成功');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return true;
			}else{
				$echojsonstr = HyItems::echo2clientjson('315','收货地址添加失败');
				if(ECHOSTRLOGFLAG){parent::hy_log_str_add($echojsonstr."\n");}
				echo $echojsonstr;
				return false;
			}
			
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