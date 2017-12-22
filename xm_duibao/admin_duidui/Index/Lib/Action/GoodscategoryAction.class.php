<?php
/*
 * 商品的分类
 */

class GoodscategoryAction extends Action {
	
	
	//定义各模块锁定级别
	private $lock_categorylist          = '9751';
	private $lock_addcategory          = '9751';
	private $lock_goods_type_add          = '9751';
	private $lock_goodstype          = '9751';
	private $lock_goodstypeshow          = '9751';
	private $lock_goodstype_updatedata          = '9751';
	private $lock_attributelist          = '9751';
	private $lock_addattributelist          = '9751';
	private $lock_attrdeletedata          = '9751';
	private $lock_addattributelistdata          = '9751';
	
	
	
	//商品的分类
	public function categorylist(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_categorylist);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		$Model = M("db_goods_type");
		$type = $Model->where("pid=0")->select();//获得一级分类
		
		$type2=array();
		$type3 = array();
		
		foreach ($type as $key=>$value){
			
			$type[$key]['child'] = array();
			$type2 = $Model->where("pid=".$value['id'])->select();//获得二级分类
			
			foreach ($type2 as $k =>$v){
				
				$type[$key]['child'][$k]['child2'] = array();//获取三级分类
				
				array_push($type[$key]['child'],$v);//合并一级分类和二级分类
				
				$type3 = $Model->where("pid=".$v['id'])->select();//获取三级分类
				
				foreach ($type3 as $v2){
					
					array_push($type[$key]['child'][$k]['child2'],$v2);//合并一级二级分类
					
				}
				
			}
			
			
			
		}
		
		
		/* print_r("<pre>");
		print_r($type[$key]['child'][$k]['child2']); */
		
		$this -> assign('list',$type);
		
		$this->assign('clist',$type[$key]['child'][$k]['child2']);
		
		
		/* print_r("<pre>");
		print_r($type); */
		
		
		
		
		
		
		
		/* $Model = new Model();
		$sql = "select * from db_goods_type where flag=1 order by sort";
		$goods_category = $Model->query($sql);
		
		$goods_category = convert_arr_key($goods_category, 'id');
		
		global $goods_category, $goods_category2;
		foreach ($goods_category AS $key => $value){
			
			if($value['level'] == 1)
				
				$r = $this->get_cat_tree($value['id']);
		}
		
		
		
		print_r($r);
		 */
		
		
		
		
		
