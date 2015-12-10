<?php
/** 
 * @date 2012.7.23
 * @author IT部门dapeng.chen 
 * @deprecated 橡树游戏平台，莱科版权所有
 * @abstract 会员管理
 */  
class ActivityAction extends CommonAction {
    public function apply_list(){//活动用户列表
    	$activity_apply=M('activity_apply');
    	import('@.ORG.Page');  
        $arr="";
        if($_GET['act_id']){
        	$act_id=trim($this->_get('act_id'));  #宝宝名称
        	if(!empty($act_id)){$arr['cms_news.id'] = array('eq',$act_id);$this->assign("act_id", $this->_get('act_id'));}
        } 
        //$arr['cms_user.status']=array('eq',1);
        $count = $activity_apply->where($arr)->count();    //计算总数 
        
        
	    $p = new Page($count, 20);
	    $apply_list=$activity_apply->field('cms_activity_apply.*,cms_news.title,cms_news.create_time act_time')->join('LEFT JOIN  cms_news ON cms_news.id=cms_activity_apply.act_id')->limit($p->firstRow . ',' . $p->listRows)->where($arr)->order('cms_activity_apply.id DESC')->select();
	    $page = $p->show(); 
		
		
	    $news=M('news');
		$news_where['status']=array('eq',1);
		$news_where['type_id']=array('eq',6);
	    $news_list=$news->field('id,title')->where($news_where)->select();
		$this->assign("news_list", $news_list);   
		
        $this->assign("page", $page);   
    	$this->assign('apply_list', $apply_list);  
        $this->display(); 
    }
       
 
    public function activity_del(){//删除会员
    	if($_GET["_URL_"][2]){
    		$user_id=$_GET["_URL_"][2];
    	}else{
    	    $user_id=$_POST['id'];
    	}
    	if (!empty($user_id)) {
            //$user = M("user");
            if(is_array($user_id)){
            	//$user_id=implode(",",$user_id);
            	//$result = $user->where("user_id in($user_id)")->delete();   
            	$result=$this->add_recycle('user_activity','id',$user_id);
            }else{
            	//$result = $user->delete($user_id); 
            	$result=$this->add_recycle('user_activity','id',array($user_id));
            }
            if (false !== $result) { 
               $this->success('删除成功！',"");
	        } else {
	           $this->error('删除出错！',"");
	        }
        }else {
            $this->error('删除项不存在！',"");
        }  
        exit;
    }
	 
    public function pre_confirm(){//确认预约
    		$user_activity=M('user_activity');
	        $id=intval($_GET['_URL_'][2]);
	        $user_id=intval($_GET['_URL_'][3]);
	        $user_where['id']=array('eq',$id);
	        $data['pre_status']=1;
	        $data['pre_time']=time();
	        $res=$user_activity->where($user_where)->save($data);
	        if($res){
	           $true_name=$this->get_user_true_name($user_id);
	           $this->set_user_log($user_id,$this->curr_action,$true_name.'确认预约');  #跟进记录
	       	   $this->success('操作成功！');
	        }else{
	           $this->error('操作失败！');
	        }
	        exit; 
    }
    
    public function to_confirm(){//确认到店
    		$user_activity=M('user_activity');
	        $id=intval($_GET['_URL_'][2]);
	        $user_id=intval($_GET['_URL_'][3]);
	        $user_where['id']=array('eq',$id);
	        $data['to_status']=1;
	        $data['to_time']=time();
	        $res=$user_activity->where($user_where)->save($data);
	        if($res){
	           $true_name=$this->get_user_true_name($user_id);
	           $this->set_user_log($user_id,$this->curr_action,$true_name.'确认到店');  #跟进记录
	       	   $this->success('操作成功！');
	        }else{
	           $this->error('操作失败！');
	        }
	        exit; 
    }
    
