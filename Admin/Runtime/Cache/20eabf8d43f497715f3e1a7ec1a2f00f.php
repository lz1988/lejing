<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<title>后台管理中心</title>
<meta http-equiv=Content-Type content=text/html;charset=UTF-8>
<link rel="shortcut icon" href="__ROOT__/favicon.ico" />
</head>
<frameset rows="35,*"  frameborder="NO" border="0" framespacing="0">
	<frame src="<?php echo U('Index/top');?>" noresize="noresize" frameborder="NO" name="topFrame" scrolling="no" marginwidth="0" marginheight="0" target="main" />
  <frameset cols="150,*"  rows="100%,*" id="frame">
	<frame src="<?php echo U('Index/left');?>" name="leftFrame" noresize="noresize" marginwidth="0" marginheight="0" frameborder="0" scrolling="no" target="main" />
	<frame src="<?php echo U('Index/home');?>" name="main" marginwidth="0" marginheight="0" frameborder="0" scrolling="auto" target="_self" />
  
<noframes>
  <body></body>
    </noframes>
</html>