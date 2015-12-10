<?php

class CommonAction extends Action {
	protected $menu   =  null; 
	protected $curr_action   =  null; 
    function _initialize(){
    	  $con=C('MODULES_MENU_ALL');
    	  $this->menu =$con;  
    	  $action_name = ACTION_NAME;
    	  $module_name = MODULE_NAME; 
    	  
    	  if($action_name != 'verify' && $action_name != 'login' && empty($_SESSION['USER_ID'])){
    	  	    // $this->login();    
    	  	    echo "<script>if (top.location !== self.location){top.location='".'http://'.$_SERVER[SERVER_NAME].':'.$_SERVER[SERVER_PORT].$_SERVER[SCRIPT_NAME].'/Common/login'."'; }else{document.location='".'http://'.$_SERVER[SERVER_NAME].':'.$_SERVER[SERVER_PORT].$_SERVER[SCRIPT_NAME].'/Common/login'."';}</script>"; 
    	  	   //$this->redirect('Index/login');
    	  	   exit;
    	  }elseif($module_name != 'Common'){
    	  	   if($_SESSION['USER_NAME']=='admin'){
    	  	   	   //所有权限
    	  	   	   /*超级管理员生成操作日志*/ 
    	  	   	   $this->log_info();
    	  	   }else{ 
    	  	   	   foreach ($_SESSION['ROLE'] as $arr =>$key){
    	  	   	   	   $role_idarr[]= $key['role_id'];
    	  	   	   }
    	  	   	   $role_idarr=implode(",",$role_idarr);
    	  	   	   $menu2_where['status']=array('eq',1);
    	  	   	   $menu2_where['level']=array('eq',2);
    	  	   	   $menu2_where['func_name']=array('eq',$module_name);
    	  	   	   $admin_menu=M('admin_menu');
    	  	   	   $m2_res=$admin_menu->field('menu_id')->where($menu2_where)->find();
    	  	   	  
    	  	   	   $menu3_where['status']=array('eq',1);
    	  	   	   $menu3_where['level']=array('eq',3);
    	  	   	   $menu3_where['pid']=array('eq',$m2_res['menu_id']);
    	  	   	   $menu3_where['func_name']=array('eq',$action_name);
    	  	   	   $m3_res=$admin_menu->field('menu_id')->where($menu3_where)->find();
    	  	   	   
    	  	   	    
    	  	   	   $rolepriv = M("rolepriv");
    	  	   	   $privarr['role_id']=array('IN',"$role_idarr");  
    	  	   	   $privarr['menu_id'] =array('eq',$m3_res['menu_id']); 
    	  	   	   
    	  	   	   $rolepriv_list = $rolepriv->where($privarr)->select();
    	  	   	   if(empty($rolepriv_list) && $module_name != 'Index'){
    	  	   	   	   $this->error("您没有该模块的权限!");
    	  	   	   }else{
    	  	   	   	     /*是否有该模块下的其它权限*/
    	  	   	   	     $menu4_where['status']=array('eq',1);
	    	  	   	     $menu4_where['level']=array('eq',3);
	    	  	   	     $menu4_where['pid']=array('eq',$m2_res['menu_id']);
	    	  	   	     $m4_arr=$admin_menu->field('menu_id,func_name')->where($menu4_where)->select();
	    	  	   	     $menuid_arr='';
	    	  	   	     foreach ($m4_arr as $m4){ 
    	  	   	         	$menuid_arr[]=$m4['menu_id'];
    	  	   	         }
    	  	   	   	     $menuid_str=implode(",",$menuid_arr);
    	  	   	   	     
	    	  	   	     $privarr['menu_id'] =array('IN',$menuid_str); 
	    	  	   	     $pri_arr=$rolepriv->field('menu_id')->where($privarr)->select(); 
	    	  	   	      
	    	  	   	     foreach ($m4_arr as $arr){ 
    	  	   	         	
    	  	   	         	$this->assign("$arr[func_name]","style='display:none;'");
    	  	   	         	foreach ($pri_arr as $plist){
    	  	   	         		if($plist['menu_id']==$arr['menu_id']){
    	  	   	         			$this->assign("$arr[func_name]","style='display:inline-block;'");
    	  	   	         		}
    	  	   	         	}
    	  	   	         }
    	  	   	         
//    	  	   	   	     $menu_actionName=C('MOD_NAME_ACTION');
//    	  	   	   	     $privarrm['role_id']=array('IN',"$role_idarr"); 
//    	  	   	         $privarrm['module']=array('eq',"$module_name");
//    	  	   	         $roleprivm_list = $rolepriv->field('methods')->where($privarrm)->select();
//    	  	   	         foreach ($menu_actionName as $act=>$key){ 
//    	  	   	         	$this->assign("$act","style='display:none;'");
//    	  	   	         }
//    	  	   	          
//    	  	   	         foreach ($roleprivm_list as $mod){
//    	  	   	         	$modarr=explode('_',$mod['methods']);
//    	  	   	         	foreach ($menu_actionName as $act2=>$act_id){
//    	  	   	         		if($modarr[1]==$act2){
//    	  	   	         			$this->assign("$act2","style='display:block'");
//    	  	   	         		} 
//    	  	   	            }
//    	  	   	         }
    	  	   	         
    	  	   	         $not_menu=C('NOT_DEL'); //不能删的项
    	  	   	         foreach ($not_menu as $menu=>$val){
    	  	   	         	if($menu==$module_name){//如果当前模块有不可删除项
    	  	   	         		if(is_array($val)){
    	  	   	         			$this->assign('notdel',$val);
    	  	   	         		}elseif($val=='ALL'){
    	  	   	         			$this->assign('notdel',"ALL");
    	  	   	         		} 
    	  	   	         	} 
    	  	   	         }
    	  	   	          
    	  	   	   	     /*其它管理员生成操作日志*/
    	  	   	   	     $this->log_info();
    	  	   	   }
    	  	   }
    	  	   ###模块－当前方法
    	  	   $this->cur_menu_name();
    	  } 
    	 
    }  
    
