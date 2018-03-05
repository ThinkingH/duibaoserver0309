/* js方法函数库 */

//轮播方法
var num = 0;//轮播元素指针从0开始计数
var t;
var focus = {
	init : function(parent,next,prev,statsbtn,tbtns){
		var celBox = $(parent);//获取轮播元素父盒子
		var celBoxAndBtns = $(tbtns);
		var nextBtn = $(next);//下翻按钮
		var prevBtn = $(prev);//上翻按钮
		var btns = $(statsbtn);//状态按钮
		var imgWidth = parseInt(celBox.children().eq(0).outerWidth());//单个轮播元素宽度
		var that = this;
		var firstCellHtml = celBox.children().eq(0).html();//获取第一个轮播元素html
		var firstCellDivClass = celBox.children().eq(0).attr('class');
		var firstCellDiv = $('<div>').attr('class',firstCellDivClass);
		firstCellDiv.html(firstCellHtml);
		celBox.append(firstCellDiv);

		var celLen = celBox.children().size() - 1;//轮播元素数量
		celBox.css('width',(imgWidth*(celLen+1)+'px'));//设置轮播父盒子宽度

		btns.empty();
		// var statsBtnNum = celLen-1;
		btns.css('width',(31*celLen+'px'));
		for(var i=0;i<celLen;i++){
			var newSpan = $('<span>').attr('index',i+1);
			btns.append(newSpan);//创建对应数量的状态按钮
		}
		btns.children('span').eq(0).addClass('on');

		that.next(nextBtn,celBox,celLen,imgWidth,btns,that);//下翻
		that.prev(prevBtn,celBox,celLen,imgWidth,btns,that);//上翻
		that.btnClick(btns,celBox,imgWidth);//按钮点击效果
		//定时器
		t = setInterval(function(){
			nextBtn.trigger('click');
		},3500);
		celBoxAndBtns.mouseenter(function(){//鼠标移入清除定时器
			clearInterval(t)
		});
		celBoxAndBtns.mouseleave(function(){//鼠标移出添加定时器
			t = setInterval(function(){
				nextBtn.trigger('click');
			},3500);
		});
	},
	prev : function(btnId,celBox,list,celWidth,btns,that){
		$(btnId).click(function(){
			if(num<1){
				num = 4;//显示第一张的时候跳到最后一张
			}else{
				num--;//点击指针自减
			}
			celBox.stop().animate({'left':'-'+num*celWidth+'px'},500);
			that.btnList(btns,num);
		});
	},
	next : function(btnId,celBox,list,celWidth,btns,that){
		$(btnId).click(function(){
			if(num>(list-2)){
				num++;
				celBox.stop().animate({'left':'-'+num*celWidth+'px'},500);
				num = 0;//显示最后一张的时候指针归零
				setTimeout(function(){
					celBox.animate({'left':'0'},0);
				},500);
			}else{
				num++;//点击指针自加
				celBox.stop().animate({'left':'-'+num*celWidth+'px'},500);
			}
			// celBox.stop().animate({'left':'-'+num*celWidth+'px'},500);
			that.btnList(btns,num);
		});
	},
	btnList : function(btns,num){
		btns.children().removeClass('on').eq(num).addClass('on');
	},
	btnClick : function(btns,celBox,celWidth){
		btns.children().mouseenter(function(){
			var index = $(this).attr('index')-1;//获取对应指针
			$(this).addClass('on').siblings().removeClass('on');//添加对应样式类名
			celBox.stop().animate({'left':'-'+index*celWidth+'px'},500);//显示对应图片
			num = index;//指针赋给num
		});
	}
}

