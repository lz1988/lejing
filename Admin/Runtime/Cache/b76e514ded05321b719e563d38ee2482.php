<?php if (!defined('THINK_PATH')) exit();?><HTML><HEAD>

<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta http-equiv="imagetoolbar" content="no">
<link rel="shortcut icon" href="__ROOT__/favicon.ico" />
<link href="__ROOT__/Public/Admin/css/login.css" type="text/css" rel="stylesheet"> 
<script src="__ROOT__/Public/Admin/js/jquery-1.6.js" type="text/javascript"></script>  
<script type="text/javascript">
function fleshverify(){  
	var timenow = new Date().getTime();
	$('#verifyImg').attr('src','__APP__/Common/verify/'+timenow); 
}
</script> 
</HEAD>
<BODY> 
<form name="" action="__APP__/Common/login/" method="POST" >
<div class="loginbody">
    <div class="logincenter"> 
        <div class="logintop">
            <div class="loginlogo">&nbsp;<!--<img src="__ROOT__/Uploads/site_config/<?php echo ($header_img); ?>" width="200" height="72" />--></div>
            <div class="loginhtgl">lejing管理系统</div>    
        </div>
        <div class="logintext">
            <div class="usernamediv"><div class="textwz">用户名：</div><div class="inputsr"><input type="text" name="account" value="" id="account"></div></div>
            <div class="pwddiv"><div class="textwz">密&nbsp;&nbsp;码：</div><div class="inputsr"><input type="password" name="password" value="" id="password"></div></div>
            <div class="pwddiv">
               <div class="textwz">验证码：</div>
               <div class="inputsr">
                   <div class="imgdiv2"><input type="text" size="6" name="verify" value="" id="verify" /></div>
                   <div class="imgdiv"> <img SRC="__APP__/Common/verify/" id="verifyImg" onClick="fleshverify()"  BORDER="0" / ></div>
                   <div class="exchange"><a href="#" onClick="fleshverify()">换一张</a></div>
               </div>
            </div>
            <div class="pwddiv"><div class="loginbutton""><input type="submit" size="6" name="login" value="登录" id=""></div><div class="inputsr"><input type="button" size="6" name="" value="重置" id=""></div></div>
        </div>
        <div class="copyright">2014 后台管理系统 版权所有</div>
    </div>
</div>
</form>
</BODY>