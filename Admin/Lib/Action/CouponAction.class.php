<?php
/**
 * @title 优惠券管理
 * @author lizhi
 * @create on 2015-03-10
 */
class CouponAction extends CommonAction
{
    public function coupon_list()
    {
        $coupon = M("coupon");
        $datalist = $coupon->select();

        $this->assign('list',$datalist);
        $this->display();
    }

    /**
     * 删除优惠券
     */
    public function coupon_delete()
    {
        $id = $this->_get('id');
        $coupon = M("coupon");
        $res = $coupon->where('coupon_id = '.$id)->delete();
        if ($res === false)
        {
            $this->error("删除失败！");
        }else{
            $this->success("操作成功！");
        }
    }

    /**
     * @title 优惠券新增
     * @author lizhi
     * @create on 2015-03-10
     */
    public function coupon_add()
    {
        if($this->isPost()){
            $coupon = M("coupon");
            $data['coupon_name'] = $this->_post('coupon_name');
            $data['coupon_money'] = $this->_post('coupon_money');
            $res = $coupon->add($data);
            if ($res !== false)
            {
                $this->success("操作成功");
            }else{
                $this->error("操作失败");
            }
        }
        else
        {
            $this->display();
        }
    }

    public function coupon_edit()
    {
        $id = $this->_get('id');
        $coupon = M("coupon");

        if($this->isPost())
        {
            $data['coupon_name'] = $this->_post('coupon_name');
            $data['coupon_money'] = $this->_post('coupon_money');
            $coupon->where('coupon_id = '.$id)->save($data);
            //echo $coupon->getLastSql();
            $this->success("操作成功");die();
        }


        $datalist = $coupon->where('coupon_id = '.$id)->find();
        //echo $coupon->getLastSql();
        $this->assign('list',$datalist);
        $this->display();
    }
}