<?php
/*
 * 订单管理
 */

class OrderAction extends Action {
	
	
	//订单列表
	public function index(){
		
		//获取相应的参数
		$date_s      = $this->_get('date_s');
		$date_e      = $this->_get('date_e');
		$good_name    = $this->_get('good_name');
		$orderno      = $this->_get('orderno');
		$phone        = $this->_get('phone');
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
				'3' => '待领取',
				'4' => '已领取',
				'5' => '待确认',
				'6' => '待评价',
				'7' => '已评价',
		);
		$optionstatus = '<option value="">订单状态</option>';
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
		$this->assign('name',$good_name);
		//-----------------------------------------------------------
		
		//商户编号
		$siteid   = session(HYSESSQZ.'siteid');
		
		//生成where条件判断字符串
		$sql_where = "siteid= '".$siteid."'  and mtype=1 and  ";
			
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
			
		if($good_name!='') {
			$sql_where .= "name like '%".$good_name."%' and  ";
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
			//echo $Model->getLastsql();
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
			
		foreach ($list as $keys=>$vals){
		
			$list[$keys]['siteid'] = isset($sitearr[$list[$keys]['siteid']])?$sitearr[$list[$keys]['siteid']]:$list[$keys]['siteid'];
			$list[$keys]['typeid'] = isset($typearr[$list[$keys]['typeid']])?$typearr[$list[$keys]['typeid']]:$list[$keys]['typeid'];
		
			if($list[$keys]['status']=='1'){
				$list[$keys]['sstatus']='已使用';
			}else if($list[$keys]['status']=='2'){
				$list[$keys]['sstatus']='处理中';
			}else if($list[$keys]['status']=='3'){
				$list[$keys]['sstatus']='待领取';
			}else if($list[$keys]['status']=='4'){
				$list[$keys]['sstatus']='已领取';
			}else if($list[$keys]['status']=='9'){
				$list[$keys]['sstatus']='未使用';
			}else if($list[$keys]['status']=='5'){
				$list[$keys]['sstatus']='待确认';
			}else if($list[$keys]['status']=='6'){
				$list[$keys]['sstatus']='待评价';
			}else if($list[$keys]['status']=='7'){
				$list[$keys]['sstatus']='已评价';
			}
		
		}
		//print_r($list);	
		$this -> assign('list',$list);
			
		// 输出模板
		$this->display();
		
	}
	
	
	//订单核销--只针对实物-兑换码和订单号进行核销
	public function hexiao(){
		
		//获取相应的参数
		//$checkstatus = $this->_get('checkstatus');
		$date_s      = $this->_get('date_s');
		$date_e      = $this->_get('date_e');
		$good_name    = $this->_get('good_name');
		$orderno      = $this->_get('orderno');
		$phone        = $this->_get('phone');
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
				'4' => '已发货',
				'5' => '待确认',
				'6' => '待评价',
				'7' => '已评价',
		);
		$optionstatus = '<option value="">订单状态</option>';
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
		$this->assign('name',$good_name);
		//-----------------------------------------------------------
		
		//商户编号
		$siteid   = session(HYSESSQZ.'siteid');
		
		//生成where条件判断字符串
		$sql_where = "siteid= '".$siteid."' and mtype='2' and ";
			
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
			
		if($good_name!='') {
			$sql_where .= "name like '%".$good_name."%' and  ";
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
			
			$list[$keys]['price']  = number_format($list[$keys]['price']/100,2);
			
			$list[$keys]['sstatus'] = $list[$keys]['status'];
			
			if($list[$keys]['status']=='1'){
				$list[$keys]['sstatus']='已使用';
			}else if($list[$keys]['status']=='2'){
				$list[$keys]['sstatus']='处理中';
			}else if($list[$keys]['status']=='3'){
				$list[$keys]['sstatus']='待发货';
			}else if($list[$keys]['status']=='4'){
				$list[$keys]['sstatus']='已发货';
			}else if($list[$keys]['status']=='9'){
				$list[$keys]['sstatus']='未使用';
			}else if($list[$keys]['status']=='5'){
				$list[$keys]['sstatus']='待确认';
			}else if($list[$keys]['status']=='6'){
				$list[$keys]['sstatus']='待评价';
			}else if($list[$keys]['status']=='7'){
				$list[$keys]['sstatus']='已评价';
			}
		
		}
			
		$this -> assign('list',$list);
			
