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
	<a href="__APP__/Adminuser/adminuser_add/" class="btn-general" <?php echo ($adminuser_add); ?>><span>添加成员</span></a>
	
	<?php echo ($cur_title); ?>
	</h3>
	<div class="search-area ">
	<form id="" name="formfind" action="" method="GET">
	<div class="item">
	<label>帐号：</label>
	<input type="text" size="10" class="input-100" name="account" value="<?php echo ($account); ?>" id="account" /> 
	<label>真实姓名：</label>
	<input type="text" size="10" class="input-100" name="admin_name" value="<?php echo ($admin_name); ?>" id="admin_name" />
	<label>城市：</label>
	 <select name="city_id">
          <option value="0">全部</option>
          <?php if(is_array($city_list)): foreach($city_list as $key=>$list): ?><option  value="<?php echo ($list["id"]); ?>" <?php if($list["id"] == $city_id): ?>selected="selected"<?php endif; ?>><?php echo ($list["name"]); ?></option><?php endforeach; endif; ?>
      </select>
	<label>手机号码：</label>
	<input type="text" size="12" class="input-150" name="phone" value="<?php echo ($phone); ?>" id="phone" />
	<label>状态：</label>
	<select name="status">
                  <option value="3">全部</option>
                  <option value="1" <?php if($status == 1 ): ?>selected<?php endif; ?>>正常</option>
                  <option value="0" <?php if($status == 0 ): ?>selected<?php endif; ?>>冻结</option>
    </select>
	
	<input class="button_s" type="submit" name="findsub" value="搜 索">
	<input class="button_s" type="button" name="findsub" value="显示所有" onclick="document.location='__APP__/<?php echo ($cur_url); ?>'">
	</div>
	</form>
	</div>
	<form action="" method="POST" name="orgtype" />
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
	    <th width="4%"><div class="th-gap">ID</div></th>
	    <th width="8%"><div class="th-gap">帐号</div></th>
		<th width="6%"><div class="th-gap">姓名</div></th>
		<th width="5%"><div class="th-gap">城市</div></th>
		<th width="10%"><div class="th-gap">邮箱</div></th>
		<th width="10%"><div class="th-gap">手机号码</div></th>
		<th width="28%"><div class="th-gap">角色</div></th>
		<th width="3%"><div class="th-gap">状态</div></th>
		<th width="15%"><div class="th-gap">操作</div></th>
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <tbody  id="tableClass">
	  <?php if(is_array($admin_list)): foreach($admin_list as $key=>$name): ?><tr onmouseover="overColor(this)" onmouseout="outColor(this)">
	    <td align="left" class="hback">
	       <input type="checkbox" <?php if(in_array($name['admin_id'],$notdel) OR $notdel=='ALL'){echo "disabled";} ?>  value="<?php echo ($name["admin_id"]); ?>" name="admin_id[]"><?php echo ($name["admin_id"]); ?>
	    </td>
	    <td align="center" class="hback"><?php echo ($name["account"]); ?></td>
	    <td align="center" class="hback"><?php echo ($name["admin_name"]); ?></td>
	    <td align="center" class="hback"><?php echo $city_arr[$name[city_id]];?></td>
		<td align="center" class="hback"><?php echo ($name["email"]); ?></td>
		<td align="center" class="hback"><?php echo ($name["phone"]); ?></td>
		<td class="hback">
		   <?php $table=M('roleadmin'); $where['cms_roleadmin.admin_id']=array('eq',$name['admin_id']); $where['cms_role.is_del']=array('eq','0'); $arr=$table->join('cms_roleadmin LEFT JOIN cms_role ON cms_roleadmin.role_id=cms_role.role_id')->field('cms_role.role_name,cms_roleadmin.role_id')->where($where)->select(); foreach ($arr as $id=>$key){ echo "<div style='float:left;height:20px;width:80px;border: 1px solid #cccccc;margin-left:13px;padding:3px;margin-top:5px;'><a href='__APP__/Role/rolepriv_allot/".$key['role_id']."'/>".$key['role_name']."</a></div>"; } ?>
		</td>
	 
		<td align="center">
		<?php if(($name['status'] == '1')): ?><img id="flag8" alt="正常" src="__ROOT__/Public/Admin/images/yes.gif" onclick="javascript:fetch_ajax(&#39;flag&#39;,&#39;8&#39;);" style="cursor:pointer;">	
			<?php else: ?>
			<img id="flag8" alt="冻结" src="__ROOT__/Public/Admin/images/no.gif" onclick="javascript:fetch_ajax(&#39;flag&#39;,&#39;8&#39;);" style="cursor:pointer;"><?php endif; ?>
		</td> 
		<td align="center" class="hback">
		<!-- 添加子栏目 -->
			<a href="__APP__/Adminuser/adminuser_edit/<?php echo ($name["admin_id"]); ?>/" class="icon-edit" <?php echo ($adminuser_edit); ?>>编辑</a>
			&nbsp;
			<a <?php echo ($adminuser_del); ?> href="__APP__/Adminuser/adminuser_del/<?php echo ($name["admin_id"]); ?>/" onclick="{if(confirm(&#39;确定要删除吗？一旦删除无法恢复！&#39;)){return true;} return false;}" class="icon-del" <?php if(in_array($name['role_id'],$notdel) OR $notdel=='ALL'){echo "style='display:none;'";} ?>>删除</a>
		</td>
	  </tr><?php endforeach; endif; ?>
	  	   
	  <tr>
	    <td align="center" colspan="" class="hback"><input type="checkbox" name="del_idall" onclick="check_all('admin_id');" value=""/>选择</td>
	    <td align="left" colspan="8" class="hback"><input <?php echo ($adminuser_del); ?> type="button" class="button" value="删除" onclick="check_from('删除','__APP__/Adminuser/adminuser_del/')" name="delall" id="delall"/></td>
	    
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
// });
</script>
</body></html>