<?php


class CodelistAction extends Action {
	
	
	//定义各模块锁定级别
	private $lock_index              = '9751';
	private $lock_deletecodelistdata = '97';
	private $lock_updatecodelistshow = '975';
	private $lock_updatecodelistdata = '975';
	private $lock_addcodelistshow    = '975';
	private $lock_addcodelistdata    = '975';
	
	
	
	
	public function index() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		$Model = new Model();
		
		
		//接收用户选择的查询参数
		$flag_s      = $this->_get('flag_s');
		$gateway_s   = $this->_get('gateway_s');
		$mbps_s      = $this->_get('mbps_s'); //流量兆数
		$ttype_s     = $this->_get('ttype_s'); //流量使用范围
		$name_s      = trim($this->_get('name_s'));
		$productid_s = trim($this->_get('productid_s'));
		$remark_s    = trim($this->_get('remark_s'));
		
		$this-> assign('name_s',$name_s);
		$this-> assign('productid_s',$productid_s);
		$this-> assign('remark_s',$remark_s);
		
		
		
		
		
		//是否启用
		$flag_arr = array(
				'1' => '启用',
				'9' => '关闭',
		);
		$optionflag = '<option value=""></option>';
		foreach($flag_arr as $keyc => $valc) {
			$optionflag .= '<option value="'.$keyc.'" ';
			if($flag_s==$keyc) { $optionflag .= ' selected="selected" '; }
			$optionflag .= '>'.$valc.'</option>';
		}
		$this->assign('optionflag',$optionflag);
		
		//运营商
		$gateway_arr = array(
				'1' => '移动-1',
				'2' => '联通-2',
				'3' => '电信-3',
		);
		$optiongateway = '<option value=""></option>';
		foreach($gateway_arr as $keyc => $valc) {
			$optiongateway .= '<option value="'.$keyc.'" ';
			if($gateway_s==$keyc) { $optiongateway .= ' selected="selected" '; }
			$optiongateway .= '>'.$valc.'</option>';
		}
		$this->assign('optiongateway',$optiongateway);
		
		//流量兆数
		//exit('error,流量兆数只能为5,10,20,30,50,70,100,150,200,500,1024,2048');
		$mbps_arr = array(
				'5' => '5',
				'10' => '10',
				'20' => '20',
				'30' => '30',
				'50' => '50',
				'70' => '70',
				'100' => '100',
				'150' => '150',
				'200' => '200',
				'300' => '300',
				'500' => '500',
				'1024' => '1024',
				'2048' => '2048',
		);
		$optionmbps = '<option value=""></option>';
		foreach($mbps_arr as $keyc => $valc) {
			$optionmbps .= '<option value="'.$keyc.'" ';
			if($mbps_s==$keyc) { $optionmbps .= ' selected="selected" '; }
			$optionmbps .= '>'.$valc.'</option>';
		}
		$this->assign('optionmbps',$optionmbps);
		
		
		//使用范围
		$ttype_arr = array(
				'1' => '全国-1',
				'2' => '省内-2',
		);
		$optionttype = '<option value=""></option>';
		foreach($ttype_arr as $keyc => $valc) {
			$optionttype .= '<option value="'.$keyc.'" ';
			if($ttype_s==$keyc) { $optionttype .= ' selected="selected" '; }
			$optionttype .= '>'.$valc.'</option>';
		}
		$this->assign('optionttype',$optionttype);
		
		
		
		
		
		
		
		//-----------------------------------------------------------
		//生成where条件判断字符串
		$sql_where = '';
		
