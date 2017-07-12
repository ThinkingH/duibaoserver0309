<?php
/*
 * 商户开户管理
 */
class ShanghuAction extends Action{
	
	
	private $lock_index                = '9751';
	private $lock_updateshow           = '975';
	private $lock_updatedata           = '975';
	private $lock_chakandata           = '975';
	private $lock_chakanshow           = '975';
	private $lock_deletedata           = '97';
	
	private $lock_store              = '975';
	private $lock_storeshow          = '975';
	private $lock_storeupdatedata    = '97';
	
	
	//广告的展示
	public function index(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
		//接收用户选择的查询参数
		$date_s      = $this->_get('date_s');
		$date_e      = $this->_get('date_e');
		$flag_s      = $this->_get('flag_s');
		$status      = $this->_get('status');
	
		//是否启用
		$flag_arr = array(
				'1' => '未注销',
				'9' => '已注销',
		);
		$optionflag = '<option value=""></option>';
		foreach($flag_arr as $keyc => $valc) {
			$optionflag .= '<option value="'.$keyc.'" ';
			if($flag_s==$keyc) { $optionflag .= ' selected="selected" '; }
			$optionflag .= '>'.$valc.'</option>';
		}
	
		//审核状态
		$status_arr = array(
				'3' => '审核失败',
				'2' => '审核成功',
				'1' => '等待审核',
		);
		$optionstatus = '<option value=""></option>';
		foreach($status_arr as $keyc => $valc) {
			$optionstatus .= '<option value="'.$keyc.'" ';
			if($status==$keyc) { $optionstatus .= ' selected="selected" '; }
			$optionstatus .= '>'.$valc.'</option>';
		}
	
	
		$this->assign('optionflag',$optionflag);
		$this->assign('optionstatus',$optionstatus);
		$this->assign('date_s',$date_s);
		$this->assign('date_e',$date_e);
	
		//数据库的初始化
		$Model = new Model();
	
		//生成where条件判断字符串
		$sql_where = " ";
	
		if($flag_s!='') {
			$sql_where .= " flag='".$flag_s."' and ";
		}
	
		if($date_s!='') {
			$sql_where .= "create_datetime>='".$date_s." 00:00:00 ' and ";
		}
	
		if($date_e!='') {
			$sql_where .= "create_datetime<='".$date_e." 23:59:59' and ";
		}
	
		if($status!='') {
			$sql_where .= "checkstatus>='".$status."' and ";
		}
	
		$sql_where = rtrim($sql_where,'and ');
	
		//生成排序字符串数据
		$sql_order = " id desc ";
	
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('shop_site')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
	
		//执行SQL查询语句
		$list  = $Model -> table('shop_site')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
	
		//数据的读出
		foreach ($list as $keys=>$vals){
				
			if($list[$keys]['flag']=='1'){
	
				$list[$keys]['flags']='<font style="background-color:#00EA00">&nbsp;&nbsp;未注销&nbsp;&nbsp;</font>';
	
			}else if($list[$keys]['flag']=='9'){
				$list[$keys]['flags']='<font style="background-color:#FF1700">&nbsp;&nbsp;已注销&nbsp;&nbsp;</font>';
			}
				
			if($list[$keys]['checkstatus']=='1'){
				$list[$keys]['checkstatus']='<font style="background-color:#F8F691">&nbsp;&nbsp;等待审核&nbsp;&nbsp;</font>';
			}else if($list[$keys]['checkstatus']=='2'){
				$list[$keys]['checkstatus']='<font style="background-color:#00EA00">&nbsp;&nbsp;审核成功&nbsp;&nbsp;</font>';
			}else if($list[$keys]['checkstatus']=='3'){
				$list[$keys]['checkstatus']='<font style="background-color:#FF1700">&nbsp;&nbsp;审核失败&nbsp;&nbsp;</font>';
			}
				
		}
			
		$this -> assign('list',$list);
	
		// 输出模板
		$this->display();
	
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	
	
	}
	
	
	//数据的修改
	public function updateshow(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updateshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
	
		$id = $this->_post('id');//发布商品的id
		$update_submit = $this->_post('update_submit');
	
		if($update_submit!=''){
				
			if($id==''){
				echo "<script>alert('非法操作！');history(-1);</script>";
				$this ->error('非法操作！');
			}else{
				//数据库的初始化
				$Model = new Model();
	
				$sqldata = "select * from shop_site where id='".$id."'";
				$listdata = $Model->query($sqldata);
				
	
				//是否启用
				$flag_arr = array(
						'1' => '未注销',
						'9' => '已注销',
	
				);
				foreach($flag_arr as $keyc => $valc) {
					$optionflag .= '<option value="'.$keyc.'" ';
					if($listdata[0]['flag']==$keyc) { $optionflag .= ' selected="selected" '; }
					$optionflag .= '>'.$valc.'</option>';
				}
				$this->assign('optionflag',$optionflag);
	
	
					
				$tiaozhuantype = array(
						'1' => '等待审核',
						'2' => '审核成功',
						'3' => '审核失败',
				);
					
				foreach($tiaozhuantype as $keyc => $valc) {
					$optioncheck .= '<option value="'.$keyc.'" ';
					if($listdata[0]['checkstatus']==$keyc) { $optioncheck .= ' selected="selected" '; }
					$optioncheck .= '>'.$valc.'</option>';
				}
				$this->assign('optioncheck',$optioncheck);
	
				if($listdata[0]['checkstatus']=='1'){
					$listdata[0]['checkstatus']='<font style="background-color:#F8F691">&nbsp;&nbsp;等待审核&nbsp;&nbsp;</font>';
				}else if($listdata[0]['checkstatus']=='2'){
					$listdata[0]['checkstatus']='<font style="background-color:#00EA00">&nbsp;&nbsp;审核成功&nbsp;&nbsp;</font>';
				}else if($listdata[0]['checkstatus']=='3'){
					$listdata[0]['checkstatus']='<font style="background-color:#FF1700">&nbsp;&nbsp;审核失败&nbsp;&nbsp;</font>';
				}
				
			}
				
			$this->assign('list',$listdata[0]);
		}
	
		$this->display();
	
	}
	
	
	
