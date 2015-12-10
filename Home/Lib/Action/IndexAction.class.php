<?php
/**
 * @author
 * @uses 橡树平台首页文件
 *
 */

// 本类由系统自动生成，仅供测试用途
class IndexAction extends PublicAction {
	#$lng=113.95843019;      #经度(必填) 113.951385      莱科：22.559142035979,113.958430192661
	#	$lat=22.559142;   #纬度(必填)  22.5538
	/*
	 *  首页
	 */
    public function index(){
		if (!$_SESSION['user_id']){
			$this->redirect('/index/login');
			exit;
		}
		
		$postdate = $_POST['postdate'];
		if (!empty($postdate)){
			$date = $postdate;
		}else{
			$date = date('Y-m');
		}
		
		$date_arr = array(
			'0' => date('Y-m',strtotime("-2 month")),
			'1' => date('Y-m',strtotime("-1 month")),
			'2' => date('Y-m'),
		);
		
		$this->assign('datenow',$date);
		$this->assign('date_arr', $date_arr);
			
		$member = M("member");
		$rs = $member->where('user_id = '.$_SESSION['user_id'])
		->join("cms_user_shop on cms_member.user_id = cms_user_shop.userid")
		->join("cms_shop on cms_shop.shop_id = cms_user_shop.shop_id")
		->field('cms_shop.shop_id,cms_shop.shop_name')
		->select();
		
		$cost 				= M("cost");
		$incomestatement 	= M("incomestatement");
		$sell_fee 			= M("sell_fee");
		$manage_fee 		= M("manage_fee");
		$express_fee 		= M("express_fee");
		$employee_pay       = M("employee_pay");
		
		$sum_cost 				= 0;
		$sum_incomestatement 	= 0;
        $sum_sell_fee_shouye    = 0;
		$sum_manage_fee 		= 0;
		$sum_express_fee 		= 0;
		$sum_employee_pay  		= 0;
		
		$_sum_cost = 0;
		$_sum_incomestatement 	= 0;
        $_sum_sell_fee   = 0;
		$_sum_manage_fee 		= 0;
		$_sum_express_fee 		= 0;
		$_sum_employee_pay  	= 0;
		
		$_sum_all = 0;
		/* echo '<pre>';
		print_r($rs);    */
		for($i = 0; $i < count($rs); $i++)
		{
		
		//echo $rs[$i]['shop_id'].PHP_EOL;
			//获取role的权限
			$role_mode = M("role_mode");
			$rolemod = $role_mode->where('user_id = '.$_SESSION['user_id'].' and shop_id='.$rs[$i]['shop_id'])->select();
			//echo $role_mode->getLastSql();
			
			$newrolemode = array();
			foreach($rolemod as $v){
				$newrolemode[] = $v['mode_id'];
			}
			
			//统计成本
			$cost_arr = $cost->field('
                sum(price) as sumprice,
                sum(discount) as sumdiscount,
                sum(jingpian_price) as sumjingpian_price,
                sum(jingpian_discount) as sumjingpian_discount,

                sum(processing_fee) as sumprocessing_fee_all,
                sum(case when int_processing_fee = 1  then processing_fee else 0 end) as sumprocessing_fee,

                sum(spending) as sumspending_all,
                sum( case when int_spending = 1  then spending else 0 end) as sumspending,

                sum(taxes_fee) as sumtaxes_fee_all,
                sum(case when int_taxes_fee = 1  then taxes_fee else 0 end) as sumtaxes_fee,

                sum(financial_cost) as sumfinancial_cost_all,
                sum(case when int_financial_cost = 1  then financial_cost else 0 end) as sumfinancial_cost')
			->where('shop_id = '.$rs[$i]['shop_id'].'  and input_date like "%'.$date.'%"')->find();
			//echo $cost->getLastSql();
			
			$cost_sumprice = 0;
			$cost_sumdiscount = 0;
			$cost_sumjingpian_price = 0;
			$cost_jingpian_discount = 0;

			if (in_array(12,$newrolemode)){
				$cost_sumprice 			= $cost_arr['sumprice'];
				$cost_sumjingpian_price = $cost_arr['sumjingpian_price'];
			}
			
			if (in_array(13,$newrolemode)){
				$cost_sumdiscount 		= $cost_arr['sumdiscount'];
				$cost_jingpian_discount = $cost_arr['sumjingpian_discount'];
			}

            //加工费
            if (in_array(69,$newrolemode)){
                $sumprocessing_fee = $cost_arr['sumprocessing_fee'];
            }elseif (in_array(70,$newrolemode)){
                $sumprocessing_fee = $cost_arr['sumprocessing_fee_all'];
            }
            //营业外支
            if (in_array(24,$newrolemode)){
                $sumspending = $cost_arr['sumspending'];
            }elseif (in_array(25,$newrolemode)){
                $sumspending = $cost_arr['sumspending_all'];
            }
            //营业税费
            if (in_array(14,$newrolemode)){
                $sumtaxes_fee = $cost_arr['sumtaxes_fee'];
            }elseif (in_array(15,$newrolemode)){
                $sumtaxes_fee = $cost_arr['sumtaxes_fee_all'];
            }
            //财务费用
            if (in_array(20,$newrolemode)){
                $sumfinancial_cost = $cost_arr['sumfinancial_cost'];
            }elseif (in_array(21,$newrolemode)){
                $sumfinancial_cost = $cost_arr['sumfinancial_cost_all'];
            }

            if (in_array(52,$newrolemode) && $_SESSION['HOMEROLEID'] == 3)
            {
                $sum_cost = 0;
            }else {
                $sum_cost = $cost_sumprice //产品成本（定价）
                    + $cost_sumdiscount //产品成本（折扣后）
                    + $cost_sumjingpian_price//镜片成本（定价）
                    + $cost_jingpian_discount//镜片成本（折扣后）
                    + $sumprocessing_fee
                    + $sumspending
                    //+ $cost_arr['sumother_price']
                    + $sumtaxes_fee
                    + $sumfinancial_cost;
            }
            //var_dump($sum_cost);
			//var_dump(array($cost_sumprice,$cost_sumdiscount,$cost_arr['sumprocessing_fee'],$cost_sumjingpian_price,$cost_jingpian_discount,$cost_arr['sumspending'], $cost_arr['sumother_price'],$cost_arr['sumtaxes_fee'],$cost_arr['sumfinancial_cost']));
			
						
			//收入
			$sumprice 		= 0;
			$sumdiscount 	= 0;
			$sumprice_change = 0;
            $subsidy_income = 0;
            $return_amount = 0;
            $operating_income = 0;

            $incomestatement_arr = $incomestatement->field('
			sum(price) as sumprice,
			sum(discount) as sumdiscount,
			sum(case when int_price_change = 1 then  price_change  else 0 end) as sumprice_change,
			sum(price_change) as sumprice_change_all,
			sum(case when int_operating_income = 1 then operating_income else 0 end) as sumoperating_income,
			sum(operating_income) as sumoperating_income_all,
			sum(case when int_subsidy_income = 1 then subsidy_income else 0 end ) as sumsubsidy_income,
			sum(subsidy_income) as sumsubsidy_income_all,
			sum(case when int_return_amount = 1 then return_amount else 0 end) as sumreturn_amount,
			sum(return_amount) as sumreturn_amount_all
			')
			->where('shop_id = '.$rs[$i]['shop_id'].' and input_date like "%'.$date.'%"')->find();
			//echo $incomestatement->getLastSql();
			//sum(other_price) as sumother_price,

			if (in_array(10,$newrolemode)){
				$sumprice = $incomestatement_arr['sumprice'];

			}
			
			if (in_array(11,$newrolemode)){
				$sumdiscount = $incomestatement_arr['sumdiscount'];
			}

            //公允价值变动收益
			if (in_array(39,$newrolemode)){
				$sumprice_change = $incomestatement_arr['sumprice_change'];
			}elseif (in_array(40,$newrolemode)){
				$sumprice_change = $incomestatement_arr['sumprice_change_all'];
			}

            //公司对加盟商补贴
            if (in_array(42,$newrolemode)){
                $subsidy_income = $incomestatement_arr['sumsubsidy_income'];
            }elseif (in_array(43,$newrolemode)){
                $subsidy_income = $incomestatement_arr['sumsubsidy_income_all'];
            }

            //加盟预算费用返还
            if (in_array(45,$newrolemode)){
                $return_amount = $incomestatement_arr['sumreturn_amount'];
            }elseif (in_array(46,$newrolemode)){
                $return_amount = $incomestatement_arr['sumreturn_amount_all'];
            }

            //营业外收入
            if (in_array(22,$newrolemode)){
                $operating_income = $incomestatement_arr['sumoperating_income'];
            }elseif (in_array(23,$newrolemode)){
                $operating_income = $incomestatement_arr['sumoperating_income_all'];
            }

            if (in_array(49,$newrolemode) && $_SESSION['HOMEROLEID'] == 3)
            {
                $sum_incomestatement = 0;
            }else {
                $sum_incomestatement = $sumprice + $sumdiscount
                    + $sumprice_change
                    + $subsidy_income
                    + $return_amount
                    + $operating_income;
            }
            //echo '<pre>';var_dump(array($sumprice,$sumdiscount,$sumprice_change,$subsidy_income,$return_amount,$operating_income));



            //销售费用
            //$sum_sell_fee_shouye = 0;
			if (in_array(16,$newrolemode))
			{
            //sum( case when int_rent_money = 1 then rent_money else 0 end )as sumrent_money,
            $sell_fee_arr_shoye = $sell_fee->field('
					sum(case when int_transfer = 1  then transfer else 0  end ) as sumtransfer,
					sum(case when int_pr_cost = 1 then pr_cost else 0  end) as sumpr_cost,
					sum(case when int_shuidianweisheng_fee = 1 then shuidianweisheng_fee else 0  end) as sumshuidianweisheng_fee,
					sum(case when int_wuye_fee = 1 then wuye_fee else 0  end) as sumwuye_fee,
					sum(case when int_advertising_fee = 1 then advertising_fee else 0  end ) as sumadvertising_fee,
					sum(case when int_cuxiao_fee = 1 then cuxiao_fee else 0  end) as sumcuxiao_fee,
					sum(case when int_xiaoshoufuliao_fee = 1 then xiaoshoufuliao_fee else 0  end) as sumxiaoshoufuliao_fee,
					sum(case when int_zhuangxiuweihu_fee = 1 then zhuangxiuweihu_fee else 0  end) as sumzhuangxiuweihu_fee,
					sum(case when int_shebeiweihu then shebeiweihu else 0  end) as sumshebeiweihu,
					sum(case when int_zhejiutuixiao = 1 then zhejiutuixiao end) as sumzhejiutuixiao,
					sum(case when int_jiameng =1 then jiameng else 0  end ) as sumjiameng,
					sum(case when int_pinpaishiyong_fee =1 then pinpaishiyong_fee else 0  end) as sumpinpaishiyong_fee,
					sum(case when int_jiameng_fee = 1 then jiameng_fee else 0  end) as sumjiameng_fee,
					sum(case when int_qita =1 then qita else 0  end) as sumqita')
                ->where('input_date like "%'.$date.'%" and shop_id = '.$rs[$i]['shop_id'])->find();
            //echo $sell_fee->getLastSql()."<br/>";
            //var_dump($rs[$i]['shop_id']);
            //echo $rs[$i]['shop_id'].PHP_EOL;

			}
            if (in_array(17,$newrolemode))
			{
            //sum(rent_money)as sumrent_money,
            $sell_fee_arr_shoye = $sell_fee->field('

					sum(transfer) as sumtransfer,
					sum(pr_cost) as sumpr_cost,
					sum(shuidianweisheng_fee) as sumshuidianweisheng_fee,
					sum(wuye_fee) as sumwuye_fee,
					sum(advertising_fee) as sumadvertising_fee,
					sum(cuxiao_fee) as sumcuxiao_fee,
					sum(xiaoshoufuliao_fee) as sumxiaoshoufuliao_fee,
					sum(zhuangxiuweihu_fee) as sumzhuangxiuweihu_fee,
					sum(shebeiweihu) as sumshebeiweihu,
					sum(zhejiutuixiao) as sumzhejiutuixiao,
					sum(jiameng) as sumjiameng,
					sum(pinpaishiyong_fee) as sumpinpaishiyong_fee,
					sum(jiameng_fee) as sumjiameng_fee,
					sum(qita) as sumqita')
                ->where('input_date like "%'.$date.'%" and shop_id = '.$rs[$i]['shop_id'])->find();
            //echo $sell_fee->getLastSql();
			}
           /* echo '<pre>';
            print_r($rs[$i]['shop_id']);*/
        /*echo '<pre>';
         print_R($sell_fee_arr);*/
			//房租
			$rent_money = 0;
			if (in_array(30,$newrolemode))
			{
				$sell_fee_arr_rent_money = $sell_fee->field('sum( case when int_rent_money = 1 then rent_money else 0 end )as sumrent_money')
					->where('input_date like "%'.$date.'%" and shop_id = '.$rs[$i]['shop_id'])->find();
			}elseif (in_array(31,$newrolemode))
			{
				$sell_fee_arr_rent_money = $sell_fee->field('sum(rent_money)as sumrent_money')
					->where('input_date like "%'.$date.'%" and shop_id = '.$rs[$i]['shop_id'])->find();
			}
				//var_dump($rent_money);

			$rent_money = $sell_fee_arr_rent_money['sumrent_money'];
			//var_dump($sell_fee_arr_shoye);

            if (in_array(64,$newrolemode) && $_SESSION['HOMEROLEID'] == 3)
            {
                $sum_sell_fee_shouye = 0;
            }else {
                $sum_sell_fee_shouye = $rent_money
                    + $sell_fee_arr_shoye['sumtransfer']
                    + $sell_fee_arr_shoye['sumpr_cost']
                    + $sell_fee_arr_shoye['sumshuidianweisheng_fee']
                    + $sell_fee_arr_shoye['sumwuye_fee']
                    + $sell_fee_arr_shoye['sumadvertising_fee']
                    + $sell_fee_arr_shoye['sumcuxiao_fee']
                    + $sell_fee_arr_shoye['sumxiaoshoufuliao_fee']
                    + $sell_fee_arr_shoye['sumzhuangxiuweihu_fee']
                    + $sell_fee_arr_shoye['sumshebeiweihu']
                    + $sell_fee_arr_shoye['sumzhejiutuixiao']
                    + $sell_fee_arr_shoye['sumjiameng']
                    + $sell_fee_arr_shoye['sumpinpaishiyong_fee']
                    + $sell_fee_arr_shoye['sumjiameng_fee']
                    + $sell_fee_arr_shoye['sumqita'];
            }

			   /* echo '<pre>';
				var_dump($rent_money)." xxxxxxxxxxx";
					print_r(array($rent_money
					, $sell_fee_arr_shoye['sumtransfer']
					, $sell_fee_arr_shoye['sumpr_cost']
					, $sell_fee_arr_shoye['sumshuidianweisheng_fee']
					, $sell_fee_arr_shoye['sumwuye_fee']
					, $sell_fee_arr_shoye['sumadvertising_fee']
					, $sell_fee_arr_shoye['sumcuxiao_fee']
					, $sell_fee_arr_shoye['sumxiaoshoufuliao_fee']
					, $sell_fee_arr_shoye['sumzhuangxiuweihu_fee']
					, $sell_fee_arr_shoye['sumshebeiweihu']
					, $sell_fee_arr_shoye['sumzhejiutuixiao']
					, $sell_fee_arr_shoye['sumjiameng']
					, $sell_fee_arr_shoye['sumpinpaishiyong_fee']
					, $sell_fee_arr_shoye['sumjiameng_fee']
					, $sell_fee_arr_shoye['sumqita']));*/

					//echo '<pre>';
				//print_r($sum_sell_fee_shouye);
				//管理费用
				$manage_fee_arr = array();
				
				if (in_array(18,$newrolemode)){
					$manage_fee_arr = $manage_fee->field('
					sum(case when int_baoxian_fee = 1 then baoxian_fee else 0 end ) as sumbaoxian_fee,
					sum(case when int_bangong_fee = 1  then bangong_fee else 0 end ) as sumbangong_fee,
					sum(case when int_tongxun_fee = 1 then tongxun_fee else 0 end) as sumtongxun_fee,
					sum(case when int_chailv_fee = 1 then chailv_fee else 0 end) as sumchailv_fee,
					sum(case when int_fuli_fee = 1 then fuli_fee  else 0 end) as sumfuli_fee,
					sum(case when int_zhaodai_fee = 1 then zhaodai_fee else 0 end ) as sumzhaodai_fee,
					sum(case when int_car_fee = 1 then car_fee else 0 end) as sumcar_fee,
					sum(case when int_peixun_fee = 1 then peixun_fee else 0 end) as sumpeixun_fee,
					sum(case when int_zhengjian_fee = 1 then zhengjian_fee else 0 end) as sumzhengjian_fee,
					sum(case when int_zhijian_fee then zhijian_fee else 0 end) as sumzhijian_fee,
					sum(case when int_gongguan_fee = 1 then gongguan_fee else 0 end) as sumgongguan_fee,
					sum(case when int_zhejiu_fee =1 then zhejiu_fee else 0 end ) as sumzhejiu_fee,
					sum(case when int_guanli_fee = 1 then guanli_fee else 0 end ) as sumguanli_fee,
					sum(case when int_gongtan_fee = 1 then gongtan_fee else 0 end) as sumgongtan_fee,
					sum(case when int_qita_fee = 1 then qita_fee else 0 end) as sumqita')
					->where('input_date like "%'.$date.'%" and shop_id = '.$rs[$i]['shop_id'])->find();
					//echo $manage_fee->getLastSql();
				}
				elseif (in_array(19,$newrolemode)){
					$manage_fee_arr = $manage_fee->field('
					sum(baoxian_fee) as sumbaoxian_fee,
					sum(bangong_fee) as sumbangong_fee,
					sum(tongxun_fee) as sumtongxun_fee,
					sum(chailv_fee) as sumchailv_fee,
					sum(fuli_fee) as sumfuli_fee,
					sum(zhaodai_fee) as sumzhaodai_fee,
					sum(car_fee) as sumcar_fee,
					sum(peixun_fee) as sumpeixun_fee,
					sum(zhengjian_fee) as sumzhengjian_fee,
					sum(zhijian_fee) as sumzhijian_fee,
					sum(gongguan_fee) as sumgongguan_fee,
					sum(zhejiu_fee) as sumzhejiu_fee,
					sum(guanli_fee) as sumguanli_fee,
					sum(gongtan_fee) as sumgongtan_fee,
					sum(qita_fee) as sumqita')
					->where('input_date like "%'.$date.'%" and shop_id = '.$rs[$i]['shop_id'])->find();
					//echo $manage_fee->getLastSql();
				}
				
				//var_dump($manage_fee_arr);

            if (in_array(55,$newrolemode) && $_SESSION['HOMEROLEID'] == 3)
            {
                $sum_manage_fee = 0;
            }else {
                $sum_manage_fee = $manage_fee_arr['sumbaoxian_fee']
                    + $manage_fee_arr['sumbangong_fee']
                    + $manage_fee_arr['sumtongxun_fee']
                    + $manage_fee_arr['sumchailv_fee']
                    + $manage_fee_arr['sumfuli_fee']
                    + $manage_fee_arr['sumzhaodai_fee']
                    + $manage_fee_arr['sumcar_fee']
                    + $manage_fee_arr['sumpeixun_fee']
                    + $manage_fee_arr['sumzhengjian_fee']
                    + $manage_fee_arr['sumzhijian_fee']
                    + $manage_fee_arr['sumgongguan_fee']
                    + $manage_fee_arr['sumzhejiu_fee']
                    + $manage_fee_arr['sumguanli_fee']
                    + $manage_fee_arr['sumgongtan_fee']
                    + $manage_fee_arr['sumqita'];
            }
				
				//物流费用
				if (in_array(36,$newrolemode)){
					$express_fee_arr = $express_fee->field('
					sum( case when int_express_cost = 1 then express_cost else 0 end ) as sumexpress_cost,			
					sum(case when int_share_cost = 1 then share_cost else 0 end) as sumshare_cost')
					->where(' input_date like "%'.$date.'%" and shop_id = '.$rs[$i]['shop_id'])->find();
					//echo $express_fee->getLastSql();
				}
				elseif (in_array(37,$newrolemode)){
					$express_fee_arr = $express_fee->field('
					sum(express_cost) as sumexpress_cost,			
					sum(share_cost) as sumshare_cost')
					->where('input_date like "%'.$date.'%" and shop_id = '.$rs[$i]['shop_id'])->find();
				}
				//echo $express_fee->getLastSql();

            if (in_array(61,$newrolemode) && $_SESSION['HOMEROLEID'] == 3){
                $sum_express_fee = 0;
            }else {
                $sum_express_fee = $express_fee_arr['sumexpress_cost'] + $express_fee_arr['sumshare_cost'];
            }
				
				//员工工资
				$sum_employee_pay = 0;
				if (in_array(33,$newrolemode)){
					$employee_pay_arr = $employee_pay->field('sum(shifagongzi) as sumshifagongzi')
					->where(' input_date like "%'.$date.'%" and is_used = "0" and shop_id = '.$rs[$i]['shop_id'])->find();
				}
				elseif (in_array(34,$newrolemode))
				{
					$employee_pay_arr = $employee_pay->field('sum(shifagongzi) as sumshifagongzi')
					->where(' input_date like "%'.$date.'%" and shop_id = '.$rs[$i]['shop_id'])->find();
				}
				//echo $employee_pay->getLastSql();
            if (in_array(58,$newrolemode) && $_SESSION['HOMEROLEID'] == 3){
                $_sum_employee_pay = 0;
            }else {
                $sum_employee_pay = $employee_pay_arr['sumshifagongzi'];
            }
				//var_dump($_SESSION['HOMEROLEID']);
				$rs[$i]['sum_cost'] 					= $sum_cost > 0 ? number_format($sum_cost,2) : '';
				$rs[$i]['sum_cost_mode']				= ($_SESSION['HOMEROLEID'] == 3) ? (in_array(52,$newrolemode) ? 1 : 0) : 0;
				$_sum_cost += $sum_cost; 
				
				$rs[$i]['sum_incomestatement'] 			= $sum_incomestatement > 0 ?number_format($sum_incomestatement,2) : '';
				$rs[$i]['sum_incomestatement_mode'] 	= ($_SESSION['HOMEROLEID'] == 3) ? (in_array(49,$newrolemode) ? 1 : 0) : 0;
				$_sum_incomestatement += $sum_incomestatement;
				
				$rs[$i]['sum_sell_fee'] 				= $sum_sell_fee_shouye > 0 ? number_format($sum_sell_fee_shouye,2): '';
				$rs[$i]['sum_sell_fee_mode'] 			= ($_SESSION['HOMEROLEID'] == 3) ? (in_array(64,$newrolemode) ? 1 : 0) : 0;
				$_sum_sell_fee += $sum_sell_fee_shouye;
				
				$rs[$i]['sum_manage_fee'] 				= $sum_manage_fee > 0 ? number_format($sum_manage_fee,2): '';
				$rs[$i]['sum_manage_fee_mode'] 			= ($_SESSION['HOMEROLEID'] == 3) ? (in_array(55,$newrolemode) ? 1 : 0) : 0;
				$_sum_manage_fee += $sum_manage_fee;
				
				$rs[$i]['sum_express_fee']  			= $sum_express_fee > 0 ? number_format($sum_express_fee,2) : '';
				$rs[$i]['sum_express_fee_mode'] 		= ($_SESSION['HOMEROLEID'] == 3) ? (in_array(61,$newrolemode) ? 1 : 0) : 0;
				$_sum_express_fee += $sum_express_fee;
				
				$rs[$i]['sum_employee_pay'] 			= $sum_employee_pay > 0 ? number_format($sum_employee_pay,2) : '';
				$rs[$i]['sum_employee_pay_mode'] 		= ($_SESSION['HOMEROLEID'] == 3) ? (in_array(58,$newrolemode) ? 1 : 0) : 0;
				$_sum_employee_pay += $sum_employee_pay;
				
				$rs[$i]['zuizhong_mode'] 				= ($_SESSION['HOMEROLEID'] == 3) ? (in_array(67,$newrolemode) ? 1 : 0) : 0;
				
				
				$rs[$i]['sum_all'] = $rs[$i]['sum_cost'] + $rs[$i]['sum_incomestatement'] 
				+ $rs[$i]['sum_manage_fee'] 
				+ $rs[$i]['sum_sell_fee'] + $rs[$i]['sum_express_fee'] 
				+ $rs[$i]['sum_employee_pay'];
				$_sum_all += $rs[$i]['sum_all'];
			//echo $express_fee->getLastSql();
		}
		 /* echo '<pre>';
		print_r($rs);  */
		//echo $member->getLastSql();
		
		$this->assign('_sum_cost',$_sum_cost);
		$this->assign('_sum_incomestatement',$_sum_incomestatement);
        $this->assign('_sum_sell_fee',$_sum_sell_fee);
		$this->assign('_sum_manage_fee',$_sum_manage_fee); 		
		$this->assign('_sum_express_fee',$_sum_express_fee);
		$this->assign('_sum_employee_pay',$_sum_employee_pay);
		$this->assign('_sum_all',$_sum_all);
		$this->assign('rs', $rs);
		$this->display('home');
    }
	
	public function view()
	{
		if (!$_SESSION['user_id']){
			$this->redirect('/index/login');
			exit;
		}
		$shopId = $_GET['shop_id'];
		$date 	= $_GET['input_date'];
		$this->assign('date',date('Y年m月',strtotime($date)));
		
		$user_shop = M("user_shop");
		$usershop_arr = $user_shop->where('userid = '.$_SESSION['user_id'].' and shop_id = '.$shopId)->find();
		if (isset($usershop_arr['bili']) && !empty($usershop_arr['bili'])){
			$bili = $usershop_arr['bili'];
		}else{
			$bili = 0;
		}
		
		//获取role的权限
		$role_mode = M("role_mode");
		$rolemod = $role_mode->where('user_id = '.$_SESSION['user_id'].' and shop_id = '.$shopId)->select();
		$newrolemode = array();
		foreach($rolemod as $v){
			$newrolemode[] = $v['mode_id'];
		}
		
		$cost 				= M("cost");
		$incomestatement 	= M("incomestatement");
		$sell_fee 			= M("sell_fee");
		$manage_fee 		= M("manage_fee");
		$express_fee 		= M("express_fee");
		$employee_pay       = M("employee_pay");
		$shop 				= M("shop");
		$shoparr = $shop->where('shop_id = '.$shopId)->find();
		$this->assign('shoparr',$shoparr);
		
		//1.营业收入
		$yingyerice 	= 0;
		$incomestatement_arr = $incomestatement->field('sum(price) as sumprice,sum(discount) as sumdiscount')
			->where('shop_id = '.$shopId.' and input_date like "%'.$date.'%"')->find();
			//echo $incomestatement->getLastSql();
			
		if (in_array(10,$newrolemode)){
			$yingyerice = $incomestatement_arr['sumprice'];
		}
		
		if (in_array(11,$newrolemode)){
			$yingyerice = $incomestatement_arr['sumdiscount'];
		}
		$this->assign('yingyerice', $yingyerice);
			
		//2.产品成本及加工
		$chanpinchengben = 0;
		$cost_arr = $cost->field('sum(price) as sumprice,sum(discount) as sumdiscount,
		sum(processing_fee) as sumprocessing_fee_all,
		sum(case when int_processing_fee =1 then processing_fee else 0 end) as sumprocessing_fee,
		sum(jingpian_price) as sumjingpian_price,sum(jingpian_discount) as sumjingpian_discount')
		->where('shop_id = '.$shopId.' and input_date like "%'.$date.'%"')->find();
		//echo $cost->getLastSql();
		
		$cost_sumprice = 0;
		$cost_sumdiscount = 0;
		$cost_sumjingpian_price = 0;
		$cost_jingpian_discount = 0;
        $sumprocessing_fee = 0;
		
		/* echo '<pre>';
		print_r($newrolemode); */
		if (in_array(12,$newrolemode)){
			$cost_sumprice 			= $cost_arr['sumprice'];
			$cost_sumjingpian_price = $cost_arr['sumjingpian_price'];
			//die('xx');
		}
		
		if (in_array(13,$newrolemode)){
			$cost_sumdiscount 		= $cost_arr['sumdiscount'];
			$cost_jingpian_discount = $cost_arr['sumjingpian_discount'];
			//die('xx');
		}

        //加工费
        if (in_array(69,$newrolemode)){
            $sumprocessing_fee 	= $cost_arr['sumprocessing_fee'];
            //die('xx');
        }
        if (in_array(70,$newrolemode)){
            $sumprocessing_fee = $cost_arr['sumprocessing_fee_all'];
            //die('xx');
        }
		
		$chanpinchengben = $cost_sumprice //产品成本（定价）
		+ $cost_sumdiscount //产品成本（折扣后）
		+ $sumprocessing_fee
		+ $cost_sumjingpian_price//镜片成本（定价）
		+ $cost_jingpian_discount;//镜片成本（折扣后）
		$this->assign('chanpinchengben',$chanpinchengben);
		
		//3.营业税费
        $yingyeshuifei = 0;
        $cost_arr = $cost->field('sum(taxes_fee) as sumtaxes_fee_all,sum(case when int_taxes_fee = 1 then taxes_fee else 0 end ) as sumtaxes_fee')->where('shop_id = '.$shopId.' and input_date like "%'.$date.'%"')->find();
        //echo $cost->getLastSql();
		if (in_array(14,$newrolemode))
		{
            $yingyeshuifei = $cost_arr['sumtaxes_fee'];
		}
		elseif (in_array(15,$newrolemode))
		{
            $yingyeshuifei = $cost_arr['sumtaxes_fee_all'];
			//$cost_arr = $cost->field('sum(taxes_fee) as sumtaxes_fee')->where('shop_id = '.$shopId.' and input_date like "%'.$date.'%"')->find();
		}

		$this->assign('yingyeshuifei',$yingyeshuifei);
		
		//6.人员薪资
		$sum_employee_pay = 0;
		if (in_array(33,$newrolemode)){
			$employee_pay_arr = $employee_pay->field('sum(shifagongzi) as sumshifagongzi')
			->where(' input_date like "%'.$date.'%" and is_used = "0" and shop_id = '.$shopId)->find();
		}
		elseif (in_array(34,$newrolemode))
		{
			$employee_pay_arr = $employee_pay->field('sum(shifagongzi) as sumshifagongzi')
			->where(' input_date like "%'.$date.'%" and shop_id = '.$shopId)->find();
		}
		
		$sum_employee_pay = $employee_pay_arr['sumshifagongzi'];
		$this->assign('sum_employee_pay',$sum_employee_pay);
		
		//7.物流快递费
		$kuaidiwuliufei = 0;
		$sumexpress_cost 	= 0;
		$sumshare_cost 		= 0;
		if (in_array(36,$newrolemode)){
			$express_fee_arr = $express_fee->field('
			sum( case when int_express_cost = 1 then express_cost else 0 end ) as sumexpress_cost,			
			sum(case when int_share_cost = 1 then share_cost else 0 end) as sumshare_cost')
			->where(' input_date like "%'.$date.'%" and shop_id = '.$shopId)->find();
			$sumexpress_cost = $express_fee_arr['sumexpress_cost'];
			$sumshare_cost = $express_fee_arr['sumshare_cost'];
			//echo $express_fee->getLastSql();
		}
		elseif (in_array(37,$newrolemode)){
			$express_fee_arr = $express_fee->field('
			sum(express_cost) as sumexpress_cost,			
			sum(share_cost) as sumshare_cost')
			->where('input_date like "%'.$date.'%" and shop_id = '.$shopId)->find();
            //echo $express_fee->getLastSql();
			$sumexpress_cost = $express_fee_arr['sumexpress_cost'];
			$sumshare_cost = $express_fee_arr['sumshare_cost'];
		}
		$kuaidiwuliufei = $sumexpress_cost + $sumshare_cost;
		$this->assign('kuaidiwuliufei',$kuaidiwuliufei);

        //5.店铺租金
        $sum_sell_fee_rent_money = 0;
        if (in_array(30,$newrolemode)){
            $sum_sell_fee_rent_money_arr = $sell_fee->field('
			sum(case when int_rent_money =1 then rent_money else 0  end) as sumrent_money')
                ->where('input_date like "%'.$date.'%" and shop_id = '.$shopId)->find();
        }elseif (in_array(31,$newrolemode)){
            $sum_sell_fee_rent_money_arr = $sell_fee->field('
			sum(rent_money) as sumrent_money')
                ->where('input_date like "%'.$date.'%"  and shop_id = '.$shopId)->find();
        }
        $sum_sell_fee_rent_money = $sum_sell_fee_rent_money_arr['sumrent_money'];
        $this->assign('sum_sell_fee', $sum_sell_fee_rent_money);
		
		//4.销售费用
		$sum_sell_fee = 0;
		if (in_array(16,$newrolemode)){
			$sell_fee_arr = $sell_fee->field('
			sum(case when int_transfer = 1  then transfer else 0  end ) as sumtransfer,
			sum(case when int_pr_cost = 1 then pr_cost else 0  end) as sumpr_cost,
			sum(case when int_shuidianweisheng_fee = 1 then shuidianweisheng_fee else 0  end) as sumshuidianweisheng_fee,
			sum(case when int_wuye_fee = 1 then wuye_fee else 0  end) as sumwuye_fee,
			sum(case when int_advertising_fee = 1 then advertising_fee else 0  end ) as sumadvertising_fee,
			sum(case when int_cuxiao_fee = 1 then cuxiao_fee else 0  end) as sumcuxiao_fee,
			sum(case when int_xiaoshoufuliao_fee = 1 then xiaoshoufuliao_fee else 0  end) as sumxiaoshoufuliao_fee,
			sum(case when int_zhuangxiuweihu_fee = 1 then zhuangxiuweihu_fee else 0  end) as sumzhuangxiuweihu_fee,
			sum(case when int_shebeiweihu then shebeiweihu else 0  end) as sumshebeiweihu,
			sum(case when int_zhejiutuixiao = 1 then zhejiutuixiao end) as sumzhejiutuixiao,
			sum(case when int_jiameng =1 then jiameng else 0  end ) as sumjiameng,
			sum(case when int_pinpaishiyong_fee =1 then pinpaishiyong_fee else 0  end) as sumpinpaishiyong_fee,
			sum(case when int_jiameng_fee = 1 then jiameng_fee else 0  end) as sumjiameng_fee,
			sum(case when int_qita =1 then qita else 0  end) as sumqita')
			->where('input_date like "%'.$date.'%" and shop_id = '.$shopId)->find();
            //echo $sell_fee->getLastSql();
		
		}elseif (in_array(17,$newrolemode)){
			$sell_fee_arr = $sell_fee->field('
			sum(transfer) as sumtransfer,
			sum(pr_cost) as sumpr_cost,
			sum(shuidianweisheng_fee) as sumshuidianweisheng_fee,
			sum(wuye_fee) as sumwuye_fee,
			sum(advertising_fee) as sumadvertising_fee,
			sum(cuxiao_fee) as sumcuxiao_fee,
			sum(xiaoshoufuliao_fee) as sumxiaoshoufuliao_fee,
			sum(zhuangxiuweihu_fee) as sumzhuangxiuweihu_fee,
			sum(shebeiweihu) as sumshebeiweihu,
			sum(zhejiutuixiao) as sumzhejiutuixiao,
			sum(jiameng) as sumjiameng,
			sum(pinpaishiyong_fee) as sumpinpaishiyong_fee,
			sum(jiameng_fee) as sumjiameng_fee,
			sum(qita) as sumqita')
			->where('input_date like "%'.$date.'%" and shop_id = '.$shopId)->find();
            //echo $sell_fee->getLastSql();
		}
		$sum_sell_fee = $sum_sell_fee_rent_money
			+ $sell_fee_arr['sumtransfer'] 
			+ $sell_fee_arr['sumpr_cost'] 
			+ $sell_fee_arr['sumshuidianweisheng_fee'] 
			+ $sell_fee_arr['sumwuye_fee'] 
			+ $sell_fee_arr['sumadvertising_fee'] 
			+ $sell_fee_arr['sumcuxiao_fee'] 
			+ $sell_fee_arr['sumxiaoshoufuliao_fee'] 
			+ $sell_fee_arr['sumzhuangxiuweihu_fee'] 
			+ $sell_fee_arr['sumshebeiweihu'] 
			+ $sell_fee_arr['sumzhejiutuixiao'] 
			+ $sell_fee_arr['sumjiameng'] 
			+ $sell_fee_arr['sumpinpaishiyong_fee'] 
			+ $sell_fee_arr['sumjiameng_fee'] 
			+ $sell_fee_arr['sumqita'];
		//销售费用=销售费用报表销售费用+员工工资报表 + 物流费用
		$xiaoshoufeiyong = $sum_sell_fee + $sum_employee_pay + $kuaidiwuliufei;
       /* var_dump(array($sum_sell_fee , $sum_employee_pay , $kuaidiwuliufei));
        var_dump($xiaoshoufeiyong);*/
		$this->assign('xiaoshoufeiyong',$xiaoshoufeiyong);
		
			
		//8.管理费用
		$sum_manage_fee = 0;
		$manage_fee_arr = array();
		if (in_array(18,$newrolemode)){
			$manage_fee_arr = $manage_fee->field('
			sum(case when int_baoxian_fee = 1 then baoxian_fee else 0 end ) as sumbaoxian_fee,
			sum(case when int_bangong_fee = 1  then bangong_fee else 0 end ) as sumbangong_fee,
			sum(case when int_tongxun_fee = 1 then tongxun_fee else 0 end) as sumtongxun_fee,
			sum(case when int_chailv_fee = 1 then chailv_fee else 0 end) as sumchailv_fee,
			sum(case when int_fuli_fee = 1 then fuli_fee  else 0 end) as sumfuli_fee,
			sum(case when int_zhaodai_fee = 1 then zhaodai_fee else 0 end ) as sumzhaodai_fee,
			sum(case when int_car_fee = 1 then car_fee else 0 end) as sumcar_fee,
			sum(case when int_peixun_fee = 1 then peixun_fee else 0 end) as sumpeixun_fee,
			sum(case when int_zhengjian_fee = 1 then zhengjian_fee else 0 end) as sumzhengjian_fee,
			sum(case when int_zhijian_fee then zhijian_fee else 0 end) as sumzhijian_fee,
			sum(case when int_gongguan_fee = 1 then gongguan_fee else 0 end) as sumgongguan_fee,
			sum(case when int_zhejiu_fee =1 then zhejiu_fee else 0 end ) as sumzhejiu_fee,
			sum(case when int_guanli_fee = 1 then guanli_fee else 0 end ) as sumguanli_fee,
			sum(case when int_gongtan_fee = 1 then gongtan_fee else 0 end) as sumgongtan_fee,
			sum(case when int_qita_fee = 1 then qita_fee else 0 end) as sumqita')
			->where('input_date like "%'.$date.'%" and shop_id = '.$shopId)->find();
			//echo $manage_fee->getLastSql();
		}
		elseif (in_array(19,$newrolemode)){
			$manage_fee_arr = $manage_fee->field('
			sum(baoxian_fee) as sumbaoxian_fee,
			sum(bangong_fee) as sumbangong_fee,
			sum(tongxun_fee) as sumtongxun_fee,
			sum(chailv_fee) as sumchailv_fee,
			sum(fuli_fee) as sumfuli_fee,
			sum(zhaodai_fee) as sumzhaodai_fee,
			sum(car_fee) as sumcar_fee,
			sum(peixun_fee) as sumpeixun_fee,
			sum(zhengjian_fee) as sumzhengjian_fee,
			sum(zhijian_fee) as sumzhijian_fee,
			sum(gongguan_fee) as sumgongguan_fee,
			sum(zhejiu_fee) as sumzhejiu_fee,
			sum(guanli_fee) as sumguanli_fee,
			sum(gongtan_fee) as sumgongtan_fee,
			sum(qita_fee) as sumqita')
			->where('input_date like "%'.$date.'%" and shop_id = '.$shopId)->find();
		}
		
		$sum_manage_fee = $manage_fee_arr['sumbaoxian_fee'] + $manage_fee_arr['sumbangong_fee'] 
		+ $manage_fee_arr['sumtongxun_fee'] + $manage_fee_arr['sumchailv_fee'] 
		+ $manage_fee_arr['sumfuli_fee'] + $manage_fee_arr['sumzhaodai_fee'] 
		+ $manage_fee_arr['sumcar_fee'] + $manage_fee_arr['sumpeixun_fee'] 
		+ $manage_fee_arr['sumzhengjian_fee'] + $manage_fee_arr['sumzhijian_fee'] 
		+ $manage_fee_arr['sumgongguan_fee'] + $manage_fee_arr['sumzhejiu_fee'] 
		+ $manage_fee_arr['sumguanli_fee'] + $manage_fee_arr['sumgongtan_fee'] + $manage_fee_arr['sumqita'];
		$this->assign('sum_manage_fee',$sum_manage_fee);
		
		//9.财务费用
		$caiwufeiyong = 0;
		if (in_array(20,$newrolemode))
		{
			$cost_arr = $cost->field('sum(financial_cost) as sumfinancial_cost')->where('shop_id = '.$shopId.' and int_financial_cost = 1 and input_date like "%'.$date.'%"')->find();
			//echo $cost->getLastSql();
		}elseif (in_array(21,$newrolemode)){
			$cost_arr = $cost->field('sum(financial_cost) as sumfinancial_cost')->where('shop_id = '.$shopId.' and input_date like "%'.$date.'%"')->find();
		}
		$caiwufeiyong = $cost_arr['sumfinancial_cost'];
		$this->assign('caiwufeiyong',$caiwufeiyong);
		
		//10.刷卡手续费 
		$shuakashouxufei = $caiwufeiyong;
		
		//11.加：公允价值变动收益（损失以“-”号填列）
		$shouyi 	= 0;
		if (in_array(39,$newrolemode)){
			$incomestatement_arr = $incomestatement->field('sum(price_change) as sumprice_change')
			->where('shop_id = '.$shopId.' and int_price_change = 1 and input_date like "%'.$date.'%"')->find();
			$shouyi = $incomestatement_arr['sumprice_change'];
		}
		
		if (in_array(40,$newrolemode)){
			$incomestatement_arr = $incomestatement->field('sum(price_change) as sumprice_change')
			->where('shop_id = '.$shopId.'  and input_date like "%'.$date.'%"')->find();
			$shouyi = $incomestatement_arr['sumprice_change'];
		}
		$this->assign('shouyi',$shouyi);
		
		//12.投资收益（损失以“-”号填列） 
		$touzishouyi = 0;
		$this->assign('touzishouyi',$touzishouyi);
		
		//13.二、营业利润（亏损以“-”号填列） 
		//营业利润=营业收入-产品成本及加工-营业税费-销售费用-管理费用-财务费用+公允价值变动收益
		$yingyelirun = $yingyerice - $chanpinchengben - $yingyeshuifei - $xiaoshoufeiyong - $sum_manage_fee - $caiwufeiyong + $shouyi;
		$this->assign('yingyelirun',$yingyelirun);
		
		//14.加：营业外收入
		$yingyewaishouru 	= 0;
		if (in_array(22,$newrolemode)){
			$incomestatement_arr = $incomestatement->field('sum(operating_income) as sumoperating_income')
			->where('shop_id = '.$shopId.' and int_operating_income = "1" and input_date like "%'.$date.'%"')->find();
			$yingyewaishouru = $incomestatement_arr['sumoperating_income'];
		}
		
		if (in_array(23,$newrolemode)){
			$incomestatement_arr = $incomestatement->field('sum(operating_income) as sumoperating_income')
			->where('shop_id = '.$shopId.'  and input_date like "%'.$date.'%"')->find();
			$yingyewaishouru = $incomestatement_arr['sumoperating_income'];
		}
		$this->assign('yingyewaishouru',$yingyewaishouru);
		
		//15.营业外支出 
		$yingyewaizhichu = 0;
		if (in_array(24,$newrolemode)){
			$cost_arr = $cost->field('sum(spending) as sumspending')
			->where('shop_id = '.$shopId.' and int_spending = 1 and input_date like "%'.$date.'%"')->find();
			$yingyewaizhichu = $cost_arr['sumspending'];
		}
		
		if (in_array(25,$newrolemode)){
			$cost_arr = $cost->field('sum(spending) as sumspending')
			->where('shop_id = '.$shopId.'  and input_date like "%'.$date.'%"')->find();		
			$yingyewaizhichu = $cost_arr['sumspending'];
		}
		$this->assign('yingyewaizhichu',$yingyewaizhichu);
		
		//16.非流动资产处置损失 
		$feiliudongzichan = 0;
		$this->assign('feiliudongzichan',$feiliudongzichan);
		
		//17.利润总额（亏损总额以“-”号填列） 
		//营业利润+营业外收入-营业外支出=利润总额   利润总额-所得税=净利润
		//营业利润=营业收入-产品成本及加工-营业税费-销售费用-管理费用-财务费用+公允价值变动收益
		$liyunzonge = $yingyelirun + $yingyewaishouru - $yingyewaizhichu;
		$this->assign('liyunzonge',$liyunzonge);
		
		//18.所得税费用 
		$suodeshuifeiyong = 0;
		$this->assign('suodeshuifeiyong',$suodeshuifeiyong);
		
		//19.净利润（净亏损以“-”号填列） 
		$jinglirun = $liyunzonge - $suodeshuifeiyong;
		$this->assign('jinglirun',$jinglirun);
		
		//20.加：公司对加盟商补贴 
		$jiamengbutie 	= 0;
		if (in_array(42,$newrolemode)){
			$incomestatement_arr_butie = $incomestatement->field('sum(subsidy_income) as sumsubsidy_income')
			->where('shop_id = '.$shopId.' and int_subsidy_income = 1 and input_date like "%'.$date.'%"')->find();
			//echo $incomestatement->getLastSql();
			$jiamengbutie 	= $incomestatement_arr_butie['sumsubsidy_income'];
		}
		
		if (in_array(43,$newrolemode)){
			$incomestatement_arr_butie = $incomestatement->field('sum(subsidy_income) as sumsubsidy_income')
			->where('shop_id = '.$shopId.'  and input_date like "%'.$date.'%"')->find();
			$jiamengbutie 	= $incomestatement_arr_butie['sumsubsidy_income'];
		}
		$this->assign('jiamengbutie',$jiamengbutie);
		
		//21.加：加盟预算费用返还（负“-”号表示加收）
		$jiamengfanhuan = 0;
		if (in_array(45,$newrolemode)){
			$incomestatement_arr_fanhuan = $incomestatement->field('sum(return_amount) as sumreturn_amount')
			->where('shop_id = '.$shopId.' and int_return_amount = 1 and input_date like "%'.$date.'%"')->find();
			$jiamengfanhuan = $incomestatement_arr_fanhuan['sumreturn_amount'];
		}
		
		if (in_array(46,$newrolemode)){
			$incomestatement_arr_fanhuan = $incomestatement->field('sum(return_amount) as sumreturn_amount')
			->where('shop_id = '.$shopId.'  and input_date like "%'.$date.'%"')->find();
			$jiamengfanhuan = $incomestatement_arr_fanhuan['sumreturn_amount'];
		}
		$this->assign('jiamengfanhuan',$jiamengfanhuan);
		
		//22.五、净收益（负收益以“-”号填列） 
		//净收益=净利润+公司对加盟商补贴+加盟预算费用返还（负“-”号表示加收）
		$jingshouyi = $jinglirun + $jiamengbutie + $jiamengfanhuan;
		$this->assign('jingshouyi',$jingshouyi);
		
		//23.（一）加盟商投资比例（%） 
		//$bili = 0.01;
		//$bili = $bili / 100;
		$this->assign('bili',$bili);
		
		//24.（二）加盟商分享收益（元） 
		//（二）加盟商分享收益=净收益*加盟商投资比例（%）
		$fenxiangshouyi = $jingshouyi * ($bili / 100);
		$this->assign('fenxiangshouyi',$fenxiangshouyi);
				
		$this->display("view");
	}
	
	
    public function detail(){
    	$t = $_GET['t'];
		$shop_id = $_GET['shop_id'];
		$input_date = $_GET['input_date'];
		
		//$this->assign('modearr',$newrolemode);
		switch ($t)
		{
			case 1:
				$arr = $this->incomestatement_list($shop_id,$t,$input_date);
                //$total = $arr['sum'];
                //unset($arr['sum']);
                //echo '<pre>';print_r($arr);
                //$this->asign('total',$total);
				$this->assign('arr',$arr);
				$this->display('incomestatement_list');
				break;
			case 2:
				$arr = $this->cost_list($shop_id,$t,$input_date);
				$this->assign('arr',$arr);
				$this->display('cost_list');
				break;
			case 3:
				$arr = $this->manage_fee_list($shop_id,$t,$input_date);
				/* echo '<pre>';
				print_r($arr); */
				$this->assign('arr',$arr);
				$this->display('manage_fee_list');
				break;
			case 4:
				$arr = $this->sell_fee_list($shop_id,$t,$input_date);
				$this->assign('arr',$arr);
				$this->display('sell_fee_list');
			break;
			case 5:
				$arr = $this->employee_pay_list($shop_id,$t,$input_date);
				$this->assign('arr',$arr);
				$this->display('employee_pay_list');
			break;
			case 6:
			$arr = $this->express_fee_list($shop_id,$t,$input_date);
			$this->assign('arr',$arr);
			$this->display('express_fee_list');
			break;
		}
    }
	
	//获取用户操作权限
	public function modeinfo($shop_id){
		//获取role的权限
		$role_mode = M("role_mode");
		$rolemod = $role_mode->where('user_id = '.$_SESSION['user_id'].' and shop_id = '.$shop_id)->select();
		$newrolemode = array();
		foreach($rolemod as $v){
			$newrolemode[] = $v['mode_id'];
		}
		return $newrolemode;
	}
	
	public function incomestatement_list($shop_id,$t,$input_date)
	{
		$shop = M("shop");
		$arr = $shop->where('cms_shop.shop_id = '.$shop_id.' and input_date like "%'.$input_date.'%"')
		->field('cms_shop.shop_id,cms_shop.shop_name,cms_incomestatement.*')
		->join("left join cms_incomestatement on cms_incomestatement.shop_id = cms_shop.shop_id")->select();

        $shouru = 0;
        $subsidy_income = 0;
        $return_amount = 0;
        $operating_income = 0;
        $price_change = 0;
        $total = 0;
		//echo $shop->getLastSql();
		for($i = 0; $i < count($arr); $i++){
			if (in_array(10,$this->modeinfo($shop_id))){
				$arr[$i]['shouru'] = $arr[$i]['price'];
			}elseif (in_array(11,$this->modeinfo($shop_id))){
				$arr[$i]['shouru'] = $arr[$i]['discount'];
			}
            $shouru += $arr[$i]['shouru'];

            //公允价值变动收益
            if (in_array(39,$this->modeinfo($shop_id)) && $arr[$i]['int_price_change'] != 1){
                $arr[$i]['price_change'] = 0;
            }/*elseif (in_array(40,$this->modeinfo($shop_id))){
                $arr[$i]['price_change'] = $arr[$i]['price_change'];
            }*/
            $price_change += $arr[$i]['price_change'];

            //公司对加盟商补贴
            if (in_array(42,$this->modeinfo($shop_id)) && $arr[$i]['int_subsidy_income'] != 1){
                $arr[$i]['subsidy_income'] = 0;
            }/*elseif (in_array(43,$this->modeinfo($shop_id))){
                $arr[$i]['shouru'] = $arr[$i]['discount'];
            }*/
            $subsidy_income += $arr[$i]['subsidy_income'];

            //加盟预算费用返还
            if (in_array(45,$this->modeinfo($shop_id)) && $arr[$i]['int_return_amount'] != 1){
                $arr[$i]['return_amount'] = 0;
            }/*elseif (in_array(46,$this->modeinfo($shop_id))){
                $arr[$i]['shouru'] = $arr[$i]['discount'];
            }*/
            $return_amount += $arr[$i]['return_amount'];

            //营业外收入
            if (in_array(22,$this->modeinfo($shop_id)) && $arr[$i]['int_operating_income'] != 1){
                $arr[$i]['operating_income'] = 0;
            }/*elseif (in_array(23,$this->modeinfo($shop_id))){
                $arr[$i]['shouru'] = $arr[$i]['discount'];
            }*/
            $operating_income += $arr[$i]['operating_income'];

            $total = $shouru + $price_change + $subsidy_income + $return_amount + $operating_income;
            $arr[$i]['sum'] = $arr[$i]['shouru'] + $arr[$i]['price_change'] + $arr[$i]['subsidy_income'] + $arr[$i]['return_amount'] + $arr[$i]['operating_income'];
        }
        $arr[] = array('shop_name' => '<font color="#dc143c">合计</font>',
            'shouru' => $shouru,
            'price_change' => $price_change,
            'subsidy_income' => $subsidy_income,
            'return_amount' => $return_amount,
            'operating_income' => $operating_income,
            'sum' => $total,
            );
        /*echo '<pre>';
        print_r($arr);*/
		//echo $shop->getLastSql();
		return $arr;
	}
	
	public function cost_list($shop_id,$t,$input_date)
	{
		$shop = M("shop");
		$arr = $shop->where('cms_shop.shop_id = '.$shop_id.' and input_date like "%'.$input_date.'%"')
		->field('cms_shop.shop_id,cms_shop.shop_name,cms_cost.*')
		->join("left join cms_cost on cms_cost.shop_id = cms_shop.shop_id")->select();
		//echo $shop->getLastSql();

        $chanpinchengben = 0;
        $jingpianchengben = 0;
        $spending = 0;
        $taxes_fee = 0;
        $financial_cost = 0;
        $processing_fee = 0;
        $total = 0;

		for($i = 0; $i < count($arr); $i++){
			if (in_array(12,$this->modeinfo($shop_id))){
				$arr[$i]['chanpinchengben'] = $arr[$i]['price'];
				$arr[$i]['jingpianchengben'] = $arr[$i]['jingpian_price'];
			}elseif (in_array(13,$this->modeinfo($shop_id))){
				$arr[$i]['chanpinchengben'] = $arr[$i]['discount'];
				$arr[$i]['jingpianchengben'] = $arr[$i]['jingpian_discount'];
			}

            $chanpinchengben += $arr[$i]['chanpinchengben'];
            $jingpianchengben += $arr[$i]['jingpianchengben'];

            //加工费
            if (in_array(69,$this->modeinfo($shop_id)) && $arr[$i]['int_processing_fee'] != 1){
                $arr[$i]['processing_fee'] = 0;
            }
            $processing_fee += $arr[$i]['processing_fee'];

            //营业外支出
            if (in_array(24,$this->modeinfo($shop_id)) && $arr[$i]['int_spending'] != 1){
                $arr[$i]['spending'] = 0;
            }
            $spending += $arr[$i]['spending'];

            //营业税费
            if (in_array(14,$this->modeinfo($shop_id)) && $arr[$i]['int_taxes_fee'] != 1){
                $arr[$i]['taxes_fee'] = 0;
            }
            $taxes_fee += $arr[$i]['taxes_fee'];

            //财务费用
            if (in_array(20,$this->modeinfo($shop_id)) && $arr[$i]['int_financial_cost'] != 1){
                $arr[$i]['financial_cost'] = 0;
            }
            $financial_cost += $arr[$i]['financial_cost'];

            $total = $chanpinchengben + $jingpianchengben + $processing_fee + $spending + $taxes_fee + $financial_cost;
            $arr[$i]['sum'] = $arr[$i]['chanpinchengben'] + $arr[$i]['jingpianchengben'] + $arr[$i]['processing_fee'] + $arr[$i]['taxes_fee'] + $arr[$i]['financial_cost'] + $arr[$i]['spending'];

        }

        $arr[] = array('shop_name' => '<font color="#dc143c">合计</font>',
            'chanpinchengben' => $chanpinchengben,
            'jingpianchengben' => $jingpianchengben,
            'processing_fee' => $processing_fee,
            'spending' => $spending,
            'taxes_fee' => $taxes_fee,
            'financial_cost' => $financial_cost,
            'sum' => $total,
        );
		//echo $shop->getLastSql();
		return $arr;
	}
	
	public function manage_fee_list($shop_id,$t,$input_date)
	{
		$shop = M("shop");
		$arr = $shop->where('cms_shop.shop_id = '.$shop_id.' and input_date like "%'.$input_date.'%"')
		->join("left join cms_manage_fee on cms_manage_fee.shop_id = cms_shop.shop_id");
		
		if (in_array(18,$this->modeinfo($shop_id))){
				$manage_fee_arr = $shop->field('cms_shop.shop_id,cms_shop.shop_name,cms_manage_fee.input_date,
				(case when int_baoxian_fee = 1 then baoxian_fee else 0 end ) as baoxian_fee,
				(case when int_bangong_fee = 1  then bangong_fee else 0 end ) as bangong_fee,
				(case when int_tongxun_fee = 1 then tongxun_fee else 0 end) as tongxun_fee,
				(case when int_chailv_fee = 1 then chailv_fee else 0 end) as chailv_fee,
				(case when int_fuli_fee = 1 then fuli_fee  else 0 end) as fuli_fee,
				(case when int_zhaodai_fee = 1 then zhaodai_fee else 0 end ) as zhaodai_fee,
				(case when int_car_fee = 1 then car_fee else 0 end) as car_fee,
				(case when int_peixun_fee = 1 then peixun_fee else 0 end) as peixun_fee,
				(case when int_zhengjian_fee = 1 then zhengjian_fee else 0 end) as zhengjian_fee,
				(case when int_zhijian_fee then zhijian_fee else 0 end) as zhijian_fee,
				(case when int_gongguan_fee = 1 then gongguan_fee else 0 end) as gongguan_fee,
				(case when int_zhejiu_fee =1 then zhejiu_fee else 0 end ) as zhejiu_fee,
				(case when int_guanli_fee = 1 then guanli_fee else 0 end ) as guanli_fee,
				(case when int_gongtan_fee = 1 then gongtan_fee else 0 end) as gongtan_fee,
				(case when int_qita_fee = 1 then qita_fee else 0 end) as qita_fee')
				->select();
				//echo $shop->getLastSql();
			}
			elseif (in_array(19,$this->modeinfo($shop_id))){
				$manage_fee_arr = $shop->field('cms_shop.shop_id,cms_shop.shop_name,cms_manage_fee.*')->select();
			}

        $baoxian_fee = 0;
        $bangong_fee = 0;
        $tongxun_fee = 0;
        $chailv_fee = 0;
        $fuli_fee = 0;
        $car_fee = 0;
        $gongguan_fee = 0;
        $peixun_fee = 0;
        $zhengjian_fee = 0;
        $zhaodai_fee = 0;
        $zhijian_fee = 0;
        $zhejiu_fee = 0;
        $guanli_fee = 0;
        $gongtan_fee = 0;
        $qita_fee = 0;
        $total = 0 ;

        for($i = 0; $i < count($manage_fee_arr); $i++){
            $baoxian_fee += $manage_fee_arr[$i]['baoxian_fee'];
            $bangong_fee += $manage_fee_arr[$i]['bangong_fee'];
            $tongxun_fee += $manage_fee_arr[$i]['tongxun_fee'];
            $chailv_fee += $manage_fee_arr[$i]['chailv_fee'];
            $fuli_fee += $manage_fee_arr[$i]['fuli_fee'];
            $car_fee += $manage_fee_arr[$i]['car_fee'];
            $gongguan_fee += $manage_fee_arr[$i]['gongguan_fee'];
            $zhaodai_fee += $manage_fee_arr[$i]['zhaodai_fee'];
            $peixun_fee += $manage_fee_arr[$i]['peixun_fee'];
            $zhengjian_fee += $manage_fee_arr[$i]['zhengjian_fee'];
            $zhijian_fee += $manage_fee_arr[$i]['zhijian_fee'];
            $zhejiu_fee += $manage_fee_arr[$i]['zhejiu_fee'];
            $guanli_fee += $manage_fee_arr[$i]['guanli_fee'];
            $gongtan_fee += $manage_fee_arr[$i]['gongtan_fee'];
            $qita_fee += $manage_fee_arr[$i]['qita_fee'];

            $total = $baoxian_fee + $bangong_fee + $tongxun_fee
                + $chailv_fee + $fuli_fee +
                $car_fee + $peixun_fee +
                $zhengjian_fee + $guanli_fee
                + $gongtan_fee + $qita_fee
                + $zhaodai_fee + $gongguan_fee
                + $zhejiu_fee + $zhijian_fee ;
            $manage_fee_arr[$i]['sum'] = $manage_fee_arr[$i]['baoxian_fee'] +
                $manage_fee_arr[$i]['bangong_fee'] + $manage_fee_arr[$i]['tongxun_fee'] +
                $manage_fee_arr[$i]['chailv_fee'] + $manage_fee_arr[$i]['fuli_fee'] +
                $manage_fee_arr[$i]['car_fee']  +
                $manage_fee_arr[$i]['zhaodai_fee'] +
                $manage_fee_arr[$i]['peixun_fee'] + $manage_fee_arr[$i]['gongguan_fee'] +
                $manage_fee_arr[$i]['zhengjian_fee'] + $manage_fee_arr[$i]['zhijian_fee'] +
                $manage_fee_arr[$i]['zhejiu_fee'] + $manage_fee_arr[$i]['guanli_fee'] +
                $manage_fee_arr[$i]['gongtan_fee'] + $manage_fee_arr[$i]['qita_fee'];
            /*echo '<pre>';
            print_r($manage_fee_arr[$i]['baoxian_fee'] .",". $manage_fee_arr[$i]['bangong_fee'] .",". $manage_fee_arr[$i]['tongxun_fee'] .",".
                $manage_fee_arr[$i]['chailv_fee'] .",". $manage_fee_arr[$i]['fuli_fee'] .",". $manage_fee_arr[$i]['zhaodai_fee'].
                $manage_fee_arr[$i]['car_fee'] .",". $manage_fee_arr[$i]['peixun_fee'] .",".
                $manage_fee_arr[$i]['peixun_fee'] .",". $manage_fee_arr[$i]['gongguan_fee'] .",".
                $manage_fee_arr[$i]['zhengjian_fee'] .",". $manage_fee_arr[$i]['zhijian_fee'] .",".
                $manage_fee_arr[$i]['zhejiu_fee'] .",". $manage_fee_arr[$i]['guanli_fee'] .",".
                $manage_fee_arr[$i]['gongtan_fee'] .",". $manage_fee_arr[$i]['qita_fee']);*/
            ;
        }

        $manage_fee_arr[] = array('shop_name' => '<font color="#dc143c">合计</font>',
            'baoxian_fee' => $baoxian_fee,
            'bangong_fee' => $bangong_fee,
            'tongxun_fee' => $tongxun_fee,
            'chailv_fee' => $chailv_fee,
            'fuli_fee' => $fuli_fee,
            'car_fee' => $car_fee,
            'gongguan_fee' => $gongguan_fee,
            'zhaodai_fee' => $zhaodai_fee,
            'peixun_fee' => $peixun_fee,
            'zhengjian_fee' => $zhengjian_fee,
            'zhijian_fee' => $zhijian_fee,
            'zhejiu_fee' => $zhejiu_fee,
            'guanli_fee' => $guanli_fee,
            'gongtan_fee' => $gongtan_fee,
            'qita_fee' => $qita_fee,
            'sum' => $total,
        );
		
		//echo $shop->getLastSql();
		return $manage_fee_arr;
	}
	
	public function sell_fee_list($shop_id,$t,$input_date)
	{
		//销售费用
		$shop = M("shop");
		$shop->where('cms_shop.shop_id = '.$shop_id.' and input_date like "%'.$input_date.'%"')
		->join("left join cms_sell_fee on cms_sell_fee.shop_id = cms_shop.shop_id");
		
		if (in_array(16,$this->modeinfo($shop_id))){
			$sell_fee_arr = $shop->field('cms_shop.shop_id,cms_shop.shop_name,cms_sell_fee.input_date,int_rent_money,
			rent_money,
			(case when int_transfer = 1  then transfer else 0  end ) as transfer,
			(case when int_pr_cost = 1 then pr_cost else 0  end) as pr_cost,
			(case when int_shuidianweisheng_fee = 1 then shuidianweisheng_fee else 0  end) as shuidianweisheng_fee,
			(case when int_wuye_fee = 1 then wuye_fee else 0  end) as wuye_fee,
			(case when int_advertising_fee = 1 then advertising_fee else 0  end ) as advertising_fee,
			(case when int_cuxiao_fee = 1 then cuxiao_fee else 0  end) as cuxiao_fee,
			(case when int_xiaoshoufuliao_fee = 1 then xiaoshoufuliao_fee else 0  end) as xiaoshoufuliao_fee,
			(case when int_zhuangxiuweihu_fee = 1 then zhuangxiuweihu_fee else 0  end) as zhuangxiuweihu_fee,
			(case when int_shebeiweihu then shebeiweihu else 0  end) as shebeiweihu,
			(case when int_zhejiutuixiao = 1 then zhejiutuixiao end) as zhejiutuixiao,
			(case when int_jiameng =1 then jiameng else 0  end ) as jiameng,
			(case when int_pinpaishiyong_fee =1 then pinpaishiyong_fee else 0  end) as pinpaishiyong_fee,
			(case when int_jiameng_fee = 1 then jiameng_fee else 0  end) as jiameng_fee,
			(case when int_qita =1 then qita else 0  end) as qita')
			->select();
            /*for($jj = 0; $jj < count($sell_fee_arr); $jj++){
                //营业税费
                if (in_array(30,$this->modeinfo($shop_id)) && $sell_fee_arr[$jj]['int_rent_money'] != 1){
                    $sell_fee_arr[$jj]['rent_money'] = 0;
                }
            }*/
			//echo $shop->getLastSql();
		
		}elseif (in_array(17,$this->modeinfo($shop_id))){
			$sell_fee_arr = $shop->field('cms_shop.shop_id,cms_shop.shop_name,cms_sell_fee.*')
			->select();
		}

        $rent_money = 0;
        $transfer = 0;
        $pr_cost = 0;
        $shuidianweisheng_fee = 0;
        $wuye_fee = 0;
        $advertising_fee = 0;
        $cuxiao_fee = 0;
        $xiaoshoufuliao_fee = 0;
        $zhuangxiuweihu_fee = 0;
        $shebeiweihu = 0;
        $zhejiutuixiao = 0;
        $jiameng = 0;
        $pinpaishiyong_fee = 0;
        $jiameng_fee = 0;
        $qita = 0;

        for($jj = 0; $jj < count($sell_fee_arr); $jj++)
        {
            //营业税费
            if (in_array(30,$this->modeinfo($shop_id)) && $sell_fee_arr[$jj]['int_rent_money'] != 1){
                $sell_fee_arr[$jj]['rent_money'] = 0;
            }

            $rent_money += $sell_fee_arr[$jj]['rent_money'];
            $transfer += $sell_fee_arr[$jj]['transfer'];
            $pr_cost += $sell_fee_arr[$jj]['pr_cost'];
            $shuidianweisheng_fee += $sell_fee_arr[$jj]['shuidianweisheng_fee'];
            $wuye_fee += $sell_fee_arr[$jj]['wuye_fee'];
            $advertising_fee += $sell_fee_arr[$jj]['advertising_fee'];
            $cuxiao_fee += $sell_fee_arr[$jj]['cuxiao_fee'];
            $xiaoshoufuliao_fee += $sell_fee_arr[$jj]['xiaoshoufuliao_fee'];
            $zhuangxiuweihu_fee += $sell_fee_arr[$jj]['zhuangxiuweihu_fee'];
            $shebeiweihu += $sell_fee_arr[$jj]['shebeiweihu'];
            $zhejiutuixiao += $sell_fee_arr[$jj]['zhejiutuixiao'];
            $jiameng += $sell_fee_arr[$jj]['jiameng'];
            $pinpaishiyong_fee += $sell_fee_arr[$jj]['pinpaishiyong_fee'];
            $jiameng_fee += $sell_fee_arr[$jj]['jiameng_fee'];
            $qita += $sell_fee_arr[$jj]['qita'];

            $total = $rent_money + $transfer + $pr_cost + $shuidianweisheng_fee
                + $wuye_fee + $advertising_fee +
                $cuxiao_fee + $xiaoshoufuliao_fee +
                $zhuangxiuweihu_fee + $shebeiweihu
                + $zhejiutuixiao + $jiameng
                + $pinpaishiyong_fee + $jiameng_fee
                + $qita;
            $sell_fee_arr[$jj]['sum'] = $sell_fee_arr[$jj]['rent_money'] + $sell_fee_arr[$jj]['transfer'] +
                $sell_fee_arr[$jj]['pr_cost'] + $sell_fee_arr[$jj]['shuidianweisheng_fee'] +
                $sell_fee_arr[$jj]['wuye_fee'] + $sell_fee_arr[$jj]['advertising_fee'] +
                $sell_fee_arr[$jj]['cuxiao_fee']  +
                $sell_fee_arr[$jj]['xiaoshoufuliao_fee'] +
                $sell_fee_arr[$jj]['zhuangxiuweihu_fee'] + $sell_fee_arr[$jj]['shebeiweihu'] +
                $sell_fee_arr[$jj]['zhejiutuixiao'] + $sell_fee_arr[$jj]['jiameng'] +
                $sell_fee_arr[$jj]['pinpaishiyong_fee'] + $sell_fee_arr[$jj]['jiameng_fee'] +
                $sell_fee_arr[$jj]['qita'];
        }

        $sell_fee_arr[] = array('shop_name' => '<font color="#dc143c">合计</font>',
            'rent_money'=>$rent_money,
            'transfer' => $transfer,
            'pr_cost' => $pr_cost,
            'shuidianweisheng_fee' => $shuidianweisheng_fee,
            'wuye_fee'=>$wuye_fee,
            'advertising_fee' => $advertising_fee,
            'cuxiao_fee' => $cuxiao_fee,
            'xiaoshoufuliao_fee' => $xiaoshoufuliao_fee,
            'zhuangxiuweihu_fee' => $zhuangxiuweihu_fee,
            'shebeiweihu' => $shebeiweihu,
            'zhejiutuixiao' => $zhejiutuixiao,
            'jiameng' => $jiameng,
            'pinpaishiyong_fee' => $pinpaishiyong_fee,
            'jiameng_fee' => $jiameng_fee,
            'qita' => $qita,
            'sum' => $total,
        );
		
		return $sell_fee_arr;
	}
	
	public function employee_pay_list($shop_id,$t,$input_date)
	{
		//6.人员薪资
		$shop = M("shop");
		#$shop->join("join cms_user_shop on cms_user_shop.shop_id = cms_shop.shop_id");
		$shop->join("join cms_employee_pay on cms_employee_pay.shop_id = cms_shop.shop_id");
        $shop->join("join cms_member on cms_employee_pay.user_id = cms_member.user_id");
		
		
		$sum_employee_pay = 0;
        $shifagongzi = 0;
		if (in_array(33,$this->modeinfo($shop_id))){
			$employee_pay_arr = $shop->field('cms_shop.*,cms_employee_pay.*,cms_member.*')
			->where('cms_shop.shop_id = '.$shop_id.' and input_date like "%'.$input_date.'%" and is_used = "0"')->select();
			//echo $shop->getLastSql();
		}
		elseif (in_array(34,$this->modeinfo($shop_id)))
		{
			$employee_pay_arr = $shop->field('cms_shop.*,cms_employee_pay.*,cms_member.*')
			->where('cms_shop.shop_id = '.$shop_id.' and input_date like "%'.$input_date.'%" ')->select();
		}
        //echo $shop->getLastSql();
        for($i = 0; $i < count($employee_pay_arr); $i++)
        {
            $shifagongzi += $employee_pay_arr[$i]['shifagongzi'];
        }

        $employee_pay_arr[] = array('shop_name' => '<font color="#dc143c">合计</font>', 'shifagongzi' => $shifagongzi);
		//echo $shop->getLastSql();
		return $employee_pay_arr;
	}
	
	public function express_fee_list($shop_id,$t,$input_date)
	{
		//物流费用
		$shop = M("shop");
		$shop->join("left join cms_express_fee on cms_express_fee.shop_id = cms_shop.shop_id")
		->where(' input_date like "%'.$input_date.'%" and cms_shop.shop_id = '.$shop_id);
		
		if (in_array(36,$this->modeinfo($shop_id))){
			$express_fee_arr = $shop->field('cms_shop.shop_id,cms_shop.shop_name,input_date,cms_express_fee.express,cms_express_fee.track_no,
			( case when int_express_cost = 1 then express_cost else 0 end ) as express_cost,			
			(case when int_share_cost = 1 then share_cost else 0 end) as share_cost')
			->select();
			//echo $shop->getLastSql();
		}
		elseif (in_array(37,$this->modeinfo($shop_id))){
			$express_fee_arr = $shop->field('cms_shop.shop_id,cms_shop.shop_name,cms_express_fee.*')
			->select();
			//echo $shop->getLastSql();
		}

        $express_cost = 0;
        $share_cost = 0;
        $total = 0;
        for($i = 0; $i < count($express_fee_arr); $i++){
            $express_cost += $express_fee_arr[$i]['express_cost'];
            $share_cost += $express_fee_arr[$i]['share_cost'];
            $express_fee_arr[$i]['sum'] = $express_fee_arr[$i]['express_cost'] + $express_fee_arr[$i]['share_cost'];
            $total = $express_cost + $share_cost;
        }

        $express_fee_arr[] = array('shop_name' => '<font color="#dc143c">合计</font>','express_cost' => $express_cost, 'share_cost'=> $share_cost,'sum' => $total);
		
		return $express_fee_arr;
	}
	
	
	public function login()
	{
		if (isset($_POST['login']) && !empty($_POST['login']))
		{
			$ErrorInfo = '';
			$username = $_POST['username'];
			$password = $_POST['password'];
			$remember = $_POST['remmember'];
			
			
			if ($username == "")
			{
				$ErrorInfo = "用户名不能为空";
			}
			elseif($password == "")
			{
				$ErrorInfo = "密码不能为空";
			}
			else
			{
				$member = M("member");
				$member->join("JOIN cms_member_role on cms_member_role.member_id=cms_member.user_id");
				$member->join("JOIN cms_rolemain on cms_member_role.role_id=cms_rolemain.role_id");
				$rs = $member->where('username = "'.$username.'" and status = "0"')->find();
				if ($rs)
				{
					if ($username == $rs['username'] && $password == $rs['password'])
					{
						$_SESSION['user_id'] = $rs['user_id'];
						$_SESSION['username'] = $rs['username'];
						$_SESSION['HOMEROLEID'] = $rs['role_id'];
                        $_SESSION['nickname'] = $rs['nickname'];
						
						if (isset($remember) && !empty($remember))
						{
							setcookie("username", $rs['username'], time()+3600*24*365); 
							setcookie("password", $rs['password'], time()+3600*24*365);  
						}
						
						
						
						//更新最后一次登陆时间
						$data['lastmodify'] = date('Y-m-d H:i:s');
						$member->data($data)->where('user_id = '.$rs['user_id'])->save();
						
						$this->redirect("/index/index");
					}else{
						//$this->error("对不起，用户名或者密码错误");
						$ErrorInfo = '对不起，用户名或者密码错误';
					}
				}else{
					//$this->error("对不起，该用户不存在");
					$ErrorInfo = '对不起，该用户不存在';
				}
			}
			$this->assign('ErrorInfo', $ErrorInfo);

		}
		$this->display('login');
	}
	
	public function logout()
	{
		unset($_SESSION['user_id']);
		unset($_SESSION['username']);
		unset($_SESSION['ROLEID']);
		if(!empty($_COOKIE['username']) || !empty($_COOKIE['password'])){  
			setcookie("username", null, time()-3600*24*365); 
			setcookie("password", null, time()-3600*24*365); 
		}
		$_SESSION = array();
		session_destroy();
		$this->redirect('/index/login');
	}
    public function online(){
    	$this->display();
    }
    /*
	 *  首页
	 */
    public function news(){
    	if(session('openid')==''){
    		$code=$this->_get('code');
    		if(!empty($code)){
		    	$open_id=get_openid($code);
			}elseif(!empty($_GET['openid'])){
				session('openid',$_GET['openid']);
			}
    	}
    	$news_id=$this->_get('news_id');
		$this->assign("res",get_news_detail($news_id,'content'));
		$this->assign("seo_title",'亿超眼镜');
		$this->display();
    }

    /*
	 *  validation 验证
	 */
    public function validation(){
    	$this->responseMsg();die();
    	$this->TOKEN='mixi123456';
        $this->valid(); #验证
        exit;
    	if(strlen($this->_get('key'))==32){  #验证
    		$key=$this->_get('key');
    		$admin_id=intval($this->_get('id'));
    		$admin=M('admin');
    		$admin_res=$admin->field('account')->where("admin_id=$admin_id")->find();
    		if($admin_res){
    			$token=md5('leg3s'.$admin_res['account'].$admin_id);
    			if($token==$key){
    				$this->TOKEN=md5($admin_res['account']);
    				$this->valid(); #验证
    			}else{
    				echo 'illegality';
    			}
    		}else{
    			echo 'error';
    		}
    		//exit;
    	}
    }

    /*
    *产品套餐
    */
    public function product(){
    	$product_list=get_news_type(2,1,'type_id,type_name,keywords'); #产品套餐
    	$news=M('news');
    	$news_where['status']=array('eq',1);
    	$news_where['is_del']=array('eq','0');
    	foreach ($product_list as $key=>$list){
    		$news_where['type_id']=array('eq',$list['type_id']);
    		$count=$news->where($news_where)->count();
    		if($count<1){
    			unset($product_list[$key]);
    		}else{
    			$icon=get_news($list['type_id'],1,'icon');
	    		$product_list[$key]['images']=C('NEWIMGURL').$icon[0]['icon'];
	    		$product_list[$key]['count']=$count;
    		}
    	}
    	$this->assign("product_list",$product_list);
    	$this->assign("seo_title",'产品套餐');
		$this->display();
    }

     public function product_list(){
    	$this->assign("product_list",get_news($this->_get('type_id'),50,'icon,title'));
    	$newstype=M('newstype');
    	$type_list=$newstype->field('type_name,keywords,description')->where("type_id=".$this->_get('type_id'))->find();
    	$this->assign("type_list",$type_list);
    	$this->assign("seo_title",'产品套餐');
		$this->display();
    }

    /*
    *作品欣赏
    */
    public function works(){
    	$product_list=get_news_type(5,1,'type_id,type_name,keywords'); #作品
    	$news=M('news');
    	$news_where['status']=array('eq',1);
    	$news_where['is_del']=array('eq','0');
    	foreach ($product_list as $key=>$list){
    		$news_where['type_id']=array('eq',$list['type_id']);
    		$count=$news->where($news_where)->count();
    		if($count<1){
    			unset($product_list[$key]);
    		}else{
    			$icon=get_news($list['type_id'],1,'icon');
	    		$product_list[$key]['images']=C('NEWIMGURL').$icon[0]['icon'];
	    		$product_list[$key]['count']=$count;
    		}
    	}
    	$this->assign("product_list",$product_list);
    	$this->assign("seo_title",'作品欣赏');
		$this->display('product');
    }

    /*
    *店内环境
    */
    public function environment(){
    	$product_list=get_news_type(8,1,'type_id,type_name,keywords'); #店内环境
    	$news=M('news');
    	$news_where['status']=array('eq',1);
    	$news_where['is_del']=array('eq','0');
    	foreach ($product_list as $key=>$list){
    		$news_where['type_id']=array('eq',$list['type_id']);
    		$count=$news->where($news_where)->count();
    		if($count<1){
    			unset($product_list[$key]);
    		}else{
    			$icon=get_news($list['type_id'],1,'icon');
	    		$product_list[$key]['images']=C('NEWIMGURL').$icon[0]['icon'];
	    		$product_list[$key]['count']=$count;
    		}
    	}
    	$this->assign("product_list",$product_list);
    	$this->assign("seo_title",'店内环境');
		$this->display('product');
    }

    /*
    *店内环境
    */
    public function environment_yy(){
    	$product_list=get_news_type(27,1,'type_id,type_name,keywords'); #店内环境
    	$news=M('news');
    	$news_where['status']=array('eq',1);
    	$news_where['is_del']=array('eq','0');
    	foreach ($product_list as $key=>$list){
    		$news_where['type_id']=array('eq',$list['type_id']);
    		$count=$news->where($news_where)->count();
    		if($count<1){
    			unset($product_list[$key]);
    		}else{
    			$icon=get_news($list['type_id'],1,'icon');
	    		$product_list[$key]['images']=C('NEWIMGURL').$icon[0]['icon'];
	    		$product_list[$key]['count']=$count;
    		}
    	}
    	$this->assign("product_list",$product_list);
    	$this->assign("seo_title",'酷儿游泳');
		$this->display('product');
    }

    /*
    *团队风采
    */
    public function team(){
    	$product_list=get_news_type(11,1,'type_id,type_name,keywords'); #团队风采
    	$news=M('news');
    	$news_where['status']=array('eq',1);
    	$news_where['is_del']=array('eq','0');
    	foreach ($product_list as $key=>$list){
    		$news_where['type_id']=array('eq',$list['type_id']);
    		$count=$news->where($news_where)->count();
    		if($count<1){
    			unset($product_list[$key]);
    		}else{
    			$icon=get_news($list['type_id'],1,'icon');
	    		$product_list[$key]['images']=C('NEWIMGURL').$icon[0]['icon'];
	    		$product_list[$key]['count']=$count;
    		}
    	}
    	$this->assign("product_list",$product_list);
    	$this->assign("seo_title",'团队风采');
		$this->display('product');
    }

    /*
    *品牌介绍
    */
    public function brand(){
    	$this->assign('brand_list',get_news(17,10,'id,title,content'));
    	$this->assign("ad_list",get_news(20,3,'icon,title'));
    	$this->assign("seo_title",'品牌介绍');
		$this->display();
    }


    /*
    *关于我们
    */
    public function about_us(){
    	$code=$this->_get('code');
		if(!empty($code)){
	    	$open_id=get_openid($code);
		}elseif(!empty($_GET['weixin_key'])){
			session('weixin_key',$_GET['weixin_key']);
		}
    	$list=get_news(11,1,'id,content');
    	$this->assign('list',$list);
		$this->display();
    }

    /*
    *常见问题
    */
    public function question(){
    	$code=$this->_get('code');
		if(!empty($code)){
	    	$open_id=get_openid($code);
		}elseif(!empty($_GET['weixin_key'])){
			session('weixin_key',$_GET['weixin_key']);
		}
    	$list=get_news(7,30,'id,title,content');
    	$this->assign('list',$list);
		$this->display();
    }



    public function home(){
		#$this->valid(); #验证
		$this->responseMsg();
    }

	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        }else{
        	echo 'error';
        }
        exit;
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr))
        {

            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername   = $postObj->FromUserName;
            $toUsername     = $postObj->ToUserName;
            $keyword        = trim($postObj->Content);
            $Location_X     = $postObj->Location_X;
            $Location_Y     = $postObj->Location_Y;
            $time           = time();

            $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";

            $newsTpl ='<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime><![CDATA[%s]]></CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <ArticleCount>1</ArticleCount>
            <Articles>
            <item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
            </item>
            </Articles>
            </xml> ';

            $event=$postObj->Event;
            if($event=="subscribe")
            {//首次关注
                $APPID=C('APPID');
                $SECRET=C('SECRET');
                $access_token=curl_get("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$SECRET");
                $access_token=@get_object_vars(@json_decode($access_token));
                $user_info=curl_get("https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token[access_token]&openid=$fromUsername");
                $user_info=@get_object_vars(@json_decode($user_info));
                $user=M('user');
                $u_where['openid']=array('eq',"$fromUsername");
                $u_res=$user->field('user_id')->where($u_where)->find();

                /*if(isset($postObj->EventKey))
                {
                $event_key  = $postObj->EventKey;
                $key_arr    = explode('_',$event_key);
                $code_id    = $key_arr[1];
                }
                else
                {
                $code_id=0;
                }*/

                if(!$u_res)
                {
                     $add_data['openid']    = "$fromUsername";
                     $add_data['username']  = $user_info[nickname];
                     $add_data['sex']       = $user_info[sex];
                     $add_data['headimgurl']=$user_info[headimgurl];
                     $add_data['city_name'] =$user_info[city];
                     //$user_data['nickname']=$ures[nickname];
                     $add_data['create_time']= time();
                     $add_data['status']    = 0;
                     $user_id   =   $user->add($add_data);

                    //生成默认的会员卡
                    $vip_card = M("vip_card");
                    $vip_data['user_id']    = $user_id;
                    $vip_data['openid']     = "$fromUsername";
                    $vip_data['card_no']    = vip_card_no(4);
                    $vip_card->add($vip_data);
                    //echo $vip_card->getLastSql();


                   /* $msgType = "text";
                    //$contentStr = "欢迎来到你的家啊！";
                    $contentStr = $vip_card->getLastSql();
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType,$contentStr);
                    echo  $resultStr;
                    die();*/
                }
                else
                {
                     $add_data['username']  = $user_info[nickname];
                     $add_data['sex']       = $user_info[sex];
                     $add_data['headimgurl']=$user_info[headimgurl];
                     $add_data['city_name'] =$user_info[city];
                     $add_data['create_time']=time();
                     $user->where("user_id=".$u_res[user_id])->save($add_data);
                     $user_id   =   $u_res['user_id'];
                }

                if(isset($postObj->EventKey))
                {
                    $user_sum   = M('user_sum');
                    $s_res      = $user_sum->where("user_id=$user_id")->find();

                  /*  $code       = M('code');
                    $code_res   = $code->where('id='.$code_id)->find();*/

                   /* if($code_res['user_id']>0)
                    {
                        $user_save_data['tg_user']=$code_res['user_id'];
                        $user->where("user_id=".$u_res[user_id])->save($user_save_data);
                    }*/

                   /* if($s_res){
                        $add_data['level']=$code_res['vip_id'];
                        $add_data['label']=$code_res['label_id'];
                        $user_sum->where("user_id=".$s_res[user_id])->save($add_data);
                    }else{*/
                    if (!$s_res)
                    {
                        $add_data['user_id']    = $user_id;
                        $add_data['vip']        = 1; //会员优惠政策
                        $add_data['level']      = 1; //默认为红心会员
                        /*$add_data['label']=$code_res['label_id'];*/
                        $user_sum->add($add_data);
                    }

                    /*$user_label_rel=M('user_label_rel');
                    $del_where['user_id']=array('eq',$user_id);
                    $del_res=$user_label_rel->where($del_where)->delete();
                    $label_arr=explode(',',$code_res['label_id']);
                    $data_label='';
                    foreach ($label_arr as $list){
                       $data_label[]=array(
                            'user_id' => $user_id,
                            'label_id' =>$list
                        );
                    }
                   $res2=$user_label_rel->addAll($data_label);*/

                   /* $info_id=$code_res['info_id'];
                    $info=M('info');
                    $info_res=$info->where("id=$info_id")->find();
                    if($info_res[type_id]==1)
                    {
                        $msgType = "news";
                        $info_res[link_url]=$info_res[link_url].'openid/'.$fromUsername;
                        $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType,$info_res['title'],$info_res['content'],"$info_res[link_url]/$info_res[images]",$info_res['link_url']);
                    }else{
                        $msgType = "text";
                        if($info_res[url]!=''){
                            $info_res[link_url]=$info_res[link_url].'openid/'.$fromUsername;
                            $contentStr = "<a href='$info_res[link_url]'>$info_res[content]</a>";
                        }else{
                            $contentStr =$info_res[content];
                        }
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType,$contentStr);
                    }*/
                    $replay_info = '欢迎关注蜜熙甜品。本店的微信在线订单系统即将上线，敬请关注。';
                    $config =  M("config");

                    $rs = $config->where('id = 1')->find();
                    if (!empty($rs['replay_info']))
                    {
                        $replay_info = $rs['replay_info'];
                    }

                    $msgType = "text";
                    $contentStr = $replay_info;
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType,$contentStr);
                    echo  $resultStr;
                }
            }
            elseif($event=='CLICK')
            {
                     $eventKey=$postObj->EventKey;
                     $msgType = "text";
                     $app_menu=M('app_menu');
                     $app_where['key_url']=array('eq',"$eventKey");
                     $app_res=$app_menu->field('name,remark')->where($app_where)->find();
                     $appcontent=trim($app_res['remark']);
                     if($eventKey=='forum'){
                         $msgType='news'; //$lng=114.177244;      #经度(必填  $lat
                         $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType,'育儿互助帮',$appcontent,'http://img1.mbabycare.com/uploadUrl/201212/121709320efk.jpg',"http://weixin.leg3s.com/forum/index/user/$fromUsername/lng/$Location_Y/lat/$Location_X");
                         echo  $resultStr;
                         exit;
                     }
                     if(!empty($appcontent)){
                         //$contentStr = $appcontent;
                         $name=$app_res['name'];
                         $contentStr = "点击进入-><a href='$appcontent/weixin_key/".$fromUsername."/'>$name</a>";
                         $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                         echo  $resultStr;
                     }
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, '你好');
                         echo  $resultStr;
            }
            else
            {
                 if(isset($postObj->EventKey))
                 {
                    $code_id=$postObj->EventKey;
                    $user=M('user');
                    $u_where['openid']=array('eq',"$fromUsername");
                    $u_res=$user->field('user_id,tel')->where($u_where)->find();
                    $add_data['code_id']=$code_id;
                    $user->where("user_id=".$u_res[user_id])->save($add_data);
                    $user_id=$u_res['user_id'];

                    $user_sum=M('user_sum');
                    $s_res=$user_sum->where("user_id=$user_id")->find();

                    $code=M('code');
                    $code_res=$code->where('id='.$code_id)->find();

                    if($code_res['user_id']>0)
                    {
                        $tel_res=$user->where("user_id=".$code_res['user_id'])->field("tel")->find();
                        $user_save_data['tg_user']=$code_res['user_id'];
                        $user->where("user_id=$user_id")->save($user_save_data);
                    }

                    if($s_res)
                    {
                        $add_data['level']=$code_res['vip_id'];
                        $add_data['label']=$code_res['label_id'];
                        $user_sum->where("user_id=".$s_res[user_id])->save($add_data);
                    }
                    else
                    {
                        $add_data['user_id']=$user_id;
                        $add_data['level']=$code_res['vip_id'];
                        $add_data['label']=$code_res['label_id'];
                        $user_sum->add($add_data);
                    }

                    $user_label_rel=M('user_label_rel');
                    $del_where['user_id']=array('eq',$user_id);
                    $del_res=$user_label_rel->where($del_where)->delete();
                    $label_arr=explode(',',$code_res['label_id']);
                    $data_label='';
                    foreach ($label_arr as $list)
                    {
                       $data_label[]=array(
                            'user_id' => $user_id,
                            'label_id' =>$list
                        );
                    }
                   $res2=$user_label_rel->addAll($data_label);

                    $info_id=$code_res['info_id'];
                    $info=M('info');
                    $info_res=$info->where("id=$info_id")->find();
                    if($info_res[type_id]==1)
                    {
                        $msgType = "news";
                        $info_res[link_url]=$info_res[link_url].'openid/'.$fromUsername;
                        $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType,$info_res['title'],$info_res['content'],"$info_res[link_url]/$info_res[images]",$info_res['link_url']);
                    }
                    else
                    {
                        $msgType = "text";
                        if($info_res[url]!='')
                        {
                            $info_res[link_url]=$info_res[link_url].'openid/'.$fromUsername;
                            $contentStr = "<a href='$info_res[link_url]'>$info_res[content]</a>";
                        }else
                        {
                            $contentStr =$info_res[content];
                        }
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType,$contentStr);
                    }
                    echo  $resultStr;exit;
                 }
                 else
                 {
                    if(!empty($keyword))
                    {
                        $repay_data['content']=$keyword;
                        $repay_data['openid']="$fromUsername";
                        $repay_data['create_time']=time();
                        $user_reply=M('user_reply');
                        $user_reply->add($repay_data);
                    }
                 }
            }
                echo "Input something...";
            }
        else
        {
                echo "";
                exit;
        }
    }

	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

		$token = $this->TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );

		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}
