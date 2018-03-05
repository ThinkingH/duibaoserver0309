<?php
/*
 * 兑换码的信息管理
 */

class DuihuanmaAction extends Action{
	
	
	//定义各模块锁定级别
	private $lock_index              = '9751';
	private $lock_addduihuanma       = '975';
	private $lock_addduihuanmadata   = '975';
	private $lock_deletedhdata       = '97';
	
	//兑换码的展示页面
	public function index(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//换取相应的参数
		$flag_s = $this->_get('flag_s');//兑换码的使用状态
		$type_s = $this->_get('type_s');//兑换码的类型
		
		$Model = new Model();
		
		//是否启用
		$flag_arr = array(
				'1' => '已使用',
				'9' => '未使用',
		);
		$optionflag = '<option value=""></option>';
		foreach($flag_arr as $keyc => $valc) {
			$optionflag .= '<option value="'.$keyc.'" ';
			if($flag_s==$keyc) { $optionflag .= ' selected="selected" '; }
			$optionflag .= '>'.$valc.'</option>';
		}
		$this->assign('optionflag',$optionflag);
		
		//渠道编号
		$siteidarr = array();
		$sitesql = "select id,name,flag from shop_site where flag='1'";
		$sitelist = $Model->query($sitesql);
		
		foreach ($sitelist as $keys=>$vals){
			$siteidarr[$sitelist[$keys]['id']] = $sitelist[$keys]['name'];
			
		}
		
		//主商品
		$typesarr = array();
		$sql_type = "select typeid,name,flag from shop_config where flag=1 order by typeid asc";
		$list_type = $Model->query($sql_type);
		
		foreach ($list_type as $keys=>$vals){
			$typesarr[$list_type[$keys]['typeid']] = $list_type[$keys]['name'];
			
		}
		
		//商品编号--子类型
		$typearr = array();
		$sql_childtype = "select childtype,xushi,name from shop_type order by id asc";
		$list_childtype = $Model->query($sql_childtype);
		
		foreach ($list_childtype as $keys=>$vals){
			$typearr[$list_childtype[$keys]['childtype']] = $list_childtype[$keys]['name'];
			
		}
		
		$optionchildtype = '<option value=""></option>';
		foreach($list_childtype as $val) {
		
			$optionchildtype .= '<option value="'.$val['childtype'].'"';
			if($type_s==$val['childtype']) {
			 $optionchildtype .= ' selected="selected" ';
			 }
			$optionchildtype .= '>'.$val['childtype'].'--'.$val['name'].'</option>';
		}
		$this -> assign('optionchildtype',$optionchildtype);
		//-----------------------------------------------------------
		//生成where条件判断字符串
		$sql_where = '';
		
		if($flag_s!='') {
			$sql_where .= " flag='".$flag_s."' and ";
		}
		if($type_s!='') {
			$sql_where .= " type='".$type_s."' and ";
		}
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
		
		
		//生成排序字符串数据
		$sql_order = " id asc ";
		
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('xb_duihuanma')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('xb_duihuanma')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		//---------------------------------------------------------------
		
		
		
		foreach($list as $keyc => $valc) {
		
			if($list[$keyc]['flag']=='1') {
				$list[$keyc]['flag'] = '<font style="background-color:#00EA00">&nbsp;&nbsp;已使用&nbsp;&nbsp;</font>';
			}else if($list[$keyc]['flag']=='9') {
				$list[$keyc]['flag'] = '<font style="background-color:#FF1700">&nbsp;&nbsp;未使用&nbsp;&nbsp;</font>';
			}else {
				$list[$keyc]['flag'] = 'ERR';
			}
				
			$list[$keyc]['type'] = isset($typearr[$list[$keyc]['type']])?$typearr[$list[$keyc]['type']]:$list[$keyc]['type'];
			$list[$keyc]['maintype'] = isset($typesarr[$list[$keyc]['maintype']])?$typesarr[$list[$keyc]['maintype']]:$list[$keyc]['maintype'];
			$list[$keyc]['siteid'] = isset($siteidarr[$list[$keyc]['siteid']])?$siteidarr[$list[$keyc]['siteid']]:$list[$keyc]['siteid'];
		
				
		}		
		
		$this -> assign('list',$list);
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	
	//兑换码的添加
	public function addduihuanma(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addduihuanma);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//是否启用
		$flag_arr = array(
				'9' => '未使用',
				'1' => '已使用',
		);
		/* $optionflag = '<option value=""></option>'; */
		foreach($flag_arr as $keyc => $valc) {
			$optionflag .= '<option value="'.$keyc.'" ';
			$optionflag .= '>'.$valc.'</option>';
		}
		$this->assign('optionflag',$optionflag);
		
		$Model = new Model();
		
		//商品编号--主类型
		$typearr = array();
		$sql_type = "select typeid,name,flag from shop_config where flag=1 order by typeid asc";
		$list_type = $Model->query($sql_type);
		
		$optiontype = '<option value=""></option>';
		foreach($list_type as $val) {
			$optiontype .= '<option value="'.$val['typeid'].'"';
			/* if($val['typeid']==$type) {
			 $optiontype .= ' selected="selected" ';
			 } */
			$optiontype .= '>'.$val['typeid'].'--'.$val['name'].'</option>';
		}
		$this -> assign('optiontype',$optiontype);
		
		
		//商品编号--子类型
		$typearr = array();
		$sql_childtype = "select childtype,xushi,name from shop_type order by id asc";
		$list_childtype = $Model->query($sql_childtype);
		
		$optionchildtype = '<option value=""></option>';
		foreach($list_childtype as $val) {
			$optionchildtype .= '<option value="'.$val['childtype'].'"';
			/* if($val['typeid']==$type) {
			 $optiontype .= ' selected="selected" ';
			 } */
			$optionchildtype .= '>'.$val['childtype'].'--'.$val['name'].'</option>';
		}
		$this -> assign('optionchildtype',$optionchildtype);
		
		//渠道编号
		$sitesql = "select id,lianxiren,flag from shop_site where flag='1' and checkstatus='2'";
		$sitelist = $Model->query($sitesql);
		
		$optionsiteid = '<option value=""></option>';
		foreach($sitelist as $keyc => $valc) {
			$optionsiteid .= '<option value="'.$valc['id'].'" ';
			/* if($sitelist[0]['flag']==$keyc) { $optionsiteid .= ' selected="selected" '; } */
			$optionsiteid .= '>'.$valc['id'].'--'.$valc['lianxiren'].'</option>';
		}
		$this -> assign('optionsiteid',$optionsiteid);
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	//兑换码的添加
	public function addduihuanmadata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addduihuanmadata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//获取相应测参数
		$flag       = $this->_post('flag');
		$type       = $this->_post('type');
		$maintype   = $this->_post('maintype');
		$goods_name = $this->_post('goods_name');
		$duihuanma  = $_POST['duihuanma'];
		$siteid     = $this->_post('siteid');
		
		if($type==''){
			echo "<script>alert('兑换码类型不能为空！');history.go(-1);</script>";
			$this -> error('兑换码类型不能为空！');
		}
		
		if($maintype==''){
			echo "<script>alert('兑换码主类型不能为空！');history.go(-1);</script>";
			$this -> error('兑换码主类型不能为空！');
		}
		
		if($goods_name==''){
			echo "<script>alert('商品名称不能为空！');history.go(-1);</script>";
			$this -> error('商品名称不能为空！');
		}
		
		if($duihuanma==''){
			
			echo "<script>alert('兑换码不能为空！');history.go(-1);</script>";
			$this -> error('兑换码不能为空！');
		}
		
		
		$uniquedh = array();
		//兑换码字符串的分割
		$duihuanma_arr = explode("\n",$duihuanma);
		
		foreach($duihuanma_arr as $vald) {
			$vald = trim($vald);
			if($vald!='') {
				$uniquedh[$vald] = $vald;
			}
		}
		
		//重复兑换码个数
		$repeatcode = 0;
		$norepeatcode=0;
		
		//数据库的初始化
		$Model = new Model();
		
		foreach ($uniquedh as $vals){
			$dm = trim($vals);
			$date=date('Y-m-d H:i:s');
			
			$sel = "select id from xb_duihuanma where duihuanma='".$dm."' and maintype='".$maintype."' and type='".$type."'";
			$sel_list = $Model->query($sel);
			
			if(count($sel_list)>0){
				
				$repeatcode++;
				
			}else{
				$insert_sql = "insert into xb_duihuanma( siteid,goods_name,maintype, type, flag,duihuanma,createtime)
					values('".$siteid."',".$goods_name."','".$maintype."','".$type."','".$flag."','".$dm."','".$date."');";
				$Model->execute($insert_sql);
				$norepeatcode++;
			}
			
		}
		
		
		//兑换码的总个数
		$num=$repeatcode+$norepeatcode;
		
		if($num>0) {
			echo "<script>alert('数据添加成功,验证码总数".$num."\n重复验证码有".$repeatcode."\n非重复的验证码正常插入的验证码有".$norepeatcode."');window.location.href='".__APP__."/Duihuanma/index".$yuurl."';</script>";
			$this ->success('数据添加成功,验证码总数'.$num.',重复验证码有'.$repeatcode.',非重复的验证码正常插入的验证码有'.$norepeatcode,'__APP__/Duihuanma/index'.$yuurl);
				
		}else {
			echo "<script>alert('数据添加失败，系统错误!');history.go(-1);</script>";
			$this -> error('数据添加失败，系统错误!');
		}
		
	}
	
	
	//兑换码的删除
	public function deletedhdata(){
		
	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_deletedhdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//获取参数
		$id = $this->_post('id');
		$delete_submit = $this->_post('delete_submit');
		
		if($delete_submit!=''){
			
			if($id==''){
				
				echo "<script>alert('非法操作！'); history.go(-1);</script>";
				$this->error('非法操作！');
			}
			
			//数据库初始化
			$Model = new Model();
			
			//说明此数据没有关联数据，可以删除
			$ret = $Model -> table('xb_duihuanma') -> where("id='".$id."'") -> delete();
			
			if($ret) {
				echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Duihuanma/index".$yuurl."';</script>";
				$this -> success('数据删除成功!','__APP__/Duihuanma/index'.$yuurl);
			}else {
				echo "<script>alert('数据删除失败，系统错误!');history.go(-1);</script>";
				$this -> error('数据删除失败，系统错误!');
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