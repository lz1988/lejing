<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>栏目设置</title>
<meta name="author" content="OEdev">
<script src="__ROOT__/Public/Admin/js/jquery-1.6.js"></script>
<script src="__ROOT__/Public/Admin/js/public.js"></script>
<link type="text/css" rel="stylesheet" href="__ROOT__/Public/Admin/css/admin.css">
<style type="text/css">
#show{

}
</style>
</head>
<body>
<div class="main-wrap">
  <div class="path"><p><?php echo ($cur_menu); ?> > <?php echo ($res["menu_title"]); ?></p></div>
  
  <div class="main-cont">
    <h3 class="title">
    
	<a href="<?php echo U('Menu/add');?>/module_id/<?php echo ($request["pid"]); ?>" class="btn-general" <?php echo ($add); ?>><span>添加模块</span></a>
 
	<?php if($res["level"] == 0): ?>一级模块
	<?php elseif($res["level"] == 1): ?>
	二级模块
	<?php else: ?>
	操作方法<?php endif; ?>
	
	</h3>
	<div class="search-area ">
	<form id="" name="formfind" action="" method="GET">
	<input type="hidden" name="pid" value="<?php echo ($request["pid"]); ?>" />
	<div class="item">
	<label>模块名：</label>
	<input type="text" size="14" class="input-150" name="menu_title" value="<?php echo ($request["menu_title"]); ?>" id="phone" /> 
	
	<label>状态：</label>
	<select name="status">
                  <option value="3">全部</option>
                  <option value="1" <?php if($request["status"] == 1 ): ?>selected<?php endif; ?>>正常</option>
                  <option value="0" <?php if($request["status"] == 0 ): ?>selected<?php endif; ?>>冻结</option>
    </select>
	
	<input class="button_s" type="submit" name="findsub" value="搜 索">
	<input class="button_s" type="button" name="findsub" value="显示所有" onclick="document.location='<?php echo U('Menu/index');?>'">
	</div>
	</form>
	</div>
	<form action="" method="POST" name="orgtype" />
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
	    <th width="4%"><div class="th-gap">ID</div></th>
	    <th width="20%"><div class="th-gap">模块标题</div></th>
		<th width="50%"><div class="th-gap">备注</div></th>
		<th width="10%"><div class="th-gap">状态</div></th>
		<th width="20%"><div class="th-gap">操作</div></th>
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <tbody  id="tableClass">
	  <?php if(is_array($list)): foreach($list as $key=>$name): ?><tr onmouseover="overColor(this)" onmouseout="outColor(this)">
	    <td align="left" class="hback">
	       <input type="checkbox" <?php if(in_array($name['menu_id'],$notdel) OR $notdel=='ALL'){echo "disabled";} ?>  value="<?php echo ($name["menu_id"]); ?>" name="menu_id[]"><?php echo ($name["menu_id"]); ?>
	    </td>
		<td class="hback">
		   <?php echo ($name["menu_title"]); ?>
		</td>
	    <td class="hback">
		   <?php echo ($name["remark"]); ?>
		</td>
		<td align="center">
		<?php if(($name['status'] == '1')): ?><img id="flag8" alt="开启" src="__ROOT__/Public/Admin/images/yes.gif" onclick="javascript:fetch_ajax(&#39;flag&#39;,&#39;8&#39;);" style="cursor:pointer;">	
			<?php else: ?>
			<img id="flag8" alt="关闭" src="__ROOT__/Public/Admin/images/no.gif" onclick="javascript:fetch_ajax(&#39;flag&#39;,&#39;8&#39;);" style="cursor:pointer;"><?php endif; ?>
		</td> 
		<td align="center" class="hback">
		<!-- 添加子栏目 -->
		    <?php if($res["level"] != 2): ?><a href="<?php echo U('Menu/module_list');?>/pid/<?php echo ($name["menu_id"]); ?>" class="icon-edit">所属分类</a>
			&nbsp;<?php endif; ?>
			<a href="<?php echo U('Menu/edit');?>/id/<?php echo ($name["menu_id"]); ?>" class="icon-edit" <?php echo ($edit); ?>>编辑</a>
			&nbsp;
			<a <?php echo ($del); ?> href="<?php echo U('Menu/del');?>/menu_id/<?php echo ($name["menu_id"]); ?>" onclick="{if(confirm(&#39;确定要删除吗？一旦删除无法恢复！&#39;)){return true;} return false;}" class="icon-del" <?php if(in_array($name['menu_id'],$notdel) OR $notdel=='ALL'){echo "style='display:none;'";} ?> >删除</a>
		</td>
	  </tr><?php endforeach; endif; ?>
	  	   
	  <tr>
	    <td align="center" colspan="" class="hback"><input type="checkbox" name="del_idall" onclick="check_all('menu_id');" value=""/>选择</td>
	    <td align="left" colspan="7" class="hback"><input <?php echo ($del); ?> type="button" class="button" value="删除" onclick="check_from('删除','__APP__/Menu/del/')" name="delall" id="delall"/></td>
	    
	  </tr>	
	   
	  	</tbody></table>
	  </form>
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
// });$("Element".attr(key,value)")
//alert($("Element".attr(href,add)")).text());
</script>
</body></html>