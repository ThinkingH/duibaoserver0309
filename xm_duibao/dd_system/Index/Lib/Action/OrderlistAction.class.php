<?php
/*
 * 订单数据的查询
 */

class OrderlistAction extends Action {
	
	//定义各模块锁定级别
	private $lock_index              = '9751';
	private $lock_pingjia            = '975';
	private $lock_dealorder          = '975';
	private $lock_updateshow          = '975';
	private $lock_updatedata          = '975';
	private $lock_shiwuorder          = '975';
	private $lock_orderinfoshow          = '975';
	
	
		//用户的兑换记录
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
			$phone       = $this->_get('phone');
			$orderno     = $this->_get('orderno');
			$name        = $this->_get('name');
			$zstatus     = $this->_get('zstatus');
			
		
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
			
			
			$status_arr = array(
					'3' => '待发货',
					'4' => '已领取',
					'5' => '待确认',
					'6' => '待评价',
					'7' => '已评价',
			);
			$optionstatus = '<option value=""></option>';
			foreach($status_arr as $keyc => $valc) {
				$optionstatus .= '<option value="'.$keyc.'" ';
				if($zstatus==$keyc) { $optionstatus .= ' selected="selected" '; }
				$optionstatus .= '>'.$valc.'</option>';
			}
			
			$this->assign('optionstatus',$optionstatus);
			
			
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
			
