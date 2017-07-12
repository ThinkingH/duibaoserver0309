<?php
/*
 * 订单数据的查询
 */

class OrderlistAction extends Action {
	
	//定义各模块锁定级别
	private $lock_index              = '9751';
	private $lock_pingjia            = '975';
	
	
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
			$miyao       = $this->_get('miyao');
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
					$list[$keys]['status']='已使用';
				}else if($list[$keys]['status']=='2'){
					$list[$keys]['status']='处理中';
				}else if($list[$keys]['status']=='3'){
					$list[$keys]['status']='待领取';
				}else if($list[$keys]['status']=='4'){
					$list[$keys]['status']='已领取';
				}else if($list[$keys]['status']=='9'){
					$list[$keys]['status']='未使用';
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


	