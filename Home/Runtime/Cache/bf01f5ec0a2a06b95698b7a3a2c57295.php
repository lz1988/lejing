<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0" />
<title>乐境微信管理系统-管理费用</title>
<link rel="stylesheet" href="__PUBLIC__/Home/css/style.css">
<script src="__PUBLIC__/Home/js/jquery.min.js"></script>
</head>

<body class="reg_body">
<header class="h_reg">
	欢迎你：<?php echo ($_SESSION['username']); ?> &nbsp;&nbsp; 【<?php echo ($_SESSION['nickname']); ?>】 &nbsp;&nbsp; <a href="__APP__/index/logout">退出</a>
</header>
<section class="main_reg">
	
	<table border="1" width="100%">
	<tr>
	<td>日期</td>
	<td>分店名称</td>
	<td>营业收入</td>
	<!-- <td>其他业务收入</td> -->
	<td>公允价值变动收益</td>
	<td>营业外收入</td>
	<td>公司补贴收益</td>
	<td>加盟预算返还</td>
        <td>汇总</td>
	</tr>
	<?php if(is_array($arr)): $i = 0; $__LIST__ = $arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
	<td><?php echo ($vo["input_date"]); ?></td>
	<td><?php echo ($vo["shop_name"]); ?></td>
	<td><?php echo ($vo["shouru"]); ?></td>
	<!-- <td><?php echo ($vo["other_price"]); ?></td> -->
	<td><?php echo ($vo["price_change"]); ?></td>
	<td><?php echo ($vo["operating_income"]); ?></td>
	<td><?php echo ($vo["subsidy_income"]); ?></td>
	<td><?php echo ($vo["return_amount"]); ?></td>
        <td><?php echo ($vo["sum"]); ?></td>
 	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	</table>
</section>
</body>
</html>