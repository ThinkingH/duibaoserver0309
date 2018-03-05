<?php
/*
 * 优惠券的管理
 */
class YouhuiquanAction extends Action{
	
	//定义各模块锁定级别
	private $lock_index                 = '9751';
	private $lock_updateyhqshow         = '975';
	private $lock_updateyhqshowdata     = '975';
	private $lock_deleteyhqdata         = '97';
	
	private $lock_addquanshow         = '975';
	private $lock_addquantypedata     = '975';
	private $lock_updateflagdata      = '975';
	
	//优惠券的首页
	public function index(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//换取相应的参数
		$flag    = $this->_get('flag');   //优惠券的到期设置
		$type    = $this->_get('type');     //优惠券的类型
		$youxiao = $this->_get('youxiao');  //是否有效
		$tuijian = $this->_get('tuijian');  //是否推荐
		
		
		//是否启用
		$flag_arr = array(
				'1' => '已上架',
				'9' => '未上架',
				'2' => '已作废',
		);
		$optionflag = '<option value=""></option>';
		foreach($flag_arr as $keyc => $valc) {
			$optionflag .= '<option value="'.$keyc.'" ';
			if($flag==$keyc) { $optionflag .= ' selected="selected" '; }
			$optionflag .= '>'.$valc.'</option>';
		}
		$this->assign('optionflag',$optionflag);
		
		//是否推荐
		$tuijian_arr = array(
				'1' => '推荐',
				'9' => '不推荐',
		);
		$optiontuijian = '<option value=""></option>';
		foreach($tuijian_arr as $keyc => $valc) {
			$optiontuijian .= '<option value="'.$keyc.'" ';
			if($tuijian==$keyc) { $optiontuijian .= ' selected="selected" '; }
			$optiontuijian .= '>'.$valc.'</option>';
		}
		$this->assign('optiontuijian',$optiontuijian);
		
		//优惠券的类型
		$youxiao_arr = array(
				'ok' => '未过期',
				'no' => '已过期',
		);
		$optionok = '<option value=""></option>';
		foreach($youxiao_arr as $keyc => $valc) {
			$optionok .= '<option value="'.$keyc.'" ';
			if($youxiao==$keyc) { $optionok .= ' selected="selected" '; }
			$optionok .= '>'.$valc.'</option>';
		}
		$this->assign('optionok',$optionok);
		$this->assign('type',$type);
		
		//数据库的初始化
		$Model = new Model();
		
		$sql_order = "id desc";
		
		//生成where条件判断字符串
		$sql_where = '';
		
		if($flag!='') {
			$sql_where .= " flag='".$flag."' and ";
		}
		if($type!='') {
			//$sql_where .= " type='".$type."' and ";
			$sql_where .= " type like '%".$type."%' and ";
		}
		
		if($youxiao!=''){
			$sql_where .= " youxiao='".$youxiao."' and ";
		}
		
		if($tuijian!=''){
			$sql_where .= " tuijian='".$tuijian."' and ";
		}
		
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
		$Model = new Model();
		
		//生成排序字符串数据
		$sql_order = " id desc ";
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('youhuiquan')
						-> where($sql_where)
						-> order($sql_order)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('youhuiquan')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		
		foreach($list as $keyc => $valc) {
		
			if($list[$keyc]['flag']=='1') {
				$list[$keyc]['flag'] = '<font style="background-color:#00EA00;padding:2px;">已上架</font>';
			}else if($list[$keyc]['flag']=='9') {
				$list[$keyc]['flag'] = '<font style="background-color:#FF1700;padding:2px;">未上架</font>';
			}else if($list[$keyc]['flag']=='2'){
				$list[$keyc]['flag'] = '<font style="background-color:#F5F971;padding:2px">已作废</font>';
			}
			
			
			if($list[$keyc]['youxiao']=='ok'){
				$list[$keyc]['youxiao'] = '<font style="background-color:#00EA00;padding:2px">未过期</font>';
			}else if($list[$keyc]['youxiao']=='no'){
				$list[$keyc]['youxiao'] = '<font style="background-color:#FF1700;padding:2px">已过期</font>';
			}
			
			$list[$keyc]['content'] = nl2br($list[$keyc]['content']);
				
			
			if($list[$keyc]['tuijian']=='1'){
				$list[$keyc]['tuijian'] = '<font style="background-color:#00EA00;padding:2px">推荐</font>';
			}else if($list[$keyc]['tuijian']=='9'){
				$list[$keyc]['tuijian'] = '<font style="background-color:#FF1700;padding:2px">不推荐</font>';
			}else {
				$list[$keyc]['tuijian'] = 'ERR';
			}
				
		}		
		
		$this -> assign('list',$list);
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	}
	
	
	//优惠券的修改
	public function updateyhqshow(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updateyhqshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
	
		//接收用户选择的查询参数
		$id            = $this->_post('id');
		$update_submit = $this->_post('update_submit');
	
	
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
				
			//优惠券的查询
			$tasklist_sql  = "select * from youhuiquan where id='".$id."'";
			$list          = $Model->query($tasklist_sql);
				
				
			if(count($list)<=0) {
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this->error('非法操作！');
			}else {
				
				$list[0]['content'] = $list[0]['content'];
				
				$optionflag = '';
				$optionflag .= '<option value="1" ';
				if($list[0]['flag']==1) { $optionflag .= ' selected="selected" '; }
				$optionflag .= '>已上架</option>';
				$optionflag .= '<option value="9" ';
				if($list[0]['flag']==9) { $optionflag .= ' selected="selected" '; }
				$optionflag .= '>未上架</option>';
				$optionflag .= '<option value="2" ';
				if($list[0]['flag']==2) { $optionflag .= ' selected="selected" '; }
				$optionflag .= '>已作废</option>';
				
				$optiontuijian = '';
				$optiontuijian .= '<option value="1" ';
				if($list[0]['tuijian']==1) { $optiontuijian .= ' selected="selected" '; }
				$optiontuijian .= '>推荐</option>';
				$optiontuijian .= '<option value="9" ';
				if($list[0]['tuijian']==9) { $optiontuijian .= ' selected="selected" '; }
				$optiontuijian .= '>未推荐</option>';
	
				$optiontype = '';
				$optiontype .= '<option value="kfc" ';
				if($list[0]['type']=='kfc') { $optiontype .= ' selected="selected" '; }
				$optiontype .= '>kfc</option>';
				$optiontype .= '<option value="mdl" ';
				if($list[0]['type']=='mdl') { $optiontype .= ' selected="selected" '; }
				$optiontype .= '>mdl</option>';
				
				$optionok = '';
				$optionok .= '<option value="ok" ';
				if($list[0]['youxiao']=='ok') { $optionok .= ' selected="selected" '; }
				$optionok .= '>未过期</option>';
				$optionok .= '<option value="no" ';
				if($list[0]['youxiao']=='no') { $optionok .= ' selected="selected" '; }
				$optionok .= '>已过期</option>';
	
				$this-> assign('optionflag',$optionflag);
				$this-> assign('optiontype',$optiontype);
				$this-> assign('optiontok',$optionok);
				$this-> assign('optiontuijian',$optiontuijian);
				$this -> assign('list',$list[0]);
				
				
	
			}
				
			// 输出模板
			$this->display();
				
			printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
				
				
		}
	
	}
	
	
	//优惠信息的修改
	public function updateyhqshowdata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updateyhqshowdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);

	
		//接收用户选择的查询参数
		$id            = $this->_post('id');
		$update_submit = $this->_post('update_submit');
		$flag                 = $this->_post('flag');  //游戏的开关
		$type                 = $this->_post('type');
		$tuijian              = $this->_post('tuijian');
		$jiage                = $this->_post('jiage');
		$youxiao              = trim($this->_post('youxiao'));
		$youxiaoqi            = trim($_POST['youxiaoqi']);
		$title                = trim($this->_post('title'));
		
		$content              = trim($_POST['content']);
		
		$Model = new Model(); // 实例化User对象
		
		$data=array();
		$data['flag']     = $flag;
		$data['type']     = $type;
		$data['youxiao']  = $youxiao;
		$data['youxiaoqi']  = $youxiaoqi;
		$data['jiage']      = $jiage;
		$data['title']      = $title;
		$data['tuijian']    = $tuijian;
		$data['content']  = $content;
		
		$imagedata_sql = $Model->table('youhuiquan')->where("id='".$id."'")->save($data);
		
		if($imagedata_sql){
		
			echo "<script>alert('数据修改成功！'); window.location.href='".__APP__."/Youhuiquan/index".$yuurl."';</script>";
			$this->error('数据修改成功！');
		}else{
			echo "<script>alert('数据修改失败！'); history.go(-1);</script>";
			$this->error('数据修改失败！');
		}
	}
	
	
	
	//优惠券的删除
	public function deleteyhqdata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_deleteyhqdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
			
		$id             = $this->_post('id');
		$delete_submit  = $this->_post('delete_submit');
			
		if($delete_submit!=''){
				
			if($id=='') {
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this -> error('非法操作！');
			}
				
			//数据库初始化
			$Model = new Model();
				
			//说明此数据没有关联数据，可以删除
			$ret = $Model -> table('youhuiquan') -> where("id='".$id."'") -> delete();
		
		
			if($ret) {
				echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Youhuiquan/index".$yuurl."';</script>";
				$this -> success('数据删除成功!','__APP__/Youhuiquan/index'.$yuurl);
			}else {
				echo "<script>alert('数据删除失败，系统错误!');history.go(-1);</script>";
				$this -> error('数据删除失败，系统错误!');
			}
				
		}
	}
	
	
	//快速更新flag
	public function updateflagdata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updateflagdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
			
		$id             = $this->_post('id');
		$flag           = $this->_post('flag');
		$delete_submit  = $this->_post('delete_submit');
			
		if($delete_submit!=''){
		
			if($id=='') {
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this -> error('非法操作！');
			}
		
			//数据库初始化
			$Model = new Model();
		
			//说明此数据没有关联数据，可以删除
			//$ret = $Model -> table('youhuiquan') -> where("id='".$id."'") -> delete();
			$updatesql  = "update youhuiquan set flag='".$flag."' where id='".$id."' ";
			$ret = $Model ->execute($updatesql);
		
		
			if($ret) {
				echo "<script>alert('操作成功！');window.location.href='".__APP__."/Youhuiquan/index".$yuurl."';</script>";
				$this -> success('操作成功!','__APP__/Youhuiquan/index'.$yuurl);
			}else {
				echo "<script>alert('操作失败，系统错误!');history.go(-1);</script>";
				$this -> error('操作失败，系统错误!');
			}
		
		}
	}
	
	
	//优惠券的添加
	public function addquanshow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addquanshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//优惠券的类型
		$youxiao_arr = array(
				'ok' => '未过期',
				'no' => '已过期',
		);
		foreach($youxiao_arr as $keyc => $valc) {
			$optionok .= '<option value="'.$keyc.'" ';
			if($verdata_list[0]['youxiao']==$keyc) { $optionok .= ' selected="selected" '; }
			$optionok .= '>'.$valc.'</option>';
		}
		$this->assign('optionok',$optionok);
		
		
		// 输出模板
		$this->display();
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	public function addquantypedata(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addquantypedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//获取相应的参数
		$flag         = $this->_post('flag');            //关闭和开启
		$type         = $this->_post('type');           //商品类型
		$youxiao      = trim($this->_post('youxiao'));   //有效期
		$youxiaoqi     = trim($this->_post('youxiaoqi'));   //有效期
		$content       = $this->_post('content');           //描述
		$title          = $this->_post('title');            //商品标题
		$theurl   = trim($this->_post('theurl'));    //跳转链接
		$imgurl   = trim($this->_post('imgurl'));    //跳转链接
		$jiage    = $this->_post('jiage');    //跳转链接
		
		//数据库的初始化nl2br
		
		$Model = new Model();
		
		$data = array();
		
		$data['flag']       = $flag;
		$data['type']       = $type;
		$data['tuijian']    = '9';
		$data['jiage']      = $jiage;
		$data['youxiao']    = $youxiao;
		$data['youxiaoqi']  = $youxiaoqi;
		$data['content']    = $content;
		$data['imgurl']     = $imgurl;
		$data['title']      = $title;
		$data['theurl']     = $theurl;
		$data['zhuaqutimes'] = date('Y-m-d h:i:s');
		
		
		$imagedata_sql = $Model->table('youhuiquan')->add($data);
		
		//echo $Model->getLastsql();exit;
		
		if($imagedata_sql){//说明文件上传成功
				
			echo "<script>alert('数据添加成功！');window.location.href='".__APP__."/Youhuiquan/index".$yuurl."';</script>";
			$this ->success('数据添加成功!','__APP__/Youhuiquan/index'.$yuurl);
		}else{
			echo "<script>alert('数据添加失败！'); history.go(-1);</script>";
			$this ->success('数据添加失败!');
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