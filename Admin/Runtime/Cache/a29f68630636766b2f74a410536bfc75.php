<?php if (!defined('THINK_PATH')) exit();?><link href="__ROOT__/Public/Admin/css/list.css" rel="stylesheet" type="text/css" />
<script src="__ROOT__/Public/Admin/js/jquery-1.6.js" type="text/javascript"></script>
<script src="__ROOT__/Public/Admin/js/formvalidator/formValidator-4.1.1.js" type="text/javascript" charset="UTF-8"></script>
<script src="__ROOT__/Public/Admin/js/formvalidator/formValidatorRegex.js" type="text/javascript" charset="UTF-8"></script> 
<script type="text/javascript">  
var jstype="<?php echo ($jstype); ?>"; 
var check_url="__APP__/Common/check_adminaccount/";
  
</script> 
<style type="text/css">
tr{height:30px;}
.STYLE1 {
	font-size: 12px;
	font-weight: bold;
}
</style>
<script src="__ROOT__/Public/Admin/js/manage/adminuser_add.js" type="text/javascript"></script> 
<!--<div class="currentposition">
    <div><a  href="__APP__/Index/home/"/>后台管理</a> >> <a  href="__APP__/Adminuser/adminuser_list/">管理成员管理</a> >><?php echo ($typeName); ?></div>
</div>-->
<div class="addclass">
<div class="addtopheight">&nbsp;</div>
<table width="100%" class="addsearchClass" id="tableClass" border="0" cellpadding="0" cellspacing="0">   
<form name="form1" id="form1" method="POST" action=""> 
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>模块标题：</td>
       <td width="80%" ><div style="float:left;"><input type="text" size="30" id="menu_title" name="menu_title"  /> <span class="note">模块的中文描述。</span></div><div class="note" id="accountTip" style="float:left;">*</div></td>
   </tr>  
   <?php if($mres["level"] != 0): ?><tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span><?php if($mres["level"] == 2): ?>方法名<?php else: ?>模块名<?php endif; ?>：</td>
       <td width="80%" ><div style="float:left;"><input type="text" id="func_name" size="30" name="func_name"  /> <span class="note">模块的URL构成,例子:填写User,则访问该模块www.XXX.com/admin.php/User/xxx。</span></div><div class="note" id="accountTip" style="float:left;">*</div></td>
   </tr><?php endif; ?>
   <?php if($mres["level"] == 1): ?><tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>url：</td>
       <td width="80%" ><div style="float:left;"><input type="text" id="url" size="30" name="url"  /> <span class="note">url 如:User/fun</span></div><div class="note" id="accountTip" style="float:left;">*</div></td>
   </tr><?php endif; ?>
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>备注：</td>
       <td width="80%" ><div style="float:left;"><input type="text" id="remark" name="remark" value="" /><span class="note">对该模块的备注。</span></div><div class="note" id="rpasswordTip" style="float:left;">*(6-20个字符，只允许字母、数字、下划线)</div></td>
   </tr> 
   <tr>
       <td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>排序：</td>
       <td width="80%" ><div style="float:left;"><input type="text" id="sort" size="3" name="sort" value="99" /><span class="note">对模块进行排序,数字越小排序越靠前。</span></div><div class="note" id="rpasswordTip" style="float:left;">*(6-20个字符，只允许字母、数字、下划线)</div></td>
   </tr> 
	<tr>
		<td width="10%" height="30" align="right" class="left_txt2"><span class="must">*</span>状态：</td>
		<td width="80%" >
			<div style="float:left;">
				<input type="radio" id="status_off" name="status" value="0" />关闭
				<input type="radio" id="status_on"  name="status" value="1" checked="checked" />开启
			</div>
		</td>
   </tr> 
   
   <tr>
       <td colspan="2">
          <div class="addoperation">
          <input type="submit" class="buttonsave" value="保存" name="sub_authority" />
          &nbsp;
          <input type="reset" class="buttoncancel" value="取消" onclick="javascript:history.go(-1);" name="B12" />
          </div>
       </td> 
   </tr>
</form>
</table>
<div>&nbsp;</div>
</div>