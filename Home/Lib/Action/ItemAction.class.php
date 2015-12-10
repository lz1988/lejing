<?php
/**
 * @author  
 * @uses 橡树平台首页文件
 * 
 */

// 本类由系统自动生成，仅供测试用途
class ItemAction extends PublicAction {

    public function shop_page()
    {
        $this->display();
    }
	/*
	 *  首页
	 */
     public function item_list(){
         if(session('openid')==''){
    		$code=$this->_get('code');
    		if(!empty($code)){
		    	$open_id=get_openid($code);
			}elseif(!empty($_GET['openid'])){
				session('openid',$_GET['openid']);
			}
    	}

         //堂食
    	$item=M('item');
        /* $item_where['shop_type'] = array("notlike", '%85%');
    	$item_where['status']=array('eq',1);
         $item_where['assemble_type'] = array('eq',1);
    	$item_list_tangshi=$item->where($item_where)->where($item_where)->limit(5)->order(' item_id desc ')->field('item_id,item_name,item_price,icon')->select();
         //echo $item->getLastSql();

    	$this->assign('item_list_tangshi',$item_list_tangshi);*/

         //外卖
        /* $item=M('item');
         $item_where['shop_type'] = array("like", '%85%');
         $item_where['status']=array('eq',1);
         $item_where['assemble_type'] = array('eq',1);
         $item_list_waimai=$item->where($item_where)->where($item_where)->limit(5)->order(' item_id desc ')->field('item_id,item_name,item_price,icon')->select();

         $this->assign('item_list_waimai',$item_list_waimai);*/

         $newtype = M("newstype");
         $result = $newtype->field('type_id,type_name')->where('pid = 78 and is_del = 0')->select();
         foreach($result as $k=>$v){
             $result[$k][type_id] = $item->where('item_type like "%'.$v['type_id'].'%"')->select();

         }
         //轮播图
         $lunbo = M("lunbo");
         $rs = $lunbo->select();
         $this->assign('rs', $rs);

         $this->assign('result',$result);

         $this->assign('title','新品推荐');

		$this->display();
    }

    
    public function detail(){
    	if(session('openid')==''){
    		$code=$this->_get('code');
    		if(!empty($code)){
		    	$open_id=get_openid($code);
			}elseif(!empty($_GET['openid'])){
				session('openid',$_GET['openid']);
			}
    	}
    	$item=M('item');
    	$item_id=intval($this->_get('item_id'));
    	$item_where['status']=array('eq',1);
    	$item_where['item_id']=array('eq',$item_id);
        $item_where['assemble_type'] = 1;
    	$item_list=$item->where($item_where)->find();//->field('item_name,item_price,icon,detail,color,model')
        //echo $item->getLastSql();
    	$this->assign('res',$item_list);
    	 
    	//$item_brother=M('item_brother');
        //$br_where['item_id']=array('eq',$item_id);
        //$brother_list=$item_brother->field('brother_item_id')->where($br_where)->select();
        //$brother_list=$item_brother->join('as b LEFT JOIN cms_item as i ON i.item_id=b.brother_item_id')->field('i.item_name,i.color,i.item_id')->where($br_where)->select();
        //$item_arr[]=$item_id;
        //$this->assign('brother_list',$brother_list);
        //foreach ($brother_list as $th){
        	//$item_arr[]=$th['brother_item_id'];
        //}
        //$item_str_id=implode(',',$item_arr);
       // $color_where['item_id']=array('in',"$item_str_id");
       /* $color_where['model']=array('eq',"$item_list[model]");
        $color_list=$item->where($color_where)->field('color,item_id')->order('item_id ASC')->select();
        $this->assign('color_list',$color_list);*/
        //$color_arr=explode(',',$item_list['color']);
        //$this->assign('color_arr',$color_arr);
        
    	
    	$item_images=M('item_images');
    	$img_list=$item_images->where('item_id='.$item_id)->field('img_path')->order('sort ASC')->select();
    	$this->assign('img_list',$img_list);
    	
    	/**新闻分类**/
    	$newstype=M("newstype");
    	$newstypeinfo = $newstype->where("is_del='0' and pid<8")->order('sort ASC')->select();  
    	$this->assign('newstypearr',$newstypeinfo);
    	
    	foreach($newstypeinfo as $list){
    		$pp_arr[$list[type_id]]=$list['type_name'];
    	}
    	$this->assign('pp_arr',$pp_arr);

        $this->assign('title',$item_list['item_name']);

        $this->display();
    }

