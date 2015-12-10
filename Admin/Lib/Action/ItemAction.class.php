<?php
/** 
 * @date 2012.7.24
 * @author IT部门dapeng.chen 
 * @deprecated 橡树游戏平台，莱科版权所有
 * @abstract 新闻资讯
 */  
class ItemAction extends CommonAction {
    public function item_list(){//新闻资讯列表
    	$item=M("item");
    	import('@.ORG.Page');  
        $arr="";
        if(isset($_GET['findsub'])){ 
        	$item_name=trim($_GET['item_name']); 
        	if(!empty($item_name)){$arr['item_name'] = array('LIKE',"%".$item_name."%");}  
//        	if($_GET['type_id']!=0){$arr['cms_news.type_id'] = array('eq',$_GET['type_id']);$this->assign('type_id',$_GET['type_id']);}  
//
//        	if(!empty($_GET['create_time']) AND !empty($_GET['update_time'])){
//        		$arr['cms_news.create_time'] = array(array('egt',strtotime($_GET['create_time'])),array('lt',strtotime($_GET['update_time'])+24*60*60),'AND');$this->assign("update_time", $_GET['update_time']);$this->assign("create_time", $_GET['create_time']);
//        	}elseif(!empty($_GET['create_time']) AND empty($_GET['update_time'])){
//        		$arr['cms_news.create_time'] = array('egt',strtotime($_GET['create_time']));$this->assign("create_time", $_GET['create_time']);
//        	}elseif(!empty($_GET['update_time']) AND empty($_GET['create_time'])){
//        		$arr['cms_news.create_time'] = array('lt',strtotime($_GET['update_time'])+24*60*60);$this->assign("update_time", $_GET['update_time']);
//        	}
        }
        //$arr['is_del']=array('eq',0);
        $count = $item->where($arr)->count();    //计算总数 
       
        $p = new Page($count, 20);
        $item_list = $item->where($arr)->order('create_time DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        //echo $item->getLastSql();
        $page = $p->show(); 
        $this->assign("page", $page);   
    	$this->assign('item_list', $item_list);  
    	
    	/**新闻分类**/
    	$newstype=M("newstype");
    	$newstypeinfo = $newstype->where("is_del='0'")->order('sort ASC')->select();  
    	$this->assign('newstypearr',$newstypeinfo);
        $arr_type='';
    	foreach ($newstypeinfo as $res){
    		$arr_type[$res['type_id']]=$res['type_name'];
    	}
    	$this->assign('arr_type', $arr_type); 
        $this->display(); 
    }
    
    public function select_item(){
    	$item=M("item");
    	import('@.ORG.Page');  
        $arr="";
        $count = $item->where($arr)->count();    //计算总数 
       
        $p = new Page($count, 20);
        $item_list = $item->where($arr)->order('create_time DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $page = $p->show(); 
        $this->assign("page", $page);   
    	$this->assign('item_list', $item_list);  
    	$this->display();
    }
    
    public function item_add(){//添加资讯
    	    $item=D("item");

    		if($_POST){
                /*echo '<pre>';
                print_r($_POST);
                die();*/
                $_POST['create_time']=time();
                $_POST['content']= str_replace('src="/Uploads/Attached/', 'src="http://'.$_SERVER[SERVER_NAME].'/Uploads/Attached/', $_POST['content']);
                //$_POST['color']=implode(',',array_filter(array_unique($_POST['color'])));
                //$_POST['style']=implode(',',$_POST[style]);
                $_POST['titanium_id']=implode(',',$_POST[titanium_id]);
                $_POST['item_type'] = implode(',',$_POST['item_type']);
                $_POST['shop_type'] = implode(',',$_POST['shop_type']);
                //echo '<pre>';print_r($_POST);
    		//print_r($_POST);exit;
    		if ($vo = $item->create()) {
               /*var_dump($vo);
                die();*/
	            $item_id = $item->add();
                //echo $item->getLastSql();die();
	            if ($item_id !== false) {
	            	//添加关联商品
	            	$gruop_item_id=array_filter(array_unique($_POST['gruop_item_id']));
	            	$item_brother=M('item_brother');
	            	for ($ik=0;$ik<count($gruop_item_id);$ik++){
	            		$data_gruop[] = array(
							'item_id' => $item_id,
							'brother_item_id' =>$gruop_item_id[$ik],
						);
	            	}
	            	if(!empty($data_gruop)){
	            		$item_brother->addAll($data_gruop);
	            	}
	            	
	            	//添加商品图片
					import('ORG.Util.Image.ThinkImage');
					$images_thumb=$_POST['item_pic'];
					$arraythumb=C('ITEM_THUMB');
					$i = 0;
					//print_r($images_thumb);exit;
					foreach ($images_thumb as $key=>$val){
						$filename=substr($val, strrpos($val, '/')+1);
						$filenamearr=explode('.', $filename);
						if($val=="") continue;
						foreach ($arraythumb as $key1=>$val1){
							//$filenamethumb=substr($val, 0,strrpos($val, '/')+1).$filenamearr[0].'_'.$val1[0].'X'.$val1[1].'.'.$filenamearr[1];
							$filenamethumb=$_SERVER['DOCUMENT_ROOT'].$val.'_'.$val1[0].'X'.$val1[1].'.jpg';
							$img = new ThinkImage(THINKIMAGE_GD, $_SERVER['DOCUMENT_ROOT'].$val);
							$img->thumb($val1[0], $val1[1],THINKIMAGE_THUMB_SCALING)->save($filenamethumb);
						}	
						$data_image[] = array(
							'item_id' => $item_id,
							'img_path' =>"$val",
							'sort' => ++$i
						);
					}
					if(!empty($data_image)){
						$item_images=M('item_images');
						$item_images->addAll($data_image);
						$save_data['icon']=$data_image[0]['img_path'];
						$item->where("item_id=$item_id")->save($save_data);
					}
	            	$this->success('数据保存成功！',"__APP__/Item/item_list/");exit;
	            } else {
	                $this->error("数据写入错误!","__APP__/Item/item_list/");
	            }
	        } else {
	            $this->error($item->getError());
	        } 
	        exit;
    	}
    	
    	
    	/**新闻分类**/
    	$newstype=M("newstype");
    	$newstypeinfo = $newstype->where("is_del='0'")->order('sort ASC')->select();
    	$this->assign('newstypearr',$newstypeinfo);
    	 
    	
    	$arr[0]['create_time']=time();
    	$this->assign('arr',$arr);
    	C('TOKEN_ON',false); 
    	$this->display();
    }
    
    public function item_edit(){//资讯编辑
    	if($_POST){
    		$item=D("item"); 
    		$item_id=$_POST['item_id']; 
    	   
    		$_POST['content']= str_replace('src="/Uploads/Attached/', 'src="http://'.$_SERVER[SERVER_NAME].'/Uploads/Attached/', $_POST['content']);
    		//$_POST['color']=implode(',',array_filter(array_unique($_POST['color'])));
    		//print_r($_POST[style]);exit;
    		/*$_POST['style']=implode(',',$_POST[style]);
    		$_POST['titanium_id']=implode(',',$_POST[titanium_id]);*/
            $_POST['item_type'] = implode(',',$_POST['item_type']);
            $_POST['shop_type'] = implode(',',$_POST['shop_type']);

            //echo '<pre>';print_r($_POST);
            //die();
    		if ($vo = $item->create()) {
                /*print_r($vo);*/
	            $list = $item->save();
                /*echo $item->getLastSql();
                die();*/
	            if ($list !== false) {
	            	//添加关联商品
	            	$gruop_item_id=array_filter(array_unique($_POST['gruop_item_id']));
	            	$str_group_id=implode(',',$gruop_item_id);
	            	$item_brother=M('item_brother');
	            	$group_del['item_id']=array('eq',$item_id);
	            	$resg=$item_brother->where($group_del)->delete();
	            	for ($ik=0;$ik<count($gruop_item_id);$ik++){
	            		$data_gruop[] = array(
							'item_id' => $item_id,
							'brother_item_id' =>$gruop_item_id[$ik],
						);
	            	}
	            	if(!empty($data_gruop)){
	            		$item_brother->addAll($data_gruop);
	            	}
	            	
	            	$item_images=M('item_images');
	            	$item_images->where(array('item_id'=>array('eq',$item_id)))->delete();
	            	//添加商品图片
					import('ORG.Util.Image.ThinkImage');
					$images_thumb=$_POST['item_pic'];
					$arraythumb=C('ITEM_THUMB');
					$i = 0;
					//print_r($images_thumb);exit;
					foreach ($images_thumb as $key=>$val){
						$filename=substr($val, strrpos($val, '/')+1);
						$filenamearr=explode('.', $filename);
						if($val=="") continue;
						foreach ($arraythumb as $key1=>$val1){
							$filenamethumb=$_SERVER['DOCUMENT_ROOT'].$val.'_'.$val1[0].'X'.$val1[1].'.jpg';
							$img = new ThinkImage(THINKIMAGE_GD, $_SERVER['DOCUMENT_ROOT'].$val);
							$img->thumb($val1[0], $val1[1],THINKIMAGE_THUMB_SCALING)->save($filenamethumb);
						}	
						$data_image[] = array(
							'item_id' => $item_id,
							'img_path' =>"$val",
							'sort' => ++$i
						);
					}
					if(!empty($data_image)){
						$item_images->addAll($data_image);
						$save_data['icon']=$data_image[0]['img_path'];
						$item->where("item_id=$item_id")->save($save_data);
					}
	                $this->success('数据保存成功！',"__APP__/Item/item_list/");exit;
	               
	            } else {
	                $this->error("没有更新任何数据!","__APP__/Item/item_list/");
	            }
	        } else {
	            $this->error($item->getError());
	        } 
	        exit;
    	}
    	 
    	/**新闻分类**/
    	$newstype=M("newstype");
    	$newstypeinfo = $newstype->where("is_del='0'")->order('sort ASC')->select();
    	$this->assign('newstypearr',$newstypeinfo);
//echo '<pre>';print_r($newstypeinfo);
    	$item=M("item");
    	$id=$_GET["_URL_"][2];   
    	$condition['item_id'] = $id; //使用查询条件 
        $infoAll = $item->where($condition)->find();  
        //$infoAll['style']=explode(',',$infoAll['style']);
        //$infoAll['titanium_id']=explode(',',$infoAll['titanium_id']);
        
        $this->assign('arr',$infoAll);
       // $color_arr=explode(',',$infoAll['color']);
        //print_r($color_arr);exit;
        //$this->assign('color_arr',$color_arr);
        
        $item_images=M('item_images');
        $list_image=$item_images->where("item_id=".$infoAll[item_id])->order('img_id ASC')->select();
        
        $this->assign('list_image',$list_image);
        
        $item_brother=M('item_brother');
        $br_where['b.item_id']=array('eq',$infoAll[item_id]);
        $brother_list=$item_brother->join('as b LEFT JOIN cms_item as i ON i.item_id=b.brother_item_id')->field('i.item_name,i.color,i.item_id')->where($br_where)->select();
        $this->assign('brother_list',$brother_list);
    	$this->display();
    }
 
    public function item_del(){//删除新闻资讯
    	$item_id=$_GET['item_id'];
    	if (!empty($item_id)) {
            $item = M("item");
            $where['item_id']=array('eq',$item_id);
            $res=$item->where($where)->delete();
            
            if (false !== $res) { 
               $this->success('删除成功！');
	        } else {
	           $this->error('删除出错！');
	        }
        }else {
            $this->error('删除项不存在！');
        }  
        exit;
    }
    
    public function action(){
		$action = $_GET['act'];
		$picname = $_FILES['mypic']['name'];
		$picsize = $_FILES['mypic']['size'];
		if ($picname != "") {
			if ($picsize > C('ITEM_UPLOAD_SIZE')*1024*1024) {
				echo '图片大小不能超过4M';
				exit;
			}
			$type = strstr($picname, '.');
            $type = strtolower($type);
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
 ?>
