<?php

/*function get_city_name($cid3) {
		$city=M('city');
		$res3=get_city_pid($cid3);
		$pid3=$res3['pid'];
		$name3=$res3['cname'];
		$res2=get_city_pid($pid3);
		$pid2=$res2['pid'];
		$name2=$res2['cname'];
		$res1=get_city_pid($pid2);
	 
		$name1=$res1['cname'];
		echo $name1.'   '.$name2.'   '.$name3.'  ';
}*/

	/**@  根据地区找到PID
	 *    cid
	 * */
/*function get_city_pid($cid){
	$region=M('region');
	$region_res=$region->query("SELECT pid,name FROM `cms_region` where id=$cid");

	if($region_res[0]['pid']!=0){
		return get_city_pid($region_res[0]['pid']);
	}else{
		return $region_res[0]['name'];
	}
	
}*/

function get_city_name($cid3) {
    $city=M('city');
    $res3=get_city_pid($cid3);
    $pid3=$res3['pid'];
    $name3=$res3['cname'];
    $res2=get_city_pid($pid3);
    $pid2=$res2['pid'];
    $name2=$res2['cname'];
    $res1=get_city_pid($pid2);

    $name1=$res1['cname'];
    return $name1.'   '.$name2.'   '.$name3.'  ';
}

/**@  根据地区找到PID
 *    cid
 * */
function get_city_pid($cid){
    $city=M('city');
    $res=$city->field('pid,cname')->where("cid=$cid")->find();
    return $res;
}




/**
* @deprecated 密码加密 
* @param1    password 
* @author    dapeng.chen 2012.12.7 
* @return    加密串
* **/
function sha1_password($string){
   return strtoupper(sha1(trim($string)));
}

/**
* @deprecated 通过积分得到vip等级
* @param1    score 积分 
* @author    youxiang.zhang 2012.12.13 
* @return    level vip等级
* **/
function getVipLevel($score){
	$vip_define = M('vip_define');
	$de_where['min_score']=array('gt',$score);
	$level = $vip_define->field('name')->where($de_where)->order('min_score ASC')->find();
	if(empty($level)){
		$level = $vip_define->field('name')->order('min_score DESC')->find();
	}
	return $level['name'];
}

/**
* @deprecated 通过vip等级得到积分区间
* @param1    level vip等级
* @author    youxiang.zhang 2012.12.14
* @return    section 积分区间
* **/
function getSectionByVipLevel($level){
	$model = M('vip_define');
	$vip = $model->select();
	$section = array();
	if($level == $vip[0]['id']){
		$section['min'] = 0;
		$section['max'] = $vip[0]['min_score'];
	}
	if($level == $vip[1]['id']){
		$section['min'] = $vip[0]['min_score'];
		$section['max'] = $vip[1]['min_score'];
	}
	if($level == $vip[2]['id']){
		$section['min'] = $vip[1]['min_score'];
		$section['max'] = $vip[2]['min_score'];
	}
	if($level == $vip[3]['id']){
		$section['min'] = $vip[2]['min_score'];
		$section['max'] = '';
	}
	return $section;
}

/**
* @deprecated 通过user_id得到用户名
* @param1    user_id 用户ID
* @author    youxiang.zhang 2012.12.14
* @return    userName 用户名
* **/
function getUserNameByID($user_id){
	$model = M('user');
	$info = $model->field('account')->where("user_id = $user_id")->find();
	return $info['account'];
}

