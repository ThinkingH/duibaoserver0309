<?php

/*
 * 用户信息的查看
 */

class UserlistAction extends Action{
	
	//定义各模块锁定级别
	private $lock_normaluser              = '9751';
	private $lock_jifenchangshow          = '975';
	private $lock_tempjifenchangshow      = '975';
	private $lock_jifenshowdata           = '975';
	private $lock_updateusershow          = '975';
	private $lock_tempupdateusershow      = '975';
	private $lock_updateusershowdata      = '975';
	private $lock_deleteuserdata         = '97';
	private $lock_tempuser              = '9751';
	
	//用户信息的展示
	public function normaluser(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_normaluser);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		//接收用户选择的查询参数
		$phone           = $this->_get('phone');
		$flag_s          = $this->_get('flag_s');
		$nickname        = $this->_get('nickname');
		$sex             = $this->_get('sex');
		$date_s         = $this->_get('date_s');
		$date_e         = $this->_get('date_e');
		$bianhao      = $this->_get('bianhao');
		
		
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
		
		$sex_arr = array(
				'1' => '男',
				'2' => '女',
				'3' => '保密',
		);
		$optionsex = '<option value=""></option>';
		foreach($sex_arr as $keyc => $valc) {
			$optionsex .= '<option value="'.$keyc.'" ';
			if($sex==$keyc) { $optionsex .= ' selected="selected" '; }
			$optionsex .= '>'.$valc.'</option>';
		}
		
		$phonetype_arr = array(
				'1' => '安卓',
				'2' => 'ios',
		);
		$optionphonetype = '<option value=""></option>';
		foreach($phonetype_arr as $keyc => $valc) {
			$optionphonetype .= '<option value="'.$keyc.'" ';
			if($phonetype==$keyc) { $optionphonetype .= ' selected="selected" '; }
			$optionphonetype .= '>'.$valc.'</option>';
		}
		
		
		$this->assign('optionflag',$optionflag);
		$this->assign('phone',$phone);
		$this->assign('optionphonetype',$optionphonetype);
		$this->assign('nickname',$nickname);
		$this->assign('optionsex',$optionsex);
		
		$this->assign('date_s',$date_s);
		$this->assign('date_e',$date_e);
		$this->assign('bianhao',$bianhao);
		
		$Model = new Model();
		
		
		//-----------------------------------------------------------
		//生成where条件判断字符串
		$sql_where = '';
		
		if($flag_s!='') {
			$sql_where .= " is_lock='".$flag_s."' and ";
		}
		if($phone!='') {
			$sql_where .= " phone='".$phone."' and ";
		}
		
		if($nickname!='') {
			$sql_where .= " nickname like '%".$nickname."%' and ";
		}
		
		if($sex!='') {
			$sql_where .= " sex='".$sex."' and ";
		}
		
		if($bianhao!='') {
			$sql_where .= " id='".$bianhao."' and ";
		}
		
		if($date_s!='') {
			$sql_where .= " create_datetime>='".$date_s."' and ";
		}
		
