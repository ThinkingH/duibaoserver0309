<?php
require_once('./HyDb.php');

//数据库初始化
$HyDb = new HyDb();

$sqldata  = "select id,typeid,contenttime,title,content,y_url from zt_wx_news order by intorder desc limit 100";
$listdata = $HyDb->get_all($sqldata);

/* print_r($listdata); */

?>

<!DOCTYPE HTML>
<html>
<head>
<title>中铁国恒大宗财经新闻</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta name="format-detection" content="telephone=no"/>
<meta name="keywords" content="手机中铁国恒网,中铁国恒首页,新闻资" />
<meta name="description" content="提供24小时全面及时的财经资讯，内容覆盖国内外突发新财经新闻" />
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport" />
<link href="css/common.min.css" type="text/css" rel="stylesheet">
<link href="css/skin.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/style.min.css" />


</head>
<body>
<!-- header 初始 -->
			<!--<img src="picture/logo.png" style="width:100%;margin:10px 0px 0px 0px;">
			<img src="picture/big.jpg" style="width:100%;margin:15px 0px;">
			
			
			
		
		<div style="position:absolute;z-index:999;top:160px;left:67%;">
		<img src="picture/wx.jpg" width="60px" height="60px"/>
		</div> -->
		
		<div style="width:100%;padding:0px;margin:0px;position: relative;">
		<img src="picture/logo.png" style="width:100%;margin:10px 0px 0px 0px;">
		<img src="picture/big.jpg" style="width:100%;margin:15px 0px;">
		<img src="picture/wx.jpg" height="20%" style="position: absolute; right: 20%; bottom:10%;z-index:999;"/>
		</div>


<script type="text/javascript" src="js/jia.js" charset="utf-8"></script>

</p>

<div class="live_wrap">

<!-- 事件标题 -->

<div class="live_bt">

	<strong>事件直播</strong>
	
	<p class="live_bt_select">
	
	<span class="j_page" id="manualRefresh"><a href="index.php">手动刷新</a></span>
	
	</p>

</div>
			
			<?php 
			
			
			
			foreach($listdata as $keyd => $vald){
				
				$temp = explode('<a',$listdata[$keyd]['content']);
				$content = $temp[0];
				
				if($listdata[$keyd]['typeid']=='1'){
				
					echo '<div class="live_items">';
					echo '<div class="live_list_time">'.substr($listdata[$keyd]['contenttime'],-14).'</div>';
								
					echo '<p class="live_list_t">'.$content.'</p>';
					echo '</div>';
				}else if($listdata[$keyd]['typeid']=='2'||$listdata[$keyd]['typeid']=='3'){
					
					echo '<div class="live_items">';
					echo '<div class="live_list_time">'.substr($listdata[$keyd]['contenttime'],-14).'</div>';
					
					
					echo '<p class="live_list_t">'.$listdata[$keyd]['title'].'</p>';
					/*echo '<p class="live_list_t"><a href="../news/xiangqing.php?id='.$listdata[$keyd]['id'].'">查看详细内容</a></p>';*/
					  echo '<p class="live_list_t"><a  href="/newslist/xiangqing.php?id='.$listdata[$keyd]['id'].'" style="color:orange;">查看详细内容</a></p>';
					echo '</div>';
				}
			}
			?>
			
			
			
			
</body></html>