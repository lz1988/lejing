<?php
/**
 * @author  dapeng.chen
 * @data   2013-07-11
 *
 */


// 本类由系统自动生成，仅供测试用途
class UserAction extends PublicAction {

	/**
	 * 会员卡
	 * **/
	/*public function index(){
		if(session('openid')==''){
    		$code=$this->_get('code');
    		if(!empty($code)){
		    	$open_id=get_openid($code);
			}elseif(!empty($_GET['openid'])){
				session('openid',$_GET['openid']);
			}
    	}
		$this->assign("seo_title",'用户中心');
		$this->display();
	}*/

    /**@title生成订单操作
     * @author lizhi
     * @create on 2015-03-10
     */
    public function done()
    {
       /* echo '<pre>';
        print_r($_POST);
        die();*/
        if(session('openid')=='')
        {
            $code=$this->_get('code');
            if(!empty($code)){
                $open_id=get_openid($code);
            }elseif(!empty($_GET['openid'])){
                session('openid',$_GET['openid']);
            }
        }

        $orders_item = M("orders_item");

        //购物车列表
        $item_cart = M("item_cart");
        $where['openid'] = session('openid');
        $cart_data_list = $item_cart->where($where)->select();

        //检查购物车产品是否一致
        $shop_id    = $cart_data_list[0]['shop_id'];
       /* echo '<pre>';print_r($shop_id);
        die();*/

        if (empty($cart_data_list))
        {
            $this->error("购物车产品为空");
        }

        $total_price = 0;

        foreach($cart_data_list as $k=>$v)
        {
            $total_price += $v['item_price'] * $v['item_num'];
        }

        $order_sn = date('Ymd').generate_code(6);

        //订单明细
        $orders = M("orders");
        $add_order['orders_no']     = $order_sn;
        $add_order['openid']        = session('openid');
        $add_order['create_time']   = time();
        $add_order['amount']        = $total_price;
        $add_order['user_ip']       = get_client_ip();

        $add_order['tel']           = $_POST['tel'];
        $add_order['consignee']     = $_POST['consignee'];
        $add_order['province_id']   = $_POST['province_id'];
        $add_order['city_id']       = $_POST['city_id'];
        $add_order['district_id']   = $_POST['district_id'];
        $add_order['address']       = $_POST['address'];
        $add_order['remark']        = $_POST['remark'];
        $add_order['send_date']     = $_POST['send_date'];
        $add_order['send_time']     = $_POST['send_time'];
        $add_order['shop_id']       = $shop_id;
        //ECHO '<pre>';print_r($_POST);die();
        $insert_id = $orders->add($add_order);

        $_SESSION['order_id'] = $insert_id;
        //session('orders_id',$insert_id);
        //echo $orders->getLastSql();die();

        //订单产品明细
        foreach($cart_data_list as $key=>$val)
        {
            $add_orders_item['orders_id']   = $insert_id;
            $add_orders_item['item_id']     = $val['item_id'];
            $add_orders_item['buy_num']     = $val['item_num'];
            $add_orders_item['buy_price']   = $val['item_price'];
            $add_orders_item['item_size']   = $val['item_size'];
            $add_orders_item['item_state']  = $val['item_state'];
            $orders_item->add($add_orders_item);
            //echo $orders_item->getLastSql();
        }

        //清空购物车数据
        $item_cart_del = M("item_cart");
        $item_cart_del->where('openid="'.session('openid').'"')->delete();

        $this->redirect('/user/my_orders',array('orders_id'=>$insert_id));
    }


    /**
     * @return mixed
     * @title 获取购物车信息
     * @author lizhi
     * @create on 2015-03-08
     */
    public function cart_info()
    {
        //购物车列表
        $item_cart = M("item_cart");
        $where['openid'] = session('openid');
        $cart_data_list = $item_cart->where($where)->select();
        return $cart_data_list;
    }


