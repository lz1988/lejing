<?php

/**
 * Class IncomestatementAction
 * @title 收入管理报表
 * @author zhi.li
 * @create on 2015-09-24
 */
class SellfeeAction extends CommonAction
{
    public function sellfee_list()
    {
        import('@.ORG.Page');
		
		if ($_POST['export']){
			$this->export_csv();
			exit;
		}

        $sell_fee = M('sell_fee');
        $arr=" 1 = 1 ";
		
		$shop_id = $_POST['shop_id'];
		if ($shop_id){
			$arr .= ' and cms_sell_fee.shop_id ='.$shop_id;
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
        $count = $sell_fee->where($arr)->join("join cms_shop on cms_shop.shop_id=cms_sell_fee.shop_id")->count();
        $p = new Page($count, 20);
        $sell_fee_arr = $sell_fee->join("join cms_shop on cms_shop.shop_id=cms_sell_fee.shop_id")->limit($p->firstRow . ',' . $p->listRows)->where($arr)->order('cms_sell_fee.id desc')->select();
        //echo $sell_fee->getLastSql();
        /* echo '<pre>';
         print_r($user_arr);*/
		 
		$shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
		
        $page = $p->show();
        $this->assign("page", $page);
        $this->assign('sell_fee_arr', $sell_fee_arr);
        $this->display();
    }

     public function sellfee_add()
    {
        if ($_POST)
        {
            $data['input_date'] 			= $_POST['input_date'];
			$data['shop_id'] 				= $_POST['shop_id'];
			$data['sellno'] 				= $_POST['sellno'];
			$data['zhaiyao'] 				= $_POST['zhaiyao'];
			$data['rent_money'] 			= $_POST['rent_money'];
			$data['transfer'] 				= $_POST['transfer'];
			$data['pr_cost'] 				= $_POST['pr_cost'];
			$data['shuidianweisheng_fee'] 	= $_POST['shuidianweisheng_fee'];
			$data['wuye_fee'] 				= $_POST['wuye_fee'];
			$data['advertising_fee'] 		= $_POST['advertising_fee'];
			$data['cuxiao_fee'] 			= $_POST['cuxiao_fee'];
			$data['xiaoshoufuliao_fee'] 	= $_POST['xiaoshoufuliao_fee'];
			$data['zhuangxiuweihu_fee'] 	= $_POST['zhuangxiuweihu_fee'];
			$data['shebeiweihu'] 			= $_POST['shebeiweihu'];
			$data['zhejiutuixiao'] 			= $_POST['zhejiutuixiao'];
			$data['jiameng'] 				= $_POST['jiameng'];
			$data['pinpaishiyong_fee'] 		= $_POST['pinpaishiyong_fee'];
			$data['jiameng_fee'] 			= $_POST['jiameng_fee'];
			$data['qita'] 					= $_POST['qita'];
			$data['remark'] 				= $_POST['remark'];
			$data['creater']				= $_SESSION['USER_NAME'];
			
			//
			$data['int_rent_money'] 			= $_POST['int_rent_money'] ? $_POST['int_rent_money'] : 0;
			$data['int_transfer'] 				= $_POST['int_transfer'] ? $_POST['int_transfer'] : 0;
			$data['int_pr_cost'] 				= $_POST['int_pr_cost'] ? $_POST['int_pr_cost'] : 0;
			$data['int_shuidianweisheng_fee'] 	= $_POST['int_shuidianweisheng_fee'] ? $_POST['int_shuidianweisheng_fee'] : 0;
			$data['int_wuye_fee'] 				= $_POST['int_wuye_fee'] ? $_POST['int_wuye_fee'] : 0;
			$data['int_advertising_fee'] 		= $_POST['int_advertising_fee'] ? $_POST['int_advertising_fee'] : 0;
			$data['int_cuxiao_fee'] 			= $_POST['int_cuxiao_fee'] ? $_POST['int_cuxiao_fee'] : 0;
			$data['int_xiaoshoufuliao_fee'] 	= $_POST['int_xiaoshoufuliao_fee'] ? $_POST['int_xiaoshoufuliao_fee'] : 0;
			$data['int_zhuangxiuweihu_fee'] 	= $_POST['int_zhuangxiuweihu_fee'] ? $_POST['int_zhuangxiuweihu_fee'] : 0;
			$data['int_shebeiweihu'] 			= $_POST['int_shebeiweihu'] ? $_POST['int_shebeiweihu'] : 0;
			$data['int_jiameng'] 				= $_POST['int_jiameng'] ? $_POST['int_jiameng'] : 0;
			$data['int_zhejiutuixiao'] 			= $_POST['int_zhejiutuixiao'] ? $_POST['int_zhejiutuixiao'] : 0;
			$data['int_pinpaishiyong_fee'] 		= $_POST['int_pinpaishiyong_fee'] ? $_POST['int_pinpaishiyong_fee'] : 0;
			$data['int_jiameng_fee'] 			= $_POST['int_jiameng_fee'] ? $_POST['int_jiameng_fee'] : 0;
			$data['int_qita'] 					= $_POST['int_qita'] ? $_POST['int_qita'] : 0;
			
            $sell_fee = M("sell_fee");
            $rs = $sell_fee->add($data);
			/*echo $sell_fee->getLastSql();
			die();*/

            if ($rs !== false)
            {
                $this->redirect('/sellfee/sellfee_list');
                exit;
            }
        }
		
		$shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
        $this->display();
    }

    public function sellfee_edit()
    {
        $id = $_GET['id'];
        if ($_POST)
        {
			$data['input_date'] 			= $_POST['input_date'];
			$data['shop_id'] 				= $_POST['shop_id'];
			$data['sellno'] 				= $_POST['sellno'];
			$data['zhaiyao'] 				= $_POST['zhaiyao'];
			$data['rent_money'] 			= $_POST['rent_money'];
			$data['transfer'] 				= $_POST['transfer'];
			$data['pr_cost'] 				= $_POST['pr_cost'];
			$data['shuidianweisheng_fee'] 	= $_POST['shuidianweisheng_fee'];
			$data['wuye_fee'] 				= $_POST['wuye_fee'];
			$data['advertising_fee'] 		= $_POST['advertising_fee'];
			$data['cuxiao_fee'] 			= $_POST['cuxiao_fee'];
			$data['xiaoshoufuliao_fee'] 	= $_POST['xiaoshoufuliao_fee'];
			$data['zhuangxiuweihu_fee'] 	= $_POST['zhuangxiuweihu_fee'];
			$data['shebeiweihu'] 			= $_POST['shebeiweihu'];
			$data['zhejiutuixiao'] 			= $_POST['zhejiutuixiao'];
			$data['pinpaishiyong_fee'] 		= $_POST['pinpaishiyong_fee'];
			$data['jiameng_fee'] 			= $_POST['jiameng_fee'];
			$data['qita'] 					= $_POST['qita'];
			$data['remark'] 				= $_POST['remark'];
			
			//
			$data['int_rent_money'] 			= $_POST['int_rent_money'] ? $_POST['int_rent_money'] : 0;
			$data['int_transfer'] 				= $_POST['int_transfer'] ? $_POST['int_transfer'] : 0;
			$data['int_pr_cost'] 				= $_POST['int_pr_cost'] ? $_POST['int_pr_cost'] : 0;
			$data['int_shuidianweisheng_fee'] 	= $_POST['int_shuidianweisheng_fee'] ? $_POST['int_shuidianweisheng_fee'] : 0;
			$data['int_wuye_fee'] 				= $_POST['int_wuye_fee'] ? $_POST['int_wuye_fee'] : 0;
			$data['int_advertising_fee'] 		= $_POST['int_advertising_fee'] ? $_POST['int_advertising_fee'] : 0;
			$data['int_cuxiao_fee'] 			= $_POST['int_cuxiao_fee'] ? $_POST['int_cuxiao_fee'] : 0;
			$data['int_xiaoshoufuliao_fee'] 	= $_POST['int_xiaoshoufuliao_fee'] ? $_POST['int_xiaoshoufuliao_fee'] : 0;
			$data['int_zhuangxiuweihu_fee'] 	= $_POST['int_zhuangxiuweihu_fee'] ? $_POST['int_zhuangxiuweihu_fee'] : 0;
			$data['int_shebeiweihu'] 			= $_POST['int_shebeiweihu'] ? $_POST['int_shebeiweihu'] : 0;
			$data['int_jiameng'] 				= $_POST['int_jiameng'] ? $_POST['int_jiameng'] : 0;
			$data['int_zhejiutuixiao'] 			= $_POST['int_zhejiutuixiao'] ? $_POST['int_zhejiutuixiao'] : 0;
			$data['int_pinpaishiyong_fee'] 		= $_POST['int_pinpaishiyong_fee'] ? $_POST['int_pinpaishiyong_fee'] : 0;
			$data['int_jiameng_fee'] 			= $_POST['int_jiameng_fee'] ? $_POST['int_jiameng_fee'] : 0;
			$data['int_qita'] 					= $_POST['int_qita'] ? $_POST['int_qita'] : 0;
			
			$sell_fee = M("sell_fee");
            $rs = $sell_fee->where('id = '.$id)->save($data);
			//echo $sell_fee->getLastSql();
			//die();

            if ($rs !== false)
            {
                $this->redirect('/sellfee/sellfee_list');
                exit;
            }
        }
		
		$shop = M("shop");
		$shop_arr = $shop->select();
		$this->assign('shop_arr', $shop_arr);
		
		$sell_fee = M("sell_fee");
		$result = $sell_fee->where('id = '.$id)->find();
		$this->assign('arr', $result);
		
        $this->display();
    }

    public function sellfee_delete()
    {
        $id = $_GET['id'];
        $sell_fee = M("sell_fee");
        $rs = $sell_fee->where('id = '.$id)->delete();
        if ($rs !== false)
        {
			$this->redirect('/sellfee/sellfee_list');
        }
    }
	
	function export_csv()
	{
		$sell_fee = M('sell_fee');
        $arr=" 1 = 1 ";
		
		$shop_id = $_POST['shop_id'];
		if ($shop_id){
			$arr .= ' and cms_sell_fee.shop_id ='.$shop_id;
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
		
        $datalist = $sell_fee->join("join cms_shop on cms_shop.shop_id=cms_sell_fee.shop_id")
		->field('cms-shop.shop_name,cms_sell_fee.*')
		->where($arr)->select();
        
		$filename = "export_csv_".date('Y-m-d').".csv";
        $head = array('id'=>'编号',
            'input_date'=>'日期',
            'shop_name'=>'门店',
            'sellno'=>'凭证单号',
            'rent_money'=>'房租',
            'transfer'=>'转让进场',
            'pr_cost'=>'公关费用',
            'shuidianweisheng_fee'=>'水电卫生费',
            'wuye_fee'=>'物业管理费',
            'advertising_fee'=>'广告宣传',
            'cuxiao_fee'=>'促销费',
            'xiaoshoufuliao_fee'=>'销售辅料',
            'zhuangxiuweihu_fee'=>'装修及维护',
            'shebeiweihu'=>'设备及维护',
			'zhejiutuixiao'=>'折旧及摊销',
			'jiameng'=>'加盟',
			'pinpaishiyong_fee'=>'品牌使用费',
			'jiameng_fee'=>'加盟商分成',
			'qita'=>'其它'
        );
        $this->download_xls($filename,$head,$datalist);
	}
}
?>