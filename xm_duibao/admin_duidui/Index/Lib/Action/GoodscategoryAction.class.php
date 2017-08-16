<?php
/*
 * 商品的分类
 */

class GoodscategoryAction extends Action {
	
	
	//定义各模块锁定级别
	private $lock_index          = '9751';
	
	
	
	//商品的分类
	public function categorylist(){
		
		
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//判断用户是否登陆
		$this->loginjudgeshow($this->lock_index);
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		
		//拼接url参数
		$yuurl = $this -> createurl($_GET);
		$this -> assign('yuurl',$yuurl);
		
		
		
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
	 	
	 	
	 	
	 	
	 	
	 	
	 	
	 	
	 	
	 	/* $goods_category_info = M("shop_category")->where('id=0')->find();
	 	
	 	$level_cat = $this->find_parent_cat($goods_category_info['id']); // 获取分类默认选中的下拉框
	 	
	 	print_r($level_cat);
	 	
	 	$cat_list = M('shop_category')->where("parent_id = 0")->select(); // 已经改成联动菜单
	 	
		
	 	$this->assign('level_cat',$level_cat);
	 	$this->assign('cat_list',$cat_list);
	 	$this->assign('goods_category_info',$goods_category_info); */
	 	$m=M('goods_type');
	 	$data=M('goods_type')->field("*,concat(path,',',id) as paths")->order('paths')->select();
	 	
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
			 
			$path=M('goods_type')->field("path")->find($pid);//深度
			$treepath=$path['path'];
			$level=substr_count($data['path'],",");//等级
			
			$data = array();
			$data['name'] =$name;
			$data['pid']  =$pid;
			$data['path'] = $treepath;
			$data['level'] = $level;
			$data['flag'] = $flag;
			$data['sort'] = $sort_order;
			
			$re=M('goods_type')->add($data);//返回插入id
			
			$path['id']=$re;
			$path['path']=$data['path'].','.$re;
			$path['level']=substr_count($path['path'],",");
			$res=M('goods_type')->save($path);
			
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
			$re=M('goods_type')->add($data);//返回插入id
	
			$path['id']=$re;
			$path['path']=$data['path'].','.$re;
			 
			$res=M('goods_type')->save($path);
			
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//分类的增加或修改‘
	public function addEditCategory(){
		
		
		
		
		
		
		
		
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