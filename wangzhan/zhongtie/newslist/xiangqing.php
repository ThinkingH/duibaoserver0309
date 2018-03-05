<?php

require_once('./HyDb.php');

//数据库初始化
$HyDb = new HyDb();

//获取id
$id = isset($_GET['id'])?$_GET['id']:'';

$sqldata  = "select title,contenttime,content from zt_wx_news where id='".$id."'";
$listdata = $HyDb->get_row($sqldata);



?>


<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="x-rim-auto-match" content="none">
    <title><?php echo $listdata['title'];?></title>
    
    <style type="text/css">
    
    body {
    display: block;
    margin: 0 auto;
	}
    .left{
   		 text-decoration:none;
   		 float:right;
   		 padding-right:50px;
   		 color:orange;
   		 font-size:20px;
    }
    
   article {
	 /*  background: #f3f3f3; */
	  background: #fff;
	  padding-left:5px;
	}
    
    .home article .fullSlide {
  width: 100%;
  height: 15rem;
  display: block;
  overflow: hidden;
  position: relative;
}


.home article .menu .item i {
  height: 6rem;
  width: 100%;
  display: block;
}

.home article .menu .item.nrb {
  border-right: none;
}
.home article .menu .item.nbb {
  border-bottom: none;
}
.home article .menu .item .new {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: red;
  height: 2rem;
  width: 2rem;
  -webkit-border-radius: 1rem;
  -moz-border-radius: 1rem;
  border-radius: 1rem;
  font-size: 1.6rem;
  color: #ffffff;
  font-style: normal;
  line-height: 2rem;
}



.home article .banner {
  width: 100%;
  height: auto;
  overflow: hidden;
}
.home article .banner img {
  width: 100%;
  height: auto;
}
.home article .piece {
  background: #ffffff;
  margin-bottom: .8rem;
  display: block;
  overflow: hidden;
}



.news {
  background-color: #ffffff;
}
.news .list-wrap a {
  display: block;
  height: auto;
  border-bottom: 1px solid #e9e9e9;
  padding: .8rem 0;
}
.news .list-wrap a h2 {
  font-weight: normal;
  font-size: 1.6rem;
  width: 90%;
  height: 2.5rem;
  line-height: 2.5rem;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  padding-left: 1rem;
}
.news .list-wrap a .time {
  color: #cccccc;
  font-size: 1.2rem;
  width: 20rem;
  height: 2rem;
  line-height: 2rem;
  padding-left: 1rem;
}
.news .list-wrap a:active {
  background: #f0f0f0;
}
.news .content .hd {
  padding: 0 1rem;
}
.news .content .hd h2 {
  text-align: center;
  /* line-height: 3rem;
  padding: .5rem 0;
  border-bottom: 1px solid #e9e9e9; */
}
.news .content .hd .message {
  line-height: 2.5rem;
  color: #999;
}
.news .content .hd .message .date,
.news .content .hd .message .for {
  margin-right: 1rem;
}
.news .content .bd .font {
  padding: .5rem 2rem 1rem;
  line-height: 3rem;
  font-size: 1.5rem;
  display: block;
  overflow: hidden;
  word-break: break-all;
  word-wrap: break-word;
}
.news .content .bd .font p {
  text-indent: 2.5rem;
}
.news .content .bd .font img {
  max-width: 90%;
}

 article {
  background: #f3f3f3;
  margin-bottom: 5.5rem;
  padding-left:5px;
} 
 article.no-menu {
  margin-bottom: 0;
} 
    
    
    
    
    </style>
</head>
	<div style="width:100%;padding:0px;margin:0px;position: relative;">
		<img src="picture/logo.png" style="width:100%;margin:10px 0px 0px 0px;">
		<img src="picture/big.jpg" style="width:100%;margin:15px 0px;">
		<img src="picture/wx.jpg" height="20%" style="position: absolute; right: 20%; bottom:10%;z-index:999;"/>
		
		
	</div>
	

<body class="home">

	
            
<article class="no-menu">
    <div class="news">
        <div class="content">
            <div class="hd">
                <h2><?php echo $listdata['title'];?></h2>
                <div class="message">
                    <span class="date">时间:<?php echo $listdata['contenttime'];?></span>
                </div>
            </div>
            <div class="bd">
               <!--  <div class="font"> -->
                 <?php echo $listdata['content'];?>
                 
                 <span><a class="left" href="http://www.chinaresc.com/newslist/index.php">返回</a></span>
                 <br><br> <br><br>
                <!-- </div> -->
            </div>
        </div>
    </div>
</article>


</body>
</html>
