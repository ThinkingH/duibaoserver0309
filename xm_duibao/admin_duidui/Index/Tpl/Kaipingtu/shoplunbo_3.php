<?php
require_once('./HyDb.php');

//数据库初始化
$HyDb = new HyDb();

$sqldata  = "select content from xb_lunbotu where picname='7' and biaoshi='1' and flag='1' limit 1";
$listdata = $HyDb->get_row($sqldata);


?>

<!DOCTYPE HTML>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta name="format-detection" content="telephone=no"/>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport" />
</head>



<body>
		
<?php 
echo htmlspecialchars_decode($listdata['content']);
?>

			
</body>

</html>