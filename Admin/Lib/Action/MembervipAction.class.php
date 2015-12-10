<?php
/** 
 * @date 2012.11.22
 * @author IT部门dongcong.huang 
 * @abstract 会员积分等级管理
 */  
class MembervipAction extends CommonAction {

    public function memberscore_list(){//会员积分列表

   	    $score=M("vip_score");
    	import('@.ORG.Page');  
        $arr="";
        
        if($_GET['findsub']){ 
        	$account=trim($_GET['account']);         	

        	if(!empty($account)){$arr['account'] = array('LIKE',"%".$account."%");$this->assign("account", $_GET['account']);}  
        	if (!empty($_GET['start_time']) AND !empty($_GET['end_time'])){
        		$arr['create_time'] = array(array('egt',strtotime($_GET['start_time'])),array('lt',strtotime($_GET['end_time'])+24*3600));$this->assign("end_time", $_GET['end_time']);$this->assign("start_time", $_GET['start_time']);
        	}elseif(!empty($_GET['start_time']) AND empty($_GET['end_time'])){
        		$arr['create_time'] = array('egt',strtotime($_GET['start_time']));$this->assign("start_time", $_GET['start_time']);
        	}elseif(!empty($_GET['end_time']) AND empty($_GET['start_time'])){$arr['create_time'] = array('lt',strtotime($_GET['end_time']));$this->assign("end_time", $_GET['end_time']);}
        }

        //if($_GET['_URL_']){
        //	$arr['account'] = array('eq',$_GET['_URL_'][2]);
        //}        
        
        
        $count = $score->where($arr)->count();    //计算总数 
        $p = new Page($count, 15);
        $score_list = $score->field('oak_vip_score.*,oak_user.account,oak_vip_event_define.name as eventname')->join('oak_vip_score left join oak_user on oak_vip_score.uid=oak_user.user_id')->join('left join oak_vip_event_define on oak_vip_score.event_code=oak_vip_event_define.code')->where($arr)->order('create_time DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $page = $p->show(); 
        $this->assign("page", $page);   
    	$this->assign('score_list', $score_list);  
   	
    	$this->display("Member:memberscore_list");
    }	

    public function memberscore_edit(){//会员积分管理#编辑

    	if($_POST){
    		$vipscoreup=D("vip_score");
    		$id=$_POST['id'];
    		if ($vo = $vipscoreup->create()) {
	            $list = $vipscoreup->save(); 
	            if ($list !== false) { 
	                $this->success('数据更新成功！',"__APP__/Membervip/memberscore_list/");
	            } else {
	                $this->error("没有更新任何数据!","__APP__/Membervip/memberscore_list/");
	            }
	        } else {
	            $this->error($vipscoreup->getError());
	        } 
	        exit;
    	}
    	$vipscore=M("vip_score");
    	$Id=$_GET["_URL_"][2];   
    	$condition['id'] = $Id; //使用查询条件
        $infoAll = $vipscore->field('oak_vip_score.*,oak_user.account')->join('oak_vip_score left join oak_user on oak_vip_score.uid=oak_user.user_id')->where($condition)->select();
        $this->assign('arr',$infoAll);
        
        $snapshot =json_decode($infoAll[0]['snapshot'],true);
        //var_dump($snapshot["jiner"]);
        $this->assign('snapshot',$snapshot);
		       
		$this->assign('typeName',"会员积分编辑");   	   	    	
    	$this->display("Member:memberscore_edit");
    }     
    
	public function membervip_list(){//会员等级列表

   	    $vipdefine=M("vip_define");
    	import('@.ORG.Page');
        $arr="";    	  
                                  
        $count = $vipdefine->count();    //计算总数 
        $p = new Page($count, 15);
        $vipdefine_list = $vipdefine->where($arr)->order('name ASC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $page = $p->show(); 
        $this->assign("page", $page);   
    	$this->assign('vipdefine_list', $vipdefine_list);  		
		
        $this->display("Member:membervip_list"); 
    }

    public function membervip_add(){//会员等级列表#添加
    	
   	if($_POST){
    		$vipdefineup=D("vip_define"); 
    		if ($vo = $vipdefineup->create()) {
	            $list = $vipdefineup->add(); 
	            if ($list !== false) { 
	                $this->success('数据保存成功！');
	            } else {
	                $this->error("数据写入错误!");
	            }
	        } else {
	            $this->error($vipdefineup->getError());
	        } 
	        exit;
    	}
    	$vipdefine=M("vip_define"); 
    	$this->assign('typeName',"添加会员等级");  
    	$this->assign('typeUrl',"__APP__/Member/membervip_add/");
    	$this->display("Member:membervip_add");
    } 

    public function membervip_edit(){//会员等级列表#编辑
 	
     	if($_POST){
     		if($_POST[is_freight]!=1){$_POST[is_freight]=0;}
    		$vipdefineup=D("vip_define");
    		$typeid=$_POST['type_id'];
    		if ($vo = $vipdefineup->create()) {
	            $list = $vipdefineup->save(); 
	            if ($list !== false) { 
	                $this->success('数据更新成功！',"__APP__/Membervip/membervip_list/");
	            } else {
	                $this->error("没有更新任何数据!","__APP__/Membervip/membervip_list/");
	            }
	        } else {
	            $this->error($vipdefineup->getError());
	        } 
	        exit;
    	}
    	$vipdefine=M("vip_define");
    	$vipname=$_GET["_URL_"][2];   
    	$condition['name'] = $vipname; //使用查询条件
        $infoAll = $vipdefine->where($condition)->select();  
        $this->assign('arr',$infoAll);
        
    	$this->assign('typeName',"会员等级编辑"); 
    	$this->display("Member:membervip_add");    	
    } 
        
    public function membervip_del(){//会员等级列表#删除
    	
    	$this->display("Member:membervip_list");
    }    

    public function memberloginlog_list(){//会员登录日志
    	
    	$this->display("Member:memberloginlog_list");
    }
    
}