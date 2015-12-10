<?php

class ActivityAction extends PublicAction {
	
	
	/*
	 *  活动首页
	 */	
    public function index(){
        $code=$this->_get('code');
		if(!empty($code)){
	    	$open_id=get_openid($code);
		}elseif(!empty($_GET['weixin_key'])){
			session('weixin_key',$_GET['weixin_key']);
		}
    	for($i=0;$i<12;$i++){
    		$t=strtotime("$i month");
    		$str=date("Y年m月", strtotime("$i month"));
    		$date_arr[]=array($t,$str);
    	}
     	$this->assign('list',$date_arr);
		$this->display();
    }
    
    /*
	 *  活动列表页
	 */	
    public function activity_list(){
    	$act_time=$this->_get('act_time');
    	$begin_time=strtotime(date('Y-m',$act_time).'-01');
    	$d_str=date('Y-m',$act_time).'-01';
    	$end_time=strtotime(date('Y-m-d', strtotime("$d_str +1 month -1 day")));
    	$news=M('news');
    	$act_where['create_time'] = array(array('egt',$begin_time),array('lt',$end_time+24*3600));
    	$act_where['status']=array('eq',1);
    	$act_where['is_del']=array('eq',0);
    	$act_where['type_id']=array('eq',6);
    	$news_list=$news->field('id,title')->where($act_where)->order('id DESC')->limit(30)->select();
    	 
    	$newstype=M('newstype');
	    $newstype->where("type_id=6")->setInc('click_num');
    	
     	$this->assign('list',$news_list);
		$this->display();
    }
    
    /*
	 *  活动列表页
	 */	
    public function activity_detail(){
    	$id=$this->_get('id');
    	$activity_apply=M('activity_apply');
    	$apply_where['act_id']=array('eq',$id);
    	$apply_where['weixin_key']=array('eq',session('weixin_key'));
    	$apple_res=$activity_apply->where($apply_where)->find();
    	
    	$list=get_news_detail($id,'title,content,create_time');
    	if(!empty($apple_res)){
    		$is_apple=3;#已报名
    	}elseif($list['create_time']<time()){
    		$is_apple=2;#已过期
    	}else{
    		$is_apple=1;#活动正常
    	}
    	$this->assign('is_apple',$is_apple);
     	$this->assign('list',$list);
     	$this->assign("seo_title",$list['title']);
		$this->display();
    }
    
    /*
	 *  活动报名
	 */	
    public function activity_apply(){
		$this->display();
    }
    
    
    /*
	 * AXAX提交活动报名
	 */	
    public function ajaxSubActivity(){
    	//session('weixin_key','ssdwukk');
    	$id=$this->_post('id');
    	$username=$this->_post('username');
    	$identity=$this->_post('identity');
    	$tel=$this->_post('tel');
    	$weixin_key=session('weixin_key');
    	if(!empty($weixin_key)){
    		$activity_apply=M('activity_apply');
    		$act_where['act_id']=array('eq',$id);
    		$act_where['weixin_key']=array('eq',$weixin_key);
    		$act_res=$activity_apply->where($act_where)->find();
    		if(!empty($act_res)){
    			$this->ajaxReturn('',"该活动你已经报过名了！",-2);
    		}else{
    			$add_data['act_id']=$id;
    			$add_data['username']=$username;
    			$add_data['identity']=$identity;
    			$add_data['weixin_key']=$weixin_key;
    			$add_data['tel']=$tel;
    			$add_data['create_time']=time();
    			$res=$activity_apply->add($add_data);
    			if($res){
    				$this->ajaxReturn('',"报名成功！",1);
    			}else {
    				$this->ajaxReturn('',"报名失败！",-5);
    			}
    		}
    	}else{
    		$this->ajaxReturn('',"请通过菜单重新进入！",-1);
    	}
    }
    
    
    /*
	 *  首页
	 */	
    public function wq_essence(){
    	$code=$this->_get('code');
		if(!empty($code)){
	    	$open_id=get_openid($code);
		}elseif(!empty($_GET['weixin_key'])){
			session('weixin_key',$_GET['weixin_key']);
		}
		$cur_time=strtotime('2014-01-01');
    	for($i=1;$i<13;$i++){
    		$t=strtotime("-$i month");
    		if($t>$cur_time){
    			$str=date("Y年m月", strtotime("-$i month"));
    		    $date_arr[]=array($t,$str);
    		}
    	}
     	$this->assign('list',$date_arr);
		$this->display();
    }
    
    public function ajaxMyActivity(){//我的活动-ajax分页
    	$p=$this->_get('p');#页
    	$begin_page=$p*8+1;  #开始
    	$end_page=$begin_page+8;
    	$activity=M('Activity');
    	$activity_where['is_del']=array('eq',0);
    	$activitys=$activity->field('id,title,logo,begin_time,end_time,age_begin,age_end,sign_end_time')->where($activity_where)->limit(8)->limit("$begin_page,$end_page")->order('create_time DESC')->select(); 
    	$agearr=C('USER_AGE');
    	foreach($activitys as $key=>$list){
    		$arr[$key]['id']=$list[id];
    		$arr[$key]['name']=$list[title];
    		$arr[$key]['logo']=$list[logo];
    		$arr[$key]['time']=date('Y-m-d H:i',$list[begin_time]).' 至 '.date('Y-m-d H:i',$list[end_time]);
    		$arr[$key]['age']=$agearr[$list['age_begin']].'-'.$agearr[$list['age_end']];
    		//if($act[end_time]<time()){$arr[$key]['status']='<i class="cutoff">已结束</i>';}elseif($act[sign_end_time]<time()){$arr[$key]['status']='<i class="cutoff">报名截止</i>';}elseif(in_array($act[id],$learn_arr)){$arr[$key]['status']='<i class="cutoff">已报名</i>';}else{$arr[$key]['status']='<i class="apply">我要报名</i>';}
    		if($act[end_time]<time()){$arr[$key]['status']='';}elseif($act[sign_end_time]<time()){$arr[$key]['status']='';}elseif(in_array($act[id],$learn_arr)){$arr[$key]['status']='<i class="cutoff">已报名</i>';}else{$arr[$key]['status']='<i class="apply">我要报名</i>';}
    	}
    	$this->ajaxReturn($arr,"",0);
    }
    
