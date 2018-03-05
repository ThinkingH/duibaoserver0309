<?php
class GongsijieshaoAction extends Action{
	
	/*介绍*/
	public function jieshao(){
		
		
		
		
		$this->assign('d_guanyuwomen','active');
		$this->assign('about_jianjie','active');
		$this->display();
	}

   
 
   
   /*仓储服务*/
	public function cangchufuwu(){
		

		
		$this->assign('d_guanyuwomen','active');
		$this->assign('about_cangchu','active');
		$this->display();
	}


	/*金融服务*/
	public function jinrongfuwu(){
		

		$this->assign('d_guanyuwomen','active');
		$this->assign('about_jinrong','active');
		$this->display();
	}


/*物流服务*/
	public function wuliufuwu(){
		
		
        
		$this->assign('d_guanyuwomen','active');
		$this->assign('about_wuliu','active');
		$this->display();
	}
	
}