    //加入购物车
    public function add_cart(){

        if(session('openid')=='')
        {
            $code=$this->_get('code');
            if(!empty($code)){
                $open_id=get_openid($code);
            }elseif(!empty($_GET['openid'])){
                session('openid',$_GET['openid']);
            }
        }

    	$item_id    = intval($this->_post('item_id'));
        $item_num   = intval($this->_post('number'));
    	//$item_num   = 1;
        $cart = M()->query("select sum(item_num) as num from cms_item_cart where item_id={$item_id} group by item_id");
        $cart_num = 0;
        if (!empty($cart[0]))
        {
            $cart_num = $cart[0]['num'];
        }

    	$item = M('item');
    	$item_where['status']=array('eq',1);
    	$item_where['item_id']=array('eq',$item_id);
    	$item_list=$item->where($item_where)->field('item_name,item_price,icon,inventory,ori_price,shop_type')->find();
       /* echo $item->getLastSql();
        die();*/
        //var_dump($item_list);die();
    	/*if($item_list['inventory'] < ($item_num + $cart_num))
        {
    		$this->ajaxReturn('','库存不足',-1);
    	}*/
    	/*if(session('sub.sub_id')<1){
    		$this->ajaxReturn('','请先预约',-2);
    	}*/
    	$item_cart=M('item_cart');
    	$cart_where['openid']=array('eq',session('openid'));
    	//$cart_where['color']=array('eq',"$color");
    	$cart_where['item_id']=array('eq',$item_id);
    	//$cart_where['sub_id']=session('sub.sub_id');
    	$cart_res=$item_cart->field('cart_id')->where($cart_where)->find();
       /* echo $item_cart->getLastSql();
        die();*/
    	if($cart_res)
        {
    		$res=$item_cart->where($cart_where)->setInc('item_num',$item_num);
    	}
        else
        {
    		$add_data['item_name']=$item_list['item_name'];
    		$add_data['item_price']=$item_list['item_price'];
    		$add_data['ori_price']=$item_list['ori_price'];
    		
    		$add_data['icon']=$item_list['icon'];
    		$add_data['item_num']=$item_num;
    		$add_data['openid']=session('openid');
    		$add_data['item_id']=$item_id;
    		$add_data['add_time']=time();
            $add_data['shop_id'] = $item_list['shop_type'];
    		$res=$item_cart->add($add_data);
           /* echo $item_cart->getLastSql();
            die();*/
    	}
    	if($res){
    		$this->ajaxReturn('','添加成功',1);
    	}else{
    		$this->ajaxReturn('','添加失败',-11);
    	}
    }

    //叠加产品数
    public function check_num()
    {
        $item_id    = intval($this->_post('item_id'));
        $item_num   = intval($this->_post('number'));

        $cart = M()->query("select sum(item_num) as num from cms_item_cart where item_id={$item_id} group by item_id");
        $cart_num = 0;
        if (!empty($cart[0]))
        {
            $cart_num = $cart[0]['num'];
        }

        $item = M('item');
        $item_where['status']=array('eq',1);
        $item_where['item_id']=array('eq',$item_id);
        $item_list=$item->where($item_where)->field('inventory')->find();
        //var_dump($item_list);die();
        if($item_list['inventory'] < ($item_num + $cart_num))
        {
            $this->ajaxReturn($item_num,'库存不足',-1);
        }
    }
    
    
    #收藏
    /*public function add_collection(){
    	$item_id=$this->_post('item_id');
        if($item_id<1){
        	$this->ajaxReturn('','非法请求,数据错误',-1);
        }
    	 
    	$item_collection=M('item_collection');
    	$collection_data['item_id']=$item_id;
    	$collection_data['openid']=session('openid');
    	$res=$item_collection->field('col_id')->where($collection_data)->find();
    	if($res){
    		$this->ajaxReturn('','你已经收藏过了',-3);
    	}
    	$collection_data['create_time']=time();
    	$add_res=$item_collection->add($collection_data);
    	if($add_res){
    		$this->ajaxReturn('','收藏成功',1);
    	}else{
    		$this->ajaxReturn('','收藏失败',-4);
    	}
    	exit;
    }*/
    
