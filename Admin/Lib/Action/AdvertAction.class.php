<?php
/** 
 * @date 2012.7.20
 * @author IT部门dapeng.chen 
 * @deprecated 橡树游戏平台，莱科版权所有
 * @abstract 广告管理
 */  
class AdvertAction extends CommonAction {
    public function advert_list(){//广告列表
    	$advert=M("advert");
    	import('@.ORG.Page'); 
        $title="";
        if($_GET['findsub']){ 
        	$title=$_GET['title'];
        	if(!empty($title)){$arr['cms_advert.title'] = array('LIKE',"%".$title."%"); $this->assign("title", $title);  }
        	if($_GET['status']!=2){$arr['cms_advert.status'] = array('eq',$_GET['status']); 
        	     $this->assign("status", $_GET['status']);  
        	}else{$this->assign("status", 2);}
        	if($_GET['type_id']!=0){$arr['cms_adverttype.type_id'] = array('eq',$_GET['type_id']); 
        	     $this->assign("type_id", $_GET['type_id']);  
        	}
        	if($_GET['game_id']>0){
        		 $arr['cms_game.game_id'] = array('eq',$_GET['game_id']); 
        	     $this->assign("game_id", $_GET['game_id']);  
        	}
        }
        $arr['cms_advert.is_del']=array('eq','0');
        $count = $advert->where($arr)->count();    //计算总数 
        $p = new Page($count, 20); 
        $advert_list = $advert->join('cms_advert LEFT JOIN cms_adverttype ON cms_advert.type_id = cms_adverttype.type_id')->join('LEFT JOIN cms_game ON cms_advert.game_id=cms_game.game_id')->field('cms_advert.*,cms_adverttype.type_name,cms_game.game_name')->where($arr)->order('cms_advert.sort ASC,cms_advert.id DESC')->limit($p->firstRow . ',' . $p->listRows)->select(); 
        $page = $p->show();
        $this->assign("page", $page); 
        $this->assign('advert_list', $advert_list);
         
        $con=C('ADVERTIMGURL');
    	$this->assign('advertimgurl',$con);
    	
    	$game=M('game');
    	$g_where['status']=array('eq','1');
    	$g_where['is_del']=array('eq','0');
    	$game_list=$game->field('game_id,game_name')->where($g_where)->select();
    	$this->assign('game_list',$game_list);
    	
    	$adverttype=M("adverttype");
    	$adverttype_list = $adverttype->select();
    	$this->assign('adverttype_list',$adverttype_list);
    	
        $this->display("Advert:advert_list"); 
    }
    
    public function advert_add(){//添加广告
    	if($_POST){
    		$advertup=D("advert"); 
			$_POST['open_time'] = strtotime($_POST['open_time']);
    		if ($vo = $advertup->create()) {
	            $list = $advertup->add(); 
	            if ($list !== false) { 
	                $this->success('数据保存成功！',"__APP__/Advert/advert_list/");
	            } else {
	                $this->error("数据写入错误!","__APP__/Advert/advert_list/");
	            }
	        } else {
	            $this->error($advertup->getError());
	        } 
	        exit;
    	}
    	
    	$adverttype=M("adverttype");
    	$adverttype_list = $adverttype->select();
    	$this->assign('adverttype_list',$adverttype_list);
    	
    	$this->assign('updivishide',"style='display:none;'");
    	$this->assign('updivisshow',"style=''");
    	$this->assign('updivishide2',"style='display:none;'");
    	$this->assign('updivisshow2',"style=''");
    	
    	$con=C('ADVERTIMGURL');
    	$this->assign('advertimgurl',$con);
    	
    
		$this->assign('open_time',date('Y-m-d H:i:s'));
		
    	$this->assign('typeName',"添加广告");
    	$this->assign('typeUrl',"__APP__/Advert/advert_add/");
    	$this->display("Advert:advert_add");
    }
    
    public function advert_edit(){//广告编辑
    	if($_POST){
    		$advertup=D("advert");
    		$id=$_POST['id'];
			$_POST['open_time'] = strtotime($_POST['open_time']);
    		if ($vo = $advertup->create()) {
	            $list = $advertup->save(); 
	            if ($list !== false) { 
	                $this->success('数据更新成功！',"__APP__/Advert/advert_list/");
	            } else {
	                $this->error("没有更新任何数据!","__APP__/Advert/advert_list/");
	            }
	        } else {
	            $this->error($advertup->getError());
	        } 
	        exit;
    	}
    	$con=C('ADVERTIMGURL');
    	$this->assign('advertimgurl',$con);
    	$advert=M("advert");
    	$id=$_GET["_URL_"][2];   
    	$condition['id'] = $id; //使用查询条件
        $infoAll = $advert->where($condition)->select();  
        $infoAll[0]['small_icon2']=$con.$infoAll[0]['small_icon'];
        $infoAll[0]['large_icon2']=$con.$infoAll[0]['large_icon'];
        $this->assign('arr',$infoAll);
        
        if($infoAll[0][small_icon]==""){
    		$this->assign('updivisshow',""); 
    		$this->assign('updivishide',"style='display:none;'"); 
    	}else{
    		$this->assign('updivishide',""); 
    		$this->assign('updivisshow',"style='display:none;'"); 
    	}
    	if($infoAll[0][large_icon]==""){
    		$this->assign('updivisshow2',"");
    		$this->assign('updivishide2',"style='display:none;'");
    	}else{
    		$this->assign('updivishide2',"");
    		$this->assign('updivisshow2',"style='display:none;'");
    	}
    	
    	$adverttype=M("adverttype");
    	$adverttype_list = $adverttype->select();
    	$this->assign('adverttype_list',$adverttype_list);
    	
    	
		$this->assign('open_time',date('Y-m-d H:i:s',$infoAll[0]['open_time']));
		
    	$this->assign('typeName',"广告编辑");
    	$this->assign('typeUrl',"__APP__/Advert/advert_edit/");
    	$this->display("Advert:advert_add");
    }
 
    public function advert_del(){//删除广告
    	if($_GET["_URL_"][2]){
    		$id=$_GET["_URL_"][2];
    	}else{
    	    $id=$_POST['id'];
    	}
    	if (!empty($id)) {
            //$advert = M("advert");
            if(is_array($id)){
            	//$id=implode(",",$id);
            	//$result = $advert->where("id in($id)")->delete();   
            	$result=$this->add_recycle('advert','id',$id);
            }else{
            	//$result = $advert->delete($id);  
            	$result=$this->add_recycle('advert','id',array($id));
            }
            if (false !== $result) { 
               $this->success('删除成功！',"__APP__/Advert/advert_list/");
	        } else {
	           $this->error('删除出错！',"__APP__/Advert/advert_list/");
	        }
        }else {
            $this->error('删除项不存在！',"__APP__/Advert/advert_list/");
        }  
        exit;
    }
    
    public function advert_upall(){//广告更新
    	if($_POST){
    		$advertup=D("advert");
    		$id=$_POST['sortid'];
    		$sort=$_POST['sort']; 
    		for($i=0;$i<count($id);$i++){
    			$arr['sort']=$sort[$i];
    			$list =$advertup->where("id=$id[$i]")->save($arr); // 根据条件保存修改的数据
    		}
    		if($list !== false){
    			$this->success('数据更新成功！');
    			exit;
    		}else {
	            $this->error($advertup->getError());exit;
	        }  
    	}else{
    		$this->error('操作错误！');exit;
    	}  
    }
     
}