/**
* @deprecated 获取当前登陆IP
* @param1    
* @author     
* @return    IP
* **/
function get_ip(){
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}elseif (isset($_SERVER['HTTP_CLIENT_IP'])){
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	}else{
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

/**
* @deprecated 游戏服状态
* @param1    
* @author    dapeng.chen 
* @return    IP
* **/
function getserver_status($status){
	$con_sta=C('GAME_SERVER_STATUS');
	if($status==0){
		echo "<span style='color:red;'>$con_sta[$status]</span>";
	}elseif ($status==1){
		echo "<span style='color:#fff;background:#000;padding:2px;'>$con_sta[$status]</span>";
	}elseif($status==2){
		echo "<span style='color:#fff;background:#000;padding:2px;'>$con_sta[$status]</span>";
	}elseif($status==3){
		echo "<span >$con_sta[$status]</span>";
	}elseif($status==4){
		echo "<span >$con_sta[$status]</span>";
	}elseif($status==5){
		echo "<span >$con_sta[$status]</span>";
	}elseif($status==6){
		echo "<span style='color:#fff;background:blue;padding:2px;'>$con_sta[$status]</span>";
	}elseif($status==7){
		echo "<span style='color:#fff;background:green;padding:2px;'>$con_sta[$status]</span>";
	}else{
		echo '错误状态';
	}
}

/**
* @deprecated IP定位
* @param1    
* @author    dapeng.chen 
* @return    IP
* **/
function getip_area($ipadd){
//	require_once("./Home/Lib/ORG/IpLocation.class.php");	# 加载充值接口文件
//	$classip = new IpLocation('qqwry.dat'); 
//
//	$area = $classip->getlocation($ipadd); 
//	//mb_convert_encoding($address['area1'],'utf-8','GBK'); 
//		print_r($area);exit;
    import('@.ORG.QQWry');	
	$qqwry = new QQWry();
	$area =$qqwry->location($ipadd);
	$area[0]=mb_convert_encoding($area[0],'utf-8','GBK'); 
	$area[1]=mb_convert_encoding($area[1],'utf-8','GBK'); 
	return $area;
}

/**
* @deprecated 删除缓存
* @param1    
* @author    dapeng.chen 
* @return    删除缓存
* **/
function del_run($dirUrl){
  if ( $handle = opendir($dirUrl) ) {  
	   while ( false !== ( $item = readdir( $handle ) ) ) {  
		   if ( $item != "." && $item != ".." ) {  
			   if ( is_dir( "$dirUrl/$item" ) ) {  
			      del_run( "$dirUrl/$item" );  
			   } else {  
			      if( unlink( "$dirUrl/$item" ) ){echo "成功删除文件： $dirUrl/$item<br />\n";flush(); ob_flush();usleep(100000);}
			   }  
		   }  
	   }  
	   closedir( $handle );  
	   if( rmdir( $dirUrl ) ){echo "成功删除目录： $dirUrl<br />\n";flush(); ob_flush();usleep(100000);}
  }
}



/**
 * 发送Email方法
 * @param $address 收件人地址,可以是多个地址的数组
 * @param $subject 邮件标题
 * @param $body    邮件内容
 * @param $altbody 接收邮箱不兼容HTML时的替换内容
 * @return boolean 
 */
function sendmail($address, $subject, $body, $altbody = '请使用兼容HTML格式邮箱.') {
    vendor('PHPMailer_5_2_1.class#phpmailer');
    $mail = new PHPMailer();
    $mail->IsSMTP(); //设置PHPMailer应用SMTP发送Email
    $mail->CharSet = 'UTF-8';
    $mail->Host = C('LEG_MAIL_HOST');  // 指定邮件服务器
    $mail->Port = C('LEG_MAIL_PORT');    //指定邮件服务器端口
    $mail->SMTPAuth = true;     // 开启 SMTP验证
    //设置SMTP用户名和密码
    $mail->Username = C('LEG_MAIL_USERNAME');
    $mail->Password = C('LEG_MAIL_PASSWORD');
    $mail->From = C('LEG_MAIL_ADDRESS_FROM'); //指定发送邮件地址
    $mail->FromName = C('LEG_MAIL_ADDRESS_FROM_NAME'); //为发送邮件地址命名
    if (is_array($address)) {
        foreach ($address as $val) {
            $mail->AddAddress($val);
        }
    } else {
        $mail->AddAddress($address);
    }
    $mail->AddReplyTo(C('LEG_MAIL_ADDRESS_FROM'), C('LEG_MAIL_ADDRESS_FROM_NAME'));

    $mail->WordWrap = C('LEG_MAIL_WORD_WRAP_SIZE');   // 设置自动换行的字符长度为 50		
    $mail->IsHTML(C('LEG_MAIL_IS_HTML')); // 设置Email格式为HTML

    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = $altbody; //当收件人客户端不支持接收HTML格式email时的可替代内容;	
    //发送邮件。
    if (!$mail->Send()) {
        return false;//throw_exception("Mailer Error: " . $mail->ErrorInfo);提示邮箱发送不成功的错误信息
    } else {
        return true;
    }
}

//传入生日得到年龄
function get_age($birthday){
   if($birthday == FALSE){
        return "无法获取数据";
   }
   $birth = date("Y-n-j",$birthday);
   $day   = date("Y-n-j",time());
   $birthList =  split('-', $birth);
   $dayList   = split('-',$day);
   $year = (int)$dayList[0] - $birthList[0];
   $month = (int)$dayList[1] - $birthList[1];
   $days = (int)$dayList[2] - $birthList[2];
   //如果月数为负数的时候 代表不满一年
   if($year == 0 && $month == 0){
       return $days."天";
   }else{
       if($month < 0 ){
            $year--;
            $month = 12 + $month;
       }
       if($days > 0 ){
           $month ++;
       }
       return $year."年".$month."个月";
   }
}


//传入生日得到年龄
function get_orders_user($orders_id){
     $orders_user=M('orders_user');
     $user_list=$orders_user->where("orders_id=$orders_id")->select();
     $html='';
     foreach ($user_list as $list){
     	 $html.='<div>('.$list['username'].'),('.$list['identity'].'),('.$list['tel'].')</div>';
     }
     return $html;
}

/**
 *  获取项目名
 * return array
 *     
 */
function get_project_name($id){
	$venues_project=M('venues_project');
	$arr=$venues_project->field('project')->where("venues_id=$id")->select();
	$arr_name='';
	foreach ($arr as $list){
		$arr_name[]=$list[project];
	}
	return $arr_name;
}

/**
 *  获取栏目下所有资讯的点击量
 * return array
 *     
 */
function get_newstype_num($type_id){
	$news=M('news');
	$count=$news->where("type_id=$type_id")->sum('click_num');
	return intval($count);
}

function get_user_reply($openid){
    $user_reply=M('user_reply');
    $reply_where['openid']=array('eq',"$openid");
    $reply_list=$user_reply->field('content,create_time')->where($reply_where)->order('id DESC')->find();
    return $reply_list;
}

//生成会员卡号
function vip_card_no($num = 4)
{
    return date('ymdHis').generate_code($num);
}