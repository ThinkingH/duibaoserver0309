<?php
header('Content-Type:text/html;charset=utf-8');
//文件的引入
require_once("../lib/c.core.php");

//入库链接
$url = 'http://xbapp.xinyouxingkong.com/admin/enter/';

//数据库的初始化
$HyDb = new HyDb(); 
$btnSubmit = isset($_POST['btnSubmit'])?$_POST['btnSubmit']:'';
$company  = isset($_POST['company'])?$_POST['company']:'';
$address  = isset($_POST['address'])?$_POST['address']:'';
$comaddress  = isset($_POST['comaddress'])?$_POST['comaddress']:'';
$contacts = isset($_POST['contacts'])?$_POST['contacts']:'';
$contact = isset($_POST['contact'])?$_POST['contact']:'';
$email    = isset($_POST['email'])?$_POST['email']:'';
$address  = isset($_POST['address'])?$_POST['address']:'';
$phone    = isset($_POST['phone'])?$_POST['phone']:'';

$passwd = '123456';
//session
$HySession = new HySession();

$HySession->set('username',$phone);

$file = isset($_FILES["file"])?$_FILES["file"]:'';

if(!empty($file)){
	
	if((($_FILES["file"]["type"] == "image/gif") ||($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 2000000000000000000) ){
	
		if($_FILES["file"]["error"] > 0){
	
			echo "<script type='text/javascript'>alert('营业执照上传失败！');</script>";
			exit;
		}else{
			move_uploaded_file($_FILES["file"]["tmp_name"],"upload/".$_FILES["file"]["name"]);
			$pic = "http://xbapp.xinyouxingkong.com/admin/enter/upload/".$_FILES["file"]["name"];
		}
	
	}
	
}

$picurl = isset($pic)?$pic:'';

if($btnSubmit!=''){
	
	if($company==''){
		
		echo "<script type='text/javascript'>alert('公司名称不能为空！');</script>";
		exit;
	}
	
	if($comaddress==''){
		echo "<script type='text/javascript'>alert('公司地址不能为空！');</script>";
		exit;
	}
	
	if($contact==''){
		echo "<script type='text/javascript'>alert('联系方式不能为空！');</script>";
		exit;
	}
	
	if($contacts==''){
		echo "<script type='text/javascript'>alert('联系人不能为空！');</script>";
		exit;
	}
	if($email==''){
		echo "<script type='text/javascript'>alert('邮箱不能为空！');</script>";
		exit;
	}
	
	if($picurl==''){
		echo "<script type='text/javascript'>alert('营业执照不能为空！');</script>";
		exit;
	}
	
	
	if($phone==''){
		echo "<script type='text/javascript'>alert('手机号不能为空！');</script>";
		exit;
	}
	
	
	//数据的入库操作
	$insertsql = "insert into shop_site (flag,checkstatus,lianxiren,phone,username,password,company,address,email,bussinelicence1,zitiaddress) values
		('1','1','".$contacts."','".$contact."','".$phone."','".$passwd."','".$company."','".$comaddress."','".$email."','".$picurl."','".$address."')";
	$insertlist = $HyDb->execute($insertsql);
	
	if($insertlist===true){
	
		echo "<script type='text/javascript'>alert('注册成功！');window.location.href='uploadshop.php';</script>";
		exit;
	}else{
		echo "<script type='text/javascript'>alert('用户注册失败！');</script>";
		exit;
	}
	
	
}




?>


<!DOCTYPE html>
<!-- release v4.1.8, copyright 2014 - 2015 Kartik Visweswaran -->
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <title></title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
        <script src="js/jquery-2.0.3.min.js"></script>
        <script src="js/fileinput.js" type="text/javascript"></script>
        <script src="js/fileinput_locale_de.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        
       <script type="text/javascript">
  	
	  $("document").ready(function(){
	
	  
	  	$("#btnSubmit").click(function() {
	  	
	  	var company      = $("#company").val();
	  	var comaddress   = $("#comaddress").val();
	  	var contact   = $("#contact").val();
	  	var contacts  = $("#contacts").val();
	  	var email     = $("#email").val();
	  	var address   = $("#address").val();
	  	var phone   = $("#phone").val();

	  	 var myreg = '/^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/'; 
		  	
	  	
	  	if(company==''){
	  	 alert('公司名称不能为空！');
	  	 return false;
	  		
	  	}
		
		if(comaddress==''){
	  		alert("公司地址不能为空！");
	  		return false;
	  	}

	  	if(!$.isNumeric(contact)|| contact==''){
	  		alert('请填写正确的联系方式！');
	  		return false;
		  }

		if(contacts==''){
	  		alert("联系人不能为空！");
	  		return false;
	  	}
	  	if(email==''){
	  		alert("邮箱不能为空！");
	  		return false;
		  }

		if(!myreg.test(email)){
			
			alert("邮箱格式不正确！");
	  		return false;
		}
		  
	  	
	  	if(!$.isNumeric(phone)||phone.length!=11){
	  		alert('请填写正确的手机号！');
	  		return false;
	  	}
	  	
	  	if(yanzhnegma==''){
	  		alert("请输入验证码!!!！");
			return false;
	  	}
	  	});
	  	
	  	
	  	
	  		
	  //发送验证码	
		$('#sendMessage').click(function(){
			
			//获取手机号
			var phone      = $("#phone").val();
		  	
		  	if(phone==''){
		  		alert('请填写手机号！');
		  		return false;
		  		
		  	}
		  	
		  	if(!$.isNumeric(phone)||phone.length!=11){
		  		alert('请填写正确的手机号！');
		  		return false;
		  		
		  	}
			
				//验证手机号是否注册过
				var errphone='no';
				 $.ajax({
			        url:"ajax_phone.php",
			        data:{phone:phone,type:1},
			        type:'GET',
			        async:false,
			        success:function(data){
						if(data=='phoneerror'){
							errphone = 'nopass';
						}
			        }
				 });
			
			
			if(errphone=='nopass'){
				alert('该手机已经注册过，请登录！'); 
				 return false;
			}
			
			
			//下发验证码
			var errmsg='no';
			var errcode='no';
			 $.ajax({
		        url:"ajax_phone.php",
		        data:{phone:phone,type:2},
		        type:'GET',
		        async:false,
		        success:function(data){
			      
					  if(data=='fail'){
						errcode='codefail';
					} 
					  if(data=='success'){
						errcode='ok';
					} 
					
					alert(data);
					 
		        }
			 }); 

			 if(errcode=='ok'){
				alert('验证码下发成功！');
				return false;
			}
			
			if(errcode=='codefail'){
				alert('短信验证码下发失败！');
				return false;
			}
			
			//使button不可点
			
			$(this).attr('disabled','disabled');
			
			var t = 100;
			
			//启动定时器
			var inte = setInterval(function(){
				$('#sendMessage').html(t+"秒钟之后重新发送");
				t--;
				
				//判断
				if(t<0){
					$('#sendMessage').removeAttr('disabled');
					$('#sendMessage').html("免费获取验证码");
					//清空定时器
					clearInterval(inte);
				}
			},1000)
		
		});
	  });
 
  
  	</script> 
        
    </head>
    <body>
        <div class="container kv-main">
            <div class="page-header">
            <h1>商家基本信息<small></h1>
           <!-- <a href="login.html" class="pull-right"><h5>已有账号,直接登录</h5></a> -->
            </div>
            <form  action="" method="post" enctype="multipart/form-data">
            
               <h3>公司信息</h3>
                 <label>公司名称：</label>
                <input type="text" class="form-control" name="company" id="company" placeholder="请在此输入公司名称">
                <br>
                <label>公司所在地：</label>
                <input type="text" class="form-control" name="comaddress" id="comaddress" placeholder="请在此输入公司所在地">
                <br>
                <label>联系方式：</label>
                <input type="text" class="form-control" name="contact" id="contact" placeholder="请在此输入联系方式">
                <br>
                <label>联系人：</label>
                <input type="text" class="form-control" name="contacts" id="contacts" placeholder="请在此输入联系人">
                <br>
                <label>邮箱：</label>
                <input type="text" class="form-control" name="email" id="email" placeholder="请在此输入邮箱">
                <br><br>
                <label>自提地址：</label>
                <input type="text" class="form-control" name="address" id="address" placeholder="请在此输入自提地址">
                <br><br>
                
                <label>营业执照：</label>
                <input id="file-0" class="file" type="file" multiple data-min-file-count="1" name="file">
               
        		<br>
                <h3>账号信息</h3>
                 <label>账号：</label>
                <input type="text" class="form-control" name="phone" id="phone" placeholder="请在此输入手机号">
                <br>
                <label>验证码：</label>
                
                <input type="text" class="yzm" name="yanzhnegma" id="yanzhnegma" placeholder="请输入验证码"> <button type="button" class="btn btn-info" id="sendMessage">发送验证码</button>
               
                <br><br>
                	<a href="uploadshop.php" class="pull-right">下一步</a>
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
</html>

