<?php

/**
 * Class IncomestatementAction
 * @title 收入管理报表
 * @author zhi.li
 * @create on 2015-09-24
 */
class ExpressfeeAction extends CommonAction
{
    public function expressfee_list()
    {
        import('@.ORG.Page');
		
		if ($_POST['export']){
			$this->export_csv();
			exit;
		}

        $express_fee = M('express_fee');
        
		$arr=" 1 = 1 ";
		
		$shop_id = $_POST['shop_id'];
		if ($shop_id){
			$arr .= ' and cms_express_fee.shop_id ='.$shop_id;
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
        $count = $express_fee->where($arr)->join("join cms_shop on cms_shop.shop_id=cms_express_fee.shop_id")->count();
        $p = new Page($count, 20);
        $express_fee_arr = $express_fee->join("join cms_shop on cms_shop.shop_id=cms_express_fee.shop_id")->limit($p->firstRow . ',' . $p->listRows)->order('cms_express_fee.id desc')->where($arr)->select();
        //echo $express_fee->getLastSql();
        /* echo '<pre>';
         print_r($user_arr);*/
		 
		 $shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
		
        $page = $p->show();
        $this->assign("page", $page);
        $this->assign('express_fee_arr', $express_fee_arr);
        $this->display();
    }

    public function expressfee_add()
    {
        if ($_POST)
        {
            $data['input_date'] 		= $_POST['input_date'];
			$data['shop_id'] 			= $_POST['shop_id'];
			$data['orderno'] 			= $_POST['orderno'];
			$data['zhaiyao'] 			= $_POST['zhaiyao'];
			$data['express'] 			= $_POST['express'];
			$data['track_no'] 			= $_POST['track_no'];
			$data['express_cost'] 		= $_POST['express_cost'];
			$data['share_cost'] 		= $_POST['share_cost'];
			$data['int_express_cost'] 	= $_POST['int_express_cost'] ? $_POST['int_express_cost'] : 0;
			$data['int_share_cost'] 	= $_POST['int_share_cost'] ? $_POST['int_share_cost'] : 0;
			$data['remark'] 			= $_POST['remark'];
			$data['creater']			= $_SESSION['USER_NAME'];
			
            $cost = M("express_fee");
            $rs = $cost->add($data);
			/* echo $cost->getLastSql();
			var_dump($cost->getError());
			die(); */
			

            if ($rs !== false)
            {
                $this->redirect('/expressfee/expressfee_list');
                exit;
            }
        }
		
		$shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
        $this->display();
    }

    public function expressfee_edit()
    {
        $id = $_GET['id'];
        if ($_POST)
        {
            $data['input_date'] 		= $_POST['input_date'];
			$data['shop_id'] 			= $_POST['shop_id'];
			$data['orderno'] 			= $_POST['orderno'];
			$data['zhaiyao'] 			= $_POST['zhaiyao'];
			$data['express'] 			= $_POST['express'];
			$data['track_no'] 			= $_POST['track_no'];
			$data['express_cost'] 		= $_POST['express_cost'];
			$data['share_cost'] 		= $_POST['share_cost'];
			$data['remark'] 			= $_POST['remark'];
			$data['int_express_cost'] 	= $_POST['int_express_cost'] ? $_POST['int_express_cost'] : 0;
			$data['int_share_cost'] 	= $_POST['int_share_cost'] ? $_POST['int_share_cost'] : 0;
            $cost = M("express_fee");
            $rs = $cost->where('id = '.$id)->save($data);
			//die($cost->getLastSql());
			//echo $cost->getLastSql();
			//die();

            if ($rs !== false)
            {
                $this->redirect('/expressfee/expressfee_list');
                exit;
            }
        }
		
		$shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
		
		$cost = M("express_fee");
		$result = $cost->where('id = '.$id)->find();
		$this->assign('arr', $result);
		
        $this->display();
    }

    public function expressfee_delete()
    {
        $id = $_GET['id'];
        $cost = M("express_fee");
        $rs = $cost->where('id = '.$id)->delete();
        if ($rs !== false)
        {
            $this->redirect('/expressfee/expressfee_list');
        }
    }
	
	function export_csv()
	{
		$express_fee = M('express_fee');
        
		$arr=" 1 = 1 ";
		
		$shop_id = $_POST['shop_id'];
		if ($shop_id){
			$arr .= ' and cms_express_fee.shop_id ='.$shop_id;
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
      
        $datalist = $express_fee->join("join cms_shop on cms_shop.shop_id=cms_express_fee.shop_id")
		->field('cms_shop.shop_name,cms_express_fee.*')
		->where($arr)->select();
       
		$filename = "export_csv_".date('Y-m-d').".csv";
        $head = array('id'=>'编号',
            'input_date'=>'日期',
            'shop_name'=>'门店',
            'sellno'=>'凭证单号',
            'express'=>'快递公司',
            'track_no'=>'快递单号',
            'express_cost'=>'自付快递费',
            'share_cost'=>'公摊快递费'
        );
		$this->download_xls($filename,$head,$datalist);
	}
}
?>