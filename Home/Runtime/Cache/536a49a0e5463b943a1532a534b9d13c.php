<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head>    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    <title><?php echo ($title); ?></title>    <link href="__PUBLIC__/Home/css/common.css" rel="stylesheet" type="text/css"/><!--公共css代码 自适应宽度-->    <link href="__PUBLIC__/Home/css/jdt.css" rel="stylesheet" type="text/css"/><!--轮播图片css代码 自适应宽度-->    <link href="__PUBLIC__/Home/css/subpage.css"  rel="stylesheet" type="text/css"/>    <script src="__PUBLIC__/Home/js/jquery.min.js"></script></head><body>
<!--<div class="header">
    <h1><?php echo ($res["item_name"]); ?></h1>
    <a href="#" class="btn_left"></a>
    <a href="#" class="btn_right"></a>
</div>--><!--页眉 结束-->
<div class="main">
    <div class="banner">
        <!-- 代码 开始 -->
        <div class="slide_container">
            <ul class="rslides" id="slider">
                <?php if(is_array($img_list)): foreach($img_list as $key=>$list): ?><li>
                    <img src="<?php echo ($list["img_path"]); ?>" alt="">
                </li><?php endforeach; endif; ?>
            </ul>
        </div>
        <div style="clear:both"></div>
        <!-- 代码 结束 -->
    </div><!--banner 结束 -->
    <div class="shop_name">
        <h2><?php echo ($res["item_name"]); ?><span>￥<?php echo ($res["item_price"]); ?></span></h2>
        <div style="clear:both"></div>
    </div>
    <div class="shop_content">
        <p><?php echo ($res["detail"]); ?></p>
        <dl class="buy_cart">
            <dt>购买份数：</dt>
            <dd>
                <a href="javascript:void(0);" class="cut">-</a>
                <input type="text" value="1" class="Number"/>
                <a href="javascript:void(0);" class="add">+</a>
            </dd>
        </dl>
        <div style="clear:both"></div>
        <div class="ui_btn">
            <input type="hidden" name="shop_id" value="<?php echo ($res["shop_id"]); ?>"/>
            <a href="javascript:void(0);" onclick="add_cart()">立即购买</a>
            <a href="javascript:buy();">加入订单</a>
            <!--<a href="#">分享到朋友圈</a>-->
        </div>
    </div>
</div><!--main 结束 -->
<script>
    $(function(){
       var num = $(".Number");

       //产品数量加减
       $(".add").click(function(){
           num.val(parseInt(num.val()) + 1);
           var number = $(".Number").val();

           $.ajax({
               url: "<?php echo U('item/check_num');?>",
               type: "post",
               dataType: "json",
               data: "item_id=<?php echo ($_GET['item_id']); ?>&number="+number,
               success: function(data) {
                   if (data.status == '-1'){
                        $(".Number").val(data.data-1);
                        alert(data.info);
                   }
               }
           });
       })

        $(".cut").click(function(){
            if (parseInt(num.val()) == 1){
                $(this).attr("disabled",true);
            }else{
                num.val(parseInt(num.val() - 1));
            }
        })
    });
    //加入购物车
    function add_cart(){
        var num = $(".Number").val();
        $.ajax({
            url: "<?php echo U('item/add_cart');?>",
            type: "post",
            dataType: "json",
            data: "item_id=<?php echo ($_GET['item_id']); ?>&number="+num,
            success: function(data) {
                alert(data.info);
                //$('#tsinfo').html(data.info);
                //$('.msg_layer').show();
                if(data.status==1){
                    //var item_num=parseInt($('.item_num').text());
                    //$('.item_num').html(item_num+1)
                    if(ctype=='yes'){
                        //document.location="<?php echo U('Item/cart');?>";
                    }else{
                        //alert(data.info);
                    }
                }else{
                    //alert(data.info);
                }
            },
            cache: false,
            timeout: 5000,
            error: function() {
                alert("错误");
            }
        });
    }

    //加入订单
    function buy(){
        var num = $(".Number").val();
        $.ajax({
            url: "<?php echo U('item/add_cart');?>",
            type: "post",
            dataType: "json",
            data: "item_id=<?php echo ($_GET['item_id']); ?>&number="+num,
            success: function(data) {
                //alert(data.info);
                //$('#tsinfo').html(data.info);
                //$('.msg_layer').show();
                if(data.status == 1){
                    //var item_num=parseInt($('.item_num').text());
                    //$('.item_num').html(item_num+1)
                   /* if(ctype=='yes'){
                        //document.location="<?php echo U('Item/cart');?>";
                    }else{
                        //alert(data.info);
                    }*/
                    location.href = '/item/consignee';
                }else{
                    alert(data.info);
                }
            },
            cache: false,
            timeout: 5000,
            error: function() {
                alert("错误");
            }
        });
    }


</script>
<footer>    <ul class="H_bottom">        <li><a href="/item/item_list"  class="menu onLight"></a></li>        <li><a href="/item/consignee" class="cart"></a></li>        <li><a href="" class="order"></a></li>        <li><a href="/user/userinfo" class="mxg"></a></li>    </ul>    <a class="backUp" href="#"></a></footer><!--页尾 结束--><script src="__PUBLIC__/Home/js/responsiveslides.min.js"></script><!--轮播图片js代码 自适应宽度--><script src="__PUBLIC__/Home/js/slide.js"></script><!--轮播图片js代码 自适应宽度--></body></html>