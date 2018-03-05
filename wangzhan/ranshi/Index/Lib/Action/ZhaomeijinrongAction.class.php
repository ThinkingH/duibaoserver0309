<?php
class ZhaomeijinrongAction extends Action {
    

    /*金融产品*/
	public function jinrongchanpin(){
		
		
		
		
		
		$this->assign('d_zhaomeijinrong','active');
		$this->assign('jinrongchanpin','active');
		$this->display();
	}

	/*快速申请*/
	public function shenqing(){

		
		$cname   = $this->_post('company');
		$money   = $this->_post('money');
		$pname   = $this->_post('pname');
		$ptel    = $this->_post('phone');
		$comment = $this->_post('releasecomment');
		$sub     =$this->_post('fabu');
		
		if($sub!=''){
		
			if($cname==''){
		
				echo "<script>alert('请填写公司名称！');history.go(-1);</script>";
				$this -> error('请填写公司名称！');
			}
		
			if($money==''){
		
				echo "<script>alert('请填写融资金额！');history.go(-1);</script>";
				$this -> error('请填写融资金额！');
		
			}
		
			if($pname==''){
				echo "<script>alert('请填写联系人姓名！');history.go(-1);</script>";
				$this -> error('请填写联系人姓名！');
			}
		
			if($ptel==''){
		
				echo "<script>alert('请填写联系人电话！');history.go(-1);</script>";
				$this -> error('请填写联系人电话！');
			}
		
			$data = array(
		
					'company'           => $cname,
					'money'             => $money,
					'pname'             => $pname,
					'ptel'              => $ptel,
					'releasecomment'    => $comment,
					'addtime'           => time(),
			);
		
			$Model = new Model();
			$id = $Model->table('kuaisushenqing')->add($data);
		
			if($id){
				echo "<script>alert('申请成功');window.open('','_self','width=400,height=400,top=100');</script>";
		
		
			}else{
				echo "<script>alert('申请失败！');window.open('','_self','width=400,height=400,top=100');</script>";
		
			}
		}
		
		
		$this->assign('d_zhaomeijinrong','active');
		$this->assign('kuaisushenqing','active');
		$this->display();
	}

    /*申请流程*/
    public function shenqingliucheng(){

    	
    	
    	
    	
    	
    	$this->assign('d_zhaomeijinrong','active');
    	$this->assign('shenqingliucheng','active');
    	$this->display();
    }
   
    /*准备材料*/
    public function zhunbeicailiao(){
    
    	
    	
    	
    	$this->assign('d_zhaomeijinrong','active');
    	$this->assign('zhunbeicailiao','active');
    	$this->display();
    }
	}