	//修改数据的添加
	public function updatedata(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updatedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
		//获取相应的参数
		$id    = $this->_post('id');
		$flag  = $this->_post('flag');
		$lianxiren = $this->_post('lianxiren');
		$phone  = $this->_post('phone');
		$email = $this->_post('email');
		$company = $this->_post('company');
		$address     = $this->_post('address');
		$storename    = $this->_post('storename');
		$shangjiatype    = $this->_post('shangjiatype');
		$qq    = $this->_post('qq');
		$remark    = $this->_post('remark');
		$uupdate_submit = $this->_post('uupdate_submit');
	
	
		$Model = new Model();
		$data=array();
	
	
		$data['flag'] = $flag;
		$data['lianxiren']  = $lianxiren;
		$data['phone']  = $phone;
		$data['email']  = $email;
		$data['company'] = $company;
		$data['address'] = $address;
		$data['storename'] = $storename;
		$data['shangjiatype'] = $shangjiatype;
		$data['qq'] = $qq;
		$data['remark'] = $remark;
			
			
		$imagedata_sql = $Model->table('shop_site')->where ("id='".$id."'")->save($data);
	
	
		if($imagedata_sql){
			echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Shanghu/index".$yuurl."';</script>";
			$this ->success('数据修改成功!','__APP__/Shanghu/index'.$yuurl);
		}else{
			echo "<script>alert('数据修改失败！'); history.go(-1);</script>";
			$this->error('数据修改失败！');
		}
	
	
	}
	
	
	//数据的查看
	public function chakanshow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_chakanshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		$id = $this->_post('id');//发布商品的id
		$update_submit = $this->_post('update_submit');
		
