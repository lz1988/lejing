<?php if (!defined('THINK_PATH')) exit();?><link href="__ROOT__/Public/Admin/css/list.css" rel="stylesheet" type="text/css" />
<script src="__ROOT__/Public/Admin/js/jquery-1.6.js" type="text/javascript"></script> 
<script src="__ROOT__/Public/Admin/js/DatePicker/WdatePicker.js" type="text/javascript"></script> 

<div class="currentposition">
    <div><?php echo ($cur_menu); ?> > <?php echo ($cur_title); ?></div>
</div>
<div class="addclass">
<div class="addtopheight">&nbsp;</div>
<table width="100%" class="addsearchClass" border="0" cellpadding="0" cellspacing="0">   
<form name="form1" method="POST" id="form1"  action=""> 
   <input type="hidden" name="id" value="<?php echo ($arr['id']); ?>" />
   
    <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>日期：</td>
       <td width="80%" >
         <input type="text" id="input_date" name="input_date" value="<?php echo ($arr['input_date']); ?>" 
		 onfocus="WdatePicker({skin:'whyGreen',dateFmt: 'yyyy-MM-dd'})" />
       </td>
   </tr> 
   
    <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>凭证单号：</td>
       <td width="80%" >
          <input type="text" id="orderno" name="orderno" value="<?php echo ($arr['orderno']); ?>" />
       </td>
   </tr> 
   
   <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>分店名称：</td>
       <td width="80%" >
         <select name="shop_id">
		 <?php if(is_array($shop_arr)): $i = 0; $__LIST__ = $shop_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["shop_id"]); ?>" <?php if($vo["shop_id"] == $arr['shop_id']): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["shop_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		 </select>
       </td>
   </tr>
   
  <!--<tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>参与分店计算：</td>
       <td width="80%" >
          <select name="is_used">
		  <option value="0">是</option>
		  <option value="1">否</option>
		  </select>
       </td>
   </tr> -->
   
   <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>内容摘要：</td>
       <td width="80%" >
          <input type="text" id="zhaiyao" name="zhaiyao" value="<?php echo ($arr['zhaiyao']); ?>" />
       </td>
   </tr> 
   
    <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>快递公司：</td>
       <td width="80%" >
          <input type="text" id="express" name="express" value="<?php echo ($arr['express']); ?>" />
       </td>
   </tr> 
   
    <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>快递单号：</td>
       <td width="80%" >
          <input type="text" id="track_no" name="track_no" value="<?php echo ($arr['track_no']); ?>" />
       </td>
   </tr> 
   
    <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>自付快递费：</td>
       <td width="80%" >
          <input type="text" id="express_cost" name="express_cost" value="<?php echo ($arr['express_cost']); ?>" />
		  <input type="checkbox" value ="1" 
		  <?php if($arr['int_express_cost'] == '1'): ?>checked="checked"<?php endif; ?>
		  name="int_express_cost" />
       </td>
   </tr> 
   
   <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>公摊快递费：</td>
       <td width="80%" >
		     <input type="text" id="share_cost" name="share_cost" value="<?php echo ($arr['share_cost']); ?>" /><input type="checkbox"  value="1"
			 <?php if($arr['int_share_cost'] == '1'): ?>checked="checked"<?php endif; ?>
			 name="int_share_cost" />
       </td>
   </tr> 

   <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>备注：</td>
       <td width="80%" >
         <textarea name="remark" name="remark" style="width:300px;"><?php echo ($arr['remark']); ?></textarea>
       </td>
   </tr> 
  
   <!-- <tr>
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
   </tr> -->
   
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
</form>
</table>
</div>