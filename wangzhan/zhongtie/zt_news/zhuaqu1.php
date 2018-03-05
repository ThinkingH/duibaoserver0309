<?php

// 页面超时设置
set_time_limit(600);

//设置时区
date_default_timezone_set('Asia/Shanghai');



require_once('./HyItems.php');
require_once('./HyDb.php');


$tmppathurl = './tmp/zhuaqu1_temp';


$zixunurl = 'http://www.315dz.com/new/infoQuotesAction_index.html';

$urlarr = array();

for($i=1;$i<=2;$i++) {
	
	$postdata = 'pageVO.toPage='.$i;
	
	
	$res = HyItems::vpost($zixunurl,$postdata);
	$content = $res['content'];
	
	$temp1arr = explode('<div class="f_hqzx_title clearfix"><a href="',$content);
	
	foreach($temp1arr as $valt) {
		if(substr($valt,0,33)=='/new/infoQuotesAction_detail.html') {
			$temp2arr = explode('"',$valt);
			array_push($urlarr,$temp2arr[0]);
		}
	}
	
	
}


if(count($urlarr)>0) {
	//数据库初始化
	$HyDb = new HyDb();
}



foreach($urlarr as $valu) {
	
	$mesurl = 'http://www.315dz.com/'.$valu;
	//echo $mesurl.'<br>';
	$res = HyItems::vget($mesurl);
	$content = $res['content'];
	
	$temp1arr = explode('<iframe width="100%" src="',$content);
	$temp2arr = explode('"',$temp1arr[1]);
	
	$y_url = 'http://www.315dz.com/'.$temp2arr[0];
	
	$res = HyItems::vget($y_url);
	$content = HyItems::hy_tonull($res['content']);
	
	
	$biaoti_temp = explode('<h2 class="tit_01" id="cont_title">',$content);
	$biaoti_temp1 = explode('</h2>',$biaoti_temp[1]);
	$biaoti = trim($biaoti_temp1[0]);
	
	$riqi_temp = explode('<div class="time_lyfl c666" id="cont_riqi">',$content);
	$riqi_temp1 = explode('来源：',$riqi_temp[1]);
	$riqi = trim($riqi_temp1[0]);
	
	$neirong_temp = explode('<div id="frameContent" class="txt_0422">',$content);
	$neirong_temp1 = explode('      </div>',$neirong_temp[1]);
	$neirong = trim($neirong_temp1[0]);
	$neirong = preg_replace("/<a[^>]*>(.*)<\/a>/isU",'${1}',$neirong); //去除a标签
	
// 	echo $biaoti.'<br>';
// 	echo $riqi.'<br>';
// 	echo $neirong.'<br>';
	
	if($biaoti!='' && $riqi!='' && $neirong!='') {
		
		$jiaoyanstring = $biaoti.$riqi.$neirong;
		$md5crc32 = md5($jiaoyanstring).crc32($jiaoyanstring);
		//$intorder = strtotime($riqi).mt_rand(1000,9999);
		$intorder = time().mt_rand(1000,9999);
		
		$sql_pan = "select id from zt_wx_news where md5crc32='".$md5crc32."' and typeid='2' ";
		$list_pan = $HyDb->get_one($sql_pan);
		
		if($list_pan<=0) {
			
			//typeid 1为纯文本推送，2为链接
			$sql_insert = "insert into zt_wx_news (typeid,intorder,contenttime,title,content,
							y_url,create_datetime,md5crc32) values 
							('2','".$intorder."','".$riqi."','".$biaoti."','".$neirong."',
							'".$y_url."','".date('Y-m-d H:i:s')."','".$md5crc32."')";
			$HyDb->execute($sql_insert);
			echo '-';
			
		}else {
			echo '|';
		}
	
	}else {
		echo '#';
	}
	
	ob_flush();
	flush();
	sleep(1);
	
// 	echo '<textarea>';
// 	print_r($content);
// 	echo '</textarea>';
	
// 	exit;
}





echo 'ok';






// echo '<textarea>';
// print_r($urlarr);
// echo '</textarea>';