		if($update_submit!=''){
		
			if($id==''){
				echo "<script>alert('非法操作！');history(-1);</script>";
				$this ->error('非法操作！');
			}else{
				//数据库的初始化
				$Model = new Model();
		
				$sqldata = "select * from shop_site where id='".$id."'";
				$listdata = $Model->query($sqldata);
		
		
				//是否启用
				$flag_arr = array(
						'1' => '未注销',
						'9' => '已注销',
		
				);
				foreach($flag_arr as $keyc => $valc) {
					$optionflag .= '<option value="'.$keyc.'" ';
					if($listdata[0]['flag']==$keyc) { $optionflag .= ' selected="selected" '; }
					$optionflag .= '>'.$valc.'</option>';
				}
				$this->assign('optionflag',$optionflag);
		
		
					
				$tiaozhuantype = array(
						'3' => '审核失败',
						'2' => '审核成功',
						'1' => '等待审核',
				);
					
				foreach($tiaozhuantype as $keyc => $valc) {
					$optioncheck .= '<option value="'.$keyc.'" ';
					if($listdata[0]['checkstatus']==$keyc) { $optioncheck .= ' selected="selected" '; }
					$optioncheck .= '>'.$valc.'</option>';
				}
				$this->assign('optioncheck',$optioncheck);
		
				
				if($listdata[0]['checkstatus']=='1'){
					$listdata[0]['checkstatuss']='<font style="background-color:#F8F691">&nbsp;&nbsp;等待审核&nbsp;&nbsp;</font>';
				}else if($listdata[0]['checkstatus']=='2'){
					$listdata[0]['checkstatuss']='<font style="background-color:#00EA00">&nbsp;&nbsp;审核成功&nbsp;&nbsp;</font>';
				}else if($listdata[0]['checkstatus']=='3'){
					$listdata[0]['checkstatuss']='<font style="background-color:#FF1700">&nbsp;&nbsp;审核失败&nbsp;&nbsp;</font>';
				}
				
				if($listdata[0]['flag']=='1'){
					$listdata[0]['flag']='<font style="background-color:#F8F691">&nbsp;&nbsp;未注销&nbsp;&nbsp;</font>';
				}else if($listdata[0]['flag']=='9'){
					$listdata[0]['flag']='<font style="background-color:#F8F691">&nbsp;&nbsp;已注销&nbsp;&nbsp;</font>';
				}
				
				
			}
		
			$this->assign('list',$listdata[0]);
		}
		
