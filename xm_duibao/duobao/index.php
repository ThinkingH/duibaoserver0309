<?php
/**
 * Created by PhpStorm.
 * User: fanfan
 * Date: 2017/8/2
 * Time: 9:31
 */

 $mid  =isset($_GET['mid'])?$_GET['mid']:0;
 $type =isset($_GET['type'])?$_GET['type']:0;
 
// echo $type;
// var_dump($_GET);
 
 
//   echo $mid;
 //echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>零元夺宝</title>
    <link rel="stylesheet" href="css/framework7.ios.min.css">
    <link rel="stylesheet" href="css/framework7.ios.colors.min.css">
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
<div class="views">
    <div class="view view-main">
        <div class="pages toolbar-fixed">
            <div class="page" data-page="index">
                <div class="page-content">
                    <!-- 轮播图 -->
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><img src="./img/banner1.png" alt=""></div>
                            <!--<div class="swiper-slide"><img src="./img/banner2.webp" alt=""></div>-->
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                    <!-- 轮播图end -->

                    <!-- 活动简介 -->
                    <div class="intro">
                        <div class="intro-t">
                            <span class="state">夺宝中</span>
                            <span class="name">小米米家智能摄像机，0元抢！</span>
                        </div>
                        <div class="intro-b">
                            <span class="type">所需0积分</span>
                            已参加：<span class="participants">0</span>人
                        </div>
                        <div class="intro-f">
                            为陪伴而来，坚守家的每个瞬间，360°全景拍摄、1080P高清摄影、红外夜视、多角度看管、智能设备语音互动，让生活更美好。
                        </div>
                    </div>
                    <!-- 活动简介end -->

                    <!-- 进度 -->
                    <div class="schedule">
                        <div class="line-bg"></div>
                        <div class="line-g">
                            <div class="line"></div>
                            <div class="circle">0/0</div>
                        </div>
                    </div>
                    <!-- 进度end -->

                    <!-- 展示信息 -->
                    <ul class="showmsg">
                        <li>
                            <p>参与序号</p>
                            <p class="cyxh">?</p>
                        </li>
                        <li>
                            <p>目标人数</p>
                            <p class="maxcount">0</p>
                        </li>
                        <li class="zjxx">
                            <p>中奖信息</p>
                            <p>未开奖</p>
                        </li>
                    </ul>
                    <!-- 展示信息end -->

                    <!-- 标签页 -->
                    <div class="content-block mytab">
                        <div class="buttons-row">
                            <a href="#tab1" class="tab-link button active">产品介绍</a>
                            <a href="#tab2" class="tab-link button">活动详情</a>
                            <a href="#tab3" class="tab-link button">参与情况</a>
                        </div>
                    </div>
                    <div class="tabs">
                        <div id="tab1" class="tab active">
                            <div class="content-block">
                                <img src="./img/detail1.jpg" alt="" style="width: 100%;display: block">
                                <img src="./img/detail2.jpg" alt="" style="width: 100%;display: block">
                            </div>
                        </div>
                        <div id="tab2" class="tab">
                            <div class="content-block">
                                <ul style="margin: auto;padding: 0;list-style: none;">
                                    <li>
                                        <h4 style="color: #333;font-size: 1.1em">● 参与方式</h4>
                                        <p style="margin-bottom: .5em;padding-left: 2rem;">
                                            1.击活动页面，填写手机号码（手机号码作为唯一身份认证信息，请务必填写准确），支付200积分参与报名。如积分不够，手机注册，领取新人礼包，并分享，领取积分。
                                        </p>
                                        <p style="margin-top: .5em;padding-left: 2rem;">
                                            2.未下载APP用户：点击H5活动页面，下载兑宝APP，进入APP活动页面，填写手机号码（手机号码作为唯一身份认证信息，请务必填写准确），支付200积分参与报名。如积分不够，手机注册，领取新人礼包，并分享，领取积分。
                                        </p>
                                    </li>
                                    <li>
                                        <h4 style="color: #333;font-size: 1.1em">● 活动规则</h4>
                                        <p style="padding-left: 2rem;">
                                            每位用户仅限参与1次，当参与用户数达到399人时，报名结束，随机从参与活动的用户中抽出一位幸运用户，获得该商品。</p>
                                    </li>
                                    <li>
                                        <h4 style="color: #333;font-size: 1.1em">● 抽奖规则</h4>
                                        <p style="padding-left: 2rem;">
                                            最后5位用户手机号的后4位数的总和，（如：1234+3456+...7894）<br>
                                            加上下一期双色球数的总和，<br>
                                            除以参与人数，得到的余数加1，<br>
                                            得到的数字是多少，幸运客户就是第几位参与的用户！
                                        </p>
                                    </li>
                                    <li>
                                        <h4 style="color: #333;font-size: 1.1em">● 结果公布</h4>
                                        <p style="padding-left: 2rem;">中奖结果会以短信的形式，发送到中奖者的手机，同时也会显示在兑宝APP首页及对应活动页。</p>
                                    </li>
                                    <li>
                                        <h4 style="color: #333;font-size: 1.1em">● 奖品领取</h4>
                                        <p style="padding-left: 2rem;">
                                            活动结束后，2个工作日内，兑宝客服会主动与中奖用户联系，奖品会以快递的形式，发送到用户手中！</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div id="tab3" class="tab">
                            <div class="content-block">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>序号</th>
                                        <th>手机号</th>
                                        <th>时间</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="oneself">
                                        <td>00</td>
                                        <td>000*****0000</td>
                                        <td>0000-00-00 00:00:00</td>
                                    </tr>
                                    <tr>
                                        <td>00</td>
                                        <td>000*****0000</td>
                                        <td>0000-00-00 00:00:00</td>
                                    </tr>
                                    <tr>
                                        <td>00</td>
                                        <td>000*****0000</td>
                                        <td>0000-00-00 00:00:00</td>
                                    </tr>
                                    <tr>
                                        <td>00</td>
                                        <td>000*****0000</td>
                                        <td>0000-00-00 00:00:00</td>
                                    </tr>
                                    <tr>
                                        <td>00</td>
                                        <td>000*****0000</td>
                                        <td>0000-00-00 00:00:00</td>
                                    </tr>
                                    <tr>
                                        <td>00</td>
                                        <td>000*****0000</td>
                                        <td>0000-00-00 00:00:00</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- 标签页end -->
                </div>

                <!-- 弹窗 -->
                <div class="popups">
                    <div class="overlay"></div>
                    <div class="item-content">
                        <div class="inner">
                            <p style="font-size: 1.5rem;color: #E34E60;font-weight: bold;text-align: center;margin: auto auto 1rem auto;">
                                报名成功<br>尽情期待开奖</p>
                            <a href="" class="button" id="ok"
                               style="background:-webkit-linear-gradient(top,#fd5300,#e3495a);border:0;
                               color:#fff;height:35px;line-height:35px;font-size:1rem;">确 认</a>
                        </div>
                        <a href="" class="close-popups">
                            <span></span>
                        </a>
                    </div>
                </div>
                <!-- 弹窗end -->

                <!-- 底部表单 -->
                <div class="toolbar">
                    <div class="toolbar">
                        <form id="my-form" class="list-block">
                            <ul>
                                <li>
                                    <div class="item-content">
                                        <div class="item-inner">
                                            <div class="item-input">
											<input value="<?php echo $mid; ?>" id="mid" style="display:none;">
											<input value="<?php echo $type; ?>" id="type" style="display:none;">
                                                <input type="number" name="phone" id="phone" placeholder="请输入您的手机号"
                                                       oninput="if(value.length>11)value=value.slice(0,11)">
                                            </div>
                                            <div class="item-btn">
                                                <a id="participation" href="" class="button ">立即参与</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
                <!-- 底部表单end -->
            </div>

        </div>
    </div>
