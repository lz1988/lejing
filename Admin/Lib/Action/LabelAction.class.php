<?php
/** 
 * @date 2012.7.25
 * @author IT部门dapeng.chen 
 * @deprecated 橡树游戏平台，莱科版权所有
 * @abstract  用户标签
 */  
class LabelAction extends CommonAction {
    public function label_list(){//标签列表
    	$label=M("UserLabel");
    	import('@.ORG.Page');  
        $arr="";
        if($_GET['findsub']){ 
        	$name=trim($_GET['name']); 
        	if(!empty($name)){$arr['name'] = array('LIKE',"%".$name."%");$this->assign("name", $_GET['name']);}
        }
        $arr['is_del']=array('eq',0);
        $count = $label->where($arr)->count();    //计算总数 
        $p = new Page($count, 15);
        $label_list = $label->where($arr)->limit($p->firstRow . ',' . $p->listRows)->select();//->order('cms_admin.admin_id DESC')
        $page = $p->show(); 
        $this->assign("page", $page);   
    
    	$this->assign('label_list', $label_list);  
    	 
        $this->display(); 
    }
    
    public function label_add(){//添加标签
    	if($_POST){
    		//$_POST['style']='color:'.$_POST['style'];
    		$label=D("UserLabel"); 
    		if ($vo = $label->create()) { 
	            $list = $label->add();  
	            if($list==true){
		        	$this->success('数据保存成功!',"__APP__/Label/label_list/");
		        }else{
		        	$this->error("数据写入错误!","__APP__/Label/label_list/");
		        }
	        } else { 
	        	$this->error($label->getError(),"__APP__/Label/label_list/");
	        }
	        exit;
    	} 
    	$this->display();
    }
    
    public function label_edit(){//标签编辑
    	if($_POST){
    		$label=D("User_label"); 
    		if ($vo = $label->create()) { 
	            $list = $label->save();  
	            if($list==true){
		        	$this->success('数据保存成功！',"__APP__/Label/label_list/");
		        }else{
		        	$this->error("数据写入错误!","__APP__/Label/label_list/");
		        }
	        }else {
	            $this->error($label->getError(),"__APP__/Label/label_list/");
	        }
	        exit;
    	}
    	  
    	$label=M("User_label "); 
    	$city_id=$_GET["_URL_"][2];   
    	$condition['id'] = $city_id; //使用查询条件 
        $infoAll = $label->where($condition)->find();  
        
        $this->assign('arr',$infoAll); 
    	$this->display("label_add");
    }
 
    public function label_del(){//删除管理员
    	if($_GET["_URL_"][2]){
    		$id=$_GET["_URL_"][2];
    	}else{
    	    $id=$_POST['id'];
    	}
    	if (!empty($id)) {
            if(is_array($id)){
            	$result=$this->add_recycle('user_label','id',$id); 
            }else{
                $result=$this->add_recycle('user_label','id',array($id)); 
            }
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
   
}