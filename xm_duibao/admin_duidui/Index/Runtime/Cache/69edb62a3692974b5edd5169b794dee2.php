<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />

<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>

<script type="text/javascript">
$("document").ready(function(){
    
    $(".add_submit").click(function(){
        
    	var typeid  = $("#typeid").val();
    	var name   = $("#name").val();
    	
    	
    	if(typeid==''){
    		alert('类型不能为空！');
    		return false;
    	}
    	
    	if(name==''){
    		alert('类型名称不能为空！');
    		return false;
    		
    	}
    	
    	
    	 if(confirm("您确认要添加此条数据吗？")) {
             //alert('ok');
         }else {
             return false;
         }
       
    });
    
    
    
    $(".mainTables>tbody>tr>td").hover(function(){
        $(this).parent().children().addClass('yu_mourse_stop_change');
    },function(){
        $(this).parent().children().removeClass('yu_mourse_stop_change');
    });
    
    
});


</script>
</head>

<body>
<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0" >
    <tr height="28"><td background="__PUBLIC__/Images/title_bg1.jpg">
        <font size="-1" ><b>当前位置&nbsp;#&nbsp;商城的配置信息&nbsp;#&nbsp;&nbsp;#&nbsp;商品类型管理</b></font></td></tr>
    <tr><td bgcolor="#b1ceef" height="1"></td></tr>
    <tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<br/>

<a href="__APP__/Shoptype/maintype<?php echo ($yuurl); ?>" class="yubutton yuwhite">返回商品类型查询页面</a>
<br/><br/><br/>


<form action="__APP__/Shoptype/madddata<?php echo ($yuurl); ?>" method="post" enctype="multipart/form-data">
    
<table class="mainTabled">


   <tr>
        <td width="200" align="right">是否开启：</td>
        <td width="900">
            <select name="flag" id="flag">
                <option value="1">开启</option>
                <option value="9">关闭</option>
            </select>
            <font size="-1" color="red"></font>
        </td>
    </tr>
    
    <!-- <tr>
        <td width="200" align="right">商品类型：</td>
        <td width="900">
           <select name="type" id="type">
            <?php echo ($optiontype); ?>
           </select>
        </td>
    </tr>  -->
    
    <tr>
        <td width="200" align="right">商品类型编号:</td>
        <td width="900">
            <input type="text" name="typeid" id="typeid" size="30" maxlength="30">
        </td>
    </tr>
    
    
    <tr>
        <td width="200" align="right">类型名称:</td>
        <td width="900">
            <input type="text" name="name" id="name" size="50" maxlength="50">
        </td>
    </tr>
    
    <tr>
        <td width="200" align="right">类型图片:</td>
        <td width="900">
            <input type='file' name='picurl' id='upFile'>
			<!-- <img src="" id="preview"/>
			<img src="" id="nextview"/>
			 <input id="imgOne" name="imgOne" type="hidden"/> -->
        </td>
    </tr>
    
    
</table>
<br/><br/>

<input type="submit" id="add_submit" class="yubutton yuwhite" name="add_submit" style="margin:15px 0px 0px 10px;" value="确认添加" />
<br/><br/>

</form>

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>