<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD html 4.0 Frameset//EN">
<html>
<head>
<title><?php echo HY_SYSTEM_NAME; if(HY_SHOW_IP){echo '_'.$_SERVER['SERVER_ADDR'];} ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="-1000">

<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />

<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<script type="text/javascript">

</script>

</head>
<frameset border="0" framespacing="0" rows="60, *" frameborder="0">
<frame name="header" src="__APP__/Header/index" frameborder="0" noresize scrolling="no">
<frameset cols="170, *">
<frame name="menu" src="__APP__/Menu/index" frameborder="0" noresize>
<frame name="main_x" src="__APP__/Main/index" frameborder="0" noresize scrolling="yes">
</frameset>
</frameset>
<noframes>
</noframes>
</html>