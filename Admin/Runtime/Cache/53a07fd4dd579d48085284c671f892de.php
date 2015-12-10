<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo ($cur_title); ?></title>
<meta name="author" content="OEdev">
<script src="__PUBLIC__/Admin/js/jquery.min.js"></script>
<script src="__ROOT__/Public/Admin/js/DatePicker/WdatePicker.js" type="text/javascript"></script> 
<link type="text/css" rel="stylesheet" href="__ROOT__/Public/Admin/css/admin.css">
<script>
$(function(){
	$("#shop_id").val("<?php echo ($_POST['shop_id']); ?>");
	$("#is_used").val("<?php echo ($_POST['is_used']); ?>");
	$("#input_date_start").val("<?php echo ($_POST['input_date_start']); ?>");
	$("#input_date_end").val("<?php echo ($_POST['input_date_end']); ?>");
})
</script>
</head>
<body>
<div class="main-wrap">
  <div class="path"><p><?php echo ($cur_menu); ?></p></div>
  
  <div class="main-cont">
	<h3 class="title">
	<a href="__APP__/managefee/managefee_add/" class="btn-general"><span>添加</span></a>管理费用统计表
	</h3>
	
	<div class="search-area ">
	<form id="" name="formfind" action="" method="post">
	<div class="item">
	<label>门店：</label>
	 <select name="shop_id" class="input-120" id="shop_id">
	         <option value="">全部</option>
             <?php if(is_array($shop_arr)): foreach($shop_arr as $key=>$list): ?><option value="<?php echo ($list["shop_id"]); ?>" <?php if(($shop_id) == "z"): ?>selected="selected"<?php endif; ?>><?php echo ($list["shop_name"]); ?></option><?php endforeach; endif; ?>
          </select>
	<!--<label>宝宝名称：</label>
	<input type="text" name="true_name" class="input-80" value="<?php echo ($true_name); ?>" id="true_name" />-->
	 
	<label>日期：</label>
	<input type="text" id="input_date_start" onClick="WdatePicker()" size="11" class="Wdate" name="input_date_start" value="" />-
    <input type="text" id="input_date_end" onClick="WdatePicker()" size="11" class="Wdate" name="input_date_end" value="" />&nbsp;&nbsp;
	<input class="button_s" type="submit" name="findsub" value="搜 索">
	  <input class="button_s" type="submit" name="export" value="导出报表">
	</div>
	</form>
	</div>
	
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
        <th width="3%"><div class="th-gap">编号</div></th>
	    <th width="6%"><div class="th-gap">分店</div></th>
        <th width="6%"><div class="th-gap">报销日期</div></th>
		<th width="5%"><div class="th-gap">凭证单号</div></th>
		<th width="5%"><div class="th-gap">内容摘要</div></th>
		<th width="5%"><div class="th-gap">保险费用</div></th>		
		<th width="5%"><div class="th-gap">办公费</div></th>		
		<th width="5%"><div class="th-gap">通讯费</div></th>		
		<th width="5%"><div class="th-gap">差旅费</div></th>		
		<th width="5%"><div class="th-gap">福利费</div></th>		
		<th width="5%"><div class="th-gap">招待费</div></th>		
		<th width="5%"><div class="th-gap">车辆使用</div></th>		
		<th width="6%"><div class="th-gap">培训及咨询费</div></th>		
		<th width="6%"><div class="th-gap">证照费用</div></th>		
		<th width="5%"><div class="th-gap">质检年检</div></th>	
		<th width="5%"><div class="th-gap">公关费用</div></th>	
		<th width="6%"><div class="th-gap">折旧及摊销</div></th>	
		<th width="5%"><div class="th-gap">管理服务</div></th>	
		<th width="5%"><div class="th-gap">公摊费用</div></th>	
		<th width="5%"><div class="th-gap">其他</div></th>	
		<!--<th width="6%"><div class="th-gap">备注</div></th>	
		<th width="6%"><div class="th-gap">审核人</div></th>
		<th width="6%"><div class="th-gap">复核人</div></th>
		<th width="6%"><div class="th-gap">录入人</div></th>
		<th width="6%"><div class="th-gap">录入日期</div></th>-->
		<th width="6%">
		操作</th>
		
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <tbody  id="tableClass">
	  <?php if(is_array($manage_fee_arr)): foreach($manage_fee_arr as $key=>$name): ?><tr onmouseover="overColor(this)" onmouseout="outColor(this)">
	  	<td align="center" class="hback"><?php echo ($name["id"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["shop_name"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["input_date"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["orderno"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["zhaiyao"]); ?></td>
	    <td align="center" class="hback"><?php echo ($name["baoxian_fee"]); ?></td>
	    <td align="center" class="hback"><?php echo ($name["bangong_fee"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["tongxun_fee"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["chailv_fee"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["fuli_fee"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["zhaodai_fee"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["car_fee"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["peixun_fee"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["zhengjian_fee"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["zhijian_fee"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["gongguan_fee"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["zhejiu_fee"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["guanli_fee"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["gongtan_fee"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["qita_fee"]); ?></td>
		<!--<td align="center" class="hback"><?php echo ($name["remark"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["reviewer"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["checker"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["create_time"]); ?></td>-->
	   	<td align="center" class="hback">
		<!-- 添加子栏目 -->
			<a href="__APP__/managefee/managefee_edit/id/<?php echo ($name["id"]); ?>/" class="icon-edit">编辑</a>
			&nbsp;
			<a  href="__APP__/managefee/managefee_delete/id/<?php echo ($name["id"]); ?>/" onclick="{if(confirm(&#39;确定要删除吗？一旦删除无法恢复！&#39;)){return true;} return false;}" class="icon-del" >删除</a>
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