		if($date_e!='') {
			$sql_where .= " create_datetime<='".$date_e."' and ";
		}
		
		
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
		
		
		//生成排序字符串数据
		$sql_order = " create_datetime desc ";
		
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('xb_user')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('xb_user')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		//---------------------------------------------------------------
		
		
		foreach($list as $keyc => $valc) {
		
			if($list[$keyc]['is_lock']=='1') {
				$list[$keyc]['is_lock'] = '<font style="background-color:#00EA00">&nbsp;&nbsp;启&nbsp;&nbsp;用&nbsp;&nbsp;</font>';
			}else if($list[$keyc]['is_lock']=='9') {
				$list[$keyc]['is_lock'] = '<font style="background-color:#FF1700">&nbsp;&nbsp;关&nbsp;&nbsp;闭&nbsp;&nbsp;</font>';
			}else {
				$list[$keyc]['is_lock'] = 'ERR';
			}
			
			if($list[$keyc]['sex']=='1') {
				$list[$keyc]['sex'] = '男';
			}else if($list[$keyc]['sex']=='2') {
				$list[$keyc]['sex'] = '女';
			}else if($list[$keyc]['sex']=='3') {
				$list[$keyc]['sex'] = '保密';
			}else {
		
			}
				
			if($list[$keyc]['phonetype']=='1') {
				$list[$keyc]['phonetype'] = '1-安卓';
			}else if($list[$keyc]['phonetype']=='2') {
				$list[$keyc]['phonetype'] = '2-ios';
			}else {
		
			}
			
			if($list[$keyc]['vipflag']=='10') {
				$list[$keyc]['vipflag'] = '普通用户';
			}else if($list[$keyc]['vipflag']=='1') {
				$list[$keyc]['vipflag'] = '会员用户';
			}
			
			if($list[$keyc]['logintime']!='0'){
				$list[$keyc]['logintime'] = date('Y-m-d H:i:s',$list[$keyc]['logintime']);
			}
			
			if('0000-00-00 00:00:00' == $list[$keyc]['vip_endtime_one']){
				$list[$keyc]['day']='0';
			}else{
				$list[$keyc]['day'] = ceil(((strtotime($list[$keyc]['vip_endtime_one']) - time() )/86400));//会员天数
				if($list[$keyc]['day']<=0){
					$list[$keyc]['day']='0';
				}
			}
			
			
			if($list[$keyc]['vip_endtime_one']<=date('Y-m-d H:i:s') && $list[$keyc]['vip_endtime_one']!='0000-00-00 00:00:00') {
				$list[$keyc]['vip_endtime_one'] = '<font style="background-color:#FF1700">&nbsp;'.$list[$keyc]['vip_endtime_one'].'&nbsp;</font>';
			}
			
			$list[$keyc]['keyong_money']   = $list[$keyc]['keyong_money']/100;
			$list[$keyc]['dongjie_money']  = $list[$keyc]['dongjie_money']/100;
				
		}
		
