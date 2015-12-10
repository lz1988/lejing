<?php
/**
 * @title 用户充值记录
 * @author zhi.li
 * @create on 2015-08-09
 */
class UserrechargeAction extends CommonAction
{
    public function index(){//会员列表
        $user=M('user');
        import('@.ORG.Page');
        $arr="";

        //用户名
        if (!empty($_GET['username']))
        {
            $arr['username'] = array("like", $_GET['username']."%");
        }

        $count = $user->join('join cms_user_recharge as r on r.uid=cms_user.user_id ')->where($arr)->count();    //计算总数
        $p = new Page($count, 20);
        $user_arr=$user->join('join cms_user_recharge as r on r.uid=cms_user.user_id ')->limit($p->firstRow . ',' . $p->listRows)->where($arr)->order('user_id DESC')->select();
        $page = $p->show();
        //echo $user->getLastSql();
        $this->assign("page", $page);
        $this->assign('user_list', $user_arr);
        $this->display();
    }
}