    public function cart(){
    	$item_cart=M('item_cart');
    	$cart_where['openid']=array('eq',session('openid'));
    	//$cart_where['sub_id']=session('sub.sub_id');
    	$cart_list=$item_cart->where($cart_where)->select();
        //echo $item_cart->getLastSql();
        //var_dump($cart_list);
    	$this->assign('cart_list',$cart_list);
		$this->display();
    }
    
   /* public function del_cart(){
    	$item_id=$this->_post('item_id');
        if($item_id<1){
        	$this->ajaxReturn('','非法请求,数据错误',-1);
        }
        $item_cart=M('item_cart');
    	$cart_where['openid']=array('eq',session('openid'));
    	$cart_where['item_id']=array('eq',$item_id);
    	$res=$item_cart->where($cart_where)->delete();
    	if($res){
    		$this->ajaxReturn('','成功',1);
    	}else{
    		$this->ajaxReturn('','删除失败',-4);
    	}
    }*/

    /**
     * @title 填写收货信息
     * @author lizhi
     * @create on 2015-03-06
     */
    public function consignee()
    {

        $this->get_city(0);
        //购物车列表
        $item_cart = M("item_cart");
        $where['openid'] = session('openid');
        $cart_data_list = $item_cart->where($where)->select();
        //echo $item_cart->getLastSql();
        $this->assign('cart_data_list',$cart_data_list);
        //var_dump($cart_data_list);
        $this->assign('title','购物车列表');

        $this->display('consignee');
    }

    /**
     * @title 修改购物车
     * @author lizhi
     * @create on 2015-03-10
     */
    public function modify_cart()
    {
        $item_cart = M("item_cart");
        $cart_id = $this->_post('cart_id');
        $num     = $this->_post('num');

        if ($num == 0){
            $data = $item_cart->where('cart_id ='.$cart_id)->delete();
            if ($data !== false)
            {
                echo "2";
                exit;
            }
            else
            {
                echo "1";
                exit;
            }
        }
        else
        {
            $where['item_num'] = $num;
            $res= $item_cart->where('cart_id ='.$cart_id)->save($where);
            if ($res !== false)
            {
                echo "0";
                exit;
            }else{
                echo "1";
                exit;
            }
        }
    }

    //快速下单
    public function buy_list()
    {

        if(session('openid')==''){
            $code=$this->_get('code');
            if(!empty($code)){
                $open_id=get_openid($code);
            }elseif(!empty($_GET['openid'])){
                session('openid',$_GET['openid']);
            }
        }

        //单品
        $item=M('item');
        $item_where['status']=array('eq',1);
        $item_where['assemble_type'] = array('eq',1);
        $item_list=$item->where($item_where)->limit(5)->field('item_id,item_name,item_price,icon')->select();
        //echo $item->getLastSql();
        $this->assign('item_list',$item_list);

        //组合
        $assemble = M("item");
        $data = $assemble->where('assemble_type = 2 and status = 1')->limit(5)->select();
        //echo $assemble->getLastSql();
        $this->assign('assemble_list',$data);
//        echo '<pre>';
        //var_dump($data);
        $this->display();
    }

