<?php
/*
 * 首页10个logo
 */

class FirsttypeAction extends Action{
	
	//定义各模块锁定级别
	private $lock_index                = '9751';
	private $lock_addfirsttypeshow     = '975';
	private $lock_addfirsttypedata     = '975';
	private $lock_updatefirsttypeshow     = '975';
	private $lock_updatefirsttypedata     = '975';
	private $lock_deletefirsttypedata     = '97';
	private $lock_updateflagdata          = '975';
	public function index(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//换取相应的参数
		$flag    = $this->_get('flag');      //是否上架
		$type    = $this->_get('type');     //优惠券的类型
		$youxiao = $this->_get('youxiao');  //是否有效
		$title   = $this->_get('title');  //商品名称
		
		//是否启用
		$flag_arr = array(
				'1' => '已上架',
				'9' => '已下架',
				'2' => '已作废',
		);
		$optionflag = '<option value=""></option>';
		foreach($flag_arr as $keyc => $valc) {
			$optionflag .= '<option value="'.$keyc.'" ';
			if($flag==$keyc) { $optionflag .= ' selected="selected" '; }
			$optionflag .= '>'.$valc.'</option>';
		}
		$this->assign('optionflag',$optionflag);
		
		
		//商品类型
		$type_arr = array(
				'xiebao'       => '鞋包',
				'yingshi'      => '影视',
				'dache'        => '打车',
				'meishi'       => '美食',
				'fuzhuang'     => '服装',
				'shenghuo'     => '生活',
				'fuwu'         => '服务',
				'dianqi'       => '电器',
				'wenti'        => '文体',
				'meizhuang'    => '美妆',
		);
		$optiontype = '<option value=""></option>';
		foreach($type_arr as $keyc => $valc) {
			$optiontype .= '<option value="'.$keyc.'" ';
			if($type==$keyc) { $optiontype .= ' selected="selected" '; }
			$optiontype .= '>'.$valc.'</option>';
		}
		$this->assign('optiontype',$optiontype);
		
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
		$this->assign('title',$title);
		
		//数据库的初始化
		$Model = new Model();
		
		$sql_order = "id desc";
		
		//生成where条件判断字符串
		$sql_where = '';
		
		if($flag!='') {
			$sql_where .= " flag='".$flag."' and ";
		}
		if($type!='') {
			$sql_where .= " type='".$type."' and ";
			//$sql_where .= " type like '%".$type."%' and ";
		}
		
		if($youxiao!=''){
			$sql_where .= " youxiao='".$youxiao."' and ";
		}
		
		if($title!=''){
			$sql_where .= " title like '%".$title."%' and ";
		}
		
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
		$Model = new Model();
		
		//生成排序字符串数据
		$sql_order = " id desc ";
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('bigstore')
						-> where($sql_where)
						-> order($sql_order)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('bigstore')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		
		foreach($list as $keyc => $valc) {
		
			if($list[$keyc]['flag']=='1') {
				$list[$keyc]['flag'] = '<font style="background-color:#00EA00">&nbsp;&nbsp;已上架&nbsp;&nbsp;</font>';
			}else if($list[$keyc]['flag']=='9') {
				$list[$keyc]['flag'] = '<font style="background-color:#FF1700">&nbsp;&nbsp;未上架&nbsp;&nbsp;</font>';
			}else if($list[$keyc]['flag']=='2'){
				$list[$keyc]['flag'] = '<font style="background-color:#F5F971">&nbsp;&nbsp;已作废&nbsp;&nbsp;</font>';
			}
			
			
			if($list[$keyc]['youxiao']=='ok'){
				$list[$keyc]['youxiao'] = '<font style="background-color:#00EA00">&nbsp;&nbsp;未过期&nbsp;&nbsp;</font>';
			}else if($list[$keyc]['youxiao']=='no'){
				$list[$keyc]['youxiao'] = '<font style="background-color:#FF1700">&nbsp;&nbsp;已过期&nbsp;&nbsp;</font>';
			}
			
			$list[$keyc]['content'] = nl2br($list[$keyc]['content']);
		}		
		
		$this -> assign('list',$list);
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
	
	}
	
	
	
	
	//添加页面
	public function addfirsttypeshow(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addfirsttypeshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//类型
		$type_arr = array(
				'xiebao'       => '鞋包',
				'yingshi'      => '影视',
				'dache'        => '打车',
				'meishi'       => '美食',
				'fuzhuang'     => '服装',
				'shenghuo'     => '生活',
				'fuwu'         => '服务',
				'dianqi'       => '电器',
				'wenti'        => '文体',
				'meizhuang'    => '美妆',
		);
		$optiongateway = '<option value=""></option>';
		foreach($type_arr as $keyc => $valc) {
			$optiongateway .= '<option value="'.$keyc.'" ';
			$optiongateway .= '>'.$valc.'</option>';
		}
		$this->assign('optiongateway',$optiongateway);
		
		
		// 输出模板
		$this->display();
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	//添加页面的实现
	public function addfirsttypedata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addfirsttypedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//获取相应的参数
		$flag         = $this->_post('flag');            //关闭和开启
		$type        = $this->_post('type');           //商品类型
		$childtype   = $this->_post('childtype');    //商品子类型
		$name          = $this->_post('name');            //商品简称
		$youxiaoqi     = trim($this->_post('youxiaoqi'));   //有效期
		$content        = $this->_post('content');           //描述
		$picurl         = trim($this->_post('picurl'));            //图片链接
		$title          = $this->_post('title');            //商品标题
		$scoretimes     = $this->_post('scoretimes');
		$tiaozhuanurl   = trim($this->_post('tiaozhuanurl'));    //跳转链接
		$youxiao   = $this->_post('youxiao');    //有效
		
		$yuanjia   = $this->_post('yuanjia');   
		$zhehoujia   = $this->_post('zhehoujia');
		$quanjia   = $this->_post('quanjia');
		
		//数据库的初始化nl2br
		$Model = new Model();
		
		$data = array();
		
		$data['flag']       = $flag;
		$data['type']       = $type;
		$data['childtype']  = $childtype;
		$data['name']       = $name;
		$data['youxiaoqi']  = $youxiaoqi;
		$data['youxiao']  = $youxiao;
		$data['content']    = $content;
		$data['picurl']     = $picurl;
		$data['title']     = $quanjia;
		
		$data['yuanjia']     = $yuanjia;
		$data['zhehoujia']     = $zhehoujia;
		$data['quanjia']     = $title;
		
		$data['scoretimes']     = $scoretimes;
		$data['tiaozhuanurl']     = $tiaozhuanurl;
		$data['create_datetime'] = date('Y-m-d h:i:s');
		
		
		$imagedata_sql = $Model->table('bigstore')->add($data);
		
		
		if($imagedata_sql){//说明文件上传成功
			
			echo "<script>alert('数据添加成功！');window.location.href='".__APP__."/Firsttype/index".$yuurl."';</script>";
			$this ->success('数据添加成功!','__APP__/Firsttype/index'.$yuurl);
		}else{
			echo "<script>alert('数据添加失败！'); history.go(-1);</script>";
			$this ->success('数据添加失败!');
		}
	}
	
	
	//数据的修改
	public function updatefirsttypeshow(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updatefirsttypeshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id            = $this->_post('id');
		$update_submit = $this->_post('update_submit');
		
		$Model = new Model();
		
		$verdata_sql = "select * from bigstore where id='".$id."'";
		$verdata_list= $Model->query($verdata_sql);
		
		if(count($verdata_list)<=0){
				
			echo "<script>alert('非法操作');history.go(-1);</script>";
			$this -> error('非法操作');
		}else{
				
			//是否启用
			$flag_arr = array(
					'1' => '已上架',
					'9' => '未上架',
					'2' => '已作废',
			);
			/* $optionflag = '<option value=""></option>'; */
			foreach($flag_arr as $keyc => $valc) {
				$optionflag .= '<option value="'.$keyc.'" ';
				if($verdata_list[0]['flag']==$keyc) { $optionflag .= ' selected="selected" '; }
				$optionflag .= '>'.$valc.'</option>';
			}
			$this->assign('optionflag',$optionflag);
			
			
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
			
			
			
				
			if($verdata_list[0]['flag']=='1'){
				$verdata_list[0]['flag']='已上架';
			}else if($verdata_list[0]['flag']=='9'){
				$verdata_list[0]['flag']='未上架';
			}else if($verdata_list[0]['flag']=='2'){
				$verdata_list[0]['flag']='已作废';
			}
			
			
			//跳转页面类型
			$tztype_arr = array(
				'xiebao'       => '鞋包',
				'yingshi'      => '影视',
				'dache'        => '打车',
				'meishi'       => '美食',
				'fuzhuang'     => '服装',
				'shenghuo'     => '生活',
				'fuwu'         => '服务',
				'dianqi'       => '电器',
				'wenti'        => '文体',
				'meizhuang'    => '美妆',
			);
				
			foreach($tztype_arr as $keyc => $valc) {
				$optiontype .= '<option value="'.$keyc.'" ';
				if($verdata_list[0]['type']==$keyc) { $optiontype .= ' selected="selected" '; }
				$optiontype .= '>'.$valc.'</option>';
			}
			$this->assign('optiontype',$optiontype);
			
		}
		
		$this->assign('list',$verdata_list[0]);
		
		// 输出模板
		$this->display();
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	//修改数据的添加
	public function updatefirsttypedata(){
		
	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updatefirsttypedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//获取相应的参数
		$id    = $this->_post('id');
		$flag  = $this->_post('flag');
		$youxiao  = $this->_post('youxiao');
		$type        = $this->_post('type');
		$childtype   = $this->_post('childtype');
		$scoretimes   = $this->_post('scoretimes');
		$title       = $this->_post('title');
		$name        = $this->_post('name');
		$youxiaoqi      = trim($this->_post('youxiaoqi'));
		$content        = $this->_post('content');
		$picurl         = trim($this->_post('picurl'));
		$tiaozhuanurl   = trim($this->_post('tiaozhuanurl'));
		
		$yuanjia   = $this->_post('yuanjia');
		$zhehoujia   = $this->_post('zhehoujia');
		$quanjia   = $this->_post('quanjia');
		
		
		
		
		$Model = new Model();
		$data = array();
		 $data['flag']            = $flag;
		 $data['youxiao']         = $youxiao;
		 $data['type']            = $type;
		 $data['childtype']       = $childtype;
		 $data['title']           = $title;
		 $data['name']            = $name;
		 
		 $data['yuanjia']            = $yuanjia;
		 $data['zhehoujia']          = $zhehoujia;
		 $data['quanjia']            = $quanjia;
		 
		 $data['youxiaoqi']       = $youxiaoqi;
		 $data['content']         = $content;
		 $data['picurl']          = $picurl;
		 $data['scoretimes']      = $scoretimes;
		 $data['tiaozhuanurl']    = $tiaozhuanurl;
		 $data['create_datetime']   = date('Y-m-d h:i:s');
			 	
		
		 $imagedata_sql = $Model->table('bigstore')->where ("id='".$id."'")->save($data);
		 	
		 if($imagedata_sql){
			 echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Firsttype/index".$yuurl."';</script>";
			 $this ->success('数据修改成功!','__APP__/Firsttype/index'.$yuurl);
		 }else{
			 echo "<script>alert('数据修改失败！'); history.go(-1);</script>";
			 $this->error('数据修改失败！');
		 }
	}
	
	
	
	
	
	//数据的删除
	public function deletefirsttypedata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_deletefirsttypedata);
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
			$ret = $Model -> table('bigstore') -> where("id='".$id."'") -> delete();
				
			if($ret) {
				echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Firsttype/index".$yuurl."';</script>";
				$this -> success('数据删除成功!','__APP__/Firsttype/index'.$yuurl);
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
			$updatesql  = "update bigstore set flag='".$flag."' where id='".$id."' ";
			$ret = $Model ->execute($updatesql);
	
	
			if($ret) {
				echo "<script>alert('操作成功！');window.location.href='".__APP__."/Firsttype/index".$yuurl."';</script>";
				$this -> success('操作成功!','__APP__/Firsttype/index'.$yuurl);
			}else {
				echo "<script>alert('操作失败，系统错误!');history.go(-1);</script>";
				$this -> error('操作失败，系统错误!');
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