		$this->display();
	}
	
	
	
	
	/**
	 * 获取指定id下的 所有分类
	 * @global type $goods_category 所有商品分类
	 * @param type $id 当前显示的 菜单id
	 * @return 返回数组 Description
	 */
	public function get_cat_tree($id)
	{
		global $goods_category, $goods_category2;
		$goods_category2[$id] = $goods_category[$id];
		foreach ($goods_category AS $key => $value){
			if($value['pid'] == $id)
			{
				$this->get_cat_tree($value['id']);
				$goods_category2[$id]['have_son'] = 1; // 还有下级
			}
		}
		
		return $goods_category2;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	//分类的添加
	 public function addcategory(){
	 	
	 	
	 	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	 	//判断用户是否登陆
	 	$this->loginjudgeshow($this->lock_addcategory);
	 	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	 	
	 	//拼接url参数
	 	$yuurl = $this -> createurl($_GET);
	 	$this -> assign('yuurl',$yuurl);
	 	
	 	//开启或关闭
	 	$flag_arr = array(
	 			'1' => '开启',
	 			'9' => '关闭',
	 	);
	 	foreach($flag_arr as $keyc => $valc) {
	 		$optionflag .= '<option value="'.$keyc.'" ';
	 		$optionflag .= '>'.$valc.'</option>';
	 	}
	 	
	 	$this -> assign('optionflag',$optionflag);
	 	
	 	
	 	//展示一级分类
	 	$Model = new Model();//$User = M("User");$User->where('status=1 AND name="thinkphp"')->find();
	 	
	 	
	 	$m=M('db_goods_type');
	 	$data=M('db_goods_type')->field("*,concat(path,',',id) as paths")->order('paths')->select();
	 	
	 	/* print_r("<pre>");
	 	print_r($data); */
	 	 
	 	foreach($data as $k=>$v){
	 		$data[$k]['name']=str_repeat("|------",$v['level']).$v['name'];
	 	}
	 	 
	 	$this->assign('data',$data);
	 	
		$this->display();
		
	} 
	
	
	//添加分类信息到数据库
	public function goods_type_add(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_goods_type_add);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		 
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$name = $this->_post('name');
		$pid  = $this->_post('pid');
		$flag = $this->_post('flag');//是否开启
		$sort_order = $this->_post('sort_order');
		
		if($name !=" "  && $pid !=0){
			 
			$path=M('db_goods_type')->field("path")->find($pid);//深度
			$treepath=$path['path'];
			$level=substr_count($data['path'],",");//等级
			
			$data = array();
			$data['name'] =$name;
			$data['pid']  =$pid;
			$data['path'] = $treepath;
			$data['level'] = $level;
			$data['flag'] = $flag;
			$data['sort'] = $sort_order;
			
			$re=M('db_goods_type')->add($data);//返回插入id
			
			$path['id']=$re;
			$path['path']=$data['path'].','.$re;
			$path['level']=substr_count($path['path'],",");
			$res=M('db_goods_type')->save($path);
			
			if($res){
				echo "<script>alert('添加成功！');window.location.href='".__APP__."/Goodscategory/addcategory".$yuurl."';</script>";
				$this -> success('数据修改成功!','__APP__/Goodscategory/addcategory'.$yuurl);
			}else{
				echo "<script>alert('数据修改失败，系统错误!');history.go(-1);</script>";
				$this -> error('数据修改失败，系统错误!');
			}
		}else if($name !="" && $pid ==0){
			 
			//$path=$m->field("path")->find($data['pid']);
			$data['name'] = $name;
			$data['pid'] = $pid;
			$data['path']=$data['pid'];
			$data['level']=1;
			$data['flag'] = $flag;
			$data['sort'] = $sort_order;
			$re=M('db_goods_type')->add($data);//返回插入id
	
			$path['id']=$re;
			$path['path']=$data['path'].','.$re;
			 
			$res=M('db_goods_type')->save($path);
			
			if($res){
				echo "<script>alert('添加成功！');window.location.href='".__APP__."/Goodscategory/addcategory".$yuurl."';</script>";
				$this -> success('数据修改成功!','__APP__/Goodscategory/addcategory'.$yuurl);
			}else{
				echo "<script>alert('数据修改失败，系统错误!');history.go(-1);</script>";
				$this -> error('数据修改失败，系统错误!');
	
			}
	
		}else{
			echo "<script>alert('添加失败，添加内容不能为空');window.location.href='".__APP__."/Goodscategory/addcategory".$yuurl."';</script>";
			$this -> success('数据修改成功!','__APP__/Goodscategory/addcategory'.$yuurl);
	
		}
	
	
	}
	
	
	//商品类型
	public function goodstype(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_goodstype);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		//$Model = new Model();
		
		$list = M("db_goods_category")->select(); // 实例化User对象
		
		$this->assign('list',$list);
		
		$this->display();
	}
	
	
	//商品的展示页面
	public function goodstypeshow(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_goodstypeshow);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$typeid = $this->_post('typeid');//区分是修改页面还是添加页面
		$id     = $this->_post('id');
		
		if($typeid=='1'){
			$list  = M("db_goods_category")->where('id='.$id)->select();
		}
		
		$this->assign('list',$list[0]);
		
		$this->display();
	}
	
	
	//类型的修改
	public function goodstype_updatedata(){
	
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_goodstype_updatedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
	
	
		$id        = $this->_post('id');
		$typename  = $this->_post('typename');
		$typeid    = $this->_post('typeid');//1-修改添加 2-添加
		$update_submit = $this->_post('uupdate_submit');
		
		if($update_submit!=''){
			
			if($typeid=='1'){//修改添加
				
				//数据的修改保存操作
				$data = array();
				$data['name'] = $typename;
				$goodslist = M('db_goods_category')->where('id='.$id)->save($data);
					
				if($goodslist){
					echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Goodscategory/goodstype".$yuurl."';</script>";
					$this -> success('数据修改成功!','__APP__/Goodscategory/goodstype'.$yuurl);
				}else{
					echo "<script>alert('数据修改失败，系统错误!');history.go(-1);</script>";
					$this -> error('数据修改失败，系统错误!');
				}
				
			}else if($typeid=='2'){
				
				$data = array();
				$data['name'] = $typename;
				$goodslist = M('db_goods_category')->add($data);
				
				if($goodslist){
					echo "<script>alert('数据添加成功！');window.location.href='".__APP__."/Goodscategory/goodstype".$yuurl."';</script>";
					$this -> success('数据添加成功!','__APP__/Goodscategory/goodstype'.$yuurl);
				}else{
					echo "<script>alert('数据修改失败，系统错误!');history.go(-1);</script>";
					$this -> error('数据修改失败，系统错误!');
				}
				
			}
			
		}else{
			echo "<script>alert('系统错误!');history.go(-1);</script>";
			$this -> error('系统错误!');
		}
		
	}
	
	
	//数据的删除
	public function goodsdeletedata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_goodsdeletedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id        = $this->_post('id');
		$delete_submit = $this->_post('delete_submit');
		
		if($delete_submit!=''){
			
			$list = M('db_goods_category')->where('id='.$id)->delete();
			
			if($list){
				
				echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Goodscategory/goodstype".$yuurl."';</script>";
				$this -> success('数据删除成功!','__APP__/Goodscategory/goodstype'.$yuurl);
				
			}else{
				echo "<script>alert('数据删除失败!');history.go(-1);</script>";
				$this -> error('数据删除失败!');
			}
		}
		
	}
	
	
	//商品的属性表
	public function attributelist(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_attributelist);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		//数据库的初始化
		$Model = new Model();
		
		
		//分类参数
		$type = $this->_post('type');//查询的商品类型
		
		$typenamearr = M('db_goods_type')->where('level=1')->getField('id,name');
		
		$optiontype = '<option value=""></option>';
		foreach($typenamearr as $keyc => $valc) {
			$optiontype .= '<option value="'.$keyc.'" ';
			if($type==$keyc) { $optiontype .= ' selected="selected" '; }
			$optiontype .= '>'.$valc.'</option>';
		}
		
		$this->assign('optiontype',$optiontype);
		
		$sql_where = '';
		
		if($type!='') {
			$sql_where .= "type_id='".$type."' and ";
		}
		
		$sql_where = rtrim($sql_where,'and ');
		
		//生成排序字符串数据
		$sql_order = " id desc ";
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('db_goods_attribute')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('db_goods_attribute')
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		//释放内存
		unset($sql_field, $sql_where, $sql_order);
		
		$flag = array(1=>'开启',2=>'关闭');
		$this->assign('flag',$flag);
		
		$this->assign('list',$list);
		$this->assign('typename',$typenamearr);
		
		$this->display();
	}
	
	
	//属性添加页面
	public function addattributelist(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addattributelist);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id = $this->_post('id');//修改id
		
		
		$Model = new Model();
		
		$attr_sql  = "select * from db_goods_attribute where id='".$id."'";
		$attr_list = $Model->query($attr_sql);
		
		
		$type_sql  = "select * from db_goods_type where flag=1 and level=1 ";
		$type_list = $Model->query($type_sql);
		
		if($id!=''){
			
			$optinontype = '<option value="">请选择</option>';
			foreach ($type_list as $keys => $vals ){
					
				$optinontype.='<option value="'.$vals['id'].'"';
				if($attr_list[0]['type_id']==$vals['id']){
					$optinontype.='selected="selected"';
				}
				$optinontype.='>'.$vals['name'].'</option>';
			}
			
			$this->assign('optinontype',$optinontype);
			
			$this->assign('list',$attr_list[0]);
			
		}else{//商品添加展示页面
			
			$optinontype = '<option value="">请选择</option>';
			foreach ($type_list as $keys => $vals ){
					
				$optinontype.='<option value="'.$vals['id'].'"';
				$optinontype.='>'.$vals['name'].'</option>';
			}
			
			$this->assign('optinontype',$optinontype);
		}
		 
		$this->display();
		
		
	}
	
	
	//属性的修改和添加
	public function addattributelistdata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_addattributelistdata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id  = $this->_post('id');
		$addarr = $this->_post('addarr');//修改操作  ，1- 添加操作
		$name = $this->_post('name');
		$type = $this->_post('type');//商品类型
		
		/* $replace = array("\t", "\r", "\n","_","@");
		$attr_values = str_replace($replace,' ',$this->_post('attr_values'));//属性值 */
		$attr_values = $this->_post('attr_values');
		$add_submit  = $this->_post('add_submit');
		
		$Model = new Model();
		
		if($add_submit!=''){
			
			if($addarr=='1'){//添加操作
					
				$data = array();
				$data['flag']='1';
				$data['attr_name'] = $name;
				$data['type_id']   = $type;
				$data['attr_values'] = $attr_values;
				$data['order'] = '50';
					
				$addlist = $Model->table('db_goods_attribute')->add($data);
					
			}else{
				
				if($id=''){
					echo "<script>alert('非法操作!');history.go(-1);</script>";
					$this -> error('非法操作!');
				}
				
				$data=array();
				$data['flag']='1';
				$data['attr_name'] = $name;
				$data['type_id']   = $type;
				$data['attr_values'] = $attr_values;
				$data['order'] = '50';
				
				$addlist = $Model->table('db_goods_attribute')->save($data);
			}
			
			if($addlist){
				echo "<script>alert('操作成功！');window.location.href='".__APP__."/Goodscategory/attributelist".$yuurl."';</script>";
				$this -> success('操作成功!','__APP__/Goodscategory/attributelist'.$yuurl);
			}else{
				echo "<script>alert('操作失败!');history.go(-1);</script>";
				$this -> error('操作失败!');
			}
			
		}
		
	}
	
	
	//数据的删除
	public function attrdeletedata(){
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_attrdeletedata);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		$id = $this->_post('id');
		
		$Model = new Model();
		
		$sql_data = "delete from db_goods_attribute where id='".$id."' ";
		$list_data = $Model->execute($sql_data);
		
		if($list_data){
			echo "<script>alert('操作成功！');window.location.href='".__APP__."/Goodscategory/attributelist".$yuurl."';</script>";
			$this -> success('操作成功!','__APP__/Goodscategory/attributelist'.$yuurl);
		}else{
			echo "<script>alert('操作失败!');history.go(-1);</script>";
			$this -> error('操作失败!');
		}
		
	}
	
	
	
	
	/**
	 *  获取选中的下拉框
	 * @param type $cat_id
	 */
	function find_parent_cat($cat_id){
		
		if($cat_id == null)
			return array();
	
		$cat_list =  M('shop_category')->getField('id,parent_id,level');
		$cat_level_arr[$cat_list[$cat_id]['level']] = $cat_id;
	
		// 找出他老爸
		$parent_id = $cat_list[$cat_id]['parent_id'];
		if($parent_id > 0)
			$cat_level_arr[$cat_list[$parent_id]['level']] = $parent_id;
		// 找出他爷爷
		$grandpa_id = $cat_list[$parent_id]['parent_id'];
		if($grandpa_id > 0)
			$cat_level_arr[$cat_list[$grandpa_id]['level']] = $grandpa_id;
	
		// 建议最多分 3级, 不要继续往下分太多级
		// 找出他祖父
		$grandfather_id = $cat_list[$grandpa_id]['parent_id'];
		if($grandfather_id > 0)
			$cat_level_arr[$cat_list[$grandfather_id]['level']] = $grandfather_id;
	
		return $cat_level_arr;
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