    /**
     * 订单明细
     */
	public function my_orders(){

        if (!$this->_get('orders_id'))
        {
            $this->redirect('/');
        }
        if(session('openid')==''){
    		$code=$this->_get('code');
    		if(!empty($code)){
		    	$open_id=get_openid($code);
			}elseif(!empty($_GET['openid'])){
				session('openid',$_GET['openid']);
			}
    	}

		$orders = M("orders");
        $orders_where['openid']     = array('eq',session('openid'));
        $orders_where['orders_id']  = $this->_get('orders_id');//session('orders_id');
        $orders_list = $orders->where($orders_where)->field('orders_id,orders_no,create_time,amount,shop_id')->order('create_time desc')->find();
        //echo $orders->getLastSql();

        $orders_item=M('orders_item');
        //foreach($orders_list as $key=>$list){
        $oi_where['o.orders_id']=array('eq',$orders_list['orders_id']);
        $oi_list=$orders_item->join('as o LEFT JOIN cms_item as i ON o.item_id=i.item_id')->where($oi_where)->field('o.buy_num,o.buy_price,i.item_name,i.icon')->select();
        $total_price  = 0;
        foreach ($oi_list as $key=>$oi)
        {
            $total_price = $total_price + $oi['buy_price'] * $oi['buy_num'];
        }

        $vip_card = M("vip_card");
        $arr_money = $vip_card->where('user_id ='.session('user_id'))->find();
        //echo $vip_card->getLastSql();

        $this->assign('arr_money',$arr_money);
        $this->assign("orders_list",$orders_list);
        $this->assign("oi_list",$oi_list);
        $this->assign('total_price',number_format($total_price,2));
        if ($orders_list['shop_id'] != 85)
        {

            $this->assign('title','堂食订单');
            $this->display('my_orders_tangshi');
        }
        else
        {
            $this->assign('title','外卖订单');
            $this->display('my_orders');
        }
	}

	public function orders_detail(){
		$orders_id=$this->_get('orders_id');

		$this->display();
	}

	public function validation_orders(){
		if(session('openid')==''){
    		$code=$this->_get('code');
    		if(!empty($code)){
		    	$open_id=get_openid($code);
			}elseif(!empty($_GET['openid'])){
				session('openid',$_GET['openid']);
			}
    	}

		$user_validation=M('user_validation');
		$val_where['openid']=array('eq',session('openid'));
		$val_list=$user_validation->where($val_where)->order('create_time desc')->select();
		$this->assign("val_list",$val_list);
		$this->display();
	}


    public function orders_success(){
    	$orders_id=$this->_get('orders_id');
    	$where['orders_id']=array('eq',$orders_id);

    	$orders_user=M('orders_user');
    	$user_list=$orders_user->where($where)->select();
    	$this->assign("user_list", $user_list);

    	$where['weixin_key']=array('eq',session('weixin_key'));
    	$orders=M('orders');
    	$orders_list=$orders->where($where)->order('create_time DESC')->find();
    	$this->assign("list", $orders_list);
        $this->display();
    }


    /**
     * 微信支付
     */
    public function pay()
    {
        /**
         * JS_API支付demo
         * ====================================================
         * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
         * 成功调起支付需要三个步骤：
         * 步骤1：网页授权获取用户openid
         * 步骤2：使用统一支付接口，获取prepay_id
         * 步骤3：使用jsapi调起支付
         */
        //include_once("../WxPayPubHelper/WxPayPubHelper.php");
        //include_once("../WxPayPubHelper/WxPayPubHelper.php");
        import('@.ORG.wxpay.WxPayPubHelper.WxPayPubHelper');

        //使用jsapi接口
        $jsApi = new JsApi_pub();

        //=========步骤1：网页授权获取用户openid============
        //通过code获得openid
        if (!isset($_GET['code'])) {
            //触发微信返回code码
            $url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL);
            Header("Location: $url");
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $jsApi->setCode($code);
            $openid = $jsApi->getOpenId();
        }

        //=========步骤2：使用统一支付接口，获取prepay_id============
        //使用统一支付接口
        $unifiedOrder = new UnifiedOrder_pub();