</div>
<!-- Picker -->
<div class="picker-modal picker-info">
    <div class="toolbar">
        <div class="toolbar-inner">
            <div class="left">下载兑宝</div>
            <div class="right"><a href="#" class="close-picker">关闭</a></div>
        </div>
    </div>
    <div class="picker-modal-inner">
        <div class="content-block" style="margin: auto;background-color: #F3F3F3;height: 100%;">
            <table style="width: 100%;text-align: center;">
                <tr>
                    <td style="vertical-align: middle;height:80px;font-size: 1rem;" colspan="2">
                        您还不是兑宝用户，请下载兑宝APP后再次尝试！
                    </td>
                </tr>
                <tr>
                    <td rowspan="2"><img src="./img/5979600e8e7df.png" alt="APP"
                                         style="vertical-align:middle;width:60px;height:60px;"><br><br>兑宝
                    </td>
                    <td style="vertical-align:middle;">
                        <a class="external" style="display:inline-block;padding:6px 12px;
                           color:#fff;background-color:#5bc0de;border-color:#46b8da;text-decoration:none;border-radius:3px;"
                           href="http://url.cn/4EKRnLz">安卓用户</a>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:middle;">
                        <a class="external" style="display:inline-block;padding:6px 12px;
                           color:#fff;background-color:#449d44;border-color:#398439;text-decoration:none;border-radius:3px;"
                           href="https://itunes.apple.com/cn/app/%E5%85%91%E5%AE%9D/id1224951145?mt=8">苹果用户</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/framework7.min.js"></script>
<script type="text/javascript" src="js/my_app.js"></script>
</body>
</html>

