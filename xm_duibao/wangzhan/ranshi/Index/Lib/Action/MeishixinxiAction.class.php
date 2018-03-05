<?php

//本类由系统自动生成，仅供测试用途
class MeishixinxiAction extends Action {
	
	
	//煤市资讯
	public function zixun(){
		
		$sql_where = 'ntype=2';
		$sql_order = 'id desc,zid desc';
		
		$Model = new Model();
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('xinwenzixun')
						-> where($sql_where)
						-> count();   // 查询满足要求的总记录数
		$Page = new Page($count,10);  // 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();        // 分页显示输出
		
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		 $this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('xinwenzixun')
						//-> field($sql_field)
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		$this->assign('list',$list);
		
		$this->display();
	}
	
	
	
	
	public function zixun_c() {
		
		
		$id = $this->_get('id');
		
		
		if($id=='') {
			$thsi->error('非法操作');
		}
		
		$Model = new Model();
		
		$sql_p = "select * from xinwenzixun where id='".$id."' limit 1 ";
		
		$list_p = $Model->query($sql_p);
		//print_r($list_p);
		$this->assign('list_p',$list_p[0]);
		//$this->display();
		
		
	}
	
	
	
	
	/*物流仓储*/
	public function cangchu(){
		
		/* 分页 */
		$sql_field = 'id,name,sendtime';
		$sql_where = 'ntype=1';
		$sql_order = 'id desc,zid desc';
		
		$Model = new Model();
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('xinwenzixun')
						-> where($sql_where)
						-> count();   // 查询满足要求的总记录数
		$Page = new Page($count,10);  // 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();        // 分页显示输出
		
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		 $this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('xinwenzixun')
						-> field($sql_field)
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		$this->assign('list',$list);
		//print_r($list);
		$this->display();
	}
	
	
	
	
	public function cangchu_c() {
		
		$id = $this->_get('id');
		
		if($id=='') {
			$thsi->error('非法操作');
		}
		
		$Model = new Model();
		
		$sql_s = "select * from xinwenzixun where id='".$id."' limit 1 ";
		
		$list_s = $Model->query($sql_s);
		//print_r($list_s);
		$this->assign('list_s',$list_s[0]);
		$this->display();
		
		
		
		
		
	}

	
	/*指数价格*/

	public function jiage(){
		
		
		
		/* 分页 */
		//$sql_field = 'ntype';
		$sql_where = 'ntype=4';
		$sql_order = 'id desc,zid desc';
		
		$Model = new Model();
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('xinwenzixun')
						-> where($sql_where)
						-> count();   // 查询满足要求的总记录数
		$Page = new Page($count,10);  // 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();        // 分页显示输出
		
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		 $this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('xinwenzixun')
						//-> field($sql_field)
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		$this->assign('list',$list);
		
		$this->display();
	}

    public function jiage_c() {
		
		
		$id = $this->_get('id');
		//print_r($id);
		
		if($id=='') {
			$thsi->error('非法操作');
		}
		
		$Model = new Model();
		
		$sql_s = "select * from xinwenzixun where id='".$id."' limit 1 ";
		
		$list_s = $Model->query($sql_s);
		//print_r($list_s);
		$this->assign('list_s',$list_s[0]);
		$this->display();
		
		
	}

	
    /*煤市行情*/
	public function hangqing(){
		
		/* 分页 */
		//$sql_field = 'ntype';
		$sql_where = 'ntype=3';
		$sql_order = 'id desc,zid desc';
		
		$Model = new Model();
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('xinwenzixun')
						-> where($sql_where)
						-> count();   // 查询满足要求的总记录数
		$Page = new Page($count,10);  // 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();        // 分页显示输出
		
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		 $this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('xinwenzixun')
						//-> field($sql_field)
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		$this->assign('list',$list);
		
		$this->display();
	}
	
	public function hangqing_c() {
		
		//
		$id   = $this->_get('id');  //唯一标示id
		
		//print_r($id);
		
		if($id=='') {
			$thsi->error('非法操作');
		}
		
		$Model = new Model();
		
		$sql_s = "select * from xinwenzixun where id='".$id."' limit 1 ";
		
		$list_s = $Model->query($sql_s);
		//print_r($list_s);
		$this->assign('list_s',$list_s[0]);
		$this->display();
		
		
	}
	
	
	
}