        //设置统一支付接口参数
        //设置必填参数
        //appid已填,商户无需重复填写
        //mch_id已填,商户无需重复填写
        //noncestr已填,商户无需重复填写
        //spbill_create_ip已填,商户无需重复填写
        //sign已填,商户无需重复填写
        /* $openid = 'oEw2Ut0xvD3YjW8pQ4C5zoA75B6w';
          $out_trade_no = '123214511223214';
          $jiage=0.1; */

        $orders = M("orders");
        $orders_where['openid']     = array('eq',session('openid'));
        $orders_where['orders_id']  = session('order_id');//session('orders_id');
        $orders_list = $orders->where($orders_where)->field('orders_id,orders_no,create_time,amount')->order('create_time desc')->find();
        //echo $orders->getLastSql();

        $orders_item=M('orders_item');
        //foreach($orders_list as $key=>$list){
        $oi_where['o.orders_id']=array('eq',$orders_list['orders_id']);
        $oi_list=$orders_item->join('as o LEFT JOIN cms_item as i ON o.item_id=i.item_id')->where($oi_where)->field('o.buy_num,o.buy_price,i.item_name,i.icon')->select();
        $total_price    = 0;
        $total_num      = 0;
        foreach ($oi_list as $key=>$oi)
        {
            $total_price    = $total_price + $oi['buy_price'] * $oi['buy_num'];
            $total_num     += $oi['buy_num'];
        }


        $unifiedOrder->setParameter("openid", "$openid"); //openid
        $unifiedOrder->setParameter("body", "test one"); //商品描述
        //自定义订单号，此处仅作举例
        $timeStamp = time();
        $out_trade_no = WxPayConf_pub::APPID . "$timeStamp";
        $unifiedOrder->setParameter("out_trade_no", "$out_trade_no"); //商户订单号
        $unifiedOrder->setParameter("total_fee", 0.1 * 100); //总金额
        $unifiedOrder->setParameter("notify_url", WxPayConf_pub::NOTIFY_URL . "/order_sn/{$_SESSION['order_id']}"); //通知地址
        $unifiedOrder->setParameter("trade_type", "JSAPI"); //交易类型

        $prepay_id = $unifiedOrder->getPrepayId();
        //=========步骤3：使用jsapi调起支付============
        $jsApi->setPrepayId($prepay_id);

        $jsApiParameters = $jsApi->getParameters();

        $this->assign("orders_list",$orders_list);
        $this->assign("oi_list",$oi_list);
        $this->assign('total_price',number_format($total_price,2));
        $this->assign('total_num',$total_num);
        $this->assign('jsApiParameters', $jsApiParameters);
        $this->display();


