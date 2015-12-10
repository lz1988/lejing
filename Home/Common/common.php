<?php


/**
 * 发送Email方法
 * @param $address 收件人地址,可以是多个地址的数组
 * @param $subject 邮件标题
 * @param $body    邮件内容
 * @param $altbody 接收邮箱不兼容HTML时的替换内容
 * @return boolean 
 */
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
		echo $name1.'   '.$name2.'   '.$name3.'  ';
}
//递归获取地区 return是返回值 深圳 福田区 梅林
function getRegionPidName($id,$ret=""){
	$region = M('region');
	$sql = "select pid,name from cms_region where id = $id";
	$result = $region->query($sql);
	$ret =$result[0]['name'].$ret;
	
	if($result[0]['pid']){
		getRegionPidName($result[0]['pid'],$ret);
		
	}else{
		//不知为何 用return没有输出
		echo $ret;
	}
	
}
	/**@  根据地区找到PID
	 *    cid
	 * */
function get_city_pid($cid){
	$city=M('city');
	$res=$city->field('pid,cname')->where("cid=$cid")->find();
	return $res;
}
  
function getInvalidWordsList() {
    	$file1 = APP_PATH.'Lib/ORG/invalid_words1.txt';
    	$file2 = APP_PATH.'Lib/ORG/invalid_words2.txt';
    	
    	$invalid_words1 = is_file($file1)? file_get_contents($file1):'';
    	$invalid_words2 = is_file($file2)? file_get_contents($file2):'';
    	
    	$invalid_words1 = $invalid_words1 .'#'. $invalid_words2;
    	$invalid_words1 = preg_replace('/\s+/', '#', $invalid_words1);
    	
    	return $invalid_words1;
    }
function isInvalidWord($word) {
    	
    	$word = trim($word);
    	
    	if(strlen($word)==0){
    		return false;
    	}
    	
    	$invalid_words = getInvalidWordsList();
    	$invalid_words = '#'.$invalid_words.'#';
    	
    	if(strpos($invalid_words, '#'.$nick_name.'#') !== false){
    		return true;
    	}
    	return false;
    }
  
function encryptPassword($password){
    	return md5($password);
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

// 获取客户端IP地址
/**
 +----------------------------------------------------------
 * 字符串截取，支持中文和其他编码
 +----------------------------------------------------------
 * @static
 * @access public
 +----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
{
    if(function_exists("mb_substr")){
            if ($suffix && strlen($str)>$length)
                return mb_substr($str, $start, $length, $charset)."...";
        else
                 return mb_substr($str, $start, $length, $charset);
    }
    elseif(function_exists('iconv_substr')) {
            if ($suffix && strlen($str)>$length)
                return iconv_substr($str,$start,$length,$charset)."...";
        else
                return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."…";
    return $slice;
}
 
//send email

function sendOakyxMail($to, $subject, $content){
	try {
		include APP_PATH.'Lib/ORG/Phpmailer.class.php';
		$mail = new PHPMailer(true); //New instance, with exceptions enabled
		
		$body             = $content;
		$body             = preg_replace('/\\\\/','', $body); //Strip backslashes
	
		$mail->IsSMTP();                           // tell the class to use SMTP
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->Port       = 25;                    // set the SMTP server port
		$mail->Host       = "mail.yx1758.com"; // SMTP server
		$mail->Username   = "svc@yx1758.com";     // SMTP server username
		$mail->Password   = "ZTdiYKol";            // SMTP server password
	
		//$mail->IsSendmail();  // tell the class to use Sendmail
	
		$mail->AddReplyTo("kefu@oakyx.com","橡树游戏");
	
		$mail->From       = "kefu@oakyx.com";
		$mail->FromName   = "橡树游戏";

		$mail->AddAddress($to);
		$mail->Subject   = "=?utf-8?B?" . base64_encode($subject) . "?=";
		//$mail->Subject  = $subject;
	
		//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$mail->WordWrap   = 80; // set word wrap
		
		$mail->MsgHTML($body);
	
		$mail->IsHTML(true); // send as HTML
	
		$mail->Send();
		return true;
	} catch (phpmailerException $e) {
		return false;
	}
}

function hideEmail($email){
	if(empty($email)){
		return '';exit;
	}
	$str = explode('@',$email,'2');
    $initial = substr($str['0'],0,3);
    $Hide="***";
    return $initial.$Hide.'@'.$str['1'];
}
/**
 *   实现中文字串截取无乱码的方法
 */
function getSubstr($string, $start, $length) {
        if(mb_strlen($string,'utf-8')>$length){
            $str = mb_substr($string, $start, $length,'utf-8'); 
            return $str.'...';
        }else{
                return $string;
        }
}

/**
 *   curl_get
 *   $url 地址
 */
function curl_get($url){
	$curl = curl_init();                                                        # 初始化一个 cURL 对象
    curl_setopt($curl, CURLOPT_URL, $url);                        # 设置你需要抓取的URL
    curl_setopt($curl, CURLOPT_HEADER,0);                        # 设置header
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);        # 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//禁止直接显示获取的内容 重要

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//禁止直接显示获取的内容 重要

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
    ob_start( );                                                                          # 打开缓冲区
    curl_exec( $curl );
    $contents = ob_get_contents( );
    ob_end_clean();                                                                 #（或程序执行完毕）之前不会被输出
    curl_close( $curl );
    return  $contents;
}

/**
 *   curl_get
 *   $url 地址
 */
