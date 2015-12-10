<?php
/** 
 * @date 2013.8.2
 * @author IT部门dapeng.chen 
 * @deprecated 版权所有
 * @abstract 订单管理
 */  
class SubscribeAction extends CommonAction {
    public function subscribe_list(){//订单列表
        import('@.ORG.Page');  
        
        if($_GET['findsub']){
	        //if($_REQUEST['order_no']){
	           // $map['order_no'] = array('like',"%".$_REQUEST['order_no']."%");
	        //}
	        if($_REQUEST['venues_id']){
	            $map['venues_id'] = array('eq',$_REQUEST['venues_id']);
	        }
	        if($_REQUEST['project_name']){
	            $map['project'] = array('eq',$_REQUEST['project_name']);
	        }
	        if($_REQUEST['status']!=3){
	            $map['status'] = array('eq',$_REQUEST['status']);
	        }
	        if($_REQUEST['sign_status']!=3){
	            $map['sign_status'] = array('eq',$_REQUEST['sign_status']);
	        }
//	       
//	        if($_REQUEST['business_id']){
//	             $map['admin_id'] =  array('eq',$_REQUEST['business_id']);
//	        }else if($_REQUEST['counselor_id']){
//	             $map['admin_id'] =  array('eq',$_REQUEST['counselor_id']);
//	        }
	        $paramArr=array();			
	        if($_REQUEST['create_time_start']!=''){
	                $startDateArr=strtotime($_REQUEST['create_time_start']);
	                array_push($paramArr,array('egt',$startDateArr));
	                $map['create_time']=$paramArr;				
	        }
	        if($_REQUEST['create_time_end']!=''){
	                $endDateArr=strtotime($_REQUEST['create_time_end']);
	                array_push($paramArr,array('elt',$endDateArr));
	                $map['create_time']=$paramArr;
	        }	
        }
        
    	$subscribe= M("subscribe");
        $count= $subscribe->where($map)->count();// 查询满足要求的总记录数
        $Page=new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
        //查询分页数据
        $list = $subscribe->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('create_time desc')->select();
        //echo $orders->getLastSql();exit;
        $show=$Page->show();// 分页显示输出
         
        
        $this->assign("list",$list);
        $this->assign("page",$show);
        
        $user=M('user');
        $user_where['is_master']=array('eq',1);
        //$user_where['status']=array('eq',1);
        $user_list=$user->field('username,user_id')->where($user_where)->select();
        foreach ($user_list as $res){
        	$user_arr[$res[user_id]]=$res[username];
        }
        $this->assign('user_list',$user_arr);
        
        $this->display(); 
    }
    
     
    
    /*
     * 订单添加
     */
    public function subscribe_send(){//订单添加
    	$subscribe = M('subscribe');
        if($this->isPost()){
             $id=$this->_post('id');
             $save_data['user_id']=$this->_post('user_id');
             $save_data['status']=$this->_post('status');
	         $list = $subscribe->where("id=$id")->save($save_data);
	         if($list){
	            	$this->success('数据保存成功！',"__APP__/subscribe/subscribe_list/");exit; 
	         } else {
	                $this->error("没有更新任何数据!","__APP__/subscribe/subscribe_list/");
	         }
	         exit;
    	}
        
        $orders_id=$this->_get('id');
        $arr=$subscribe->where("id=$orders_id")->find();
        if($arr['status']!=1){
        	//$this->error("无效的订单，不可以编辑!","__APP__/Orders/orders_list/");
        }
        $this->assign('arr',$arr);
        
        $user=M('user');
        $user_where['is_master']=array('eq',1);
        //$user_where['status']=array('eq',1);
        $user_list=$user->field('username,user_id')->where($user_where)->select();
        $this->assign('user_list',$user_list);
        $this->display();
    }
 
