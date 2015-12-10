<?php
/** 
 * @date 2012.7.24
 * @author IT部门dapeng.chen 
 * @deprecated 橡树游戏平台，莱科版权所有
 * @abstract 新闻资讯
 */  
class ValidationAction extends CommonAction {
    public function validation_list(){//新闻资讯列表
    	$user_validation=M("user_validation");
    	import('@.ORG.Page');  
        $arr="";
        if(isset($_GET['findsub'])){ 
        	$item_name=trim($_GET['item_name']); 
        	if(!empty($item_name)){$arr['item_name'] = array('LIKE',"%".$item_name."%");}  
        }
        $arr['sub_id']=array('eq',$this->_get('sub_id'));
        $count = $user_validation->where($arr)->count();    //计算总数 
       
        $p = new Page($count, 20);
        $item_list = $user_validation->where($arr)->order('create_time DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $page = $p->show(); 
        $this->assign("page", $page);   
    	$this->assign('item_list', $item_list); 
    	$this->assign('sub_id', $_GET[sub_id]);
        $this->display(); 
    }
    
    public function validation_add(){//添加资讯
    	    $user_validation=D("user_validation");  		
    		if($_POST){
    		$_POST['create_time']=time();
    		if ($vo = $user_validation->create()) {
    			//$id=$this->_post('id');
    			$item_id = $user_validation->add(); 
	            
	            if ($item_id !== false) {
	            	$this->success('数据保存成功！',"__APP__/validation/validation_list/sub_id/".$_POST[sub_id]);exit;
	            } else {
	                $this->error("数据写入错误!","__APP__/validation/validation_list/".$_POST[sub_id]);
	            }
	        } else {
	            $this->error($user_validation->getError());
	        } 
	        exit;
    	}
    	
    	$sub_id=$this->_get('sub_id');
    	$subscribe=M('subscribe');
    	$res=$subscribe->where("id=$sub_id")->find();
    	$this->assign('res',$res);
    	//$arr=$user_validation->where("sub_id=$sub_id")->find();
    	//$this->assign('arr',$arr);
    	$this->display();
    }
    
    public function validation_edit(){//资讯编辑
    	$user_validation=D("user_validation");  
    	if($_POST){
    		$_POST['create_time']=time();
    		if ($vo = $user_validation->create()) {
    			$id=$this->_post('id');
    			$item_id = $user_validation->save(); 
	            
	            if ($item_id !== false) {
	            	$this->success('数据保存成功！',"__APP__/validation/validation_list/sub_id/".$_POST[sub_id]);exit;
	            } else {
	                $this->error("数据写入错误!","__APP__/validation/validation_list/sub_id/".$_POST[sub_id]);
	            }
	        } else {
	            $this->error($user_validation->getError());
	        } 
	        exit;
    	}
    	$id=$this->_get('id');
    	$arr=$user_validation->where("id=$id")->find();
    	$this->assign('arr',$arr);
    	
//    	$sub_id=$this->_get('sub_id');
//    	$subscribe=M('subscribe');
//    	$res=$subscribe->where("id=$arr")->find();
//    	$this->assign('res',$res);
    	
    	$this->display();
    }
 
    public function validation_del(){//删除新闻资讯
    	$id=$_GET['id'];
    	$user_validation=D("user_validation");  
    	$res=$user_validation->where("id=$id")->delete();
    	if (false !== $res) { 
               $this->success('删除成功！');
	        } else {
	           $this->error('删除出错！');
	     }
    	 
        exit;
    }
    
    public function action(){
		$action = $_GET['act'];
		$picname = $_FILES['mypic']['name'];
		$picsize = $_FILES['mypic']['size'];
		if ($picname != "") {
			if ($picsize > C('ITEM_UPLOAD_SIZE')*1024*1024) {
				echo '图片大小不能超过2M';
				exit;
			}
			$type = strstr($picname, '.');
			if ($type != ".gif" && $type != ".jpg" && $type != ".png") {
				echo '图片格式不对！';
				exit;
			}
			$rand = rand(100, 999);
			$dirdate=date("Ymd");
			$dir=$_SERVER["DOCUMENT_ROOT"].C('ITEM_UPLOAD_DIR').$dirdate;
			if(!file_exists($dir)) mkdir($dir);
			$pics = date("YmdHis") . $rand . $type;
			//上传路径
			$pic_path = $dir."/". $pics;
			move_uploaded_file($_FILES['mypic']['tmp_name'], $pic_path);
		}
		$size = round($picsize/1024,2);
		$arr = array(
				'name'=>$picname,
				'pic'=>$dirdate."/".$pics,
				'size'=>$size
		);
		echo json_encode($arr);
	}
   
}