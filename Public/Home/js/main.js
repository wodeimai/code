// JavaScript Document
$(function(){

	var oH = $(window).height();	//获取屏幕高度
	    oW = $(window).width();	//获取屏幕宽度
	$(window).scroll(function(){

		var oH = $(window).height();

		if ($(window).scrollTop() > oH/4 ){
			$(".tscroll").addClass('tscrollblock')
		}else{
			$(".tscroll").removeClass('tscrollblock')
		}
	});  
	//列表背景颜色变化
	$('.imglist li').hover(function(){
		$(this).css('background','#fafafa');
	},function(){
		$(this).css('background','#fff');
	});
 })
			
	
	
