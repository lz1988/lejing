<?php
/** 
 * @date 2012.7.24
 * @author IT部门dapeng.chen 
 * @deprecated 橡树游戏平台，莱科版权所有
 * @abstract 新闻栏目
 */  
class MsAction extends CommonAction {
    public function ms_list(){//新闻栏目列表
    	$message=M("message");
    	import('@.ORG.Page');  
        $arr="";
        if($_GET['findsub']){ 
        	$type_name=trim($_GET['name']);
        	 
        	if(!empty($type_name)){$arr['name'] = array('LIKE',"%".$type_name."%");$this->assign("name", $_GET['name']);}  
        } 
      
        $count = $message->where($arr)->count();    //计算总数 
        $p = new Page($count, 15);
        $menu_list = $message->where($arr)->order('id DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $page = $p->show(); 
        $this->assign("page", $page);   
    	$this->assign('menu_list', $menu_list);
        $this->display(); 
    }
    
    public function ms_add(){//添加新闻栏目
    	if($_POST){ 
    		if($this->isPost()){
    			if(!empty($_FILES['images']['name'] )){
					import("ORG.Net.UploadFile");
					$upload = new UploadFile();// 实例化上传类
					$upload->maxSize  = C('UPLOAD_IMAGE_MAX_SIZE');// 设置附件上传大小
					$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
					$upload->saveRule='time'; // 上传文件命名规则
					$upload->savePath =C('UPLOAD_SETING_PATH');// 设置附件上传目录
					$upload->autoSub=true;//是否自动为上传文件生成子目录		
					$upload->subType='date';//子目录创建方式，目录名以日期的'Ymd'格式创建
					if(!$upload->upload()) {// 上传错误提示错误信息
						$this->error($upload->getErrorMsg());
					}else{// 上传成功 获取上传文件信息
						$uploadFileinfo=$upload->getUploadFileInfo();
						$add_data['logo']=__ROOT__.'/'.$uploadFileinfo[0]['savepath'].$uploadFileinfo[0]['savename'];
					}
				}
				$add_data['question']=$this->_post('question');
				$add_data['content']=$this->_post('content');
				$add_data['type']=$this->_post('type');
				$add_data['url']=$this->_post('url');
				$add_data['title']=$this->_post('title');
				
				$message=M("message"); 
	            $list = $message->add($add_data); 
	            if ($list !== false) { 
	                $this->success('数据保存成功！',"__APP__/Ms/ms_list/");
	            } else {
	                $this->error("数据写入错误!");
	            }
	        exit;
    	}
    	}
    	$this->display();
    }
    
    public function ms_edit(){//新闻栏目编辑
    	$message=M("message"); 
    	 if($this->isPost()){
    			if(!empty($_FILES['images']['name'] )){
					import("ORG.Net.UploadFile");
					$upload = new UploadFile();// 实例化上传类
					$upload->maxSize  = C('UPLOAD_IMAGE_MAX_SIZE');// 设置附件上传大小
					$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
					$upload->saveRule='time'; // 上传文件命名规则
					$upload->savePath =C('UPLOAD_SETING_PATH');// 设置附件上传目录
					$upload->autoSub=true;//是否自动为上传文件生成子目录		
					$upload->subType='date';//子目录创建方式，目录名以日期的'Ymd'格式创建
					if(!$upload->upload()) {// 上传错误提示错误信息
						$this->error($upload->getErrorMsg());
					}else{// 上传成功 获取上传文件信息
						$uploadFileinfo=$upload->getUploadFileInfo();
						$add_data['logo']=__ROOT__.'/'.$uploadFileinfo[0]['savepath'].$uploadFileinfo[0]['savename'];
					}
				}
				$add_data['question']=$this->_post('question');
				$add_data['content']=$this->_post('content');
				$add_data['type']=$this->_post('type');
				$add_data['url']=$this->_post('url');
				$add_data['title']=$this->_post('title');
				$id=$this->_post('id');
				$list=$message->where("id=$id")->save($add_data);
				if ($list !== false) { 
	                $this->success('数据保存成功！',"__APP__/Ms/ms_list/");
	            } else {
	                $this->error("数据写入错误!");
	            }
	            exit;
    	}
    	$id=$_GET['id'];   
    	$condition['id'] = $id; //使用查询条件 
        $infoAll = $message->where($condition)->find();  
        $this->assign('arr',$infoAll);
        $this->assign('typelist',$list);
           
    	$this->display("ms_add");
    }
 
    public function ms_del(){//删除新闻栏目
    	$message=M("message"); 
    	$id=$this->_get('id');
    	$result=$message->where("id=$id")->delete();
    	if (false !== $result) { 
            $this->success('删除成功！');
	    } else {
	        $this->error('删除出错！');
	    } 
        exit;
    }
}
