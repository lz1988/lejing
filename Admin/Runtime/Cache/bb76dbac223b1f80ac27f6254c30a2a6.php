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
  <h3 class="title">
	<a href="__APP__/shop/shop_add/" class="btn-general"><span>添加分店</span></a>店铺管理
	</h3>
	
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table" align="center">
	  <thead class="tb-tit-bg">
	  <tr>
          <th width="3%"><div class="th-gap">分店编号</div></th>
	    <th width="6%"><div class="th-gap">分店名称</div></th>
          <th width="6%"><div class="th-gap">区域</div></th>
		  <th width="6%"><div class="th-gap">地址</div></th>
		<th width="4%"><div class="th-gap">电话</div></th>
		<th width="6%"><div class="th-gap">店长</div></th>		
	  </tr>
	  </thead>
	  <tfoot class="tb-foot-bg"></tfoot>
	  <tbody  id="tableClass">
	  <?php if(is_array($shop_arr)): foreach($shop_arr as $key=>$name): ?><tr onmouseover="overColor(this)" onmouseout="outColor(this)">
          <td align="center" class="hback"><?php echo ($name["shop_code"]); ?></td>
	    <td align="center" class="hback"><?php echo ($name["shop_name"]); ?></td>
          <td align="center" class="hback"><?php echo ($name["region"]); ?></td>
		   <td align="center" class="hback"><?php echo ($name["address"]); ?></td>
	    <td align="center" class="hback"><?php echo ($name["mobile"]); ?></td>
	    <td align="center" class="hback"><?php echo ($name["leader"]); ?></td>
	 
	  </tr><?php endforeach; endif; ?> 
	  	</tbody></table>

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