//一行多个图片切换显示效果
function imgSlideOne(slideOne){
	var speed = slideOne.speed;//切换速度
	var num = 0;
	var showLen = slideOne.showLen;//默认显示的数量
	var dz_cellWidth = slideOne.cellWidth;//每个图片盒子宽度
	var dz_cellLength = $(slideOne.parent).children().size();//图片个数
	$(slideOne.parent).css('width',((dz_cellWidth*dz_cellLength)+'px'));//设置父盒子宽度

	$(slideOne.prev).on('click',function(){
		if(num<(dz_cellLength-showLen)){
			num++;
			$(slideOne.parent).animate({
				'left':('-'+num*dz_cellWidth+'px')
			},speed);
		}
	});
	$(slideOne.next).on('click',function(){
		if(num>0){
			num--;
			$(slideOne.parent).animate({
				'left':('-'+num*dz_cellWidth+'px')
			},speed);
		}
	});
}

//选项卡切换效果函数
function tabChange(tab,tabclass,div){
	$(div).eq(0).show();
	$(tab).on('click','li',function(){
		$(this).addClass(tabclass).siblings().removeClass(tabclass);
		$(div).eq($(this).index()).show().siblings(div).hide();
	});
}

//获取当前日期
function GetCurrentDateTime(day,weeka) {
    var d = new Date();
    var year = d.getFullYear();
    var month = d.getMonth() + 1;
    var date = d.getDate();
    var week = d.getDay();
    var hours = d.getHours();
    var minutes = d.getMinutes();
    var seconds = d.getSeconds();
    var ms = d.getMilliseconds();
    var curDateTime = year;
    var todaya = document.getElementById(day)||'';
    var weeka = document.getElementById(weeka)||'';
    if (month > 9)
        curDateTime = curDateTime + "-" + month;
    else
        curDateTime = curDateTime + "-0" + month; 
    if (date > 9)
        curDateTime = curDateTime + "-" + date;
    else
        curDateTime = curDateTime + "-0" + date;
    // if (hours > 9)
    //     curDateTime = curDateTime + " " + hours;
    // else
    //     curDateTime = curDateTime + " 0" + hours;
    // if (minutes > 9)
    //     curDateTime = curDateTime + ":" + minutes;
    // else
    //     curDateTime = curDateTime + ":0" + minutes;
    // if (seconds > 9)
    //     curDateTime = curDateTime + ":" + seconds;
    // else
    //     curDateTime = curDateTime + ":0" + seconds;
    var weekday = "";
    if (week == 0)
        weekday = "星期日";
    else if (week == 1)
        weekday = "星期一";
    else if (week == 2)
        weekday = "星期二";
    else if (week == 3)
        weekday = "星期三";
    else if (week == 4)
        weekday = "星期四";
    else if (week == 5)
        weekday = "星期五";
    else if (week == 6)
        weekday = "星期六";
    // curDateTime = curDateTime + " " + weekday;
    // return curDateTime;
    if(todaya&&weeka){
    	todaya.innerHTML = curDateTime;
    	weeka.innerHTML = weekday;
    }
}

