<?php if (!defined('THINK_PATH')) exit();?><script src="__ROOT__/Public/Admin/js/jquery-1.6.js"></script>
<script src="__ROOT__/Public/Admin/js/list.js"></script> 
<script src="__ROOT__/Public/Admin/js/DatePicker/WdatePicker.js" type="text/javascript"></script>
<link href="__ROOT__/Public/Admin/css/list.css" rel="stylesheet" type="text/css" />
 <DIV>&nbsp;</DIV>
<div >
    <div style="font-weight:bold;">欢迎进入<?php echo ($site_title); ?></div>
</div>
<DIV></DIV>
<div class="FF">
<div style="width:48%;height:150px;border: 1px solid #CCCCCC;background:#F2F2F2;margin-top:15px;float:left;">
   <div>我的个人信息</div>
   <div><?php echo ($user_name); ?></div>
   <div>角色：<?php echo ($role); ?></div>
   <div>最近登录时间：<?php echo (date('Y-m-d H:i:s',$login_time)); ?></div>
   <div>最近登录IP：<?php echo ($ip); ?></div>
   <div>登录次数：<?php echo ($count); ?>次</div>
</div>
<div style="width:48%;height:150px;border: 1px solid #CCCCCC;background:#F2F2F2;margin-top:15px;float:left;margin-left:3%;">
  <!-- <div>客户分类:</div>
   <div>今日生日： 23</div>
   <div>未分类用户： 42</div>
   <div>导入用户： 186</div>
   <div>试听用户： 19</div>
   <div>成交用户： 12</div>-->
</div>
<div style="width:48%;height:150px;border: 1px solid #CCCCCC;background:#F2F2F2;margin-top:15px;float:left;">
  <!-- <div>成交汇总:</div>
   <div>昨天成交： 23  笔，金额：1400.00元</div>
   <div>本周成交金额： 4200.00 元；实收款： 4000.00 元</div>
   <div>本月成交金额： 186000.00 元；实收款： 180000.00 元</div>
   <div>：</div>
   <div>：</div>-->
</div>
<div style="width:48%;height:150px;border: 1px solid #CCCCCC;background:#F2F2F2;margin-top:15px;float:left;margin-left:3%;">
</div>
</div>