<?php
/**
 * Created by PhpStorm.
 * User: fanfan
 * Date: 2017/8/16
 * Time: 16:28
 */
// 引入主文件
require_once("../../lib/c.core.php");

$myorderid = isset($_GET['myorderid']) ? trim($_GET['myorderid']) : '未知';
$paymoney = isset($_GET['paymoney']) ? trim($_GET['paymoney']) : '0';

$HyDb = new HyDb ();

$selet_sql = "select id,name,productid,productnum,price from shop_userbuy where zhifu_order='".$myorderid."'";
$selet_list = $HyDb->get_row($selet_sql);

//单价
$daprice = $selet_list['price']/$selet_list['productnum'];

$price = number_format($daprice / 100, 2);//单价

//商品总价
$paymoney = number_format($paymoney / 100, 2);
//商品名
$goodname = $selet_list['name'];

//数量
$productnum = $selet_list['productnum'];

//商品id
$productid = $selet_list['id'];

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>支付页</title>
    <style type="text/css">
        body {
            position: fixed;
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 100%;
            height: 100%;
            margin: auto;
            padding: 0;
            font-size: 1rem;
            background-color: #EBEBEB;
            overflow: hidden;
        }

        .piece {
            display: block;
            box-sizing: border-box;
            background-color: #ffffff;
            width: 90%;
            margin: auto;
            padding: 15px;
            border: 1px solid #E0E0E0;
            border-radius: 5px;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .title > .title-minor {
            color: #8A8A8A;
            margin-bottom: 5px;
        }

        .title > .title-mian {
            font-size: 1.8rem;
        }

        .list-item {
            border-top: 1px dashed #8A8A8A;
            border-bottom: 1px solid #8A8A8A;
            padding: 5px 15px;
        }

        .list-item > .list-row {
            min-height: 45px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .list-item > .list-row > .list-col {
            flex-shrink: 0;
            width: 100px;
        }

        .list-item > .list-row > .list-col:first-child {

            color: #8A8A8A;
        }

        .list-item > .list-row > .list-col:last-child {
            flex: 1;
            width: 100%;
            color: #333333;
        }

        .btn-g {
            display: flex;
            width: 100%;
            margin-top: 20px;
            overflow: hidden;
        }

        .btn-g > .btn:first-child {
            margin-left: auto;
        }

        .btn-g > .btn {
            margin-left: 10px;
            height: 45px;
            width: calc(50% - 5px);
            position: relative;
        }

        .btn-g > .btn > a {
            position: absolute;
            top: 0;
            left: 0;
            font-size: 2rem;
            width: 200%;
            height: 200%;
            line-height: 90px;
            text-align: center;
            display: block;
            text-decoration: none;
            color: #ffffff;
            transform: scale(0.5);
            transform-origin: 0 0;
            border-radius: 10px;
        }

        .btn-g > .btn > a.yes {
            background-color: #2C9B2C;
        }

        .btn-g > .btn > a.no {
            background-color: #CE5855;
        }
    </style>
    <script src="https://cdn.bootcss.com/layer/3.0.3/mobile/layer.js"></script>
    <script>
        var num = temp_num = 3;
        var layer_win = null;

        function ok() {
            console.log('已支付');
            //loading带文字
            layer_win = layer.open({
                type: 2,
                shadeClose: false,
                content: '支付确认中！'
            });
            var url = '../selpay/shop_payselect_11.php',
                data ="myorderid=<?php echo $myorderid;?>";
            postdata(url, data);
        }

        function no() {
            console.log('未支付');
            try {
                window.postMessage(JSON.stringify({
                    'type': 'close'
                }));
            } catch (e) {
                layer.closeAll();
                layer.open({
                    content: '该支付仅在APP内有效！'
                    , btn: '我知道了'
                });
            }

        }

        function postdata(url, data) {
            temp_num--;
            if (temp_num <= -1) {
                wanc(0);
            } else {
                ajaxPost(
                    url,
                    data,
                    function (response) {
//                        console.log(response);
                        console.log('成功');
                        if (response == 'DELIVRD') {
                            wanc(1);
                        } else
                            window.setTimeout(function () {
                                postdata(url, data);
                            }, 1800);
                    },
                    function (str) {
//                        console.log(str);
                        console.log('失败');
                        window.setTimeout(function () {
                            postdata(url, data);
                        }, 2000);
                    });
            }
        }

        function wanc(re) {
            layer.close(layer_win);
            console.log('完成');
            temp_num = num;
           
            try {
				/*if(re==0){
             		 window.postMessage(JSON.stringify({
                 		 'ispay':true,
                         'price' : '<?php echo $price;?>',
                         'goodname' : '<?php echo $goodname;?>',
                         'productnum' : '<?php echo $productnum?>',
                         'productid' : '<?php echo $productid?>',
                          'issuccess' : false
                      }));
                  }else if(re==1){*/
                	 window.postMessage(JSON.stringify({
                		 'ispay':true,
                         'price' : '<?php echo $price;?>',//单价
                         'goodname' : '<?php echo $goodname;?>',////商品名
                         'productnum' : '<?php echo $productnum?>',//数量
                         'productid' : '<?php echo $productid?>',//商品id
                         'issuccess' : true
                     }));
//                  }else{//未知


//                      }
               
            } catch (e) {
                layer.closeAll();
                layer.open({
                    content: '该支付仅在APP内有效！'
                    , btn: '我知道了'
                });
            }
            return true;
        }

        // ajax 对象
        function ajaxObject() {
            var xmlHttp;
            try {
                // Firefox, Opera 8.0+, Safari
                xmlHttp = new XMLHttpRequest();
            }
            catch (e) {
                // Internet Explorer
                try {
                    xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e) {
                    try {
                        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                    } catch (e) {
                        alert("您的浏览器不支持AJAX！");
                        return false;
                    }
                }
            }
            return xmlHttp;
        }

        // ajax post请求：
        function ajaxPost(url, data, fnSucceed, fnFail, fnLoading) {
            var ajax = ajaxObject();
            ajax.open("post", url, true);
            ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            ajax.onreadystatechange = function () {
                if (ajax.readyState == 4) {
                    if (ajax.status == 200) {
                        if (fnSucceed != undefined)
                            fnSucceed(ajax.responseText);
                    }
                    else {
                        if (fnFail != undefined)
                            fnFail("HTTP请求错误！错误码：" + ajax.status);
                    }
                }
                else {
                    if (fnLoading != undefined)
                        fnLoading();
                }
            }
            ajax.send(data);
        }

    </script>
</head>
<body>
<div class="piece">
    <div class="title">
        <div class="title-minor"></div>
        <div class="title-mian">您是否已完成支付？</div>
    </div>
    <div class="list-item">
        <div class="list-row">
            <div class="list-col">订&ensp;单&ensp;号：</div>
            <div class="list-col"><?php echo $myorderid; ?></div>
        </div>
        <div class="list-row">
            <div class="list-col">商品名称：</div>
            <div class="list-col"><?php echo $selet_list['name'];?></div>
        </div>
        <div class="list-row">
            <div class="list-col">订单金额：</div>
            <div class="list-col">￥<?php echo number_format($paymoney / 100, 2); ?></div>
        </div>
    </div>
    <div class="btn-g">
        <div class="btn"><a class="no" onclick="no()">未支付</a></div>
        <div class="btn"><a class="yes" onclick="ok()">已完成</a></div>
    </div>
</div>
</body>
</html>
