<?php
/**
 * @title会员积分
 * @author lizhi
 */
class MemberscoreAction extends CommonAction{

    public function memberscore_list()
    {
        if(!empty($_GET['username']))
        {
            $where['username'] = array('like',$_GET['username']."%");
        }

        import('@.ORG.Page');
        $user = M("user");
        $count = $user->join(" join cms_vip_score on cms_user.openid = cms_vip_score.openid")->where($where)->count();    //计算总数
        $p = new Page($count, 20);
        $datalist = $user->join(" join cms_vip_score on cms_user.openid = cms_vip_score.openid")->where($where)->limit($p->firstRow . ',' . $p->listRows)->order('uid desc ')->select();
        //echo $user->getLastSql();
        $page = $p->show();

        $this->assign("page", $page);

        $this->assign('score_list',$datalist);
        $this->display('Member:memberscore_list');

    }

    /**
     * @title 删除会员积分
     */
    public function memberscore_delete()
    {
        $id = $this->_get('id');
        $vip_score = M("vip_score");
        $res = $vip_score->where('id = '.$id)->delete();
        if ($res !== false)
        {
            $this->redirect("__APP__/memberscore/memberscore_list");
        }
    }

}