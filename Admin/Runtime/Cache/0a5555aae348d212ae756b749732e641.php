<?php if (!defined('THINK_PATH')) exit();?><link href="__ROOT__/Public/Admin/css/list.css" rel="stylesheet" type="text/css" />
<script src="__ROOT__/Public/Admin/js/jquery-1.6.js" type="text/javascript"></script> 
<script src="__ROOT__/Public/Admin/js/DatePicker/WdatePicker.js" type="text/javascript"></script> 
<div class="currentposition">
    <div><?php echo ($cur_menu); ?> > <?php echo ($cur_title); ?></div>
</div>
<div class="addclass">
<div class="addtopheight">&nbsp;</div>
<form name="form1" method="POST" id="form1"  action=""> 
<table width="100%" class="addsearchClass" border="0" cellpadding="0" cellspacing="0">   
   <input type="hidden" name="id" value="<?php echo ($arr['id']); ?>" />
  <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>分店名称：</td>
       <td width="80%" >
         <select name="shop_id">
		 <?php if(is_array($shop_arr)): $i = 0; $__LIST__ = $shop_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["shop_id"]); ?>" <?php if($vo["shop_id"] == $arr['shop_id']): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["shop_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		 </select>
       </td>
   </tr>
    <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>日期：</td>
       <td width="80%" >
         <input type="text" id="input_date" name="input_date" value="<?php echo ($arr['input_date']); ?>" 
		 onfocus="WdatePicker({skin:'whyGreen',dateFmt: 'yyyy-MM-dd'})" />
       </td>
   </tr> 
   <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>姓名：</td>
       <td width="80%" >
         <select name="user_id">
		 <?php if(is_array($user_arr)): $i = 0; $__LIST__ = $user_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["user_id"]); ?>" <?php if($vo["user_id"] == $user_arr['user_id']): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["nickname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		 </select>
       </td>
   </tr>
   
   <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>参与分店计算：</td>
       <td width="80%" >
          <select name="is_used">
		  <option value="0" <?php if($arr["is_used"] == '0'): ?>selected="selected"<?php endif; ?>>是</option>
		  <option value="1" <?php if($arr["is_used"] == '1'): ?>selected="selected"<?php endif; ?>>否</option>
		  </select>
       </td>
   </tr> 
   
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>标准工资：</td>
       <td width="80%" >
          <input type="text" id="gongzi" name="gongzi" value="<?php echo ($arr['gongzi']); ?>" />
       </td>
   </tr> 
   
    <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>职务补贴：</td>
       <td width="80%" >
          <input type="text" id="butie" name="butie" value="<?php echo ($arr['butie']); ?>" />
       </td>
   </tr> 
   
    <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>量化考核：</td>
       <td width="80%" >
          <input type="text" id="lianghua" name="lianghua" value="<?php echo ($arr['lianghua']); ?>" />
       </td>
   </tr> 
   
    <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>应出勤：</td>
       <td width="80%" >
          <input type="text" id="yingchuqin" name="yingchuqin" value="<?php echo ($arr['yingchuqin']); ?>" />
       </td>
   </tr> 
   
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>实出勤：</td>
       <td width="80%" >
          <input type="text" id="shichuqin" name="shichuqin" value="<?php echo ($arr['shichuqin']); ?>" />
       </td>
   </tr> 

   
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>考勤工资：</td>
       <td width="80%" >
		     <input type="text" id="kaoqinggongzi" name="kaoqinggongzi" value="<?php echo ($arr['kaoqinggongzi']); ?>" />
       </td>
   </tr> 

   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>加班：</td>
       <td width="80%" >
         <input type="text" id="jiaban" name="jiaban" value="<?php echo ($arr['jiaban']); ?>" />
       </td>
   </tr> 
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>加班费：</td>
       <td width="80%" >
         <input type="text" id="jibanfei" name="jibanfei" value="<?php echo ($arr['jibanfei']); ?>" />
       </td>
   </tr> 
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>提成额：</td>
       <td width="80%" >
         <input type="text" id="tichenge" name="tichenge" value="<?php echo ($arr['tichenge']); ?>" />
       </td>
   </tr> 
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>业务奖罚：</td>
       <td width="80%" >
         <input type="text" id="yewuchengfa" name="yewuchengfa" value="<?php echo ($arr['yewuchengfa']); ?>" />
       </td>
   </tr> 
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>行政奖罚：</td>
       <td width="80%" >
         <input type="text" id="xingzhengchufa" name="xingzhengchufa" value="<?php echo ($arr['xingzhengchufa']); ?>" />
       </td>
   </tr> 
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>餐补：</td>
       <td width="80%" >
         <input type="text" id="canbu" name="canbu" value="<?php echo ($arr['canbu']); ?>" />
       </td>
   </tr> 
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>交通：</td>
       <td width="80%" >
         <input type="text" id="jiaotong" name="jiaotong" value="<?php echo ($arr['jiaotong']); ?>" />
       </td>
   </tr> 
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>住宿：</td>
       <td width="80%" >
         <input type="text" id="zhusu" name="zhusu" value="<?php echo ($arr['zhusu']); ?>" />
       </td>
   </tr> 
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>代垫款：</td>
       <td width="80%" >
         <input type="text" id="daidiankuan" name="daidiankuan" value="<?php echo ($arr['daidiankuan']); ?>" />
       </td>
   </tr> 
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>代扣费：</td>
       <td width="80%" >
         <input type="text" id="daikoufei" name="daikoufei" value="<?php echo ($arr['daikoufei']); ?>" />
       </td>
   </tr> 
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>代缴社保：</td>
       <td width="80%" >
         <input type="text" id="daijiaoshebao" name="daijiaoshebao" value="<?php echo ($arr['daijiaoshebao']); ?>" />
       </td>
   </tr> 

    <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>实发工资：</td>
       <td width="80%" >
         <input type="text" id="shifagongzi" name="shifagongzi" value="<?php echo ($arr['shifagongzi']); ?>" />
       </td>
   </tr> 

    <!--<tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>复核人：</td>
       <td width="80%" >
         <input type="text" id="name" name="name" value="<?php echo ($arr['name']); ?>" />
       </td>
   </tr> 

    <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>审核人：</td>
       <td width="80%" >
         <input type="text" id="name" name="name" value="<?php echo ($arr['name']); ?>" />
       </td>
   </tr> -->

   <!--<tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>备注：</td>
       <td width="80%" >
         <input type="text" id="name" name="name" value="<?php echo ($arr['name']); ?>" />
       </td>
   </tr> 
   -->
   <!--
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>菜单类型：</td>
       <td width="80%" >
          <input type="radio" name="type" <?php if($arr['type'] == 'click'): ?>checked<?php endif; ?> value="click"/>消息类型         &nbsp;&nbsp;&nbsp;&nbsp;
           <input type="radio" name="type" <?php if('view' == $arr['type']): ?>checked<?php endif; ?> value="view" />连接类型
       </td>
   </tr>
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>key或url：</td>
       <td width="80%" >
          <div style="float:left;"><input type="text" id="key_url" name="key_url" value="<?php echo ($arr['key_url']); ?>" size="50"/></div>
          <div class="note" style="float:left;" id="urlTip">连接地址或者消息KEY</div>
       </td>
   </tr> 
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"></td>
       <td width="80%" valign="top"> 
          <div class="note">*连接地址,需要连接的地址</div>
       </td>
   </tr>
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2">是否显示：</td>
       <td width="80%" ><div style="float:left;">
           <input type="radio" name="status" <?php if(($arr['status'] == '1') OR ($arr[0]['status'] == '')): ?>checked<?php endif; ?> value="1" id="status" />显示         &nbsp;&nbsp;&nbsp;&nbsp;
           <input type="radio" name="status" <?php if($arr['status'] == '0'): ?>checked<?php endif; ?> value="0" id="status" />不显示
          </div></td>
   </tr>

  
   <tr>
       <td height="10" align="right" class="left_txt2">消息内容：</td>
       <td><textarea name="remark" class="textarea" style="width:600px;height:300px;visibility:hidden;" cols="50" id="content" rows="2" ><?php echo ($arr['remark']); ?></textarea></td>
   </tr>
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2">排序：</td>
       <td width="80%" ><div style="float:left;"><input type="text" style="width:50px;" id="sort_order" size="3" name="sort" value="<?php echo ($arr['sort']); ?>" /></div><div class="note" style="float:left;" id="sort_orderTip">(*)</div></td>
   </tr>
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"></td>
       <td width="80%" valign="top"> 
          <div class="notnote">*数值越小，位置越靠前</div>
       </td>
   </tr>--> 
   <tr>
		<td width="20%" height="30" align="right" class="left_txt2"></td>
		<td width="80%" >
		<input type="submit" class="buttonsave" value="保存" name="sub_authority" />
		&nbsp;
		<input type="reset" class="buttoncancel" value="取消" onclick="javascript:history.go(-1);" name="B12" />
       </td> 
   </tr>

</table>
</form>
</div>