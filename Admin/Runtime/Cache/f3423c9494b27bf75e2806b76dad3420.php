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
          <input type="text" id="sellno" name="sellno" value="<?php echo ($arr['sellno']); ?>" />
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
		  <option value="0" <?php if($arr['is_used'] == '0'): ?>selected="selected"<?php endif; ?>>是</option>
		  <option value="1" <?php if($arr['is_used'] == '1'): ?>selected="selected"<?php endif; ?>>否</option>
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
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>房租：</td>
       <td width="80%" >
          <input type="text" id="rent_money" name="rent_money" value="<?php echo ($arr['rent_money']); ?>" />
		   <input type="checkbox" value ="1" 
		  <?php if($arr['int_rent_money'] == '1'): ?>checked="checked"<?php endif; ?>
		  name="int_rent_money" />
       </td>
   </tr> 
   
    <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>转让进场：</td>
       <td width="80%" >
          <input type="text" id="transfer" name="transfer" value="<?php echo ($arr['transfer']); ?>" />
		   <input type="checkbox" value ="1" 
		  <?php if($arr['int_transfer'] == '1'): ?>checked="checked"<?php endif; ?>
		  name="int_transfer" />
       </td>
   </tr> 
   
    <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>公关费用：</td>
       <td width="80%" >
          <input type="text" id="pr_cost" name="pr_cost" value="<?php echo ($arr['pr_cost']); ?>" />
		   <input type="checkbox" value ="1" 
		  <?php if($arr['int_pr_cost'] == '1'): ?>checked="checked"<?php endif; ?>
		  name="int_pr_cost" />
       </td>
   </tr> 
   
   <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>水电卫生费：</td>
       <td width="80%" >
		     <input type="text" id="shuidianweisheng_fee" name="shuidianweisheng_fee" value="<?php echo ($arr['shuidianweisheng_fee']); ?>" />
			  <input type="checkbox" value ="1" 
		  <?php if($arr['int_shuidianweisheng_fee'] == '1'): ?>checked="checked"<?php endif; ?>
		  name="int_shuidianweisheng_fee" />
       </td>
   </tr> 

   <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>物业管理费：</td>
       <td width="80%" >
         <input type="text" id="wuye_fee" name="wuye_fee" value="<?php echo ($arr['wuye_fee']); ?>" />
		  <input type="checkbox" value ="1" 
		  <?php if($arr['int_wuye_fee'] == '1'): ?>checked="checked"<?php endif; ?>
		  name="int_wuye_fee" />
       </td>
   </tr> 
   <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>广告宣传：</td>
       <td width="80%" >
         <input type="text" id="advertising_fee" name="advertising_fee" value="<?php echo ($arr['advertising_fee']); ?>" />
		  <input type="checkbox" value ="1" 
		  <?php if($arr['int_advertising_fee'] == '1'): ?>checked="checked"<?php endif; ?>
		  name="int_advertising_fee" />
       </td>
   </tr> 
   
    <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>促销费：</td>
       <td width="80%" >
         <input type="text" id="cuxiao_fee" name="cuxiao_fee" value="<?php echo ($arr['cuxiao_fee']); ?>" />
		  <input type="checkbox" value ="1" 
		  <?php if($arr['int_cuxiao_fee'] == '1'): ?>checked="checked"<?php endif; ?>
		  name="int_cuxiao_fee" />
       </td>
   </tr> 
    <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>销售辅料：</td>
       <td width="80%" >
         <input type="text" id="xiaoshoufuliao_fee" name="xiaoshoufuliao_fee" value="<?php echo ($arr['xiaoshoufuliao_fee']); ?>" />
		  <input type="checkbox" value ="1" 
		  <?php if($arr['int_xiaoshoufuliao_fee'] == '1'): ?>checked="checked"<?php endif; ?>
		  name="int_xiaoshoufuliao_fee" />
       </td>
   </tr> 
   
    <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>装修及维护：</td>
       <td width="80%" >
         <input type="text" id="zhuangxiuweihu_fee" name="zhuangxiuweihu_fee" value="<?php echo ($arr['zhuangxiuweihu_fee']); ?>" />
		  <input type="checkbox" value ="1" 
		  <?php if($arr['int_zhuangxiuweihu_fee'] == '1'): ?>checked="checked"<?php endif; ?>
		  name="int_zhuangxiuweihu_fee" />
       </td>
   </tr> 
    <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>设备及维护：</td>
       <td width="80%" >
         <input type="text" id="shebeiweihu" name="shebeiweihu" value="<?php echo ($arr['shebeiweihu']); ?>" />
		  <input type="checkbox" value ="1" 
		  <?php if($arr['int_shebeiweihu'] == '1'): ?>checked="checked"<?php endif; ?>
		  name="int_shebeiweihu" />
       </td>
   </tr> 
    <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>折旧及摊销：</td>
       <td width="80%" >
         <input type="text" id="zhejiutuixiao" name="zhejiutuixiao" value="<?php echo ($arr['zhejiutuixiao']); ?>" />
		  <input type="checkbox" value ="1" 
		  <?php if($arr['int_zhejiutuixiao'] == '1'): ?>checked="checked"<?php endif; ?>
		  name="int_zhejiutuixiao" />
       </td>
   </tr> 
    <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>加盟费：</td>
       <td width="80%" >
         <input type="text" id="jiameng" name="jiameng" value="<?php echo ($arr['jiameng']); ?>" />
		  <input type="checkbox" value ="1" 
		  <?php if($arr['int_jiameng'] == '1'): ?>checked="checked"<?php endif; ?>
		  name="int_jiameng" />
       </td>
   </tr> 
    <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>品牌使用费：</td>
       <td width="80%" >
         <input type="text" id="pinpaishiyong_fee" name="pinpaishiyong_fee" value="<?php echo ($arr['pinpaishiyong_fee']); ?>" />
		  <input type="checkbox" value ="1" 
		  <?php if($arr['int_pinpaishiyong_fee'] == '1'): ?>checked="checked"<?php endif; ?>
		  name="int_pinpaishiyong_fee" />
       </td>
   </tr> 
    <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>加盟商分成：</td>
       <td width="80%" >
         <input type="text" id="jiameng_fee" name="jiameng_fee" value="<?php echo ($arr['jiameng_fee']); ?>" />
		  <input type="checkbox" value ="1" 
		  <?php if($arr['int_jiameng_fee'] == '1'): ?>checked="checked"<?php endif; ?>
		  name="int_jiameng_fee" />
       </td>
   </tr> 
   
    <tr>
       <td width="20%" height="30" align="right" class="left_txt2"><span class="must">*</span>其他：</td>
       <td width="80%" >
         <input type="text" id="qita" name="qita" value="<?php echo ($arr['qita']); ?>" />
		  <input type="checkbox" value ="1" 
		  <?php if($arr['int_qita'] == '1'): ?>checked="checked"<?php endif; ?>
		  name="int_qita" />
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