//获取当前农历
function nongLi(id){
	var nongLiId = document.getElementById(id)||'';
	var CalendarData=new Array(100);
	var madd=new Array(12);
	var tgString="甲乙丙丁戊己庚辛壬癸";
	var dzString="子丑寅卯辰巳午未申酉戌亥";
	var numString="一二三四五六七八九十";
	var monString="正二三四五六七八九十冬腊";
	var weekString="日一二三四五六";
	var sx="鼠牛虎兔龙蛇马羊猴鸡狗猪";
	var cYear,cMonth,cDay,TheDate;
	CalendarData = new Array(0xA4B,0x5164B,0x6A5,0x6D4,0x415B5,0x2B6,0x957,0x2092F,0x497,0x60C96,0xD4A,0xEA5,0x50DA9,0x5AD,0x2B6,0x3126E, 0x92E,0x7192D,0xC95,0xD4A,0x61B4A,0xB55,0x56A,0x4155B, 0x25D,0x92D,0x2192B,0xA95,0x71695,0x6CA,0xB55,0x50AB5,0x4DA,0xA5B,0x30A57,0x52B,0x8152A,0xE95,0x6AA,0x615AA,0xAB5,0x4B6,0x414AE,0xA57,0x526,0x31D26,0xD95,0x70B55,0x56A,0x96D,0x5095D,0x4AD,0xA4D,0x41A4D,0xD25,0x81AA5,0xB54,0xB6A,0x612DA,0x95B,0x49B,0x41497,0xA4B,0xA164B, 0x6A5,0x6D4,0x615B4,0xAB6,0x957,0x5092F,0x497,0x64B, 0x30D4A,0xEA5,0x80D65,0x5AC,0xAB6,0x5126D,0x92E,0xC96,0x41A95,0xD4A,0xDA5,0x20B55,0x56A,0x7155B,0x25D,0x92D,0x5192B,0xA95,0xB4A,0x416AA,0xAD5,0x90AB5,0x4BA,0xA5B, 0x60A57,0x52B,0xA93,0x40E95);
	madd[0]=0;
	madd[1]=31;
	madd[2]=59;
	madd[3]=90;
	madd[4]=120;
	madd[5]=151;
	madd[6]=181;
	madd[7]=212;
	madd[8]=243;
	madd[9]=273;
	madd[10]=304;
	madd[11]=334;
	 
	function GetBit(m,n){
		return (m>>n)&1;
	}
	function e2c(){
		TheDate= (arguments.length!=3) ? new Date() : new Date(arguments[0],arguments[1],arguments[2]);
		var total,m,n,k;
		var isEnd=false;
		var tmp=TheDate.getYear();
		if(tmp<1900){
			tmp+=1900;
		}
		total=(tmp-1921)*365+Math.floor((tmp-1921)/4)+madd[TheDate.getMonth()]+TheDate.getDate()-38;
		 
		if(TheDate.getYear()%4==0&&TheDate.getMonth()>1) {
			total++;
		}
		for(m=0;;m++){
			k=(CalendarData[m]<0xfff)?11:12;
			for(n=k;n>=0;n--){
				if(total<=29+GetBit(CalendarData[m],n)){
				isEnd=true; break;
				}
				total=total-29-GetBit(CalendarData[m],n);
			}
			if(isEnd) break;
		}
		cYear=1921 + m;
		cMonth=k-n+1;
		cDay=total;
		if(k==12){
			if(cMonth==Math.floor(CalendarData[m]/0x10000)+1){
				cMonth=1-cMonth;
			}
			if(cMonth>Math.floor(CalendarData[m]/0x10000)+1){
				cMonth--;
			}
		}
	}
	 
	function GetcDateString(){
		var tmp="";
		// tmp+=tgString.charAt((cYear-4)%10);
		// tmp+=dzString.charAt((cYear-4)%12);
		// tmp+="(";
		// tmp+=sx.charAt((cYear-4)%12);
		// tmp+=")年 ";
		// if(cMonth<1){
		// tmp+="(闰)";
		// tmp+=monString.charAt(-cMonth-1);
		// }else{
		//  tmp+=monString.charAt(cMonth-1);
		// }
		tmp+=monString.charAt(cMonth-1);
		tmp+="月";
		tmp+=(cDay<11)?"初":((cDay<20)?"十":((cDay<30)?"廿":"三十"));
		if (cDay%10!=0||cDay==10){
			tmp+=numString.charAt((cDay-1)%10);
		}
		return tmp;
	}
	 
	function GetLunarDay(solarYear,solarMonth,solarDay){
	//solarYear = solarYear<1900?(1900+solarYear):solarYear;
		if(solarYear<1921 || solarYear>2020){
			return "";
		}else{
			solarMonth = (parseInt(solarMonth)>0) ? (solarMonth-1) : 11;
			e2c(solarYear,solarMonth,solarDay);
			return GetcDateString();
		}
	}
	 
	var D=new Date();
	var yy=D.getFullYear();
	var mm=D.getMonth()+1;
	var dd=D.getDate();
	var ww=D.getDay();
	var ss=parseInt(D.getTime() / 1000);
	if (yy<100) yy="19"+yy;
	if(nongLiId){
		nongLiId.innerHTML = '农历' +GetLunarDay(yy,mm,dd);
	}
}