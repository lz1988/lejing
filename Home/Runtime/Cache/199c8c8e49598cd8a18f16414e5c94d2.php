<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<div class="main">
    <div class="banner">
        <!-- 代码 开始 -->
        <div class="slide_container">
            <ul class="rslides" id="slider">
                <?php if(is_array($rs)): $i = 0; $__LIST__ = $rs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                    <img src="<?php echo ($vo["lunbo_images"]); ?>" alt="">
                </li><?php endforeach; endif; else: echo "" ;endif; ?>

            </ul>
        </div>
        <!-- 代码 结束 -->
    </div><!--banner 结束 -->
    <div style="clear:both"></div>
<!--    <div class="food_box">
        <h2 class="food_title">新品推荐</h2>
        <ul class="new_food_list">
            <?php if(is_array($item_list)): foreach($item_list as $key=>$list): ?><li><a href="<?php echo U('item/detail',array('item_id'=>$list[item_id]));?>"><div class="new_food_list_pic"><img src="<?php echo ($list["icon"]); ?>" ></div><div class="food_name"><?php echo ($list["item_name"]); ?></div></a></li><?php endforeach; endif; ?>
        </ul>
    </div> &lt;!&ndash;列表 结束 &ndash;&gt;--><!--<?php echo ($_SESSION['username']); ?>-->

    <div class="food_box">
       <!-- <h2 class="food_title">堂食推荐</h2>
        <ul class="new_food_list">
            <?php if(is_array($item_list_tangshi)): foreach($item_list_tangshi as $key=>$list): ?><li><a href="<?php echo U('item/detail',array('item_id'=>$list[item_id]));?>"><div class="new_food_list_pic"><img src="<?php echo ($list["icon"]); ?>" ></div><div class="food_name"><?php echo ($list["item_name"]); ?><span class="New-price">￥<?php echo ($list["item_price"]); ?></span></div></a></li><?php endforeach; endif; ?>
        </ul>
        <h2 class="food_title">外卖推荐</h2>
        <ul class="new_food_list">
            <?php if(is_array($item_list_waimai)): foreach($item_list_waimai as $key=>$list): ?><li><a href="<?php echo U('item/detail',array('item_id'=>$list[item_id]));?>"><div class="new_food_list_pic"><img src="<?php echo ($list["icon"]); ?>" ></div><div class="food_name"><?php echo ($list["item_name"]); ?><span class="New-price">￥<?php echo ($list["item_price"]); ?></span></div></a></li><?php endforeach; endif; ?>
        </ul>-->
        <?php if(is_array($result)): $i = 0; $__LIST__ = $result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><h2 class="food_title"><?php echo ($vo["type_name"]); ?></h2>
        <ul class="new_food_list">
            <?php if(is_array($vo["type_id"])): $i = 0; $__LIST__ = $vo["type_id"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('item/detail',array('item_id'=>$item[item_id]));?>"><div class="new_food_list_pic"><img src="<?php echo ($item["icon"]); ?>" ></div><div class="food_name"><?php echo ($item["item_name"]); ?><span class="New-price">￥<?php echo ($item["item_price"]); ?></span></div></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul><?php endforeach; endif; else: echo "" ;endif; ?>

    </div> <!--列表 结束 -->
</div>
<footer>