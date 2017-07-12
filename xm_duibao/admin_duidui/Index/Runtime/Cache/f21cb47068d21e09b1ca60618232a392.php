<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />

<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/datexxx/WdatePicker.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<script type="text/javascript">
$("document").ready(function(){
    
    $(".delete_submit").click(function(){
        
        if(confirm("您确认要删除此条数据吗？")) {
            
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
<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0">
    <tr height="28"><td background="__PUBLIC__/Images/title_bg1.jpg">
        <font size="-1" ><b>当前位置&nbsp;#&nbsp;商城配置信息&nbsp;#&nbsp;商品添加信息的查询</b></font></td></tr>
    <tr><td bgcolor="#b1ceef" height="1"></td></tr>
    <tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<a href="__APP__/Shopadd/addshow<?php echo ($yuurl); ?>" class="yubutton yuwhite">添加商品信息</a>
<br/><br/>

<form action="__APP__/Shopadd/index" method="get" >
<table class="mainTabled" >

<tr>
    <td align="center">是否启用</td>
    <td align="center">审核状态</td>
    <td align="center">是否推荐</td>
    <td align="center">商户编号</td>
    <td align="center">商品名称</td>
    <td align="center">已上架商品</td>
    <td align="center">上架时间</td>
    <td align="center"></td>
</tr>

<tr>

    <td>
        <select name="flag">
            <?php echo ($optionflag); ?>
        </select>
    </td>
    
     <td>
        <select name="status">
            <?php echo ($optionstatus); ?>
        </select>
    </td>
    
     <td>
        <select name="tuijian">
            <?php echo ($optionhottypeid); ?>
        </select>
    </td>
    
    <td>
        <input type="text" name="siteid" id="siteid" value="<?php echo ($siteid); ?>" size="10" maxlength="15" />
    </td>
    
    <td>
        <input type="text" name="name" id="name" value="<?php echo ($name); ?>" size="20" maxlength="20" />
    </td>
    
    <td>
        <select name="onsales">
            <?php echo ($optiononsales); ?>
        </select>
    </td>
    
   <td>
        <input type="text" size="12" maxlength="10" name="date_s" value="<?php echo ($date_s); ?>" onclick="WdatePicker()" />--
        <input type="text" size="12" maxlength="10" name="date_e" value="<?php echo ($date_e); ?>" onclick="WdatePicker()" />
    </td>
    
    <td>
        <input type="submit" class="yubuttons yuwhite" name="submit_select" id="submit_select" value="查询指定匹配内容" />
    </td>
</tr>
</table>
</form>
<br/>

<table class="mainTables" width="2000">
<thead>
    <tr>
       <!--  <td width="50">&nbsp;</td> -->
       <!--  <td width="50">&nbsp;</td> -->
        <td width="50">&nbsp;</td>
        <td width="50">&nbsp;</td>
        <td width="50"><b>自增编号<br/></b></td>
         <td width="80"><b>是否开启<br/></b></td>
        <td width="100"><b>审核状态<br/></b></td>
        
        <td width="100"><b>是否上架<br/></b></td>
        <td width="100"><b>是否推荐<br/></b></td>
        
        <td width="100"><b>渠道编号<br/></b></td>
        <td width="100"><b>类型编号<br/></b></td>
        <td width="100"><b>类型子编号<br/></b></td>
        
        <td width="150"><b>商品名称</b></td>
        <td width="80"><b>商品价格<br/></b></td>
        <td width="80"><b>商品积分<br/></b></td>
        <td width="120"><b>商品主图<br/></b></td>
        <td width="200"><b>商品展示图1<br/></b></td>
        <td width="200"><b>商品展示图2<br/></b></td>
        <td width="200"><b>商品展示图3<br/></b></td>
        
        <td width="120"><b>支付类型<br/></b></td>
        <td width="100"><b>购买兑换次数<br/></b></td>
        <td width="100"><b>评价次数<br/></b></td>
        
        <!-- <td width="100"><b>商品创建时间<br/></b></td> -->
        <td width="150"><b>商品上架时间<br/></b></td>
        <td width="150"><b>商品下架时间<br/></b></td>
       
       
        
        
        
        <td width="100"><b>剩余库存数量<br/></b></td>
        <td width="100"><b>每日最大库存<br/></b></td>
        
        <td width="100"><b>用户每日兑换最大次数<br/></b></td>
        <td width="100"><b>用户每月兑换最大次数<br/></b></td>
        <td width="100"><b>用户终生兑换最大次数<br/></b></td>
        
        
    </tr>
</thead>

<tbody>
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
        <!-- <td>
            <form action="__APP__/Shopadd/onsalesdata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
            <input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
            <?php if($vo['onsales']=='2'){ ?>
            <input type="submit" class="yubuttonss yuwhite" name="sales_submit" value="上架"/>
            <?php }else{ ?>
            <input type="submit" class="yubuttonss yuwhite" name="sales_submit" value="上架" disabled="disabled"/>
            <?php } ?>
            </form>
        </td> -->
       <!--  <td>
            <form action="__APP__/Shopadd/tuijianshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
            <input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
            <?php if($vo['hottypeid']=='100'){ ?>
            <input type="submit" class="yubuttonss yuwhite" name="tuijian_submit" value="推荐" />
            <?php }else{ ?>
            <input type="submit" class="yubuttonss yuwhite" name="tuijian_submit" value="推荐" disabled="disabled" />
            <?php } ?>
            </form>
        </td> -->
        
        <td>
            <form action="__APP__/Shopadd/updateshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
            <input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
            <input type="submit" class="yubuttonss yuwhite" name="update_submit" value="修改" />
            </form>
        </td>
        
        <td>
            <form action="__APP__/Shopadd/deletedata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
            <input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
            <input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="删除" />
            </form>
    </td>
        <td><?php echo ($vo["id"]); ?></td>
        <!-- <td><?php echo ($vo["onsales"]); ?></td> -->
        <td><?php echo ($vo["flag"]); ?></td>
        <td><?php echo ($vo["status"]); ?></td>
        
        <td><?php echo ($vo["onsales"]); ?></td>
        <td><?php echo ($vo["hottypeid"]); ?></td>
        
        <td><?php echo ($vo["siteid"]); ?></td>
        <td><?php echo ($vo["typeid"]); ?></td>
        <td><?php echo ($vo["typeidchild"]); ?></td>
        
        <td><?php echo ($vo["name"]); ?></td>
        <td><?php echo ($vo["price"]); ?></td>
        <td><?php echo ($vo["score"]); ?></td>
        <td>
        <img src="<?php echo ($vo["mainpic"]); ?>" height="50" />
        </td>
       <td>
       <img src="<?php echo ($vo["showpic1"]); ?>" height="100" />
       </td>
       <td>
       <img src="<?php echo ($vo["showpic2"]); ?>" height="100" />
       </td>
       <td>
       <img src="<?php echo ($vo["showpic3"]); ?>" height="100" />
       </td>
        <td><?php echo ($vo["feetype"]); ?></td>
        <td><?php echo ($vo["buycount"]); ?></td>
        <td><?php echo ($vo["pingjiacount"]); ?></td>
       
       
        <!-- <td><?php echo ($vo["create_datetime"]); ?></td> -->
        <td><?php echo ($vo["start_datetime"]); ?></td>
        <td><?php echo ($vo["stop_datetime"]); ?></td>
       
       
        
        <td><?php echo ($vo["kucun"]); ?></td>
        <td><?php echo ($vo["daymax"]); ?></td>
        
        <td><?php echo ($vo["userdaymax"]); ?></td>
        <td><?php echo ($vo["usermonthmax"]); ?></td>
        <td><?php echo ($vo["userallmax"]); ?></td>
        
       <!--  <td><?php echo ($vo["statusmsg"]); ?></td>
        <td><?php echo ($vo["remark"]); ?></td> -->
        
        
    </tr><?php endforeach; endif; else: echo "没有对应数据" ;endif; ?>
</tbody>

</table>

<br/><br/>

<center><?php echo ($page); ?></center>

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>