<?php

/**
 * Class ManagefeeAction
 * @title 管理费用报表
 * @author zhi.li
 * @create on 2015-09-28
 */
class ManagefeeAction extends CommonAction
{
    public function managefee_list()
    {
        import('@.ORG.Page');
		
		if ($_POST['export']){
			$this->export_csv();
			exit;
		}

        $manage_fee = M('manage_fee');
        
		$arr=" 1 = 1 ";
		
		$shop_id = $_POST['shop_id'];
		if ($shop_id){
			$arr .= ' and cms_manage_fee.shop_id ='.$shop_id;
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
		
        $count = $manage_fee->where($arr)->join("join cms_shop on cms_shop.shop_id=cms_manage_fee.shop_id")->count();
        $p = new Page($count, 20);
        $manage_fee_arr = $manage_fee->join("join cms_shop on cms_shop.shop_id=cms_manage_fee.shop_id")->order('cms_manage_fee.id desc')->limit($p->firstRow . ',' . $p->listRows)->where($arr)->select();
        //echo $cost->getLastSql();
        /* echo '<pre>';
         print_r($user_arr);*/
		$shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
		
        $page = $p->show();
        $this->assign("page", $page);
        $this->assign('manage_fee_arr', $manage_fee_arr);
        $this->display();
    }

    public function managefee_add()
    {
        if ($_POST)
        {
            $data['input_date'] 		= $_POST['input_date'];
			$data['shop_id'] 			= $_POST['shop_id'];
			$data['orderno'] 			= $_POST['orderno'];
			$data['zhaiyao'] 			= $_POST['zhaiyao'];
			$data['baoxian_fee'] 		= $_POST['baoxian_fee'];
			$data['bangong_fee'] 		= $_POST['bangong_fee'];
			$data['tongxun_fee'] 		= $_POST['tongxun_fee'];
			$data['chailv_fee'] 		= $_POST['chailv_fee'];
			$data['fuli_fee'] 			= $_POST['fuli_fee'];
			$data['zhaodai_fee'] 		= $_POST['zhaodai_fee'];
			$data['zhijian_fee'] 		= $_POST['zhijian_fee'];
			$data['car_fee'] 			= $_POST['car_fee'];
			$data['peixun_fee'] 		= $_POST['peixun_fee'];
			$data['zhengjian_fee'] 		= $_POST['zhengjian_fee'];
			$data['gongguan_fee'] 		= $_POST['gongguan_fee'];
			$data['zhejiu_fee'] 		= $_POST['zhejiu_fee'];
			$data['guanli_fee'] 		= $_POST['guanli_fee'];
			$data['gongtan_fee'] 		= $_POST['gongtan_fee'];
			$data['qita_fee'] 			= $_POST['qita_fee'];
			$data['remark'] 			= $_POST['remark'];
			$data['creater']			= $_SESSION['USER_NAME'];
			
			//
			$data['int_baoxian_fee'] 	= $_POST['int_baoxian_fee'] ? $_POST['int_baoxian_fee'] : 0;
			$data['int_bangong_fee'] 	= $_POST['int_bangong_fee'] ? $_POST['int_bangong_fee'] : 0;
			$data['int_tongxun_fee'] 	= $_POST['int_tongxun_fee'] ? $_POST['int_tongxun_fee'] : 0;
			$data['int_chailv_fee'] 	= $_POST['int_chailv_fee'] ? $_POST['int_chailv_fee'] : 0;
			$data['int_fuli_fee'] 		= $_POST['int_fuli_fee'] ? $_POST['int_fuli_fee'] : 0;
			$data['int_zhaodai_fee'] 	= $_POST['int_zhaodai_fee'] ? $_POST['int_zhaodai_fee'] : 0;
			$data['int_car_fee'] 		= $_POST['int_car_fee'] ? $_POST['int_car_fee'] : 0;
			$data['int_peixun_fee'] 	= $_POST['int_peixun_fee'] ? $_POST['int_peixun_fee'] : 0;
			$data['int_zhengjian_fee'] 	= $_POST['int_zhengjian_fee'] ? $_POST['int_zhengjian_fee'] : 0;
			$data['int_zhijian_fee'] 	= $_POST['int_zhijian_fee'] ? $_POST['int_zhijian_fee'] : 0;
			$data['int_gongg'] 			= $_POST['int_gongg'] ? $_POST['int_gongg'] : 0;
			$data['int_zhejiu_fee'] 	= $_POST['int_zhejiu_fee'] ? $_POST['int_zhejiu_fee'] : 0;
			$data['int_guanli_fee'] 	= $_POST['int_guanli_fee'] ? $_POST['int_guanli_fee'] : 0;
			$data['int_gongtan_fee'] 	= $_POST['int_gongtan_fee'] ? $_POST['int_gongtan_fee'] : 0;
			$data['int_qita_fee'] 		= $_POST['int_qita_fee'] ? $_POST['int_qita_fee'] : 0;
			
            $cost = M("manage_fee");
            $rs = $cost->add($data);

            if ($rs !== false)
            {
                $this->redirect('/managefee/managefee_list');
                exit;
            }
        }
		
		$shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
        $this->display();
    }

    public function managefee_edit()
    {
        $id = $_GET['id'];
        if ($_POST)
        {
            $data['input_date'] 		= $_POST['input_date'];
			$data['shop_id'] 			= $_POST['shop_id'];
			$data['orderno'] 			= $_POST['orderno'];
			$data['zhaiyao'] 			= $_POST['zhaiyao'];
			$data['baoxian_fee'] 		= $_POST['baoxian_fee'];
			$data['bangong_fee'] 		= $_POST['bangong_fee'];
			$data['tongxun_fee'] 		= $_POST['tongxun_fee'];
			$data['chailv_fee'] 		= $_POST['chailv_fee'];
			$data['fuli_fee'] 			= $_POST['fuli_fee'];
			$data['zhaodai_fee'] 		= $_POST['zhaodai_fee'];
			$data['car_fee'] 			= $_POST['car_fee'];
			$data['peixun_fee'] 		= $_POST['peixun_fee'];
			$data['zhengjian_fee'] 		= $_POST['zhengjian_fee'];
			$data['zhijian_fee'] 		= $_POST['zhijian_fee'];
			$data['gongguan_fee'] 		= $_POST['gongguan_fee'];
			$data['zhejiu_fee'] 		= $_POST['zhejiu_fee'];
			$data['guanli_fee'] 		= $_POST['guanli_fee'];
			$data['gongtan_fee'] 		= $_POST['gongtan_fee'];
			$data['qita_fee'] 			= $_POST['qita_fee'];
			$data['remark'] 			= $_POST['remark'];
			
			//
			$data['int_baoxian_fee'] 	= $_POST['int_baoxian_fee'] ? $_POST['int_baoxian_fee'] : 0;
			$data['int_bangong_fee'] 	= $_POST['int_bangong_fee'] ? $_POST['int_bangong_fee'] : 0;
			$data['int_tongxun_fee'] 	= $_POST['int_tongxun_fee'] ? $_POST['int_tongxun_fee'] : 0;
			$data['int_chailv_fee'] 	= $_POST['int_chailv_fee'] ? $_POST['int_chailv_fee'] : 0;
			$data['int_fuli_fee'] 		= $_POST['int_fuli_fee'] ? $_POST['int_fuli_fee'] : 0;
			$data['int_zhaodai_fee'] 	= $_POST['int_zhaodai_fee'] ? $_POST['int_zhaodai_fee'] : 0;
			$data['int_car_fee'] 		= $_POST['int_car_fee'] ? $_POST['int_car_fee'] : 0;
			$data['int_peixun_fee'] 	= $_POST['int_peixun_fee'] ? $_POST['int_peixun_fee'] : 0;
			$data['int_zhengjian_fee'] 	= $_POST['int_zhengjian_fee'] ? $_POST['int_zhengjian_fee'] : 0;
			$data['int_zhijian_fee'] 	= $_POST['int_zhijian_fee'] ? $_POST['int_zhijian_fee'] : 0;
			$data['int_gongg'] 			= $_POST['int_gongg'] ? $_POST['int_gongg'] : 0;
			$data['int_zhejiu_fee'] 	= $_POST['int_zhejiu_fee'] ? $_POST['int_zhejiu_fee'] : 0;
			$data['int_guanli_fee'] 	= $_POST['int_guanli_fee'] ? $_POST['int_guanli_fee'] : 0;
			$data['int_gongtan_fee'] 	= $_POST['int_gongtan_fee'] ? $_POST['int_gongtan_fee'] : 0;
			$data['int_qita_fee'] 		= $_POST['int_qita_fee'] ? $_POST['int_qita_fee'] : 0;
			
            $manage_fee = M("manage_fee");
            $rs = $manage_fee->where('id = '.$id)->save($data);
			//echo $manage_fee->getLastSql();
			//die();

            if ($rs !== false)
            {
                $this->redirect('/managefee/managefee_list');
                exit;
            }
        }
		
		$shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
		
		$manage_fee = M("manage_fee");
		$result = $manage_fee->where('id = '.$id)->find();
		$this->assign('arr', $result);
		
        $this->display();
    }

    public function managefee_delete()
    {
        $id = $_GET['id'];
        $cost = M("manage_fee");
        $rs = $cost->where('id = '.$id)->delete();
        if ($rs !== false)
        {
            $this->redirect('/managefee/managefee_list');
        }
    }
	
	function export_csv()
	{
		$manage_fee = M('manage_fee');
        
		$arr=" 1 = 1 ";
		
		$shop_id = $_POST['shop_id'];
		if ($shop_id){
			$arr .= ' and cms_manage_fee.shop_id ='.$shop_id;
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
		
        $datalist = $manage_fee->join("join cms_shop on cms_shop.shop_id=cms_manage_fee.shop_id")
		->field('cms_shop.shop_name,cms_manage_fee.*')
		->where($arr)->select();
              
		$filename = "export_csv_".date('Y-m-d').".csv";
        $head = array('id'=>'编号',
            'input_date'=>'日期',
            'shop_name'=>'门店',
            'baoxian_fee'=>'保险费用',
            'bangong_fee'=>'办公费',
            'tongxun_fee'=>'通讯费',
            'chailv_fee'=>'差旅费',
            'fuli_fee'=>'福利费',
            'zhaodai_fee'=>'招待费',
            'car_fee'=>'车辆使用',
            'peixun_fee'=>'培训及咨询费',
            'zhengjian_fee'=>'证照费用',
            'zhijian_fee'=>'质检年检',
            'gongguan_fee'=>'公关费用',
			'zhejiu_fee'=>'折旧及摊销',
			'guanli_fee'=>'管理服务',
			'gongtan_fee'=>'公摊费用',
			'qita_fee'=>'其它'
        );
      $this->download_xls($filename,$head,$datalist);
	}
}
?>