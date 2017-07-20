<?php
/*
 * 上家渠道表的管理
 */

class BasicsiteAction extends Action {
	//basic_site渠道信息数据表操作
	
	
	//定义各模块锁定级别
	private $lock_index          = '9751';
	private $lock_deletesitedata = '97';
	private $lock_updatesiteshow = '975';
	private $lock_updatesitedata = '975';
	private $lock_addsiteshow    = '975';
	private $lock_addsitedata    = '975';
	
	
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
		$site_s    = $this->_get('site_s');
		$name_s    = $this->_get('name_s');
		$flag_s    = $this->_get('flag_s');
		
		if($site_s!='') {
			if(!is_numeric($site_s)) {
				$site_s = '';
			}
		}
		
		$this-> assign('site_s',$site_s);
		$this-> assign('name_s',$name_s);
		
		
		$optionflag = '<option value=""></option>';
		$optionflag .= '<option value="1" ';
		if($flag_s==1) { $optionflag .= ' selected="selected" '; }
		$optionflag .= '>启用</option>';
		$optionflag .= '<option value="9" ';
		if($flag_s==9) { $optionflag .= ' selected="selected" '; }
		$optionflag .= '>关闭</option>';
		$this -> assign('optionflag',$optionflag);
		
		//-----------------------------------------------------------
		//生成where条件判断字符串
		$sql_where = '';
		if($site_s!='') {
			$sql_where .= " id='".$site_s."' and ";
		}
		if($flag_s!='') {
			$sql_where .= " flag='".$flag_s."' and ";
		}
		if($name_s!='') {
			$sql_where .= " name like'".$name_s."%' and ";
		}
		$sql_where = rtrim($sql_where,'and ');
		//-----------------------------------------------------------
		
		$sql_field = " id,name,flag,remark ";
		
		//生成排序字符串数据
		$sql_order = " id asc ";
		

