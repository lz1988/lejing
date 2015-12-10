<?php
/** 
 * @date 2012.7.20
 * @author IT部门dapeng.chen 
 * @deprecated 橡树游戏平台，莱科版权所有
 * @abstract 广告组管理
 */  
class AdverttypeAction extends CommonAction {
    public function adverttype_list(){//广告组列表
    	$adverttype=M("adverttype");
    	import('@.ORG.Page'); 
        $type_name="";
        if($_GET){ 
        	$type_name=$_GET['type_name'];
        } 
        $arr['type_name'] = array('LIKE',"%".$type_name."%"); 
        $arr['is_del']=array('eq','0');
        $count = $adverttype->where($arr)->count();    //计算总数 
        $p = new Page($count, 20); 
        $adverttype_list = $adverttype->where($arr)->order('type_id DESC')->limit($p->firstRow . ',' . $p->listRows)->select(); 
        $page = $p->show();
        $this->assign("page", $page); 
        $this->assign("type_name", $type_name); 
    	$this->assign('adverttype_list', $adverttype_list);
        $this->display("Advert:adverttype_list"); 
    }
    
    public function adverttype_add(){//添加广告组
    	if($_POST){
    		$adverttypeup=D("adverttype"); 
    		if ($vo = $adverttypeup->create()) {
	            $list = $adverttypeup->add(); 
	            if ($list !== false) { 
	                $this->success('数据保存成功！',"__APP__/Adverttype/adverttype_list/");
	            } else {
	                $this->error("数据写入错误!","__APP__/Adverttype/adverttype_list/");
	            }
	        } else {
	            $this->error($adverttypeup->getError());
	        } 
	        exit;
    	}
    	$adverttype=M("adverttype"); 
    	$this->assign('typeName',"添加广告组");
    	$this->assign('typeUrl',"__APP__/Adverttype/adverttype_add/");
    	$this->display("Advert:adverttype_add");
    }
    
    public function adverttype_edit(){//广告组编辑
    	if($_POST){
    		$adverttypeup=D("adverttype");
    		$typeid=$_POST['type_id'];
    		if ($vo = $adverttypeup->create()) {
	            $list = $adverttypeup->save(); 
	            if ($list !== false) { 
	                $this->success('数据更新成功！',"__APP__/Adverttype/adverttype_list/");
	            } else {
	                $this->error("没有更新任何数据!","__APP__/Adverttype/adverttype_list/");
	            }
	        } else {
	            $this->error($adverttypeup->getError());
	        } 
	        exit;
    	}
    	$adverttype=M("adverttype");
    	$typeId=$_GET["_URL_"][2];   
    	$condition['type_id'] = $typeId; //使用查询条件
        $infoAll = $adverttype->where($condition)->select();  
        $this->assign('arr',$infoAll);
    	$this->assign('typeName',"广告组编辑");
    	$this->assign('typeUrl',"__APP__/Adverttype/adverttype_edit/");
    	$this->display("Advert:adverttype_add");
    }
 
    public function adverttype_del(){//删除广告组
    	if($_GET["_URL_"][2]){
    		$typeId=$_GET["_URL_"][2];
    	}else{
    	    $typeId=$_POST['type_id'];
    	}
    	if (!empty($typeId)) {
            $advert = M("advert");
            if(is_array($typeId)){
            	$wheretypeId=implode(",",$typeId);
            	$gamewhere['type_id']=array('in',$wheretypeId);
             	$game_info=$advert->field('type_id')->where($gamewhere)->limit(1)->find(); 
            	if(empty($game_info)){ 
            	    $result=$this->add_recycle('adverttype','type_id',$typeId);  
            	}else{
            		$this->error('您所选的类型下有新闻信息，不能删除！');
            	}
            }else{
            	$gamewhere['type_id']=array('eq',$typeId);
             	$game_info=$advert->field('type_id')->where($gamewhere)->limit(1)->find(); 
            	if(empty($game_info)){ 
            	    $result=$this->add_recycle('adverttype','type_id',array($typeId));
            	}else{
            		$this->error('您所选的类型下有新闻信息，不能删除！');
            	} 
            }
            if (false !== $result) { 
               $this->success('删除成功！',"__APP__/Adverttype/adverttype_list/");
	        } else {
	           $this->error('删除出错！',"__APP__/Adverttype/adverttype_list/");
	        }
        }else {
            $this->error('删除项不存在！',"__APP__/Adverttype/adverttype_list/");
        }  
        exit;
    }
    
}