<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title><?php echo C('WEB_SITE_TITLE');?></title>
    <meta name="description" content="<?php echo C('WEB_SITE_DESCRIPTION');?>"/>
    <meta name="keywords" content="<?php echo C('WEB_SITE_KEYWORD');?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
     <link rel="shortcut icon" href="/Public/Home/images/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="/Public/Home/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/Public/Home/css/normalize3.0.2.min.css">
    <link rel="stylesheet" type="text/css" href="/Public/Home/css/style.css">
    <!--[if lt IE 9]>
    <script src="../js/html5.js"></script>
    <script src="../js/ie9.js"></script>
    <![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>
    <!--head begin-->
    <div class="head">
        <div class="container">
            <div class="row ">
            	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                   <div class="logo_seach">
                    <h1><a href="/"><img src="/Public/Home/images/logo.png"  height="50"></a></h1>
                        <div><input type="text" placeholder="搜索全网折扣..." /><p><a href="#"><img src="/Public/Home/images/seach.gif" alt="" /></a></p></div>
                   </div>
                </div>  
            </div>
               
        </div>
    </div>
   

    <!--head over-->
    <div class="container">
        <div class="row">
            <!--  左侧 begin -->
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            	<div class="tscroll">
                	<div class="w1200">
                        <div class="title">
                            <ul><li class="hover"><a href="/">国内折扣</a></li>
                                <!--li><a href="/">海淘折扣</a></li>
                                <li><a href="#">小时风云榜</a></li>
                                <li><a href="#">九块九</a></li-->
                            </ul>
                            <p>近10天共收录<?php echo ($total_count); ?>条 / 第<?php echo $_GET['p']?$_GET['p']:'1';?>页</p>
                            <div class="clearfix"></div>
                            <div style="text-align:center;">
                            <div id="uptext2" class="uptext2"></div>
                            </div>
                        </div>
                        <div class="rseach"><input type="text" placeholder="搜索全网折扣..." /><p><a href="#"><img src="/Public/Home/images/seach2.gif" alt="" /></a></p></div>
                    </div>
               	</div>
                
                <!--  web begin -->
                <div class="main_l">
                	<div class="title2">
                    	<ul class="hr">
                        <li><span>类别：</span>
                        <a href="/list/all" <?php if($cate == '' or $cate == 'all'): ?>class="hover"<?php endif; ?> >全部</a>
                        <?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><a href="/list/<?php echo ($item["key"]); ?>" <?php if($cate == $item['key']): ?>class="hover"<?php endif; ?>><?php echo ($item["name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                        </li>
                        <li><span>商城：</span>
                        <a href="#" class="hover">全部</a>
                        <?php if(is_array($mall_list)): $i = 0; $__LIST__ = $mall_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><a href="<?php echo ($item["id"]); ?>"><?php echo ($item["mall_name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                        </li>
                        </ul>
                    </div>
                    <!--div class="hour">
                    	<div class="hr">
                        	<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 hourdiv">
                            	<p><img src="/Public/Home/images/icon.gif" alt="" /></p>
                                <h3>半小时内最热门</h3><h4>每5分钟刷新一次</h4>
                            </div>
                            <ul class="col-lg-10 col-md-10 col-sm-9 col-xs-12 hourdiv2">
                            	
                                <li><a href="#">
                            		<p><img src="/Public/Home/images/img01.gif" alt="" /></p>
                                    <h3><b>1.</b>标题</h3>
                                    </a>
                                </li>
                                
                            </ul>
                        </div>
                    </div-->
                    <div class="uptext" id="uptext" style="display: none;">&bull; <a href="#">有新发布条目，点此查看 &gt;</a></div>
                    <div class="imglist">
                    	<ul>
                        <?php if(is_array($list_data)): $i = 0; $__LIST__ = $list_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li class="hr">
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
                                    <p class="mb0"><a href="/info/<?php echo ($item["id"]); ?>" target="_blank"><img src="<?php echo (get_cover($item["pic"],'path')); ?>" alt="" class="img" /></a></p>
                                </div>
                                <dl class="col-lg-10 col-md-10 col-sm-9 col-xs-9">
                                    <dt><h1><a href="/info/<?php echo ($item["id"]); ?>" target="_blank"><?php echo ($item["title"]); ?></a></h1>
                                    	 <h3><?php echo ($item["desc"]); ?>...  <span><a href="/info/<?php echo ($item["id"]); ?>">完整阅读&gt;</a></span></h3>
                                         <h4><span><?php echo ($item["source_time"]); ?></span> <?php echo ($item["source_name"]); ?></h4>
                                    </dt>
                                    <dd>
                                    	<p><a href="/go2url/<?php echo ($item["id"]); ?>" target="_blank" class="imglistsub">直达链接 &gt;</a></p>
                                        <h3><?php if($item['target']): ?>商城：<span><?php echo ($item["target"]); ?></span><?php endif; ?></h3>
                                    </dd>
                                </dl>
                        	</li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div>
                    <!--  web over -->
                </div>
                <!--  分页 begin -->
                <div class="page"> <?php echo ((isset($_page) && ($_page !== ""))?($_page):''); ?> </div>
                <!--  分页 over -->
                
                
                <!--  phone begin -->
                <div class="mphone">
                	
                    <div class="mhidden">
                    	<?php if(is_array($list_data)): $i = 0; $__LIST__ = $list_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li>
                    		<h1><a href="/info/<?php echo ($item["id"]); ?>" target="_blank"><?php echo ($item["title"]); ?></a></h1>
                            <dl>
                            	<dt><img src="<?php echo (get_cover($item["pic"],'path')); ?>" alt="" class="img" /></dt>
                                <dd>
                                	<h3><?php echo ($item["desc"]); ?>...</h3>
                                    <h4><?php if($item['target']): ?><span><?php echo ($item["target"]); ?></span><?php endif; echo ($item["source_time"]); ?> <?php echo ($item["source_name"]); ?></h4>
                                </dd>
                            </dl>
                            <h5><a href="/info/<?php echo ($item["id"]); ?>" target="_blank" class="grsub">展开全文</a><a href="/go2url/<?php echo ($item["id"]); ?>" target="_blank" class="orgsub">立即购买</a></h5>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?> 
                    </div>
                    <ul class="list"></ul>
       			    <h2 class="moreload"><a class="moresub" href="javascript:;" onClick="upload.loadMore();">加载更多5条...</a></h2>
                </div>
                <!--  phone over -->
                
            </div>
            
            <!--  左侧 over -->
            <!--  右侧 begin -->
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 main_r">
            	<div class="title4">
                	<div class="Bar"> 
                        <div> 
                        	<span style="width:<?php echo floor($today_count/$total*100);?>%;"></span>
                        </div> 
                    </div> 
                    今日<?php echo ($type); ?>发布：<?php echo ($today_count); ?>条 <span style="float:right;">预计<?php echo ($total); ?>条</span>
                </div>
            	<!--div class="main_l">
                	
                    <div class="remind">
                    	<ul><li><p><img src="/Public/Home/images/icon02.gif" alt="" /></p><h3><span>手机APP</span>iPhone & Android</h3></li>
                        	<li><p><img src="/Public/Home/images/icon03.gif" alt="" /></p><h3><span>浏览器插件</span>时时提醒好折扣</h3></li>
                            <li><p><img src="/Public/Home/images/icon04.gif" alt="" /></p><h3><span>一个框</span>网购最快入口</h3></li>
                        
                        </ul>
                    </div>
                </div-->
                
                
                <div class="mt20 main_l">
                	<div class="hr title3">常用网站导航</div>
                    <ul class="partner">
                    	<li class="hr"><a href="http://www.jd.com" target="_blank"><img src="/Public/Home/images/mall/jd.png" alt="" class="img" /></a></li>
                        <li class="hr"><a href="http://www.z.cn" target="_blank"><img src="/Public/Home/images/mall/z.png" alt="" class="img" /></a></li>
                        <li class="hr"><a href="http://www.suning.com" target="_blank"><img src="/Public/Home/images/mall/suning.png" alt="" class="img" /></a></li>
                        <li class="hr"><a href="http://www.kaola.com/" target="_blank"><img src="/Public/Home/images/mall/kaola.png" alt="" class="img" /></a></li>
                        <li class="hr"><a href="http://www.dangdang.com" target="_blank"><img src="/Public/Home/images/mall/dd.png" alt="" class="img" /></a></li>
                        <li class="hr"><a href="http://www.yhd.com" target="_blank"><img src="/Public/Home/images/mall/1.png" alt="" class="img" /></a></li>
                        <li class="hr"><a href="http://you.163.com" target="_blank"><img src="/Public/Home/images/mall/yanxuan.png" alt="" class="img" /></a></li>
                        <li class="hr"><a href="http://www.gome.com.cn" target="_blank"><img src="/Public/Home/images/mall/guomei.png" alt="" class="img" /></a></li>
                    </ul>
                </div>
                
                
                <!--div class="mt20 main_l">
                	<div class="hr title3">分类/热词</div>
                    <ul class="hotclass">
                    	<li><span>&bull;<a href="#">母婴</a></span><a href="#">尿布</a><a href="#">奶粉</a><a href="#">奶瓶</a><a href="#">婴儿车</a><a href="#">吸奶器</a></li>
                        <li><span>&bull;<a href="#">日用</a></span><a href="#">尿布</a><a href="#">奶粉</a><a href="#">奶瓶</a><a href="#">婴儿车</a><a href="#">吸奶器</a></li>
                        <li><span>&bull;<a href="#">家电</a></span><a href="#">尿布</a><a href="#">奶粉</a><a href="#">奶瓶</a><a href="#">婴儿车</a><a href="#">吸奶器</a></li>
                        <li><span>&bull;<a href="#">食品</a></span><a href="#">尿布</a><a href="#">奶粉</a><a href="#">奶瓶</a><a href="#">婴儿车</a><a href="#">吸奶器</a></li>
                        <li><span>&bull;<a href="#">没装配饰</a></span><a href="#">尿布</a><a href="#">奶粉</a><a href="#">奶瓶</a><a href="#">婴儿车</a><a href="#">吸奶器</a></li>
                        <li><span>&bull;<a href="#">户外活动</a></span><a href="#">尿布</a><a href="#">奶粉</a><a href="#">奶瓶</a><a href="#">婴儿车</a><a href="#">吸奶器</a></li>
                    </ul>
                </div-->
                
                
                <div class="mt20 main_l aa">
                	<div class="hr title3">最热点击Top9<a href="#">全部&gt;</a></div>
                    <ul class="hot">
                    	<li><p><img src="/Public/Home/images/1.gif" alt="" /></p><h3>京东商城 太平鸟男装 条纹茄克衫外套 <span>69元包邮</span> 需用<span>券</span></h3></li>
                        <li><p><img src="/Public/Home/images/2.gif" alt="" /></p><h3>京东商城 太平鸟男装 条纹茄克衫外套 <span>69元包邮</span> 需用<span>券</span></h3></li>
                        <li><p><img src="/Public/Home/images/3.gif" alt="" /></p><h3>京东商城 太平鸟男装 条纹茄克衫外套 <span>69元包邮</span> 需用<span>券</span></h3></li>
                        <li><p><img src="/Public/Home/images/4.gif" alt="" /></p><h3>京东商城 太平鸟男装 条纹茄克衫外套 <span>69元包邮</span> 需用<span>券</span></h3></li>
                        <li><p><img src="/Public/Home/images/5.gif" alt="" /></p><h3>京东商城 太平鸟男装 条纹茄克衫外套 <span>69元包邮</span> 需用<span>券</span></h3></li>
                        <li><p><img src="/Public/Home/images/6.gif" alt="" /></p><h3>京东商城 太平鸟男装 条纹茄克衫外套 <span>69元包邮</span> 需用<span>券</span></h3></li>
                        <li><p><img src="/Public/Home/images/7.gif" alt="" /></p><h3>京东商城 太平鸟男装 条纹茄克衫外套 <span>69元包邮</span> 需用<span>券</span></h3></li>
                        <li><p><img src="/Public/Home/images/8.gif" alt="" /></p><h3>京东商城 太平鸟男装 条纹茄克衫外套 <span>69元包邮</span> 需用<span>券</span></h3></li>
                        <li><p><img src="/Public/Home/images/9.gif" alt="" /></p><h3>京东商城 太平鸟男装 条纹茄克衫外套 <span>69元包邮</span> 需用<span>券</span></h3></li>
                        
                    </ul>
                </div>
                
            </div>
            <!--  右侧 over -->
        </div>
    </div>
    
   
    <!--  footer begin -->
    <div class="footer">
      <div class="container">
        <div class="row">
        	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
            	<h3>关于我们</h3>
                <p>我得买是全网首个将多家折扣资讯网站进行智能聚合的引擎，每天更新数据1500-3000条，与数据来源网站保持分钟级别的同步，让您在一站获得全网折扣信息。</p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
            	<h3>官方微博</h3>
                <p>我得买是全网首个将多家折扣资讯网站进行智能聚合的引擎，每天更新数据1500-3000条，与数据来源网站保持分钟级别的同步，让您在一站获得全网折扣信息。</p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
            	<h3>微信公众号</h3>
                <p>我得买是全网首个将多家折扣资讯网站进行智能聚合的引擎，每天更新数据1500-3000条，与数据来源网站保持分钟级别的同步，让您在一站获得全网折扣信息。</p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
            	<h3>网站信息</h3>
                <p>我得买是全网首个将多家折扣资讯网站进行智能聚合的引擎，每天更新数据1500-3000条，与数据来源网站保持分钟级别的同步，让您在一站获得全网折扣信息。</p>
            </div>
        </div>
      </div>

    </div>
    <!--  footer over -->
    <!--  head slide begin -->
   
    <!--  head slide over -->
	<script type="text/javascript" src="/Public/Home/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/Home/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/Home/js/main.js"></script>
    <script>
		var _content = []; //临时存储li循环内容
		var upload = {
			_default:5, //默认显示图片个数
			_loading:5,  //每次点击按钮后加载的个数
			init:function(){
				var lis = $(".mphone .mhidden li");
				$(".mphone ul.list").html("");
				for(var n=0;n<upload._default;n++){
					lis.eq(n).appendTo(".mphone ul.list");
				}
				
				for(var i=upload._default;i<lis.length;i++){
					_content.push(lis.eq(i));
				}
				$(".mphone .mhidden").html("");
			},
			loadMore:function(){
				var mLis = $(".mphone ul.list li").length;
				for(var i =0;i<=upload._loading;i++){
					var target = _content.shift();
					if(!target){
                        $('.mphone .moreload').html("<p class='moresub'><a href='/Index/index/p/<?php echo $_GET['p']?$_GET['p']+1:2;?>.html'>下一页</a></p>");
						//$('.mphone .moreload').html("<div class='page'> <?php echo ((isset($_page) && ($_page !== ""))?($_page):''); ?> </div>");
						break;
					}
					$(".mphone ul.list").append(target);
					
				}
			}
		}
		upload.init();


        $(document).ready(function(e) {         
            t = $('.aa').offset().top;            
            $(window).scroll(function(e){
                s = $(document).scrollTop();    
                if(s > t){
                    $('.aa').addClass('aablock');                                 
                }else{
                    $('.aa').removeClass('aablock');
                }
            })
        });
        
	</script>



    <script type='text/javascript'>
    var maxid=<?php echo ($big_id); ?>;
    var cnt=0;
    var sh=setInterval(function(){
    $.get("/get_news/"+maxid, function(data){
        if(data.cnt>5)
        {
            var hrefhtml_s="<a href='/'>有"+data.cnt+"条新发布条目，点此查看 </a><span><a href='#'>关闭提示</a></span>";
            $("#uptext2").html(hrefhtml_s);
            //$("#uptext2").show();
            var hrefhtml_b="&bull; <a href='/'>有"+data.cnt+"条新发布条目，点此查看 &gt;</a>";
            $("#uptext").html(hrefhtml_b);
            $("#uptext").show();
            var curtitle="<?php echo C('WEB_SITE_TITLE');?>";
            var notifytitle='('+data.cnt+'条更新) '+curtitle;
            $(document).attr("title",notifytitle);
            
            if(data.cnt>200)
            {
                clearInterval(sh);
            }
        }
        //console.log(data);
    });
    cnt++;
    if(cnt>60)
    {
        clearInterval(sh);
    }
},5000);
</script>

</body>

</html>