		import('ORG.Page');// 导入分页类
		$count = $Model -> table('shop_site')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,50);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('shop_site')
						-> field($sql_field)
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		//---------------------------------------------------------------
		
		foreach($list as $keyc => $valc) {
			
			
			if($list[$keyc]['flag']=='1') {
				$list[$keyc]['flag'] = '<font style="background-color:#00EA00">&nbsp;&nbsp;&nbsp;开启</font>';
			}else if($list[$keyc]['flag']=='9') {
				$list[$keyc]['flag'] = '<font style="background-color:#FF1700">&nbsp;&nbsp;关闭</font>';
			}else {
				$list[$keyc]['flag'] = 'ERR';
			}
			
		}
		$this -> assign('list',$list);
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
		
	}
	
	
	
	
	//用户单击删除按钮后的判断模块
	public function deletesitedata() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_deletesitedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		if($this->_post('delete_submit')!=null){

			$Model = new Model();
			
			if($this->_post('site_id')!=null) {
				$site_id = $this->_post('site_id');
			}else {
				echo "<script>alert('非法操作！');history.go(-1);</script>";
				$this -> error('非法操作！');
			}
			
			
			//查询道具表，看是否有道具与该渠道存在关联
			$sql_pan = "select count(*) as con from shop_product where siteid=".$site_id;
			$list_pan = $Model -> query($sql_pan);
			
			if($list_pan[0]['con']>0) {
				echo "<script>alert('此渠道在商品列表中存在关联数据，请解除渠道下所有的商品后再次尝试删除操作---shop_product');history.go(-1);</script>";
				$this -> error('此渠道在商品列表中存在关联数据，请解除渠道下所有的商品后再次尝试删除操作---shop_product');
			}else {
				
				//说明此数据没有关联数据，可以删除
				$ret = $Model -> table('shop_site') -> where("id='".$site_id."'") -> delete();
					
					
				if($ret) {
					echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Basicsite/index".$yuurl."';</script>";
					$this -> success('数据删除成功!','__APP__/Basicsite/index'.$yuurl);
				}else {
					echo "<script>alert('数据删除失败，系统错误!');history.go(-1);</script>";
					$this -> error('数据删除失败，系统错误!');
				}
			}
			
		}
		
	}
	
	
	
	
	//修改表单输出页面
	public function updatesiteshow(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updatesiteshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		if($this->_post('update_submit')!=null){
			
			$Model = new Model();
			
			$site_id = '';
		
			if($this->_post('site_id')!=null){
				$site_id = $this -> _post('site_id');
			}
			
			//查询道具表，看是否有道具与该渠道存在关联
			$sql_pan = "select count(*) as con from shop_site where id=".$site_id;
			$list_pan = $Model -> query($sql_pan);
			
			if($list_pan[0]['con']>0) {
				
				$sql_basicsite = "select id,name,flag,remark
								from shop_site
								where id=".$site_id;
				$list = $Model -> query($sql_basicsite);
				
				$is_flag_option = '';
				$is_flag_option .= '<option value="1" ';
				if($list[0]['flag']==1) { $is_flag_option .= ' selected="selected" '; }
				$is_flag_option .= '>启用</option>';
				$is_flag_option .= '<option value="9" ';
				if($list[0]['flag']==9) { $is_flag_option .= ' selected="selected" '; }
				$is_flag_option .= '>关闭</option>';
				
				
				$this-> assign('is_flag_option',$is_flag_option);
				$this -> assign('list',$list[0]);
				
				
			}else {
				echo "<script>alert('非法操作');history.go(-1);</script>";
				$this -> error('非法操作');
			}
			
			
		}
		
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	
	
	//用户提交修改数据后的操作模块
	public function updatesitedata() {
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_updatesitedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		if($this->_post('update_submit')!=null){
			
			$Model = new Model();
			
			//定义存储数据的基础变量
			$site_id   = '';
			$siet_name = '';
			$remark    = '';
			$flag      = 9;
				
			if($this->_post('site_id')!=null){
				$site_id = $this->_post('site_id');
				//判断是否为纯数字
				if(is_numeric($site_id) && $site_id>=1000 && $site_id<=9999 ) {
					//通过
				}else {
					echo "<script>alert('非法操作-');history.go(-1);</script>";
					$this -> error('非法操作-');
				}
			}else {
				echo "<script>alert('非法操作');history.go(-1);</script>";
				$this -> error('非法操作');
			}
				
				
			$site_name = $this->_post('site_name');
			$flag      = $this->_post('flag');
			$remark    = $this->_post('remark');
			
			
			//更新数据
			$data['name']      = $site_name;
			$data['flag']      = $flag;
			$data['remark']    = $remark;
			
			
			$ret = $Model ->table('shop_site') -> where("id='".$site_id."'") -> save($data);
			
			
			if($ret) {
				echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Basicsite/index".$yuurl."';</script>";
				$this ->success('数据修改成功!','__APP__/Basicsite/index'.$yuurl);
			}else {
				echo "<script>alert('数据修改失败，您未做任何改动!');history.go(-1);</script>";
				$this -> error('数据修改失败，您未做任何改动!');
			}
			
			
		}
		
		
	}
	
	
	
	
	//数据添加表单输出页面
	public function addsiteshow() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addsiteshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		// 输出模板
		$this->display();
		
		printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024);
		
	}
	
	
	
	
	//数据添加执行模块
	public function addsitedata() {
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addsitedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		if($this->_post('insert_submit')!=null){
			
			$Model = new Model();
			
			if($this->_post('site_id')!=null){
				$site_id = $this->_post('site_id');
				//判断是否为纯数字
				if(is_numeric($site_id) && $site_id>=1000 && $site_id<=9999 ) {
					//通过
				}else {
					echo "<script>alert('渠道编号必须只能由数字组成，并且介于1000到9999之间');history.go(-1);</script>";
					$this -> error('渠道编号必须只能由数字组成，并且介于1000到9999之间');
				}
			}else {
				echo "<script>alert('渠道编号不能为空');history.go(-1);</script>";
				$this -> error('渠道编号不能为空');
			}
			
			$site_name = $this->_post('site_name');
			$flag      = $this->_post('flag');
			$remark    = $this->_post('remark');
			
			
			$sql_panduan = "select count(*) as con
							from basic_site 
							where id=".$site_id;
			$list_panduan = $Model -> query($sql_panduan);
			
			if($list_panduan[0]['con']>0) {
				echo "<script>alert('渠道编号不能重复');history.go(-1);</script>";
				$this -> error('渠道编号不能重复');
			}else {
				
				//将获取到的数据写入数据库
				
				$data['id']        = $site_id;
				$data['name']      = $site_name;
				$data['flag']      = $flag;
				$data['remark']    = $remark;
				
				$ret = $Model -> table('shop_site') -> add($data);
				
				if($ret) {
					echo "<script>alert('数据添加成功！');window.location.href='".__APP__."/Basicsite/index".$yuurl."';</script>";
					$this ->success('数据添加成功!','__APP__/Basicsite/index'.$yuurl);
					
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
	
	
	
	
	//日志记录数据写入封装函数
	public function tphy_writelog($data) {
		//判断该日志文件存放路径是否存在，不存在则进行创建
		
		$path = isset($this->logfilename) ? $this->logfilename : '/log/datachange/';
		$name = date('Y-m-d').'_change.log';
		
		if(!is_dir($path)) {
			//创建该目录
			mkdir($path, 0777, true);
		}
		$data .= "\n\n";
		//生成文件路径名称
		$filepathname = $path.$name;
		
		$fp = fopen($filepathname,'a'); //打开句柄
		fwrite($fp, $data);  //将文件内容写入字符串
		fclose($fp); //关闭句柄
	
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