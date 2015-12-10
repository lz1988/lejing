<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />

<title></title>
<link type="text/css" rel="stylesheet" href="__TMPL__css/common.css" />
<link type="text/css" rel="stylesheet" href="__TMPL__css/jquery.bxslider.css" />
</head>
<body>
<!--<nav>
    <ul class="page_nav clearfix">
        <li class="li_one">
            <span class="trigger">分类筛选</span>
            <ul class="left_menu">
                <?php $conf=C('ITEM_SEARCH'); $tnamearr=''; foreach($newstypearr as $nres){ $tnamearr[$nres[type_id]]=$nres[type_name]; } ?>
                <?php if(is_array($conf)): foreach($conf as $key=>$list): ?><li>
                    <a href="javascript:void(0)" class="m_list m<?php echo ($list["id"]); ?>"><span><?php if($_GET[$key]){echo $tnamearr[$_GET[$key]];}else{echo $list[name];} ?></span></a>
                    <div class="m_l_main">
                        <?php if(($list["id"] == 2) or ($list["id"] == 6)): ?><div <?php if($list["id"] > 5): ?>class="wrapper"<?php else: ?>class="wrapper wrapper2"<?php endif; ?>>
                            <div class="scroller"><?php endif; ?>
                        <?php if($_GET[$key]){ $new_param2=$where_param; unset($new_param2[$key]); $cea_url=U('Item/item_list',$new_param2); } if($_GET[$key]){echo "<a class='clean' href='{$cea_url}'>清除</a>";} ?>
                        <?php if(is_array($newstypearr)): foreach($newstypearr as $key2=>$list2): if(($list2["pid"]) == $list["id"]): $new_param=$where_param; $new_param[$key]=$list2[type_id]; $link_url=U('Item/item_list',$new_param); ?>
			            <a href="<?php echo ($link_url); ?>"><?php echo ($list2["type_name"]); ?></a><?php endif; endforeach; endif; ?>
			            <?php if(($list["id"] == 2) or ($list["id"] == 6)): ?></div>
			            </div><?php endif; ?>
                        <span></span>
                    </div>
                </li><?php endforeach; endif; ?>
            </ul>
            
        </li>
        <li class="li_two">
            <form action="" method="GET">
            <input type="text" name="keyword" value="<?php echo ($_GET['keyword']); ?>" class="s_input"><input type="submit" value="搜索" class="s_butt">
            </form>
        </li>
       
    </ul>
</nav>-->
<article class="page_content clearfix" style=" padding: 0px 0 0 0;">
    <div class="index_info">
        <div class="scroll">
            <ul class="bxslider">
                <li><img src="__TMPL__images/b1.jpg" width="100%"></li>
                <li><img src="__TMPL__images/b2.jpg" width="100%"></li>  
                <li><img src="__TMPL__images/b3.jpg" width="100%"></li>
                <li><img src="__TMPL__images/b4.jpg" width="100%"></li> 
                <li><img src="__TMPL__images/b5.jpg" width="100%"></li>    
            </ul>
        </div>        
    </div>
    <ul class="index_main">
        <li class="box1"><a href="/user/subscribe"><span>预约验配</span></a></li>
        <li class="box2"><a href="/index/news/news_id/2"><span>查看门店</span></a></li>
        <li class="box3"><a href="/index/news/news_id/3"><span>网店购买</span></a></li>
        <li class="box4"><a href="/user/validation_orders"><span>验光单查询</span></a></li>
        <li class="box5"><a href="/index/news/news_id/4"><span>活动专区</span></a></li>
    </ul>
</article> 
<footer class="footer_nav">
<ul class="app-nav">
        <li><a href="/" class="lottery">首页</a></li>
        <li>
            <a href="#" class="recharge">产品</a>
            <div class="bootm_menu">
                <a href="/item/item_list">眼镜架</a>
                <a href="/item/item_list">眼镜片</a>
                <a href="/item/item_list">太阳镜</a>
            </div>
        </li>
        <li><a href="/item/cart" class="shopping-cart">试戴车</a></li>
        <li><a href="/user/index" class="my">我的</a></li>
</ul>
</footer>
<script type="text/javascript" src="__TMPL__js/jquery-1.8.2.js"></script> 
<script type="text/javascript" src="__TMPL__js/jquery.bxslider.min.js"></script>
<script type="text/javascript">
$(function(){   
    $('.bxslider').bxSlider({
        auto: true,
        mode: 'fade',
        captions: true
    }); 
    $(".select_color a").click(function(){
        $(this).addClass("cur");
        $(this).siblings().removeClass("cur");
    })
    
    $(".app-nav li").click(function () {
        $(this).find(".bootm_menu").toggle();
        $(document).one("click", function () {
            $(".bootm_menu").hide(); 
          
        });
        $(document).one("touchmove", function () {
            $(".bootm_menu").hide(); 
           
        });
            event.stopPropagation();
    })  
});
</script>

</body>
</html>