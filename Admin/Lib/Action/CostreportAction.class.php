<?php

/**
 * Class CostreportAction
 * @title 成本管理报表
 * @author zhi.li
 * @create on 2015-09-27
 */
class CostreportAction extends CommonAction
{
    public function costreport_list()
    {
        import('@.ORG.Page');

        if ($_POST['export']){
            $this->export_csv();
            exit;
        }

        $cost = M('cost');
        
		$arr=" 1 = 1 ";
		
		$shop_id = $_POST['shop_id'];
		if ($shop_id){
			$arr .= ' and cms_cost.shop_id ='.$shop_id;
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
		
        $count = $cost->where($arr)->join("join cms_shop on cms_shop.shop_id=cms_cost.shop_id")->count();
        $p = new Page($count, 20);
        $cost_arr = $cost->join("join cms_shop on cms_shop.shop_id=cms_cost.shop_id")->limit($p->firstRow . ',' . $p->listRows)->where($arr)->order('cms_cost.id desc')->select();
        //echo $cost->getLastSql();
        /* echo '<pre>';
         print_r($user_arr);*/
        $page = $p->show();
		
		$shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
		
        $this->assign("page", $page);
        $this->assign('cost_arr', $cost_arr);
        $this->display();
    }

    public function costreport_add()
    {
        if ($_POST)
        {
            $data['input_date'] 		= $_POST['input_date'];
			$data['shop_id'] 			= $_POST['shop_id'];
			$data['is_used'] 			= $_POST['is_used'];
			$data['price'] 				= $_POST['price'];
			$data['discount'] 			= $_POST['discount'];
			$data['processing_fee'] 	= $_POST['processing_fee'];
			$data['jingpian_price'] 	= $_POST['jingpian_price'];
			$data['jingpian_discount'] 	= $_POST['jingpian_discount'];
			$data['spending'] 			= $_POST['spending'];
			/* $data['other_price'] 		= $_POST['other_price']; */
			$data['taxes_fee'] 			= $_POST['taxes_fee'];
			$data['financial_cost'] 	= $_POST['financial_cost'];
			$data['remark'] 			= $_POST['remark'];
			$data['creater']			= $_SESSION['USER_NAME'];
			
			//
			$data['int_spending'] = $_POST['int_spending'] ? $_POST['int_spending'] : 0;
			$data['int_taxes_fee'] = $_POST['int_taxes_fee'] ? $_POST['int_taxes_fee'] : 0;
			$data['int_financial_cost'] = $_POST['int_financial_cost'] ? $_POST['int_financial_cost'] : 0;
			$data['int_processing_fee'] = $_POST['int_processing_fee'] ? $_POST['int_processing_fee'] : 0;
			
            $cost = M("cost");
            $rs = $cost->add($data);

            if ($rs !== false)
            {
                $this->redirect('/costreport/costreport_list');
                exit;
            }
        }
		
		$shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
        $this->display();
    }

    public function costreport_edit()
    {
        $id = $_GET['id'];
        if ($_POST)
        {
            $data['input_date'] 		= $_POST['input_date'];
			$data['shop_id'] 			= $_POST['shop_id'];
			$data['is_used'] 			= $_POST['is_used'];
			$data['price'] 				= $_POST['price'];
			$data['discount'] 			= $_POST['discount'];
			$data['processing_fee'] 	= $_POST['processing_fee'];
			$data['jingpian_price'] 	= $_POST['jingpian_price'];
			$data['jingpian_discount'] 	= $_POST['jingpian_discount'];
			$data['spending'] 			= $_POST['spending'];
			/* $data['other_price'] 		= $_POST['other_price']; */
			$data['taxes_fee'] 			= $_POST['taxes_fee'];
			$data['financial_cost'] 	= $_POST['financial_cost'];
			$data['remark'] 			= $_POST['remark'];
			
			//
			$data['int_spending'] = $_POST['int_spending'] ? $_POST['int_spending'] : 0;
			$data['int_taxes_fee'] = $_POST['int_taxes_fee'] ? $_POST['int_taxes_fee'] : 0;
			$data['int_financial_cost'] = $_POST['int_financial_cost'] ? $_POST['int_financial_cost'] : 0;
			$data['int_processing_fee'] = $_POST['int_processing_fee'] ? $_POST['int_processing_fee'] : 0;
			
            $cost = M("cost");
            $rs = $cost->where('id = '.$id)->save($data);
			//echo $cost->getLastSql();
			//die();

            if ($rs !== false)
            {
                $this->redirect('/costreport/costreport_list');
                exit;
            }
        }
		
		$shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
		
		$cost = M("cost");
		$result = $cost->where('id = '.$id)->find();
		$this->assign('arr', $result);
		
        $this->display();
    }

    public function costreport_delete()
    {
        $id = $_GET['id'];
        $cost = M("cost");
        $rs = $cost->where('id = '.$id)->delete();
        if ($rs !== false)
        {
            $this->redirect('/costreport/costreport_list');
        }
    }

    public function export_csv()
    {
        $cost = M('cost');

        $arr=" 1 = 1 ";

        $shop_id = $_POST['shop_id'];
        if ($shop_id){
            $arr .= ' and cms_cost.shop_id ='.$shop_id;
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

        $datalist = $cost->join("join cms_shop on cms_shop.shop_id=cms_cost.shop_id")
		->field('cms_shop.*,cms_cost.*')
		->where($arr)->select();

        for($i =0; $i < count($datalist); $i++){
            $datalist[$i]['is_used'] = $datalist[$i]['is_used'] == '0' ? '是' : '否';
        }
        $filename = "export_csv_".date('Y-m-d').".csv";
        $head = array('id'=>'编号',
            'input_date'=>'日期',
            'shop_name'=>'门店',
            'is_used'=>'参与计算',
            'price'=>'产品成本（定价）',
            'discount'=>'产品成本（折扣后）',
            'processing_fee'=>'加工费',
            'jingpian_price'=>'镜片成本（定价）',
            'jingpian_discount'=>'镜片成本（折扣后）',
            'spending'=>'营业外支出',
            'other_price'=>'其他业务成本',
            'taxes_fee'=>'营业税费',
            'financial_cost'=>'财务费用',
            'remark'=>'备注'
        );
        //$this->download_csv($filename,$head,$datalist);
		$this->download_xls($filename,$head,$datalist);
    }
}
?>