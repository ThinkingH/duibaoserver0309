$.slide = function(config){
	var config_default = {
		step : 4	
	};
	var config = $.extend(config_default, config);
	var index_now = 0;//当前索引
	var indexes = config.slides.length;//总条数
	var slides = [];
	var slide_width = config.width;
	var slide_height = config.height;
	var zindex_0 = 0;
	var zindex_1 = 1;
	var zindex_2 = 2;
	var zindex_modal = 3;
	var slide = config.container;
	var accordion_height = config.accordion_height;//横向手风琴高
	var accordion_width = config.accordion_width;//纵向手风琴宽
	var playing = false;
	var step = config.step;//自动播放间隔时间
	var played = new Date();//记录上次播放的时间
	var auto = config.auto;
	var slideHover = false;
	var prevBt = config.prevBt;
	var nextBt = config.nextBt;
	var pages = config.pages;
	$.slide.stop = false;
	config.slides.each(function(k,v){
		slides.push($(v));
	});
	//创建下一张索引
	function createIndex(){
		var r = 0;
		 r = index_now + 1;
		if(r > indexes - 1){
			r =0;
		}	
		return r;
	}
	//创建上一张索引
	function preIndex(){
		var r = 0;
		 r = index_now - 1;
		if(r < 0){
			r = indexes - 1;
		}	
		return r;	
	}
	//创建一个自定义索引
	function customIndex(index){
		var r = index - 1;
		return r;
	}
	//更新当前索引
	function upindex(index){
		index_now = index;
		playing = false;
		return index_now;	
	}
	//播放对应索引的图片
	function play(index,type,direction,oldFly){
		if(index === index_now){
			return;
		}
		if(playing){
			return;
		}
		played = new Date();
		playing = true;
		
		if(typeof direction === 'boolean'){
			var oldFly = direction;	
		}
		init(index);
		config.pages.removeClass('hover_').eq(index).addClass('hover_');
		if(config.infoDom){
			$(config.infoDom).html((index+1)+'/'+indexes);
		}
		initPlay(index,type,direction);
		if(oldFly){
			oldPlay(index_now,type,direction);	
		}
		newPlay(index,type,direction);
	}
	function init(index){
		slides[index_now].css({
			zIndex : zindex_1	
		});
		slides[index].css({
			zIndex : zindex_2
		});
	}
	//播放初始化
	function initPlay(index,type,direction){
		//滑入
		if(type === 'fly'){
			direction = direction || 'l_r';
			if(direction === 'l_r'){
				slides[index].css({
					left : -slide_width,
					top : 0	
				});		
			}else if(direction === 'r_l'){
				slides[index].css({
					left : slide_width,
					top : 0	
				});	
			}else if(direction === 't_b'){
				slides[index].css({
					left : 0,
					top : -slide_height
				});	
			}else if(direction === 'b_t'){
				slides[index].css({
					left : 0,
					top : slide_height
				});		
			}
		}
		//渐隐
		else if(type === 'fade'){
			slides[index].addClass('none').css({
				left : 0,
				top : 0	
			});	
		}
		//手风琴
		else if(type === 'accordion'){
			direction = direction || 'horizontal';
			//手风琴--水平方向
			if(direction === 'horizontal'){
				var modal_con = modal('horizontal');
			}
			//手风琴--垂直方向
			else if(direction === 'vertical'){
				var modal_con = modal('vertical');
			}
			slide.append(modal_con);
			modal_con.find('.slide_modal_small_con').append(slides[index].clone().css({
				left : 0,
				top : 0
			})).css({
				overflow : 'hidden'
			});
		}
		//插入
		else if(type === 'insert'){
			direction = direction || 'horizontal';
			//插入--水平方向
			if(direction === 'horizontal'){
				var modal_con = modal('horizontal');
			}
			//插入--垂直方向
			else if(direction === 'vertical'){
				var modal_con = modal('vertical');
			}
			slide.append(modal_con);
			modal_con.find('.slide_modal_small_con').append(slides[index].clone().css({
				left : 0,
				top : 0
			})).css({
				overflow : 'hidden'
			});
			if(direction === 'horizontal'){
				modal_con.find('.slide_modal_small').each(function(k,v){
					if(k%2){
						$(this).css({
							left : -slide_width
						});
					}else{
						$(this).css({
							left : slide_width
						});
					}
				});
			}
			else if(direction === 'vertical'){
				modal_con.find('.slide_modal_small').each(function(k,v){
					if(k%2){
						$(this).css({
							top : -slide_height
						});
					}else{
						$(this).css({
							top : slide_height
						});
					}
				});
			}
		}
	}
	//播放旧片
	function oldPlay(index,type,direction){
		if(type === 'fly'){
			if(direction === 'l_r'){
				slides[index].animate({
					left : slide_width,
					top : 0	
				});		
			}else if(direction === 'r_l'){
				slides[index].animate({
					left : -slide_width,
					top : 0	
				});		
			}else if(direction === 't_b'){
				slides[index].animate({
					left : 0,
					top : slide_height	
				});		
			}else if(direction === 'b_t'){
				slides[index].animate({
					left : 0,
					top : -slide_height	
				});		
			}
		}
		else if(type === 'fade'){
	
			slides[index].fadeOut(function(){
				slides[index].show();
			});
		}
	}
	//播放新片
	function newPlay(index,type,direction){
		if(type === 'fly'){
			slides[index].animate({
				left : 0,
				top : 0	
			},function(){
				slides[index_now].css({
					'z-index' : 0,
					left : -9999,
					top : -9999
				});
				upindex(index);	
			});	
		}
		else if(type === 'fade'){
			slides[index].hide().fadeIn(function(){
				slides[index_now].css({
					'z-index' : 0,
					left : -9999,
					top : -9999
				});	
				upindex(index);
			});
		}
		else if(type === 'accordion'){
			//end变量用来判断动画是否结束
			//.slide_modal_small是一个集合，动画同事开始，同时结束，所以只要有一个结束及认为全部结束，不再执行其它回调函数
			var end = false;
			direction = direction || 'horizontal';
			if(direction === 'horizontal'){
				slide.find('.slide_modal_small').slideDown(function(){
					if(!end){
						end = true;
						endDealIA(index);
					}
				});
			}else if(direction === 'vertical'){
				slide.find('.slide_modal_small').animate({
					width : accordion_width
				},function(){
					if(!end){
						end = true;
						endDealIA(index);
					}
				});
			}
		}
		else if(type === 'insert'){
			var end = false;
			direction = direction || 'horizontal';
			if(direction === 'horizontal'){
				var aimCorr = {left : 0};
			}
			else if(direction === 'vertical'){
				slide.find('.slide_modal_small').show();
				slide.find('.slide_modal_small').css({
					width : accordion_width
				});
				var aimCorr = {top : 0};
			}
			slide.find('.slide_modal_small').show().animate(aimCorr,function(){
				if(!end){
					end = true;
					endDealIA(index);
				}
			});
		}
		else if(type === 'close'){
			//console.log('oh,close');
			var end = false;
			direction = direction || 'horizontal';
			if(direction === 'horizontal'){
				slide.find('.slide_modal_small').show().animate({
					top : $(this).prevAll().length * accordion_height
				},function(){
					if(!end){
						end = true;
						endDealIA(index);
					}
				});
			}
			else if(direction === 'vertical'){
				slide.find('.slide_modal_small').show();
				slide.find('.slide_modal_small').css({
					width : accordion_width
				});
				var aimCorr = {top : 0};
			}
			
		}
	}
	//创建一个div遮罩
	function modal(direction){
		var r;
		var modalDiv = $('<div class="slide_modal" style="position:relative;z-index:'+zindex_modal+';"></div>');
		modalDiv.css({
			width : slide_width,
			height : slide_height,
			position : 'relative'
		});
		modalDiv.appendTo(slide);
		if(direction === 'horizontal'){
			var num = slide_height/accordion_height;
			var i = 0;
			while(i < num){
				i++;
				modalDiv.append('<div class="slide_modal_small" style="position:absolute;display:none;overflow:hidden;\
				top:'+(i-1)*accordion_height+'px;width:'+slide_width+'px;height:'+accordion_height+'px;">\
				<div class="slide_modal_small_con" style="position:absolute;top:'+(-accordion_height*(i-1))+'px;height:'+slide_height+'px;width:'+slide_width+'px"></div></div>');
			}
		}else if(direction === 'vertical'){
			var num = slide_width/accordion_width;
			var i = 0;
			while(i < num){
				i++;
				modalDiv.append('<div class="slide_modal_small" style="position:absolute;width:0px;overflow:hidden;\
				left:'+(i-1)*accordion_width+'px;height:'+slide_height+'px;">\
				<div class="slide_modal_small_con" style="position:absolute;left:'+(-accordion_width*(i-1))+'px;height:'+slide_height+'px;width:'+slide_width+'px"></div></div>');
			}
		}
		r = modalDiv;
		return r;
	}
	//插入  手风琴   动画结束后处理
	function endDealIA(index){
		slide.find('.slide_modal').remove();
		slides[index].css({
			left : 0,
			top : 0,
			display : 'block',
			'z-index' : zindex_0
		});
		slides[index_now].css({
			left : -9999,
			top : -9999
		});	
		upindex(index);
		slide.find('.slide_modal_small').stop(true,false);
	}
	slide.mouseenter(function(){
		slideHover = true;
		played = new Date();	
	});
	slide.mouseleave(function(){
		slideHover = false;
		played = new Date();	
	});
	//自动播放下一张
	function autoPlay(){
		//设置动画样式
		var step_time = new Date().getTime() - played.getTime();
		//时间不足间隔跳过该次函数
		if(step_time >= step*1000 && !slideHover && !$.slide.stop){
			play(createIndex(),getType(),getDirection(),getBoolean());
			setTimeout(function(){
				autoPlay();		
			},step*1000);	
		}else{
			if(slideHover && step_time >= step*1000){
				setTimeout(function(){
					autoPlay();	
				},step*1000);
				return;
			}
			setTimeout(function(){
				autoPlay();	
			},step*1000 - step_time);
		}
	}
	if(auto){
		setTimeout(function(){
			autoPlay();	
		},step*1000);	
	}
	//产生随机效果的函数
	var type_num = 0;
	var types = ['fly','fade','insert','accordion'];
	function getType_(){
		type_num ++;
		if(type_num > types.length - 1){
			type_num = 0;
		}
		return types[type_num];
	}
	function getType(){
		var r = '';
		if(config.type){
			r = config.type;
			if(r == 'scroll'){
				r = 'fly';
			}
		}else{
			type_num = Math.ceil(Math.random()*4)-1;
			r = types[type_num];	
		}
	//			console.log('--','type',r);
		return r;
	}
	function getDirection(){
		var r = '';
		if(config.direction){
			r = config.direction;
		}else{
			if(types[type_num] === 'fly'){
				var directions = ['l_r','r_l','t_b','b_t'];
				r = directions[Math.ceil(Math.random()*4)-1];
			}
			else if((types[type_num] === 'accordion') || (types[type_num] === 'insert')){
				var directions = ['vertical','horizontal'];
				r = directions[Math.ceil(Math.random()*2)-1];
			}	
		}
	//			console.log('direction',r);
		return r;
	}
	function getBoolean(){
		var r = false;
		if(config.type){
			if(config.type == 'fly'){
				r = false;
			}else if(config.type == 'scroll'){
				r = true;	
			}	
		}else{
			if((types[type_num] === 'fly') || (types[type_num]) === 'fade'){
				var directions = [true,false];
				r = directions[Math.ceil(Math.random()*2)-1];
			}	
		}
	//			console.log('oldFly',r);
		return r;
	}
	function desc(direction){
		var r = direction;
		switch(direction){
			case 'l_r':
				r = 'r_l';
				break;
			case 'r_l':
				r = 'l_r';
				break;
			case 't_b':
				r = 'b_t';
				break;
			case 'b_t':
				r = 't_b';
				break;
			default:
				r = direction;
		}
		return r;
	}
	prevBt.click(function(){
		play(preIndex(),getType(),getDirection(),getBoolean());
	});
	nextBt.click(function(){
		play(createIndex(),getType(),desc(getDirection()),getBoolean());
	});
	pages.each(function(k,v){
		$(v).click(function(){
			play(customIndex(k+1),getType(),getDirection(),getBoolean())
		});
	});
	if(config.stopBt){
		$(config.stopBt).click(function(){
			if($.slide.stop){
				$.slide.stop = false;
			}else{
				$.slide.stop = true;	
			}
			if(config.stopFun){
				config.stopFun($(config.stopBt),$.slide.stop);		
			}
		});
	}
};