    //按照分类显示产品列表
    public function shop_buy_list()
    {
        //print_r($this->_get('shop_id'));
        $shop_id = $this->_get('shop_id');
        //die();
        //精致甜点系列
        $newstype = M("newstype");
        $arr_type_id = $newstype->where("type_name = '精致甜点系列'")->field('type_id')->find();

        $item = M("item");
        $data_jingzhitiandian = $item->where('locate('.$arr_type_id['type_id'].',item_type) and locate('.$shop_id.',shop_type) and assemble_type = 1')->select();
        //echo $item->getLastSql();
        /*echo '<pre>';
        print_r($data_jingzhitiandian);*/

        //杯饮系列
        $newstype = M("newstype");
        $arr_type_id_beiyin = $newstype->where("type_name = '杯饮系列'")->field('type_id')->find();

        $item = M("item");
        $data_beiyin = $item->where('locate('.$arr_type_id_beiyin['type_id'].',item_type) and locate('.$shop_id.',shop_type) and assemble_type = 1')->select();

        //糯米饭系列
        $newstype = M("newstype");
        $arr_type_id_nuomi = $newstype->where("type_name = '糯米饭系列'")->field('type_id')->find();

        $item = M("item");
        $data_nuoni = $item->where('locate('.$arr_type_id_nuomi['type_id'].',item_type) and locate('.$shop_id.',shop_type) and assemble_type = 1')->select();
        //echo $item->getLastSql();

        //特色混搭系列
        $newstype = M("newstype");
        $arr_type_id_tese= $newstype->where("type_name = '特色混搭系列'")->field('type_id')->find();

        $item = M("item");
        $data_tese = $item->where('locate('.$arr_type_id_tese['type_id'].',item_type) and locate('.$shop_id.',shop_type) and assemble_type = 1')->select();

        //招牌系列
        $newstype = M("newstype");
        $arr_type_id_zhaopai = $newstype->where("type_name = '招牌系列'")->field('type_id')->find();

        $item = M("item");
        $data_zhaopai = $item->where('locate('.$arr_type_id_zhaopai['type_id'].',item_type) and locate('.$shop_id.',shop_type) and assemble_type = 1')->select();

        $this->assign('data_jingzhitiandian',$data_jingzhitiandian);
        $this->assign('data_beiyin',$data_beiyin);
        $this->assign('data_nuoni',$data_nuoni);
        $this->assign('data_tese',$data_tese);
        $this->assign('data_zhaopai',$data_zhaopai);
        $this->display();
    }

    //购买
    public function buy()
    {
        echo '<pre>';
        print_r($_POST);
        die();

        if(session('openid')=='')
        {
            $code=$this->_get('code');
            if(!empty($code))
            {
                $open_id=get_openid($code);
            }
            elseif(!empty($_GET['openid']))
            {
                session('openid',$_GET['openid']);
            }
        }

        $item_cart = M("item_cart");
        $item_id_once   = $_POST['item_id_once'];
        $num_once       = $_POST['num_once'];
        $size_once      = $_POST['size_once'];
//echo '<pre>';var_dump($_POST);DIE();
        $item = M("item");
        foreach($item_id_once as $val)
        {
            if ($num_once[$val] > 0)
            {
                $res = $item->where("item_id = ".$val)->find();

                $add['openid']      = session('openid');
                $add['item_price']  = $res['item_price'];
                $add['item_name']   = $res['item_name'];
                $add['ori_price']   = $res['ori_price'];
                $add['item_num']    = $num_once[$val];
                $add['icon']        = $res['icon'];
                $add['add_time']    = time();
                $add['item_size']   = $size_once[$val];
                $add['item_id']     = $val;
                $add['sub_id']      = 0;
                $item_cart->add($add);
            }
        }

        $this->redirect('/item/consignee');
        /*echo '<pre>';
        print_r($_POST);
        DIE();*/
    }

