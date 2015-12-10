<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo ($cur_title); ?></title>
<meta name="author" content="OEdev">
<script src="__PUBLIC__/Admin/js/jquery.min.js"></script>
<script src="__ROOT__/Public/Admin/js/public.js"></script>
<script src="__ROOT__/Public/Admin/js/DatePicker/WdatePicker.js" type="text/javascript"></script> 
<link type="text/css" rel="stylesheet" href="__ROOT__/Public/Admin/css/admin.css">
<script  src="__PUBLIC__/Admin/js/jquery.ocupload-1.1.2.js"></script>

<script src="__PUBLIC__/Admin/js/jquery.dragndrop.js"></script>  

<script src="__PUBLIC__/Admin/js/jquery.msgbox.js"></script>   

<link rel="stylesheet" href="__PUBLIC__/Admin/css/jquery.msgbox.css"/>  

<script>
$(window).load(function(){	
	//上传Excel文件   
	var excelUpload=$('#btn_import').upload({    
		name: 'upload_excel',         
		action: '<?php echo U('Member/member_import');?>',		
		enctype: 'multipart/form-data',                
		autoSubmit: true,		
		onComplete: function(data) {
			data = $.parseJSON(data);
			if(data.status==1){				
				alert('导入影片数据成功!');
			}
			else{
				alert(data.info);
			}
		}
	});	  
});
  
function exportAllMovie(){
	var searchParam='';
	if($('#true_name').val()!=''){
		searchParam+='/true_name/'+encodeURI($('#true_name').val());
	}
	if($('#user_label_id').val()!=''){
		searchParam+='/user_label_id/'+encodeURI($('#user_label_id').val());
	}
	if($('#region_id').val()!=''){
		searchParam+='/region_id/'+encodeURI($('#region_id').val());
	}
	if($('#admin_id').val()!=''){
		searchParam+='/admin_id/'+encodeURI($('#admin_id').val());
	}
	if($('#source_id').val()!=''){
		searchParam+='/source_id/'+encodeURI($('#source_id').val());
	}	
        if($('#start_time').val()!=''){
		searchParam+='/start_time/'+encodeURI($('#start_time').val());
	}
	if($('#end_time').val()!=''){
		searchParam+='/end_time/'+encodeURI($('#end_time').val());
	}
          alert(searchParam);
	location.href="<?php echo U('Member/member_export');?>"+searchParam;
}


//在编辑页面，判断哪个值是选中的 
var getSelectEditValue = function(){
	var $select = $('select[data]');
	if( $select.length > 0){
		$select.each(function(){
			$(this).val( $(this).attr('data') );
		})
	}//if
}
var clickSelectAll = function(){
	if( $(this).attr('checked') ==true ){
              $('.ckb').attr('checked', 'checked'); //定义class
	}
	else{
		$('.ckb').removeAttr('checked'); 
	} 
};


var clickBtnDelete = function(){
	var $this   = $(this);
	var select_id = '';
	$('.ckb').each(function(){
		if( $(this).attr('checked') ==true ){
			select_id += $(this).val() + ',' ;
		}
	});
	if(select_id == ''){
		$.msgbox("请先选择要删除的行");
		return false;
	}
	select_id=select_id.substr(0,select_id.length-1); 
 
	$.msgbox({
			height:120, 
			content:{type:'confirm', content: '确认要删除吗？'},
			onClose:function(result){
				if(result){ 
					location.href = $this.attr('url') + '?id=' + select_id; 
				} 
			}
	});//msgbox
};

//把User_id 存到ckb的属性data 
var clickBtnSend = function(){
	var $this   = $(this);
	var select_id = '';
	$('.ckb').each(function(){
		if( $(this).attr('checked') ==true ){
			select_id += $(this).attr('data') + ',' ;
		}
	});
        if(select_id == ''){
		$.msgbox("请先选择要发送的用户");
		return false;
	}
	select_id=select_id.substr(0,select_id.length-1); 
 
	$.msgbox({
			height:120, 
			content:{type:'confirm', content: '确认要发送吗？'},
			onClose:function(result){
				if(result){ 
					location.href = $this.attr('url') + '?id=' + select_id; 
				} 
			}
	});//msgbox
};

var clickRowDelete = function(){
	//var select_id = $(this).parents('tr').find('.ckb').val();
	var $this  = $(this);
	$.msgbox({
			height:120, 
			content:{type:'confirm', content: '确认要删除吗？'},
			onClose:function(result){
				if(result){
					location.href = $this.attr('url');
				} 
			}
	});//msgbox
};


