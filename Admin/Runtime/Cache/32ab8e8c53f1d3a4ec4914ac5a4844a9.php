<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>管理页面</title> 
<script src="__ROOT__/Public/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<link href="__TMPL__css/<?php echo ($temp_style); ?>.css" rel="stylesheet" type="text/css" />
<style>

</style>
</head>


<body>
<dl class='leftmenu' style="overflow:auto;">
<?php  $none=''; if(count($arr)>=5){ $none='none;'; } ?>
	<?php if(is_array($menu_list)): foreach($menu_list as $av=>$list): ?><a href="javascript:void(0)"><dt onclick="showhide(<?php echo ($av); ?>)"><?php echo ($list["menu_title"]); ?></dt></a>
	   <div id="dtdd<?php echo ($av); ?>" style="display:<?php echo ($none); ?>"><!--style="display:none;"-->
	     
		   <?php if(is_array($list["menu2"])): foreach($list["menu2"] as $key=>$list2): ?><dd><a href="<?php if(in_array(($list2["1"]), explode(',',"WxChannel,WxChannelUpdate"))): ?>/Admin/qd/<?php echo ($list2["1"]); ?>.php<?php else: ?>__APP__/<?php echo ($list2["1"]); endif; ?>" target="main"><?php echo ($list2["0"]); ?></a></dd><?php endforeach; endif; ?>
	   </div><?php endforeach; endif; ?>
</dl>
<script type="text/javascript">
function showhide(id){
    if($('#dtdd'+id).is(':hidden')){
    	$('#dtdd'+id).show();
    }else{
    	$('#dtdd'+id).hide(); 
    }
}

$('#dtdd0').show();
</script> 

</body>
</html>