    public function sign_confirm(){//是否取消
    		$user_activity=M('user_activity');
	        $id=intval($_GET['_URL_'][2]);
	        $user_id=intval($_GET['_URL_'][3]);
	        $user_where['id']=array('eq',$id);
	        $data['sign_status']=0;
	        $res=$user_activity->where($user_where)->save($data);
	        if($res){
	           $true_name=$this->get_user_true_name($user_id);
	           $this->set_user_log($user_id,$this->curr_action,$true_name.'已取消');  #跟进记录
	       	   $this->success('操作成功！');
	        }else{
	           $this->error('操作失败！');
	        }
	        exit; 
    }
    
    
	public function member_log(){//会员操作日志 
		if(!$_GET["_URL_"][2]){
    		$this->error('操作有误！','member_list');
    	}
		$user_id=$_GET["_URL_"][2];
		if(!empty($user_id)){
			$model = M('userlog');
			import('@.ORG.Page');  
			$count = $model->where("user_id = $user_id")->count();    //计算总数 
			$p = new Page($count, 15);
			$user_list = $userLog = $model->where("user_id = $user_id")->limit($p->firstRow . ',' . $p->listRows)->select();
			$page = $p->show(); 
			$this->assign('userLog',$userLog);
			$this->assign("page", $page);   
			$this->display('member_log');
		}
		else{
			$this->error('该会员不存在！','member_list');
		}
	}
    