    /*
     * @title 分类购买
     * @author lizhi
     * @create on 2015-03-08
     */
    public function buy_all()
    {
        //获取店铺id
        $shop_id = $this->_post('shop_id');

        if(session('openid')=='')
        {
            $code=$this->_get('code');
            if(!empty($code)){
                $open_id=get_openid($code);
            }elseif(!empty($_GET['openid'])){
                session('openid',$_GET['openid']);
            }
        }

        //单个类别的id
        $item_id_1 = $_POST['item_id_1'];
        $item_id_2 = $_POST['item_id_2'];
        $item_id_3 = $_POST['item_id_3'];
        $item_id_4 = $_POST['item_id_4'];
        $item_id_5 = $_POST['item_id_5'];

        //单个类别的数量
        $num_1 = $_POST['num_1'];
        $num_2 = $_POST['num_2'];
        $num_3 = $_POST['num_3'];
        $num_4 = $_POST['num_4'];
        $num_5 = $_POST['num_5'];

        //类别2的冷热状态
        $state = $_POST['state'];

        //加入购物车
        $item = M("item");
        $item_cart = M("item_cart");

        //类别1
        foreach($item_id_1 as $val)
        {
            if ($num_1[$val] > 0)
            {
                $res = $item->where("item_id = ".$val)->find();

                $add['openid']      = session('openid');
                $add['item_price']  = $res['item_price'];
                $add['item_name']   = $res['item_name'];
                $add['ori_price']   = $res['ori_price'];
                $add['item_num']    = $num_1[$val];
                $add['icon']        = $res['icon'];
                $add['add_time']    = time();
                $add['item_id']     = $val;
                $add['sub_id']      = 0;
                $add['shop_id']     = $shop_id;
                $item_cart->add($add);
                //echo $item_cart->getLastSql();DIE();
            }
        }

        //类别2
        foreach($item_id_2 as $val)
        {
            if ($num_2[$val] > 0)
            {
                $res = $item->where("item_id = ".$val)->find();

                $add['openid']      = session('openid');
                $add['item_price']  = $res['item_price'];
                $add['item_name']   = $res['item_name'];
                $add['ori_price']   = $res['ori_price'];
                $add['item_num']    = $num_2[$val];
                $add['icon']        = $res['icon'];
                $add['add_time']    = time();
                $add['item_id']     = $val;
                $add['sub_id']      = 0;
                $add['item_state']  = $state[$val];
                $add['shop_id']     = $shop_id;
                $item_cart->add($add);
                //echo $item_cart->getLastSql();die();
            }
        }

        //类别3
        foreach($item_id_3 as $val)
        {
            if ($num_3[$val] > 0)
            {
                $res = $item->where("item_id = ".$val)->find();

                $add['openid']      = session('openid');
                $add['item_price']  = $res['item_price'];
                $add['item_name']   = $res['item_name'];
                $add['ori_price']   = $res['ori_price'];
                $add['item_num']    = $num_3[$val];
                $add['icon']        = $res['icon'];
                $add['add_time']    = time();
                $add['item_id']     = $val;
                $add['sub_id']      = 0;
                $add['shop_id']     = $shop_id;
                $item_cart->add($add);
            }
        }

        //类别4
        foreach($item_id_4 as $val)
        {
            if ($num_4[$val] > 0)
            {
                $res = $item->where("item_id = ".$val)->find();

                $add['openid']      = session('openid');
                $add['item_price']  = $res['item_price'];
                $add['item_name']   = $res['item_name'];
                $add['ori_price']   = $res['ori_price'];
                $add['item_num']    = $num_4[$val];
                $add['icon']        = $res['icon'];
                $add['add_time']    = time();
                $add['item_id']     = $val;
                $add['sub_id']      = 0;
                $add['shop_id']     = $shop_id;
                $item_cart->add($add);
            }
        }

        //类别5
        foreach($item_id_5 as $val)
        {
            if ($num_5[$val] > 0)
            {
                $res = $item->where("item_id = ".$val)->find();

                $add['openid']      = session('openid');
                $add['item_price']  = $res['item_price'];
                $add['item_name']   = $res['item_name'];
                $add['ori_price']   = $res['ori_price'];
                $add['item_num']    = $num_5[$val];
                $add['icon']        = $res['icon'];
                $add['add_time']    = time();
                $add['item_id']     = $val;
                $add['sub_id']      = 0;
                $add['shop_id']     = $shop_id;
                $item_cart->add($add);
            }
        }
        $this->redirect('/item/consignee');
    }

    /**
     * mixi游戏
     */
    public function games()
    {
        C('LAYOUT_ON',false);
        $this->display();
    }

    public function share()
    {
        C('LAYOUT_ON',false);
        $this->display();
    }


}
?>
