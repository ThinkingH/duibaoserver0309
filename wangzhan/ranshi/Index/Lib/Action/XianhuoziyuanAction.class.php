<?php
// 本类由系统自动生成，仅供测试用途

class XianhuoziyuanAction extends Action {
	
	
	private $phonename = '';
	private $phone400  = '****-***-***';
	
	
	
	public function product(){
		
		//1-烟煤，2-无烟煤 ，3-炼焦煤, 4-其他
		$mei_type = $this->_get('mtype');
		
		
		$sql_field = 'id,m_name,m_type,m_diweirezhi,m_shuliang,m_jiage,m_jiaohuodi,c_quanliufen';
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
		$count = $Model -> table('xianhuoziyuan')
						-> where($sql_where)
						-> count();// 查询满足要求的总记录数
		$Page = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$this->assign('page',$show);// 赋值分页输出
		
		//执行SQL查询语句
		$list  = $Model -> table('xianhuoziyuan')
						-> field($sql_field)
						-> where($sql_where)
						-> order($sql_order)
						-> limit($Page->firstRow.','.$Page->listRows)
						-> select();
		
		
		$this->assign('list',$list);
		$this->assign('phone400',$this->phone400);
		$this->assign('d_xianghuoziyuan','active');
		
		$this->display();
	}
	
	
	
	
	/*查看详情页面*/
	public function  chakanxiangqing(){

		$id=$this->_get('id');
		//print_r($id);

		if($id==''){
			$this->error('非法操作');
		}
		
       $Model=new Model();
       $sql_cha="select * from xianhuoziyuan where id='".$id."' limit 1";
       $list_cha=$Model->query($sql_cha);
       $this->assign('list_cha',$list_cha[0]);

       $this->assign('d_xianghuoziyuan','active');
       $this->display();

	}
	
	
	
	
	public function fabu(){
		
		/*发布需求页面  */
		$sub        = $this->_post('fabu'); //提交按钮
		$mtype      = $this->_post('mtype');           //煤炭类型
		$mname      = $this->_post('mname'); 
		$rezhi      = $this->_post('calorific');
		$liufen     = $this->_post('ads');
		$shuifen    = $this->_post('shuifen');
		$neishuifen = $this->_post('neishuifen');
		$huifa      = $this->_post('huifafen');
		$huifen     = $this->_post('huifen');
		$didian     = $this->_post('didian');
		$shuliang   = $this->_post('ton');
		$jigou      = $this->_post('inspectionagency'); //
		$beizhu     = $this->_post('releasecomment');
		
		
		
		if($sub!='') {
			//用户提交了数据，对数据进行处理
			
			
				
			
			
			 if($mtype=='') {
				echo "<script>alert('请填写煤炭名称');history.go(-1);</script>";
				$this -> error('请填写煤炭名称');
				
				
			} 
			if($mname=='') {
				echo "<script>alert('请填写煤炭品种！'); history.go(-1);</script>";
				$this->error('请填写煤炭品种！');
			}
			if($didian=='') {
				echo "<script>alert('请填写交货地！'); history.go(-1);</script>";
				$this->error('请填写交货地！');
			}
			
			if($shuliang=='') {
				echo "<script>alert('请填写数量！'); history.go(-1);</script>";
				$this->error('请填写数量！');
			}
			
			$data = array(
					'mtype'        => $mtype,
					'mname'        => $mname,
					'rezhi'        => $rezhi,
					'liufen'       => $liufen,
					'shuifen'      => $shuifen,
					'neishuifen'   => $neishuifen,
					'huifa'        => $huifa,
					'huifen'       => $huifen,
					'didian'       => $didian,
					'shuliang'     => $shuliang,
					'jigou'        => $jigou,
					'beizhu'       => $beizhu,
					'addtime'      => time(),
			);
			
			$Model=new Model();
			$id = $Model->table('fabuxuqiu')->add($data);
			
			
			if($id){
				echo "<script>alert('发布成功！'); </script>";
				//$this->success('留言成功');
				 
			}else{
				echo "<script>alert('发布失败！'); history.go(-1);</script>";
				$this->success("发布失败");
			}
		}else {
			//用户未提交数据，输出提交页面
			//不作数据操作判断处理
			
			
			
			
		}
		$this->assign('d_xianghuoziyuan','active');
		$this->display();
		
	}
	


	
}