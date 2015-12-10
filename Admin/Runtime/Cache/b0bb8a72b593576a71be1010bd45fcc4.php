<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>栏目设置</title>
<meta name="author" content="OEdev">
<script src="__ROOT__/Public/Admin/js/jquery-1.6.js"></script>
<script src="__ROOT__/Public/Admin/js/public.js"></script>
<link type="text/css" rel="stylesheet" href="__ROOT__/Public/Admin/css/admin.css">
</head>
<body>
<div class="main-wrap">
  <div class="path"><p><?php echo ($cur_menu); ?></p></div>
  
  <div class="main-cont">
    <h3 class="title">
	<a href="__APP__/Role/role_add/" class="btn-general" <?php echo ($role_add); ?>><span>添加新角色</span></a>
	
	权限分组列表
	</h3>
	<div class="search-area ">
	<form id="" name="formfind" action="" method="GET">
	<div class="item">
	<label>角色名称：</label>
	<input type="text" class="input-150" name="role_name" value="<?php echo ($role_name); ?>" id="role_name" />
	<input class="button_s" type="submit" name="findsub" value="搜 索">
	<input class="button_s" type="button" name="findsub" value="显示所有" onclick="document.location='__APP__/Role/role_list/'">
	</div>
	</form>
	</div>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
	    <th width="4%"><div class="th-gap">ID</div></th>
	    <th width="8%"><div class="th-gap">角色名称</div></th>
		<th width="18%"><div class="th-gap">说明</div></th>
		
		<th><div class="th-gap">成员</div></th>
		<th width="28%"><div class="th-gap">操作</div></th>
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <tbody  id="tableClass">
	  <?php if(is_array($role_list)): foreach($role_list as $key=>$name): ?><tr onmouseover="overColor(this)" onmouseout="outColor(this)">
	    <td align="center" class="hback"><?php echo ($name["role_id"]); ?></td>
	    <td align="center" class="hback"><?php echo ($name["role_name"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["desc"]); ?></td>
		
		<td class="hback">
		  <?php $table=M('roleadmin'); $where['cms_roleadmin.role_id']=array('eq',$name['role_id']); $where['cms_admin.is_del']=array('eq','0'); $arr=$table->join('LEFT JOIN cms_admin ON cms_roleadmin.admin_id=cms_admin.admin_id')->field('cms_admin.account,cms_admin.admin_id,cms_admin.admin_name')->where($where)->select(); foreach ($arr as $id=>$key){ echo " <a href='__APP__/Adminuser/adminuser_edit/".$key['admin_id']."'/>".$key['admin_name']."(".$key['account'].")</a>&nbsp;|&nbsp;"; } ?>
		</td>
	 
		 
		<td align="center" class="hback">
		<!-- 添加子栏目 -->
		    <a href="__APP__/Role/member_manage/<?php echo ($name["role_id"]); ?>/<?php echo ($name["role_name"]); ?>/" class="icon-edit" <?php echo ($member_manage); ?>>成员管理</a>
		    &nbsp;
		<!-- 修改 -->
			<a href="__APP__/Role/rolepriv_allot/<?php echo ($name["role_id"]); ?>/<?php echo ($name["role_name"]); ?>/" class="icon-edit" <?php echo ($rolepriv_allot); ?>>分配权限</a>
			&nbsp;
			<a href="__APP__/Role/role_edit/<?php echo ($name["role_id"]); ?>/" class="icon-edit" <?php echo ($role_edit); ?>>编辑</a>
			&nbsp;
			<a <?php echo ($role_del); ?> href="__APP__/Role/role_del/<?php echo ($name["role_id"]); ?>/" onclick="{if(confirm(&#39;确定要删除吗？一旦删除无法恢复！&#39;)){return true;} return false;}" class="icon-del" <?php if(in_array($name['role_id'],$notdel) OR $notdel=='ALL'){echo "style='display:none;'";} ?>>删除</a>
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

<!--           以下为子栏目操作页面          -->

<script type="text/javascript">
//一级列表栏目
// $("#tableClass tr").hover(function (){
// 	alert(44);
// 	
// });
</script>
</body></html>