<?php if (!defined('THINK_PATH')) exit();?><link href="__TMPL__css/<?php echo ($temp_style); ?>.css" rel="stylesheet" type="text/css" />
<style type="text/css">

</style>
<script type="text/javascript">
function logout(){
	if (confirm("您确定要退出后台吗？")){ 
		return true;
	}else{
		return false;
}
}
</script>
<div class="top">
   <div class="toptext">
      <?php echo ($user_name); ?>      
      <a href="__APP__/Index/home/" target="main"><span class="clearcache">管理首页</span></a>
      <a href="__APP__/Common/modify_pwd/" target="main"><span class="modifypwd">修改密码</span></a> 
      <a href="__APP__/Index/clearcache/" target="main"><span class="clearcache">清除缓存</span></a>
      <a href="__APP__/Index/select_style/green/" target="main"><span class="clearcache">风格1</span></a>
      <a href="__APP__/Index/select_style/gray/" target="main"><span class="clearcache">风格2</span></a>
      <a target="_top" href="__APP__/Common/logout/" onclick="return logout()"><span class="logout">退出</span></a>
   </div> 
</div>