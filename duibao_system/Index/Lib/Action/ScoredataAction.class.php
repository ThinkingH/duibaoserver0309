<?php
/*
 * 数据的查询
 */

class ScoredataAction extends Action{
	
	
	//定义各模块锁定级别
	private $lock_codedata              = '975';
	private $lock_duihuan               = '9751';
	private $lock_scorechang            = '9751';
	private $lock_usertuisong           = '9751';
	private $lock_tempusertuisong       = '9751';
	
	//验证码的发送次数
	public function codedata(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_codedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
	
		//接收用户选择的查询参数
		$date_s      = $this->_get('date_s');
		$date_e      = $this->_get('date_e');
		$phone       = $this->_get('phone');
	
	
		$this->assign('date_s',$date_s);
		$this->assign('date_e',$date_e);
		$this->assign('phone',$phone);
	
		$Model = new Model();
	
		//-----------------------------------------------------------
		//生成where条件判断字符串
		$sql_where = '';
	
		if($date_s!='') {
			$sql_where .= "sendtime>='".strtotime($date_s)."' and ";
		}
	
		if($date_e!='') {
			$sql_where .= "sendtime<='".strtotime($date_e)."' and ";
		}
	
		if($type!='') {
			$sql_where .= "phone='".$phone."' and ";
		}
	
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
	
	
		//生成排序字符串数据
		$sql_order = " id desc ";
	
	
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('xb_vcode_send')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
	
		//执行SQL查询语句
		$list  = $Model -> table('xb_vcode_send')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
	
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		//---------------------------------------------------------------
	
	
		foreach($list as $keyc => $valc) {
	
			if($list[$keyc]['type']=='1') {
				$list[$keyc]['type'] = '注册';
			}else if($list[$keyc]['type']=='2') {
				$list[$keyc]['type'] = '登录';
			}else if($list[$keyc]['type']=='3'){
				$list[$keyc]['type'] = '重置';
			}
			
			$list[$keyc]['sendtime'] = date('Y-m-d H:i:s',$list[$keyc]['sendtime']);
	
		}
			$this -> assign('list',$list);
	
			// 输出模板
			$this->display();
	
			printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	
		}
	
	
		//用户的兑换记录
		public function duihuan(){
			
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_duihuan);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
			
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
			
			//接收用户选择的查询参数
			$date_s      = $this->_get('date_s');
			$date_e      = $this->_get('date_e');
			$phone       = $this->_get('phone');
			$orderno     = $this->_get('orderno');
			$name        = $this->_get('name');
			$miyao       = $this->_get('miyao');
			
		
			$Model = new Model();
			
			//商品的类型
			$typearr = array();
			$sql_type = "select typeid,name,flag from shop_config where flag=1 order by typeid asc";
			$list_type = $Model->query($sql_type);
			
			foreach ($list_type as $keys=>$vals){
				$typearr[$list_type[$keys]['typeid']] = $list_type[$keys]['typeid'].'-'.$list_type[$keys]['name'];
			}
			
			$optiontype = '<option value=""></option>';
			foreach($list_type as $val) {
				$optiontype .= '<option value="'.$val['typeid'].'"';
				if($val['typeid']==$miyao) {
					$optiontype .= ' selected="selected" ';
				}
				$optiontype .= '>'.$val['typeid'].'--'.$val['name'].'</option>';
			}
			$this -> assign('optiontype',$optiontype);
			
			
			$this->assign('date_s',$date_s);
			$this->assign('date_e',$date_e);
			$this->assign('phone',$phone);
			$this->assign('orderno',$orderno);
			$this->assign('name',$name);
			//-----------------------------------------------------------
			//生成where条件判断字符串
			$sql_where = ' ';
			
			if($date_s!='') {
				$sql_where .= " order_createtime>='".$date_s." 00:00:00' and ";
			}
			
			if($date_e!='') {
				$sql_where .= "order_createtime<='".$date_e." 23:59:59' and ";
			}
			
			if($phone!='') {
				$sql_where .= "xb_user.phone='".$phone."' and ";
			}
			
			if($orderno!='') {
				$sql_where .= "orderno='".$orderno."' and ";
			}
			
			if($name!='') {
				$sql_where .= "name like '%".$name."%' and  ";
			}
			
			if($miyao!='') {
				$sql_where .= "typeid='".$miyao."' and ";
			}
			$sql_where = rtrim($sql_where,'and ');
			
			//-----------------------------------------------------------
			
			//id与手机号关联的数组
			$phonearr = array();
			$duihuancodearr = array();
			$overtimearr= array();
			$ordertimearr = array();
			
			
			//渠道编号
			$sitearr = array();
			$sitesql = "select id,name,flag from shop_site where flag='1'";
			$sitelist = $Model->query($sitesql);
			foreach ($sitelist as $keys=>$vals){
				$sitearr[$sitelist[$keys]['id']] = $sitelist[$keys]['id'].'-'.$sitelist[$keys]['name'];
					
			}
			
