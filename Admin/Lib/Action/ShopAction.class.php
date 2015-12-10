<?php

class ShopAction extends CommonAction
{
  public function index()
  {
      $shop=M('shop');
      import('@.ORG.Page');
      $arr=" 1 = 1 ";

      $shop_id = $_POST['shop_id'];
      if ($shop_id){
          $arr .= ' and cms_shop.shop_id ='.$shop_id;
      }

      $count = $shop->where($arr)->count();    //计算总数
      $p = new Page($count, 20);
      $shop_arr_all = $shop->limit($p->firstRow . ',' . $p->listRows)->where($arr)->select();

      $shop_arr = $shop->select();
      $this->assign('shop_arr', $shop_arr);
      //echo $shop->getLastSql();
      /* echo '<pre>';
       print_r($user_arr);*/
      $page = $p->show();
      $this->assign("page", $page);
      $this->assign('shop_arr_all', $shop_arr_all);
      $this->display();
  }

  //新增分店
  public function shop_add()
  {
      if($this->isPost()) 
	  { 
          $data['shop_code']  = $this->_post('shop_code');
          $data['shop_name']  = $this->_post('shop_name');
          $data['leader']     = $this->_post('leader');
          $data['region']     = $this->_post('region');
          $data['warehouse']  = $this->_post('warehouse');
          $data['mobile']     = $this->_post('mobile');
          $data['address']    = $this->_post('address');   
          $data['remark']     = $this->_post('remark');

          $shop = M('shop'); 
          $list = $shop->add($data);

          if($list !== false){
              $this->redirect("/shop/index");
          }else{
              $this->error("数据写入错误!","");
          }
          exit;
      }
      $this->display();
  }

  //分店信息修改
  public function shop_edit()
  {
    $shop = M('shop'); 
    if($this->isPost()){ 
          $data['shop_code']  = $this->_post('shop_code');
          $data['shop_name']  = $this->_post('shop_name');
          $data['leader']     = $this->_post('leader');
          $data['region']     = $this->_post('region');
          $data['warehouse']  = $this->_post('warehouse');
          $data['mobile']     = $this->_post('mobile');
          $data['address']    = $this->_post('address');   
          $data['remark']     = $this->_post('remark');

          $list = $shop->where('shop_id = '.$this->_post('shopid'))->save($data);
          /*echo $shop->getLastSql();
          die();*/

          if($list !== false){
              $this->redirect("/shop/index");
          }else{
              $this->error("数据写入错误!","");
          }
          exit;
      }
      $shopId = $this->_get('shopid');
      $shoplist = $shop->where('shop_id = '. $shopId)->find();
      $this->assign('arr', $shoplist);
      $this->display();
  }

  public function shop_delete()
  {
        $shopId = $this->_get('shopid');
        $shop = M('shop');
        $result = $shop->where('shop_id = '.$shopId)->delete();

        if ($result !== false)
        {
          $this->redirect("/shop/index");
        }
  }
}
?>