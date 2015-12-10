<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo ($cur_title); ?></title>
    <meta name="author" content="OEdev">
    <link type="text/css" rel="stylesheet" href="__ROOT__/Public/Admin/css/admin.css" media="screen">
    <script src="__PUBLIC__/Admin/js/jquery.min.js"></script>
</head>
<body>
<div class="main-wrap">
    <div class="path"><p><?php echo ($cur_menu); ?></p></div>

    <div class="main-cont">
        <h3 class="title">
            <a href="__APP__/rolemain/rolemain_add/" class="btn-general"><span>添加角色</span></a>角色管理
        </h3>

        <form action="" method="POST" name="orgtype" />
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
            <thead class="tb-tit-bg">
            <tr>
                <th width="3%"><div class="th-gap">编号</div></th>
                <th width="6%"><div class="th-gap">角色名称</div></th>
                <th width="6%"><div class="th-gap">操作</div></th>
            </tr>
            </thead>
            <tfoot class="tb-foot-bg"></tfoot>
            <tbody  id="tableClass">
            <?php if(is_array($rolemain_arr)): foreach($rolemain_arr as $key=>$name): ?><tr onmouseover="overColor(this)" onmouseout="outColor(this)">
                    <td align="center" class="hback"><?php echo ($name["role_id"]); ?></td>
                    <td align="center" class="hback"><?php echo ($name["role_name"]); ?></td>
                    <td align="center" class="hback">
                        <a href="<?php echo U('rolemain/rolemain_update');?>/roleid/<?php echo ($name["role_id"]); ?>" class="icon-edit">编辑</a>
                        &nbsp;
                        <a href="<?php echo U('rolemain/rolemain_del');?>/roleid/<?php echo ($name["role_id"]); ?>" onclick="{if(confirm(&#39;确定要删除吗？一旦删除无法恢复！&#39;)){return true;} return false;}">删除</a>
                    </td>
                </tr><?php endforeach; endif; ?>


            </tbody></table>
        </form>
        <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" style="margin-top:10px;">
            <tbody><tr>
                <td align="center" class="page"><?php echo ($page); ?></td>
            </tr>
            </tbody></table>
    </div>
</div>

<script type="text/javascript">
</script>

</body></html>