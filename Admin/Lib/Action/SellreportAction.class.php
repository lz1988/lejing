<?php

/**
 * Class SellreportAction
 * @title 销售费用报表
 * @author zhi.li
 * @create on 2015-09-28
 */
class SellreportAction extends CommonAction
{
    public function sellreport_list()
    {
        import('@.ORG.Page');

        $incomestatement = M('incomestatement');
        $arr="";
        $count = $incomestatement->where($arr)->join("join cms_shop on cms_shop.shop_id=cms_incomestatement.shop_id")->count();
        $p = new Page($count, 20);
        $incomestatement_arr = $incomestatement->join("join cms_shop on cms_shop.shop_id=cms_incomestatement.shop_id")->limit($p->firstRow . ',' . $p->listRows)->order('cms_incomestatement.id desc')->where($arr)->select();
        //echo $incomestatement->getLastSql();
        /* echo '<pre>';
         print_r($user_arr);*/
        $page = $p->show();
        $this->assign("page", $page);
        $this->assign('incomestatement_arr', $incomestatement_arr);
        $this->display();
    }
}
?>