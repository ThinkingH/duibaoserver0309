<?php 
/*
 * 用户分析模块
 */
class UserAction extends Action {
	
	
	//版本号渠道号
	public function ajax_list(){
		
		
		$callback=isset($_GET['callback'])?$_GET['callback']:'';
		
		//数据库的初始化
		$Model = new Model();
		
		//渠道名称
		$site_sql  = "select id,name from xb_site_version where flag=1 and biaoshi=2 order by id asc";
		$site_list = $Model->query($site_sql);
		
		//版本号名称
		$version_sql  = "select id,name from xb_site_version where flag=1 and biaoshi=1 order by id asc";
		$version_list = $Model->query($version_sql);
		
		
		$returnarr = array(
				'returncode'=> '1',
				'returnmsg' => '数据获取成功',
				'site'      => $site_list,      //渠道
				'version'   => $version_list,   //版本号
		);
		
		echo $callback."(".json_encode($returnarr).")";
		
			
	}
	
	
	public function ajax_addusers(){
		
		
		//获取参数
		$passday = isset($_GET['passday'])?$_GET['passday']:'';  //查询的天数eg 过去7天  nowday    过去30天 pass_thirty_day     过去60天  pass_sixty_day  过去90天    pass_ninety_day
		
		$starttime = isset($_GET['starttime'])?$_GET['starttime']:'';//开始时间 starttime
		$endtime   = isset($_GET['endtime'])?$_GET['endtime']:'';//结束时间 endtime
		
		$version  = isset($_GET['version'])?$_GET['version']:'';//版本号
		$site     = isset($_GET['site'])?$_GET['site']:'';     //渠道编号
		
		$callback=isset($_GET['callback'])?$_GET['callback']:'';
		
		//数据库的初始化
		$Model = new Model();
		
		$dayarr=array();
		
		if($passday=='nowday'){//过去7天
		
			$begin = date('Y-m-d 00:00:00 ', strtotime('-6 days'));
			$end   = date('Y-m-d 23:59:59');
		
		}else if($passday=='pass_thirty_day'){//过去30天
		
			$begin = date('Y-m-d 00:00:00 ', strtotime('-29 days'));
			$end   = date('Y-m-d 23:59:59');
		
		}else if($passday=='pass_sixty_day'){//过去60天
		
			$begin = date('Y-m-d 00:00:00 ', strtotime('-59 days'));
			$end   = date('Y-m-d 23:59:59');
		
		}else if($passday=='pass_ninety_day'){//过去90天
		
			$begin = date('Y-m-d 00:00:00 ', strtotime('-89 days'));
			$end   = date('Y-m-d 23:59:59');
		}else{
		
			$begin = $starttime.' 00:00:00';
			$end   = $endtime .' 23:59:59';
		}
		
		if(strtotime($end)-strtotime($begin)>1*24*60*60*90){
		
			$returnarr = array(
					'returncode'=> '0',
					'returnmsg' => '查询时间间隔不可以超过90天',
			);
		
			exit($callback."(".json_encode($returnarr).")");
		
		}
		
		//查询操作
		$where_sql = '';
		$canshu_sql='';
		
		if($version==''){//版本信息为空
			$where_sql = '';
			$canshu_sql='';
		}else{
			$where_sql = " and version = '".$version."' ";
			$canshu_sql = ',version';
		}
		
		if($site==''){//渠道编号
			$where_sql .='';
		}else{
			$where_sql .= " and plat_form='".$site." ' "  ;
			$canshu_sql .= ',plat_form';
		}
		
		$daynum_sql  = "SELECT DATE_FORMAT(create_datetime,'%Y-%m-%d') as day, 
						count(*) as num $canshu_sql FROM `xb_user`
						where create_datetime>='".$begin."' and create_datetime<='".$end."' $where_sql 
						 group by DATE_FORMAT(create_datetime,'%Y-%m-%d') $canshu_sql";
		$daynum_list = $Model->query($daynum_sql);
		
		
		$addnum = 0;//总人数
		foreach ($daynum_list as $keys => $vals){
		
			$dayarr[$daynum_list[$keys]['day']] =  $daynum_list[$keys]['num'];
			
			$addnum = $addnum+$daynum_list[$keys]['num'];//7天的总人数
			}
		
		$daysum         = array();//日期+新增人数
		$daysum_precent = array();//日期+新增人数+百分比
		
		//过去30，60，90天的折线数据
		for($i=strtotime($begin);$i<=strtotime($end);$i=$i+24*3600){
		
			$brr= empty($dayarr[date('Y-m-d',$i)]) ? '0' : $dayarr[date('Y-m-d',$i)];//每天的人数
			$day= date('Y-m-d',$i);
			
			if($addnum=='0'){
			$precent = '0.0%';//百分比
		}else{
			$precent = (round($brr/$addnum,3)*100).'%';//百分比
			}
		
		
		
		$arr = array($day,$brr);//天数+人数
		$arr_precent = array($day,$brr,$precent);
		
		array_push($daysum,$arr);//该数组展示每天的新增人数
		}
		
		
		//新增用户明细
		$end1 = date('Y-m-d 23:59:59 ');
		//$begin1=date('Y-m-d 00:00:00',strtotime('-4 days',strtotime($end)));
		$begin1 = date('Y-m-d 00:00:00 ', strtotime('-4 days'));
		
		if(strtotime($end1)<strtotime('+1 days',strtotime($end))){
		
				$end = date('Y-m-d 23:59:59 ');
		}
		
		
		for($i=strtotime($end);$i>=strtotime($begin1);$i=$i-24*3600){
		
			$brr= empty($dayarr[date('Y-m-d',$i)]) ? '0' : $dayarr[date('Y-m-d',$i)];//每天的人数
			$day= date('Y-m-d',$i);
		
			if($addnum=='0'){
			$precent = '0.0%';//百分比
			}else{
			$precent = (round($brr/$addnum,3)*100).'%';//百分比
			}
		
		
			$arr_precent = array($day,$brr,$precent);
			array_push($daysum_precent,$arr_precent);//该数组展示每天的新增人数
		
		}
		
		//最近活动消费
		$detail_sql = "select id,nickname,phone,openid,plat_form,create_datetime from xb_user
						where create_datetime>='".$begin."' and create_datetime<='".$end."' $where_sql
						order by create_datetime desc limit 5";
		$detail_list = $Model->query($detail_sql);
		
		
		foreach ($detail_list as $keys => $vals){
			
			if($detail_list[$keys]['nickname']===null){
				$detail_list[$keys]['nickname']='';
		}
		
		if($detail_list[$keys]['phone']===null){
		
			$detail_list[$keys]['phone']='';
		}else{
			$detail_list[$keys]['phone']=substr($detail_list[$keys]['phone'],0,3).'****'.substr($detail_list[$keys]['phone'],-3);
		}
		
		if($detail_list[$keys]['openid']===null){
		$detail_list[$keys]['openid']='';
		}
		
		if($detail_list[$keys]['plat_form']===null){
		$detail_list[$keys]['plat_form']='';
		}
		}
		
		
	$returnarr = array(
		'returncode'=> '1',
		'returnmsg' => '数据获取成功',
		'daynumlist' => $daysum,      //折线图
		'prelist'    => $daysum_precent,//新增用户列表
		'detaillist' => $detail_list
		
		);
		
		exit($callback."(".json_encode($returnarr).")");
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
}


















?>
