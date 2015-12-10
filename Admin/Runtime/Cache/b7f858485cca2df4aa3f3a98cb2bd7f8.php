<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo ($cur_title); ?></title>
<meta name="author" content="OEdev">
<script src="__PUBLIC__/Admin/js/jquery.min.js"></script>
<script src="__ROOT__/Public/Admin/js/DatePicker/WdatePicker.js" type="text/javascript"></script> 
<link type="text/css" rel="stylesheet" href="__ROOT__/Public/Admin/css/admin.css">
<link rel="stylesheet" href="__PUBLIC__/Admin/css/jquery.msgbox.css"/>  
</head>
<body>
<div class="main-wrap">
  <div class="path"><p><?php echo ($cur_menu); ?></p></div>
  
  <div class="main-cont">
  <h3 class="title">
	<a href="__APP__/incomestatement/incomestatement_add/" class="btn-general"><span>添加</span></a>收入统计报表
	</h3>
	
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
          <th width="3%"><div class="th-gap">编号</div></th>
		  <th width="5%"><div class="th-gap">日期</div></th>
	    <th width="6%"><div class="th-gap">店铺</div></th>
		<th width="3%"><div class="th-gap">参与计算</div></th>
          <th width="6%"><div class="th-gap">营业收入（定价）</div></th>
		  <th width="6%"><div class="th-gap">营业收入（折扣后）</div></th>
		<th width="4%"><div class="th-gap">其他业务收入</div></th>
		<th width="6%"><div class="th-gap">公允价值变动收益</div></th>		
		<th width="5%"><div class="th-gap">营业外收入</div></th>		
		<th width="5%"><div class="th-gap">公司补贴收益</div></th>		
		<th width="5%"><div class="th-gap">加盟预算返还</div></th>		
		<!--<th width="6%"><div class="th-gap">备注</div></th>-->
		<th width="6%"><div class="th-gap">操作</div></th>		
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <tbody  id="tableClass">
	  <?php if(is_array($incomestatement_arr)): foreach($incomestatement_arr as $key=>$name): ?><tr onmouseover="overColor(this)" onmouseout="outColor(this)">
	  <td align="center" class="hback"><?php echo ($name["id"]); ?></td>
	  <td align="center" class="hback"><?php echo ($name["input_date"]); ?></td>
          <td align="center" class="hback"><?php echo ($name["shop_name"]); ?></td>
		  <td align="center" class="hback"><?php if($name["is_used"] == '0'): ?>是<?php else: ?>否<?php endif; ?></td>
	    <td align="center" class="hback"><?php echo ($name["price"]); ?></td>
          <td align="center" class="hback"><?php echo ($name["discount"]); ?></td>
		   <td align="center" class="hback"><?php echo ($name["other_price"]); ?></td>
	    <td align="center" class="hback"><?php echo ($name["price_change"]); ?></td>
	    <td align="center" class="hback"><?php echo ($name["operating_income"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["subsidy_income"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["return_amount"]); ?></td>
		<!--<td align="center" class="hback"><?php echo ($name["remark"]); ?></td>-->
		 
	   <td align="center" class="hback">
		<!-- 添加子栏目 -->
			<a href="__APP__/incomestatement/incomestatement_edit/id/<?php echo ($name["id"]); ?>" class="icon-edit">编辑</a>
			&nbsp;
			<a  href="__APP__/incomestatement/incomestatement_delete/id/<?php echo ($name["id"]); ?>" onclick="{if(confirm(&#39;确定要删除吗？一旦删除无法恢复！&#39;)){return true;} return false;}" class="icon-del" >删除</a>
		</td>

	 
	  </tr><?php endforeach; endif; ?> 
	  	</tbody></table>

		<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" style="margin-top:10px;">
	  <tbody><tr>
		<td align="center" class="page"><?php echo ($page); ?></td>
	  </tr>
	</tbody></table>
	  </div>
</div>

<script type="text/javascript">
</script>

</body></html>