<?php
return array(
	//'配置项'=>'配置值'
	
	// 开启日志记录
	'LOG_RECORD' => true,
	'LOG_LEVEL'  => 'EMERG,ALERT,CRIT,ERR,WARN',
		
	//检查文件大小写
	'APP_FILE_CASE' => true,	
	
	'URL_MODEL'	=> 1,	
	
	// 添加数据库配置信息
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => '127.0.0.1', // 服务器地址
	'DB_NAME'   => 'ranshi', // 数据库名
	'DB_USER'   => 'ranshi', // 用户名
	'DB_PWD'    => 'Ranshi2106', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => '', // 数据库表前缀
);

?>
