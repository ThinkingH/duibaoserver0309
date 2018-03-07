<?php
header('Content-Type:text/html;charset=utf-8');
//文件的引入
require_once("../lib/c.core.php");
//数据库的初始化
$HyDb = new HyDb();

//获取相应的参数
$title = isset($_POST['title'])?$_POST['title']:'';
$price = isset($_POST['price'])?$_POST['price']:'';
$score = isset($_POST['score'])?$_POST['score']:'';
$goods_sn = isset($_POST['goods_sn'])?$_POST['goods_sn']:'';
$kucun = isset($_POST['kucun'])?$_POST['kucun']:'';
$miaoshu = isset($_POST['maoshu'])?$_POST['maoshu']:'';
$btnSubmit = isset($_POST['btnSubmit'])?$_POST['btnSubmit']:'';

/* $file = isset($_FILES["file"])?$_FILES["file"]:'';

if(!empty($file)){

	if((($_FILES["file"]["type"] == "image/gif") ||($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 2000000000000000000) ){

		if($_FILES["file"]["error"] > 0){

			echo "<script type='text/javascript'>alert('商品图片上传失败！');</script>";
			exit;
		}else{
			move_uploaded_file($_FILES["file"]["tmp_name"],"upload/".$_FILES["file"]["name"]);
			$pic = "http://xbapp.xinyouxingkong.com/admin/enter/upload/".$_FILES["file"]["name"];
		}

	}

} */

$picurl = isset($pic)?$pic:'';

if($btnSubmit!=''){
	
	if($title==''){
		echo "<script type='text/javascript'>alert('商品名称不能为空！');</script>";
		exit;
	}
	
	if($price==''){
		echo "<script type='text/javascript'>alert('商品价格不能为空！');</script>";
		exit;
	}
	if($score==''){
		echo "<script type='text/javascript'>alert('兑换积分不能为空！');</script>";
		exit;
	}
	if($goods_sn==''){
		echo "<script type='text/javascript'>alert('商品编号不能为空！');</script>";
		exit;
	}
	if($kucun==''){
		echo "<script type='text/javascript'>alert('商品库存不能为空！');</script>";
		exit;
	}
	if($miaoshu==''){
		echo "<script type='text/javascript'>alert('商品描述不能为空！');</script>";
		exit;
	}
	
	$HySession = new HySession();
	
	$username=$HySession->get('username');
	
	//读取商户的编号
	$bianhaosql = "select id from shop_site where username='".$username."'";
	$bianhaolist = $HyDb->get_row($bianhaosql);
	
	if($bianhaolist['id']>0){
		$siteid = $bianhaolist['id'];
	}else{
		$siteid = '';
	}
	
	//数据的入库操作
	$date = date('Y-m-d H:i:s');
	$insertsql = "insert into shop_product (siteid,status,onsales,goods_sn,name,price,score,mainpic,feetype,start_datetime,kucun,miaoshu) values 
			('".$siteid."','3','2','".$goods_sn."','".$title."','".$price."','".$score."','".$picurl."','1','".$date."','".$kucun."','".$miaoshu."')";
	
	$insertlist = $HyDb->execute($insertsql);
	
	if($insertlist===true){
	
		echo "<script type='text/javascript'>alert('商品上传成功,等待审核！');window.location.href='http://xbapp.xinyouxingkong.com/admin_shop/admin_y.php';</script>";
		exit;
	}else{
		echo "<script type='text/javascript'>alert('商品上传失败！');</script>";
		exit;
	}
	
}

?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>兑宝商户后台</title>
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/register.css">
<script src="js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/xcConfirm.css"/>
<script src="js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
<script src="js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>