		if($flag_s!='') {
			$sql_where .= " flag='".$flag_s."' and ";
		}
		if($gateway_s!='') {
			$sql_where .= " gateway='".$gateway_s."' and ";
		}
		if($mbps_s!='') {
			$sql_where .= " mbps='".$mbps_s."' and ";
		}
		if($ttype_s!='') {
			$sql_where .= " ttype='".$ttype_s."' and ";
		}
		if($productid_s!='') {
			$sql_where .= " productid like '".$productid_s."%' and ";
		}
		if($name_s!='') {
			$sql_where .= " name like '%".$name_s."%' and ";
		}
		if($remark_s!='') {
			$sql_where .= " remark like '%".$remark_s."%' and ";
		}
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
		
		
		//生成排序字符串数据
		$sql_order = " id asc ";
		

		import('ORG.Page');// 导入分页类
		$count = $Model -> table('dh_codelist')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('dh_codelist')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		//---------------------------------------------------------------
		
		
		
		foreach($list as $keyc => $valc) {
				
			if($list[$keyc]['flag']=='1') {
				$list[$keyc]['flag'] = '<font style="background-color:#00EA00">&nbsp;&nbsp;启&nbsp;&nbsp;用&nbsp;&nbsp;</font>';
			}else if($list[$keyc]['flag']=='9') {
				$list[$keyc]['flag'] = '<font style="background-color:#FF1700">&nbsp;&nbsp;关&nbsp;&nbsp;闭&nbsp;&nbsp;</font>';
			}else {
				$list[$keyc]['flag'] = 'ERR';
			}
			
			if($list[$keyc]['gateway']=='1') {
				$list[$keyc]['gateway'] = '移动-1';
			}else if($list[$keyc]['gateway']=='2') {
				$list[$keyc]['gateway'] = '联通-2';
			}else if($list[$keyc]['gateway']=='3') {
				$list[$keyc]['gateway'] = '电信-3';
			}else {
				
			}
			
			if($list[$keyc]['ttype']=='1') {
				$list[$keyc]['ttype'] = '1-全国';
			}else if($list[$keyc]['ttype']=='2') {
				$list[$keyc]['ttype'] = '2-省内';
			}else {
				
			}
			
			
			$list[$keyc]['yuan_price'] = $list[$keyc]['yuan_price']/100;
			$list[$keyc]['now_price']  = $list[$keyc]['now_price']/100;
			
			
			
			
		}
		
		
		$this -> assign('list',$list);
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
		
	}
	
	
	
	
	//用户单击删除按钮后的判断模块
	public function deletecodelistdata() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_deletecodelistdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		$delete_submit = $this->_post('delete_submit');
		$codelist_id   = $this->_post('codelist_id');
		
		if($delete_submit=='') {
			echo "<script>alert('非法操作！');history.go(-1);</script>";
			$this -> error('非法操作！');
			
		}else {
			
			if(!is_numeric($codelist_id)) {
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this -> error('非法操作！');
			}else {
				
				//数据库初始化
				$Model = new Model();
				
				
				//判断该数据是否存在
				$sql_pan = "select id from dh_codelist where id='".$codelist_id."' limit 1";
				$list_pan = $Model->query($sql_pan);
				
				if(count($list_pan)<=0) {
					echo "<script>alert('数据不存在，无法删除');history.go(-1);</script>";
					$this -> error('数据不存在，无法删除');
					
					
				}else {
					
					//说明此数据没有关联数据，可以删除
					$ret = $Model -> table('dh_codelist') -> where("id='".$codelist_id."'") -> delete();
					
					$templogs = $Model->getlastsql();
					hy_caozuo_logwrite($templogs,__CLASS__.'---'.__FUNCTION__);
					
					if($ret) {
						echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Codelist/index".$yuurl."';</script>";
						$this -> success('数据删除成功!','__APP__/Codelist/index'.$yuurl);
					}else {
						echo "<script>alert('数据删除失败，系统错误!');history.go(-1);</script>";
						$this -> error('数据删除失败，系统错误!');
					}
					
					
				}
				
				
			}
			
			
		}
		
		
	}
	
	
	
	
	//修改表单输出页面
	public function updatecodelistshow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updatecodelistshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		$update_submit = $this->_post('update_submit');
		$codelist_id   = $this->_post('codelist_id');
		
		if($update_submit=='') {
			echo "<script>alert('非法操作！');history.go(-1);</script>";
			$this -> error('非法操作！');
			
		}else {
			
			//数据库初始化
			$Model = new Model();
			
			//判断数据是否存在
			$sql_codelist = "select * from dh_codelist where id='".$codelist_id."'";
			$list = $Model->query($sql_codelist);
			
			if(count($list)<=0) {
				echo "<script>alert('非法操作');history.go(-1);</script>";
				$this -> error('非法操作');
				
			}else {
				//取出一维数组
				$list = $list[0];
				
				
				//是否启用
				$flag_arr = array(
						'1' => '启用',
						'9' => '关闭',
				);
				$optionflag = '<option value=""></option>';
				foreach($flag_arr as $keyc => $valc) {
					$optionflag .= '<option value="'.$keyc.'" ';
					if($list['flag']==$keyc) { $optionflag .= ' selected="selected" '; }
					$optionflag .= '>'.$valc.'</option>';
				}
				$this->assign('optionflag',$optionflag);
				
				//运营商
				$gateway_arr = array(
						'1' => '移动-1',
						'2' => '联通-2',
						'3' => '电信-3',
				);
				$optiongateway = '<option value=""></option>';
				foreach($gateway_arr as $keyc => $valc) {
					$optiongateway .= '<option value="'.$keyc.'" ';
					if($list['gateway']==$keyc) { $optiongateway .= ' selected="selected" '; }
					$optiongateway .= '>'.$valc.'</option>';
				}
				$this->assign('optiongateway',$optiongateway);
				
				//流量兆数
				//exit('error,流量兆数只能为5,10,20,30,50,70,100,150,200,500,1024,2048');
				$mbps_arr = array(
						'5' => '5',
						'10' => '10',
						'20' => '20',
						'30' => '30',
						'50' => '50',
						'70' => '70',
						'100' => '100',
						'150' => '150',
						'200' => '200',
						'300' => '300',
						'500' => '500',
						'1024' => '1024',
						'2048' => '2048',
				);
				$optionmbps = '<option value=""></option>';
				foreach($mbps_arr as $keyc => $valc) {
					$optionmbps .= '<option value="'.$keyc.'" ';
					if($list['mbps']==$keyc) { $optionmbps .= ' selected="selected" '; }
					$optionmbps .= '>'.$valc.'</option>';
				}
				$this->assign('optionmbps',$optionmbps);
				
				
				//使用范围
				$ttype_arr = array(
						'1' => '全国-1',
						'2' => '省内-2',
				);
				$optionttype = '<option value=""></option>';
				foreach($ttype_arr as $keyc => $valc) {
					$optionttype .= '<option value="'.$keyc.'" ';
					if($list['ttype']==$keyc) { $optionttype .= ' selected="selected" '; }
					$optionttype .= '>'.$valc.'</option>';
				}
				$this->assign('optionttype',$optionttype);
				
				
				//-----------------------------------------------------------------------
				$province_arr = array();
				$province_temparr = hy_province_config();
				foreach($province_temparr as $valp) {
					$province_arr[$valp] = $valp;
				}
				$optionprovince = '<option value=""></option>';
				foreach($province_arr as $keys => $vals) {
					$optionprovince .= '<option value="'.$keys.'"';
					if($keys==$list['province']) {
						$optionprovince .= ' selected="selected" ';
					}
					$optionprovince .= ' >'.$vals.'</option>';
				}
				$this->assign('optionprovince',$optionprovince);
				
				
				
				$this->assign('list',$list);
				
				
			}
			
			
		}
		
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	
	
	//用户提交修改数据后的操作模块
	public function updatecodelistdata() {
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updatecodelistdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$update_submit = $this->_post('update_submit');
		$codelist_id   = $this->_post('codelist_id');
		$flag          = $this->_post('flag');
		$name          = trim($this->_post('name'));
		$gateway       = $this->_post('gateway');
		$mbps          = $this->_post('mbps');
		$ttype         = $this->_post('ttype');
		$province      = $this->_post('province');
		$productid     = trim($this->_post('productid'));
		$yuan_price    = trim($this->_post('yuan_price'));
		$now_price     = trim($this->_post('now_price'));
		$remark        = trim($this->_post('remark'));
		
		
		if($update_submit=='') {
			echo "<script>alert('非法操作！');history.go(-1);</script>";
			$this -> error('非法操作！');
				
		}else {
			
			if(!is_numeric($codelist_id)) {
				echo "<script>alert('非法操作');history.go(-1);</script>";
				$this -> error('非法操作');
			}
			if($name=='') {
				echo "<script>alert('产品名称不能为空');history.go(-1);</script>";
				$this -> error('产品名称不能为空');
			}
			if($gateway=='') {
				echo "<script>alert('运营商不能为空');history.go(-1);</script>";
				$this -> error('运营商不能为空');
			}
			if($mbps=='') {
				echo "<script>alert('流量对应兆数不能为空');history.go(-1);</script>";
				$this -> error('流量对应兆数不能为空');
			}
			if($ttype=='') {
				echo "<script>alert('可用范围不能为空');history.go(-1);</script>";
				$this -> error('可用范围不能为空');
			}
			if($productid=='') {
				echo "<script>alert('产品编号不能为空');history.go(-1);</script>";
				$this -> error('产品编号不能为空');
			}
			if(!is_numeric($yuan_price)) {
				echo "<script>alert('官方原始价格不能为空且只能为数字');history.go(-1);</script>";
				$this -> error('官方原始价格不能为空且只能为数字');
			}
			if(!is_numeric($now_price)) {
				echo "<script>alert('拿到成本价格不能为空且只能为数字');history.go(-1);</script>";
				$this -> error('拿到成本价格不能为空且只能为数字');
			}
			if('2'==$ttype && ''==$province) {
				echo "<script>alert('省内流量必须指明充值省份');history.go(-1);</script>";
				$this -> error('省内流量必须指明充值省份');
			}
			
			
			
			//数据库初始化
			$Model= new Model();
			
			
			//不允许上家产品编号重复添加
			$sql_panduan = "select id from dh_codelist where productid='".$productid."' and id<>'".$codelist_id."'";
			$list_panduan = $Model->query($sql_panduan);
			
			if(count($list_panduan)>0) {
				echo "<script>alert('上家产品标识编号不能重复添加');history.go(-1);</script>";
				$this -> error('上家产品标识编号不能重复添加');
			}else {
				
				
				//添加数据
				$data = array();
				$data['name']       = $name;
				$data['flag']       = $flag;
				$data['gateway']    = $gateway;
				$data['mbps']       = $mbps;
				$data['ttype']      = $ttype;
				$data['province']   = $province;
				$data['productid']  = $productid;
				$data['yuan_price'] = $yuan_price;
				$data['now_price']  = $now_price;
				$data['remark']     = $remark;
				
				
				$ret = $Model ->table('dh_codelist')->where("id='".$codelist_id."'")->save($data);
				
				$templogs = $Model->getlastsql();
				hy_caozuo_logwrite($templogs,__CLASS__.'---'.__FUNCTION__);
				
				
				if($ret) {
					echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Codelist/index".$yuurl."';</script>";
					$this ->success('数据修改成功!','__APP__/Codelist/index'.$yuurl);
				}else {
					echo "<script>alert('数据修改失败，您未做任何改动!');history.go(-1);</script>";
					$this -> error('数据修改失败，您未做任何改动!');
				}
				
				
			}
			
			
		}
		
		
	}
	
	
	
	
	//数据添加表单输出页面
	public function addcodelistshow() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addcodelistshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		
		//是否启用
		$flag_arr = array(
				'1' => '启用',
				'9' => '关闭',
		);
		$optionflag = '<option value=""></option>';
		foreach($flag_arr as $keyc => $valc) {
			$optionflag .= '<option value="'.$keyc.'" ';
			//if($flag_s==$keyc) { $optionflag .= ' selected="selected" '; }
			$optionflag .= '>'.$valc.'</option>';
		}
		$this->assign('optionflag',$optionflag);
		
		//运营商
		$gateway_arr = array(
				'1' => '移动-1',
				'2' => '联通-2',
				'3' => '电信-3',
		);
		$optiongateway = '<option value=""></option>';
		foreach($gateway_arr as $keyc => $valc) {
			$optiongateway .= '<option value="'.$keyc.'" ';
			//if($gateway_s==$keyc) { $optiongateway .= ' selected="selected" '; }
			$optiongateway .= '>'.$valc.'</option>';
		}
		$this->assign('optiongateway',$optiongateway);
		
		//流量兆数
		//exit('error,流量兆数只能为5,10,20,30,50,70,100,150,200,500,1024,2048');
		$mbps_arr = array(
				'5' => '5',
				'10' => '10',
				'20' => '20',
				'30' => '30',
				'50' => '50',
				'70' => '70',
				'100' => '100',
				'150' => '150',
				'200' => '200',
				'300' => '300',
				'500' => '500',
				'1024' => '1024',
				'2048' => '2048',
		);
		$optionmbps = '<option value=""></option>';
		foreach($mbps_arr as $keyc => $valc) {
			$optionmbps .= '<option value="'.$keyc.'" ';
			//if($mbps_s==$keyc) { $optionmbps .= ' selected="selected" '; }
			$optionmbps .= '>'.$valc.'</option>';
		}
		$this->assign('optionmbps',$optionmbps);
		
		
		//使用范围
		$ttype_arr = array(
				'1' => '全国-1',
				'2' => '省内-2',
		);
		$optionttype = '<option value=""></option>';
		foreach($ttype_arr as $keyc => $valc) {
			$optionttype .= '<option value="'.$keyc.'" ';
			//if($ttype_s==$keyc) { $optionttype .= ' selected="selected" '; }
			$optionttype .= '>'.$valc.'</option>';
		}
		$this->assign('optionttype',$optionttype);
		
		
		//-----------------------------------------------------------------------
		$province_arr = array();
		$province_temparr = hy_province_config();
		foreach($province_temparr as $valp) {
			$province_arr[$valp] = $valp;
		}
		$optionprovince = '<option value=""></option>';
		foreach($province_arr as $keys => $vals) {
			$optionprovince .= '<option value="'.$keys.'"';
			//if($keys==$list['province']) { $optionprovince .= ' selected="selected" '; }
			$optionprovince .= ' >'.$vals.'</option>';
		}
		$this->assign('optionprovince',$optionprovince);
		
		
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	
	
	//数据添加执行模块
	public function addcodelistdata() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addcodelistdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		$insert_submit = $this->_post('insert_submit');
		$flag          = $this->_post('flag');
		$name          = trim($this->_post('name'));
		$gateway       = $this->_post('gateway');
		$mbps          = $this->_post('mbps');
		$ttype         = $this->_post('ttype');
		$province      = $this->_post('province');
		$productid     = trim($this->_post('productid'));
		$yuan_price    = trim($this->_post('yuan_price'));
		$now_price     = trim($this->_post('now_price'));
		$remark        = trim($this->_post('remark'));
		
		
		if($insert_submit=='') {
			echo "<script>alert('非法操作！');history.go(-1);</script>";
			$this -> error('非法操作！');
			
		}else {
			
			if($name=='') {
				echo "<script>alert('产品名称不能为空');history.go(-1);</script>";
				$this -> error('产品名称不能为空');
			}
			if($gateway=='') {
				echo "<script>alert('运营商不能为空');history.go(-1);</script>";
				$this -> error('运营商不能为空');
			}
			if($mbps=='') {
				echo "<script>alert('流量对应兆数不能为空');history.go(-1);</script>";
				$this -> error('流量对应兆数不能为空');
			}
			if($ttype=='') {
				echo "<script>alert('可用范围不能为空');history.go(-1);</script>";
				$this -> error('可用范围不能为空');
			}
			if($productid=='') {
				echo "<script>alert('产品编号不能为空');history.go(-1);</script>";
				$this -> error('产品编号不能为空');
			}
			if(!is_numeric($yuan_price)) {
				echo "<script>alert('官方原始价格不能为空且只能为数字');history.go(-1);</script>";
				$this -> error('官方原始价格不能为空且只能为数字');
			}
			if(!is_numeric($now_price)) {
				echo "<script>alert('拿到成本价格不能为空且只能为数字');history.go(-1);</script>";
				$this -> error('拿到成本价格不能为空且只能为数字');
			}
			if('2'==$ttype && ''==$province) {
				echo "<script>alert('省内流量必须指明充值省份');history.go(-1);</script>";
				$this -> error('省内流量必须指明充值省份');
			}
			
			
			
			//数据库初始化
			$Model= new Model();
			
			//不允许上家产品编号重复添加
			$sql_panduan = "select id from dh_codelist where productid='".$productid."'";
			$list_panduan = $Model->query($sql_panduan);
				
			if(count($list_panduan)>0) {
				echo "<script>alert('上家产品标识编号不能重复添加');history.go(-1);</script>";
				$this -> error('上家产品标识编号不能重复添加');
			}else {
				
				
				//添加数据
				$data = array();
				$data['name']       = $name;
				$data['flag']       = $flag;
				$data['gateway']    = $gateway;
				$data['mbps']       = $mbps;
				$data['ttype']      = $ttype;
				$data['province']   = $province;
				$data['productid']  = $productid;
				$data['yuan_price'] = $yuan_price;
				$data['now_price']  = $now_price;
				$data['remark']     = $remark;
				
				
				$ret = $Model->table('dh_codelist')->add($data);
				
				
				$templogs = $Model->getlastsql();
				hy_caozuo_logwrite($templogs,__CLASS__.'---'.__FUNCTION__);
				
				
				if($ret) {
					echo "<script>alert('数据添加成功！');window.location.href='".__APP__."/Codelist/index".$yuurl."';</script>";
					$this ->success('数据添加成功!','__APP__/Codelist/index'.$yuurl);
					
				}else {
					echo "<script>alert('数据添加失败，系统错误!');history.go(-1);</script>";
					$this -> error('数据添加失败，系统错误!');
				}
				
				
				
			}
			
			
		}
		
		
		
	}
	
	
	
	
	//生成url拼接参数
	private function createurl($get) {
	
		$yuurl = '?';
		foreach($get as $keyg => $valg) {
			if(substr($keyg,0,6)!='submit' && $keyg!='_URL_') {
				if(is_array($valg)) {
					foreach($valg as $valcc) {
						$yuurl .= $keyg.'[]='.urlencode($valcc).'&';
					}
	
				}else {
					$yuurl .= $keyg.'='.urlencode($valg).'&';
				}
			}
		}
		$yuurl = rtrim($yuurl,'&');
	
		if(strlen($yuurl)>1) {
			return $yuurl;
		}else {
			return '';
		}
	
	
	}
	
	
	
	
	//判断用户是否登陆的前台展现封装模块
	private function loginjudgeshow($lock_key) {
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$lockarr = loginjudge($lock_key);
		if($lockarr['grade']=='C') {
			//通过
		}else if($lockarr['grade']=='B') {
			exit($lockarr['exitmsg']);
		}else if($lockarr['grade']=='A') {
			echo $lockarr['alertmsg'];
			$this -> error($lockarr['errormsg'],'__APP__/Login/index');
		}else {
			exit('系统错误，为确保系统安全，禁止登入系统');
		}
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
	}
	
	
	
}
