<?php
/*
 * 商品的属性
 */
class ShoparrAction extends Action {
	
	
	//商品类型
	public function shoptype(){
		
		
		$Model = new Model();
		
		$type_sql = "select * from db_goods_category ";
		$type_list = $Model->query($type_sql);
		
		$this->assign('list',$type_list);
		$this->display();
	}
	
	//分类数据的删除
	public function  delGoodsCategory(){
		
		$id = $this->_get('id');
		
		$delsql = M('db_goods_category')->where("id = $id ")->delete();
		 
		if($delsql){
			echo "<script>alert('操作成功！');window.location.href='".__APP__."/Shoparr/shoptype".$yuurl."';</script>";
			$this -> success('操作成功!','__APP__/Shoparr/shoptype'.$yuurl);
		}else{
			echo "<script>alert('操作失败!');history.go(-1);</script>";
			$this -> error('操作失败!');
		}
		
	}
	
	
	//商品类型的修改
	public function goodstypeshow(){
		
		
		$type=$this->_get('type');//添加商品分类页面
		$id  = $this->_get('id');//修改的标识
		
		if($id!=''){
			
			$list  = M("db_goods_category")->where('id='.$id)->select();
			
			$this->assign('list',$list[0]);
		}
		
		$this->display();
	 }
	 
	 
	 //数据的修改
	 public function goodstype_updatedata(){
	 	
	 	$id = $this->_post('id');
	 	$name = $this->_post('name');
	 	$uupdate_submit = $this->_post('uupdate_submit');
	 	
	 	if($id==''){//数据的添加
	 		
	 		//数据的修改保存操作
	 		$data = array();
	 		$data['name'] = $name;
	 		$goodslist = M('db_goods_category')->add($data);
	 			
	 		if($goodslist){
	 			echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Shoparr/shoptype".$yuurl."';</script>";
	 			$this -> success('数据修改成功!','__APP__/Shoparr/shoptype'.$yuurl);
	 		}else{
	 			echo "<script>alert('数据修改失败，系统错误!');history.go(-1);</script>";
	 			$this -> error('数据修改失败，系统错误!');
	 		}
	 		
	 	}else{//数据的修改
	 		
	 		//数据的修改保存操作
	 		$data = array();
	 		$data['name'] = $name;
	 		$goodslist = M('db_goods_category')->where('id='.$id)->save($data);
	 			
	 		if($goodslist){
	 			echo "<script>alert('数据修改成功！');window.location.href='".__APP__."/Shoparr/shoptype".$yuurl."';</script>";
	 			$this -> success('数据修改成功!','__APP__/Shoparr/shoptype'.$yuurl);
	 		}else{
	 			echo "<script>alert('数据修改失败，系统错误!');history.go(-1);</script>";
	 			$this -> error('数据修改失败，系统错误!');
	 		}
	 	}
	 	
	 }
	 
	 
	 //数据的删除
	 public function goodsdeletedata(){
	 
	 	$id        = $this->_get('id');
	 	
	 	$list = M('db_goods_category')->where('id='.$id)->delete();
	 	
	 	if($list){
	 		echo "<script>alert('数据删除成功！');window.location.href='".__APP__."/Shoparr/shoptype';</script>";
	 		$this -> success('数据删除成功!','__APP__/Shoparr/shoptype');
	 	}else{
	 		echo "<script>alert('数据删除失败!');history.go(-1);</script>";
	 		$this -> error('数据删除失败!');
	 	}
	 }
	 
	 
	 //商品分类
	 public function shopcategory(){
	 	
	 	$Model = new Model();
	 	
	 	import('ORG.Page');// 导入分页类
	 	$count = $Model -> table('db_goods_type')
					 	-> where('level=1')
					 	-> count();// 查询满足要求的总记录数
	 	$Page = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
	 	$show = $Page->show();// 分页显示输出
	 	
	 	$cat_list = $this->goods_cat_list();
	 	
	 	//进行分页数据查询 注意limit方法的参数要使用Page类的属性
	 	$this->assign('page',$show);// 赋值分页输出
	 	$this->assign('cat_list',$cat_list);
	 	$this->display();
	 }
	 