       /* $orders = M("orders");
        $orders_where['openid']     = array('eq',session('openid'));
        $orders_where['orders_id']  = $this->_get('orders_id');//session('orders_id');
        $orders_list = $orders->where($orders_where)->field('orders_id,orders_no,create_time,amount')->order('create_time desc')->find();
        //echo $orders->getLastSql();

        $orders_item=M('orders_item');
        //foreach($orders_list as $key=>$list){
        $oi_where['o.orders_id']=array('eq',$orders_list['orders_id']);
        $oi_list=$orders_item->join('as o LEFT JOIN cms_item as i ON o.item_id=i.item_id')->where($oi_where)->field('o.buy_num,o.buy_price,i.item_name,i.icon')->select();
        $total_price    = 0;
        $total_num      = 0;
        foreach ($oi_list as $key=>$oi)
        {
            $total_price    = $total_price + $oi['buy_price'] * $oi['buy_num'];
            $total_num     += $oi['buy_num'];
        }

        $this->assign("orders_list",$orders_list);
        $this->assign("oi_list",$oi_list);
        $this->assign('total_price',number_format($total_price,2));
        $this->assign('total_num',$total_num);
        $this->display();*/
    }

    public function order_success()
    {
        $this->display();
    }

    // 打印log
    function log_result($file, $word) {
        $fp = fopen($file, "a");
        flock($fp, LOCK_EX);
        fwrite($fp, "执行日期：" . strftime("%Y-%m-%d-%H：%M：%S", time()) . "\n" . $word . "\n\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    //记录支付日志
    public function write_log() {

        /**
         * 通用通知接口demo
         * ====================================================
         * 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
         * 商户接收回调信息后，根据需要设定相应的处理流程。
         *
         * 这里举例使用log文件形式记录回调信息。
         */
        //import('@.ORG.wxpay.WxPayPubHelper.demo.log_.php');
        import('@.ORG.wxpay.WxPayPubHelper.WxPayPubHelper');
        //include_once("./log_.php");
        //include_once("../WxPayPubHelper/WxPayPubHelper.php");
        //使用通用通知接口
        $notify = new Notify_pub();

        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);

        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if ($notify->checkSign() == FALSE) {
            $notify->setReturnParameter("return_code", "FAIL"); //返回状态码
            $notify->setReturnParameter("return_msg", "签名失败"); //返回信息
        } else {
            $notify->setReturnParameter("return_code", "SUCCESS"); //设置返回码
        }
        $returnXml = $notify->returnXml();
        echo $returnXml;

        //==商户根据实际情况设置相应的处理流程，此处仅作举例=======
        //以log文件形式记录回调信息
        //$log_ = new Log_();
        $log_name = "./Home/Lib/ORG/wxpay/demo/notify_url.log"; //log文件路径
        $this->log_result($log_name, "【接收到的notify通知】:\n" . $xml . "\n");

        if ($notify->checkSign() == TRUE) {

            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                $this->log_result($log_name, "【通信出错】:\n" . $xml . "\n");
            } elseif ($notify->data["result_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                $this->log_result($log_name, "【业务出错】:\n" . $xml . "\n");
            } else {

                //更新商户订单号到order表,并修改订单状态
                $order = M("orders");
                $rs = $order->where("orders_id = '" . $_REQUEST['order_sn'] . "'")->find();

                //获取当前订单用户信息
                $user = M("user");
                $UserInfo = $user->where("openid='".$rs['openid']."'")->find();
                //ECHO $order->getLastSql();

                if ($rs) {
                    $save['trade_no'] = $notify->data["out_trade_no"];
                    $save['pay_status'] = 1;
                    $save['pay_time'] = date('Y-m-d H:i:s');
                    $save['transaction_id'] = $notify->data["transaction_id"];
                    $r = $order->where("orders_id = '" . $_REQUEST['order_sn'] . "'")->save($save);

                    if ($r && $UserInfo)
                    {
                        $vip_score = M("vip_score");
                        $add['openid'] = $UserInfo['openid'];
                        $add['uid'] = $UserInfo['user_id'];
                        $add['event_code'] = 'pay';
                        $add['score'] = $rs['amount'];
                        $add['create_time'] = time();
                        $add['update_time'] = time();
                        $add['descri']  = "订单号为：".$rs['orders_no']."&nbsp;收件人：".$rs['consignee']."&nbsp;电话：".$rs['tel']."&nbsp;地址：".$rs['address'];
                        $vip_score->add($add);

                        //更新积分详情表
                        $user_score=M('user_score');
                        $res = $user_score->where("user_id=".$UserInfo['user_id'])->find();
                        if($res){
                            $user_score->where("user_id=".$UserInfo['user_id'])->setInc('score',$rs['amount']);
                            $user_score->where("user_id=".$UserInfo['user_id'])->setInc('score_total',$rs['amount']);
                            //echo $user_score->getLastSql();die();
                        }else{
                            $sorce_data['user_id'] = $UserInfo['user_id'];
                            $sorce_data['score_total']=(int)$rs['amount'];
                            $sorce_data['score'] = (int)$rs['amount'];
                            //$user_score->setInc('score',$add_date['score']);
                            $user_score->add($sorce_data);
                            //echo $user_score->getLastSql();die();
                        }

                        //更新用户等级：红心会员，皇冠会员等
                        $vip_define=M('vip_define');
                        $cur_num=(int) $res['score_total']+$rs['amount'];
                        $vip_where['min_score']=array('egt',$cur_num);
                        $vip_res=$vip_define->field('id,name')->where($vip_where)->order('min_score DESC')->find();
                        if(empty($vip_res))
                        {
                            $vip_res=$vip_define->field('id,name')->order('min_score DESC')->find();
                        }

                        $user_sum=M('user_sum');
                        $sum_data['level']=$vip_res['id'];
                        $user_sum->where("user_id=".$UserInfo['user_id'])->save($sum_data);
                    }
                    //ECHO $order->getLastSql();
                }

                //此处应该更新一下订单状态，商户自行增删操作
                $this->log_result($log_name, "【支付成功】:" . $order->getLastSql() . "\n" . $xml . "\n");
            }

            //商户自行增加处理流程,
            //例如：更新订单状态
            //例如：数据库操作
            //例如：推送支付完成信息
        }
    }

    public function member_recharge_pay()
    {
        //$cardcode = str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_RIGHT);
        /**
         * JS_API支付demo
         * ====================================================
         * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
         * 成功调起支付需要三个步骤：
         * 步骤1：网页授权获取用户openid
         * 步骤2：使用统一支付接口，获取prepay_id
         * 步骤3：使用jsapi调起支付
         */
        //include_once("../WxPayPubHelper/WxPayPubHelper.php");
        //include_once("../WxPayPubHelper/WxPayPubHelper.php");
        import('@.ORG.wxpay.WxPayPubHelper.WxPayPubHelper');

        //使用jsapi接口
        $jsApi = new JsApi_pub();

        //=========步骤1：网页授权获取用户openid============
        //通过code获得openid
        if (!isset($_GET['code'])) {
            //触发微信返回code码
            $url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL_RECHARGE);
            Header("Location: $url");
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $jsApi->setCode($code);
            $openid = $jsApi->getOpenId();
        }

        //=========步骤2：使用统一支付接口，获取prepay_id============
        //使用统一支付接口
        $unifiedOrder = new UnifiedOrder_pub();

        //取出当前订单的充值信息
        $user_recharge = M("user_recharge");
        $result = $user_recharge->where('order_recharge_sn = "'.$_SESSION['order_recharge_sn'].'"')->find();
        $charge_remark = "充值：".int($result['amount']);

        $unifiedOrder->setParameter("openid", "$openid"); //openid
        $unifiedOrder->setParameter("body", $charge_remark); //商品描述
        //自定义订单号，此处仅作举例
        $timeStamp = time();
        $out_trade_no = WxPayConf_pub::APPID . "$timeStamp";
        $unifiedOrder->setParameter("out_trade_no", "$out_trade_no"); //商户订单号
        $unifiedOrder->setParameter("total_fee", 0.1 * 100); //总金额
        $unifiedOrder->setParameter("notify_url", WxPayConf_pub::NOTIFY_URL_RECHARGE . "/order_recharge_sn/{$_SESSION['order_recharge_sn']}"); //通知地址
        $unifiedOrder->setParameter("trade_type", "JSAPI"); //交易类型

        $prepay_id = $unifiedOrder->getPrepayId();
        //=========步骤3：使用jsapi调起支付============
        $jsApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsApi->getParameters();
        $this->assign('jsApiParameters', $jsApiParameters);

        $this->display();
    }

    //关于我们
    public function about()
    {
        $this->display();
    }

    //联系我们
    public function contact()
    {
        $this->display();
    }

    /**
     * @title 会员卡充值
     * @author lizhi
     * @create on 2015-03-08
     */
    public function member_recharge()
    {
        if(session('openid')==''){
            $code=$this->_get('code');
            if(!empty($code)){
                $open_id=get_openid($code);
            }elseif(!empty($_GET['openid'])){
                session('openid',$_GET['openid']);
            }
        }

        if (empty($_SESSION['user_id']))
        {
            $this->error("用户身份存在异常，请稍后再试");
        }

        if ($this->isPost())
        {
            $amount = $_POST['amount'];
            if ($amount == 0)
            {
                $this->error("对不起，请选择充值的金额");
            }

            $user_recharge = M("user_recharge");
            $add['uid'] = $_SESSION['user_id'];
            $add['order_recharge_sn'] = 'CH'.generate_code(8);
            $add['openid'] = $_SESSION['openid'];
            $add['amount'] = $amount;
            $add['recharge_status'] = '0';
            $r = $user_recharge->add($add);

            if ($r !== false){
                $_SESSION['order_recharge_sn'] = $add['order_recharge_sn'];
                $this->redirect('/user/member_recharge_pay');
            }
        }
        $this->display();
    }

    //记录支付日志
    public function write_log_recharge() {

        /**
         * 通用通知接口demo
         * ====================================================
         * 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
         * 商户接收回调信息后，根据需要设定相应的处理流程。
         *
         * 这里举例使用log文件形式记录回调信息。
         */
        //import('@.ORG.wxpay.WxPayPubHelper.demo.log_.php');
        import('@.ORG.wxpay.WxPayPubHelper.WxPayPubHelper');
        //include_once("./log_.php");
        //include_once("../WxPayPubHelper/WxPayPubHelper.php");
        //使用通用通知接口
        $notify = new Notify_pub();

        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);

        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if ($notify->checkSign() == FALSE) {
            $notify->setReturnParameter("return_code", "FAIL"); //返回状态码
            $notify->setReturnParameter("return_msg", "签名失败"); //返回信息
        } else {
            $notify->setReturnParameter("return_code", "SUCCESS"); //设置返回码
        }
        $returnXml = $notify->returnXml();
        echo $returnXml;

        //==商户根据实际情况设置相应的处理流程，此处仅作举例=======
        //以log文件形式记录回调信息
        //$log_ = new Log_();
        $log_name = "./Home/Lib/ORG/wxpay/demo/notify_url.log"; //log文件路径
        $this->log_result($log_name, "【接收到的notify通知】:\n" . $xml . "\n");

        if ($notify->checkSign() == TRUE) {

            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                $this->log_result($log_name, "【通信出错】:\n" . $xml . "\n");
            } elseif ($notify->data["result_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                $this->log_result($log_name, "【业务出错】:\n" . $xml . "\n");
            } else {

                //更新用户充值记录
                $user_recharge = M("user_recharge");
                $rs = $user_recharge->where("order_recharge_sn = '" . $_REQUEST['order_recharge_sn'] . "'")->find();
                $amount = (int)$rs['amount'];
                $arr_amount_remark = array('100'=>'充100送30','500'=>'充500送100');
                $remarks = $arr_amount_remark[$amount] ? $arr_amount_remark[$amount] : '暂无备注';

                if ($rs) {
                    $save['trade_no'] = $notify->data["out_trade_no"];
                    $save['recharge_status'] = 1;
                    $save['recharge_time'] = date('Y-m-d H:i:s');
                    $save['transaction_id'] = $notify->data["transaction_id"];
                    $save['remark'] = $remarks;
                    $result = $user_recharge->where("order_recharge_sn = '" . $_REQUEST['order_recharge_sn'] . "'")->save($save);

                    if ($amount == 100)
                    {
                        $money = 130;
                    }
                    elseif($amount == 500)
                    {
                        $money = 600;
                    }
                    else
                    {
                        $money = 0;
                    }

                    if ($result !== false)
                    {
                        //更新用户的总余额
                        $user_sum = M("user_sum");
                        $user_sum->where('user_id = '.$rs['uid'])->setInc('recharge_money',$money);
                    }else{
                        $this->log_result($log_name, "【更新用户充值记录】:" . $user_recharge->getLastSql());
                    }

                    //ECHO $order->getLastSql();
                }

                //此处应该更新一下订单状态，商户自行增删操作
                $this->log_result($log_name, "【支付成功】:" . $user_recharge->getLastSql() . "\n" . $xml . "\n");
            }

            //商户自行增加处理流程,
            //例如：更新订单状态
            //例如：数据库操作
            //例如：推送支付完成信息
        }
    }

    public function test()
    {
        //更新用户充值记录
        $user_recharge = M("user_recharge");
        $rs = $user_recharge->where("order_recharge_sn = '" . $_REQUEST['order_recharge_sn'] . "'")->find();
        $arr_amount_remark = array('100'=>'充100送30','500'=>'充500送100');
        $amount = (int)$rs['amount'];
        var_dump($arr_amount_remark[$amount]);

        if ($amount == 100)
        {
            $money = 130;
        }elseif($amount == 500){
            $money = 600;
        }else{
            $money = 0;
        }

        //更新用户的总余额
        $user_sum = M("user_sum");
        $user_sum->where('user_id = '.$rs['uid'])->setInc('recharge_money',$money);
        echo $user_sum->getLastSql();
    }
    public function user_recharge_success()
    {
        $this->display();
    }
    /**
     * 会员卡支付
     */
    public function cart_pay()
    {
        $orders_id = $this->_get('orders_id');
        $orders = M("orders");
        $arr_orders = $orders->where('orders_id = '.$orders_id)->find();
        //echo $orders->getLastSql();
        if (!$arr_orders)
        {
            die("订单无效！");
        }

        //检测会员卡金额是否充足
        $vip_card = M("vip_card");
        $card_money = $vip_card->where('user_id = '.session('user_id'))->find();
        if ($card_money['card_money'] < $arr_orders['amount'])
        {
            //die("对不起，您的会员卡金额不足，请及时充值！");
            $this->error("对不起，您的会员卡金额不足，请及时充值！");
        }

        $account_log = M('account_log');
        $data['user_id'] = session('user_id');
        $data['openid'] = session('openid');
        $data['user_money'] = -1 * abs(floatval($arr_orders['amount']));
        $data['change_time'] = time();
        $data['change_desc'] = "使用会员卡".$card_money['card_no']."支付".$arr_orders['amount'];
        $account_log->add($data);
        //echo $account_log->getLastSql();die();
        //修改用户会员卡余额
        $res = $vip_card->where('user_id = '.session('user_id'))->setDec('card_money',$arr_orders['amount']);
        //echo $vip_card->getLastSql();

        //修改订单信息
        $save['pay_type'] = 2;//会员卡支付
        $save['pay_status'] = 1;
        $save['pay_time'] = time();
        $orders->where('orders_id = '.$orders_id)->save($save);

        if ($res !== false)
        {

            $this->display('pay_success');
        }else{
            $this->error("支付失败！");
        }

    }

    //用户信息
    public function userinfo()
    {

        if(session('openid')=='')
        {
            $code=$this->_get('code');
            if(!empty($code)){
                $open_id=get_openid($code);
            }elseif(!empty($_GET['openid'])){
                session('openid',$_GET['openid']);
            }
        }
        //用户积分
        $user_score = $this->get_user_score();

        //用户生日礼包
        $birth_gift = $this->get_user_birth_gift();

        //会员等级
        $vip_level = $this->vip_level();

        //余额
        $user_account = $this->get_account();

        //会员卡号
        $vip_card = $this->get_vip_card();

        $coupon_num = $this->get_coupon();
        $this->assign('coupon_num',$coupon_num);


        //echo '<pre>';print_r($birth_gift);die();
        //var_dump($user_score);
        $this->assign('user_score',$user_score);
        $this->assign('birth_gift',$birth_gift);
        $this->assign('vip_level',$vip_level);
        $this->assign('user_account',$user_account);
        $this->assign('vip_card',$vip_card);
        $this->assign('title','个人中心');

        $this->display();
    }

    /**
     * @title 完善个人信息
     * @author lizhi
     * @create on 2015-03-08
     */
    public function perfect_profile()
    {
        $userinfo = $this->get_userinfo();
        $this->assign('userinfo',$userinfo);

        $user_birth_gift    = M("user_birth_gift");
        $count = $user_birth_gift->count();

        $this->assign('count',$count);
        $this->assign('title',"完善个人资料");

        $this->display();
    }

    /**
     * @title 修改用户个人信息
     */
    public function edit_perfect_profile()
    {
        if(session('openid')=='')
        {
            $code=$this->_get('code');
            if(!empty($code)){
                $open_id=get_openid($code);
            }elseif(!empty($_GET['openid'])){
                session('openid',$_GET['openid']);
            }
        }

        //print_r($_POST);die();
        $tel = $this->_post('tel');
        $nick_name = $this->_post('nickname');
        $sex = $this->_post('sex');
        $qq = $this->_post('qq');
        $nian = $this->_post('year');
        $yue = $this->_post('month');
        $ri = $this->_post('day');

        $user_birth_gift    = M("user_birth_gift");

        $user_birth_gift_count = $user_birth_gift->count();
        if ($user_birth_gift_count == 0)
        {
            $user = M("user");
            $data = $user->where('user_id="'.session('user_id').'"')->find();
            /*echo $user->getLastSql();
            print_r($_SESSION);
            var_dump($data);die();*/
            //var_dump($data);die();
            if ($data)
            {
                //DIE('111');
                $add['tel'] = $tel;
                $add['nick_name'] = $nick_name;
                $add['sex'] = $sex;
                $add['qq'] = $qq;
                $add['create_time'] = time();
                $add['openid'] = session('openid');
                //$add['username'] = session('username');
                $add['nian']     = $nian;
                $add['yue']     = $yue;
                $add['ri']      = $ri;
                $user->where("user_id ='".session('user_id')."'")->save($add);
                //echo $user->getLastSql();die();

                //新增生日礼包
                $add['birth_name']  = $nian.$yue.$ri."生日大礼包";
                $add['birth_date']  = $nian.$yue.$ri;
                $add['openid']      = session('openid');
                $add['user_id']     = session('user_id');
                $user_birth_gift->data($add)->add();
                //echo $user_birth_gift->getLastSql();die();
            }
        }
        $this->redirect('/user/userinfo');
    }

    /**
     * @title 获取用户积分
     * @author lizhi
     * @create on 2015-03-08
     */
    public function get_user_score()
    {
        $vip_score = M("user_score");
        $data = $vip_score->field('score')->where('uid = '.session('user_id'))->find();
        return isset($data['score'])?$data['score']:0;
    }

    //获取用户生日礼包
    public function get_user_birth_gift()
    {
        $user_birth_gift = M("user_birth_gift");
        $data = $user_birth_gift->where('user_id ="'.session('user_id').'"')->find();
        return $data;
    }

    /**
     * @title 获取用户会员等级
     */
    public function vip_level()
    {
        $user_sum = M("user_sum");
        $data = $user_sum->where('user_id = '.session('user_id'))->join(" join cms_vip_define on cms_user_sum.vip = cms_vip_define.id")->find();
        //echo $user_sum->getLastSql();
        return $data;
    }

    /**
     * 获取用户会员余额
     */
    public function get_account()
    {
        $account_log = M('vip_card');
        $data = $account_log->field('card_money')->where('user_id = '.session('user_id'))->find();
        //cho $account_log->getLastSql();
        //print_r($data);
        return $data['card_money'] > 0 ? $data['card_money'] : 0;
    }

    /**
     * @return mixed
     * @title 获取会员的卡号
     */
    public function get_vip_card()
    {
        $vip_card = M("vip_card");
        $data = $vip_card->where('user_id = '.session('user_id'))->find();
        return $data['card_no'];
    }

    //生成会员卡号
    //生成会员卡号
    public function vip_card_no()
    {
        return date('ymdHis').generate_code(4);
    }

    //返回用户信息
    public function get_userinfo()
    {
        $user = M("user");
        $data = $user->where('user_id="'.session('user_id').'"')->find();
        return $data;
    }

    /**
     * @return mixed
     * @title 获取用户会员券数目
     */
    public function get_coupon()
    {
        $coupon = M("user_coupon");
        $num = $coupon->where('user_id ='.session('user_id'))->field('count(id) as num')->group('user_id')->find();
        return $num['num'] >0 ? $num['num']:0;
    }



}?>