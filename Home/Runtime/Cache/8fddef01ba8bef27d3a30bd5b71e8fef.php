<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0" />
<title>乐境微信管理系统</title>
<link rel="stylesheet" href="__PUBLIC__/Home/css/style.css">
<!-- <script src="__PUBLIC__/Home/js/jquery.min.js"></script>
 --><!-- <script>
$(function(){
	$("input[type='submit']").click(function(){
		var username = $("#username").val();
		var password = $("#password").val();
		if (username.length == 0){
			alert("用户名不能为空");
			return false;
		}
		if (password.length == 0){
			alert("密码不能为空");
			return false;
		}
	})
})
</script> -->
</head>

<body class="reg_body">

<header class="h_reg">
	<!-- <img src="templates/images/logo2.png"/> -->
</header>
<section class="main_reg">
	<h3>用户登录</h3>

		<form action="" method="post">
		<div>
		    <span align="right">用户名：</span>
		    <input name="username" type="text"  placeholder="请输入用户名" required/>
		    <!--<span id="username_notice"> *</span>-->
	    </div>
	    <div>
		    <span align="right">密码：</span>
		    <input name="password" type="password"  placeholder="请输入密码" required/>
		    <!--<span id="username_notice"> *</span>-->
	    </div>
		
		<div><label for="remmember">记住我</label>
		<input type="checkbox" name="remmember" id="remmember" value="1" style="width:20px;height:20px;" checked/>
		</div>
		
		<div class="login_notice"><?php echo ($ErrorInfo); ?></div>
		<div>
			<input name="login" type="submit" value="登录" />
		</div>
		<!-- <div class="login_btn">
			<a href='user.php?act=register'>免费注册</a> |
			<a href='index.php'>返回首页</a>	
		</div> -->
	</form>
</section>
<footer class="f_reg">
	<!--<a href='index.php'>返回首页</a><br />	-->
</footer>
</body>
</html>