			//兑换码的使用状态
			$duihuancode_sql  = "select flag,orderno,userid,key_over_datetime,over_datetime from dh_orderlist ";
			$duihuancode_list = $Model->query($duihuancode_sql); 
			
			foreach ($duihuancode_list as $keys=>$vals){
				
				$duihuancodearr[$duihuancode_list[$keys]['orderno']] = $duihuancode_list[$keys]['flag'];
				$overtimearr[$duihuancode_list[$keys]['orderno']]    = $duihuancode_list[$keys]['key_over_datetime'];
				$ordertimearr[$duihuancode_list[$keys]['orderno']]   = $duihuancode_list[$keys]['over_datetime'];
			}
			
			
			$sql_data = 'shop_userbuy.*,xb_user.phone';
				
			//生成排序字符串数据
			$sql_order = " shop_userbuy.id desc ";
				
				
			import('ORG.Page');// 导入分页类
			$count = $Model -> table('shop_userbuy')
							-> join('xb_user on xb_user.id = shop_userbuy.userid')
							-> where($sql_where)
							-> count();// 查询满足要求的总记录数
			$Page = new Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$this->assign('page',$show);// 赋值分页输出
				
			//执行SQL查询语句
			$list  = $Model -> table('shop_userbuy')
							-> join('xb_user on xb_user.id = shop_userbuy.userid')
							-> field($sql_data)
							-> where($sql_where)
							-> order($sql_order)
							-> limit($Page->firstRow.','.$Page->listRows)
							-> select();
			//释放内存
			unset($sql_field, $sql_where, $sql_order);
			
			foreach ($list as $keys=>$vals){
				
				$list[$keys]['siteid'] = isset($sitearr[$list[$keys]['siteid']])?$sitearr[$list[$keys]['siteid']]:$list[$keys]['siteid'];
				$list[$keys]['typeid'] = isset($typearr[$list[$keys]['typeid']])?$typearr[$list[$keys]['typeid']]:$list[$keys]['typeid'];
				
				if($list[$keys]['status']=='1'){
					$list[$keys]['status']=='已使用';
				}else if($list[$keys]['status']=='2'){
					$list[$keys]['status']=='处理中';
				}else if($list[$keys]['status']=='3'){
					$list[$keys]['status']=='等待发货';
				}else if($list[$keys]['status']=='4'){
					$list[$keys]['status']=='已发货';
				}else if($list[$keys]['status']=='9'){
					$list[$keys]['status']=='未使用';
				}
				
			}
			
			
			$this -> assign('list',$list);
			
			// 输出模板
			$this->display();
			
			printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
			
		}
		
		
		//积分记录查询
		public function scorechang(){
			
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_scorechang);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
				
				
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
			
			
			//接收用户选择的查询参数
			$date_s      = $this->_get('date_s');
			$date_e      = $this->_get('date_e');
			$userid       = $this->_get('userid');
			$phone        = $this->_get('phone');
			$type         = $this->_get('type');
			$maintype     = $this->_get('maintype');
			
			$score     = $this->_get('score');
			
			
			$type_arr = array(
						'1' => '增加',
						'2' => '减少',
			);
			$optiontype = '<option value=""></option>';
			foreach($type_arr as $keyc => $valc) {
				$optiontype .= '<option value="'.$keyc.'" ';
				if($type==$keyc) { $optiontype .= ' selected="selected" '; }
				$optiontype .= '>'.$valc.'</option>';
			}
			
			$maintype_arr = array(
					'1' => '任务',
					'2' => '商城',
			);
			$optionmaintype = '<option value=""></option>';
			foreach($maintype_arr as $keyc => $valc) {
				$optionmaintype .= '<option value="'.$keyc.'" ';
				if($maintype==$keyc) { $optionmaintype .= ' selected="selected" '; }
				$optionmaintype .= '>'.$valc.'</option>';
			}
			
			$this->assign('optiontype',$optiontype);
			$this->assign('optionmaintype',$optionmaintype);
			$this->assign('maintype',$maintype);
			$this->assign('type',$type);
			$this->assign('phone',$phone);
			$this->assign('score',$score);
			
			$this->assign('userid',$userid);
			
			$this->assign('date_s',$date_s);
			$this->assign('date_e',$date_e);
			
			
			$Model = new Model();
			
			//-----------------------------------------------------------
			//生成where条件判断字符串
			$sql_where = '';
			
			if($date_s!='') {
				$sql_where .= " gettime>='".strtotime($date_s)."' and ";
			}
			if($date_e!='') {
				$sql_where .= " gettime<='".strtotime($date_e)."' and ";
			}
			
			if($userid!='') {
				$sql_where .= " userid='".$userid."' and ";
			}
			
			if($phone!='') {
				$sql_where .= " xb_user.phone='".$phone."' and ";
			}
			
			if($type!='') {
				$sql_where .= " type='".$type."' and ";
			}
			
			if($maintype!='') {
				$sql_where .= " maintype='".$maintype."' and ";
			}
			
			if($score!=''){
				
				$sql_where .= " score='".$score."' and ";
			}
			
			$sql_where = rtrim($sql_where,'and ');
			
			$sql_data = 'xb_user_score.*,xb_user.phone';
			
			
			//生成排序字符串数据
			$sql_order = " xb_user_score.id desc ";
			
			
			import('ORG.Page');// 导入分页类
			$count = $Model -> table('xb_user_score')
							-> join('xb_user on xb_user.id = xb_user_score.userid')
							-> where($sql_where)
							-> count();// 查询满足要求的总记录数
			$Page = new Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$this->assign('page',$show);// 赋值分页输出
			
			//执行SQL查询语句
			$list  = $Model -> table('xb_user_score')
							-> join('xb_user on xb_user.id = xb_user_score.userid')
							-> field($sql_data)
							-> where($sql_where)
							-> order($sql_order)
							-> limit($Page->firstRow.','.$Page->listRows)
							-> select();
			
			//释放内存
			unset($sql_field, $sql_where, $sql_order);
			
			 // echo $Model->getLastsql();
		 	/*echo '<pre>';
			print_r($list);
			echo '</pre>'; */
			
			foreach ($list as $keys=>$vals){
				
				if($list[$keys]['type']=='1'){
					$list[$keys]['type']='增加';
				}else if($list[$keys]['type']=='9'){
					$list[$keys]['type']='减少';
				}
				
				if($list[$keys]['maintype']=='1'){
					$list[$keys]['maintype']='任务';
				}else if($list[$keys]['maintype']=='2'){
					$list[$keys]['maintype']='商城';
				}
				
				$list[$keys]['gettime'] = date('Y-m-d H:i:s',$list[$keys]['gettime']);
				
			}
			
			$this -> assign('list',$list);
			// 输出模板
			$this->display();
				
			printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		}
		
		
		//用户的推送页面
		public function usertuisong(){
			
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_usertuisong);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
				
				
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
			
			
			//接收用户选择的查询参数
			$date_s      = $this->_get('date_s');
			$date_e      = $this->_get('date_e');
			$userid       = $this->_get('userid');
			$phone        = $this->_get('phone');
			$type         = $this->_get('type');
			$status       = $this->_get('status');
			
			
			$type_arr = array(
						'1' => '积分',
						'2' => '任务',
			);
			$optiontype = '<option value=""></option>';
			foreach($type_arr as $keyc => $valc) {
				$optiontype .= '<option value="'.$keyc.'" ';
				if($type==$keyc) { $optiontype .= ' selected="selected" '; }
				$optiontype .= '>'.$valc.'</option>';
			}
			
			$status_arr = array(
					'1' => '未读',
					'2' => '已读',
			);
			$optionmaintype = '<option value=""></option>';
			foreach($status_arr as $keyc => $valc) {
				$optionmaintype .= '<option value="'.$keyc.'" ';
				if($status==$keyc) { $optionmaintype .= ' selected="selected" '; }
				$optionmaintype .= '>'.$valc.'</option>';
			}
			
			$this->assign('optiontype',$optiontype);
			$this->assign('optionmaintype',$optionmaintype);
			$this->assign('status',$status);
			$this->assign('type',$type);
			$this->assign('phone',$phone);
			
			$this->assign('userid',$userid);
			
			$this->assign('date_s',$date_s);
			$this->assign('date_e',$date_e);
			
			
			$Model = new Model();
			
			//-----------------------------------------------------------
			//生成where条件判断字符串
			$sql_where = '';
			
			if($date_s!='') {
				$sql_where .= " create_inttime>='".strtotime($date_s)."' and ";
			}
			if($date_e!='') {
				$sql_where .= " create_inttime<='".strtotime($date_e)."' and ";
			}
			
			if($userid!='') {
				$sql_where .= " userid='".$userid."' and ";
			}
			
			if($phone!='') {
				$sql_where .= " xb_user.phone='".$phone."' and ";
			}
			
			if($type!='') {
				$sql_where .= " type='".$type."' and ";
			}
			
			if($status!='') {
				$sql_where .= " status='".$status."' and ";
			}
			
			
			$sql_where = rtrim($sql_where,'and ');
			
			$sql_data = 'xb_user_tuisong.*,xb_user.phone';
			
			
			//生成排序字符串数据
			$sql_order = " xb_user_tuisong.id desc ";
			
			
			import('ORG.Page');// 导入分页类
			$count = $Model -> table('xb_user_tuisong')
							-> join('xb_user on xb_user.id = xb_user_tuisong.userid')
							-> where($sql_where)
							-> count();// 查询满足要求的总记录数
			$Page = new Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$this->assign('page',$show);// 赋值分页输出
			
			//执行SQL查询语句
			$list  = $Model -> table('xb_user_tuisong')
							-> join('xb_user on xb_user.id = xb_user_tuisong.userid')
							-> field($sql_data)
							-> where($sql_where)
							-> order($sql_order)
							-> limit($Page->firstRow.','.$Page->listRows)
							-> select();
			
			//释放内存
			unset($sql_field, $sql_where, $sql_order);
			
			
			foreach ($list as $keys=>$vals){
				
				if($list[$keys]['type']=='1'){
					$list[$keys]['type']='积分';
				}else if($list[$keys]['type']=='2'){
					$list[$keys]['type']='任务';
				}
				
				if($list[$keys]['status']=='1'){
					$list[$keys]['status']='未读';
				}else if($list[$keys]['status']=='2'){
					$list[$keys]['status']='已读';
				}
				
				$list[$keys]['create_inttime'] = date('Y-m-d H:i:s',$list[$keys]['create_inttime']);
				
			}
			
			$this -> assign('list',$list);
			// 输出模板
			$this->display();
				
			printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
				
			
		}
		
		
		//临时用户的推送信息
		public function tempusertuisong(){
			
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_tempusertuisong);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
			
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
				
				
			//接收用户选择的查询参数
			$date_s      = $this->_get('date_s');
			$date_e      = $this->_get('date_e');
			$userid      = $this->_get('userid');
				
				
			$type_arr = array(
					'1' => '积分',
					'2' => '任务',
			);
			$optiontype = '<option value=""></option>';
			foreach($type_arr as $keyc => $valc) {
				$optiontype .= '<option value="'.$keyc.'" ';
				if($type==$keyc) { $optiontype .= ' selected="selected" '; }
				$optiontype .= '>'.$valc.'</option>';
			}
				
			$status_arr = array(
					'1' => '未读',
					'2' => '已读',
			);
			$optionmaintype = '<option value=""></option>';
			foreach($status_arr as $keyc => $valc) {
				$optionmaintype .= '<option value="'.$keyc.'" ';
				if($status==$keyc) { $optionmaintype .= ' selected="selected" '; }
				$optionmaintype .= '>'.$valc.'</option>';
			}
				
			$this->assign('optiontype',$optiontype);
			$this->assign('optionmaintype',$optionmaintype);
				
			$this->assign('userid',$userid);
				
			$this->assign('date_s',$date_s);
			$this->assign('date_e',$date_e);
				
				
			$Model = new Model();
				
			//-----------------------------------------------------------
			//生成where条件判断字符串
			$sql_where = '';
				
			if($date_s!='') {
				$sql_where .= " create_inttime>='".strtotime($date_s)."' and ";
			}
			if($date_e!='') {
				$sql_where .= " create_inttime<='".strtotime($date_e)."' and ";
			}
				
			if($userid!='') {
				$sql_where .= " userid='".$userid."' and ";
			}
				
				
			$sql_where = rtrim($sql_where,'and ');
				
				
			//生成排序字符串数据
			$sql_order = " id desc ";
				
				
			import('ORG.Page');// 导入分页类
			$count = $Model -> table('xb_temp_user_tuisong')
							-> where($sql_where)
							-> count();// 查询满足要求的总记录数
			$Page = new Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$this->assign('page',$show);// 赋值分页输出
				
			//执行SQL查询语句
			$list  = $Model -> table('xb_temp_user_tuisong')
							-> where($sql_where)
							-> order($sql_order)
							-> limit($Page->firstRow.','.$Page->listRows)
							-> select();
				
			//释放内存
			unset($sql_field, $sql_where, $sql_order);
				
				
			foreach ($list as $keys=>$vals){
			
				if($list[$keys]['type']=='1'){
					$list[$keys]['type']='积分';
				}else if($list[$keys]['type']=='2'){
					$list[$keys]['type']='任务';
				}
			
				if($list[$keys]['status']=='1'){
					$list[$keys]['status']='未读';
				}else if($list[$keys]['status']=='2'){
					$list[$keys]['status']='已读';
				}
			
				$list[$keys]['create_inttime'] = date('Y-m-d H:i:s',$list[$keys]['create_inttime']);
			
			}
				
			$this -> assign('list',$list);
			// 输出模板
			$this->display();
			
			printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
			
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