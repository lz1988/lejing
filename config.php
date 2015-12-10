<?php
// 示例的全局数据库配置文件
return array(
    'URL_MODEL'=>3, // 如果你的环境不支持PATHINFO 请设置为3
//     'DB_TYPE'=>'mysql',
//     'DB_HOST'=>'localhost',
//     'DB_NAME'=>'crm',
//     'DB_USER'=>'root',
//     'DB_PWD'=>'',
//     'DB_PORT'=>'3306',
     'DB_TYPE'=>'mysql',
     'DB_HOST'=>'127.0.0.1',
     'DB_NAME'=>'lejing',
     'DB_USER'=>'root',
     'DB_PWD'=>'123456',
     'DB_PORT'=>'3306',
    'DOMAIN_PATH' =>'http://legcms.com',
    'DB_PREFIX'=>'cms_',
    'NEWIMGURL'=>__ROOT__.'/Uploads/New/',//新闻图片路径
    'ADVERTIMGURL'=>__ROOT__.'/Uploads/Advert/',//广告图片路径
    'USERIMGURL'=>__ROOT__.'/Uploads/User/',//会员头像图片路径
	'SITE_CONFIG'=>__ROOT__.'/Uploads/site_config/',//站点文件上传路径
	'UPLOAD_ACTIVITY_PATH'=>'Uploads/New/',//活动图上传路径
    'UPLOAD_SETING_PATH'=>'Uploads/New/',//LOGO
    'UPLOAD_IMAGE_MAX_SIZE'=>2097152,//2Mb,允许上传图片的最大尺寸(单位byte)
    'UPLOAD_FILE_MAX_SIZE'=>5242880,//5Mb,允许上传文件的最大尺寸(单位byte)
    'APPID'=>'wx3beaa765b4e8464d',
	'SECRET'=>'8ab444506875e46ed83d3700799933a1',


	'ITEM_BRAND'=>array(1=>'品牌1',2=>'品牌2'),#project
	'ITEM_STYLE'=>array(1=>'风格1',2=>'风格2'),#project
	'ITEM_SEARCH'=>array(
	     /*'titanium_id'=>array('name'=>'适合人群',id=>1),
	     'material_id'=>array('name'=>'材质',id=>2),
	     'border_id'=>array('name'=>'框形',id=>3),
	     'style'=>array('name'=>'风格',id=>4),
	     'price_id'=>array('name'=>'价格',id=>5),
	     'brand'=>array('name'=>'品牌',id=>6),
	     'other_id'=>array('name'=>'其它',id=>7),*/
         'item_type'=>array('name'=>'产品所属类别',id=>78),
        'shop_type'=>array('name'=>'店铺',id=>84),

	),
	'SMS'=>array(
	    'server_url'=>'http://61.145.229.29:7903/MWGate/wmgw.asmx',
	    'user_name'=>'J21276',
	    'password'=>'552003',
	    'pszSubPort'=>'*'
	),
	'ITEM_SIZE'=>array(#商品尺码
		1=>'大码',
		2=>'中码',
		3=>'小码',
	),
	'ITEM_SHAPE'=>array(#商品形状
		1=>'方形',
		2=>'椭圆形',
		3=>'圆形',
		4=>'其他形状',
	),
);
?>

