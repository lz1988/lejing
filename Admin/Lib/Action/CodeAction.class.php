<?php
/** 
 * @date 2012.7.23
 * @author IT部门dapeng.chen 
 * @deprecated 橡树游戏平台，莱科版权所有
 * @abstract 会员管理
 */  
class CodeAction extends CommonAction {
    public function code_list(){//会员列表
    	$code=M('code');
    	if($this->isPost()){
    		$title=$this->_post('title');
    		if(empty($title)){
    			$this->error('名称不能为空');exit;
    		}
    		$add_data['title']=$title;
    		$res=$code->add($add_data);
    		if($res){
    			$info=M('info');
    			$info_list=$info->field('id,title')->select();
    			$this->assign('info_list',$info_list);
    			
    			$vip_define=M('vip_define');
		    	$vip_list=$vip_define->select();
		    	$this->assign('vip_list', $vip_list);
		    	
		    	$user_label=M('user_label');
		    	$label_list=$user_label->select();
		    	$this->assign('label_list', $label_list);
    			
		    	$this->assign('id', $res);
		    	
		    	$APPID=C('APPID');
    			$SECRET=C('SECRET');
		    	$access_token=$this->get_curl("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$SECRET");
    			$access_token=@get_object_vars(@json_decode($access_token));
    			$create_url  = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token[access_token]";

				$create_data = json_encode(array('action_name'=>'QR_LIMIT_SCENE','action_info'=>array('scene'=>array('scene_id'=>$res))));
				$return_data=$this->post_curl($create_url,$create_data);
				
				$return_data = json_decode($return_data,true);
				$ticket = $return_data["ticket"];
				$images = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
				$save_data['images']=$images;
				$code->where("id=$res")->save($save_data);
		    	
				$this->assign('images', $images);
    			$this->display('code_add');
    		}else{
    			$this->error('错误');
    		}
    		exit;
    	}
    	
    	
    	
    	import('@.ORG.Page');  
        $arr="";
        if($_GET['findsub']){ 
        	
        	if (!empty($_GET['start_time']) AND !empty($_GET['end_time'])){
        		$arr['u.create_time'] = array(array('egt',strtotime($_GET['start_time'])),array('lt',strtotime($_GET['end_time'])+24*3600));
        	}elseif(!empty($_GET['start_time']) AND empty($_GET['end_time'])){
        		$arr['u.create_time'] = array('egt',strtotime($_GET['start_time']));
        	}elseif(!empty($_GET['end_time']) AND empty($_GET['start_time'])){$arr['create_time'] = array('lt',strtotime($_GET['end_time']));}
         }
         
        $arr['user_id']=array('eq',0);
        $count = $code->where($arr)->count();    //计算总数 
	    $p = new Page($count, 20);
	    $code_list=$code->limit($p->firstRow . ',' . $p->listRows)->where($arr)->order('id DESC')->select();
	    $page = $p->show();
        $this->assign("page", $page);   
    	$this->assign('code_list', $code_list);
        $this->display(); 
    }
    
    public function code_edit(){
       $code=D('code');
       if($this->isPost()){
       	    $is_res=false;
    		if ($vo = $code->create()) { 
	            $list = $code->save();
	            if ($list !== false) { 
	            	$is_res=true; 
	            } else { 
	                $is_res=false;
	            }
	        } else { 
	            $is_res=false;
	        }
	        if($is_res==true){
	        	$this->success('数据保存成功！',"__APP__/Code/code_list/");
	        }else{
	        	$this->error("数据写入错误!","__APP__/Code/code_list/");
	        }
	        exit;
       }
    }
    
    public function code_modify(){
       $code=D('code');
       if($this->isPost()){
       	    $is_res=false;
    		if ($vo = $code->create()) { 
	            $list = $code->save();
	            if ($list !== false) { 
	            	$is_res=true; 
	            } else { 
	                $is_res=false;
	            }
	        } else { 
	            $is_res=false;
	        }
	        if($is_res==true){
	        	$this->success('数据保存成功！',"__APP__/Code/code_list/");
	        }else{
	        	$this->error("数据写入错误!","__APP__/Code/code_list/");
	        }
	        exit;
       }
       
       $id=$this->_get('id');
       $info=M('info');
       $info_list=$info->field('id,title')->select();
       $this->assign('info_list',$info_list);
    			
       $vip_define=M('vip_define');
	   $vip_list=$vip_define->select();
	   $this->assign('vip_list', $vip_list);
		    	
	   $user_label=M('user_label');
	   $label_list=$user_label->select();
	   $this->assign('label_list', $label_list);
       
	   $code_res=$code->where("id=".$id)->find();
	   $this->assign('arr', $code_res);
       $this->display('code_modify');
    }
    
