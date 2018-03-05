<?php
class ZhaomeiwuliuAction extends Action {
    

    /*物流运力*/
	public function wuliuziyuan(){

		
		$sql_field = 'id,w_chuanming,w_type,w_kongchuangang,w_daixiehuowu,w_dunwei';
		$sql_where = 'w_type!=""';
		$sql_order = " id desc ";
		
		$Model = new Model();
		
		import('ORG.Page');// 导入分页类
		$count = $Model -> table('wuliuyunli')
		-> where($sql_where)
		-> count();// 查询满足要求的总记录数
		$Page = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('wuliuyunli')
		-> field($sql_field)
		-> where($sql_where)
		-> order($sql_order)
		-> limit($Page->firstRow.','.$Page->listRows)
		-> select();
		
		
		$this->assign('list',$list);
		
		
		
		
		$this->assign('d_zhaomeiwuliu','active');
		$this->assign('wuliuziyuan','active');
		$this->display();
	}

	/*货物资源*/
	public function huowuziyuan(){

		
		
		
		$this->assign('d_zhaomeiwuliu','active');
		$this->assign('huowuziyuan','active');
		$this->display();
	}

    /*我要合作*/
    public function hezuo(){
    	
    	/* 企业物流 */
    	
    	$type     = $this->_post('qtype');
    	$name     = $this->_post('qname');
    	$phone    = $this->_post('qphone');
    	$company  = $this->_post('qcompany');
    	$qcomment = $this->_post('qcomment');
    	$fabu     = $this->_post('submit');
    	
    	if($fabu!='') {
    		//用户提交了数据，对数据进行处理
    			
    		
    		if($type=='') {
    			echo "<script>alert('请填写运输的类型！');history.go(-1);</script>";
    			$this -> error('请填写运输的类型！');
    	
    	
    		}
    		if($name=='') {
    			echo "<script>alert('请填写公司名称！'); history.go(-1);</script>";
    			$this->error('请填写公司名称！');
    		}
    		
    		if($phone=='') {
    			echo "<script>alert('请填写联系电话！'); history.go(-1);</script>";
    			$this->error('请填写联系电话！');
    		}
    			
    		
    			
    		$data = array(
    				'qtype'     => $type,
    				'qname'     => $name,
    				'qphone'    => $phone,
    				'qcompany'  => $company,
    				'addtime'   => time(),
    		);
    			
    		$Model=new Model();
    		$id = $Model->table('qiyewuliu')->add($data);
    			
    			
    		if($id){
    			echo "<script>alert('提交成功！'); </script>";
    			//$this->success('提交成功！');
    				
    		}else{
    			echo "<script>alert('提交失败！'); history.go(-1);</script>";
    			//$this->success("提交失败！");
    		}
    	}
    	
    	/*  个人物流*/
    	/* $type     = $this->_post('gtype');
    	$name     = $this->_post('gpname');
    	$phone    = $this->_post('gphone');
    	$chepai  = $this->_post('gnum');
    	$gweight = $this->_post('gweight');
    	$gcomment = $this->_post('gcomment');
    	$fabu     = $this->_post('fabu');
    	
    	if($fabu!='') {
    		//用户提交了数据，对数据进行处理
    		 
    	
    		if($type=='') {
    			echo "<script>alert('请填写运输的类型！');history.go(-1);</script>";
    			$this -> error('请填写运输的类型！');
    			 
    			 
    		}
    		if($name=='') {
    			echo "<script>alert('请填写公司名称！'); history.go(-1);</script>";
    			$this->error('请填写公司名称！');
    		}
    	
    		if($phone=='') {
    			echo "<script>alert('请填写联系电话！'); history.go(-1);</script>";
    			$this->error('请填写联系电话！');
    		}
    		
    		$data = array(
    				'gtype'     => $type,
    				'gpname'     => $name,
    				'gphone'    => $phone,
    				'gnum'      => $chepai,
    				'gweight'   => $gweight,
    				'gcomment'   => $gweight,
    				'addtime'   => time(),
    		);
    		
    		$Model=new Model();
    		$id = $Model->table('gerenwuliu')->add($data);
    		 
    		 
    		if($id){
    			echo "<script>alert('提交成功！'); </script>";
    			//$this->success('提交成功！');
    		
    		}else{
    			echo "<script>alert('提交失败！'); history.go(-1);</script>";
    			//$this->success("提交失败！");
    		}
    	} */
    	
    	
    	$this->assign('d_zhaomeiwuliu','active');
    	$this->assign('hezuo','active');
    	$this->display();
    }

	}