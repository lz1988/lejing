<?php
/*
 *  机构管理
 *  作者：dapeng.chen
    qq:645555438
 */
class InfoAction extends CommonAction {
        
        //机构列表
       public function info_list(){
           $warn = M('warn');
           import('@.ORG.Page');  
            
           if($_REQUEST['name']){
                $arr['name'] = array('like',"%".$_REQUEST['name']."%");
           }
           if($_GET['date']){
           		$start_time=strtotime(date('Y-m-d'))+$_GET['date']*24*3360;
           	    $end_time=$start_time+24*3360;
           	    if($_GET['date']>-3){
           	    	$arr['create_time']  = array(array('egt',$start_time), array('lt',$end_time)); 
           	    }else{
           	    	$arr['create_time']  = array('lt',$start_time); 
           	    }
           }
           if($_GET['type_id']==2){
            	$arr['type_id']=array('eq',2);
           }else{
             	$arr['type_id']=array('eq',1);
           }
           if($_GET['is_look']){
	           $arr['is_look']=array('eq','0');
	           $arr['type_id']=array('eq',2);
	        }
           $arr['pid']=array('eq',0);
           $count= $warn->where($arr)->count();// 查询满足要求的总记录数
           $Page=new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数 
           $list = $warn->where($arr)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
           //分页跳转的时候保证查询条件
           $admin=M('admin');
           $APPID=C('APPID');
		    $SECRET=C('SECRET');
			$access_token=$this->get_curl("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$SECRET");
		    $access_token=@get_object_vars(@json_decode($access_token));
           foreach ($list as $key=>$res){
           	   $where['admin_id']=array('eq',$res['admin_id']);
           	   if($where['admin_id']>0){
           	   	   $ares=$admin->where($where)->find();
           	   	   $user_url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token[access_token]&openid=$ares[weixin_key]&lang=zh_CN";
    				$url_res=@get_object_vars(@json_decode($this->get_curl($user_url)));
    				$list[$key][logo]=$url_res['headimgurl'];
           	   }
           }
           
           $show=$Page->show();// 分页显示输出
           $this->assign("info_list",$list);
           $this->assign("page",$show);
           $this->assign("count",$count);
           
           if($_GET['is_look']){
	           $look_where['is_look']=array('eq',0);
		       $look_data['is_look']=1;
		       $warn->where($look_where)->save($look_data);
	        }
	       
           $this->display();
       }
       
