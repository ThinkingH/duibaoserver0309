<?php
/*
 * 临时用户登录
 */
class HyXb1007 extends HyXb{
	
	private $imgwidth;
	private $imgheight;
	private $page;
	private $pagesize;

	//数据的初始化
	public function __construct($input_data){
		//数据初始化
		parent::__construct($input_data);
	}


	//短信下发验证码--操作
	protected function controller_exec1(){
		
		//时间戳+随机数生成的临时id
		$tempuserid = parent::func_create_randid();
		
		//用户名+time()+随机数生成的userkey
		$tempuserkey = parent::func_create_randkey();
		
		$temparr= array(
				array(
					'userid'  => $tempuserid,
					'userkey' => $tempuserkey,
				),
		);
		
		//临时用户数据插入到用户临时表中xb_temp_user
		$tempuser_sql = "insert into xb_temp_user(id,tokenkey,create_datetime) values ('".$tempuserid."','".$tempuserkey."','".parent::__get('create_datetime')."')";
		parent::hy_log_str_add(HyItems::hy_trn2space($tempuser_sql)."\n");
		$tempuser_list = parent::__get('HyDb')->execute($tempuser_sql);
		
		$echojsonstr = HyItems::echo2clientjson('100','临时用户登录成功',$temparr);
		if(ECHOSTRLOGFLAG) {parent::hy_log_str_add($echojsonstr."\n");}
		echo $echojsonstr;
		return true;
	}



	//操作入口
	public function controller_init(){
		
		
		$this->controller_exec1();

		return true;
	}




}