	/*
	 *  活动明细
	 */	
    public function detail(){
    	$id =intval($_GET['id']);
    	$activity=M('Activity');
    	$activitys=$activity->where("id=$id")->field('id,title,logo,address,activity_type,age_begin,age_end,begin_time,end_time,content,sign_end_time')->find();
    	//$aid=intval($_GET['id']);
    	$type=$activitys['activity_type'];   	
    	
    	$endtime=$activitys['end_time'];
    	$current=time();
    	$learn=M('learn');   	
    	$id=$activitys[id];   	
    	$le_where['related_id']=array('eq',$id);
    	$le_where['type_id']  =array('eq',1);
    	$le_where['weixin_key']  =array('eq',session('weixin_key'));
    	$wxk=$learn->where($le_where)->find();
    	if($activitys['end_time']<time()){
    		//$bm_status="<div class='apply-btn'><a style='background: #9f9f9f;border-color: #8e8e8e;box-shadow: inset 0 1px 0 0 #afafaf;'>已结束</a></div>";
    	}elseif($activitys['sign_end_time']<time()){
    		//$bm_status="<div class='apply-btn'><a style='background: #9f9f9f;border-color: #8e8e8e;box-shadow: inset 0 1px 0 0 #afafaf;'>报名截止</a></div>";
    	}elseif(!empty($wxk)){
    		//$bm_status="<div class='apply-btn'><a style='background: #9f9f9f;border-color: #8e8e8e;box-shadow: inset 0 1px 0 0 #afafaf;'>已报名</a></div>";
    	}else{
    		$bm_status="<div class='apply-btn'><a href='__URL__/apply/id/$id'>我要报名</a></div>";
    	}
    	$activity_type=C('ACTIVITY_TYPE');
    	$activitys[bm_status]=$bm_status;
    	$activitys['activity_type']=$activity_type[$activitys[activity_type]];
    	//判断活动时间是否为同一天
    	$beginday=date('Y-m-d',$activitys['begin_time']);
    	$endday=date('Y-m-d',$activitys['end_time']);
    	if($beginday==$endday){
    		$activitys['state']=1;
    	}else{
    		$activitys['state']=0;
    	}
    	
    	$this->assign('activity',$activitys);
    	//$activity_type=intval($activitys['activity_type']);
    	$now_time=time();
    	$correlations=$activity->where("activity_type=$type AND is_del=0 AND end_time>$now_time AND id<>$id")->field('id,title')->order('create_time desc')->select();
    	
    	$this->assign('correlation',$correlations);
    	$config=M('config');
    	$con=$config->field('tel,address')->find();
    	$this->assign('config',$con);
		$this->assign('agearr',C('USER_AGE'));
		$this->display('detail');
    }

    public function apply(){
    	$id =intval($_GET['id']);
    	$this->assign('id',$id);
    	$this->display('apply');
    }
    
    public function subAjax(){
    	    $add_date['weixin_key']=session('weixin_key');
    		$add_date['related_id']=$_POST['id'];
        	$add_date['create_time']=time();
    		$add_date['tel']=$_POST['tel'];
    		$add_date['name']=  $_POST['name'];
    		$add_date['sex']=  $_POST['sex'];
    		$year=           $_POST['year'];
    		$month=           $_POST['month'];
    		$day=           $_POST['day'];
    		$add_date['birthday']= strtotime("$year-$month-$day");
    		$learn=M('Learn');
    		$activity=M('activity');
    		$act_res=$activity->where("id=$add_date[related_id]")->field('limit_num')->find();
    		if($act_res['limit_num']>0){ #判断活动是否限制人数
    			$cou_where['related_id']=array('eq',$add_date[related_id]);
    			$cou_where['type_id']=array('eq',1);
    			$count=$learn->where($cou_where)->count(); #已报名人数
    			
    			if($act_res['limit_num']<$count or $act_res['limit_num']==$count){
    				echo -1;exit;
    			}
    		}
    		$weixin_key=session('weixin_key');
    		$old_where['type_id']=array('eq',1);
    		$old_where['related_id']=array('eq',$add_date[related_id]);
    		$old_where['weixin_key']=array('eq',"$weixin_key");
    		$lesrn_res=$learn->where($old_where)->field('id')->find();
    		if(!empty($lesrn_res)){
    			echo -2;exit;
    		}
    		
    		$result=intval($learn->add($add_date));
    		if ($result){
    			echo 1;
    		}else {
    			echo 0;
    		}
    		exit;
    }
    

}


?>