       public function info_send(){
       		if($this->isPost()){
       			header("Content-type: text/html; charset=utf-8");
       			$group_id=$this->_post('group_id');
       			
       			$sele_info=$this->_post('sele_info');
       			
       			$APPID=C('APPID');
				$SECRET=C('SECRET');
       			if($sele_info==0){
       				$add_data['content']=$send_content=$_POST['send_content'];
	       			if(empty($send_content)){
	       				$this->error("发送内容不能为空!");exit;
	       			}
					$access_token=$this->get_curl("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$SECRET");
				    $access_token=@get_object_vars(@json_decode($access_token));
				    $send_url="https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=$access_token[access_token]";
	       			if($this->_post('send_type')==1){
	       				$post_url="http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token[access_token]&type=image";
	       				$data=array('media'=>'@/var/www/html'.$this->_post('logo'));
	       				//$data[media]=$_FILES;
	       				$send_res=$this->post_curl($post_url,$data);
	       				$media_id=@get_object_vars(@json_decode($send_res));
	       				$media_id=$media_id['media_id'];
	       				
	       				$upnews_url="https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=$access_token[access_token]";
	       				$news_data='{
						   "articles": [
								 {
						             "thumb_media_id":"'.$media_id.'",
						             "author":"亿超眼镜",
									 "title":"亿超眼镜",
									 "content_source_url":"/",
									 "content":"'.$send_content.'",
									 "digest":"亿超眼镜",
						             "show_cover_pic":"1"
								 }
						   ]
						}';
	       				$news_res=$this->post_curl($upnews_url,$news_data);
	       				$media_id=@get_object_vars(@json_decode($news_res));
	       				$media_id=$media_id['media_id'];
	       				//print_r($news_res);exit;
	       				$data='{
						   "filter":{
						      "group_id":"'.$group_id.'"
						   },
						   "mpnews":{
						      "media_id":"'.$media_id.'"
						   },
						    "msgtype":"mpnews"
						}';
	       				$send_res=$this->post_curl($send_url,$data);
	       				//print_r($media_id);exit;
	       			}else{
	       				$data='{
						   "filter":{
						      "group_id":"'.$group_id.'"
						   },
						   "text":{
						      "content":"'.$send_content.'"
						   },
						    "msgtype":"text"
						}';
	       				
	       				$send_res=$this->post_curl($send_url,$data);
	       				//print_r($send_res);exit;
	       			}
       			}else{#选择模板发送
       				$id=(int)$this->_post('info_tpl_id');
       				$info=M('info');
       				$res=$info->where("id=$id")->find();
       				$send_content=$res['content'];
		       		$title=$res[title];
       				if($res){
       					$access_token=$this->get_curl("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$SECRET");
					    $access_token=@get_object_vars(@json_decode($access_token));
					    $send_url="https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=$access_token[access_token]";
		       			if($res[type_id]==1){
		       				$post_url="http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token[access_token]&type=image";
		       				$data=array('media'=>'@/var/www/html'.$res[images]);
		       				//$data[media]=$_FILES;
		       				$send_res=$this->post_curl($post_url,$data);
		       				$media_id=@get_object_vars(@json_decode($send_res));
		       				$media_id=$media_id['media_id'];
		       				
		       				$upnews_url="https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=$access_token[access_token]";
		       				
		       				$news_data='{
							   "articles": [
									 {
							             "thumb_media_id":"'.$media_id.'",
							             "author":"亿超眼镜",
										 "title":"'.$title.'",
										 "content_source_url":"/",
										 "content":"'.$send_content.'",
										 "digest":"亿超眼镜",
							             "show_cover_pic":"1"
									 }
							   ]
							}';
		       				$news_res=$this->post_curl($upnews_url,$news_data);
		       				$media_id=@get_object_vars(@json_decode($news_res));
		       				$media_id=$media_id['media_id'];
		       				//print_r($news_res);exit;
		       				$data='{
							   "filter":{
							      "group_id":"'.$group_id.'"
							   },
							   "mpnews":{
							      "media_id":"'.$media_id.'"
							   },
							    "msgtype":"mpnews"
							}';
		       				$send_res=$this->post_curl($send_url,$data);
		       				//print_r($media_id);exit;
		       			}else{
		       				$data='{
							   "filter":{
							      "group_id":"'.$group_id.'"
							   },
							   "text":{
							      "content":"'.$send_content.'"
							   },
							    "msgtype":"text"
							}';
		       				
		       				$send_res=$this->post_curl($send_url,$data);
		       			}
       				}
       			}
       			$this->success('发送成功！'); 
       			exit;
       			//发送短信
       		}
       		$APPID=C('APPID');
	    	$SECRET=C('SECRET');
			$access_token=$this->get_curl("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$SECRET");
	    	$access_token=@get_object_vars(@json_decode($access_token));
	    	$group_url="https://api.weixin.qq.com/cgi-bin/groups/get?access_token=$access_token[access_token]";
	    	$group_list=$this->get_curl($group_url);
	    	$group_list=@get_object_vars(@json_decode($group_list));
	    	$group_arr=$group_list['groups'];
	    	$group_arrlist='';
	    	foreach ($group_arr as $gkey=>$glist){
	    		$glist=@get_object_vars($glist);
	    		$group_arrlist[$gkey]['id']=$glist['id'];
	    		$group_arrlist[$gkey]['name']=$glist['name'];
	    		$group_arrlist[$gkey]['count']=$glist['count'];
	    	}
    		$this->assign("group_list",$group_arrlist);
   	        $this->display();
       }
       
       
       public function info_reply(){
       		if($this->isPost()){
       			header("Content-type: text/html; charset=utf-8");
       			$openid=$this->_post('openid');
       			$add_data['content']=$send_content=$_POST['send_content'];
       			if(empty($send_content)){
       				$this->error("发送内容不能为空!");exit;
       			}
       			$admin=M('admin');
       			$admin_where['admin_id']=array('eq',$this->_post('admin_id'));
       			//$admin_where['phone']=array('neq','');
       			$admin_list=$admin->field('weixin_key')->where($admin_where)->find();
       			$arr_mobiles='';
       			
       			
       			
       			$APPID=C('APPID');
			    $SECRET=C('SECRET');
				$access_token=$this->get_curl("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$SECRET");
			    $access_token=@get_object_vars(@json_decode($access_token));
			    $send_url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$access_token[access_token]";
			    
			    $data='{
				    "touser":"'.$openid.'",
				    "msgtype":"text",
				    "text":
				    {
				         "content":"'.$send_content.'"
				    }
				}';
			    //print_r($data);exit;
			    $news_res=$this->post_curl($send_url,$data);
       			
       			 
				$user_reply=M('user_reply');
				$warn_data['content']=$send_content;
				$warn_data['openid']=$openid;
				$warn_data['reply_openid']=$openid;
				$warn_data['create_time']=time();
				$res=$user_reply->add($warn_data);
				if($res){
       				$this->success('发送成功！'); 
       			}else{
       				$this->error("发送失败!");exit;
       			}
       			exit;
       			//发送短信
       		}
       		$user_reply=M('user_reply');
       		$openid=$this->_get('openid');
       		$user_reply_where['openid']=array('eq',$openid);
       		$user_reply_where['reply_openid']=array('eq',$openid);
       		$user_reply_where['_logic']='OR';
       		$reply_list=$user_reply->where($user_reply_where)->order('create_time')->select();
			$this->assign('reply_list',$reply_list);
   	        $this->display();
       }
       
       
       
       public function select_tel(){
	       $info=D('info');
	   	   $info_list=$info->select();
	       $this->assign('info_list',$info_list);
       	   $this->display();
       }
        
}

?>
