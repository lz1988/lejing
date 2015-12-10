<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>栏目设置</title>
<meta name="author" content="OEdev">
<script src="__ROOT__/Public/Admin/js/jquery-1.6.js"></script>
<script src="__ROOT__/Public/Admin/js/public.js"></script>
<link type="text/css" rel="stylesheet" href="__ROOT__/Public/Admin/css/admin.css" media="screen">

</head>
<body>
<div class="main-wrap">
  <div class="path"><p><?php echo ($cur_menu); ?></p></div>
  <div class="main-cont">
    <h3 class="title">
    <a href="__APP__/Appmenu/create_menu/" class="btn-general" <?php echo ($create_menu); ?>><span>生成菜单</span></a>
	<a href="__APP__/Appmenu/menu_add/" class="btn-general" <?php echo ($menu_add); ?>><span>添加微信菜单</span></a>
	
	菜单设置
	</h3>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
	    <th width="6%"><div class="th-gap">ID</div></th>
		<th><div class="th-gap">菜单名称</div></th>
		<th width="12%"><div class="th-gap">菜单类型</div></th>
		<th width="12%"><div class="th-gap">菜单标识</div></th>
		<th width="6%"><div class="th-gap">状态</div></th>
		<th width="6%"><div class="th-gap">排序</div></th>
		<th width="25%"><div class="th-gap">操作</div></th>
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <tbody  id="tableClass">
	  <?php if(is_array($menu_list)): foreach($menu_list as $key=>$list): ?><tr onmouseover="overColor(this)" onmouseout="outColor(this)">
	    <td align="center" class="hback"><?php echo ($list["id"]); ?></td>
		<td class="hback">
				<b><a href="__APP__/Appmenu/menu_list/type_id/<?php echo ($list["id"]); ?>/"><?php echo ($list["name"]); ?></a></b>
				&nbsp;&nbsp;
		</td>
		<td class="hback" align="center">
		<?php if($list["type"] == 'click'): ?>消息类型
		<?php else: ?>
		<font color="Blue">连接类型</font><?php endif; ?>
		</td>
		<td class="hback" align="center">一级菜单</td>
		<td align="center" class="hback">
			<?php if($list["status"] == 1): ?><img id="flag8" src="__ROOT__/Public/Admin/images/yes.gif" onclick="javascript:fetch_ajax(&#39;flag&#39;,&#39;8&#39;);" style="cursor:pointer;">
			<?php else: ?>
			<img id="flag8" src="__ROOT__/Public/Admin/images/no.gif" onclick="javascript:fetch_ajax(&#39;flag&#39;,&#39;8&#39;);" style="cursor:pointer;"><?php endif; ?>
		</td>
		<td align="center" class="hback"><?php echo ($list["sort"]); ?></td>
		<td align="center" class="hback">
		<!-- 添加子栏目 -->
		    <a href="__APP__/Appmenu/menu_add/<?php echo ($list["id"]); ?>/" class="icon-add" <?php echo ($menu_add); ?>>添加子菜单</a>
		    &nbsp;
		<!-- 修改 -->
			<a href="__APP__/Appmenu/menu_edit/<?php echo ($list["id"]); ?>/" <?php echo ($menu_edit); ?> class="icon-edit">编辑</a>
			&nbsp;
			<a <?php echo ($messagetype_del); ?> href="__APP__/Appmenu/menu_del/<?php echo ($list["id"]); ?>/" onclick="{if(confirm(&#39;确定要删除吗？一旦删除无法恢复！&#39;)){return true;} return false;}" class="icon-del">删除</a>
			 
		</td>
	  </tr>
	  
	  
	  <?php if(is_array($p_res)): foreach($p_res as $key=>$name): if($list[id]==$name[pid]){?>
	  <tr onmouseover="overColor(this)" onmouseout="outColor(this)">
	    <td align="center" class="hback"><?php echo $name[id]; ?></td>
		<td class="hback">
				&nbsp;&nbsp;&nbsp;&nbsp;├ <a href="__APP__/Appmenu/menu_list/id/<?php echo $name[id]; ?>/"><?php echo $name[name]; ?></a>
				&nbsp;&nbsp;
		</td>
		<td class="hback" align="center">
		<?php if($name["type"] == 'click'): ?>消息类型
		<?php else: ?>
		<font color="Blue">连接类型</font><?php endif; ?>
		</td>
		<td class="hback" align="center">
		   <font color="#008000">二级菜单</font>
		</td>
		<td align="center" class="hback">
			<?php if($name["status"] == 1): ?><img id="flag8" src="__ROOT__/Public/Admin/images/yes.gif" onclick="javascript:fetch_ajax(&#39;flag&#39;,&#39;8&#39;);" style="cursor:pointer;">	
			<?php else: ?>
			<img id="flag8" src="__ROOT__/Public/Admin/images/no.gif" onclick="javascript:fetch_ajax(&#39;flag&#39;,&#39;8&#39;);" style="cursor:pointer;"><?php endif; ?>	
		</td>
		<td align="center" class="hback"><?php echo $name[sort]; ?></td>
		<td align="center" class="hback">
		<!-- 添加子栏目 -->
			&nbsp;
		<!-- 修改 -->
			<a <?php echo $messagetype_edit;?> href="__APP__/Appmenu/menu_edit/<?php echo $name[id]?>/" class="icon-edit">编辑</a>
			&nbsp;
			<a {$menu_del} href="__APP__/Appmenu/menu_del/<?php echo $name[id]?>/" <?php if(in_array($name['id'],$notdel) OR $notdel=='ALL'){echo "style='display:none;'";} ?> onclick="{if(confirm(&#39;确定要删除吗？一旦删除无法恢复！&#39;)){return true;} return false;}" class="icon-del">删除</a>
		</td>
	  </tr>
	  <?php } endforeach; endif; endforeach; endif; ?>
	  	   
	  	   
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

</script>
</body></html>