    /**
	* 删除SEO栏目
	* @param seo_id 
	* @param null
	* @return null
	*/
	public function code_del(){//
    	$id=$this->_get('id');
    	if (!empty($id)) {
    		$code=M('code');
    		$del_where['id']=array('eq',$id);
    		$result=$code->where($del_where)->delete();
            if (false !== $result) { 
               $this->success('删除成功！');
	        } else {
	           $this->error('删除出错！');
	        }
        }else {
            $this->error('删除项不存在！');
        }  
        exit;
    }
    
   public function add_score(){//会员积分管理#编辑

    	if($_POST){
    		if($this->_post('score')<1){
    			$this->error("请填写积分!");exit;
    		}
    		$vipscoreup=M("vip_score");
    		$add_date['update_time']  = time();
    		$add_date['create_time']  = time();
    		$add_date['uid']  = (int) $this->_post('user_id');
    		$add_date['event_code']  = 'sys';
    		$add_date['score']  = $this->_post('score');
    		$add_date['descri']  = $this->_post('descri');
    		$list = $vipscoreup->add($add_date); 
    		 if ($list !== false) {
    		 	 $user=M('user');
    		 	 $user->where("user_id=$add_date[uid]")->setInc('score',$add_date['score']); 
	             $this->success('数据更新成功！',"__APP__/Member/member_list/");
	         } else {
	             $this->error("没有更新任何数据!");
	         }
	        exit;
    	}
    	$this->display(); 
   }
   
   public function info_list(){
   	   $info=D('info');
   	   $info_list=$info->select();
       $this->assign('info_list',$info_list);
   	   $this->display();
   }
   
   public function info_add(){
   	   $info=D('info');
       if($this->isPost()){
       	    $is_res=false;
    		if ($vo = $info->create()) { 
	            $list = $info->add();
	            if ($list !== false) { 
	            	$is_res=true; 
	            } else { 
	                $is_res=false;
	            }
	        }else { 
	            $is_res=false;
	        }
	        if($is_res==true){
	        	$this->success('数据保存成功！',"__APP__/Code/info_list/");
	        }else{
	        	$this->error("数据写入错误!","__APP__/Code/info_list/");
	        }
	        exit;
       }
   	   $this->display();
   }
   
   public function info_edit(){
   	   $info=D('info');
       if($this->isPost()){
       	    $is_res=false;
    		if ($vo = $info->create()) { 
	            $list = $info->save();
	            if ($list !== false) { 
	            	$is_res=true; 
	            } else { 
	                $is_res=false;
	            }
	        } else { 
	            $is_res=false;
	        }
	        if($is_res==true){
	        	$this->success('数据保存成功！',"__APP__/Code/info_list/");
	        }else{
	        	$this->error("数据写入错误!","__APP__/Code/info_list/");
	        }
	        exit;
       }
       $id=$this->_get('id');
       $arr=$info->where("id=$id")->find();
       $this->assign('arr',$arr);
   	   $this->display('info_add');
   }
   
   
   public function code_user_list(){//会员列表
    	$code=M('code');
    	if($this->isPost()){
    		$title=$this->_post('title');
    		if(empty($title)){
    			$this->error('名称不能为空');exit;
    		}
    		$add_data['title']=$title;
    		$res=$code->add($add_data);
    		if($res){
    			$info=M('info');
    			$info_list=$info->field('id,title')->select();
    			$this->assign('info_list',$info_list);
    			
    			$vip_define=M('vip_define');
		    	$vip_list=$vip_define->select();
		    	$this->assign('vip_list', $vip_list);
		    	
		    	$user_label=M('user_label');
		    	$label_list=$user_label->select();
		    	$this->assign('label_list', $label_list);
    			
		    	$this->assign('id', $res);
		    	
		    	$APPID=C('APPID');
    			$SECRET=C('SECRET');
		    	$access_token=$this->get_curl("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$SECRET");
    			$access_token=@get_object_vars(@json_decode($access_token));
    			$create_url  = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token[access_token]";

				$create_data = json_encode(array('action_name'=>'QR_LIMIT_SCENE','action_info'=>array('scene'=>array('scene_id'=>$res))));
				$return_data=$this->post_curl($create_url,$create_data);
				
				$return_data = json_decode($return_data,true);
				$ticket = $return_data["ticket"];
				$images = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
				$save_data['images']=$images;
				$code->where("id=$res")->save($save_data);
		    	
				$this->assign('images', $images);
    			$this->display('code_add');
    		}else{
    			$this->error('错误');
    		}
    		exit;
    	}
    	
    	$user=M('user');
    	import('@.ORG.Page');
        $arr['is_tg']=array('neq',1);
        $count = $user->where($arr)->count();    //计算总数 
	    $p = new Page($count, 20);
	    $user_list=$user->limit($p->firstRow . ',' . $p->listRows)->where($arr)->order('user_id DESC')->select();
	    $page = $p->show();
        $this->assign("page", $page);   
    	$this->assign('user_list', $user_list);
        $this->display(); 
    }
    
