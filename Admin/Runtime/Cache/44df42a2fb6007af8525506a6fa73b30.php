<?php if (!defined('THINK_PATH')) exit();?><link href="__ROOT__/Public/Admin/css/list.css" rel="stylesheet" type="text/css" />
<script src="__ROOT__/Public/Admin/js/jquery-1.6.js" type="text/javascript"></script>  
<script type="text/javascript"> 
	function check_all(name,idi,idj){
        for(i=2;i<=idj; i++)
 		{	
 			var id=name+idi+i;
 			
 			var obj = document.getElementById(id);
 			obj.checked = obj.checked ? false : true;
 		} 
	}
	function check_all2(id){
		if($('.select'+id+' input:checkbox').attr("checked")){
           $('.select'+id+' input:checkbox').removeAttr("checked");
        }else{
           $('.select'+id+' input:checkbox').attr("checked",'true');
        }  
	}
</script>
<style type="text/css">
.addclass input{
height:10px;
line-height:10px;
}
.addoperation input{
height:30px;
line-height:30px;
}
</style>
<div class="currentposition">
    <div><?php echo ($cur_menu); ?>(<?php echo ($role_name); ?>)</div>
 
</div>
<div class="addclass">
<div class="addtopheight">&nbsp;</div>
<table width="100%" class="addsearchClass" border="0" cellpadding="0" cellspacing="0">   
<form name="form1" method="POST" id="form1"  action=""> 
   <input type="hidden" name="role_id" value="<?php echo ($role_id); ?>" />
   <tr> 
       <td width="80%"  >
       <div style="width:1271px;">
       <?php if(is_array($hello)): foreach($hello as $key=>$vo): ?><div style="">
         <div style="background-color: #cccccc;font-weight:bold;"><a href="javascript:void(0)"><?php echo ($vo["menu_title"]); ?></a></div>
         <?php if(is_array($MOD_NAME_ARR)): foreach($MOD_NAME_ARR as $kv2=>$vo2): if($vo['menu_id']==$vo2['pid']){ ?>
         <div style="text-align:right;width:145px;margin-top:5px;background-color: #cff;float:left;border: 1px solid #cccccc;margin-left:5px;margin-right:15px;padding:3px;padding-left:5px;padding-right:10px;">
             <?php echo ($vo2["menu_title"]); ?>
             <input type="checkbox" onclick="check_all2('<?php echo ($vo2["menu_id"]); ?>');" name="" value="" /> 
         </div>
         <?php if(is_array($menu_list3)): foreach($menu_list3 as $kv3=>$vo3): if($vo3['pid']==$vo2['menu_id']){ ?>
             <div style="margin-top:3px;float:left;margin-left:5px;margin-right:15px;padding:3px;padding-left:5px;padding-right:10px;" class="select<?php echo ($vo2["menu_id"]); ?>"> 
             <input type="checkbox" 
		     <?php if(is_array($rolepriv_list)): $i = 0; $__LIST__ = $rolepriv_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$arr): $mod = ($i % 2 );++$i; if($arr['menu_id']==$vo3['menu_id']){ echo "checked='checked'"; } endforeach; endif; else: echo "" ;endif; ?>
      name="modulemethods[]" value="<?php echo ($vo3["menu_id"]); ?>" /><?php echo ($vo3["menu_title"]); ?>
             </div>
             
             <?php } endforeach; endif; ?> 
         <div  style="height:33px;">  </div>
         <?php } endforeach; endif; ?> 
         </div><?php endforeach; endif; ?> 
      </div> 
       </td>
   </tr>   
   <tr>
       <td colspan="2">
          <div class="currentposition">  </div>
          <div class="addoperation" style="text-align:center;">
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