			if($zstatus!='') {
				$sql_where .= "status='".$zstatus."' and ";
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
					$list[$keys]['status']='已使用';
				}else if($list[$keys]['status']=='2'){
					$list[$keys]['status']='处理中';
				}else if($list[$keys]['status']=='3'){
					$list[$keys]['status']='待发货';
				}else if($list[$keys]['status']=='4'){
					$list[$keys]['status']='已领取';
				}else if($list[$keys]['status']=='5'){
					$list[$keys]['status']='待确认';
				}else if($list[$keys]['status']=='6'){
					$list[$keys]['status']='待评价';
				}else if($list[$keys]['status']=='7'){
					$list[$keys]['status']='已评价';
				}
				
			}
			
			
			$this -> assign('list',$list);
			
			// 输出模板
			$this->display();
			
			printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
			
		}
		
		
		//评价管理
		public function pingjia(){
			
			
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_pingjia);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
				
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
			
			//获取相应的参数
			$date_s = $this->_get('date_s');
			$date_e = $this->_get('date_e');
			
			if($date_s==''){
				$date_s = date('Y-m-d');
			}
			
			if($date_e==''){
				$date_e = date('Y-m-d');
			}
			
			$this->assign('date_s',$date_s);
			$this->assign('date_e',$date_e);
			
			$Model = new Model();
			
			//用户id的查询
			$userarr = array();
			$useridsql  = "select id,phone from xb_user where is_lock=1 ";
			$useridlist = $Model->query($useridsql);
			
			foreach ($useridlist as $keys => $vals){
				
				$userarr[$useridlist[$keys]['id']] = $useridlist[$keys]['phone'];
			}
			
			//商户的编号
			$sitearr = array();
			$siteidsql = "select id,name from shop_site where flag=1";
			$sitelist  = $Model->query($siteidsql);
			
			foreach ($sitelist as $keys=>$vals){
				
				$sitearr[$sitelist[$keys]['id']] = $sitelist[$keys]['id'].'--'.$sitelist[$keys]['name'];
			}
			
			//商城名称
			$shopnamearr = array();
			$shopnamesql = "select * from shop_product where status=1 and flag=1  ";
			$shopnamelist = $Model->query($shopnamesql);
			
			foreach ($shopnamelist as $keys => $vlas){
				$shopnamearr[$shopnamelist[$keys]['id']] = $shopnamelist[$keys]['id'].'--'.$shopnamelist[$keys]['name'];
			}
			
			
			//生成where条件判断字符串
			$sql_where = ' ';
				
			if($date_s!='') {
				$sql_where .= " create_datetime>='".$date_s." 00:00:00' and ";
			}
				
			if($date_e!='') {
				$sql_where .= "create_datetime<='".$date_e." 23:59:59' and ";
			}
				
			$sql_where = rtrim($sql_where,'and ');
			
			
			
			//生成排序字符串数据
			$sql_order = " id desc ";
			
			import('ORG.Page');// 导入分页类
			$count = $Model -> table('shop_comment')
							-> where($sql_where)
							-> count();// 查询满足要求的总记录数
			$Page = new Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
			$show = $Page->show();// 分页显示输出
				
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$this->assign('page',$show);// 赋值分页输出
			
			//执行SQL查询语句
			$list  = $Model -> table('shop_comment')
							-> where($sql_where)
							-> order($sql_order)
							-> limit($Page->firstRow.','.$Page->listRows)
							-> select();
			
			foreach ($list as $keys=>$vals){
				
				$list[$keys]['phone']   = isset($userarr[$list[$keys]['userid']])?$userarr[$list[$keys]['userid']]:$list[$keys]['userid'];//用户手机号
				$list[$keys]['siteid']  = isset($sitearr[$list[$keys]['siteid']])?$sitearr[$list[$keys]['siteid']]:$list[$keys]['siteid'];//渠道编号
				$list[$keys]['productid'] = isset($shopnamearr[$list[$keys]['productid']])?$shopnamearr[$list[$keys]['productid']]:$list[$keys]['productid'];
				
			}
			
			$this -> assign('list',$list);
				
			// 输出模板
			$this->display();
				
			printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
			
			
		}
		
		
		//订单处理查询
		public function dealorder(){
				
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_dealorder);
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
			$zstatus     = $this->_get('zstatus');
			$duihuan     = $this->_get('duihuan');
			$bianhao     = $this->_get('bianhao');
			$siteid     = $this->_get('siteid');
			
				
		
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
			
			
			//商家编号
			$sitearr = array();
			$sql_siteid = "select id,lianxiren from shop_site where flag=1 and checkstatus='2' order by id asc";
			$list_siteid = $Model->query($sql_siteid);
			
			$optionsiteid = '<option value=""></option>';
			foreach($list_siteid as $val) {
				$sitearr[$val['id']] = $val['id'].'-'.$val['lianxiren'];
				$optionsiteid .= '<option value="'.$val['id'].'"';
				if($val['id']==$siteid) {
					$optionsiteid .= ' selected="selected" ';
				}
				$optionsiteid .= '>'.$val['id'].'--'.$val['lianxiren'].'</option>';
			}
			$this -> assign('optionsiteid',$optionsiteid);
			
			
			$status_arr = array(
					'3' => '待领取',
					'4' => '已领取',
					'5' => '待确认',
					'6' => '待评价',
					'7' => '已评价',
			);
			$optionstatus = '<option value=""></option>';
			foreach($status_arr as $keyc => $valc) {
				$optionstatus .= '<option value="'.$keyc.'" ';
				if($zstatus==$keyc) { $optionstatus .= ' selected="selected" '; }
				$optionstatus .= '>'.$valc.'</option>';
			}
				
			$this->assign('optionstatus',$optionstatus);
			
			$duihuan_arr = array(
					'1' => '未兑换',
					'2' => '已兑换',
			);
			$optionduihuan = '<option value=""></option>';
			foreach($duihuan_arr as $keyc => $valc) {
				$optionduihuan .= '<option value="'.$keyc.'" ';
				if($duihuan==$keyc) { $optionduihuan .= ' selected="selected" '; }
				$optionduihuan .= '>'.$valc.'</option>';
			}
				
			$this->assign('optionduihuan',$optionduihuan);
				
				
			$this->assign('date_s',$date_s);
			$this->assign('date_e',$date_e);
			$this->assign('phone',$phone);
			$this->assign('orderno',$orderno);
			$this->assign('name',$name);
			$this->assign('bianhao',$bianhao);
			//-----------------------------------------------------------
			//生成where条件判断字符串
			$sql_where = ' mtype=1  and ';
				
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
			
			
			if($zstatus!='') {
				$sql_where .= "status='".$zstatus."' and ";
			}
			
			if($bianhao!='') {
				$sql_where .= "shop_userbuy.id='".$bianhao."' and ";
			}
			
			if($siteid!='') {
				$sql_where .= "siteid='".$siteid."' and ";
			}
			
			$sql_where = rtrim($sql_where,'and ');
				
			//-----------------------------------------------------------
				
			//id与手机号关联的数组
			$phonearr = array();
			$duihuancodearr = array();
			$overtimearr= array();
			$ordertimearr = array();
				
				
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
			//echo $Model->getLastsql();
			//释放内存
			unset($sql_field, $sql_where, $sql_order);
				
			foreach ($list as $keys=>$vals){
		
				$list[$keys]['siteid'] = isset($sitearr[$list[$keys]['siteid']])?$sitearr[$list[$keys]['siteid']]:$list[$keys]['siteid'];
				$list[$keys]['typeid'] = isset($typearr[$list[$keys]['typeid']])?$typearr[$list[$keys]['typeid']]:$list[$keys]['typeid'];
				
				//金额处理
				$list[$keys]['price'] = number_format($list[$keys]['price']/100,2);
				
				if($list[$keys]['status']=='1'){
					$list[$keys]['status']='已使用';
				}else if($list[$keys]['status']=='2'){
					$list[$keys]['status']='处理中';
				}else if($list[$keys]['status']=='3'){
					$list[$keys]['status']='待领取';
				}else if($list[$keys]['status']=='4'){
					$list[$keys]['status']='已领取';
				}else if($list[$keys]['status']=='9'){
					$list[$keys]['status']='未使用';
				}else if($list[$keys]['status']=='5'){
					$list[$keys]['status']='待确认';
				}else if($list[$keys]['status']=='6'){
					$list[$keys]['status']='待评价';
				}else if($list[$keys]['status']=='7'){
					$list[$keys]['status']='已评价';
				}
				
			}
				
				
			$this -> assign('list',$list);
				
			// 输出模板
			$this->display();
				
			printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
				
		}
		
		//虚拟商品的兑换修改
		public function updateshow(){
		
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_updateshow);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
		
			$id = $this->_post('id');
			$update_submit = $this->_post('update_submit');
		
			$Model = new Model();
		
			$sqldata = "select * from shop_userbuy where id='".$id."'";
			$listdata = $Model->query($sqldata);
		
			if(count($listdata)<=0){
				echo "<script>alert('非法操作');history.go(-1);</script>";
				$this -> error('非法操作');
			}else{
				
				$this->assign('list',$listdata[0]);
			}
			
		
			$this->display();
			
			printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
		
		}
		
		
		//实物商品兑换修改
		public function shiwuupdateshow(){
		
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_shiwuupdateshow);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
		
			$id = $this->_post('id');
			$update_submit = $this->_post('update_submit');
		
			$Model = new Model();
		
			$sqldata = "select * from shop_userbuy where id='".$id."'";
			$listdata = $Model->query($sqldata);
			
			if(count($listdata)<=0){
				echo "<script>alert('非法操作');history.go(-1);</script>";
				$this -> error('非法操作');
			}else{
				
				//用户的地址
				$user_address_sql  = "select * from xb_user_address where id='".$listdata[0]['address_id']."' ";
				$user_address_list = $Model->query($user_address_sql); 
				
				$this->assign('alist',$user_address_list[0]);
				$this->assign('list',$listdata[0]);
			}
			
		
			$this->display();
			
			printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
		
		}
		public function orderinfoshow(){
		
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_orderinfoshow);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
		
			$id = $this->_post('id');
			$update_submit = $this->_post('update_submit');
		
			$Model = new Model();
		
			$sqldata = "select * from shop_userbuy where id='".$id."'";
			$listdata = $Model->query($sqldata);
			
			if(count($listdata)<=0){
				echo "<script>alert('非法操作');history.go(-1);</script>";
				$this -> error('非法操作');
			}else{
				//金额处理
				$listdata[0]['price'] = number_format($listdata[0]['price']/100,2);
				//用户的地址
				$user_address_sql  = "select * from xb_user_address where id='".$listdata[0]['address_id']."' ";
				$user_address_list = $Model->query($user_address_sql); 
				
				$this->assign('alist',$user_address_list[0]);
				$this->assign('list',$listdata[0]);
			}
			
		
			$this->display();
			
			printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
		
		}
		
		
		//兑换操作
		public function updatedata(){
			
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_updatedata);
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
			//拼接url参数
			$yuurl = $this -> createurl($_GET);
			$this -> assign('yuurl',$yuurl);
			
			$id = $this->_post('id');
			$type = $this->_post('type');//发货还是兑换
			$keystr = $this->_post('keystr');
			$passwd = $this->_post('passwd');
			$shiping_name = $this->_post('shiping_name');
			$shipingorder = $this->_post('shipingorder');
			
			$update_submit = $this->_post('update_submit');
			
			if($update_submit!=''){
				
				if($id==''){
					echo "<script>alert('非法操作');history.go(-1);</script>";
					$this -> error('非法操作');
				}
				
				
				if($type=='1'){
					
					if($keystr=='' && $passwd==''){
							
						echo "<script>alert('两者都不能为空！');history.go(-1);</script>";
						$this -> error('两者都不能为空！');
					}
					
					$data['keystr'] = $keystr;
					$data['passwd']  = $passwd;
					
					$tioazhuanurl = 'dealorder';
					
				}else if($type=='2'){
					
					$data['shiping_name']  = $shiping_name;
					$data['shipingorder']  = $shipingorder;
					
					$tioazhuanurl = 'shiwuorder';
				}
				
				$data['status'] = '4';
				$data['fh_fahuotime'] = date('Y-m-d h:i:s');
				
				$Model = new Model();
				
				$imagedata_sql = $Model->table('shop_userbuy')->where ("id='".$id."'")->save($data);
				
				
				if($imagedata_sql){
					echo "<script>alert('操作成功！');window.location.href='".__APP__."/Orderlist/".$tioazhuanurl.$yuurl."';</script>";
					$this ->success('操作成功!','__APP__/Orderlist/'.$tioazhuanurl.$yuurl);
				}else{
					echo "<script>alert('操作失败！'); history.go(-1);</script>";
					$this->error('操作失败！');
				}
			
				
			}
			
		}
		
		//实物订单查询************************************************************************************************************************
		public function shiwuorder(){
		
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//判断用户是否登陆
			$this->loginjudgeshow($this->lock_shiwuorder);
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
			$zstatus     = $this->_get('zstatus');
			$bianhao     = $this->_get('bianhao');
			$siteid     = $this->_get('siteid');
		
		
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
			
			//商家编号
			$sitearr = array();
			$sql_siteid = "select id,lianxiren from shop_site where flag=1 and checkstatus='2' order by id asc";
			$list_siteid = $Model->query($sql_siteid);
				
			$optionsiteid = '<option value=""></option>';
			foreach($list_siteid as $val) {
				$sitearr[$val['id']] = $val['id'].'-'.$val['lianxiren'];
				$optionsiteid .= '<option value="'.$val['id'].'"';
				if($val['id']==$siteid) {
					$optionsiteid .= ' selected="selected" ';
				}
				$optionsiteid .= '>'.$val['id'].'--'.$val['lianxiren'].'</option>';
			}
			$this -> assign('optionsiteid',$optionsiteid);
		
		
			$status_arr = array(
					'3' => '待发货',
					'4' => '已发货',
					'5' => '待确认',
					'6' => '待评价',
					'7' => '已评价',
			);
			$optionstatus = '<option value=""></option>';
			foreach($status_arr as $keyc => $valc) {
				$optionstatus .= '<option value="'.$keyc.'" ';
				if($zstatus==$keyc) { $optionstatus .= ' selected="selected" '; }
				$optionstatus .= '>'.$valc.'</option>';
			}
		
			$this->assign('optionstatus',$optionstatus);
				
			$duihuan_arr = array(
					'1' => '未发货',
					'2' => '已发货',
			);
			$optionduihuan = '<option value=""></option>';
			foreach($duihuan_arr as $keyc => $valc) {
				$optionduihuan .= '<option value="'.$keyc.'" ';
				if($duihuan==$keyc) { $optionduihuan .= ' selected="selected" '; }
				$optionduihuan .= '>'.$valc.'</option>';
			}
		
			$this->assign('optionduihuan',$optionduihuan);
		
		
			$this->assign('date_s',$date_s);
			$this->assign('date_e',$date_e);
			$this->assign('phone',$phone);
			$this->assign('orderno',$orderno);
			$this->assign('name',$name);
			$this->assign('siteid',$siteid);
			$this->assign('bianhao',$bianhao);
			//-----------------------------------------------------------
			//生成where条件判断字符串
			$sql_where = " mtype='2' and " ;
		
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
			
			if($zstatus!='') {
				$sql_where .= "status='".$zstatus."' and ";
			}
		
			if($bianhao!='') {
				$sql_where .= "shop_userbuy.id='".$bianhao."' and ";
			}
				
			if($siteid!='') {
				$sql_where .= "siteid='".$siteid."' and ";
			}	
				
			$sql_where = rtrim($sql_where,'and ');
		
			//-----------------------------------------------------------
		
			//id与手机号关联的数组
			$phonearr = array();
			$duihuancodearr = array();
			$overtimearr= array();
			$ordertimearr = array();
		
		
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
				//number_format(8.3486,2);
				$list[$keys]['price']  = number_format($list[$keys]['price']/100,2);
		
				if($list[$keys]['status']=='1'){
					$list[$keys]['statuss']='已使用';
				}else if($list[$keys]['status']=='2'){
					$list[$keys]['statuss']='处理中';
				}else if($list[$keys]['status']=='3'){
					$list[$keys]['statuss']='待发货';
				}else if($list[$keys]['status']=='4'){
					$list[$keys]['statuss']='已发货';
				}else if($list[$keys]['status']=='9'){
					$list[$keys]['statuss']='未使用';
				}
		
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


	