    public function user_tg_audit(){
    	$user_id=$this->_get('user_id');
    	$user=M('user');
    	if($user_id>0){
    		$code=M('code');
    		$add_data['title']=$this->_get('username');
    		$add_data['user_id']=$user_id;
    		$code_res=$code->where("user_id=$user_id")->find();
			if(empty($code_res)){
				$res=$code->add($add_data);
			}else{
				$res=$code_res['user_id'];
			}
    		
    		$APPID=C('APPID');
    		$SECRET=C('SECRET');
		    $access_token=$this->get_curl("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$SECRET");
    		$access_token=@get_object_vars(@json_decode($access_token));
    		$create_url  = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token[access_token]";

			$create_data = json_encode(array('action_name'=>'QR_LIMIT_SCENE','action_info'=>array('scene'=>array('scene_id'=>$res))));
			$return_data=$this->post_curl($create_url,$create_data);
				
			$return_data = json_decode($return_data,true);
			$ticket = $return_data["ticket"];
			$images = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
			
			$add_save_data['images']=$images;
    		$code_res=$code->where("user_id=$user_id")->find();
			$res2=$code->where("id=$res")->save($add_save_data);
    		
    		$save_data['is_tg']=1;
    		$res=$user->where("user_id=$user_id")->save($save_data);
    		if($res==true){
	        	$this->success('数据保存成功！',"__APP__/Code/code_user_list/");
	        }else{
	        	$this->error("数据写入错误!","__APP__/Code/code_user_list/");
	        }
    	}
    }
    
    public function tg_user(){
    	$user=M('user');
    	import('@.ORG.Page');
        $arr['u.is_tg']=array('eq',1);
        $count = $user->where($arr)->count();    //计算总数 
	    $p = new Page($count, 5);
	    $user_list=$user->join('as u LEFT JOIN cms_code as c ON u.user_id=c.user_id')->field('u.*,c.images')->limit($p->firstRow . ',' . $p->listRows)->where($arr)->order('u.user_id DESC')->select();
	    $page = $p->show();
        $this->assign("page", $page);   
    	$this->assign('user_list', $user_list);
        $this->display(); 
    }
    
    public function send_user_commission(){
    	$user_id=$this->_get('user_id');
    	
    	$timestamp=time();
        $firstday=strtotime(date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01')));
        $lastday=strtotime(date('Y-m-d',strtotime("$firstday +1 month -1 day")));
        $com_where[create_time]=array(array('egt',$firstday),array('elt',$lastday));
        
        $user=M('user');
        $orders=M('orders');
        $tg_list=$user->field('openid')->where("tg_user=$user_id")->select();
	    $tg_user_arr='';
	    foreach($tg_list as $list){
	        $tg_user_arr[]=$list['openid'];
	    }
	    $tg_user_str=implode(',',$tg_user_arr);
	    
	    $orders_where['create_time']=array(array('egt',$firstday),array('elt',$lastday));
	    $orders_where['openid']=array('in',$tg_user_str);
	    $m_money=$orders->where($orders_where)->sum('amount');
	    if($m_money>0){
	    	$add_data['user_id']=$user_id;
		    $add_data['money']=$m_money;
		    $add_data['send_time']=time();
		    $add_data['create_time']=$lastday;
		    $user_commission=M('user_commission');
		    $res=$user_commission->add($add_data);
		    if($res){
		    	$this->success('发放成功');
		    }else{
		    	$this->error('发放失败');
		    }
	    }else{
	    	$this->error('没有可发放的金额');
	    }
	    exit;
    }
    
    
    public function tg_user_info(){
    	$user_id=$this->_get('user_id');
    }
   
}