		$this->display();
		
		
		
	}
	
	
	//审核，驳回
	public function chakandata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_chakandata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		$tijiao_submit = $this->_post('tijiao_submit');
		$id            = $this->_post('id');
		$checkstatus   = $this->_post('checkstatus');
		$remark        = $this->_post('remark');
		//$biaozhi       = $this->_post('biaozhi');
		
		$Model = new Model();
		
		if($tijiao_submit!=''){
			
			$updatesql = "update shop_site set checkstatus='".$checkstatus."',remark='".$remark."' where id='".$id."'";
			$updatelist = $Model->execute($updatesql);
			
			if($updatelist){
				
				$sqldata = "select * from shop_site where id='".$id."' ";
				$sqllist = $Model->query($sqldata);
				
				if($sqllist[0]['checkstatus']=='2'){
					
					$mailto = $sqllist[0]['email'];
					$body = '亲爱的'.$sqllist[0]['lianxiren'].'女士/先生，你好！  您入驻馅饼商城提交的资料现已通过审核，请使用账号'.$sqllist[0]['username'].' 初始密码123456进行登录查看！';
					
					hy_common_sendemail($mailto, $subject='兑宝商户入驻审核通知', $body);
					
					echo "<script>alert('审核成功！');window.location.href='".__APP__."/Shanghu/index".$yuurl."';</script>";
					$this ->success('审核成功！','__APP__/Shanghu/index'.$yuurl);
					
				}else if($sqllist[0]['checkstatus']=='3'){
					
					$mailto = $sqllist[0]['email'];
					$body = '亲爱的'.$sqllist[0]['lianxiren'].'女士/先生，你好！  您入驻馅饼商城提交的资料现未通过审核，请登录查看完善资料！';
					
					hy_common_sendemail($mailto, $subject='兑宝商户入驻审核通知', $body);
					
					echo "<script>alert('驳回成功！');window.location.href='".__APP__."/Shanghu/index".$yuurl."';</script>";
					$this ->success('驳回成功！','__APP__/Shanghu/index'.$yuurl);
					
				}
				
			}else{
				echo "<script>alert('操作失败！'); history.go(-1);</script>";
				$this ->success('操作失败！');
			}
			
		}
		
		/* if($tijiao_submit!=''){
			
			if($biaozhi=='1'){//审核通过
				
				$updatesql = "update shop_site set checkstatus=2 where id='".$id."'";
				$updatelist = $Model->execute($updatesql);
				
				
				if($updatelist){
					
					//用户邮箱的发送
					$emailsql = "select phone,email,lianxiren from shop_site where checkstatus=2 and flag=1 and id='".$id."' ";
					$emaillist = $Model->query($emailsql);
					
					$mailto = $emaillist[0]['email'];
					$body = '亲爱的'.$emaillist[0]['lianxiren'].'女士/先生，你好！  您入驻馅饼商城提交的资料现已通过审核，请登录查看！';
					
					hy_common_sendemail($mailto, $subject='兑宝商户入驻审核通知', $body);
						
					echo "<script>alert('审核成功！');window.location.href='".__APP__."/Shanghu/index".$yuurl."';</script>";
					$this ->success('审核成功！','__APP__/Shanghu/index'.$yuurl);
				}else{
					echo "<script>alert('审核失败！'); history.go(-1);</script>";
					$this ->success('审核失败！');
				}
				
				
			}else if($biaozhi=='2'){//驳回
				
				$updatesql = "update shop_site set checkstatus=3 where id='".$id."'";
				$updatelist = $Model->execute($updatesql);
				
				if($updatelist){
					
					//用户邮箱的发送
					$emailsql = "select phone,email,lianxiren from shop_site where checkstatus=3 and flag=1 and id='".$id."' ";
					$emaillist = $Model->query($emailsql);
						
					$mailto = $emaillist[0]['email'];
					$body = '亲爱的'.$emaillist[0]['lianxiren'].'女士/先生，你好！  您入驻馅饼商城提交的资料现未通过审核，请登录查看完善资料！';
						
					hy_common_sendemail($mailto, $subject='兑宝商户入驻审核通知', $body);
				
					echo "<script>alert('驳回成功！');window.location.href='".__APP__."/Shanghu/index".$yuurl."';</script>";
					$this ->success('驳回成功！','__APP__/Shanghu/index'.$yuurl);
				}else{
					echo "<script>alert('驳回失败！'); history.go(-1);</script>";
					$this ->success('驳回失败！');
				}
				
			}
			
			
			
		} */
		
		
	}
	
	
	
	
	
	
	
	
	//数据的删除
	public function deletedata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_deletedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		$id = $this->_post('id');
		$delete_submit = $this->_post('delete_submit');
		
		if($delete_submit!=''){
			
			if($id==''){
				echo "<script>alert('非法操作');history(-1);</script>";
				$this->error('非法操作');
			}
			
			//数据库初始化
			$Model = new Model();
			
			//对应安装包的删除
			$seldata_sql  = "select bussinelicence1,bussinelicence2 from shop_site where id='".$id."'";
			$seldata_list = $Model->query($seldata_sql);
			
			$filepath1=$seldata_list[0]['bussinelicence1'];
			$filepath2=$seldata_list[0]['bussinelicence2'];
			
			$filename1 = $_SERVER["DOCUMENT_ROOT"].substr($filepath1,31);
			$filename2 = $_SERVER["DOCUMENT_ROOT"].substr($filepath2,31);
		
			if(file_exists($filename1)){
				
				unlink($filename1);
			}
			
			if(file_exists($filename2)){
				
				unlink($filename2);
			}
			$data = array();
			$data['flag'] = '9';
			
			//说明此数据没有关联数据，可以删除
			$ret = $Model -> table('shop_site') -> where("id='".$id."'") -> save($data);
			
			if($ret) {
				echo "<script>alert('数据注销成功！');window.location.href='".__APP__."/Shanghu/index".$yuurl."';</script>";
				$this -> success('数据注销成功!','__APP__/Shanghu/index'.$yuurl);
			}else {
				echo "<script>alert('数据注销失败，系统错误!');history.go(-1);</script>";
				$this -> error('数据注销失败，系统错误!');
			}
			
		}
		
	}
	
	
	
	//--------------------------------------------------------------------------------------------------------------------
	//店铺信息
	public function store(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_store);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
		//接收用户选择的查询参数
		$date_s      = $this->_get('date_s');
		$date_e      = $this->_get('date_e');
		$flag_s      = $this->_get('flag_s');
		$status      = $this->_get('status');
	
		//是否启用
		$flag_arr = array(
				'1' => '未注销',
				'9' => '已注销',
		);
		$optionflag = '<option value=""></option>';
		foreach($flag_arr as $keyc => $valc) {
			$optionflag .= '<option value="'.$keyc.'" ';
			if($flag_s==$keyc) { $optionflag .= ' selected="selected" '; }
			$optionflag .= '>'.$valc.'</option>';
		}
	
		//审核状态
		$status_arr = array(
				'3' => '审核失败',
				'2' => '审核成功',
				'1' => '等待审核',
		);
		$optionstatus = '<option value=""></option>';
		foreach($status_arr as $keyc => $valc) {
			$optionstatus .= '<option value="'.$keyc.'" ';
			if($status==$keyc) { $optionstatus .= ' selected="selected" '; }
			$optionstatus .= '>'.$valc.'</option>';
		}
	
	
		$this->assign('optionflag',$optionflag);
		$this->assign('optionstatus',$optionstatus);
		$this->assign('date_s',$date_s);
		$this->assign('date_e',$date_e);
	
		//数据库的初始化
		$Model = new Model();
	
		//生成where条件判断字符串
		$sql_where = " ";
	
		if($flag_s!='') {
			$sql_where .= " flag='".$flag_s."' and ";
		}
	
		if($date_s!='') {
			$sql_where .= "create_datetime>='".$date_s." 00:00:00 ' and ";
		}
	
		if($date_e!='') {
			$sql_where .= "create_datetime<='".$date_e." 23:59:59' and ";
		}
	
		if($status!='') {
			$sql_where .= "checkstatus>='".$status."' and ";
		}
	
		$sql_where = rtrim($sql_where,'and ');
	
		//生成排序字符串数据
		$sql_order = " id desc ";
	
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('shop_site')
		-> where($sql_where)
		-> count();// 查询满足要求的总记录数
		$Page = new Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
	
		//执行SQL查询语句
		$list  = $Model -> table('shop_site')
		-> where($sql_where)
		-> order($sql_order)
		-> limit($Page->firstRow.','.$Page->listRows)
		-> select();
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
	
		//数据的读出
		foreach ($list as $keys=>$vals){
	
			if($list[$keys]['flag']=='1'){
	
				$list[$keys]['flags']='<font style="background-color:#00EA00">&nbsp;&nbsp;未注销&nbsp;&nbsp;</font>';
	
			}else if($list[$keys]['flag']=='9'){
				$list[$keys]['flags']='<font style="background-color:#FF1700">&nbsp;&nbsp;已注销&nbsp;&nbsp;</font>';
			}
	
			if($list[$keys]['storestatus']=='1'){
				$list[$keys]['storestatus']='<font style="background-color:#F8F691">&nbsp;&nbsp;等待审核&nbsp;&nbsp;</font>';
			}else if($list[$keys]['storestatus']=='2'){
				$list[$keys]['storestatus']='<font style="background-color:#00EA00">&nbsp;&nbsp;审核成功&nbsp;&nbsp;</font>';
			}else if($list[$keys]['storestatus']=='3'){
				$list[$keys]['storestatus']='<font style="background-color:#FF1700">&nbsp;&nbsp;审核失败&nbsp;&nbsp;</font>';
			}
	
		}
			
		$this -> assign('list',$list);
	
		// 输出模板
		$this->display();
	
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	
	
	}
	
	
	//店铺查看
	public function storeshow(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_storeshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
	
		$id = $this->_post('id');//发布商品的id
		$update_submit = $this->_post('update_submit');
	
		if($update_submit!=''){
	
			if($id==''){
				echo "<script>alert('非法操作！');history(-1);</script>";
				$this ->error('非法操作！');
			}else{
				//数据库的初始化
				$Model = new Model();
	
				$sqldata = "select * from shop_site where id='".$id."'";
				$listdata = $Model->query($sqldata);
	
	
				//是否启用
				$flag_arr = array(
						'1' => '未注销',
						'9' => '已注销',
	
				);
				foreach($flag_arr as $keyc => $valc) {
					$optionflag .= '<option value="'.$keyc.'" ';
					if($listdata[0]['flag']==$keyc) { $optionflag .= ' selected="selected" '; }
					$optionflag .= '>'.$valc.'</option>';
				}
				$this->assign('optionflag',$optionflag);
	
	
					
				$tiaozhuantype = array(
						'3' => '审核失败',
						'2' => '审核成功',
						'1' => '等待审核',
				);
					
				foreach($tiaozhuantype as $keyc => $valc) {
					$optioncheck .= '<option value="'.$keyc.'" ';
					if($listdata[0]['storestatus']==$keyc) { $optioncheck .= ' selected="selected" '; }
					$optioncheck .= '>'.$valc.'</option>';
				}
				$this->assign('optioncheck',$optioncheck);
	
	
				if($listdata[0]['storestatus']=='1'){
					$listdata[0]['storestatus']='<font style="background-color:#F8F691">&nbsp;&nbsp;等待审核&nbsp;&nbsp;</font>';
				}else if($listdata[0]['storestatus']=='2'){
					$listdata[0]['storestatus']='<font style="background-color:#00EA00">&nbsp;&nbsp;审核成功&nbsp;&nbsp;</font>';
				}else if($listdata[0]['storestatus']=='3'){
					$listdata[0]['storestatus']='<font style="background-color:#FF1700">&nbsp;&nbsp;审核失败&nbsp;&nbsp;</font>';
				}
	
				if($listdata[0]['flag']=='1'){
					$listdata[0]['flag']='<font style="background-color:#00EA00">&nbsp;&nbsp;未注销&nbsp;&nbsp;</font>';
				}else if($listdata[0]['flag']=='9'){
					$listdata[0]['flag']='<font style="background-color:#F8F691">&nbsp;&nbsp;已注销&nbsp;&nbsp;</font>';
				}
	
			}
	
			$this->assign('list',$listdata[0]);
		}
	
		$this->display();
	
	}
	
	
	//店铺审核操作
	public function storeupdatedata(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_storeupdatedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
	
		$id = $this->_post('id');//发布商品的id
		$storestatus = $this->_post('storestatus');
		$storemark = $this->_post('storemark');
		$tijiao_submit = $this->_post('tijiao_submit');
		
		
		if($tijiao_submit!=''){
			
			$Model = new Model();
			
			$updatesql  = "update shop_site set storestatus='".$storestatus."',storemark='".$storemark."' where id='".$id."'";
			$updatelist = $Model->execute($updatesql);
			
			if($updatelist) {
				echo "<script>alert('审核操作成功！');window.location.href='".__APP__."/Shanghu/store".$yuurl."';</script>";
				$this -> success('审核操作成功!','__APP__/Shanghu/store'.$yuurl);
			}else {
				echo "<script>alert(''审核操作失败，系统错误!');history.go(-1);</script>";
				$this -> error('审核操作失败，系统错误!');
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