</head>
<body>
<div id="dowebok">
  <div class="section login">
    <div class="headt" style="background:#000000;padding: 10px 0;height:30px;">
      <div class="container">
        <div class="row">
          <div class="headt_left"><img src="picture/icon.png" width="30px"></div>
          <div class="headt_right">
            <div class="lianx pull-left"><!-- <span class="tel_bj"></span> --></div>
          </div>
        </div>
      </div>
    </div>
    <div class="wrap" >
      <div class="container">
        <section class="row">
          <div class="generic">
            <p class="font6">上传商品信息</p>
            <form action="" method="post" onsubmit="return check()">
            <div class="form-group int_line">
              <label for="name" class="col-xs-3 col-sm-1  col-md-1   control-label"><em>*</em>商品名称</label>
              <div class="col-xs-9 col-sm-6 col-md-6    ">
                <div class="fg-line">
                  <input type="text" class="form-control"  name="title" id="title" placeholder="请填写商品名称">
                </div>
                <div class="message" style="color:#ff0000;font-size:10px"></div>
              </div>
              <label class="col-xs-12 col-sm-5  control-label color999" ></label>
            </div>
            
            <div class="form-group int_line">
              <label for="name" class="col-xs-3 col-sm-1  col-md-1   control-label"><em>*</em>商品价格</label>
              <div class="col-xs-9 col-sm-6 col-md-6    ">
                <div class="fg-line">
                  <input type="text" class="form-control"  name="price" id="price" placeholder="请填写商品价格">
                </div>
                <div class="message" style="color:#ff0000;font-size:10px"></div>
              </div>
              <label class="col-xs-12 col-sm-5  control-label color999" ></label>
            </div>
            
            <div class="form-group int_line">
              <label for="name" class="col-xs-3 col-sm-1  col-md-1   control-label"><em>*</em>兑换积分</label>
              <div class="col-xs-9 col-sm-6 col-md-6    ">
                <div class="fg-line">
                  <input type="text" class="form-control"  name="score" id="score" placeholder="请填写兑换积分">
                </div>
                <div class="message" style="color:#ff0000;font-size:10px"></div>
              </div>
            </div>
            
            <div class="form-group int_line">
              <label for="name" class="col-xs-3 col-sm-1  col-md-1   control-label"><em>*</em>商品编号</label>
              <div class="col-xs-9 col-sm-6 col-md-6    ">
                <div class="fg-line">
                  <input type="text" class="form-control"  name="goods_sn" id="goods_sn" placeholder="请填写商品编号">
                </div>
                <div class="message" style="color:#ff0000;font-size:10px"></div>
              </div>
            </div>
           
             <div class="form-group int_line">
              <label for="name" class="col-xs-3 col-sm-1  col-md-1   control-label"><em>*</em>库存</label>
              <div class="col-xs-9 col-sm-6 col-md-6    ">
                <div class="fg-line">
                  <input type="text" class="form-control"  name="kucun" id="kucun" placeholder="请输入库存">
                </div>
                <div class="message" style="color:#ff0000;font-size:10px"></div>
              </div>
            </div>
            
              <div class="form-group int_line">
              <label for="name" class="col-xs-3 col-sm-1  col-md-1   control-label"><em>*</em>商品描述</label>
              <div class="col-xs-9 col-sm-6 col-md-6    ">
                <div class="fg-line">
                <textarea rows="5" cols="25"  name="maoshu" id="maoshu" placeholder="请输入商品描述"> </textarea>
                  <!-- <input type="text" class="form-control"  name="maoshu" id="maoshu" placeholder="请输入商品描述"> -->
                </div>
                <div class="message" style="color:#ff0000;font-size:10px"></div>
              </div>
            </div>
            
             <!-- <div class="form-group int_line">
              <label for="name" class="col-xs-3 col-sm-1  col-md-1   control-label"><em>*</em>商品图片</label>
              <div class="col-xs-9 col-sm-6 col-md-6 " style="padding-top:20px;">
                <div class="fg-line">
               <input id="file-0" class="file" type="file" multiple data-min-file-count="1" name="file"> 
                </div>
                <div class="message" style="color:#ff0000;font-size:10px"></div>
              </div>
            </div> -->
           
            <div class="form-group int_line">
               <div class="col-xs-12" style="padding-left: 15px;">
               <input class="btn btn-large btn-primary" name="btnSubmit" id="btnSubmit" type="submit" value="上传" style="width:300px; padding:6px 200; font-size:1.6rem; text-align:center; margin-bottom:12px;background-color:#25a48d;border-color:#25a48d; outline:none">
                <!--<button type="submit" style="width:300px; padding:6px 200; font-size:1.6rem; text-align:center; margin-bottom:12px;background-color:#25a48d;border-color:#25a48d; outline:none" class="btn btn-primary " onclick="">申请</button>--> 
               </div>
            </div>
            </form>
          </div>
          <!--分线一下--> 
        </section>
      </div>
    </div>
    
  </div>
</div>

</body>
<script>
  //表单提交检查
  function check(){

	//商品名称
    title = $("#title").val().trim();
    if(title == ''){
		window.wxc.xcConfirm("商品名称不能为空！", window.wxc.xcConfirm.typeEnum.confirm);
		return false;
    }

    //商品价格
    price = $("#price").val().trim();
    if(price == ''){
    	window.wxc.xcConfirm("商品价格不能为空！", window.wxc.xcConfirm.typeEnum.confirm);
      return false;
    }
    
    //商品积分
    var score_value = $("#score").val().trim();
    if( score_value== ''){
      window.wxc.xcConfirm("商品积分不能为空！", window.wxc.xcConfirm.typeEnum.confirm);
      return false;
    }
    
    //商品编号
    var goods_sn = $("#goods_sn").val().trim();
    if(goods_sn == ''){
      window.wxc.xcConfirm("商品编号不能为空！", window.wxc.xcConfirm.typeEnum.confirm);
      return false;
    }
    
    //库存
    var kucun_value = $("#kucun").val().trim();
    if(kucun_value == ''){
      window.wxc.xcConfirm("库存不能为空！", window.wxc.xcConfirm.typeEnum.confirm);
      return false;
    }
    //商品描述
    var maoshu_value = $("#maoshu").val().trim();
    if(maoshu_value == ''){
      window.wxc.xcConfirm("商品描述不能为空！", window.wxc.xcConfirm.typeEnum.confirm);
      return false;
    }
    
    
  }


 
</script>
</html>