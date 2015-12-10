<?php

/**
 * Class IncomestatementAction
 * @title 收入管理报表
 * @author zhi.li
 * @create on 2015-09-24
 */
class IncomestatementAction extends CommonAction
{
    public function incomestatement_list()
    {
        import('@.ORG.Page');

        if ($_POST['export']){
			$this->export_csv();
			exit;
		}

        $incomestatement = M('incomestatement');
        $arr=" 1 = 1 ";
		
		$shop_id = $_POST['shop_id'];
		if ($shop_id){
			$arr .= ' and cms_incomestatement.shop_id ='.$shop_id;
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
		
        $count = $incomestatement->where($arr)->join("join cms_shop on cms_shop.shop_id=cms_incomestatement.shop_id")->count();
        $p = new Page($count, 20);
        $incomestatement_arr = $incomestatement->join("join cms_shop on cms_shop.shop_id=cms_incomestatement.shop_id")
		->limit($p->firstRow . ',' . $p->listRows)
		->where($arr)
		->order('cms_incomestatement.id desc')
		->select();
        //echo $incomestatement->getLastSql();
        /* echo '<pre>';
         print_r($user_arr);*/
        $page = $p->show();
        $this->assign("page", $page);
		
		$shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
		
        $this->assign('incomestatement_arr', $incomestatement_arr);
        $this->display();
    }

    public function incomestatement_add()
    {
        if ($_POST)
        {
            $data['input_date'] 		= $_POST['input_date'];
			$data['shop_id'] 			= $_POST['shop_id'];
			$data['is_used'] 			= $_POST['is_used'];
			$data['price'] 				= $_POST['price'];
			$data['discount'] 			= $_POST['discount'];
			/* $data['other_price'] 		= $_POST['other_price']; */
			$data['price_change'] 		= $_POST['price_change'];
			$data['operating_income'] 	= $_POST['operating_income'];
			$data['subsidy_income'] 	= $_POST['subsidy_income'];
			$data['return_amount'] 		= $_POST['return_amount'];
			$data['remark'] 			= $_POST['remark'];
			$data['creater']			= $_SESSION['USER_NAME'];
			
			//
			$data['int_price_change'] = $_POST['int_price_change'] ? $_POST['int_price_change'] : 0;
			$data['int_operating_income'] = $_POST['int_operating_income'] ? $_POST['int_operating_income'] : 0;
			$data['int_subsidy_income'] = $_POST['int_subsidy_income'] ? $_POST['int_subsidy_income'] : 0;
			$data['int_return_amount'] = $_POST['int_return_amount'] ? $_POST['int_return_amount'] : 0;
			
            $incomestatement = M("incomestatement");
            $rs = $incomestatement->add($data);

            if ($rs !== false)
            {
                $this->redirect('/incomestatement/incomestatement_list');
                exit;
            }
        }
		
		$shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
        $this->display();
    }

    public function incomestatement_edit()
    {
        $id = $_GET['id'];
        if ($_POST)
        {
            $data['input_date'] 		= $_POST['input_date'];
			$data['shop_id'] 			= $_POST['shop_id'];
			$data['is_used'] 			= $_POST['is_used'];
			$data['price'] 				= $_POST['price'];
			$data['discount'] 			= $_POST['discount'];
			/* $data['other_price'] 		= $_POST['other_price']; */
			$data['price_change'] 		= $_POST['price_change'];
			$data['operating_income'] 	= $_POST['operating_income'];
			$data['subsidy_income'] 	= $_POST['subsidy_income'];
			$data['return_amount'] 		= $_POST['return_amount'];
			$data['remark'] 			= $_POST['remark'];
			
			//
			$data['int_price_change'] = $_POST['int_price_change'] ? $_POST['int_price_change'] : 0;
			$data['int_operating_income'] = $_POST['int_operating_income'] ? $_POST['int_operating_income'] : 0;
			$data['int_subsidy_income'] = $_POST['int_subsidy_income'] ? $_POST['int_subsidy_income'] : 0;
			$data['int_return_amount'] = $_POST['int_return_amount'] ? $_POST['int_return_amount'] : 0;
			
            $incomestatement = M("incomestatement");
            $rs = $incomestatement->where('id = '.$id)->save($data);
			//echo $incomestatement->getLastSql();
			//die();

            if ($rs !== false)
            {
                $this->redirect('/incomestatement/incomestatement_list');
                exit;
            }
        }
		
		$shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
		
		$incomestatement = M("incomestatement");
		$result = $incomestatement->where('id = '.$id)->find();
		$this->assign('arr', $result);
		
        $this->display();
    }

    public function incomestatement_delete()
    {
        $id = $_GET['id'];
        $incomestatement = M("incomestatement");
        $rs = $incomestatement->where('id = '.$id)->delete();
        if ($rs !== false)
        {
            $this->redirect('/incomestatement/incomestatement_list');
        }
    }

    /**
     * @title 导出csv
     */
    public function export_csv()
    {
        $incomestatement = M('incomestatement');
        $arr=" 1 = 1 ";

        $shop_id = $_POST['shop_id'];
        if ($shop_id){
            $arr .= ' and cms_incomestatement.shop_id ='.$shop_id;
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

        $datalist = $incomestatement->join("join cms_shop on cms_shop.shop_id=cms_incomestatement.shop_id")
			->field('cms_shop.shop_name,cms_incomestatement.*')
            ->where($arr)
            ->order('cms_incomestatement.id desc')
            ->select();
        for($i =0; $i < count($datalist); $i++){
            $datalist[$i]['is_used'] = $datalist[$i]['is_used'] == '0' ? '是' : '否';
        }
        $filename = "export_csv_".date('Y-m-d').".csv";
        $head = array('id'=>'编号',
            'input_date'=>'日期',
            'shop_name'=>'门店',
            'is_used'=>'参与计算',
            'price'=>'营业收入(定价)',
            'discount'=>'营业收入(折扣后)',
            'other_price'=>'其他业务收入',
            'price_change'=>'公允价值变动收益',
            'operating_income'=>'营业外收入',
            'subsidy_income'=>'公司补贴收益',
            'return_amount'=>'加盟预算返还',
            'remark'=>'备注'
        );
        $this->download_xls($filename,$head,$datalist);
		//Excel::download_xls($filename, $head_array, $datalist);
    }
}
?>