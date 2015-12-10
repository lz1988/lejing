<?php

/**
 * Class EmployeepayAction
 * @title 职工薪资报表
 * @author zhi.li
 * @create on 2015-09-29
 */
class EmployeepayAction extends CommonAction
{
    public function employeepay_list()
    {
        import('@.ORG.Page');

		if ($_POST['export']){
			$this->export_csv();
			exit;
		}
        $employee_pay = M('employee_pay');
        $arr=" 1 = 1 ";
		
		$shop_id = $_POST['shop_id'];
		if ($shop_id){
			$arr .= ' and cms_employee_pay.shop_id ='.$shop_id;
		}
		
		$is_used = $_POST['is_used'];
		if (isset($is_used) && $is_used <> 8){
			$arr .= ' and is_used = "'.$is_used.'"';
		}
		
		$input_date_start = $_POST['input_date_start'];
		if ($input_date_start){
			$arr .= ' and input_date >= "'.$input_date_start.'"';
		}
		
		$input_date_end = $_POST['input_date_end'];
		if ($input_date_end){
			$arr .= ' and input_date <="'.$input_date_end.'"';
		}
        $count = $employee_pay->where($arr)	
			->join('join cms_member on cms_member.user_id = cms_employee_pay.user_id')
			->join("join cms_shop on cms_shop.shop_id=cms_employee_pay.shop_id")->count();
        $p = new Page($count, 20);
        $employee_pay_arr = $employee_pay->join("join cms_shop on cms_shop.shop_id=cms_employee_pay.shop_id")
			->join('join cms_member on cms_member.user_id = cms_employee_pay.user_id')
			->limit($p->firstRow . ',' . $p->listRows)->where($arr)->order('cms_employee_pay.id desc')->select();
        //echo $employee_pay->getLastSql();
        /* echo '<pre>';
         print_r($user_arr);*/
		 $shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
		
        $page = $p->show();
        $this->assign("page", $page);
        $this->assign('employee_pay_arr', $employee_pay_arr);
        $this->display();
    }

     public function employeepay_add()
    {
        if ($_POST)
        {
            #$data['input_date'] 			= $_POST['input_date'];
			$data['shop_id'] 			= $_POST['shop_id'];
			$data['is_used'] 			= $_POST['is_used'];
			$data['user_id'] 			= $_POST['user_id'];
			$data['gongzi'] 			= $_POST['gongzi'];
			$data['butie'] 				= $_POST['butie'];
			$data['lianghua'] 			= $_POST['lianghua'];
			$data['yingchuqin'] 		= $_POST['yingchuqin'];
			$data['shichuqin'] 			= $_POST['shichuqin'];
			$data['kaoqinggongzi'] 		= $_POST['kaoqinggongzi'];
			$data['jiaban'] 			= $_POST['jiaban'];
			$data['jibanfei'] 			= $_POST['jibanfei'];
			$data['tichenge'] 			= $_POST['tichenge'];
			$data['yewuchengfa'] 		= $_POST['yewuchengfa'];
			$data['xingzhengchufa'] 	= $_POST['xingzhengchufa'];
			$data['canbu'] 				= $_POST['canbu'];
			$data['jiaotong'] 			= $_POST['jiaotong'];
			$data['zhusu'] 				= $_POST['zhusu'];
			$data['daidiankuan'] 		= $_POST['daidiankuan'];
			$data['daikoufei'] 			= $_POST['daikoufei'];
			$data['daijiaoshebao'] 		= $_POST['daijiaoshebao'];
			$data['shifagongzi'] 		= $_POST['shifagongzi'];
			$data['creater']			= $_SESSION['USER_NAME'];
			$data['input_date']			= $_POST['input_date'];
			
            $employee_pay = M("employee_pay");
            $rs = $employee_pay->add($data);
			//echo $employee_pay->getLastSql();
			//die();

            if ($rs !== false)
            {
                $this->redirect('/employeepay/employeepay_list');
                exit;
            }
        }
		
		$shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
		
		$member = M("member");
		$user_arr = $member->join('join cms_member_role on cms_member.user_id = cms_member_role.member_id and cms_member_role.role_id = 3')->select();
		$this->assign('user_arr', $user_arr);
		
        $this->display();
    }