    /***
     *模块和方法 
     */
    function module_action(){
    	$action_name = ACTION_NAME;
    	$module_name = MODULE_NAME;
        $admin_menu=M('admin_menu');
       
        
    	$menu2_where['status']=array('eq',1);
    	$menu2_where['level']=array('eq',2);
    	$menu2_where['func_name']=array('eq',$module_name);
    	$m2_res=$admin_menu->field('menu_id,menu_title,pid,url')->where($menu2_where)->find();
    	
    	$menu1_where['menu_id']=array('eq',$m2_res['pid']);
    	$m1_res=$admin_menu->field('menu_id,menu_title,url')->where($menu1_where)->find();
    	  	   
	    $menu3_where['status']=array('eq',1);
	    $menu3_where['level']=array('eq',3);
	   // $menu3_where['pid']=array('eq',$m2_res['menu_id']);
	    $menu3_where['func_name']=array('eq',$action_name);
	    $m3_res=$admin_menu->field('menu_title,pid,url')->where($menu3_where)->find();
	    return array(1=>$m1_res,2=>$m2_res,3=>$m3_res);
    }
    
    ###模块－当前方法
    function cur_menu_name(){
	    $res=$this->module_action();
	    $this->assign('cur_menu','当前位置：'.$res[1]['menu_title'].' >> <a href="/admin.php/'.$res[2][url].'"> '.$res[2]['menu_title'].'</a>');
	    $this->assign('cur_title',$res[3]['menu_title']);
	    $this->assign('cur_url',$res[2]['url']);
    }
    
    function log_info(){
    	/*生成操作日志*/ 
    	$action_name = ACTION_NAME;
    	$module_name = MODULE_NAME;
    	$con_mod=C("MOD_NAME_ARR"); 
    	$metarr=explode("_",$action_name); 
    	$action=$metarr[1];
    	//if($metarr[1]=='add' OR $metarr[1]=='edit'){
    	$ma_res=$this->module_action();
    	$action=$ma_res[3]['menu_title'];
    	$this->curr_action=$action;
    	if($metarr[1]!='del'){
    		#非删除项
    	  	if($_POST){
    	  	   	$addarr="$action的信息为：【";
    	  	   	foreach ($_POST as $info=>$key){
    	  	   	   	if($info=='__hash__'){
    	  	   	   	   	  continue;
    	  	   	   	}
    	  	   	   	$addarr.=$info." =>".$key.",";
    	  	    }
    	  	    $addarr.="】";
    	  	    
    	  	   	$this->create_log($action,$ma_res[2]['menu_title'],$addarr); 
    	  	}
    	}elseif($metarr[1]=='del'){
    	  	$not_menu=C('NOT_DEL'); 
    	  	if($not_menu[$module_name]=="ALL" AND $_SESSION['USER_ID']!=1){
    	  	    $this->error("系统设定不可删除!");exit;
    	  	}
    	  	   	   	   
    	  	if($_GET["_URL_"][2]){
    	  		if ($_GET["_URL_"][2]==1 AND $module_name=='Adminuser') {$this->error("超级管理员不可删除!");exit;}
    	  	   	if (in_array($_GET["_URL_"][2], $not_menu[$module_name]) AND $_SESSION['USER_ID']!=1) {$this->error("您选种的信息不可删除!");exit;}
				$condel="删除了主键ID为：【".$_GET["_URL_"][2]."】信息";
    	  	    $this->create_log($action,$ma_res[2]['menu_title'],$condel);
    	  	}elseif($_POST){ 
    	  	   foreach ($_POST as $info=>$key){
    	  	   	  if($info=='__hash__' OR $info=='sortid' OR $info=='sort'){
    	  	   	   	  continue;
    	  	   	  }
    	  	   	  if(is_array($key)){
    	  	   	   	 $delarr="删除了键ID为：【 ";
    	  	   	   	 if(!empty($key)){
	    	  	   	   	foreach ($key as $id=>$idkey){
	    	  	   	   	     if ($idkey==1 AND $module_name=='Adminuser') {$this->error("超级管理员不可删除!");exit;}
	    	  	   	   	     
	    	  	   	   	   	  if (in_array($idkey, $not_menu[$module_name]) AND $_SESSION['USER_ID']!=1) {$this->error("您选择的信息不可删除!");exit;}
	    	  	   	   	   	       $delarr.=$idkey.",";
	    	  	   	   	}
    	  	   	   	 }
	    	  	   	 $delarr.="】的信息";
	    	  	   	 $this->create_log($action,$ma_res[2]['menu_title'],$delarr);
    	  	      }       	 
    	  	   } 
    	   }
        }
    }
    