        public function updeteSpUser($user_id,$sp_account){
             $UserSpread = M('UserSpread');
             $UserSpreadInfo = $UserSpread->where("user_id = $user_id")->find();
             if($UserSpreadInfo == FALSE){
                  $data['user_id']=  $user_id;
                  $UserSpread->add($data);
                  $UserSpreadInfo = $UserSpread->where("user_id = $user_id")->find();
             }
             $RebatesLog=M('RebatesLog');
             if(empty($sp_account)){
                   //如果原来的值本身相等，不做任何操作，否者做修改sp_user等于0，并且返利日志删除
                   if($UserSpreadInfo['sp_user']==0){
                               return true;  
                   }else{
                        $sp_user = $UserSpreadInfo['sp_user'];
                        $row =  $UserSpread->where("user_id = $user_id")->setField("sp_user",0);
                        if($row === FALSE){
                            
                              return FALSE;
                        }else{
                              $LogInfo  =$RebatesLog->where("pay_user = $user_id")->select();
                              if(!empty($LogInfo)){
                                 foreach ($LogInfo as $key => $value) {
                                     $rebates_money += $value['rebates_money'];
                                     unset($date);
                                     $oakSpreadLog =M('SpreadLog'); 
                                     $date['user_id'] = $user_id;
                                     $date['sp_user'] = 0;
                                     $date['type'] = 1;
                                     $date['content'] = serialize($value);
                                     $date['amount'] = $value['rebates_money'];
                                     $date['add_time'] = time();
                                     $date['admin_id'] =  session("USER_ID");
                                     $oakSpreadLog->add($date);
                                 }
                                 $row = $RebatesLog->where("pay_user = $user_id")->delete();
                                 $rows2 = $UserSpread->where("user_id = $sp_user")->setDec("sp_money",$rebates_money);
                                 if($row === FALSE || $rows2 === FALSE ){
                                  
                                      return FALSE;
                                 }else{
                                     $oakSpreadLog =M('SpreadLog');
                                     $date['user_id'] = $user_id;
                                     $date['sp_user'] = 0;
                                     $date['type'] = 4;
                                     $date['content'] = serialize($value);
                                     $date['amount'] = $value['rebates_money'];
                                     $date['add_time'] = time();
                                     $date['admin_id'] =  session("USER_ID");
                                     $oakSpreadLog->add($date);
                                   
                                     return TRUE;
                                 }
                             }else{
                                
                                 return TRUE;
                             }
                        }
                    }
             }else{
                   $userInfo = $this->getUserInfo($sp_account);
                   //如果原来的值本身相等，不做任何操作，否者做修改sp_user等于0，并且返利日志删除
                   if($UserSpreadInfo['sp_user']==$userInfo['user_id']){
                           return true;
                   }else{
                       
                          if($userInfo['sp_qd_id']==0){
                             return FALSE;
                         }else{
                             //取到推广人基本信息
                                 $SpreadInfo = $UserSpread->where("user_id = ".$userInfo['user_id'])->find();
                                 if($SpreadInfo === FALSE){
                                      return FALSE;   
                                }
                                $row =  $UserSpread->where("user_id = $user_id")->setField("sp_user",$userInfo['user_id']);
                                if($row === FALSE){
                                      return FALSE;   
                                }else{
                                    $Payorder = M('Payorder');
                                    $JiaRebates_money = 0;
                                    $orderList = $Payorder->where("user_id = $user_id and pay_status = 1 and is_del=0 ")->select();
                                    foreach ($orderList as $key => $value) {
                                         $order_no = $value['order_no'];
                                         $LogInfo  =$RebatesLog->where("order_no = '$order_no'")->find();
                                         if(empty($LogInfo)){
                                                unset($data);
                                                $data['order_no'] = $order_no;
                                                $data['rebates_user'] = $userInfo['user_id'];
                                                $data['rebates_rate'] = $SpreadInfo['sp_rate'];
                                                $data['pay_user'] = $user_id;
                                                $data['pay_money'] = $value['amount'];
                                                $data['rebates_money'] = $value['amount']*$SpreadInfo['sp_rate']/100;
                                                $data['add_time'] = time();
                                                $data['is_del'] = 0;
                                                $rows = $RebatesLog->add($data);
                                                if($rows === FALSE){
                                                 
                                                     return FALSE;  
                                                } 
                                                $JiaRebates_money +=$data['rebates_money'];
                                         }else{
                                                unset($data);
                                                $rebates_money+=$LogInfo['rebates_money'];
                                                $data['log_id']   = $LogInfo['log_id']; 
                                                $data['rebates_user'] = $userInfo['user_id'];
                                                $data['rebates_rate'] = $SpreadInfo['sp_rate'];
                                                $data['rebates_money'] = $value['amount']*$SpreadInfo['sp_rate']/100;
                                                $data['add_time'] = time();
                                                $rows = $RebatesLog->save($data);
                                                 if($rows === FALSE){
                                                      
                                                     return FALSE;  
                                                }
                                                $JiaRebates_money +=$data['rebates_money'];
                                         }
                                       
                                         if($UserSpreadInfo['sp_user'] != 0){
                                                $oakSpreadLog =M('SpreadLog');
                                                $date['user_id'] = $user_id;
                                                $date['sp_user'] = $userInfo['user_id'];
                                                $date['type'] = 2;
                                                $date['content'] = serialize($value);
                                                $date['amount'] = $LogInfo['rebates_money'];
                                                $date['add_time'] = time();
                                                $date['admin_id'] =  session("USER_ID");
                                                $oakSpreadLog->add($date);
                                                $oakSpreadLog =M('SpreadLog');
                                                $date['user_id'] = $user_id;
                                                $date['sp_user'] = $UserSpreadInfo['sp_user'];
                                                $date['type'] = 1;
                                                $date['content'] = serialize($value);
                                                $date['amount'] = $LogInfo['rebates_money'];
                                                $date['add_time'] = time();
                                                $date['admin_id'] =  session("USER_ID");
                                                $oakSpreadLog->add($date);
                                         }else{
                                                $oakSpreadLog =M('SpreadLog');
                                                $date['user_id'] = $user_id;
                                                $date['sp_user'] = $userInfo['user_id'];
                                                $date['type'] = 2;
                                                $date['content'] = serialize($value);
                                                $date['amount'] = $LogInfo['rebates_money'];
                                                $date['add_time'] = time();
                                                $date['admin_id'] =  session("USER_ID");
                                                $oakSpreadLog->add($date);
                                         }
                                    }
                                
                                     if($UserSpreadInfo['sp_user'] != 0){
                                         $rows = $UserSpread->where("user_id = ".$UserSpreadInfo['sp_user'])->setDec("sp_money",$rebates_money);
                                         $oakSpreadLog =M('SpreadLog');
                                         $date['user_id'] = $user_id;
                                         $date['sp_user'] = $UserSpreadInfo['sp_user'];
                                         $date['type'] = 4;
                                         $date['content'] = serialize($value);
                                         $date['amount'] = $rebates_money;
                                         $date['add_time'] = time();
                                          $date['admin_id'] =  session("USER_ID");
                                         $oakSpreadLog->add($date);
                                    }else{
                                         $rows = true;
                                    }
                                    $rows2 = $UserSpread->where("user_id = ".$userInfo['user_id'])->setInc("sp_money",$JiaRebates_money);
                                   
                                     $oakSpreadLog =M('SpreadLog');
                                     $date['user_id'] = $user_id;
                                     $date['sp_user'] = $userInfo['user_id'];
                                     $date['type'] =3;
                                     $date['content'] = serialize($value);
                                     $date['amount'] = $JiaRebates_money;
                                     $date['add_time'] = time();
                                     $date['admin_id'] =  session("USER_ID");
                                     $oakSpreadLog->add($date);
                                      
                                     
                                    if($rows === FALSE || $rows2 === FALSE){
                                                       
                                                      return FALSE;   
                                     }else{
                                                      
                                                      return TRUE;
                                     }
                               }
                         }
                   }
             }
        }
        
        
        public function  getUserAccount($user_id){
                $user =  M('User');
                $result = $user->field("account")->find($user_id);
                return $result['account'];
        } 
        
