/* 315项目公用js调用 */
var dz315Host = 'www.coal.315dz.cn';
var cmsHost = '139.217.20.237';
var cmsDomain = 'http://139.217.20.237';
$(function(){
	//控制图文新闻图片居中
	imgCenter(500);
	function imgCenter(speed){
		setTimeout(function(){
			if($('.imgbox').size()>0){
				var imgBox = $('.imgbox');
				for(var i=0; i<imgBox.size(); i++){
					var imgBoxImg = imgBox.eq(i).children('img');
					var imgBoxImgWidth = imgBoxImg.outerWidth();
					var chaVal = imgBoxImgWidth - 178;
					if(chaVal>0){
						imgBoxImg.css('left',('-' + chaVal/2 + 'px'));
					}else{
						imgBoxImg.css('left',(Math.abs(chaVal/2) + 'px'));
					}
				}
			}
		},speed);
	}
	$('body').on('click','.showMorehandle',function(){
		imgCenter(50);
	})

	//控制大图图文新闻图片上下居中
	setTimeout(function(){
		if($('.imgboxw').size()>0){
			var imgBoxw = $('.imgboxw');
			for(var i=0; i<imgBoxw.size(); i++){
				var imgBoxwImg = imgBoxw.eq(i).children('img');
				var imgBoxwImgHeight = imgBoxwImg.outerHeight();
				var chaVal = imgBoxwImgHeight - 156;
				if(chaVal>0){
					imgBoxwImg.css('top',('-' + chaVal/2 + 'px'));
				}else{
					imgBoxwImg.css('top',(Math.abs(chaVal/2) + 'px'));
				}
				
			}
		}
	},500);
	
	//检测新闻时间是否为今天
	var getToday = new Date($('#today').text()),//获取服务器当前时间
		oSpans = $('.dz-news-stats span.fl'),//获取所有输出位置
		ois = $('.dz-stats i.fl'),//获取右侧所有输出位置
		spanLen = oSpans.length,
		oiLen = ois.length,
		todayNum = getToday.getTime();

	checkToday(oSpans,spanLen,todayNum);//左侧时间判断
	checkToday(ois,oiLen,todayNum);//右侧时间判断
	function checkToday(chi,len,todatTime){
		for(var i=0; i<len; i++){
			var publishTime = new Date(chi.eq(i).text());
			var publishTimeNum = publishTime.getTime();
			if(publishTimeNum === todatTime){
				chi.eq(i).text('今天');
			}
		}
	}

	//搜索框提示文字
	$('.inputbox').on('click',function(){
		$(this).children('.warnwords').hide().siblings('input').focus();
	});
	$('.inputbox').on('blur','input',function(){
		var inputVal = $(this).val();
		if(inputVal == ''){
			$(this).siblings('.warnwords').show();
		}
	});

	//右侧二维码
	$('.jyd-floatbox,.jyd-phonedown').on('click','.close',function(){
		$(this).parent().hide();
	});
	//微信图标鼠标滑过效果
	if($('.jyd-floatbox').size()>0 && $('.weixin').size()>0){
		var winwidth = $(window).width();//浏览器宽度
		var nowTop = ($('.jyd-floatbox').offset().top) - ($(window).scrollTop());//浮窗top值
		var floatBoxWidth = $('.jyd-floatbox').outerWidth() + 31;//浮窗宽度
		var newRight = parseInt((winwidth-1100)/2)-floatBoxWidth;//新的右边距

		$(window).resize(function(){//浏览器窗口改变时重新获取值
			winwidth = $(window).width();//浏览器宽度
			nowTop = ($('.jyd-floatbox').offset().top) - ($(window).scrollTop());//浮窗top值
			floatBoxWidth = $('.jyd-floatbox').outerWidth() + 31;//浮窗宽度
			newRight = parseInt((winwidth-1100)/2)-floatBoxWidth;//新的右边距
		});

		$('.weixin').hover(function(){
			var newTop = ($('.weixin').offset().top) - ($(window).scrollTop());//微信图标距离顶部距离
			$('.jyd-floatbox').stop().animate({
				'right':newRight+'px',
				'top':newTop+'px'
			},500);
		},function(){
			$('.jyd-floatbox').stop().animate({
				'right':0,
				'top':nowTop
			},500);
		});
	}
	
	
	

	//返回顶部
	$('.gotop').on('click',function(){
		$('body,html').animate({scrollTop:0},200);
	});
	$(window).scroll(function(){
		var nowScrollTop = $(this).scrollTop();//滚动距离
		nowScrollTop > 0 ? $('.gotop').show():$('.gotop').hide();
	});
	

	//调用显示更多插件。参数是标准的 jquery 选择符 
	if($(".showMoreNChildren").size()>0){
		$.showMore(".showMoreNChildren");
	}
	
})