    public function employeepay_edit()
    {
        $id = $_GET['id'];
        if ($_POST)
        {
			$data['shop_id'] 			= $_POST['shop_id'];
			$data['is_used'] 			= $_POST['is_used'];
			$data['user_id'] 			= $_POST['user_id'];
			$data['gongzi'] 			= $_POST['gongzi'];
			$data['butie'] 				= $_POST['butie'];
			$data['lianghua'] 			= $_POST['lianghua'];
			$data['yingchuqin'] 		= $_POST['yingchuqin'];
			$data['shichuqin'] 			= $_POST['shichuqin'];
			$data['kaoqinggongzi'] 		= $_POST['kaoqinggongzi'];
			$data['jiaban'] 			= $_POST['jiaban'];
			$data['jibanfei'] 			= $_POST['jibanfei'];
			$data['tichenge'] 			= $_POST['tichenge'];
			$data['yewuchengfa'] 		= $_POST['yewuchengfa'];
			$data['xingzhengchufa'] 	= $_POST['xingzhengchufa'];
			$data['canbu'] 				= $_POST['canbu'];
			$data['jiaotong'] 			= $_POST['jiaotong'];
			$data['zhusu'] 				= $_POST['zhusu'];
			$data['daidiankuan'] 		= $_POST['daidiankuan'];
			$data['daikoufei'] 			= $_POST['daikoufei'];
			$data['daijiaoshebao'] 		= $_POST['daijiaoshebao'];
			$data['shifagongzi'] 		= $_POST['shifagongzi'];
			$data['input_date']			= $_POST['input_date'];
			
			$employee_pay = M("employee_pay");
			$rs = $employee_pay->where('id = '.$id)->save($data);
			//echo $employee_pay->getLastSql();
			//die();

            if ($rs !== false)
            {
                $this->redirect('/employeepay/employeepay_list');
                exit;
            }
        }
		
		$shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
		
		$employee_pay = M("employee_pay");
		$result = $employee_pay
			->join('join cms_member on cms_member.user_id = cms_employee_pay.user_id')
			->join("join cms_shop on cms_shop.shop_id=cms_employee_pay.shop_id")
		->where('id = '.$id)->find();
        //echo $employee_pay->getLastSql();
		
		$member = M("member");
		$user_arr = $member->join('join cms_member_role on cms_member.user_id = cms_member_role.member_id and cms_member_role.role_id = 3')->select();
        //echo '<pre>';print_r($user_arr);
		$this->assign('user_arr', $user_arr);
		//echo $member->getLastSql();
		$this->assign('arr', $result);
		
        $this->display();
    }

    public function employeepay_delete()
    {
        $id = $_GET['id'];
        $employee_pay = M("employee_pay");
        $rs = $employee_pay->where('id = '.$id)->delete();
        if ($rs !== false)
        {
			$this->redirect('/employeepay/employeepay_list');
        }
    }
	
	function export_csv()
	{
		$employee_pay = M('employee_pay');
        $arr=" 1 = 1 ";
		
		$shop_id = $_POST['shop_id'];
		if ($shop_id){
			$arr .= ' and cms_employee_pay.shop_id ='.$shop_id;
		}
		
		$is_used = $_POST['is_used'];
		if (isset($is_used) && $is_used <> 8){
			$arr .= ' and is_used = "'.$is_used.'"';
		}
		
		$input_date_start = $_POST['input_date_start'];
		if ($input_date_start){
			$arr .= ' and input_date >= "'.$input_date_start.'"';
		}
		
		$input_date_end = $_POST['input_date_end'];
		if ($input_date_end){
			$arr .= ' and input_date <="'.$input_date_end.'"';
		}
       
        $datalist = $employee_pay->join("join cms_shop on cms_shop.shop_id=cms_employee_pay.shop_id")
			->join('join cms_member on cms_member.user_id = cms_employee_pay.user_id')
			->field('cms_member.*,cms_shop.shop_name,cms_employee_pay.*')
			->where($arr)->select();
			
		 for($i =0; $i < count($datalist); $i++){
            $datalist[$i]['is_used'] = $datalist[$i]['is_used'] == '0' ? '是' : '否';
        }
			
		$filename = "export_csv_".date('Y-m-d').".csv";
        $head = array('id'=>'编号',
            'input_date'=>'日期',
            'shop_name'=>'门店',
			'nickname' => '姓名',
			'zhiwu'=>'职务',
            'gongzi'=>'标准工资',
            'butie'=>'职务补贴',
            'lianghua'=>'量化考核',
            'yingchuqin'=>'应出勤',
            'shichuqin'=>'实出勤',
            'kaoqinggongzi'=>'考勤工资',
            'jiaban'=>'加班',
            'jibanfei'=>'加班费',
            'tichenge'=>'提成额',
            'yewuchengfa'=>'业务奖罚',
            'xingzhengchufa'=>'行政奖罚',
			'canbu'=>'餐补',
			'jiaotong'=>'交通',
			'zhusu'=>'住宿',
			'daidiankuan'=>'代垫款',
			'daikoufei'=>'代扣费',
			'daijiaoshebao'=>'代缴社保',
			'shifagongzi'=>'实发工资'
        );
        $this->download_xls($filename,$head,$datalist);
	}
}
?>