$(window).load(function() { 
	getSelectEditValue();	
       $('#chk_all').bind('change', clickSelectAll);
       $('#btn_delete').bind('click', clickBtnDelete);
       $('#btn_send').bind('click', clickBtnSend);
       $('.delete').bind('click', clickRowDelete);
       
});

function set_date(date_val){
	$('#start_time').val(date_val);
	$('#end_time').val("<?php echo date('Y-m-d'); ?>");
}
</script>
</head>
<body>
<div class="main-wrap">
  <div class="path"><p><?php echo ($cur_menu); ?></p></div>
  
  <div class="main-cont">
    <!--<h3 class="title">
        <a href="__APP__/Info/info_send/" class="btn-general"><span>群发信息</span></a>
        <?php echo ($cur_title); ?>
	</h3>-->
	<div class="search-area ">
	<form id="" name="formfind" action="" method="GET">
	<!--<div class="item">
	<label>最近关注时间：</label>
	<input type="text" id="start_time" onClick="WdatePicker()" size="11" class="Wdate" name="start_time" value="<?php echo ($_GET['start_time']); ?>" />-
    <input type="text" id="end_time" onClick="WdatePicker()" size="11" class="Wdate" name="end_time" value="<?php echo ($_GET['end_time']); ?>" />&nbsp;&nbsp;
    <a href="javascript:void(0)" onclick="set_date('<?php echo ($date_list[0]); ?>')">昨日</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="set_date('<?php echo ($date_list[1]); ?>')">最近7天</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="set_date('<?php echo ($date_list[2]); ?>')">最近30天</a>&nbsp;&nbsp;
	</div>-->
    <div class="item">
        <label>用户名：</label>
        <input type="text" id="username" size="11"  name="username" value="<?php echo ($_GET['username']); ?>" />&nbsp;&nbsp;
    </div>
	<div class="item">
	<label>性别：</label>
	<input type="radio" name="sex" checked value="10" />不限&nbsp;&nbsp;
	<input type="radio" name="sex" <?php if(($_GET['sex']) == "0"): ?>checked<?php endif; ?> value="0" />男&nbsp;&nbsp;
	<input type="radio" name="sex" <?php if(($_GET['sex']) == "1"): ?>checked<?php endif; ?> value="1" />女&nbsp;&nbsp;
	</div>
	
	<div class="item">
	<input class="button_s" type="submit" name="findsub" value="筛 选">
	</div>
	</form>
	</div>
	<form action="" method="POST" name="orgtype" />
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
          <th width="3%"><div class="th-gap">用户编号</div></th>
	    <th width="6%"><div class="th-gap">用户名</div></th>
          <th width="6%"><div class="th-gap">姓名</div></th>
		  <th width="6%"><div class="th-gap">级别</div></th>
		<th width="4%"><div class="th-gap">性别</div></th>
		<th width="6%"><div class="th-gap">手机</div></th>
		<th width="6%"><div class="th-gap">QQ</div></th>
          <th width="6%"><div class="th-gap">状态</div></th>
		<th width="6%"><div class="th-gap">最后登录时间</div></th>
		
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <tbody  id="tableClass">
	  <?php if(is_array($user_list)): foreach($user_list as $key=>$name): ?><tr onmouseover="overColor(this)" onmouseout="outColor(this)">
          <td align="center" class="hback"><?php echo ($name["user_id"]); ?></td>
	    <td align="center" class="hback"><?php echo ($name["username"]); ?></td>
          <td align="center" class="hback"><?php echo ($name["nickname"]); ?></td>
		   <td align="center" class="hback"><?php echo ($name["role_name"]); ?></td>
	    <td align="center" class="hback"><?php if($name["sex"] == '0'): ?>男<?php elseif($name["sex"] == '1'): ?>女<?php endif; ?></td>
	    <td align="center" class="hback"><?php echo ($name["mobile"]); ?></td>
	    <td align="center" class="hback"><?php echo ($name["qq"]); ?></td>
          <td align="center" class="hback"><?php if($name["status"] == '0'): ?>在职<?php else: ?>离职<?php endif; ?></td>
	    <td align="center" class="hback"><?php echo (date("Y-m-d H:i:s",$name["fstcreate"])); ?></td>
	 
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