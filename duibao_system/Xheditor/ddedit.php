<?php 

$content = $_POST["content"];

$r = file_put_contents('./content/1.txt', $content);

if($r<=0){
	
	echo "<script>alert('数据提交失败！');</script>";
	
}else{
	echo "<script>alert('数据提交成功！');</script>";
}




?>


<!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">

<script type="text/javascript" src="./jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="./xheditor-1.1.14-zh-cn.min.js"></script>


<script type="text/javascript">
$(pageInit);
function pageInit()
{
	$('#changecontent').xheditor({tools:'Source,|,Cut,Copy,Paste,Pastetext,|,Align,List,Outdent,Indent,|,Blocktag,Fontface,FontSize,|,Bold,Italic,Underline,Strikethrough,|,FontColor,BackColor,|,Emot,Table,Removeformat,Link,Unlink,|,Source,Preview,SelectAll,Hr,|,|,Img,|,|',upImgUrl:"./demos/upload.php",upImgExt:"jpg,jpeg,gif,png"});
}
function submitForm(){$('#frmDemo').submit();}
</script>
<script type="text/javascript">
$("document").ready(function(){
	//数据添加确认
	$("#update_submit").click(function() {
		
		if(confirm("您确认要提交数据吗？")) {
			//alert('ok');
		}else {
			return false;
		}
		
	});
	
	
	
	
});


</script>
</head>

<body>

<br/>

<form action="" method="post" >
	
<table class="mainTabled">
	
	
	<tr>
		<td width="200" align="right">内容:</td>
		<td width="800">
			<textarea id="changecontent" name="content" rows="32" cols="120"></textarea>
			
		</td>
	</tr>
	
	
	
</table>
<br/><br/>

<input type="submit" id="update_submit" class="yubutton yuwhite" name="update_submit" style="margin-left:55%;" value="提交" />
<br/><br/>

</form>

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>