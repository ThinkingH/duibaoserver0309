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
$maoshu = isset($_POST['maoshu'])?$_POST['maoshu']:'';
$btnSubmit = isset($_POST['btnSubmit'])?$_POST['btnSubmit']:'';

$file = isset($_FILES["file"])?$_FILES["file"]:'';

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

}

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
	if($maoshu==''){
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
			('".$siteid."','3','2','".$goods_sn."','".$title."','".$price."','".$score."','".$picurl."','1','".$date."','".$kucun."','".$maoshu."')";
	
	$insertlist = $HyDb->execute($insertsql);
	
	if($insertlist===true){
	
		echo "<script type='text/javascript'>alert('商品上传成功！');window.location.href='http://xbapp.xinyouxingkong.com/admin_shop/admin_y.php';</script>";
		exit;
	}else{
		echo "<script type='text/javascript'>alert('商品上传失败！');</script>";
		exit;
	}
	
}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>商品上传</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
        <script src="js/jquery-2.0.3.min.js"></script>
        <script src="js/fileinput.js" type="text/javascript"></script>
        <script src="js/fileinput_locale_de.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script type="text/javascript">
  	
	  $("document").ready(function(){
	  
	  	$("#btnSubmit").click(function() {
	  	
		  	var title      = $("#title").val();
		  	var price    = $("#price").val();
		  	var score   = $("#score").val();
		  	var goods_sn     = $("#goods_sn").val();
		  	var kucun   = $("#kucun").val();
	
		  	
		  	if(title==''){
			  	 alert('商品名称不能为空！');
			  	 return false;
		  		
		  	}
			
			if(price==''){
		  		alert("商品价格不能为空！");
		  		return false;
		  	}
	
		  	if(!$.isNumeric(price)){
		  		alert('价格填写不正确！');
		  		return false;
			  }
	
			if(score==''){
		  		alert("兑换积分不能为空！");
		  		return false;
		  	}
		  	if(goods_sn==''){
		  		alert("商品编号不能为空！");
		  		return false;
			  }
	
			if(kucun==''){
				
				alert("商品库存不能为空！");
		  		return false;
			}
			  
		  	
		  	
		  	});
	  });
	  		
	  
  
  	</script> 
</head>

<body>

 <div class="container kv-main">
            <div class="page-header">
            <h1>商家基本信息</h1>
            </div>
            <form  action="" method="post" enctype="multipart/form-data">
                <label>商品名称：</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="请在此输入商品名称">
                <br>
                <label>商品价格：</label>
                <input type="text" class="form-control" name="price" id="price" placeholder="请在此输入商品价格">
                <br>
                <label>兑换积分：</label>
                <input type="text" class="form-control" name="score" id="score" placeholder="请在此输入商品价格">
                <br>
                <label>商品编号：</label>
                <input type="text" class="form-control" name="goods_sn" id="goods_sn" placeholder="请在此输入商品编号">
                <br>
                <label>库存：</label>
                <input type="text" class="form-control" name="kucun" id="kucun" placeholder="请在此输入商品库存">
                <br>
                
               <label>商品图片：</label>
                <input id="file-0" class="file" type="file" multiple data-min-file-count="1" name="file">
                <br>
               <label>商品描述：</label>
                <textarea rows="5" cols="50" class="form-control" name="maoshu" id="maoshu" ></textarea>
                <br>
                
             	<input class="btn btn-large btn-primary" name="btnSubmit" id="btnSubmit" type="submit" value="提交">
            </form>
            <br>
           </body>
	<script>
    $("#file-0").fileinput({
        'allowedFileExtensions' : ['jpg', 'png','gif'],
    });
    $("#file-1").fileinput({
        uploadUrl: '#', // you must set a valid URL here else you will get an error
        allowedFileExtensions : ['jpg', 'png','gif'],
        overwriteInitial: false,
        maxFileSize: 1000,
        maxFilesNum: 10,
        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function(filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
	});
    /*
    $(".file").on('fileselect', function(event, n, l) {
        alert('File Selected. Name: ' + l + ', Num: ' + n);
    });
    */
	$("#file-3").fileinput({
		showUpload: false,
		showCaption: false,
		browseClass: "btn btn-primary btn-lg",
		fileType: "any",
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>"
	});
	$("#file-4").fileinput({
		uploadExtraData: {kvId: '10'}
	});
    $(".btn-warning").on('click', function() {
        if ($('#file-4').attr('disabled')) {
            $('#file-4').fileinput('enable');
        } else {
            $('#file-4').fileinput('disable');
        }
    });    
    $(".btn-info").on('click', function() {
        $('#file-4').fileinput('refresh', {previewClass:'bg-info'});
    });
    /*
    $('#file-4').on('fileselectnone', function() {
        alert('Huh! You selected no files.');
    });
    $('#file-4').on('filebrowse', function() {
        alert('File browse clicked for #file-4');
    });
    */
    $(document).ready(function() {
        $("#test-upload").fileinput({
            'showPreview' : false,
            'allowedFileExtensions' : ['jpg', 'png','gif'],
            'elErrorContainer': '#errorBlock'
        });
        /*
        $("#test-upload").on('fileloaded', function(event, file, previewId, index) {
            alert('i = ' + index + ', id = ' + previewId + ', file = ' + file.name);
        });
        */
    });
    $("#input-24").fileinput({
    initialPreview: [
        "<img src='/images/moon.jpg' class='file-preview-image' alt='The Moon' title='The Moon'>",
        "<img src='/images/earth.jpg' class='file-preview-image' alt='The Earth' title='The Earth'>",
    ],
    overwriteInitial: false,
    maxFileSize: 100,
    initialCaption: "The Moon and the Earth"
});
	</script>




</body>
</html>

