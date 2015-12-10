<?php
/** 
 * @date 2012.7.24
 * @author IT部门dapeng.chen 
 * @deprecated 橡树游戏平台，莱科版权所有
 * @abstract 支付方式
 */  
class PaywayAction extends CommonAction {
    public function payway_list(){//支付方式
    	$payway=M("payway");
    	import('@.ORG.Page');  
        $arr="";
        if($_GET['findsub']){ 
        	$payway_name=trim($_GET['payway_name']);
        	 
        	if(!empty($payway_name)){$arr['payway_name'] = array('LIKE',"%".$payway_name."%");$this->assign("payway_name", $_GET['payway_name']);} 
        	if($_GET['type_id']!=0){$arr['type_id'] = array('eq',$_GET['type_id']);$this->assign('type_id',$_GET['type_id']);} 
        } 
        $arr['is_del']=array('eq','0');
        $count = $payway->where($arr)->count();    //计算总数 
        $p = new Page($count, 15);
        $payway_list = $payway->where($arr)->order('sort DESC,payway_id DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $page = $p->show(); 
        $this->assign("page", $page);   
    	$this->assign('payway_list', $payway_list);  
    	
     
    	$con=C('PAYIMGURL'); 
        $this->assign("payimgurl",$con); 
    	
        $this->display("payway_list"); 
    }
    
    public function payway_add(){//添加支付方式
    	if($_POST){ 
    		$paywayup=D("payway"); 
    		if ($vo = $paywayup->create()) { 
	            $list = $paywayup->add(); 
	            if ($list !== false) { 
	                $this->success('数据保存成功！',"__APP__/Payway/payway_list/");
	            } else {
	                $this->error("数据写入错误!","__APP__/Payway/payway_list/");
	            }
	        } else {
	            $this->error($paywayup->getError());
	        } 
	        exit;
    	}
    	$this->assign('updivishide',"style='display:none;'");
    	$this->assign('updivisshow',"style=''");
    	$con=C('PAYIMGURL'); 
        $this->assign("payimgurl",$con);
    	
        /**支付类型**/
    	$paytype=M("paytype");
    	$paytypeinfo = $paytype->select();  
    	$this->assign('paytypearr',$paytypeinfo);
        $this->assign("edit",'edit');
    	$this->assign('typeName',"添加支付方式");
    	$this->assign('typeUrl',"__APP__/Payway/payway_add/");
    	$this->display("payway_add");
    }
    
    public function payway_edit(){//支付方式编辑
    	if($_POST){
    		$paywayup=M("payway"); 
    		if ($vo = $paywayup->create()) { 
	            $list = $paywayup->save(); 
	            if ($list !== false) { 
	                $this->success('数据更新成功！',"__APP__/Payway/payway_list/");
	            } else {
	                $this->error("没有更新任何数据!","__APP__/Payway/payway_list/");
	            }
	        } else {
	            $this->error($paywayup->getError());
	        } 
	        exit;
    	} 
    	$con=C('PAYIMGURL'); 
        $this->assign("payimgurl",$con);
        
    	$payway=M("payway");
    	$payway_id=$_GET["_URL_"][2];   
    	$condition['payway_id'] = $payway_id; //使用查询条件 
        $infoAll = $payway->where($condition)->select();  
        $infoAll[0]['logo2']=$con.$infoAll[0]['logo'];
        $this->assign('arr',$infoAll);
          
        if($infoAll[0][logo]==""){
    		$this->assign('updivisshow',""); 
    		$this->assign('updivishide',"style='display:none;'"); 
    	}else{
    		$this->assign('updivishide',""); 
    		$this->assign('updivisshow',"style='display:none;'"); 
    	}
        
    	/**支付类型**/
    	$paytype=M("paytype");
    	$paytypeinfo = $paytype->select();  
    	$this->assign('paytypearr',$paytypeinfo);
    	  
    	$this->assign('typeName',"支付方式编辑");
    	$this->assign('typeUrl',"__APP__/Payway/payway_edit/");
    	$this->display("payway_add");
    }
 
    public function payway_del(){//删除支付方式
    	if($_GET["_URL_"][2]){
    		$payway_id=$_GET["_URL_"][2];
    	}else{
    	    $payway_id=$_POST['payway_id'];
    	}
    	if (!empty($payway_id)) {
            $payorder = M("payorder");
            if(is_array($payway_id)){  
            	$wheretypeId=implode(",",$payway_id);
            	$gamewhere['payway_id']=array('in',$wheretypeId);
             	$game_info=$payorder->field('id')->where($gamewhere)->limit(1)->find(); 
            	if(empty($game_info)){
            	    $result=$this->add_recycle('payway','payway_id',$payway_id);
            	}else{
            		$this->error('您所选的支付渠道下有订单信息，不能删除！');
            	}
            }else{
            	$gamewhere['payway_id']=array('eq',$payway_id);
             	$game_info=$payorder->field('id')->where($gamewhere)->limit(1)->find(); 
            	if(empty($game_info)){
            	    $result=$this->add_recycle('payway','payway_id',array($payway_id)); 
            	}else{
            		$this->error('您所选的支付渠道下有订单信息，不能删除！');
            	}
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
    
    public function payway_upall(){//支付方式更新
    	if($_POST){
    		$paywayup=D("payway");
    		$payway_id=$_POST['sortid'];
    		$sort=$_POST['sort']; 
    		for($i=0;$i<count($payway_id);$i++){
    			$arr['sort']=$sort[$i];
    			$list =$paywayup->where("payway_id=$payway_id[$i]")->save($arr); // 根据条件保存修改的数据
    		}
    		if($list !== false){
    			$this->success('数据更新成功！');
    			exit;
    		}else {
	            $this->error($paywayup->getError());exit;
	        }  
    	}else{
    		$this->error('操作错误！');exit;
    	}
    }
    
    
}