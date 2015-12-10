<?php
/** 
 * @date 2012.7.25
 * @author IT部门dapeng.chen 
 * @deprecated 橡树游戏平台，莱科版权所有
 * @abstract 管理员
 */  
class AdminuserAction extends CommonAction {
    public function adminuser_list(){//管理员列表
    	$admin=M("admin");
    	import('@.ORG.Page');  
        $arr="";
        if($_GET['findsub']){ 
        	$account=trim($_GET['account']); 
        	if(!empty($account)){$arr['account'] = array('LIKE',"%".$account."%");$this->assign("account", $_GET['account']);}  
        	if(!empty($_GET['phone'])){$arr['phone'] = array('LIKE',"%".trim($_GET['phone'])."%");$this->assign("phone", $_GET['phone']);}
        	if(!empty($_GET['admin_name'])){$arr['admin_name'] = array('LIKE',"%".trim($_GET['admin_name'])."%");$this->assign("admin_name", $_GET['admin_name']);}
        	if($_GET['status']!=3){$arr['status'] = array('eq',$_GET['status']);$this->assign("status", $_GET['status']);}            else{
        		$this->assign("status",3);
        	} 
        	$city_id=intval($this->_get('city_id'));
        	if($city_id>0){$arr['city_id'] = array('eq',$city_id);$this->assign("city_id", $city_id);}
        }else{
        	$this->assign("status",3);
        }
        $arr['is_del']=array('eq','0');
        $arr['admin_id']=array('neq',1);
        $count = $admin->where($arr)->count();    //计算总数 
        $p = new Page($count, 15);
        $admin_list = $admin->where($arr)->limit($p->firstRow . ',' . $p->listRows)->select();//->order('cms_admin.admin_id DESC')
        $page = $p->show(); 
        $this->assign("page", $page);   
    
    	$this->assign('admin_list', $admin_list);  
    	$city=M('Region');
    	$city_where['status']=array('eq',1);
    	$city_where['pid']=array('eq',0);
    	$city_list=$city->where($city_where)->order('sort ASC')->select();
    	foreach ($city_list as $list){
    		$city_arr[$list[id]]=$list['name'];
    	}
    	$this->assign('city_arr', $city_arr); 
    	$this->assign('city_list', $city_list); 
        $this->display("Manage:adminuser_list"); 
    }
    
    public function adminuser_add(){//添加管理员
    	if($_POST){  
    		$_POST['admin_type']="admin";
    		$_POST['password']=md5($_POST['password']);
    		$_POST['temp_style']='green';
    		$adminup=D("admin"); 
    		$adminov=false;
    		if ($vo = $adminup->create()) { 
	            $list = $adminup->add();  
	            if ($list !== false) { 
	            	$adminov=true; 
	            } else { 
	                $adminov=false;
	            }
	        } else { 
	            $adminov=false;
	        }
	        
	        if($adminov==true){
	        	$this->success('数据保存成功！',"__APP__/Adminuser/adminuser_list/");
	        }else{
	        	$this->error("数据写入错误!","__APP__/Adminuser/adminuser_list/");
	        }
	        exit;
    	}
    	$city=M('Region');
    	$city_where['status']=array('eq',1);
    	$city_where['pid']=array('eq',0);
    	$city_list=$city->where($city_where)->order('sort ASC')->select();
    	$this->assign('city_list', $city_list); 
    	$this->assign('arr',array('status'=>1));
    	$this->display("Manage:adminuser_add");
    }
    
    public function adminuser_edit(){//管理员编辑
    	if($_POST){
    		$adminup=D("admin");
    		$_POST['admin_type']="admin";
    		if(empty($_POST['password'])){
    			$arrpassword=$adminup->where("admin_id=$_POST[admin_id]")->field('password')->find();
    			$_POST['password']=$arrpassword['password'];
    		}else{
    			$_POST['password']=md5($_POST['password']);
    		}
    		
    		if ($vo = $adminup->create()) { 
	            $list = $adminup->save();  
	        }  
	        
	       
	        if($list !== false){
	        	$this->success('数据更新成功！',"__APP__/Adminuser/adminuser_list/");
	        }else {
	            $this->error("没有更新任何数据!","__APP__/Adminuser/adminuser_list/");
	        }
	        exit;
    	}
    	  
    	$admin=M("admin");
    	$admin_id=$_GET["_URL_"][2];   
    	$condition['cms_admin.admin_id'] = $admin_id; //使用查询条件 
        $infoAll = $admin->where($condition)->find();  
        if($infoAll['status']==0){$infoAll['status']=3;}
        $this->assign('arr',$infoAll);
         
    	$this->assign('acc',"readonly"); 
    	
    	$city=M('Region');
    	$city_where['status']=array('eq',1);
    	$city_where['pid']=array('eq',0);
    	$city_list=$city->where($city_where)->order('sort ASC')->select();
    	
    	
    	$this->assign('jstype', 'edit'); 
    	$this->assign('city_list', $city_list); 
    	$this->display("Manage:adminuser_add");
    }
 
    public function adminuser_del(){//删除管理员
    	if($_GET["_URL_"][2]){
    		$admin_id=$_GET["_URL_"][2];
    	}else{
    	    $admin_id=$_POST['admin_id'];
    	}
    	if (!empty($admin_id)) {
            //$admin = M("admin");
            //$admininfo = M("admininfo");
            if(is_array($admin_id)){
//            	$admin_id=implode(",",$admin_id);
//            	$result = $admin->where("admin_id in($admin_id)")->delete();  
//            	$result2 = $admininfo->where("admin_id in($admin_id)")->delete();   
            	$result=$this->add_recycle('admin','admin_id',$admin_id); 
            }else{
//            	$result = $admin->delete($admin_id);  
//            	$result2 = $admininfo->delete($admin_id);
                $result=$this->add_recycle('admin','admin_id',array($admin_id)); 
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