<?php

   /**
   * 采购信息页面模块
   */
class CaigouxinxiAction extends Action{
	
	/**
	 * 采购信息列表
	 */
	public function demand(){
		
		
		//ctype字段含义，1-动力煤，2-无烟煤，3-炼焦煤，4-其他
		$mei_type = $this->_get('ctype');
		
		
		$sql_field = 'id,m_type,m_diweirezhi,m_shuliang,m_jiage,m_jiaohuodi';
		$sql_where = '';
		$sql_order = " id desc ";
		
		if($mei_type=='1') {
			
			$sql_where .= " m_type='动力煤' ";
		}else if($mei_type=='2') {
			
			$sql_where .= " m_type='无烟煤' ";
		}else if($mei_type=='3'){
			$sql_where.=" m_type='炼焦煤' ";
		}else if($mei_type=='4'){
			$sql_where.=" m_type not in('无烟煤','炼焦煤')";
			
		}
		
		
		$Model = new Model();
		
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('caigouxinxi')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,6);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('caigouxinxi')
						-> field($sql_field)
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		
		//$this->assign('list',$list);
		
		//点击页面来刷新时间
		$pages = $this->_get('p');
		foreach($list as $keyl => $vall) {
			$list[$keyl]['m_date'] = date('Y-m-d',strtotime("- ".$pages." days"));
		}
		
		
		$this->assign('list',$list);
		$this->assign('d_caigouxinxi','active');
		
		$this->display();
		
		
	}
	
	
	
	//
	public function fabujieshou() {
		
		$comment = $this->_post('comment');
		$company = $this->_post('company');
		$phone   = $this->_post('phone');
		$sub     = $this->_post('fabu');
		
		if($sub!=''){
			
			if($company==''){
				echo "<script>alert('请填写公司名称！'); history.go(-1);</script>";
				$this->error('请填写公司名称！');
			}
			
			if($phone==''){
				echo "<script>alert('请填写联系电话！'); history.go(-1);</script>";
				$this->error('请填写联系电话！');
			}
			
			if($comment==''){
				echo "<script>alert('请填写备注信息！'); history.go(-1);</script>";
    			$this->error('请填写备注信息！');
			}
			
			$data = array(
				'comment' => $comment,
				'company' => $company,
				'phone'   => $phone,
				'addtime' => time(),
			);
			
			$Model = new Model();
			
			$id = $Model->table('weituozhaohuo')->add($data);
			
			if($id){
				echo "<script>alert('发布成功！'); history.go(-1);</script>";
				//$this->success('发布成功！');
			}else{
				echo "<script>alert('发布失败！'); history.go(-1);</script>";
				$this->success('发布失败！');
			}
		}
 		
		
		$this->assign('d_caigouxinxi','active');
		$this->display();
		
	}
	

	
}