    public function select_item(){
    	if($this->isPost()){
    		$cart_id_arr=$_POST['cart_id'];
    		if(empty($cart_id_arr)){
    			$this->error('没有选择任何商品！');exit; 
    		}
    		$sub_id=(int)$_POST['sub_id'];
    		$item=M('item');
	        $cart_id_str=implode(',',$cart_id_arr);
	    	$item_cart=M('item_cart');
	    	$cart_where['c.cart_id']=array('in',$cart_id_str);
	    	$cart_res=$item_cart->join('as c LEFT JOIN cms_item as i ON c.item_id=i.item_id')->field('c.item_num,c.item_price,c.item_name,c.item_id,i.inventory')->where($cart_where)->select();
    		$is_true=true;
	    	$total_price=0;
	    	foreach($cart_res as $list){
	    		if($list[item_num]>$list[inventory]){
	    			$is_true=false;
	    			$str=$list[item_name].' 库存不足';
	    			break;
	    		}
	    		$total_price=$total_price+$list[item_num]*$list[item_price];
	    	}
	    	if(!$is_true){
	    		$this->error($str);exit; 
	    	}
    		
    		$orders=M('orders');
	    	$order_data['create_time']=time();
	    	$order_data['orders_no']=date('YmdHis').session('user_id');
	    	$order_data['amount']=$total_price;
	    	$order_data['orders_status']=0;
	    	$order_data['pay_status']=0;
	    	$order_data['payway_id']=0;
	    	$order_data['user_ip']=get_client_ip();
	    	$order_data['is_del']=0;
	    	$order_data['sub_id']=$sub_id;
	    	$order_data['openid']=$_POST['openid'];
	    	$order_data['orders_status']=0;
	    	
	    	$orders_id=$orders->add($order_data);
	    	if($orders_id){
	    		$orders_item=M('orders_item');
	    		$item_data='';
	    		foreach($cart_res as $list){
	    			$item_data[]=array(
	    				'orders_id'=>	$orders_id,
	    				'item_id'=>	$list[item_id],
	    				'buy_num'=>	$list[item_num],
	    				'buy_price'=>	$list[item_price],
	    			);
	    		}
	    		$res=$orders_item->addAll($item_data);
	    		if($res){
		    		$user=M('user');
		    		$openid=$_POST['openid'];
		    		$user_where['openid']=array('eq',"$openid");
		    		$user_res=$user->where($user_where)->field('user_id')->find();
		    		$user_sum=M('user_sum');
					$sum_res=$user_sum->where('user_id='.$user_res['user_id'])->field('user_id,orders_num,total_price')->find();
					if($sum_res){
						$orders_cs=$orders->where($user_where)->count();
						$orders_total_money=$orders->where($user_where)->sum('amount');
						$sum_save_data['orders_num']=$sum_res['orders_num']+1;
						$sum_save_data['total_price']=$sum_res['total_price']+$total_price;
						$sum_save_data['price']=$orders_total_money/$orders_cs;
						$user_sum->where('user_id='.$user_res['user_id'])->save($sum_save_data);
					}else{
						$sum_data['user_id']=$user_res['user_id'];
						$sum_data['orders_num']=1;
						$sum_data['total_price']=$total_price;
						$sum_data['price']=$total_price;
						$user_sum->add($sum_data);
					}
		    		
		    		$this->success('数据保存成功！',"__APP__/subscribe/subscribe_list/");exit; 
		    	}else{
		    		$this->success('数据保存失败！',"__APP__/subscribe/subscribe_list/");exit; 
		    	}
	    	}else{
		    	$this->error('数据保存失败！',"__APP__/subscribe/subscribe_list/");exit; 
		    }
    		//print_r($item_id_arr);exit;
    	}
    	$sub_id=$this->_get('sub_id');
    	$orders=M('orders');
    	$orders_res=$orders->field('orders_id')->where("sub_id=$sub_id")->find();
    	if($orders_res){
    		$this->error('该预定用户已经下过订单了！',"__APP__/subscribe/subscribe_list/");exit; 
    	}
    	$item_cart=M('item_cart');
    	$item_where['c.sub_id']=array('eq',$sub_id);
    	$cart_list=$item_cart->join('as c LEFT JOIN cms_item as i ON c.item_id=i.item_id')->where($item_where)->field('i.item_name,i.item_id,i.item_price,i.color,i.icon,c.item_num,c.cart_id')->select();
    	$this->assign('cart_list',$cart_list);
    	
    	$subscribe=M('subscribe');
    	$sub_res=$subscribe->where("id=$sub_id")->field('username,tel,openid,id')->find();
    	$this->assign('sub_res',$sub_res);
    	
    	
    	$this->display();
    }
    