		$this -> assign('list',$list);
		
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	}
	
	
	
	
	//积分的变动--展示页面
	public function jifenchangshow(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_jifenchangshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$jifen_submit = $this->_post('jifen_submit');
		$id           = $this->_post('id');
		$tablename    = $this->_post('tablename');
		
		
		if($tablename=='1'){
			
			$tablename = 'xb_user';
			$temptablename = 'xb_user_score';
			$tiaozhuan  = 'normaluser';
			
			$this -> assign('shuoming','正式用户积分变动');
			$this -> assign('tiaozhuan',$tiaozhuan);
			
		}else if($tablename=='2'){
			
			$tablename = 'xb_temp_user';
			$temptablename = 'xb_temp_user_score';
			$tiaozhuan  = 'tempuser';
			
			$this -> assign('shuoming','非正式用户积分变动');
			$this -> assign('tiaozhuan',$tiaozhuan);
		}
		
		
		if($jifen_submit==''){
			echo "<script>alert('非法操作！');history.go(-1);</script>";
			$this->error('非法操作！');
		}else{
			
			if($id==''){
				
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this->error('非法操作！');
				
			}else{
				
				//数据库初始化
				$Model = new Model();
				
				//查询app用户表中对应总金额
				$sql_getmoney = "select id,phone,openid,keyong_jifen from $tablename where id='".$id."'";
				$list_getmoney = $Model->query($sql_getmoney);
				
				$phonedata  = $list_getmoney[0]['phone'];
				$openiddata = $list_getmoney[0]['openid'];
				
				if(is_numeric($phonedata) && strlen($phonedata)=='11'){
					$biaoshiid=$list_getmoney[0]['phone'];
				}
					
				if(strlen($openiddata)>='12'){
					$biaoshiid=$list_getmoney[0]['openid'];
				}
				
				
				//商城用户表中的积分
				$tp_shangchneg  = "select pay_points from duibaoshop.tp_users where mobile='".$biaoshiid."'";
				$list_shancheng = $Model->query($tp_shangchneg);
				
				
				if(count($list_getmoney)<=0) {
					echo "<script>alert('非法操作！');history.go(-1);</script>";
					$this->error('非法操作！');
				}else {
					
					//查询30条该渠道变更日志记录
					$sql_sitelog = "select * from $temptablename where userid='".$id."' order by id desc limit 30";
					$list_sitelog = $Model->query($sql_sitelog);
					
					
					foreach ($list_sitelog as $keys => $vals){
						
						$list_sitelog[$keys]['gettime'] = date('Y-m-d H:i:s',$list_sitelog[$keys]['gettime']);
						
						if($list_sitelog[$keys]['type']=='1'){
							$list_sitelog[$keys]['type']='增加';
						}else if($list_sitelog[$keys]['type']=='9'){
							$list_sitelog[$keys]['type']='减少';
							
						}
					}
				
					$this->assign('list',$list_getmoney[0]);
					$this->assign('loglist',$list_sitelog);
					$this->assign('slist',$list_shancheng[0]);
				
				}
			}
			
		}
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	//临时用户积分的变更
	public function tempjifenchangshow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_tempjifenchangshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$jifen_submit = $this->_post('jifen_submit');
		$id           = $this->_post('id');
		$tablename    = $this->_post('tablename');
		
		
		if($tablename=='1'){
				
			$tablename = 'xb_user';
			$temptablename = 'xb_user_score';
			$tiaozhuan  = 'normaluser';
				
			$this -> assign('shuoming','正式用户积分变动');
			$this -> assign('tiaozhuan',$tiaozhuan);
				
		}else if($tablename=='2'){
				
			$tablename = 'xb_temp_user';
			$temptablename = 'xb_temp_user_score';
			$tiaozhuan  = 'tempuser';
				
			$this -> assign('shuoming','非正式用户积分变动');
			$this -> assign('tiaozhuan',$tiaozhuan);
		}
		
		
		if($jifen_submit==''){
			echo "<script>alert('非法操作！');history.go(-1);</script>";
			$this->error('非法操作！');
		}else{
				
			if($id==''){
		
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this->error('非法操作！');
		
			}else{
		
				//数据库初始化
				$Model = new Model();
		
				//查询渠道对应总金额
				$sql_getmoney = "select id,tokenkey,keyong_jifen from $tablename where id='".$id."'";
				$list_getmoney = $Model->query($sql_getmoney);
	
				if(count($list_getmoney)<=0) {
					echo "<script>alert('非法操作！');history.go(-1);</script>";
					$this->error('非法操作！');
				}else {
		
					//查询50条该渠道变更日志记录
					$sql_sitelog = "select * from $temptablename where userid='".$id."' order by id desc limit 50";
					$list_sitelog = $Model->query($sql_sitelog);
						
					foreach ($list_sitelog as $keys => $vals){
						
						$list_sitelog[$keys]['gettime'] = date('Y-m-d H:i:s',$list_sitelog[$keys]['gettime']);
						
						if($list_sitelog[$keys]['type']=='1'){
							$list_sitelog[$keys]['type']='增加';
						}else if($list_sitelog[$keys]['type']=='9'){
							$list_sitelog[$keys]['type']='减少';
							
						}
					}
					
					$this->assign('list',$list_getmoney[0]);
					$this->assign('loglist',$list_sitelog);
		
				}
			}
				
		}
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	
	//积分增加
	public function jifenshowdata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_jifenshowdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$jifenup_submit = $this->_post('jifenup_submit');       //积分修改按钮
		$id             = $this->_post('id');                 //对应id
		$jifentype      = $this->_post('jifentype');          //积分增加的类型
		$upprice        = $this->_post('upprice');            //增加积分的值
		$remark         = $this->_post('remark');
		
		$tablename    = $this->_post('tablename');
		
		$tablename     = 'xb_user';
		$temptablename = 'xb_user_score';
		$tiaozhuan     = 'normaluser';
		
		
		if($jifenup_submit=='') {
			echo "<script>alert('非法操作！');history.go(-1);</script>";
			$this->error('非法操作！');
				
		}else {
				
			if($id==''){
				
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this -> error('非法操作！');
				
			}
				
			if($jifentype=='add') {
				if(substr($upprice,0,1)!='+') {
					echo "<script>alert('积分填写与积分修改类型不一致，请确认积分开头是否是以+开头');history.go(-1);</script>";
					$this -> error('积分填写与积分修改类型不一致，请确认积分开头是否是以+开头');
				}
			}else if($jifentype=='plus') {
				if(substr($upprice,0,1)!='-') {
					echo "<script>alert('积分填写与积分修改类型不一致，请确认积分开头是否是以-开头');history.go(-1);</script>";
					$this -> error('积分填写与积分修改类型不一致，请确认积分开头是否是以-开头');
				}
			}
				
		
			//数据库初始化
			$Model = new Model();
			
			$phone_sql  = "select id,phone,openid,keyong_jifen from xb_user where id='".$id."'";
			$phone_list = $Model->query($phone_sql);
			
			$phonedata  = $phone_list[0]['phone'];
			$openiddata = $phone_list[0]['openid'];
				
			if(is_numeric($phonedata) && strlen($phonedata)=='11'){
				$biaoshiid=$phone_list[0]['phone'];
			}
			
			if(strlen($openiddata)>='12'){
				$biaoshiid=$phone_list[0]['openid'];
			}
			
			//判断该用户是否存在商城中
			$cunzai_sql  = "select user_id from duibaoshop.tp_users where mobile='".$biaoshiid."'";
			$cunzai_list = $Model->query($cunzai_sql);
			
			if($cunzai_list[0]['user_id']>0){
					
				//先更新app用户表和商城用户表中的积分同步
				$users_two_sql  = "update duibaoshop.tp_users set pay_points = '".$phone_list[0]['keyong_jifen']."' where mobile='".$biaoshiid."' ";
				$users_two_list = $Model->execute($users_two_sql);
					
				//对应tpshop表中的积分同步增加
				$sql_tpshopscore  = "update duibaoshop.tp_users set pay_points = pay_points  ".$upprice." where mobile='".$biaoshiid."'";
				$list_tpshopscore = $Model->execute($sql_tpshopscore);
			}
			
			
			//更新更新用户的积分
			$sql_upsitemoney = "update $tablename set keyong_jifen=keyong_jifen ".$upprice." where id='".$id."'";
			$ret = $Model->execute($sql_upsitemoney);
				
			if($ret) {
				//将此次金额变更写入日志
				$data['userid']          = $id;
				$data['maintype']        = '1';
				$data['gettime']         = time();
				$data['type']           = '1';
				$data['score']          = $upprice;
				$data['getdescribe']    = '后台操作积分的变更';
				$data['remark']         = $remark;
		
				$Model->table($temptablename)->add($data);
				
		
			 	echo "<script>alert('数据添加成功！');window.location.href='".__APP__."/Userlist/'".$tiaozhuan."'".$yuurl."';</script>";
				$this ->success('数据添加成功!','__APP__/Userlist/'.$tiaozhuan.$yuurl);
		
			}else {
				echo "<script>alert('数据添加失败，系统错误!');history.go(-1);</script>";
				$this -> error('数据添加失败，系统错误!');
		
			}
				
		}
		
		
		
	}
	
	
	
	//积分的修改
	public function updateusershow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updateusershow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		$update_submit = $this->_post('update_submit');       //积分修改按钮
		$id             = $this->_post('id');                 //对应id
		$tablename    = $this->_post('tablename');
		
		if($tablename=='1'){
		
			$tablename = 'xb_user';
			$temptablename = 'xb_user_score';
			$tiaozhuan  = 'normaluser';
			
			$this -> assign('shuoming','正式用户信息修改');
			$this -> assign('tiaozhuan',$tiaozhuan);
			$this -> assign('fanhui','返回用户信息查询页面');
		
		}else if($tablename=='2'){
		
			$tablename = 'xb_temp_user';
			$temptablename = 'xb_temp_user_score';
			$tiaozhuan  = 'tempuser';
			
			$this -> assign('shuoming','临时用户信息修改');
			$this -> assign('tiaozhuan',$tiaozhuan);
			$this -> assign('fanhui','返回临时用户信息查询页面');
		}
		
		
		if($update_submit==''){
			echo "<script>alert('非法操作！');history.go(-1);</script>";
			$this->error('非法操作！');
				
		}else {
				
			if($id=='') {
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this->error('非法操作！');
			}
			
			//数据库初始化
			$Model = new Model();
			
			
			//查询用户表的信息
			$sql_basicsite = "select * from $tablename where id=".$id;
			$list = $Model->query($sql_basicsite);
			
			if(count($list)<=0) {
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this->error('非法操作！');
			}else {
			
				$option_flag = '';
				$option_flag .= '<option value="1" ';
				if($list[0]['is_lock']==1) { $option_flag .= ' selected="selected" '; }
				$option_flag .= '>启用</option>';
				$option_flag .= '<option value="9" ';
				if($list[0]['is_lock']==9) { $option_flag .= ' selected="selected" '; }
				$option_flag .= '>关闭</option>';
				
				
				foreach ($list as $keys => $vals){
					
					if($list[$keys]['sex']=='1'){
						$list[$keys]['sex']='男';
					}else if($list[$keys]['sex']=='2'){
						$list[$keys]['sex']='女';
					}else{
						$list[$keys]['sex']='保密';
					}
					
					
					if($list[$keys]['phonetype']=='1'){
						$list[$keys]['phonetype']='安卓';
					}else if($list[$keys]['phonetype']=='2'){
						$list[$keys]['phonetype']='ios';
					}
				}
			
			
				$this-> assign('option_flag',$option_flag);
				$this -> assign('list',$list[0]);
			}
		}
			
			
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
		
	}
	
	
	//临时用户信息的修改
	public function tempupdateusershow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_tempupdateusershow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		$update_submit = $this->_post('update_submit');       //积分修改按钮
		$id             = $this->_post('id');                 //对应id
		$tablename    = $this->_post('tablename');
		
		if($tablename=='1'){
		
			$tablename = 'xb_user';
			$temptablename = 'xb_user_score';
			$tiaozhuan  = 'normaluser';
				
			$this -> assign('shuoming','正式用户信息修改');
			$this -> assign('tiaozhuan',$tiaozhuan);
		
		}else if($tablename=='2'){
		
			$tablename = 'xb_temp_user';
			$temptablename = 'xb_temp_user_score';
			$tiaozhuan  = 'tempuser';
				
			$this -> assign('shuoming','临时用户信息修改');
			$this -> assign('tiaozhuan',$tiaozhuan);
		}
		
		
		if($update_submit==''){
			echo "<script>alert('非法操作！');history.go(-1);</script>";
			$this->error('非法操作！');
		
		}else {
		
			if($id=='') {
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this->error('非法操作！');
			}
				
			//数据库初始化
			$Model = new Model();
				
				
			//查询用户表的信息
			$sql_basicsite = "select * from $tablename where id=".$id;
			$list = $Model->query($sql_basicsite);
				
			if(count($list)<=0) {
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this->error('非法操作！');
			}else {
					
				$this-> assign('option_flag',$option_flag);
				$this -> assign('list',$list[0]);
			}
		}
			
			
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	//用户信息的修改
	public function updateusershowdata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updateusershowdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		$update_submit = $this->_post('update_submit');       //积分修改按钮
		$id             = $this->_post('id');                 //对应id
		$tablename    = $this->_post('tablename');
		
		
		
		
		
		if($update_submit=='') {
			echo "<script>alert('非法操作！');history.go(-1);</script>";
			$this->error('非法操作！');
				
		}else {
				
			//判断是否为纯数字
			if($id=='') {
				echo "<script>alert('非法操作-');history.go(-1);</script>";
				$this -> error('非法操作-');
			}
				
			if($tablename=='1'){
				
				$username = 'xb_user';
				$tiaozhuan  = 'normaluser';
				
				$userlevel = $this->_post('userlevel');
				$flag = $this->_post('flag');
				
				//更新数据
				$data['is_lock']    = $flag;
				$data['userlevel']  = $userlevel;
				
			}else if($tablename=='2'){
				
				$username = 'xb_temp_user';
				$tiaozhuan  = 'tempuser';
				
				$remark = $this->_post('remark');
				
				$data['remark']    = $remark;
				
			}
			
			//数据库初始化
			$Model = new Model();
		
			$ret = $Model ->table($username) -> where("id='".$id."'") -> save($data);
		
		
			$templogs = $Model->getlastsql();
			//hy_caozuo_logwrite($templogs,__CLASS__.'---'.__FUNCTION__);
		
		
			if($ret) {
				echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Userlist/'".$tiaozhuan."'".$yuurl."';</script>";
				$this ->success('数据修改成功!','__APP__/Userlist/'.$tiaozhuan.$yuurl);
			}else {
				echo "<script>alert('数据修改失败，您未做任何改动!');history.go(-1);</script>";
				$this -> error('数据修改失败，您未做任何改动!');
			}
				
		}
		
		
	}
	
	
	//数据的删除
	public function deleteuserdata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_deleteuserdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		$delete_submit  = $this->_post('delete_submit');       //积分修改按钮
		$id             = $this->_post('id');                 //对应id
		$tablename      = $this->_post('tablename');
		
		
		if($tablename=='1'){
		
			$tablename = 'xb_user';
			$temptablename = 'xb_user_score';
			$tiaozhuan  = 'normaluser';
				
		}else if($tablename=='2'){
		
			$tablename = 'xb_temp_user';
			$temptablename = 'xb_temp_user_score';
			$tiaozhuan  = 'tempuser';
		}
		
		
		if($delete_submit!=''){
				
			if($id=='') {
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this -> error('非法操作！');
			}
				
			//数据库初始化
			$Model = new Model();
			
			$ret1 = $Model -> table($tablename) -> where("id='".$id."'") -> find();
			
			if($ret1['is_lock']=='1'){
				echo "<script>alert('只有关闭状态的数据才可以进行删除!');history.go(-1);</script>";
				$this -> error('只有关闭状态的数据才可以进行删除!');
			}
			
			//数据的删除
			$ret = $Model -> table($tablename) -> where("id='".$id."'") -> delete();
			
			if($ret) {
				echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Userlist/'".$tiaozhuan."'".$yuurl."';</script>";
				$this -> success('数据删除成功!','__APP__/Userlist/'.$tiaozhuan.$yuurl);
			}else {
				echo "<script>alert('数据删除失败，系统错误!');history.go(-1);</script>";
				$this -> error('数据删除失败，系统错误!');
			}
		}
		
		
		
	}
	
	
	
	//临时用户
	public function tempuser(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_tempuser);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		//接收用户选择的查询参数submit_select
		//$date_s      = substr($this->_get('date_s'),0,10);
		$id          = $this->_get('id');
		$score       = $this->_get('score');
		$date_s       = $this->_get('date_s');
		$date_e       = $this->_get('date_e');
		
		
		$submit_select  = $this->_get('submit_select');
		
		$this -> assign('id',$id);
		$this -> assign('score',$score);
		$this -> assign('date_s',$date_s);
		$this -> assign('date_e',$date_e);
		
		$Model = new Model();
		
		//-----------------------------------------------------------
		//生成where条件判断字符串
		$sql_where = '';
		
		if($id!='') {
			$sql_where .= "id='".$id."' and ";
		}
		
		if($score!='') {
			$sql_where .= "keyong_jifen='".$score."' and ";
		}
		
		if($date_s!='') {
			$sql_where .= "create_datetime>='".$date_s."' and ";
		}
		
		if($date_e!='') {
			$sql_where .= "create_datetime<='".$date_e."' and ";
		}
		
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
		
		
		//生成排序字符串数据
		$sql_order = " create_datetime desc";
		
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('xb_temp_user')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('xb_temp_user')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		//---------------------------------------------------------------
		
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