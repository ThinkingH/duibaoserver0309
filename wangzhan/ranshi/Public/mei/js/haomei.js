// JavaScript Document
$(function(){
	/** 煤大户价格 价格走势横条滑动效果**/
	$(".price_title_right a").each(function(i){
		$(this).mouseover(function(){
			$(".price_content").hide();
			$(".price_content").eq(i).show();
			$(".price_title_right .line").stop();
			var w=$(".price_title_right .line").width();
			$(".price_title_right .line").animate({left:w*i},300);
			$(".price_title_right a").siblings().removeClass('light-blue-text');
			$(".price_title_right a").eq(i).addClass('light-blue-text');
			
		});
		
	});
	$(".price_content").each(function(){
		 $(this).find(".content_block").last().css({"border":'0'})
	});
	
	/**第一个楼层切换效果**/
	(function( $ ){  
	  $.fn.tab = function(idName,selecdName) {      
		$("#"+idName).find(".f_title_tab a").each(function(i){
				$(this).mouseover(function(){
					$("#"+idName).find(".f_title_tab a").removeClass(selecdName);
					$(this).addClass(selecdName);
					$("#"+idName).find(".content_top_xhzy").hide();
					$("#"+idName).find(".content_top_xhzy").eq(i).show();
					$("#"+idName).find(".content_text_list").hide();
					$("#"+idName).find(".content_text_list").eq(i).show();
				})
		})
	  
	  };  
	})( jQuery );
	(function($){  
	  $.fn.Tab2 = function(idName,selecd,noselecd){
		  $("#"+idName).find(".f_title_tab a").each(function(i){
			  $(this).mouseover(function(){
				  $("#"+idName).find(".f_title_tab a").removeClass(selecd);
				  $("#"+idName).find(".f_title_tab a").addClass(noselecd);
				  $(this).removeClass(noselecd);
				  $(this).addClass(selecd);
				  $("#"+idName).find(".content_top_xhzy").hide();
				  $("#"+idName).find(".content_top_xhzy").eq(i).show();
				  $("#"+idName).find(".content_text_list").hide();
				  $("#"+idName).find(".content_text_list").eq(i).show();
			  })
		  })	  
	  };
	})( jQuery );

	$().Tab2('new_onef','selecd','one_noselecd');
	$().Tab2('new_twof','selecd blue','blue-text');
	$().Tab2('new_threef','selecd orange','orange -text');		
	$().Tab2('new_fourf','selecd indigo','indigo-text');		
	
	//订单动态滚动
	$(".purchase_scroll1").find("table").scrollcontent(50);
	$(".purchase_scroll2").find("table").scrollcontent(50);
	$(".purchase_scroll3").find("table").scrollcontent(50);
	$(".purchase_scroll4").find("table").scrollcontent(50);
	
	
	
	/**移入提示信息详情效果**/
	$(".content_text_list li ").each(function(i){
		$(this).mousemove(function(ev){
			  var str=$(this).find('p').eq(0).html()+'&nbsp;'+$(this).find('p').eq(1).html();
			  $("#tip_message").show();
			  $("#tip_message").html(str);
			  var t=$(window).scrollTop();
			  var x=ev.clientX - $("#tip_message").width()-40;
			  var y=ev.clientY+t-15;			  
			  $("#tip_message").css({left:x,top:y});
		});
		$(this).mouseout(function(){
			$("#tip_message").hide();
		});
		
	});

	//订单动态移入提示效果
	$(".mj_message_table").on('mousemove','.td_w84',function(ev){
		var str=$(this).parent().find('span').html();
			  $("#tip_message").show();
			  $("#tip_message").html(str);
			  var t=$(window).scrollTop();
			  var x=ev.clientX+20;
			  var y=ev.clientY+t-15;
			  $("#tip_message").css({left:x,top:y});
	});
	$(".mj_message_table").on('mouseout','.td_w84',function(ev){
			  $("#tip_message").hide();	
	});
	
	
	/*网站主功能模块*/
	/*筛选热门地区展开和收起效果**/
	$(".open_1").click(function(){
		   $(this).hide();
		   $(".close_1").show();
		   $(".rmdq_fl").show();
		   $(this).siblings('p').hide();
		  // $(".sx_type").eq(1).addClass('bottom_line');
	})
			
	$(".close_1").click(function(){
		   $(this).hide();
		   $(".open_1").show();
		   $(".rmdq_fl").hide();
		   $(this).siblings('p').show();
		   //$(".sx_type").eq(1).removeClass('bottom_line');
	})
	/*筛选热门地区展开和收起效果**/
	$(".open_2").click(function(){
		   $(this).hide();
		   $(".close_2").show();
		   $(".rmdq_f2").show();
		   $(this).siblings('p').hide();
		  // $(".sx_type").eq(1).addClass('bottom_line');
	})
			
	$(".close_2").click(function(){
		   $(this).hide();
		   $(".open_2").show();
		   $(".rmdq_f2").hide();
		   $(this).siblings('p').show();
		   //$(".sx_type").eq(1).removeClass('bottom_line');
	})
	/*****筛选品种种类的效果*****/     
	$(".hm_search").on('click','.open',function(){
		   var w=0;
		   $(this).hide().siblings('.close').show();
		   $(this).siblings('p').find('a').each(function(i){
				 w+=$(this).width()+25;
		   })   
		   var bei=Math.ceil(w/$(this).siblings('p').width());
		   var h=$(this).siblings('p').height();  
		   $(this).siblings('p').css({'height':bei*h});
		   w=0;
				
	})
	$(".hm_search").on('click','.close',function(){
		  $(this).hide().siblings('.open').show();
		  $(this).siblings('p').css('height','31');
	})
	/**有效时间移入显示下拉列表**/
	$(".yxsj .select").mouseover(function(){
			$(".select_list").show();
		}).mouseout(function(){
			$(".select_list").hide();
	})
	/***资源列表 公司移入显示效果***/
	$(".company").mouseover(function(){
		$(this).find('.company_div').show();
	}).mouseout(function(){
		$(this).find('.company_div').hide();
	});
	
	/* //电梯效果 */
	var lift={
		pos:{},
		get_pos:function()
		{
			$("body").find(".floor_flag").each(function(index)
			{
				lift.pos[index]=$(this).offset().top;
			});
		},
		style:function(index)
		{
			var $this=$(".lift ul").find("li").eq(index);
			var color=$this.css("borderLeftColor");
			$this.css({"background":color,"color":"#FFFFFF"}).siblings("li").each(function()
			{
				$(this).css({"background":"#FFFFFF","color":$(this).css("border-color")});
			});
			$(".lift").find(".arrow").css({"top":(19+52*index)+"px","border-left-color":color});
		},
		go:function()
		{
			$(".lift ul").find("li").on("click",function()
			{
				$(window).off("scroll",lift.scroll);
				var index=$(this).index();
				$("html,body").animate({scrollTop:lift.pos[index]-60},500,function()
				{
					lift.style(index);
					$(window).on("scroll",lift.scroll); 
				});

			});
		},
		go_top:function()
		{
			$(".lift").find(".go_top").on("click",function()
			{
				$("html,body").animate({scrollTop:"0"},500);
			});
		},
		scroll:function()
		{
			var s_top=$(window).scrollTop();
			if(s_top>lift.pos[0]-100)
			{
				$(".lift").fadeIn(500);
				var n=$(".lift ul").find("li").length;
				for(i=0;i<n;i++)
				{
					if($("body").find(".floor_flag").eq(i).offset().top-60<=s_top)
					{
						lift.style(i);
					}
				}
			}
			else
			{
				$(".lift").fadeOut(500);
			}
		},
		init:function()
		{
			lift.get_pos();
			lift.go();
			lift.go_top();
			$(window).on("scroll",lift.scroll);
		}
	};
	lift.init();
	/* //电梯效果 end*/
	
	/* //首页Flash 切换时间设置  */
	$('.carousel').carousel({
	  interval: 3000
	});
	/* //首页Flash 切换时间设置   end*/

});
/*对象方法************************************************************************************************************/
//上下滚动内容
$.fn.scrollcontent=function(interval)
{

    var $this=$(this);
    var box=$this.closest("div")
    var m=box.height();
    var n=$this.height();

    if(n>=m)
    {
        $this.append($this.html());
        var i=0;
        var timer;
        var start=function()
        {
            timer=setInterval(function()
            {
                box.scrollTop(i);
                i===n ? i=0 : i++;
            },interval);
        }
        var stop=function()
        {
            clearInterval(timer);
        }

        start();

        box.on("mouseover",stop).on("mouseout",start);
    }
};