     /**
	 *  删除，包括行内删除的，和勾中要删除的
	 */
	public function order_delete(){
		$id=$_GET['id'];
                if(id != ''){
                      $Orderlog = M('Orderlog');
                      $rows = $Orderlog->where("id in ($id) ")->setField("del_flag",1);
                      if($rows!==false){
                          $this->success('数据保存成功！',"__APP__/Order/order_list/");exit; 
                      }else{
                           $this->error("没有更新任何数据!","__APP__/Order/order_list/");
                      }
                }else{
                       $this->error("请选择删除的列","__APP__/Order/order_list/");
                }
	}
	
        public function orders_export(){//订单导出 
     	import('@.ORG.PHPExcel');  
        $arr="";
        ini_set('memory_limit', '-1');
        set_time_limit(0);
       
        	if($_REQUEST['venues_id']){
	            $map['cms_orders.venues_id'] = array('eq',$_REQUEST['venues_id']);
	        }
	        if($_REQUEST['project_name']){
	            $map['cms_orders.project'] = array('eq',$_REQUEST['project_name']);
	        }
	        if($_REQUEST['status']!=3){
	            $map['cms_orders.status'] = array('eq',$_REQUEST['status']);
	        }
	        if($_REQUEST['sign_status']!=3){
	            $map['cms_orders.sign_status'] = array('eq',$_REQUEST['sign_status']);
	        }
 
	        $paramArr=array();			
	        if($_REQUEST['create_time_start']!=''){
	                $startDateArr=strtotime($_REQUEST['create_time_start']);
	                array_push($paramArr,array('egt',$startDateArr));
	                $map['cms_orders.create_time']=$paramArr;				
	        }
	        if($_REQUEST['create_time_end']!=''){
	                $endDateArr=strtotime($_REQUEST['create_time_end']);
	                array_push($paramArr,array('elt',$endDateArr));
	                $map['cms_orders.create_time']=$paramArr;
	        }	
        $orders=M("orders");
        //$payorder_list = $payorder->join('oak_payorder LEFT JOIN oak_payway ON oak_payorder.payway_id = oak_payway.payway_id')->join('LEFT JOIN oak_server ON oak_payorder.server_id=oak_server.server_id')->join('LEFT JOIN oak_game ON oak_game.game_id=oak_server.game_id')->join('LEFT JOIN oak_user ON oak_user.user_id=oak_payorder.user_id')->where($arr)->field('oak_payorder.*,oak_payway.payway_name,oak_server.server_name,oak_game.game_name,oak_user.account')->order('oak_payorder.start_time DESC')->select();
        
        $payorder_list = $orders->join('LEFT JOIN cms_venues ON cms_venues.id=cms_orders.venues_id')
								  ->where($map)
								  ->field('cms_orders.*,cms_venues.name')
								  ->order('cms_orders.orders_id DESC')
								  ->select(); 
        
        
        error_reporting(E_ALL);
        date_default_timezone_set('Europe/London');
        $objPHPExcel = new PHPExcel();
        
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
									 ->setLastModifiedBy("Maarten Balliauw")
									 ->setTitle("订单详情")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");
		
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', '订单号')
		            ->setCellValue('B1', '预定场馆')
		            ->setCellValue('C1', '预定项目和场地')
		            ->setCellValue('D1', '预定时间')
		            ->setCellValue('E1', '订单状态')
		            ->setCellValue('F1', '到场状态')
		            ->setCellValue('G1', '下单时间')
		            ->setCellValue('H1', '姓名1')
		            ->setCellValue('I1', '身份证1')
		            ->setCellValue('J1', '电话1')
		            ->setCellValue('K1', '姓名2')
		            ->setCellValue('L1', '身份证2')
		            ->setCellValue('M1', '电话2')
		            ->setCellValue('N1', '姓名3')
		            ->setCellValue('O1', '身份证3')
		            ->setCellValue('P1', '电话3')
		            ->setCellValue('Q1', '姓名4')
		            ->setCellValue('R1', '身份证4')
		            ->setCellValue('S1', '电话4')
		            ->setCellValue('T1', '姓名5')
		            ->setCellValue('U1', '身份证5')
		            ->setCellValue('V1', '电话5')
		            ->setCellValue('W1', '姓名6')
		            ->setCellValue('X1', '身份证6')
		            ->setCellValue('Y1', '电话6')
		            ->setCellValue('Z1', '姓名7')
		            ->setCellValue('AA1', '身份证7')
		            ->setCellValue('AB1', '电话7')
		            ->setCellValue('AC1', '姓名8')
		            ->setCellValue('AD1', '身份证8')
		            ->setCellValue('AE1', '电话8')
		            ->setCellValue('AF1', '姓名9')
		            ->setCellValue('AG1', '身份证9')
		            ->setCellValue('AH1', '电话9')
		            ->setCellValue('AI1', '姓名10')
		            ->setCellValue('AJ1', '身份证10')
		            ->setCellValue('AK1', '电话10')
		            ;
		
		// Miscellaneous glyphs, UTF-8
		$num=2;
		date_default_timezone_set('PRC');	# 设置北京时区
		$arr=array('G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Y','AA','BB','CC');
		foreach ($payorder_list as $val) {
			if($val['status']==1){$str= "成功";}elseif($val['status']==2){$str= "取消";}else{$str= "不成功";}
			if($val['sign_status']==1){$sign_status= "已到场";}else{$sign_status= "未到场";}
			if($val['create_time']==0){$finish_time= "未完成";}else{$finish_time= date('Y-m-d H:i:s',$val['create_time']);}
			$user_info=$this->get_orders_user($val[orders_id]);
			$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue("A$num", ' '.$val['orders_no'])
		            ->setCellValue("B$num", $val['name'])
		            ->setCellValue("C$num", $val['project'].'('.$val['project_code'].')')
		            ->setCellValue("D$num", $val['time_ymd'].' '.$val['time_hour'])
		            ->setCellValue("E$num", $str)
		            ->setCellValue("F$num", $sign_status)
		            ->setCellValue("G$num", date('Y-m-d H:i:s',$val['create_time']))
		            ->setCellValue("H$num", $user_info[0][0])
		            ->setCellValue("I$num", $user_info[0][1])
		            ->setCellValue("J$num", $user_info[0][2])
		            ->setCellValue("K$num", $user_info[1][0])
		            ->setCellValue("L$num", $user_info[1][1])
		            ->setCellValue("M$num", $user_info[1][2])
		            ->setCellValue("N$num", $user_info[2][0])
		            ->setCellValue("O$num", $user_info[2][1])
		            ->setCellValue("P$num", $user_info[2][2])
		            ->setCellValue("Q$num", $user_info[3][0])
		            ->setCellValue("R$num", $user_info[3][1])
		            ->setCellValue("S$num", $user_info[3][2])
		            ->setCellValue("T$num", $user_info[4][0])
		            ->setCellValue("U$num", $user_info[4][1])
		            ->setCellValue("V$num", $user_info[4][2])
		            ->setCellValue("W$num", $user_info[5][0])
		            ->setCellValue("X$num", $user_info[5][1])
		            ->setCellValue("Y$num", $user_info[5][2])
		            ->setCellValue("Z$num", $user_info[6][0])
		            ->setCellValue("AA$num", $user_info[6][1])
		            ->setCellValue("AB$num", $user_info[6][2])
		            ->setCellValue("AC$num", $user_info[7][0])
		            ->setCellValue("AD$num", $user_info[7][1])
		            ->setCellValue("AE$num", $user_info[7][2])
		            ->setCellValue("AF$num", $user_info[8][0])
		            ->setCellValue("AG$num", $user_info[8][1])
		            ->setCellValue("AH$num", $user_info[8][2])
		            ->setCellValue("AI$num", $user_info[9][0])
		            ->setCellValue("AJ$num", $user_info[9][1])
		            ->setCellValue("AK$num", $user_info[9][2])
		            ;
		           //for($k=0;$k<count($arr);$k=$k+3){
		            	//$objPHPExcel->setCellValue($arr[$k].$num, $user_info[$arr[$k]][0])->setCellValue($arr[$k+1].$num, $user_info[$arr[$k+1]][1])->setCellValue($arr[$k+2].$num, $user_info[$arr[$k+2]][2]);
		            //}
		            
			$num++;
		}
		//exit;
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Simple');
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="OrderList.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		$this->success('操作成功！',"__APP__/orders/orders_list/");
		exit;
        
    }
    
	    //传入生日得到年龄
	public function get_orders_user($orders_id){
	     $orders_user=M('orders_user');
	     $user_list=$orders_user->where("orders_id=$orders_id")->select();
	     $html='';
	     $arr=array('G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Y','AA','BB','CC');
	     $i=0;
	     foreach ($user_list as $list){
	     	 $html[]=array($list['username'],$list['identity'],$list['tel']);
	     	 $i++;
	     }
	     return $html;
	}
   
}
