<?php


//引入主文件
require_once("../lib/c.core.php");


//接收用户id和用户密钥进行校验
$userkey  = HyItems::arrayItem ( $_REQUEST, 'userid' ); //userid
$checkkey = HyItems::arrayItem ( $_REQUEST, 'checkkey' ); //md5(userid+userkey)



//校验验证码是否和session中存储的一致
$HySession = new HySession();
$HySession->set('session_userid',$userkey);//userid
$HySession->set('session_checkkey',$checkkey);//md5(userid+userkey)




?>
<!DOCTYPE html>
<html>
<head>
<title>积分抽奖</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta name="format-detection" content="telephone=no"/>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport" />
<style>
body {
	font-size: 14px; font-family: "Verdana", "arial", "Helvetica", "sans-serif"
}
#lottery table{background-color:red;margin:6%;}
#lottery table td{position:relative;width:33%;text-align:center;color:#333;font-index:-999}
#lottery table td img{display:block;width:99%;}
#lottery table td.active .mask{display:block;}
.mask{
    width:100%;
    height:100%;
    position:absolute;
    left:0;
    top:0;
    background:url(images/mask.png) no-repeat;
	background-size:cover;
    display:none;
}


/*弹出框css样式*/
.verticalAlign{ vertical-align:middle; display:inline-block; height:100%; margin-left:-1px;}
.xcConfirm .xc_layer{position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: #666666; opacity: 0.5; z-index: 2147000000;}
.xcConfirm .popBox{position: fixed;top:50%; left: 10%; background-color: #ffffff; z-index: 2147000001; width: 80%; height: 150px; margin-top: -150px; border-radius: 5px; font-weight: bold; color: #535e66;}
.xcConfirm .popBox .ttBox{height: 24px; line-height: 22px; padding: 5px 20px; border-bottom: solid 1px #eef0f1;}
.xcConfirm .popBox .ttBox .tt{font-size: 16px; display: block; float: left; height: 20px; position: relative;}
.xcConfirm .popBox .ttBox .clsBtn{display: block; cursor: pointer; width: 12px; height: 12px; position: absolute; top: 22px; right: 30px;}
.xcConfirm .popBox .txtBox{margin:12px 20px; height: 42px; overflow: hidden;}
.xcConfirm .popBox .txtBox p{ height: 84px; margin-top: 16px; line-height: 26px; overflow-x: hidden; overflow-y: auto;}
.xcConfirm .popBox .txtBox p input{width: 364px; height: 30px; border: solid 1px #eef0f1; font-size: 18px; margin-top: 6px;}
.xcConfirm .popBox .btnArea{border-top: solid 1px #eef0f1;}
.xcConfirm .popBox .btnGroup{float: right;}
.xcConfirm .popBox .btnGroup .sgBtn{margin-top: 8px; margin-right: 10px;}
.xcConfirm .popBox .sgBtn{display: block; cursor: pointer; float: left; width: 60px; height: 30px; line-height: 30px; text-align: center; color: #FFFFFF; border-radius: 5px;}
.xcConfirm .popBox .sgBtn.ok{background-color: #0095d9; color: #FFFFFF;}
.xcConfirm .popBox .sgBtn.cancel{background-color: #546a79; color: #FFFFFF;}


</style>
<script src="../public/js/jquery.min.js"></script>
<script src="../public/js/xcConfirm.js"></script>
<script type="text/javascript">
function hy_dialog_show(aaa) {
	var txt = aaa;
	var option = {
		title: "提示",
		btn: parseInt("0011",2),
		onOk: function(){
			//console.log("确认");
		}
	}
	window.wxc.xcConfirm(txt, "custom", option);
	
}

</script>


</head>
<body class="keBody" style="background-color:red;">

<center><h1 style="color:yellow;">积分抽奖</h1></center>
<!--效果html开始-->
<div id="lottery">
    <table border="0" cellpadding="0" cellspacing="0" >
        <tr>
            <td class="lottery-unit lottery-unit-0"><img src="images/0.png"><div class="mask"></div></td>
            <td class="lottery-unit lottery-unit-1"><img src="images/3.png"><div class="mask"></div></td>
            <td class="lottery-unit lottery-unit-2"><img src="images/0.png"><div class="mask"></div></td>
        </tr>
        <tr>
            <td class="lottery-unit lottery-unit-7"><img src="images/20.png"><div class="mask"></div></td>
            <td><a href="#"><img src="images/c.png"></a></td>
            <td class="lottery-unit lottery-unit-3"><img src="images/5.png"><div class="mask"></div></td>
        </tr>
        <tr>
            <td class="lottery-unit lottery-unit-6"><img src="images/0.png"><div class="mask"></div></td>
            <td class="lottery-unit lottery-unit-5"><img src="images/10.png"><div class="mask"></div></td>
            <td class="lottery-unit lottery-unit-4"><img src="images/0.png"><div class="mask"></div></td>
        </tr>
    </table>
</div>

<div style="color:yellow;margin:30px 6%;">
抽奖规则：<br/>
每次抽奖需要消耗20积分<br/>
单用户每日抽奖最多为2次<br/>
积分类奖品实时发放<br/>
</div>



<script type="text/javascript">
var jishuqi = 0;

var lottery={
    index:-1,    //当前转动到哪个位置，起点位置
    count:0,    //总共有多少个位置
    timer:0,    //setTimeout的ID，用clearTimeout清除
    speed:20,    //初始转动速度
    times:0,    //转动次数
    cycle:50,    //转动基本次数：即至少需要转动多少次再进入抽奖环节
    prize:-1,    //中奖位置
    init:function(id){
        if ($("#"+id).find(".lottery-unit").length>0) {
            $lottery = $("#"+id);
            $units = $lottery.find(".lottery-unit");
            this.obj = $lottery;
            this.count = $units.length;
            $lottery.find(".lottery-unit-"+this.index).addClass("active");
        };
    },
    roll:function(){
        var index = this.index;
        var count = this.count;
        var lottery = this.obj;
        $(lottery).find(".lottery-unit-"+index).removeClass("active");
        index += 1;
        if (index>count-1) {
            index = 0;
        };
        $(lottery).find(".lottery-unit-"+index).addClass("active");
        this.index=index;
        return false;
    },
    stop:function(index){
        this.prize=index;
        return false;
    }
};

function roll(){
    lottery.times += 1;
    lottery.roll();//转动过程调用的是lottery的roll方法，这里是第一次调用初始化
    if (lottery.times > lottery.cycle+10 && lottery.prize==lottery.index) {
        clearTimeout(lottery.timer);
        lottery.prize=-1;
        lottery.times=0;
        click=false;
    }else{
        if (lottery.times<lottery.cycle) {
            lottery.speed -= 10;
        }else if(lottery.times==lottery.cycle) {

        	var index = 0;
        	
        	$.ajax({
				type: "POST",
				async: false,
				url: "../interface/choujiang.php",
				data: "key=key",
				success: function(data){
					//console.log(data);
					//alert(data);
					index = data;//Math.random()*(lottery.count)|0;
				}
			});
			if(0!=index && 1!=index && 2!=index && 3!=index && 4!=index && 5!=index && 6!=index && 7!=index ) {
				index = 0;
			}
            
            lottery.prize = index;        
        }else{
            if (lottery.times > lottery.cycle+10 && ((lottery.prize==0 && lottery.index==7) || lottery.prize==lottery.index+1)) {
                lottery.speed += 110;
            }else{
                lottery.speed += 20;
            }
        }
        if(lottery.speed<40) {
            lottery.speed=40;
        };
        //console.log(lottery.times+'^^^^^^'+lottery.speed+'^^^^^^^'+lottery.prize+'^^^^^^'+lottery.index);
        lottery.timer = setTimeout(roll,lottery.speed);//循环调用
        
        
        if(lottery.prize>-1) {
            ++jishuqi;
            
			var temp = lottery.index + 1;
			if(temp == 8) {
				temp = 0;
			}
			//console.log(jishuqi+'---'+temp);
            //console.log(lottery.times+'^^^^^^'+lottery.speed+'^^^^^^^'+lottery.prize+'^^^^^^'+lottery.index);
            if(jishuqi>=10 && temp==lottery.prize) {
            	//console.log(jishuqi+'---'+temp);
	        	$.ajax({
					type: "POST",
					async: true,
					url: "../interface/select_jiangpin.php",
					data: "key=key",
					success: function(data){
						//console.log(data);
						hy_dialog_show(data);
					}
				});
            }
        }
    }
	
    return false;
}

var click=false;

window.onload=function(){
    lottery.init('lottery');
    
    $("#lottery a").click(function(){
        if (click) {//click控制一次抽奖过程中不能重复点击抽奖按钮，后面的点击不响应
            return false;
        }else{
        	jishuqi = 0;
            lottery.speed=100;
            roll();    //转圈过程不响应click事件，会将click置为false
            click=true; //一次抽奖完成后，设置click为true，可继续抽奖
            return false;
        }
    });
	
	
};
</script>
<!--效果html结束-->

</body>
</html>