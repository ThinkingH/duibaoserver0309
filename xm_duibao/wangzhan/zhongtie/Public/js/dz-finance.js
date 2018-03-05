$(function(){
	
	//大宗首页焦点
	focus.init('#dz-list','#dz-next','#dz-prev','#dz-buttons','#dz-list,#dz-buttons');

	//大宗产业切换效果
	var slideOne = {
		speed:200,	//切换速度
		showLen:6,	//默认显示的数量
		cellWidth:160,	//每个图片盒子宽度
		parent:'.show-cells-box',	//父盒子
		prev:'.s-right-btn',	//前翻
		next:'.s-left-btn'	//后翻
	}
	imgSlideOne(slideOne);

	//友情链接展开效果
	setTimeout(function(){
		var liHeight = 84;//每行高度
		var linkNum = $('.mylinks-box').find('li').size();//友情链接个数
		var lineNum = Math.ceil(linkNum/5);//获得行数
		var flinks = $('#friend-links');
		if(linkNum>15){
			flinks.animate({'height':(liHeight*3+90)},500);
			$('.mylinks-box').animate({'height':(liHeight*3+'px')},500);
			$('.btn-showall').show().attr('flag','1');
		}
		$('.btn-showall').on('click',function(){
			if($(this).attr('flag')==='1'){
				flinks.animate({'height':(liHeight*lineNum + 90)},500);
				$('.mylinks-box').animate({'height':(liHeight*lineNum+'px')},500);
				$('.btn-showall').html('收起').attr('flag','0').css('background','url(../static/img/dz_107_up.jpg) no-repeat center right');
			}else{
				flinks.animate({'height':(liHeight*3 + 90)},500);
				$('.mylinks-box').animate({'height':(liHeight*3+'px')},500);
				$('.btn-showall').html('展开').attr('flag','1').css('background','url(../static/img/dz_107.jpg) no-repeat center right');
			}
			
		});
	},500);
	
})