	 //属性值
	 public function shopattribute(){
	 	
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
	 	
	 	$flag = array(1=>'开启',9=>'关闭');
	 	$this->assign('flag',$flag);
	 	
	 	$this->assign('list',$list);
	 	$this->assign('typename',$typenamearr);
	 	
	 	$this->display();
	 	
	 	
	 }
	 
	 
	 
	 
	 //商品分类修改页面
	 public function shopcategoryshow(){
	 	
	 	$id = $this->_get('id');
	 	
	 	if($id==''){
	 		$id='0';
	 	}
	 	
	 	//从编辑页面跳转过来
	 	$goods_category_info = M('db_goods_type')->where("id='$id'")->find();
	 	
	 	
	 	$level_cat  = $this->find_parent_cat($goods_category_info['id']); // 获取分类默认选中的下拉框
	 	$cat_list = M('db_goods_type')->where("pid = 0")->select(); // 已经改成联动菜单
	 	
	 	$data=M('db_goods_type')->field("*,concat(path,',',id) as paths")->order('paths')->select();
	 	 
	 	
	 	foreach($data as $k=>$v){
	 		$data[$k]['name']=str_repeat("|------",$v['level']).$v['name'];
	 	}
	 	
	 	$this->assign('data',$data);
	 	
	 	$this->assign('level_cat',$level_cat);
	 	$this->assign('cat_list',$cat_list);
	 	$this->assign('goods_category_info',$goods_category_info);
	 	
	 	$this->display();
	 }
	 
	 
	 //分类数据的入库操作
	 public function addEditCategory(){
	 	
	 	
	 	$name = $this->_post('name');
	 	$pid  = $this->_post('parent_id_1');
	 	$flag  = '1';
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
	 			echo "<script>alert('添加成功！');window.location.href='".__APP__."/Shoparr/shopcategory".$yuurl."';</script>";
	 			$this -> success('数据修改成功!','__APP__/Shoparr/shopcategory'.$yuurl);
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
	 			echo "<script>alert('添加成功！');window.location.href='".__APP__."/Shoparr/shopcategory".$yuurl."';</script>";
	 			$this -> success('数据修改成功!','__APP__/Shoparr/shopcategory'.$yuurl);
	 		}else{
	 			echo "<script>alert('数据修改失败，系统错误!');history.go(-1);</script>";
	 			$this -> error('数据修改失败，系统错误!');
	 	
	 		}
	 	
	 	}else{
	 		echo "<script>alert('添加失败，添加内容不能为空');window.location.href='".__APP__."/Shoparr/shopcategoryshow".$yuurl."';</script>";
	 		$this -> success('数据修改成功!','__APP__/Shoparr/shopcategoryshow'.$yuurl);
	 	
	 	}
	 	
	 	
	 }
	 
	 
	 //商品属性的修改
	 public function attributelist(){
	 	
	 	$id = $this->_get('id');
	 	
	 	//查询对应id属性值
	 	$attr_sql =  M('db_goods_attribute')->where("id = $id ")->select();
	 	
	 	//获取商品的类型
	 	$typenamearr = M('db_goods_type')->where('level=1')->getField('id,name');
	 	
	 	$optiontype = '<option value=""></option>';
	 	foreach($typenamearr as $keyc => $valc) {
	 		$optiontype .= '<option value="'.$keyc.'" ';
	 		if($attr_sql[0]['type_id']==$keyc) { $optiontype .= ' selected="selected" '; }
	 		$optiontype .= '>'.$valc.'</option>';
	 	}
	 	
	 	$this->assign('optiontype',$optiontype);
	 	
	 	//是否启用
	 	$flag_arr = array(
	 			'9' => '关闭',
	 			'1' => '启用',
	 				
	 	);
	 	foreach($flag_arr as $keyc => $valc) {
	 		$optionflag .= '<option value="'.$keyc.'" ';
	 		if($attr_sql[0]['flag']==$keyc) { $optionflag .= ' selected="selected" '; }
	 		$optionflag .= '>'.$valc.'</option>';
	 	}
	 	$this->assign('optionflag',$optionflag);
	 	
	 	$this->assign('list',$attr_sql[0]);
	 	
	 	$this->display();
	 	
	 }
	 
	 
	 //属性值的添加
	 public function attributedata(){
	 	
	 	$attr_name = $this->_post('attr_name');
	 	$type      = $this->_post('type');
	 	$flag        = $this->_post('flag');
	 	$attr_values = $this->_post('attr_values');
	 	$id          = $this->_post('id');
	 	$add_submit  = $this->_post('uupdate_submit');
	 	
	 	$Model = new Model();
	 	
	 	if($add_submit!=''){
	 			
	 		if($id==''){//添加操作
	 				
	 			$data = array();
	 			$data['flag']=$flag;
	 			$data['attr_name'] = $attr_name;
	 			$data['type_id']   = $type;
	 			$data['attr_values'] = $attr_values;
	 			$data['order'] = '50';
	 				
	 			$addlist = $Model->table('db_goods_attribute')->add($data);
	 				
	 		}else{
	 	
	 			$data=array();
	 			$data['flag']=$flag;
	 			$data['attr_name'] = $attr_name;
	 			$data['type_id']   = $type;
	 			$data['attr_values'] = $attr_values;
	 			$data['order'] = '50';
	 	
	 			$addlist = $Model->table('db_goods_attribute') -> where("id='".$id."'")->save($data);
	 		}
	 		
	 		if($addlist){
	 			echo "<script>alert('操作成功！');window.location.href='".__APP__."/Shoparr/shopattribute".$yuurl."';</script>";
	 			$this -> success('操作成功!','__APP__/Shoparr/shopattribute'.$yuurl);
	 		}else{
	 			echo "<script>alert('操作失败!');history.go(-1);</script>";
	 			$this -> error('操作失败!');
	 		}
	 			
	 	}
	 	
	 }
	 
	 
	 //属性值的删除
	 public function delattributelist(){
	 	
	 	$id = $this->_get('id');
	 	
	 	$delsql = M('db_goods_attribute')->where("id = $id ")->delete();
	 	
	 	if($delsql){
	 		echo "<script>alert('操作成功！');window.location.href='".__APP__."/Shoparr/shopattribute".$yuurl."';</script>";
	 		$this -> success('操作成功!','__APP__/Shoparr/shopattribute'.$yuurl);
	 	}else{
	 		echo "<script>alert('操作失败!');history.go(-1);</script>";
	 		$this -> error('操作失败!');
	 	}
	 	
	 	
	 	
	 }
	 
	 
	 
	 
	 
	 public function get_category(){
	 	$parent_id = I('get.parent_id'); // 商品分类 父id
	 	
	 	$list = M('db_goods_type')->where("pid = $parent_id")->select();
	 
	 	foreach($list as $k => $v)
	 		$html .= "<option value='{$v['id']}'>{$v['name']}</option>";
	 	exit($html);
	 }
	 
	 
	 /**
	  *  获取选中的下拉框
	  * @param type $cat_id
	  */
	 function find_parent_cat($cat_id)
	 {
	 	if($cat_id == null)
	 		return array();
	 
	 	$cat_list =  M('db_goods_type')->getField('id,pid,level');
	 	$cat_level_arr[$cat_list[$cat_id]['level']] = $cat_id;
	 
	 	// 找出他老爸
	 	$parent_id = $cat_list[$cat_id]['pid'];
	 	if($parent_id > 0)
	 		$cat_level_arr[$cat_list[$parent_id]['level']] = $parent_id;
	 	// 找出他爷爷
	 	$grandpa_id = $cat_list[$parent_id]['pid'];
	 	if($grandpa_id > 0)
	 		$cat_level_arr[$cat_list[$grandpa_id]['level']] = $grandpa_id;
	 
	 	// 建议最多分 3级, 不要继续往下分太多级
	 	// 找出他祖父
	 	$grandfather_id = $cat_list[$grandpa_id]['pid'];
	 	if($grandfather_id > 0)
	 		$cat_level_arr[$cat_list[$grandfather_id]['level']] = $grandfather_id;
	 
	 	return $cat_level_arr;
	 }
	 
	 
	 public function goods_cat_list($cat_id = 0, $selected = 0, $re_type = true, $level = 0){
	 	
	 	global $goods_category, $goods_category2;
	 	$Model = new Model();
	 	$sql = "SELECT * FROM db_goods_type ORDER BY pid , sort ASC";
	 	$goods_category = $Model->query($sql);
	 	
	 
	 	$goods_category = convert_arr_key($goods_category, 'id');
	 
	 	
	 	foreach ($goods_category AS $key => $value)
	 	{
	 		if($value['level'] == 1)
	 			$this->get_cat_tree($value['id']);
	 	}
	 	return $goods_category2;
	 }
	 
	 /**
	  * 获取指定id下的 所有分类
	  * @global type $goods_category 所有商品分类
	  * @param type $id 当前显示的 菜单id
	  * @return 返回数组 Description
	  */
	 public function get_cat_tree($id) {
	 	global $goods_category, $goods_category2;
	 	$goods_category2[$id] = $goods_category[$id];
	 	foreach ($goods_category AS $key => $value){
	 		if($value['pid'] == $id){
	 			$this->get_cat_tree($value['id']);
	 			$goods_category2[$id]['have_son'] = 1; // 还有下级
	 		}
	 	}
	 }
	 
	 
	 public function changeTableVal(){
	 	
	 	$id = $this->_get('id');//更新的id值
	 	$sort_value = $this->_get('sort');//sort排序值
	 	$data = array();
	 	$data['sort'] = $sort_value;
	 	
	 	M('db_goods_type')->where("id='".$id."'")->save($data); // 根据条件保存修改的数据
	 }
	
}