        public function  getUserInfo($account){
                $user =  M('User');
                $result = $user->where("account = '$account'")->find();
                return $result;
        }
        
        
        public function member_editsp(){
             if($this->isPost()){
                $user_id=$_REQUEST['user_id'];
                if(!empty($_POST['sp_user_account'])){
                     $userInfo = $this->getUserInfo($_POST['sp_user_account']);
                     if($userInfo['sp_qd_id']==0 || empty($userInfo)){
                          
                              $this->error("推广员账号不正确!");
                     }
                }
                $result = $this->updeteSpUser($user_id,$_POST['sp_user_account']);
                if($result){
               
                    $this->success('数据保存成功！',"__APP__/Member/member_list/");
                }else{
                         
                          $this->error("推广员修改错误!");
                }
                exit();
            }
            $user_id = $_REQUEST['id'];
            $user_spread = M('user');
            $userInfo=$user_spread->field('cms_user.user_id,cms_user_spread.sp_user,cms_user.account')->join('cms_user_spread on cms_user_spread.user_id =cms_user.user_id ')->where("cms_user.user_id = $user_id")->find();
            if($userInfo == FALSE){
                $this->error('该会员不存在！','member_list');
            }else if($userInfo['sp_user']){
                $account  = $this->getUserAccount($userInfo['sp_user']);
            }
           
            $this->assign("user", $userInfo);   
    	    $this->assign('account', $account);  
            $this->display("Member:member_editsp"); 
        }
        