function curl_get2($url){
	$curl = curl_init();                                                        # 初始化一个 cURL 对象
    curl_setopt($curl, CURLOPT_URL, $url);                        # 设置你需要抓取的URL
    curl_setopt($curl, CURLOPT_HEADER,0);                        # 设置header
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);        # 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//禁止直接显示获取的内容 重要

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true); //不验证证书下同
    ob_start( );                                                                          # 打开缓冲区
    curl_exec( $curl );
    $contents = ob_get_contents( );
    ob_end_clean();                                                                 #（或程序执行完毕）之前不会被输出
    curl_close( $curl );
    return  $contents;
}

function get_openid($code){
	$openid=session('openid');
	if(empty($openid)){
		$APPID=C('APPID');
		$SECRET=C('SECRET');
		$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$APPID&secret=$SECRET&code=$code&grant_type=authorization_code";
		$res=(array)json_decode(curl_get($url));
		session('openid',$res['openid']);
		//$access_token=$res['access_token'];
		$user=M('user');
		$u_where['openid']=array('eq',$res['openid']);
		$res2=$user->where($u_where)->field('user_id,username')->find();
		
		if(!$res2){
			$access_token=curl_get("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$SECRET");
		    $access_token=@get_object_vars(@json_decode($access_token));
			$get_user_info="https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token[access_token]&openid=$res[openid]&lang=zh_CN";
			$ures=(array)json_decode(curl_get($get_user_info));
			$user_data['openid']=$res[openid];
			$username=$user_data['username']=$ures[nickname];
			$user_data['tel']='';
			$user_data['sex']=$ures[sex];
			$user_data['city_name']=$ures[city];
			$add_data['headimgurl']=$ures[headimgurl];
			//$user_data['nickname']=$ures[nickname];
			$user_data['create_time']=time();
			$user_data['status']=0;
			$user_data['is_master']=0;
            $user_data['level'] = 1;
            $user_data['vip'] = 1;
			$user_id=$user->add($user_data);
		}else{
			$user_id=$res2[user_id];
			$username=$res2[username];
		}
		session('username',$username);
		session('user_id',$user_id);
	}
	return session('openid');
}


/**
 *  获取文章信息
 *   $type_id 文章类型
 *   $limit 显示数
 * 　$field 字段
 */
function get_news($type_id,$limit=50,$field='id,title'){
	$news=M('news');
	$news_where['type_id']=array('eq',$type_id);
	$news_where['is_del']=array('eq',0);
	$news_where['status']=array('eq',1);
	$news_list=$news->field($field)->where($news_where)->order('sort ASC,id DESC')->limit($limit)->select();
	$newstype=M('newstype');
	$newstype->where("type_id=$type_id")->setInc('click_num');
	return $news_list;
}

/**
 *  获取文章信息
 *   $id 文章id
 *   
 */
function get_news_detail($id,$field='id,title'){
	$news=M('news');
	$news_where['id']=array('eq',$id);
	$news_where['is_del']=array('eq',0);
	$news_where['status']=array('eq',1);
	$news_list=$news->field($field)->where($news_where)->find();
	$news->where($news_where)->setInc('click_num');
	return $news_list;
}

/**
 *  获取文章类型
 *   $type_id 文章类型
 *   
 */
function get_news_type($type_id,$limit=10,$field='type_id,type_name'){
	$newstype=M('newstype');
	$news_where['pid']=array('eq',$type_id);
	$news_where['is_del']=array('eq',0);
	$news_where['status']=array('eq',1);
	$news_list=$newstype->field($field)->where($news_where)->select();
	return $news_list;
}

/**
 *  获取场馆名
 *    
 *   
 */
function get_venues_name($id){
	$venues=M('venues');
	$news_where['id']=array('eq',$id);
	$news_list=$venues->field('name')->where($news_where)->find();
	return $news_list['name'];
}

function get_orders_user($orders_id){
     $orders_user=M('orders_user');
     $user_list=$orders_user->where("orders_id=$orders_id")->select();
     $html='';
     $num=1;
     foreach ($user_list as $list){
     	 $html.='<p>联系人'.$num.' : '.$list['username'].'</p><p>身份证号 : '.$list['identity'].'</p><p>联系电话 : '.$list['tel'].')</p>';
     	 $num++;
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
 *  获取可用的场地号
 * return code
 * 参数１$arr　场馆编号
 * 参数２:$project 项目名
 * 参数3:$venues_id 场馆ID
 * 参数4:$time_ymd 年月日
 * 参数5:$time_hour 小时
 * 参数６：$project_num_arr 每个场地可报名的人数
 */
function get_project_code($arr,$project,$venues_id,$time_ymd,$time_hour,$project_num_arr){
	$orders=M('orders');
	$code='';
	$count_where['time_ymd']=array('eq',$time_ymd);
    $count_where['project']=array('eq',$project);
    $count_where['status']=array('eq',1);
    $count_where['time_hour']=array('eq',$time_hour);
    $count_where['venues_id']=array('eq',$venues_id);
    
	for ($i=0;$i<count($arr);$i++){
		$count_where['project_code']=array('eq',$arr[$i]);
		$orders_conut=$orders->where($count_where)->count();#该场馆，该天，该时间段　所预约的数量
		$project_num=$project_num_arr[$i];
		if($project_num>$orders_conut){
			$code=$arr[$i];break;
		}
	}
	return $code;
}

//生成会员卡号
function vip_card_no($num = 4)
{
    return date('ymdHis').generate_code($num);
}

function generate_code($length = 6) {
    return rand(pow(10,($length-1)), pow(10,$length)-1);
}
