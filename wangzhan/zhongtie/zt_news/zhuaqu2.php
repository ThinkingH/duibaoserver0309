<?php

// 页面超时设置
set_time_limit(600);

//设置时区
date_default_timezone_set('Asia/Shanghai');


require_once('./HyItems.php');
require_once('./HyDb.php');


$tmppathurl = './tmp/zhuaqu2_temp';

$qqid = file_get_contents($tmppathurl);
if($qqid=='') {
	$qqid = 429600;
}
$qqid = $qqid-1;
$newsurl = 'http://live.sina.com.cn/zt/api/f/get/finance/globalnews1/index.htm';
$newsurl .= '?format=json&callback=yu&id='.$qqid.'&tag=0&pagesize=50&dire=f&dpc=1&_='.time().mt_rand(100,999);


$res = HyItems::vget($newsurl);
$content = $res['content'];

$content = substr($content,7);
$content = substr($content,0,-14);

$jsonarr = json_decode($content,1);


$data_arr = isset($jsonarr['result']['data'])?$jsonarr['result']['data']:array();


$mesage_arr = array();

foreach($data_arr as $vald) {
	
	$tt_content = explode('<a',$vald['content']);
	$tt_content1 = $tt_content[0];
	
	$tt_content2 = explode('更多资讯',$tt_content1);
	$tt_content1 = trim($tt_content2[0]);
	
	$temparr = array(
			'id' => $vald['id'],
			'content' => $tt_content1,
			'fabutime' => $vald['created_time'],
			'tag_name' => $vald['tag'][0]['tag_name'],
	);
	
	
	//筛选字段
	$shaixuanziduanarr = array(
			'煤',
			'煤炭',
			'碳',
			'能源',
			'大宗',
			'运输',
			'发电',
			'电力',
			'产能',
			'供暖',
			'港口',
	);
	
	$keys = 'no';
	foreach($shaixuanziduanarr as $vals) {
		if(stristr($tt_content1,$vals)) {
			$keys = 'ok';
			break;
		}
	}
	
	if($keys=='ok') {
		array_push($mesage_arr,$temparr);
	}
	
}



if(count($mesage_arr)>0) {
	//数据库初始化
	$HyDb = new HyDb();
}


$newqqid = isset($mesage_arr[0]['id'])?$mesage_arr[0]['id']:'';
if($newqqid!='') {
	file_put_contents($tmppathurl,$newqqid);
}



foreach($mesage_arr as $valm) {
	$biaoti  = $valm['id'];
	$neirong = $valm['content'];
	$riqi    = date('Y-m-d H:i:s',$valm['fabutime']);
	$y_url   = $valm['tag_name'];
	
	$jiaoyanstring = $biaoti.$riqi.$neirong;
	$md5crc32 = md5($jiaoyanstring).crc32($jiaoyanstring);
	//$intorder = strtotime($riqi).mt_rand(1000,9999);
	$intorder = time().mt_rand(1000,9999);
	
	$sql_pan = "select id from zt_wx_news where typeid='1' and (md5crc32='".$md5crc32."'  or (title='".$biaoti."' and y_url='".$y_url."')) ";
	//$sql_pan = "select id from zt_wx_news where md5crc32='".$md5crc32."' and typeid='1' ";
	$list_pan = $HyDb->get_one($sql_pan);
	
	if($list_pan<=0) {
			
		//typeid 1为纯文本推送，2为链接
		$sql_insert = "insert into zt_wx_news (typeid,intorder,contenttime,title,content,
						y_url,create_datetime,md5crc32) values
						('1','".$intorder."','".$riqi."','".$biaoti."','".$neirong."',
						'".$y_url."','".date('Y-m-d H:i:s')."','".$md5crc32."')";
		$HyDb->execute($sql_insert);
		echo '-';
			
	}else {
		echo '|';
	}
	
	ob_flush();
	flush();
	
}









// echo '<textarea>';

// // print_r($res);
// print_r($mesage_arr);

// echo '</textarea>';




echo 'ok';