    //生成后台操作日志
    public function create_log($action,$con_mod,$content){ 
    	$create['admin_id']     =$_SESSION['USER_ID'];
    	$create['action']       =$action;
    	$create['module']       =$con_mod;
    	$create['content']      =$content;
    	$create['ip']           =get_client_ip();
    	$create['action_time']  =time();
    	$logadmin=M("logadmin");  
	    $list = $logadmin->add($create);
    }
    
    function login(){
    	
    	if($_POST['login'] AND empty($_SESSION['USER_ID'])){
    		$account=trim($_POST['account']);
	    	$password=md5(trim($_POST['password']));
	    	$verify=md5(trim($_POST['verify']));
	    	
	    	if($_SESSION['verify']!=$verify){
	    		$this->error("验证码错误!");
	    	}
	    	
	    	$admin = M("admin");
	    	$checkarr['cms_admin.account']=array('eq',$account); 
	    	$checkarr['cms_admin.password']=array('eq',$password);  
	    	$check_list = $admin->where($checkarr)->field('cms_admin.admin_id,cms_admin.account,cms_admin.admin_type,cms_admin.status,cms_admin.admin_name,cms_admin.is_del,cms_admin.temp_style')->find();
	    	//print_r($check_list);exit;
	    	if(!empty($check_list)){
	    		if($check_list['status']==1){
	    			if($check_list['is_del']=='1'){
	    				$this->error("您的帐号已被删除!");exit;
	    			} 
	    			
	    			$config=M('config');
	    			$c_info=$config->field('allow_ip')->order('id ASC')->find();
	    			if(!empty($c_info['allow_ip'])){
	    				$allow_ip_arr=explode(',',$c_info['allow_ip']);
	    				$ip=get_client_ip();
	    				if (!in_array($ip,$allow_ip_arr)){
	    					$ip_info="您的IP:".$ip."不允许登陆!";
						    $this->error($ip_info);exit;
						}
	    			}
	    			
	    			$roleadmin = M("roleadmin");
	    			$admin_id=$check_list['admin_id']; 
	    			$rolearr['cms_roleadmin.admin_id']=array('eq',$admin_id);
	    			$rolearr['cms_role.is_del']  =array('eq','0');
	    			$role_list=$roleadmin->join('cms_roleadmin LEFT JOIN cms_role ON cms_roleadmin.role_id=cms_role.role_id')->field('cms_roleadmin.role_id')->where($rolearr)->select();
	    			$_SESSION['USER_ID']     =$check_list['admin_id'];
	    			$_SESSION['USER_NAME']     =$check_list['account'];
	    			$_SESSION['ADMIN_TYPE']  =$check_list['admin_type']; 
	    			$_SESSION['ROLE']        =$role_list; 
	    			if(empty($check_list['temp_style'])){$check_list['temp_style']='green';}
	    			$_SESSION['temp_style']        =$check_list['temp_style'];
	    			/*生成日志*/
	    			$this->create_log("login","登陆模块","login");
	    			
	    			$this->display("Index:index");
	    			//$this->redirect("__APP__/Index/index/",5,"登陆成功，页面跳转中……");
	    		}else{
	    			$this->error("您的帐号已被冻结!");
	    		}
	    	}else{
	    		$this->error("用户名或密码错误!");
	    	}
    	}
    	if(!isset($_SESSION['USER_ID']) OR empty($_SESSION['USER_ID'])) {
    		$config=M('config');
    	    $conf_res=$config->field('header_img')->order('id ASC')->find();
    	    $this->assign('header_img',$conf_res['header_img']);//登陆次数
		    $this->display('Index:login');
    	}else{
			$this->redirect('Index/index');
		}
    }
    
     public function verify()
    {
		$type	 =	 isset($_GET['type'])?$_GET['type']:'gif';
        import("@.ORG.Image");
        Image::buildImageVerify(4,1,$type);
        exit;
    }
    
    public function logout(){
    	unset($_SESSION['USER_ID']); 
    	unset($_SESSION['USER_NAME']); 
    	unset($_SESSION['ADMIN_TYPE']); 
    	unset($_SESSION['ROLE']); 
    	unset($_SESSION); 
        session_destroy();
        $config=M('config');
    	$conf_res=$config->field('header_img')->order('id ASC')->find();
    	$this->assign('header_img',$conf_res['header_img']);//登陆次数
        $this->display('Index:login');  
    }
    
    
    public function upload() {
        if (!empty($_FILES)) {
            //如果有文件上传 上传附件
            $this->_upload(); 
        }
    }
    
    // 文件上传
    protected function _upload() { 
    	$weijj=$_GET["_URL_"][2];//文件夹
    	$index_xm=$_GET["_URL_"][3];//首字母
        import("@.ORG.UploadFile");
        //导入上传类
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = 3292200;
        //设置上传文件类型
        $upload->allowExts = explode(',','jpg,gif,png,jpeg,txt,sql,swf');
        //设置附件上传目录
        $upload->savePath = "./Uploads/$weijj/";
        //设置需要生成缩略图，仅对图像文件有效
        if($index_xm=='ad'){
        	$upload->thumb = false;
        }else{
        	$upload->thumb = true;
        }
        
        // 设置引用图片类库包路径
        $upload->imageClassPath = '@.ORG.Image';
        //设置需要生成缩略图的文件后缀
        $upload->thumbPrefix = $index_xm."_";  //生产2张缩略图
        //设置缩略图最大宽度
        $upload->thumbMaxWidth = '4000,1000';
        //设置缩略图最大高度
        $upload->thumbMaxHeight = '4000,1000';
        //设置上传文件规则
        $upload->saveRule = uniqid;
        //删除原图
        
        if($index_xm=='ad'){
        	$upload->thumbRemoveOrigin = false;
        }else{
        	$upload->thumbRemoveOrigin = true;
        }
        if (!$upload->upload()) {
            //捕获上传异常
            $this->error($upload->getErrorMsg());
        } else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            import("@.ORG.Image");
            //给m_缩略图添加水印, Image::water('原文件名','水印图片地址')
            #Image::water($uploadList[0]['savepath'] . 'm_' . $uploadList[0]['savename'], '../Uploads/Images/logo2.png');
            $_POST['image'] = $uploadList[0]['savename'];
        } 
        //保存当前数据对象
        $data['image'] = $_POST['image']; 
         
