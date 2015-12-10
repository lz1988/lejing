<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<meta name="viewport" id="WebViewport" content="width=620px,initial-scale=1,target-densitydpi=device-dpi,minimum-scale=0.5,maximum-scale=1,user-scalable=1" />  
<title>乐境微信管理系统</title>
<link rel="stylesheet" href="__PUBLIC__/Home/css/style.css">
<style type="text/css">  
@viewport  
{  
    zoom: 1.0;  
    width: 620px;  
}  
@-ms-viewport  
{  
    width: 620px;  
    zoom: 1.0;  
}
</style>  
<script src="__PUBLIC__/Home/js/jquery.min.js"></script>

<script language="javascript">  
if(screen.width<620)  
{  
    document.getElementById('WebViewport').setAttribute('content', 'width=620px,initial-scale=' + screen.width / 620 + ',target-densitydpi=device-dpi,minimum-scale=0.5,maximum-scale=1,user-scalable=1');  
}  
</script> 
<script>
$(function(){
	var postdate = "<?php echo ($_POST['postdate']); ?>";
	if (postdate){
		$("#postdate").val(postdate);
	}
})
</script>
</head>

<body class="reg_body">
<header class="h_reg">
	欢迎你：<?php echo ($_SESSION['username']); ?> &nbsp;&nbsp; 【<?php echo ($_SESSION['nickname']); ?>】&nbsp;&nbsp;<a href="__APP__/index/logout">退出</a>
</header>
<section class="main_reg">

	<div class="h_search">
	<form name="form1" method="post" action="">
	日期：<select name="postdate" id="postdate">
	<?php if(is_array($date_arr)): $i = 0; $__LIST__ = $date_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo); ?>" <?php if($vo == $datenow): ?>selected="selected"<?php endif; ?> ><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	</select>
	<input type="submit" name="submit" value="搜索"/>
	</form>
	</div>
	
	<table border="1" width="100%">
	<tr>
	<td width="13%">分店<br/>名称</td>
	<td>收入<br/>合计</td>
	<td>成本<br/>合计</td>
	<td>管理<br/>费用</td>
	<td>销售<br/>费用</td>
	<td>员工<br/>工资</td>
	<td>物流<br/>费用</td>
	<!-- <td>查看<br/>明细</td> -->
	</tr>
	<tr>
	
	
	<?php if(is_array($rs)): $i = 0; $__LIST__ = $rs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
	<td>
	<?php if($vo["zuizhong_mode"] == '0'): ?><a href="__APP__/index/view?shop_id=<?php echo ($vo["shop_id"]); ?>&input_date=<?php echo ($datenow); ?>"><?php echo ($vo["shop_name"]); ?></a>
	<?php else: ?>
	<?php echo ($vo["shop_name"]); endif; ?>
	</td>
	<td>
	<?php if($vo["sum_incomestatement_mode"] == '0'): ?><a href="__APP__/index/detail?shop_id=<?php echo ($vo["shop_id"]); ?>&input_date=<?php echo ($datenow); ?>&t=1"><?php echo ($vo["sum_incomestatement"]); ?></a>
	<?php else: ?>
		0<?php endif; ?>
	</td>
	<td>
	<?php if($vo["sum_cost_mode"] == '0'): ?><a href="__APP__/index/detail?shop_id=<?php echo ($vo["shop_id"]); ?>&input_date=<?php echo ($datenow); ?>&t=2"><?php echo ($vo["sum_cost"]); ?><a/>
	<?php else: ?>
	0<?php endif; ?>
	</td>
	<td>
	<?php if($vo["sum_manage_fee_mode"] == '0'): ?><a href="__APP__/index/detail?shop_id=<?php echo ($vo["shop_id"]); ?>&input_date=<?php echo ($datenow); ?>&t=3"><?php echo ($vo["sum_manage_fee"]); ?></a>
	<?php else: ?>
	0<?php endif; ?>
	</td>
	<td>
	<?php if($vo["sum_sell_fee_mode"] == '0'): ?><a href="__APP__/index/detail?shop_id=<?php echo ($vo["shop_id"]); ?>&input_date=<?php echo ($datenow); ?>&t=4"><?php echo ($vo["sum_sell_fee"]); ?></a>
	<?php else: ?>
	0<?php endif; ?>
	</td>
	<td>
	<?php if($vo["sum_employee_pay_mode"] == '0'): ?><a href="__APP__/index/detail?shop_id=<?php echo ($vo["shop_id"]); ?>&input_date=<?php echo ($datenow); ?>&t=5"><?php echo ($vo["sum_employee_pay"]); ?></a>
	<?php else: ?>
	0<?php endif; ?>
	</td>
	<td>
	<?php if($vo["sum_express_fee_mode"] == '0'): ?><a href="__APP__/index/detail?shop_id=<?php echo ($vo["shop_id"]); ?>&input_date=<?php echo ($datenow); ?>&t=6"><?php echo ($vo["sum_express_fee"]); ?></a>
	<?php else: ?>
	0<?php endif; ?>
	</td>
	
<!-- 	<td><a href="">查看</a></td>
 -->	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	
	<tr>
	<td><font color="#dc143c">总计</font></td>
	<td><?php echo ($_sum_incomestatement); ?></td>
	<td><?php echo ($_sum_cost); ?></td>
	<td><?php echo ($_sum_manage_fee); ?></td>
	<td><?php echo ($_sum_sell_fee); ?></td>
	<td><?php echo ($_sum_employee_pay); ?></td>
	<td><?php echo ($_sum_express_fee); ?></td>
	</tr>
	</table>
</section>
</body>
</html>