   /**
     * 活动信息导出
     */
    public function activity_export() {   
                $user_activity=M('user_activity');
                import('@.ORG.Page');  
                $arr="";
                $true_name=trim($this->_get('true_name'));  #宝宝名称
        	if(!empty($true_name)){$arr['cms_user.true_name'] = array('LIKE',"%".$true_name."%");$this->assign("true_name", $this->_get('true_name'));}  
                $region_id=intval($this->_get('region_id')); #城市
        	if($region_id>0){
        		$region_list=$user_activity->query("SELECT id FROM `cms_region` where pid=$region_id and status=1");
	    		foreach ($region_list as $rlist){
	    			$arr_c1[]=$rlist['id'];
	    		}
	    		$str_c1=implode(',',$arr_c1);
	    		$region_list=$user_activity->query("SELECT id FROM `cms_region` where pid in($str_c1) and status=1");
	    		foreach ($region_list as $rlist2){
	    			$arr_c1[]=$rlist2['id'];
	    		}
	    		$str_c2=implode(',',$arr_c1);
	    		$arr['cms_user.region_id']=array('in',$str_c2);
        		$this->assign("region_id", $this->_get('region_id'));
        	}  
        	$admin_id=intval($this->_get('admin_id')); #所属客服 
        	if($admin_id>0){$arr['cms_user.admin_id'] = array('eq',$admin_id);$this->assign("admin_id", $this->_get('admin_id'));}  
        	
                $pre_status=intval($this->_get('pre_status')); #预约状态
        	if($pre_status!=3){$arr['cms_user_activity.pre_status'] = array('eq',$pre_status);$this->assign("pre_status", $this->_get('pre_status'));}else{$this->assign("pre_status",3);}
        	
        	$to_status=intval($this->_get('to_status')); #到店状态 
        	if($to_status!=3){$arr['cms_user_activity.to_status'] = array('eq',$to_status);$this->assign("to_status", $this->_get('to_status'));}else{$this->assign("to_status",3);}
        	
        	$source_id=intval($this->_get('source_id')); #到店状态 
        	if($source_id!=100){$arr['cms_user.source_id'] = array('eq',$source_id);$this->assign("source_id", $this->_get('source_id'));}else{$this->assign("source_id",100);}
        	//$arr['cms_user.status']=array('eq',1);
                $arr['cms_user_activity.type']=array('eq',2);
                $user_arr=$user_activity->field('cms_user_activity.*,cms_user.sex,cms_user.birthday,cms_user.phone,cms_user.true_name')->join('LEFT JOIN cms_user ON cms_user.user_id=cms_user_activity.user_id')->order('cms_user_activity.create_time DESC')->select();
                vendor('PHPExcel_1_7_8.Classes.PHPExcel');
		vendor('PHPExcel_1_7_8.Classes.PHPExcel.IOFactory');
		vendor('PHPExcel_1_7_8.Classes.PHPExcel.Worksheet');        	
		//创建Excel对象
		$objPHPExcel = new PHPExcel();
		//设置Excel数据缓存方式为磁盘文件缓存（适用于大数据量处理，以减少对PHP自身内存的占用）
		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
		$cacheSettings = array('dir'  => C('LEG_EXCEL_DATA_CACHE_DIR'));
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
		//设置Excel元数据
		$objPHPExcel->getProperties()->setCreator("客服管理系统");
		$objPHPExcel->getProperties()->setLastModifiedBy("客服管理系统后台程序");
		$objPHPExcel->getProperties()->setTitle("客服管理系统后台导出客服活动列表");
		$objPHPExcel->getProperties()->setSubject("客服活动列表");
		$objPHPExcel->getProperties()->setDescription("Exported document for Office 2007 XLSX, generated using PHP classes.");
		$objPHPExcel->getProperties()->setKeywords("office 2007 php");
		$objPHPExcel->getProperties()->setCategory("Export result file");
		//填充数据到活动的电子表格中			
		$objPHPExcel->setActiveSheetIndex(0);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$objWorksheet->setCellValueByColumnAndRow(0,1,'ID');   
		$objWorksheet->setCellValueByColumnAndRow(1,1,'宝宝名称');   
		$objWorksheet->setCellValueByColumnAndRow(2,1,'性别');   			
		$objWorksheet->setCellValueByColumnAndRow(3,1,'宝宝年龄'); 
		$objWorksheet->setCellValueByColumnAndRow(4,1,'课程名称'); 
		$objWorksheet->setCellValueByColumnAndRow(5,1,'预约时间'); 
		$objWorksheet->setCellValueByColumnAndRow(6,1,'电话号码'); 
		$objWorksheet->setCellValueByColumnAndRow(7,1,'预约状态');
		$objWorksheet->setCellValueByColumnAndRow(8,1,'到店状态');
		foreach ($user_arr as $key => $rs) {
			$key+=2;
			$objWorksheet->setCellValueByColumnAndRow(0,$key,$rs['id']);   
			$objWorksheet->setCellValueByColumnAndRow(1,$key,$rs['true_name']);  
                        $objWorksheet->setCellValueByColumnAndRow(2,$key,$rs['sex'] == 1 ?'女':'男');				
			$objWorksheet->setCellValueByColumnAndRow(3,$key,get_age($rs['birthday'])); 
			$objWorksheet->setCellValueByColumnAndRow(4,$key,$rs['course']);  
			$objWorksheet->setCellValueByColumnAndRow(5,$key,date("Y-m-d H:i:s",$rs['create_time'])); 
			$objWorksheet->setCellValueByColumnAndRow(6,$key,$rs['phone']); 
			$objWorksheet->setCellValueByColumnAndRow(7,$key,$rs['pre_status']  == 1 ?'已预约':'未预约');
			$objWorksheet->setCellValueByColumnAndRow(8,$key,$rs['to_status'] == 1? "已到店":'未到店');
		}
		
		//导出到文件中
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
		//直接输出到浏览器
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="ActivityUserList.xlsx"');
		header('Cache-Control: max-age=0');					
		$objWriter->save('php://output');
    }
       
}