        if ($data['image']) {
        	if($index_xm=='ad'){
        	    echo $data['image'];exit;
	        }else{
	        	echo $index_xm."_".$data['image'];exit;
	        }
            
        } else {
            echo 0;exit;//上传失败
        } 
    }
    
    // 修改密码
    public function modify_pwd() {
    	if($_POST){
    	$admin=M("admin");
        $pwd=trim($_POST['password']);
        $old_password=trim($_POST['old_password']);
        if(empty($old_password)){
        	$this->error("旧密码不能为空!");exit;
        }
        
    	$arr['password']=md5($pwd); 
    	$admin_id=$_SESSION['USER_ID'];
    
    	$parr=$admin->where("admin_id=$admin_id")->field('password')->find(); 
    	if($parr['password']!=md5($old_password)){
    		$this->error("旧密码不正确!");exit;
    	}
    	if(empty($pwd)){
    		$this->error("密码不能为空!");exit;
    	}
    	if(md5($pwd)===md5($old_password)){
    		$this->error("新密码不能与旧密码一样!");exit;
    	}
    	if ($vo = $admin->create()) {
	            $list = $admin->where("admin_id=$admin_id")->save($arr); 
	            if ($list !== false) { 
	                $this->success('密码更改成功！');
	            } else {
	                $this->error("没有更改成功!");
	            }
	        } else {
	            $this->error($admin->getError());
	        } 
	        exit;
    	}
    	$this->display('Manage:modify_pwd');
    }
    
    //检查会员用户名是否存在
    public function checkusername(){
    	$user=M("user");
    	$acc=$_GET['account']; //echo $acc;exit;
    	
    	$where['account']=array('eq',$acc);
    	$arr=$user->where($where)->field('user_id')->find(); 
    	if(!empty($arr['user_id'])){
    		echo $arr['user_id'];
    	}else{
    		echo 0;
    	}
    	exit; 
    }
    
    //检查admin用户名是否存在
    public function check_adminaccount(){//检查管理员是否存在
    	$admin=M("admin");
    	$acc=$_GET['account']; //echo $acc;exit;
    	
    	$where['account']=array('eq',$acc);
    	$arr=$admin->where($where)->field('admin_id')->select(); 
    	if(!empty($arr[0]['admin_id'])){
    		echo $arr[0]['admin_id'];
    	}else{
    		echo 0;
    	}
    	exit; 
    }
    
	//检查menber用户是否存在
    public function check_menbername(){//检查管理员是否存在
    	$menber=M("menber");
    	$acc=$_GET['menber_name']; //echo $acc;exit;
    	
    	$where['menber_name']=array('eq',$acc);
    	$arr=$menber->where($where)->field('admin_id')->select(); 
    	if(!empty($arr[0]['admin_id'])){
    		echo $arr[0]['admin_id'];
    	}else{
    		echo 0;
    	}
    	exit; 
    }
	
    //得到游戏下的官网栏目
    public function get_orgtype(){
    	$game_id=$_POST['game_id']; 
    	/**游戏**/ 
    	$orgtype=M("orgtype");
    	$orgtypeinfo = $orgtype->where("game_id=$game_id")->order('sort')->select();  
    	$this->assign('arr',$orgtypeinfo);
    	$this->assign('type_id',$_POST['type_id']);
    	$this->display("Game:get_option");
    }
    
    //放数据放到回收站
    protected function add_recycle($table,$id_name,$id_val){ 
    	$recycle=M("recycle");  
		$module_name = MODULE_NAME;
    	$uparr['is_del']='1';
    	$ojbbtable=M($table);
    	$create['module']       =$module_name;
		$create['admin_id']     =$_SESSION['USER_ID'];
		$create['table']        =$table; 
		$create['id_name']      =$id_name;
		$create['recycle_time'] =time();
    	foreach ($id_val as $id){
	    	$where[$id_name]=array('eq',$id); 
	    	$result = $ojbbtable->where($where)->save($uparr);  
	    	if($result){ 
		    	$create['id_val']      =$id;  
			    $list = $recycle->add($create);  
			    if(!$list){ 
			    	$this->error('删除出错！');exit;
			    }
		    }else{
		    	$this->error('删除出错！');exit;
		    }
	    }
	    return true;
    }
    
    //数据还原
    protected function data_restore($recy_id){ 
    	$recycle=M("recycle");
    	$recy_list=$recycle->where("recy_id=$recy_id")->find();
    	$table   =$recy_list['table'];//表名
    	$id_name =$recy_list['id_name'];//字段名
    	$id_val  =$recy_list['id_val'];//值
    	$uparr['is_del']='0';
    	$ojbbtable=M($table);    
	    $where[$id_name]=array('eq',$id_val); 
	    $result = $ojbbtable->where($where)->save($uparr);  
	    if($result){   
			$list = $recycle->delete($recy_id);
			if($list){ 
			    return true;
			}else{
				return false;
			}
		}else{
		   return false;
		} 
    }
    
    public function get_adschannel(){//得到联盟渠道
    	$pid=$_POST['pid']; 
    	/**游戏服**/ 
    	$adschannel=M("adschannel");
    	$adswheres['pid']=array('eq',$pid);
    	$adswheres['is_del']=array('eq','0');
    	$adsinfo = $adschannel->where($adswheres)->select();  
    	$this->assign('arr',$adsinfo);
    	$this->assign('fid',$_POST['fid']); 
        $this->display('Advert:get_adschannel');
    }
    
    public function get_marketchannel(){//得到联盟渠道
    	$pid=$_POST['pid']; 
    	/**游戏服**/ 
    	$adschannel=M("market_channel");
    	$adswheres['pid']=array('eq',$pid);
    	$adswheres['is_del']=array('eq','0');
    	$adsinfo = $adschannel->where($adswheres)->select();  
    	$this->assign('arr',$adsinfo);
    	$this->assign('fid',$_POST['fid']); 
        $this->display('Advert:get_adschannel');
    }
    
      
    
    public function get_cityarea(){//得到所有地区－省 
    	$city=M("city");
    	$pid=$_POST['cid'];
    	$cityawhere['pid']=array('eq',$pid);
    	$cityiarea = $city->where($cityawhere)->select();  
    	$this->assign('cityiarea',$cityiarea); 
    	$this->display('Index:get_city');
    }
    
    /**
     * 获得小类
     * 
     * **/
    public function ajaxGetSon(){
		$id = $_REQUEST['bc_id'];
		$list = M('sp_qd')->where("group_id=$id")->select();
		//dump($list);
		echo json_encode($list);
	}
	
	
	/**
	 * @deprecated 获取用户帐号号
	 * dapeng.chen
	 * @return 推广帐号
	 * **/
    public function get_user_account($user_id){
    	$user=M("user");
    	 
    	$arr=$user->where("user_id=$user_id")->field('account')->find(); 
    	if(!empty($arr['account'])){
    		return $arr['account'];
    	}else{
    		return '无';
    	}
    	exit; 
    }
	
	/**
	 * @deprecated 获取用户推广号
	 * dapeng.chen
	 * @return 推广帐号
	 * **/
    public function get_user_promotion($user_id){
    	$user_spread=M("user_spread");
    	 
    	$arr=$user_spread->where("user_id=$user_id")->field('sp_user')->find(); 
    	if($arr['sp_user']>0){
    		return $this->get_user_account($arr['sp_user']);
    	}else{
    		return '无';
    	}
    	exit; 
    }

	
	/**
	 * 'where 1=1' ==>''
	 * 'where 1=1 and a=c' ==>'where  a=c'
	 * 字符串替换，把字符串中 where 1=1 替换掉
	 * 
	 */
	public function replace1eq1($sql){
		$pattern_url = "/1=1\s+and\s+/is"; 
		if (preg_match($pattern_url, $sql)){ //包含1=1 and 			
			$sql = preg_replace ("/1=1\s+and/is", "", $sql);
		}else{
			$sql = preg_replace ("/where\s+1=1/is", "", $sql);
		} 
		return $sql;
	}
	/**
	 * ajax获取App地址
	 */
	public function ajaxgetapppath(){
		$game_id = $_REQUEST['game_id'];
		if(empty($game_id) || $game_id==0){
			$this->ajaxReturn('','参数不能为空!',2);
		}
		$info = M('game')->where("game_id=$game_id")->find();
		if($info){
			$this->ajaxReturn($info,'success!',1);
		}
		else{
			$this->ajaxReturn('','error!',0);
		}
	}
	
	/**@  得到地区上面的城市和少省
	 *    pid
	 * */
	public function get_city($cid3){
		
		$city=M('city');
		$pid3=$this->get_city_pid($cid3);
		if($pid3!=0){
		$city_list3=$city->where("pid=$pid3")->select();
		
		$cid2=$city->where("cid=$pid3")->find();
		
		//$pid2=$this->get_city_pid($city_list3[0]['cid']);
		$city_list2=$city->where("pid=$cid2[pid]")->select();
		}
		$city_list=$city->where("pid=1")->select();
		
		
		$this->assign("city_list1", $city_list);
		$this->assign("city_list2", $city_list2);
		$this->assign("city_list3", $city_list3);
		$this->assign("cid3", $cid3);
		$this->assign("cid2", $pid3);
		$this->assign("cid1", $cid2['pid']);
		
	//	echo $city_list3[0]['pid'];exit;
	}
	
	/**@  根据地区找到PID
	 *    cid
	 * */
	public function get_city_pid($cid){
		$city=M('city');
		$res=$city->field('pid')->where("cid=$cid")->find();
		return $res['pid'];
	}
	
	/**@  得到地区上面的城市和少省
	 *    pid
	 * */
	public function is_pid_get_city(){
		$pid=$this->_post('pid');
		$city=M('city');
		$city_list=$city->where("pid=$pid")->select();
		echo json_encode($city_list);
	}
	
	
	
	/**@  得到地区上面的城市和少省
	 *    pid
	 * */
	public function get_region($cid3){
		$city=M('region');
		$pid3=$this->get_region_pid($cid3);
		if($pid3!=0){
		$city_list3=$city->where("pid=$pid3")->select();
		
		$cid2=$city->where("id=$pid3")->find();
		
		//$pid2=$this->get_city_pid($city_list3[0]['cid']);
		$city_list2=$city->where("pid=$cid2[pid] and status=1")->select();
		}
		$city_list=$city->where("pid=0 and status=1")->select();
		if(($cid2['pid']>0) OR ($cid3==0)){
			$this->assign("region_list1", $city_list);
		    $this->assign("region_list2", $city_list2);
		    $this->assign("region_list3", $city_list3);
		    $this->assign("id3", $cid3);
			$this->assign("id2", $pid3);
			$this->assign("id1", $cid2['pid']);
			
		}else{
			$this->assign("region_list1", $city_list2);
		    $this->assign("region_list2", $city_list3);
		    $this->assign("region_list3", '');
		    $this->assign("id3", $cid2['pid']);
			$this->assign("id2", $cid3);
			$this->assign("id1", $pid3);
		}
		
		
	//	echo $city_list3[0]['pid'];exit;
	}
	
	/**@  根据地区找到PID
	 *    cid
	 * */
	public function get_region_pid($cid){
		$city=M('region');
		$res=$city->field('pid')->where("id=$cid")->find();
		return $res['pid'];
	}
	
	/**@  得到地区上面的城市和少省
	 *    pid
	 * */
	public function is_pid_get_region(){
		$pid=$this->_post('pid');
		$city=M('region');
		$city_list=$city->where("pid=$pid and status=1")->select();
		echo json_encode($city_list);
	}
	
	/**@  用户跟进记录 dapeng.chen
	 *    $user_id=跟进用户
	 *    $action =操作方法
	 *    $content=操作内容
	 * */
	public function set_user_log($user_id,$action,$content){
		$action_time=time();             #操作时间 
		$action_ip=get_client_ip();      #操作IP
		$admin_id=$_SESSION['USER_ID'];  #操作人
		$data['user_id']=$user_id;
		$data['action']=$action;
		$data['content']=$content;
		$data['action_time']=$action_time;
		$data['action_ip']=$action_ip;
		$data['admin_id']=$admin_id;
		$user_log =M('user_log');
		return $city_list=$user_log->add($data);
	}
	
	
	//自动联想并取得用户信息
        public function ajaxGetUserInfo(){
            $user = M('user');
            $word = $_REQUEST['q'];
            $map['status']=array('eq',1);
            $map['is_del']=array('eq',0);
            $map['true_name']=array('like','%'.$word.'%');
            $userList = $user->field('user_id,sex,birthday,phone,true_name')->where($map)->limit(0,9)->select();
            foreach ($userList as $key => $value) {
                $userList[$key]['birthday'] = get_age($value['birthday']);  
                if($value['sex']){
                    $userList[$key]['sex'] = "女";
                }else{
                    $userList[$key]['sex'] = "男";
                }
            }
            echo json_encode($userList);
       }
       
     /**
	 * @deprecated 获取用户真实姓名
	 * dapeng.chen
	 * @return 推广帐号
	 * **/
    public function get_user_true_name($user_id){
    	$user=M("user");
    	 
    	$arr=$user->where("user_id=$user_id")->field('true_name')->find(); 
    	if(!empty($arr['true_name'])){
    		return $arr['true_name'];
    	}else{
    		return '无';
    	}
    	exit; 
    }
    
    /***
     *POST CURL 数据 
     *$gateway url连接  $req_data post数据
     **/
    public function post_curl($gateway,$req_data){
    	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $gateway); //配置网关地址
		curl_setopt($ch, CURLOPT_HEADER, 0); //过滤HTTP头
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1); //设置post提交
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req_data); //post传输数据
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
    }
    
    /***
     *get CURL 数据 
     *$gateway url连接
     **/
    public function get_curl($url){
    	$curl = curl_init();							# 初始化一个 cURL 对象
		curl_setopt($curl, CURLOPT_URL, $url);			# 设置你需要抓取的URL
		curl_setopt($curl, CURLOPT_HEADER,0);			# 设置header
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);	# 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		ob_start( );  									# 打开缓冲区
		curl_exec( $curl );
		$contents = ob_get_contents( );
		ob_end_clean(); 								#（或程序执行完毕）之前不会被输出
		curl_close( $curl );
		return  $contents;
    }
    
    /**
     * 上传用户头像
     */
    public function uploadPosterPhoto() {
    	import("ORG.Net.UploadFile");
		$upload = new UploadFile();// 实例化上传类
		$upload->thumb = true;
		$upload->thumbMaxWidth  = 360;
		$upload->thumbMaxHeight = 180;  
		$upload->thumbPath = C('UPLOAD_SETING_PATH');     	
		$upload->thumbRemoveOrigin = true;
		$upload->maxSize  = C('UPLOAD_IMAGE_MAX_SIZE');// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->saveRule='uniqid'; // 上传文件命名规则
		$upload->savePath =C('UPLOAD_SETING_PATH');// 设置原始上传目录
		$upload->autoSub=false;//是否自动为上传文件生成子目录		
		if(!$upload->upload()) {// 上传错误提示错误信息
			$this->ajaxReturn(null,$upload->getErrorMsg(),0);
		}else{// 上传成功 获取上传文件信息
			$uploadFileinfo=$upload->getUploadFileInfo();				
			$imgURL=__ROOT__.'/'.$uploadFileinfo[0]['savepath'].$upload->thumbPrefix.$uploadFileinfo[0]['savename'];
			//删除旧的图片
			if(!empty($_POST['del_img'])){			
				unlink($_SERVER['DOCUMENT_ROOT'].$_POST['del_img']);
			}
			$this->ajaxReturn($imgURL,'',1);
		}
    }
	
	 /**
     * 判断路径是否存在，不存在则创建
     * @param unknown_type $path
     * @return unknown
     */
    function createDir($path){
        if(!file_exists($path)){
            mkdir($path,0755);
        }

        return $path;
    }

    /* 下载cvs函数 */

    function download_csv($file,$newheadarray,$datalist)
    {
		
		$path = 'Uploads/System/';
        $filename =  $path. $file;
		$this->createDir($path);
        $handle = fopen($filename, "w");
        fwrite($handle, chr(255) . chr(254));
        $vals = array();
		echo '<pre>';
		print_r($newheadarray);
		//die(); 
        fwrite($handle, iconv("UTF-8", "UTF-16LE", implode("\t",$newheadarray)."\r\n"));
        foreach ($datalist as $val) {
            $vals = array_intersect_key($val,$newheadarray);
			echo '<pre>';
			print_r($vals);
            fwrite($handle, iconv("UTF-8", "UTF-16LE", implode("\t",$vals)."\r\n"));
        }
		//die();
        fclose($handle);
        ob_end_clean();
        header("Content-type:text/csv");
        header('Content-Disposition: attachment; filename="'.$file.'"');
        readfile("$filename");
        unlink($filename);
        exit();

    }
	
	public  function download_xls($filename, $head_array, $datalist){
		import ( '@.ORG.PHPExcel' );
        $m_objPHPExcel          = new PHPExcel();
        $m_exportType           = "excel";
        $m_strOutputExcelFileName   = $filename.".xls"; //输出EXCEL文件名
        $objActSheet            = $m_objPHPExcel->getActiveSheet();//取得当前活动表
        /*写入数据至表格*/
        $head_keys          = array_keys($head_array);
        $closnum            = count($head_keys);
        $cellsign           = 'A';
        /*表头*/
        for($i = 0; $i < $closnum; $i++){
            $thisC = $cellsign.'1';
            $objActSheet->setCellValue($thisC , $head_array[$head_keys[$i]]);
            /* $objActSheet->getColumnDimension($cellsign)->setAutoSize(true) 根据内容自动适应列宽，但不适合于中文 */
            //$objActSheet->getColumnDimension($cellsign)->setWidth(strlen($head_array[$head_keys[$i]])); 
            $cellsign++;
        }
 
        /*数据部分*/
        $rowsign  = 2;//从第二行开始
        foreach($datalist as $val){
                    $cellsign = 'A';//重定义从A开始
                    for($i = 0; $i < $closnum; $i++){
                        $thisC = $cellsign.$rowsign;  
                        //匹配<font color="red">sku</font>
                        if(preg_match('/<font(.*?)color=\"(.*)\">(.*)<[^>]*>/', $val[$head_keys[$i]],$stylecolor)){
                            $objActSheet->setCellValue($thisC ,$stylecolor[3]);
                            switch ($stylecolor[2]) {
                                case 'red':
                                    $color = "FFFF0000";
                                    break;
                                case 'blue':
                                    $color = "FF0000FF";
                                    break;
                                case 'green':
                                    $color = "FF00FF00";
                                    break;
                                case 'yellow':
                                    $color = "FFFFFF00";
                                    break;
                                default:
                                    $color = "FF000000";
                                    break;
                            }
                            //解决PHPExcel导出长数字末尾几位数为0的问题（预留空格）
                            $objActSheet->getStyle($thisC)->getFont()->getColor()->setARGB($color);    
                        }else{
                            $objActSheet->setCellValue($thisC ,$val[$head_keys[$i]]);
                        }
                        $cellsign++;
                    }
                    $rowsign++;
        } 
            
        /* 从浏览器直接输出$m_strOutputExcelFileName */
        $objWriter = PHPExcel_IOFactory::createWriter($m_objPHPExcel, 'Excel5');
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header("Content-Disposition:attachment;filename=".$m_strOutputExcelFileName);
        header("Content-Transfer-Encoding:binary");
        $objWriter->save("php://output");
    }
    /*导出表格,弹出窗口下载*/
    public static function download_excel($filename,$head_array,$datalist){
        $output = "<HTML>";
        $output .= "<HEAD>";
        $output .= "<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">";
        $output .= "</HEAD>";
        $output .= "<BODY>";
        $output .= Excel::arr_tbl_excel($head_array,$datalist);
        $output .= "</BODY>";
        $output .= "</HTML>";
        header("Content-type:application/msexcel");
        header("Content-disposition: attachment; filename=$filename.xls");
        header("Cache-control: private");
        header("Pragma: private");
        print($output);
    }
    /*导出表格内容处理*/
    public static function  arr_tbl_excel($head_array,$datalist){
        $ret = '';
        $ret .= '<table border=1><tr>';
        /*表头输出*/
        $head_keys = array_keys($head_array);
        for($i=0;$i<count($head_keys);$i++){
            $ret.='<td>'.trim($head_array[$head_keys[$i]]).'</td>';
        }
        $ret.='</tr>';
        /*内容输出*/
        foreach($datalist as $val){
            $ret.='<tr>';
            for($j=0;$j<count($head_keys);$j++){
                //if($head_keys[$j] == 'sku'){
                    $ret.='<td>'.trim($val[$head_keys[$j]]).'</td>';
                //}else{
                //  $ret.='<td>'.$val[$head_keys[$j]].'&nbsp;</td>';
                //}
            }
            $ret.='</tr>';
        }
        $ret.= '</table>';
        return $ret;
    }
    /*导出其它格式的文档,弹出窗口下载*/
    public static function download($filename,$msg,$ext){
        Header( "Content-type:   application/octet-stream ");
        Header( "Accept-Ranges:   bytes ");
        header( "Content-Disposition:   attachment;   filename=$filename.$ext");
        header( "Expires:   0 ");
        header( "Cache-Control:   must-revalidate,   post-check=0,   pre-check=0 ");
        header( "Pragma:   public ");
        echo $msg;
        exit();
    }
    public static function get_upload_excel_datas($upload_dir, $fieldarray, $head = 1, $num = ''){
        $upload_ok = 0;
        $upload_file=$_FILES["file$num"]["name"];        //获取文件名
        $upload_tmp_file=$_FILES["file$num"]["tmp_name"];      //获取临时文件名
        $upload_filetype=$_FILES["file$num"]["type"];    //获取文件类型
        $upload_status=$_FILES["file$num"]["error"];   //获取文件出错情况
        //指定文件存储路径
        $errorchar=array ("-"," ","~","!","@","#","$","%","^","&","(",")","+",","," （","）","？","！","《","》","：","；","——");//非法字符
        foreach($errorchar as $char)
        {
            if(strpos($upload_file,$char)){
                $upload_file=str_replace($char,"_",$upload_file);
            }
        }//循环排除替换文件名中的非法字符
        ///PRINT_R($upload_file);
        //echo '<pre>';print_r($_FILES);DIE();
        $upload_path=$upload_dir.date('Y_m_d_his',time()).$upload_file;   //定义文件最终的存储路径和名称
        //print_r($_FILES);die();
        //$this->getLibrary('basefuns')->setsession('filepath'.$num, $upload_path);
        $_SESSION["filepath$num"] = $upload_path;
        if(is_uploaded_file($upload_tmp_file) ){
            switch($upload_status){
                case 0:$message_upload="";break;
                case 1:$message_upload="上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。";break;
                case 2:$message_upload="上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。";break;
                case 3:$message_upload="文件只有部分被上传。";break;
                case 4:$message_upload="没有文件被上传。";break;
                case 6:$message_upload="没有找到临时文件目录。";break;
                case 7:$message_upload="文件写入失败。";break;
            }                   //分析文件出错情况并给出提示
            if(file_exists($upload_path)){
                $message_upload="同名文件已经存在，请修改你要上传的文件名！";              //检查是否有相同文件存在
            }else if(move_uploaded_file($upload_tmp_file,$upload_path)){
                 $message_upload="文件已经成功上传，请核对下面的数据是否正常";
                 $upload_ok = 1;//读取已经成功上传的文件
                if($upload_ok){//检查数据是否正确
                    $all_arr = Excel::get_excel_datas_withkey($upload_path, $fieldarray, $head);
                }
            }
        }
        return $all_arr;
    }
    //指定列表值来取得数据，并按照对应的key来处理
    public static function get_excel_datas_withkey($filepath, $fieldarray,$head){
        if(file_exists($filepath)){
            $php_excel_obj = new PHPExcel();
            $php_reader = new PHPExcel_Reader_Excel2007();
            $file_name = $filepath;
            if(!$php_reader->canRead($file_name)){
                $php_reader= new PHPExcel_Reader_Excel5();
                if(!$php_reader->canRead($file_name)){
                    $message_upload = 'NO Excel!';
                }
            }
            $php_excel_obj = $php_reader->load($file_name);
            $current_sheet =$php_excel_obj->getSheet(0);
            $all_column =$current_sheet->getHighestColumn();
            $column_len = strlen($all_column);
            $all_row =$current_sheet->getHighestRow();
            $all_arr = array();
            $c_arr = array();
            $head = ($head and is_int($head))?$head:1;
            $key_arr = array();
            //字符对照表
            for($r_i = 1; $r_i<=$all_row; $r_i++){
                $c_arr= array();
                if($r_i==$head){
                    foreach($fieldarray as $c_i){
                        $adr= $c_i . $r_i;
                        $value= $current_sheet->getCell($adr)->getValue();
                        if(is_object($value)) $value= $value->__toString();
                        $key_arr[$c_i]= trim($value);//取第$head行作为列的key值
                    }
                    $all_arr[] =  $key_arr;
                }else{//取其它行的数值
                    foreach($fieldarray as $c_i){
                        $adr= $c_i . $r_i;
                        $value= $current_sheet->getCell($adr)->getValue();
                        if(is_object($value)) $value= $value->__toString();
                        if(strlen($key_arr[$c_i])){
                            $c_arr[$key_arr[$c_i]]= trim($value);
                        }
                    }
                    $all_arr[] =  $c_arr;
                }
            }//excel表数据转化为数组
            //unset($all_arr[$head]);
            return $all_arr;
        }
        //else{
        //  echo '文件名不存在';
        //}
    }
}

?>