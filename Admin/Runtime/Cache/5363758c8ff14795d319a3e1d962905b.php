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
	<a href="__APP__/employeepay/employeepay_add/" class="btn-general"><span>添加</span></a>员工工资结算表
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
	 
	<label>参与计算：</label>
	        <select name="is_used" class="input-120" id="is_used">
			<option value="8">请选择</option>
	         <option value="0">是</option>
			 <option value="1">否</option>
          </select>
    
	
   
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
        <th width="6%"><div class="th-gap">参入计算</div></th>
		<th width="6%"><div class="th-gap">姓名</div></th>
		<!--<th width="4%"><div class="th-gap">职务</div></th>-->
		<th width="6%"><div class="th-gap">标准工资</div></th>		
		<th width="6%"><div class="th-gap">职务补贴</div></th>		
		<th width="6%"><div class="th-gap">量化考核</div></th>		
		<th width="6%"><div class="th-gap">应出勤</div></th>		
		<th width="6%"><div class="th-gap">实出勤</div></th>		
		<th width="6%"><div class="th-gap">考勤工资</div></th>		
		<th width="6%"><div class="th-gap">加班</div></th>		
		<th width="6%"><div class="th-gap">加班费</div></th>		
		<th width="6%"><div class="th-gap">提成额</div></th>		
		<th width="6%"><div class="th-gap">业务奖罚</div></th>	
		<th width="6%"><div class="th-gap">行政奖罚</div></th>	
		<th width="6%"><div class="th-gap">餐补</div></th>	
		<th width="6%"><div class="th-gap">交通</div></th>	
		<th width="6%"><div class="th-gap">住宿</div></th>	
		<th width="6%"><div class="th-gap">代垫款</div></th>	
		<th width="6%"><div class="th-gap">代扣费</div></th>	
		<th width="6%"><div class="th-gap">代缴社保</div></th>
		<th width="6%"><div class="th-gap">实发工资</div></th>
		<!--th width="6%"><div class="th-gap">制单日期</div></th>-->
		<!--<th width="6%"><div class="th-gap">制单人</div></th>
		<th width="6%"><div class="th-gap">审批</div></th>
		<th width="6%"><div class="th-gap">会计</div></th>
		<th width="6%"><div class="th-gap">出纳</div></th>-->
		<th width="6%"><div class="th-gap">操作</div></th>

	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <tbody  id="tableClass">
	  <?php if(is_array($employee_pay_arr)): foreach($employee_pay_arr as $key=>$name): ?><tr onmouseover="overColor(this)" onmouseout="outColor(this)">
	  	<td align="center" class="hback"><?php echo ($name["id"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["shop_name"]); ?></td>
		<td align="center" class="hback"><?php if($name["is_used"] == '0'): ?>是 <?php else: ?> 否<?php endif; ?></td>
		<td align="center" class="hback"><?php echo ($name["nickname"]); ?></td>
		<!---<td align="center" class="hback"><?php echo ($name["job"]); ?></td>-->
	    <td align="center" class="hback"><?php echo ($name["gongzi"]); ?></td>
	    <td align="center" class="hback"><?php echo ($name["butie"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["lianghua"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["yingchuqin"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["shichuqin"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["kaoqinggongzi"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["jiaban"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["jibanfei"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["tichenge"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["yewuchengfa"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["xingzhengchufa"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["canbu"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["jiaotong"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["zhusu"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["daidiankuan"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["daikoufei"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["daijiaoshebao"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["shifagongzi"]); ?></td>
		<!--<td align="center" class="hback"><?php echo ($name["shenpi"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["huiji"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["chuna"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["zhidan"]); ?></td>-->
	   	<td align="center" class="hback">
		<!-- 添加子栏目 -->
			<a href="__APP__/employeepay/employeepay_edit/id/<?php echo ($name["id"]); ?>/" class="icon-edit">编辑</a>
			&nbsp;
			<a  href="__APP__/employeepay/employeepay_delete/id/<?php echo ($name["id"]); ?>/" onclick="{if(confirm(&#39;确定要删除吗？一旦删除无法恢复！&#39;)){return true;} return false;}" class="icon-del" >删除</a>
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