		// 输出模板
		$this->display();
		
	}
	
	
	//订单核销页面
	public function hexiaoshow(){
		
		
		$Model = new Model();
		
		$id = $this->_post('id');
		
		$sqldata = "select * from shop_userbuy where id='".$id."' ";
		$sqllist = $Model->query($sqldata);
		
		$this->assign('list',$sqllist[0]);
		
		
		$this->display();
		
	}
	//订单核销页面
	public function hexiaoedit(){
		
		
		$Model = new Model();
		
		$id = $this->_post('id');
		
		$sqldata = "select * from shop_userbuy where id='".$id."' ";
		$sqllist = $Model->query($sqldata);
		
		$this->assign('list',$sqllist[0]);
		
		
		$this->display();
		
	}
	
	
	//订单核销操作
	public function hexiaodata(){
		
		$id        = $this->_post('id');
		$duihuanma = $this->_post('keystr'); 
		$passwd = $this->_post('passwd'); 
		$remark    = $this->_post('remark'); 
		$shipingorder    = $this->_post('shipingorder'); 
		$shiping_name    = $this->_post('shiping_name'); 
		$type = $this->_post('type'); 
		$uupdate_submit    = $this->_post('uupdate_submit'); 
		
		//商户编号
		$siteid   = session(HYSESSQZ.'siteid');

		if($uupdate_submit!=''){
			
			$Model = new Model();
			
			if($type=='1'){//虚拟订单
				
				if($duihuanma==''){
					echo "<script>alert('兑换码不能为空！'); history.go(-1);</script>";
					$this->error('兑换码不能为空！');
				}
				
				$datasql  = "select id from shop_userbuy  where id='".$id."' and keystr='".$duihuanma."' and status='4' and siteid='".$siteid."' ";
				$datalist = $Model->query($datasql);
				
				$datasql  = "select id from shop_userbuy  where id='".$id."' and keystr='".$duihuanma."' and status='4' and siteid='".$siteid."' ";
				$datalist = $Model->query($datasql);
					
				if(count($datalist)>0){
					echo "<script>alert('该兑换码已核销，不可以重复核销！'); history.go(-1);</script>";
					$this->error('该兑换码已核销，不可以重复核销！');
				}else{
					$updatesql = "update shop_userbuy set status='4',keystr= '".$duihuanma."',passwd='".$passwd."',fh_fahuotime='".date('Y-m-d H:i:s')."',remark='".$remark."' where  siteid='".$siteid."' and id='".$id."'  ";
					$updatelist = $Model->execute($updatesql);
				
					if($updatelist) {
						echo "<script>alert('操作成功！');window.location.href='".__APP__."/Order/index".$yuurl."';</script>";
						$this -> success('操作成功！','__APP__/Order/index'.$yuurl);
					}else {
						echo "<script>alert('操作失败！');history.go(-1);</script>";
						$this -> error('操作失败！');
					}
				}
				
			}else if($type=='2'){//实物
				
				$data=array();
				$data['shiping_name']  = $shiping_name;
				$data['shipingorder']  = $shipingorder;
				$data['status'] = '4';
				$data['fh_fahuotime'] = date('Y-m-d h:i:s');
				
				$imagedata_sql = $Model->table('shop_userbuy')->where ("id='".$id."'")->save($data);
				
				if($imagedata_sql){
					echo "<script>alert('操作成功！');window.location.href='".__APP__."/Order/hexiao".$yuurl."';</script>";
					$this ->success('操作成功!','__APP__/Order/hexiao'.$yuurl);
				}else{
					echo "<script>alert('操作失败！'); history.go(-1);</script>";
					$this->error('操作失败！');
				}
			}
		}
	}
	
	//实物商品兑换修改
	public function shiwuupdateshow(){
	
	
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
	
		
	//订单删除
	public function deletedata(){
	
	
		$id = $this->_post('id');
	
		$Model = new Model();
	
	
		//说明此数据没有关联数据，可以删除
		$ret = $Model -> table('shop_userbuy') -> where("id='".$id."'") -> delete();
	
		if($ret) {
			echo "<script>alert('订单删除成功！');window.location.href='".__APP__."/Order/index".$yuurl."';</script>";
			$this -> success('订单删除成功!','__APP__/Order/index'.$yuurl);
		}else {
			echo "<script>alert('订单删除失败，系统错误!');history.go(-1);</script>";
			$this -> error('订单删除失败，系统错误!');
		}
	}
	
	
	//ajax 数据核销
	public function ajax_hexiao(){
		
		$duihuanma = $this->_get('duihuanma');
		
		
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
	
	
	
	
	
	
	
	
	
	
}