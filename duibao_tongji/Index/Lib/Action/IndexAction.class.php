<?php
/* 
 * 首页信息模块
 */

class IndexAction extends Action {
	
	//首页
	public function index(){
		
		//判断是否登录
		if(!session('adminUser')){
			 $this->redirect('__APP__/Login/index');
		}
		
		$session = session('adminUser');
		$this->assign('username',$session[0]['xingming']);
		
		
		$this->display();
	}
	
	
	public function home(){
		
		$this->display();
	}
	
	//首页展示数据
	public function ajax_home(){
		
		
		$callback=isset($_GET['callback'])?$_GET['callback']:'';
		
		$Model = new Model();
		
		//今日新增用户总数
		$addusersql  = "select count(*) as taddnum from xb_user where 
				create_datetime>='".date('Y-m-d 00:00:00')."' and 
				create_datetime<='".date('Y-m-d 23:59:59')."' and is_lock='1' ";
		$adduserlist = $Model->query($addusersql);
		
		//今日新增临时用户总数
		$temp_addusersql  = "select count(*) as taddnum from xb_temp_user 
							where create_datetime>='".date('Y-m-d 00:00:00')."' 
							and create_datetime<='".date('Y-m-d 23:59:59')."' ";
		$temp_adduserlist = $Model->query($temp_addusersql);
		
		//今日新增临时和正式总数
		$sumperson=$adduserlist[0]['taddnum']+$temp_adduserlist[0]['taddnum'];
		
		if($sumperson>0){
			$taddusernum = $sumperson;
		}else{
			$taddusernum = '0';
		}
		
		//昨天新增正式用户
		$yaddusersql  = "select count(*) as yaddnum from xb_user 
						where create_datetime>='".date("Y-m-d 00:00:00",time()-86400)."' 
						and create_datetime<='".date("Y-m-d 23:59:59",time()-86400)."' 
						and is_lock='1' ";
		$yadduserlist = $Model->query($yaddusersql);
		
		//昨日新增临时用户
		$temp_yaddusersql  = "select count(*) as yaddnum from xb_temp_user 
							where create_datetime>='".date("Y-m-d 00:00:00",time()-86400)."'
							and create_datetime<='".date("Y-m-d 23:59:59",time()-86400)."'  ";
		$temp_yadduserlist = $Model->query($temp_yaddusersql);
		
		//昨日新增总数
		$yer_person = $yadduserlist[0]['yaddnum'] + $temp_yadduserlist[0]['yaddnum'];
		
		if($yer_person>0){
			$yaddusernum = $yer_person;
		}else{
			$yaddusernum='0';
		}
		
		
		
		//正式用户总人数
		$numsql  = "select count(*) as allnum from xb_user ";
		$numlist = $Model->query($numsql);
		
		
		if($numlist[0]['allnum']>0){
			$usernum = $numlist[0]['allnum'];
		}else{
			$usernum = '0';
		}
		
		
		
		//今日启动次数
		$tqidongnum = '0';
		//昨日启动次数
		$yqidongnum = '0';
		
		
		//今日下载次数
		$tdownnum = $adduserlist[0]['taddnum'];
		//昨日下载次数
		$ydownnum = $yadduserlist[0]['yaddnum'];
		
		
		//今日活跃用户
		$thuoyuenum = '0';
		//昨日活跃用户
		$yhuoyuenum = '0';
		
		
		$dayarr = array();
		
		$begin = date('Y-m-d', strtotime('-6 days'));
		$end   = date('Y-m-d');
		
		$daynum_sql  = "SELECT DATE_FORMAT(create_datetime,'%Y-%m-%d') as day ,count(*) as num FROM `xb_user` 
						where create_datetime>='".$begin."' and create_datetime<='".$end."' 
						group by DATE_FORMAT(create_datetime,'%Y-%m-%d')";
		$daynum_list = $Model->query($daynum_sql);//新增会员趋势
		
		
		foreach ($daynum_list as $keys => $vals){
		
			$dayarr[$daynum_list[$keys]['day']] =  $daynum_list[$keys]['num'];
		}
		
		$daysum = array();
		for($i=strtotime($begin);$i<=strtotime($end);$i=$i+24*3600){
			$brr= empty($dayarr[date('Y-m-d',$i)]) ? '0' : $dayarr[date('Y-m-d',$i)];
			$day= date('Y-m-d',$i);
		
			$arr = array($day,$brr);
		
			array_push($daysum,$arr);
		}
		
		$yesterday = array(
				'addusernum' => $yaddusernum,//昨日新增用户
				'qidongnum'  => $yqidongnum, //启动次数
				'downnum'   =>  $ydownnum,    //下载次数
				'huoyuenum'  => $yhuoyuenum,  //活跃用户
		);
		
		$today   = array(
				'addusernum' => $taddusernum,//今日新增用户
				'qidongnum'  => $tqidongnum, //启动次数
				'downnum'   =>  $tdownnum,    //下载次数
				'huoyuenum'  => $thuoyuenum,  //活跃用户
		);
		
		
		
		$daynumarr = array(
				'allnum'     => $usernum,   //用户总人数
				'daynum'     => $daysum,     //每日新增用户
				'yesterday' => $yesterday,//昨日数据
				'today'    => $today,
		);
		
		$returnarr = array(
				'returncode'=> '1',
				'returnmsg' => '数据获取成功',
				'list'      => $daynumarr
					
		);
		
		echo  $callback."(".json_encode($returnarr).")";
		
		
		
	}
	
	
	

}