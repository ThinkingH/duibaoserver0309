<?php

echo __FILE__ ; // 取得当前文件的绝对地址，结果：D:\www\test.php 
echo '-------------';
echo dirname(__FILE__); // 取得当前文件所在的绝对目录，结果：D:\www\
echo '-------------';
echo dirname(dirname(__FILE__)); //取得当前文件的上一层目录名，结果：D:\

?>