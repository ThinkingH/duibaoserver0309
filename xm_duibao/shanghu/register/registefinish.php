<?php
header('Content-Type:text/html;charset=utf-8');
//文件的引入
require_once("../lib/c.core.php");

//入库链接
$url = 'http://xbapp.xinyouxingkong.com/admin/enter/';

//数据库的初始化
$HyDb = new HyDb(); 
$btnSubmit   = isset($_POST['btnSubmit'])?$_POST['btnSubmit']:'';
$company     = isset($_POST['company_name'])?$_POST['company_name']:'';
//$address  = isset($_POST['zitiaddress'])?$_POST['zitiaddress']:'';
$comaddress  = isset($_POST['company_address'])?$_POST['company_address']:'';
//$contacts = isset($_POST['company_contacts'])?$_POST['company_contacts']:'';
$contact    = isset($_POST['company_contact'])?$_POST['company_contact']:'';
$email      = isset($_POST['email'])?$_POST['email']:'';
$phone      = isset($_POST['phone'])?$_POST['phone']:'';
$yanzhnegma      = isset($_POST['yanzhnegma'])?$_POST['yanzhnegma']:'';


$passwd = '123456';
//session
$HySession = new HySession();
$code = $HySession->get('code');

$HySession->set('username',$phone);

if($btnSubmit!=''){
	
	 if($company==''){
		echo "<script type='text/javascript'>alert('公司名称不能为空！');history.go(-1);</script>";
		exit;
	}
	
	if($comaddress==''){
		echo "<script type='text/javascript'>alert('公司地址不能为空！');history.go(-1);</script>";
		exit;
	}
	
	if($contact==''){
		echo "<script type='text/javascript'>alert('联系人不能为空！');history.go(-1);</script>";
		exit;
	}
	
	if($email==''){
		echo "<script type='text/javascript'>alert('邮箱不能为空！');history.go(-1);</script>";
		exit;
	} 
	
	/* if($picurl==''){
		echo "<script type='text/javascript'>alert('营业执照不能为空！');</script>";
		exit;
	} */
	
	
	 if($phone==''){
		echo "<script type='text/javascript'>alert('手机号不能为空！');history.go(-1);</script>";
		exit;
	}
	
	if($yanzhnegma!=$code){
		echo "<script type='text/javascript'>alert('验证码不正确！');history.go(-1);</script>";
		exit;
	}
	
	
	//地址经纬度的转换
	$urlj = 'http://api.map.baidu.com/geocoder?address=urlencode('.$comaddress.')&output=json&key=WPzUoVnSMWZXrUuSR5Vs22Cd17yhCZeD';
	$data = HyItems::vget($urlj);
	
	$truepath = json_decode($data['content'], true);
	
	if($truepath['status']=='OK'){//请求成功
		
		$lat = $truepath['result']['location']['lat'];
		$lng = $truepath['result']['location']['lng'];
	}else{
		$lat='';
		$lng='';
	}
	
	//判断手机号是否注册过
	$phoneselectsql  = "select id from shop_site where pay='1' and phone='".$phone."' or company='".$company."'";
	$phoneselectlist = $HyDb->get_row($phoneselectsql);
	if($phoneselectlist['id']>0){
		echo "<script type='text/javascript'>alert('用户已注册！');history.go(-1);</script>";
		exit;
	}
	
	//数据的入库操作
	$insertsql = "insert into shop_site (pay,flag,checkstatus,lianxiren,phone,username,password,
										company,address,email,create_datetime,lat,lng) values
										('0','1','1','".$contact."','".$phone."','".$phone."','".md5($passwd)."',
										'".$company."','".$comaddress."','".$email."','".date('Y-m-d H:i:s')."','".$lat."','".$lng."')";
	
	$insertlist = $HyDb->execute($insertsql);
	
	//读取商户id,未支付
	$seluserid = "select id from shop_site where pay='0' and phone='".$phone."' order by create_datetime desc limit 1 ";
	$siteid = $HyDb->get_all($seluserid);
	
	$geturl = SITEPAYURL.'?userid='.$siteid[0]['id'];
	
	$res = HyItems::vget ( $geturl );
	
	$content = isset ( $res ['content'] ) ? $res ['content'] : '';
	
	$jsonarr = json_decode ( $content, 1 );
	
	$rr_returncode = isset ( $jsonarr ['returncode'] ) ? $jsonarr ['returncode'] : '';
	$rr_returnmsg = isset ( $jsonarr ['returnmsg'] ) ? $jsonarr ['returnmsg'] : '';
	$rr_sj_orderid = isset ( $jsonarr ['myorderid'] ) ? $jsonarr ['myorderid'] : '';
	$rr_myorderid = isset ( $jsonarr ['siteorderid'] ) ? $jsonarr ['siteorderid'] : '';
	$rr_jumpurl = isset ( $jsonarr ['h5url'] ) ? $jsonarr ['h5url'] : '';
	
	// 	print_r($content);
	
	if ($rr_returncode == 100 && $rr_jumpurl != '') {
		echo "<script>window.location.href='".$rr_jumpurl."';</script>";
		exit;
	
	} else {
		exit ('error,订单创建失败，请稍后重试' );
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/* $content111 = json_encode($res);
	file_put_contents('1.txt',$content111);
	
	print_r($res); */
	
	//exit;
	
	
	
	
	
	
	
	
	/* //插入数据后进行支付
	if($insertlist===true){//
		//window.wxc.xcConfirm("注册成功！", window.wxc.xcConfirm.typeEnum.confirm);
		echo "<script type='text/javascript'>alert('注册成功！');window.location.href='http://xbapp.xinyouxingkong.com/admin_shop/admin_y.php';</script>";
		//header("Location:http://xbapp.xinyouxingkong.com/admin_shop/admin_y.php");
		exit;
	}else{
		echo "<script type='text/javascript'>alert('用户注册失败！');</script>";
		exit;
	} */
	
	
}

?>


